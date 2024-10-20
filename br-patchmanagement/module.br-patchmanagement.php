<?php

/**
 * @copyright   Copyright (C) 2021-2024 Björn Rudner
 * @license     https://www.gnu.org/licenses/gpl-3.0.en.html
 * @version     2024-09-11
 *
 * iTop module definition file
 */

SetupWebPage::AddModule(
    __FILE__, // Path to the current file, all other file names are relative to the directory containing this file
    'br-patchmanagement/3.1.4',
    array(
        // Identification
        //
        'label' => 'Datamodel: Patch Management',
        'category' => 'business',

        // Setup
        //
        'dependencies' => array(
            'itop-config-mgmt/3.1.0',
            'itop-datacenter-mgmt/3.1.0',
            'itop-virtualization-mgmt/3.1.0',
        ),
        'mandatory' => false,
        'visible' => true,
        'installer' => 'PatchManagementInstaller',

        // Components
        //
        'datamodel' => array(),
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
     * @since v0.2.1
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
            if (version_compare($sPreviousVersion, '0.2.1', '<')) {

                SetupLog::Info("|- Upgrading br-patchmanagement from '$sPreviousVersion' to '$sCurrentVersion'.");

                $aPMNames = array(
                    'Manually',
                    'Weekend',
                    'Delayed Weekend',
                    'Exception Defined',
                );
                foreach ($aPMNames as $sPMName) {
                    $oSearch = DBSearch::FromOQL('SELECT PatchMethod WHERE name = :name');
                    $oSet = new DBObjectSet($oSearch, array(), array('name' => $sPMName));
                    if ($oSet->Count() == 0) {
                        $oPM = MetaModel::NewObject('PatchMethod', array('name' => $sPMName));
                        $oPM->DBInsert();
                        SetupLog::Info("|  |- PatchMethod '$sPMName' created.");
                    }
                }
            }
        }
    }
}
