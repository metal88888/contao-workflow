<?php

/**
 * This Contao-Workflow extension allows the definition of workflow process for entities from different providers. This
 * extension is a workflow framework which can be used from other extensions to provide their custom workflow handling.
 *
 * @package    workflow
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2014-2017 netzmacht David Molineus
 * @license    LGPL 3.0
 * @filesource
 */


\MetaPalettes::appendBefore('tl_user', 'custom', 'account', ['workflow' => ['workflow']]);
\MetaPalettes::appendBefore('tl_user', 'extend', 'account', ['workflow' => ['workflow']]);
\MetaPalettes::appendBefore('tl_user', 'admin', 'account', ['workflow' => ['workflow']]);


$GLOBALS['TL_DCA']['tl_user']['fields']['workflow'] = [
    'label'            => &$GLOBALS['TL_LANG']['tl_user']['workflow'],
    'inputType'        => 'checkbox',
    'options_callback' => ['netzmacht.contao_workflow.listeners.dca.permissions', 'getAllPermissions'],
    'save_callback'    => [
        ['netzmacht.contao_workflow.listeners.dca.save_permission_callback', 'onSaveCallback'],
    ],
    'eval'             => [
        'tl_class' => 'clr',
        'multiple' => true,
    ],
    'sql'              => 'mediumblob NULL',
];
