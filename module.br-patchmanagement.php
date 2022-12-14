<?php

/**
 * @copyright   Copyright (C) 2021 Björn Rudner
 * @license     https://www.gnu.org/licenses/gpl-3.0.en.html
 * @version     2022-08-24
 *
 * iTop module definition file
 */

SetupWebPage::AddModule(
    __FILE__, // Path to the current file, all other file names are relative to the directory containing this file
    'br-patchmanagement/0.2.0',
    array(
        // Identification
        //
        'label' => 'Datamodel: Patch Management',
        'category' => 'business',

        // Setup
        //
        'dependencies' => array(
            'itop-config-mgmt/2.4.0',
            'itop-virtualization-mgmt/0.0.0'
        ),
        'mandatory' => false,
        'visible' => true,

        // Components
        //
        'datamodel' => array(
            'model.br-patchmanagement.php'
        ),
        'webservice' => array(),
        'data.struct' => array(
            // add your 'structure' definition XML files here,
        ),
        'data.sample' => array(
            // add your sample data XML files here,
        ),

        // Documentation
        //
        'doc.manual_setup' => '', // hyperlink to manual setup documentation, if any
        'doc.more_information' => '', // hyperlink to more information, if any

        // Default settings
        //
        'settings' => array(
            // Module specific settings go here, if any
        ),
    )
);

if (!class_exists('PatchManagementInstaller')) {
    /**
     * Class PatchManagementInstaller
     *
     * @since v0.2.0
     */
    class PatchManagementInstaller extends ModuleInstallerAPI
    {

        public static function BeforeWritingConfig(Config $oConfiguration)
        {
            // If you want to override/force some configuration values, do it here
            return $oConfiguration;
        }
        public static function AfterDatabaseCreation(Config $oConfiguration, $sPreviousVersion, $sCurrentVersion)
        {
            if (version_compare($sPreviousVersion, '0.2.0', '<')) {

                SetupLog::Info("|- Upgrading br-patchmanagement from '$sPreviousVersion' to '$sCurrentVersion'.");

                $aPMNames = array(
                    'Manually',
                    'Weekend',
                    'Delayed Weekend',
                    'Exception Defined',
                );
                foreach ($aPMNames as $sPMName) {
                    $oPM = MetaModel::NewObject('PatchMethod');
                    $oPM->Set('name', $sPMName);
                    $oPM->DBWrite();
                    SetupLog::Info("|  |- PatchMethod '$sPMName' created.");
                }
            }
        }
    }
}
