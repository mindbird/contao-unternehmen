<?php

use Contao\Config;

$GLOBALS['TL_DCA']['tl_company'] = [
    'config' => [
        'dataContainer' => 'Table',
        'ptable' => 'tl_company_archive',
        'switchToEdit' => true,
        'enableVersioning' => true,
        'ctable' => ['tl_company_postal', 'tl_content'],
        'sql' => [
            'keys' => [
                'id' => 'primary',
                'pid' => 'index',
                'sorting' => 'index',
                'alias' => 'index'
            ]
        ],
        'onload_callback' => [
            [
                Mindbird\Contao\Company\Tables\Company::class,
                'onloadCallback'
            ]
        ]
    ],
    'list' => [
        'sorting' => [
            'mode' => 1,
            'flag' => 1,
            'fields' => [
                'company'
            ],
            'headerFields' => [
                'title'
            ],
            'child_record_callback' => [
                Mindbird\Contao\Company\Tables\Company::class,
                'listCompany'
            ],
            'panelLayout' => 'sort,filter,search,limit'
        ],
        'label' => [
            'fields' => [
                'company'
            ],
            'format' => '%s',
            'label_callback' => [
                Mindbird\Contao\Company\Tables\Company::class,
                'generateLabel'
            ]
        ],
        'global_operations' => [
            'category' => [
                'label' => &$GLOBALS['TL_LANG']['tl_company']['category'],
                'href' => 'table=tl_company_category',
                'icon' => 'drag.gif'
            ],
            'refreshCoordinates' => [
                'label' => &$GLOBALS['TL_LANG']['tl_company']['refresh_coordinates'],
                'href' => 'key=refresh_coordinates',
                'icon' => 'bundles/contaocompany/images/arrow_refresh.png',
                'attributes' => 'onclick="Backend.getScrollOffset();"'
            ],
            'exportCSV' => [
                'label' => &$GLOBALS['TL_LANG']['tl_company']['exportCSV'],
                'href' => 'key=exportCSV',
                'icon' => 'bundles/contaocompany/images/building_go.png',
            ],
            'all' => [
                'label' => &$GLOBALS['TL_LANG']['MSC']['all'],
                'href' => 'act=select',
                'class' => 'header_edit_all',
                'attributes' => 'onclick="Backend.getScrollOffset();"'
            ]
        ],
        'operations' => [
            'edit' => array
            (
                'label' => &$GLOBALS['TL_LANG']['tl_company']['edit'],
                'href' => 'table=tl_content',
                'icon' => 'edit.gif'
            ),
            'editheader' => array
            (
                'label' => &$GLOBALS['TL_LANG']['tl_company']['editmeta'],
                'href' => 'act=edit',
                'icon' => 'header.gif'
            ),
            'postal' => [
                'label' => &$GLOBALS['TL_LANG']['tl_company']['postal'],
                'icon' => 'tablewizard.gif',
                'href' => 'table=tl_company_postal'
            ],
            'copy' => [
                'label' => &$GLOBALS['TL_LANG']['tl_company']['copy'],
                'href' => 'act=copy',
                'icon' => 'copy.gif'
            ],
            'delete' => [
                'label' => &$GLOBALS['TL_LANG']['tl_company']['delete'],
                'href' => 'act=delete',
                'icon' => 'delete.gif',
                'attributes' => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
            ],
            'toggle' =>
                [
                    'label' => &$GLOBALS['TL_LANG']['tl_company']['toggle'],
                    'icon' => 'visible.gif',
                    'attributes' => 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleVisibility(this,%s)"',
                    'button_callback' => [Mindbird\Contao\Company\Tables\Company::class, 'toggleIcon']
                ],
            'show' => [
                'label' => &$GLOBALS['TL_LANG']['tl_company']['show'],
                'href' => 'act=show',
                'icon' => 'show.gif'
            ]
        ]
    ],
    'palettes' => [
        'default' => '{company_legend},company,contact_person,alias;{category_legend},category;{address_legend},street,postal_code,city;{coordinates_legend},button_coordinates,lat,lng;{contact_legend},phone,fax,email,homepage;{logo_legend},logo;{gallery_legend},gallery_multiSRC;{information_legend},information;{publish_legend},published,start,stop;'
    ],
    'fields' => [
        'id' => [
            'sql' => "int(10) unsigned NOT NULL auto_increment"
        ],
        'pid' => [
            'sql' => "int(10) unsigned NOT NULL default '0'"
        ],
        'sorting' => [
            'sql' => "int(10) unsigned NOT NULL default '0'"
        ],
        'tstamp' => [
            'sql' => "int(10) unsigned NOT NULL default '0'"
        ],
        'alias' => array
        (
            'exclude'                 => true,
            'search'                  => true,
            'inputType'               => 'text',
            'eval'                    => array('rgxp'=>'folderalias', 'doNotCopy'=>true, 'maxlength'=>128, 'tl_class'=>'w50 clr'),
            'save_callback' => array
            (
                array(Mindbird\Contao\Company\Tables\Company::class, 'generateAlias')
            ),
            'sql'                     => "varchar(255) BINARY NOT NULL default ''"
        ),
        'company' => [
            'label' => &$GLOBALS['TL_LANG']['tl_company']['company'],
            'exclude' => true,
            'search' => true,
            'inputType' => 'text',
            'eval' => [
                'mandatory' => true,
                'tl_class' => 'w50',
                'maxlength' => 255
            ],
            'sql' => "varchar(255) NOT NULL default ''"
        ],
        'contact_person' => [
            'label' => &$GLOBALS['TL_LANG']['tl_company']['contact_person'],
            'exclude' => true,
            'search' => true,
            'inputType' => 'text',
            'eval' => [
                'maxlength' => 255,
                'tl_class' => 'w50',
            ],
            'sql' => "varchar(255) NOT NULL default ''"
        ],
        'street' => [
            'label' => &$GLOBALS['TL_LANG']['tl_company']['street'],
            'exclude' => true,
            'search' => true,
            'inputType' => 'text',
            'eval' => [
                'mandatory' => true,
                'tl_class' => 'w50',
                'maxlength' => 255
            ],
            'sql' => "varchar(255) NOT NULL default ''"
        ],
        'postal_code' => [
            'label' => &$GLOBALS['TL_LANG']['tl_company']['postal_code'],
            'exclude' => true,
            'search' => true,
            'inputType' => 'text',
            'eval' => [
                'mandatory' => true,
                'tl_class' => 'w50',
                'maxlength' => 5
            ],
            'sql' => "varchar(5) NOT NULL default ''"
        ],
        'city' => [
            'label' => &$GLOBALS['TL_LANG']['tl_company']['city'],
            'exclude' => true,
            'search' => true,
            'inputType' => 'text',
            'eval' => [
                'mandatory' => true,
                'tl_class' => 'w50',
                'maxlength' => 255
            ],
            'sql' => "varchar(255) NOT NULL default ''"
        ],
        'button_coordinates' => [
            'label' => &$GLOBALS['TL_LANG']['tl_company']['button_coordinates'],
            'exclude' => true,
            'inputType' => 'text',
            'input_field_callback' => [
                Mindbird\Contao\Company\Tables\Company::class,
                'buttonCoordinates'
            ],
            'eval' => []
        ],
        'lat' => [
            'label' => &$GLOBALS['TL_LANG']['tl_company']['lat'],
            'exclude' => true,
            'inputType' => 'text',
            'eval' => [
                'tl_class' => 'w50',
                'maxlength' => 32
            ],
            'sql' => "varchar(32) NOT NULL default ''"
        ],
        'lng' => [
            'label' => &$GLOBALS['TL_LANG']['tl_company']['lng'],
            'exclude' => true,
            'inputType' => 'text',
            'eval' => [
                'tl_class' => 'w50',
                'maxlength' => 32
            ],
            'sql' => "varchar(32) NOT NULL default ''"
        ],
        'phone' => [
            'label' => &$GLOBALS['TL_LANG']['tl_company']['phone'],
            'exclude' => true,
            'inputType' => 'text',
            'eval' => [
                'tl_class' => 'w50',
                'maxlength' => 255,
                'rgxp' => 'phone'
            ],
            'sql' => "varchar(255) NOT NULL default ''"
        ],
        'fax' => [
            'label' => &$GLOBALS['TL_LANG']['tl_company']['fax'],
            'exclude' => true,
            'inputType' => 'text',
            'eval' => [
                'tl_class' => 'w50',
                'maxlength' => 255,
                'rgxp' => 'phone'
            ],
            'sql' => "varchar(255) NOT NULL default ''"
        ],
        'email' => [
            'label' => &$GLOBALS['TL_LANG']['tl_company']['email'],
            'exclude' => true,
            'search' => true,
            'inputType' => 'text',
            'eval' => [
                'tl_class' => 'w50',
                'maxlength' => 255,
                'rgxp' => 'email'
            ],
            'sql' => "varchar(255) NOT NULL default ''"
        ],
        'homepage' => [
            'label' => &$GLOBALS['TL_LANG']['tl_company']['homepage'],
            'exclude' => true,
            'search' => true,
            'inputType' => 'text',
            'eval' => [
                'tl_class' => 'w50',
                'maxlength' => 255,
                'rgxp' => 'url'
            ],
            'sql' => "varchar(255) NOT NULL default ''"
        ],
        'logo' => [
            'label' => &$GLOBALS['TL_LANG']['tl_company']['logo'],
            'exclude' => true,
            'search' => false,
            'inputType' => 'fileTree',
            'eval' => [
                'filesOnly' => true,
                'fieldType' => 'radio',
                'tl_class' => 'clr',
                'extensions' => Config::get('validImageTypes')
            ],
            'sql' => "binary(16) NULL"
        ],
        'category' => [
            'label' => &$GLOBALS['TL_LANG']['tl_company']['category'],
            'exclude' => true,
            'inputType' => 'checkbox',
            'filter' => true,
            'foreignKey' => 'tl_company_category.title',
            'eval' => [
                'mandatory' => false,
                'multiple' => true
            ],
            'sql' => "blob NULL",
            'relation' => [
                'type' => 'hasMany',
                'load' => 'eagerly'
            ],
            'options_callback' => [
                Mindbird\Contao\Company\Tables\Company::class,
                'optionsCallbackCategory'
            ]
        ],
        'information' => [
            'label' => &$GLOBALS['TL_LANG']['tl_company']['information'],
            'exclude' => true,
            'inputType' => 'textarea',
            'eval' => [
                'rte' => 'tinyMCE'
            ],
            'sql' => "text NULL"
        ],
        'gallery_multiSRC' => [
            'label' => &$GLOBALS['TL_LANG']['tl_company']['gallery_multiSRC'],
            'exclude' => true,
            'inputType' => 'fileTree',
            'eval' => [
                'multiple' => true,
                'fieldType' => 'checkbox',
                'orderField' => 'gallery_orderSRC',
                'files' => true,
                'isGallery' => true,
                'extensions' => Config::get('validImageTypes')
            ],
            'sql' => "blob NULL"
        ],
        'gallery_orderSRC' => [
            'label' => &$GLOBALS['TL_LANG']['tl_company']['gallery_orderSRC'],
            'sql' => "blob NULL"
        ],
        'published' => [
            'label' => &$GLOBALS['TL_LANG']['tl_company']['published'],
            'flag' => 1,
            'inputType' => 'checkbox',
            'eval' => ['submitOnChange' => true, 'doNotCopy' => true],
            'sql' => "char(1) NOT NULL default ''"
        ],
        'start' => [
            'label' => &$GLOBALS['TL_LANG']['tl_company']['start'],
            'inputType' => 'text',
            'eval' => ['rgxp' => 'datim', 'datepicker' => true, 'tl_class' => 'w50 wizard'],
            'sql' => "varchar(10) NOT NULL default ''"
        ],
        'stop' => [
            'label' => &$GLOBALS['TL_LANG']['tl_company']['stop'],
            'inputType' => 'text',
            'eval' => ['rgxp' => 'datim', 'datepicker' => true, 'tl_class' => 'w50 wizard'],
            'sql' => "varchar(10) NOT NULL default ''"
        ]
    ]
];
