<?php

/**
 * @copyright   Copyright (C) 2021-2024 Björn Rudner
 * @license     https://www.gnu.org/licenses/gpl-3.0.en.html
 * @version     2024-11-05
 *
 * Localized data
 */

Dict::Add('DE DE', 'German', 'Deutsch', array(
    // Dictionary entries go here
    'Class:FunctionalCI/Attribute:patchmethod_id' => 'Patch Methode',
    'Class:FunctionalCI/Attribute:patchmethod_id+' => 'Methode, das System zu patchen',
    'Class:FunctionalCI/Attribute:patchgroup_id' => 'Patch Gruppe',
    'Class:FunctionalCI/Attribute:patchgroup_id+' => 'Patch Gruppe zu der das System gehört',
    'Class:FunctionalCI/Attribute:patchreboot_id' => 'Patch Neustart',
    'Class:FunctionalCI/Attribute:patchreboot_id+' => 'Zeitfenster für den Neustart des Systems',
    'Server:patchmanagement' => 'Patch Management',
));

//
// Class: PatchMethod
//
Dict::Add('DE DE', 'German', 'Deutsch', array(
    'Class:PatchMethod' => 'Patch Methode',
    'Class:PatchMethod+' => '',
    'Class:PatchMethod/Attribute:description' => 'Beschreibung',
    'Class:PatchMethod/Attribute:description+' => '',
    'Class:PatchMethod/Attribute:functionalci_list' => 'Funktionale CI(s)',
    'Class:PatchMethod/Attribute:functionalci_list+' => '',
));

//
// Class: PatchGroup
//
Dict::Add('DE DE', 'German', 'Deutsch', array(
    'Class:PatchGroup' => 'Patch Gruppe',
    'Class:PatchGroup+' => '',
    'Class:PatchGroup/Attribute:description' => 'Beschreibung',
    'Class:PatchGroup/Attribute:description+' => '',
    'Class:PatchGroup/Attribute:functionalci_list' => 'Funktionale CI(s)',
    'Class:PatchGroup/Attribute:functionalci_list+' => '',
));

//
// Class: PatchReboot
//
Dict::Add('DE DE', 'German', 'Deutsch', array(
    'Class:PatchReboot' => 'Patch Neustart',
    'Class:PatchReboot+' => '',
    'Class:PatchReboot/Attribute:description' => 'Beschreibung',
    'Class:PatchReboot/Attribute:description+' => '',
    'Class:PatchReboot/Attribute:functionalci_list' => 'Funktionale CI(s)',
    'Class:PatchReboot/Attribute:functionalci_list+' => '',
));
