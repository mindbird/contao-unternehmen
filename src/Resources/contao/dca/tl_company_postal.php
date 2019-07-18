<?php

$GLOBALS['TL_DCA']['tl_company_postal'] = [
    // Config
    'config' => [
        'dataContainer' => 'Table',
        'enableVersioning' => true,
        'switchToEdit' => true,
        'ptable' => 'tl_company',
        'sql' => [
            'keys' => [
                'id' => 'primary',
                'companyId' => 'index'
            ]
        ]
    ],

    // List
    'list' => [
        'sorting' => [
            'mode' => 4,
            'fields' => [
                'from'
            ],
            'flag' => 11,
            'panelLayout' => 'filter;search,limit'
        ],
        'label' => [
            'fields' => [
                'from, till'
            ],
            'format' => '%s - %s'
        ],
        'global_operations' => [
            'all' => [
                'label' => &$GLOBALS ['TL_LANG'] ['MSC'] ['all'],
                'href' => 'act=select',
                'class' => 'header_edit_all',
                'attributes' => 'onclick="Backend.getScrollOffset()" accesskey="e"'
            ]
        ],
        'operations' => [

            'edit' => [
                'label' => &$GLOBALS ['TL_LANG'] ['tl_company_category'] ['edit'],
                'href' => 'act=edit',
                'icon' => 'header.gif'
            ],
            'copy' => [
                'label' => &$GLOBALS ['TL_LANG'] ['tl_company_category'] ['copy'],
                'href' => 'act=copy',
                'icon' => 'copy.gif'
            ],
            'delete' => [
                'label' => &$GLOBALS ['TL_LANG'] ['tl_company_category'] ['delete'],
                'href' => 'act=delete',
                'icon' => 'delete.gif',
                'attributes' => 'onclick="if(!confirm(\'' . $GLOBALS ['TL_LANG'] ['MSC'] ['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
            ]
        ]
    ],

    // Palettes
    'palettes' => [
        'default' => '{title_legend},from,till'
    ],
    // Fields
    'fields' => [
        'id' => [
            'sql' => "int(10) unsigned NOT NULL auto_increment"
        ],
        'companyId' => [
            'sql' => "int(10) unsigned NOT NULL default '0'"
        ],
        'tstamp' => [
            'sql' => "int(10) unsigned NOT NULL default '0'"
        ],
        'from' => [
            'label' => &$GLOBALS ['TL_LANG'] ['tl_company_category'] ['from'],
            'exclude' => true,
            'search' => true,
            'inputType' => 'text',
            'eval' => [
                'mandatory' => true,
                'minlength' => 5,
                'maxlength' => 5,
                'rgxp' => 'natural'
            ],
            'sql' => "varchar(5) NOT NULL default ''"
        ],
        'till' => [
            'label' => &$GLOBALS ['TL_LANG'] ['tl_company_category'] ['till'],
            'exclude' => true,
            'search' => true,
            'inputType' => 'text',
            'eval' => [
                'mandatory' => true,
                'minlength' => 5,
                'maxlength' => 5,
                'rgxp' => 'natural'
            ],
            'sql' => "varchar(5) NOT NULL default ''"
        ]
    ]
];
