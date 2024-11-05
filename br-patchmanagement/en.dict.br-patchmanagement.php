<?php

/**
 * @copyright   Copyright (C) 2021-2024 Björn Rudner
 * @license     https://www.gnu.org/licenses/gpl-3.0.en.html
 * @version     2024-11-05
 *
 * Localized data
 */

Dict::Add('EN US', 'English', 'English', array(
    // Dictionary entries go here
    'Class:FunctionalCI/Attribute:patchmethod_id' => 'Patch method',
    'Class:FunctionalCI/Attribute:patchmethod_id+' => 'Method to patch this system',
    'Class:FunctionalCI/Attribute:patchgroup_id' => 'Patch group',
    'Class:FunctionalCI/Attribute:patchgroup_id+' => 'Patch group this system belongs to',
    'Class:FunctionalCI/Attribute:patchreboot_id' => 'Patch reboot',
    'Class:FunctionalCI/Attribute:patchreboot_id+' => 'Reboot time period for this system',
    'Server:patchmanagement' => 'Patch Management',
));

//
// Class: PatchMethod
//
Dict::Add('EN US', 'English', 'English', array(
    'Class:PatchMethod' => 'Patch Method',
    'Class:PatchMethod+' => '',
    'Class:PatchMethod/Attribute:description' => 'Description',
    'Class:PatchMethod/Attribute:description+' => '',
    'Class:PatchMethod/Attribute:functionalci_list' => 'Functional CI(s)',
    'Class:PatchMethod/Attribute:functionalci_list+' => '',
));

//
// Class: PatchGroup
//
Dict::Add('EN US', 'English', 'English', array(
    'Class:PatchGroup' => 'Patch Group',
    'Class:PatchGroup+' => '',
    'Class:PatchGroup/Attribute:description' => 'Description',
    'Class:PatchGroup/Attribute:description+' => '',
    'Class:PatchGroup/Attribute:functionalci_list' => 'Functional CI(s)',
    'Class:PatchGroup/Attribute:functionalci_list+' => '',
));

//
// Class: PatchReboot
//
Dict::Add('EN US', 'English', 'English', array(
    'Class:PatchReboot' => 'Patch Reboot',
    'Class:PatchReboot+' => '',
    'Class:PatchReboot/Attribute:description' => 'Description',
    'Class:PatchReboot/Attribute:description+' => '',
    'Class:PatchReboot/Attribute:functionalci_list' => 'Functional CI(s)',
    'Class:PatchReboot/Attribute:functionalci_list+' => '',
));
