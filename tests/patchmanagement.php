<?php

declare(strict_types=1);

// Small in-memory substitutes for the iTop APIs used by the real installer.
class Config {}
class ModuleInstallerAPI {}
class SetupWebPage
{
    public static array $module;
    public static function AddModule($file, $id, $options): void
    {
        self::$module = [$id, $options];
    }
}
class SetupLog
{
    public static function Info($message): void {}
}
class DBSearch
{
    public static function FromOQL($query): string
    {
        if (!preg_match('/^SELECT (PatchMethod|PatchGroup|PatchReboot) WHERE name = :name$/D', $query, $match)) {
            throw new RuntimeException('Unexpected installer query: ' . $query);
        }
        return $match[1];
    }
}
class DBObjectSet
{
    public function __construct(private string $class, array $order, private array $parameters) {}
    public function Count(): int
    {
        return isset(MetaModel::$records[$this->class][$this->parameters['name']]) ? 1 : 0;
    }
}
class MetaModel
{
    public static array $records = [];
    public static int $inserts = 0;
    public static function NewObject($class, $attributes): object
    {
        return new class($class, $attributes['name']) {
            public function __construct(private string $class, private string $name) {}
            public function DBInsert(): void
            {
                if (isset(MetaModel::$records[$this->class][$this->name])) {
                    throw new RuntimeException('Duplicate default record.');
                }
                MetaModel::$records[$this->class][$this->name] = true;
                MetaModel::$inserts++;
            }
        };
    }
}
class Dict
{
    public static array $entries = [];
    public static function Add($language, $label, $native, $entries): void
    {
        self::$entries[$language] = $entries;
    }
}
require __DIR__ . '/../br-patchmanagement/module.br-patchmanagement.php';
foreach (glob(__DIR__ . '/../br-patchmanagement/dictionaries/*.php') as $dictionary) {
    require $dictionary;
}
$model = simplexml_load_file(__DIR__ . '/../br-patchmanagement/datamodel.br-patchmanagement.xml', SimpleXMLElement::class, LIBXML_NONET);
if ($model === false) {
    throw new RuntimeException('Cannot load patch management model.');
}
$assert = static function (bool $condition, string $message): void {
    if (!$condition) {
        throw new RuntimeException($message);
    }
};
$defaults = [
    'PatchMethod' => ['Manually', 'Weekend', 'Delayed Weekend', 'Exception Defined'],
    'PatchGroup' => ['Manually', 'Server Monday 02:00', 'Server Daily 23:00', 'Server Daily 01:00', 'Client Daily 10:00'],
    'PatchReboot' => ['Everytime', 'Daily 06:00-08:00', 'Daily 09:00-17:00', 'Daily 19:00-05:00', 'Monday 02:00'],
];
return [
    'module registration matches manifest and required iTop modules' => static function () use ($assert): void {
        $manifest = simplexml_load_file(__DIR__ . '/../extension.xml');
        [$id, $options] = SetupWebPage::$module;
        $assert($id === 'br-patchmanagement/' . trim((string) $manifest->version), 'Module version mismatch.');
        $assert($options['installer'] === PatchManagementInstaller::class && !$options['mandatory'] && $options['visible'], 'Unexpected module setup.');
        $assert($options['dependencies'] === ['itop-config-mgmt/3.2.0', 'itop-datacenter-mgmt/3.2.0', 'itop-virtualization-mgmt/3.2.0'], 'Unexpected dependencies.');
        $config = new Config();
        $assert(PatchManagementInstaller::BeforeWritingConfig($config) === $config, 'Configuration was replaced.');
    },
    'fresh installation creates defaults once and preserves existing records' => static function () use ($assert, $defaults): void {
        MetaModel::$records = ['PatchMethod' => ['Manually' => true, 'Custom' => true]];
        MetaModel::$inserts = 0;
        PatchManagementInstaller::AfterDatabaseCreation(new Config(), '', '3.2.0');
        foreach ($defaults as $class => $names) {
            foreach ($names as $name) {
                $assert(isset(MetaModel::$records[$class][$name]), "Missing $class: $name");
            }
        }
        $assert(MetaModel::$inserts === 13 && isset(MetaModel::$records['PatchMethod']['Custom']), 'Existing defaults or custom records changed.');
        PatchManagementInstaller::AfterDatabaseCreation(new Config(), '', '3.2.0');
        $assert(MetaModel::$inserts === 13, 'Repeated installation inserted duplicates.');
    },
    'installer respects both migration thresholds' => static function () use ($assert): void {
        foreach (['0.2.0' => 14, '0.2.1' => 10, '3.1.4' => 10, '3.1.5' => 0, '3.2.0' => 0] as $previous => $expected) {
            MetaModel::$records = [];
            MetaModel::$inserts = 0;
            PatchManagementInstaller::AfterDatabaseCreation(new Config(), $previous, '3.2.0');
            $assert(MetaModel::$inserts === $expected, 'Incorrect migration for ' . $previous);
        }
    },
    'patch fields and reverse links target the matching typologies' => static function () use ($assert, $model, $defaults): void {
        foreach (array_keys($defaults) as $class) {
            $field = strtolower($class) . '_id';
            $keys = $model->xpath("/itop_design/classes/class[@id='FunctionalCI']/fields/field[@id='$field']");
            $assert(count($keys) === 1 && (string) $keys[0]->target_class === $class
                && (string) $keys[0]->is_null_allowed === 'true'
                && (string) $keys[0]->on_target_delete === 'DEL_MANUAL', 'Broken external key: ' . $field);
            $types = $model->xpath("/itop_design/classes/class[@id='$class']");
            $assert(count($types) === 1 && (string) $types[0]->parent === 'Typology', 'Missing typology: ' . $class);
            $links = $types[0]->xpath("fields/field[@id='functionalci_list']");
            $assert(count($links) === 1 && (string) $links[0]->linked_class === 'FunctionalCI'
                && (string) $links[0]->ext_key_to_me === $field, 'Broken reverse link: ' . $class);
            foreach (['DE DE', 'EN US'] as $language) {
                foreach (["Class:$class", "Class:FunctionalCI/Attribute:$field"] as $key) {
                    $assert(!empty(Dict::$entries[$language][$key]), "Missing $language translation: $key");
                }
            }
        }
    },
    'servers and virtual machines expose all patch fields' => static function () use ($assert, $model): void {
        foreach (['Server', 'VirtualMachine'] as $class) {
            foreach (['patchmethod_id', 'patchgroup_id', 'patchreboot_id'] as $field) {
                foreach (['details', 'search'] as $view) {
                    $items = $model->xpath("/itop_design/classes/class[@id='$class']/presentation/$view//item[@id='$field']");
                    $assert(count($items) === 1, "Missing or repeated $class $view field: $field");
                }
            }
        }
    },
];
