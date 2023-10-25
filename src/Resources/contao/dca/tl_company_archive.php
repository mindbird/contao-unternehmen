<?php

$GLOBALS ['TL_DCA'] ['tl_company_archive'] = [

    // Config
    'config' => [
        'dataContainer' => 'Table',
        'enableVersioning' => true,
        'switchToEdit' => true,
        'ctable' => ['tl_company_category', 'tl_company'],
        'sql' => [
            'keys' => [
                'id' => 'primary'
            ]
        ]
    ],

    // List
    'list' => [
        'sorting' => [
            'mode' => 1,
            'fields' => [
                'title'
            ],
            'flag' => 1,
            'panelLayout' => 'filter;search,limit'
        ],
        'label' => [
            'fields' => [
                'title'
            ],
            'format' => '%s'
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
                'label' => &$GLOBALS ['TL_LANG'] ['tl_company_archive'] ['edit'],
                'href' => 'table=tl_company',
                'icon' => 'edit.gif'
            ],
            'editheader' => [
                'label' => &$GLOBALS ['TL_LANG'] ['tl_company_archive'] ['editheader'],
                'href' => 'act=edit',
                'icon' => 'header.gif'
            ],
            'copy' => [
                'label' => &$GLOBALS ['TL_LANG'] ['tl_company_archive'] ['copy'],
                'href' => 'act=copy',
                'icon' => 'copy.gif'
            ],
            'delete' => [
                'label' => &$GLOBALS ['TL_LANG'] ['tl_company_archive'] ['delete'],
                'href' => 'act=delete',
                'icon' => 'delete.gif',
                'attributes' => 'onclick="if(!confirm(\'' . $GLOBALS ['TL_LANG'] ['MSC'] ['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
            ],
            'show' => [
                'label' => &$GLOBALS ['TL_LANG'] ['tl_company_archive'] ['show'],
                'href' => 'act=show',
                'icon' => 'show.gif'
            ]
        ]
    ],

    // Palettes
    'palettes' => [
        'default' => '{title_legend},title,sort_order'
    ],
    // Fields
    'fields' => [
        'id' => [
            'sql' => "int(10) unsigned NOT NULL auto_increment"
        ],
        'tstamp' => [
            'sql' => "int(10) unsigned NOT NULL default '0'"
        ],
        'title' => [
            'label' => &$GLOBALS ['TL_LANG'] ['tl_company_archive'] ['title'],
            'exclude' => true,
            'search' => true,
            'inputType' => 'text',
            'eval' => [
                'mandatory' => true,
                'maxlength' => 255,
                'tl_class' => 'w50'
            ],
            'sql' => "varchar(255) NOT NULL default ''"
        ],
        'sort_order' => [
            'label' => &$GLOBALS ['TL_LANG'] ['tl_company_archive'] ['sort_order'],
            'exclude' => true,
            'search' => true,
            'inputType' => 'select',
            'eval' => [
                'mandatory' => true,
                'tl_class' => 'w50'
            ],
            'sql' => "int(1) NOT NULL default '0'",
            'options' => [1 => 'Sortierung anhand der Moduleinstellungen', 2 => 'Sortierung anhand des Archives']
        ]
    ]
];
