<?php

/**
 * @copyright   Copyright (C) 2021-2024 Björn Rudner
 * @license     https://www.gnu.org/licenses/gpl-3.0.en.html
 * @version     2024-09-11
 *
 * Localized data
 */

Dict::Add('DE DE', 'German', 'Deutsch', array(
    // Dictionary entries go here
    'Class:FunctionalCI/Attribute:patchmethod_id' => 'Patch Methode',
    'Class:FunctionalCI/Attribute:patchmethod_id+' => 'Methode, das System zu patchen',
    'Class:FunctionalCI/Attribute:patchgroup_id' => 'Patch Gruppe',
    'Class:FunctionalCI/Attribute:patchgroup_id+' => 'Patch Gruppe zu der das System gehört',
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
