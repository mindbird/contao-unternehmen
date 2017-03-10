<?php

$GLOBALS['TL_DCA']['tl_company'] = array(
    'config' => array(
        'dataContainer' => 'Table',
        'ptable' => 'tl_company_archive',
        'switchToEdit' => true,
        'enableVersioning' => true,
        'sql' => array(
            'keys' => array(
                'id' => 'primary',
                'pid' => 'index',
                'sorting' => 'index'
            )
        ),
        'onload_callback' => array(
            array(
                'Company\Tables\Company',
                'onloadCallback'
            )
        )
    ),
    'list' => array(
        'sorting' => array(
            'mode' => 1,
            'flag' => 1,
            'fields' => array(
                'company'
            ),
            'headerFields' => array(
                'title'
            ),
            'child_record_callback' => array(
                'Company\Tables\Company',
                'listCompany'
            ),
            'panelLayout' => 'sort,filter,search,limit'
        ),
        'label' => array(
            'fields' => array(
                'company'
            ),
            'format' => '%s',
            'label_callback' => array(
                'Company\Tables\Company',
                'generateLabel'
            )
        ),
        'global_operations' => array(
            'category' => array(
                'label' => &$GLOBALS['TL_LANG']['tl_company']['category'],
                'href' => 'table=tl_company_category',
                'icon' => 'drag.gif'
            ),
            'refreshCoordinates' => array(
                'label' => &$GLOBALS['TL_LANG']['tl_company']['refresh_coordinates'],
                'href' => 'key=refresh_coordinates',
                'icon' => 'system/modules/unternehmen/assets/images/arrow_refresh.png',
                'attributes' => 'onclick="Backend.getScrollOffset();"'
            ),
            'exportCSV' => array(
                'label' => &$GLOBALS['TL_LANG']['tl_company']['exportCSV'],
                'href' => 'key=exportCSV',
                'icon' => 'system/modules/unternehmen/assets/images/building_go.png',
            ),
            'all' => array(
                'label' => &$GLOBALS['TL_LANG']['MSC']['all'],
                'href' => 'act=select',
                'class' => 'header_edit_all',
                'attributes' => 'onclick="Backend.getScrollOffset();"'
            )
        ),
        'operations' => array(
            'edit' => array(
                'label' => &$GLOBALS['TL_LANG']['tl_company']['edit'],
                'href' => 'act=edit',
                'icon' => 'edit.gif'
            ),
            'copy' => array(
                'label' => &$GLOBALS['TL_LANG']['tl_company']['copy'],
                'href' => 'act=copy',
                'icon' => 'copy.gif'
            ),
            'delete' => array(
                'label' => &$GLOBALS['TL_LANG']['tl_company']['delete'],
                'href' => 'act=delete',
                'icon' => 'delete.gif',
                'attributes' => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
            ),
            'toggle' => array
            (
                'label' => &$GLOBALS['TL_LANG']['tl_company']['toggle'],
                'icon' => 'visible.gif',
                'attributes' => 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleVisibility(this,%s)"',
                'button_callback' => array('Company\Tables\Company', 'toggleIcon')
            ),
            'show' => array(
                'label' => &$GLOBALS['TL_LANG']['tl_company']['show'],
                'href' => 'act=show',
                'icon' => 'show.gif'
            )
        )
    ),
    'palettes' => array(
        'default' => '{company_legend},company,contact_person;{category_legend},category;{address_legend},street,postal_code,city;{coordinates_legend},button_coordinates,lat,lng;{contact_legend},phone,fax,email,homepage;{logo_legend},logo;{gallery_legend},gallery_multiSRC;{information_legend},information;{publish_legend},published,start,stop;'
    ),
    'fields' => array(
        'id' => array(
            'sql' => "int(10) unsigned NOT NULL auto_increment"
        ),
        'pid' => array(
            'sql' => "int(10) unsigned NOT NULL default '0'"
        ),
        'sorting' => array(
            'sql' => "int(10) unsigned NOT NULL default '0'"
        ),
        'tstamp' => array(
            'sql' => "int(10) unsigned NOT NULL default '0'"
        ),
        'company' => array(
            'label' => &$GLOBALS['TL_LANG']['tl_company']['company'],
            'exclude' => true,
            'search' => true,
            'inputType' => 'text',
            'eval' => array(
                'mandatory' => true,
                'tl_class' => 'w50',
                'maxlength' => 255
            ),
            'sql' => "varchar(255) NOT NULL default ''"
        ),
        'contact_person' => array(
            'label' => &$GLOBALS['TL_LANG']['tl_company']['contact_person'],
            'exclude' => true,
            'search' => true,
            'inputType' => 'text',
            'eval' => array(
                'maxlength' => 255,
                'tl_class' => 'w50',
            ),
            'sql' => "varchar(255) NOT NULL default ''"
        ),
        'street' => array(
            'label' => &$GLOBALS['TL_LANG']['tl_company']['street'],
            'exclude' => true,
            'search' => true,
            'inputType' => 'text',
            'eval' => array(
                'mandatory' => true,
                'tl_class' => 'w50',
                'maxlength' => 255
            ),
            'sql' => "varchar(255) NOT NULL default ''"
        ),
        'postal_code' => array(
            'label' => &$GLOBALS['TL_LANG']['tl_company']['postal_code'],
            'exclude' => true,
            'search' => true,
            'inputType' => 'text',
            'eval' => array(
                'mandatory' => true,
                'tl_class' => 'w50',
                'maxlength' => 5
            ),
            'sql' => "varchar(5) NOT NULL default ''"
        ),
        'city' => array(
            'label' => &$GLOBALS['TL_LANG']['tl_company']['city'],
            'exclude' => true,
            'search' => true,
            'inputType' => 'text',
            'eval' => array(
                'mandatory' => true,
                'tl_class' => 'w50',
                'maxlength' => 255
            ),
            'sql' => "varchar(255) NOT NULL default ''"
        ),
        'button_coordinates' => array(
            'label' => &$GLOBALS['TL_LANG']['tl_company']['button_coordinates'],
            'exclude' => true,
            'inputType' => 'text',
            'input_field_callback' => array(
                'Company\Tables\Company',
                'buttonCoordinates'
            ),
            'eval' => array()
        ),
        'lat' => array(
            'label' => &$GLOBALS['TL_LANG']['tl_company']['lat'],
            'exclude' => true,
            'inputType' => 'text',
            'eval' => array(
                'tl_class' => 'w50',
                'maxlength' => 32
            ),
            'sql' => "varchar(32) NOT NULL default ''"
        ),
        'lng' => array(
            'label' => &$GLOBALS['TL_LANG']['tl_company']['lng'],
            'exclude' => true,
            'inputType' => 'text',
            'eval' => array(
                'tl_class' => 'w50',
                'maxlength' => 32
            ),
            'sql' => "varchar(32) NOT NULL default ''"
        ),
        'phone' => array(
            'label' => &$GLOBALS['TL_LANG']['tl_company']['phone'],
            'exclude' => true,
            'inputType' => 'text',
            'eval' => array(
                'tl_class' => 'w50',
                'maxlength' => 255,
                'rgxp' => 'phone'
            ),
            'sql' => "varchar(255) NOT NULL default ''"
        ),
        'fax' => array(
            'label' => &$GLOBALS['TL_LANG']['tl_company']['fax'],
            'exclude' => true,
            'inputType' => 'text',
            'eval' => array(
                'tl_class' => 'w50',
                'maxlength' => 255,
                'rgxp' => 'phone'
            ),
            'sql' => "varchar(255) NOT NULL default ''"
        ),
        'email' => array(
            'label' => &$GLOBALS['TL_LANG']['tl_company']['email'],
            'exclude' => true,
            'search' => true,
            'inputType' => 'text',
            'eval' => array(
                'tl_class' => 'w50',
                'maxlength' => 255,
                'rgxp' => 'email'
            ),
            'sql' => "varchar(255) NOT NULL default ''"
        ),
        'homepage' => array(
            'label' => &$GLOBALS['TL_LANG']['tl_company']['homepage'],
            'exclude' => true,
            'search' => true,
            'inputType' => 'text',
            'eval' => array(
                'tl_class' => 'w50',
                'maxlength' => 255,
                'rgxp' => 'url'
            ),
            'sql' => "varchar(255) NOT NULL default ''"
        ),
        'logo' => array(
            'label' => &$GLOBALS['TL_LANG']['tl_company']['logo'],
            'exclude' => true,
            'search' => false,
            'inputType' => 'fileTree',
            'eval' => array(
                'filesOnly' => true,
                'fieldType' => 'radio',
                'tl_class' => 'clr',
                'extensions' => 'jpg, jpeg, png, gif'
            ),
            'sql' => "binary(16) NULL"
        ),
        'category' => array(
            'label' => &$GLOBALS['TL_LANG']['tl_company']['category'],
            'exclude' => true,
            'inputType' => 'checkbox',
            'filter' => true,
            'foreignKey' => 'tl_company_category.title',
            'eval' => array(
                'mandatory' => false,
                'multiple' => true
            ),
            'sql' => "blob NULL",
            'relation' => array(
                'type' => 'hasMany',
                'load' => 'eagerly'
            ),
            'options_callback' => array(
                'Company\Tables\Company',
                'optionsCallbackCategory'
            )
        ),
        'information' => array(
            'label' => &$GLOBALS['TL_LANG']['tl_company']['information'],
            'exclude' => true,
            'inputType' => 'textarea',
            'eval' => array(
                'rte' => 'tinyMCE'
            ),
            'sql' => "text NULL"
        ),
        'gallery_multiSRC' => array
        (
            'label' => &$GLOBALS['TL_LANG']['tl_company']['gallery_multiSRC'],
            'exclude' => true,
            'inputType' => 'fileTree',
            'eval' => array(
                'multiple' => true,
                'fieldType' => 'checkbox',
                'orderField' => 'gallery_orderSRC',
                'files' => true,
                'isGallery' => true,
                'extensions' => Config::get('validImageTypes')
            ),
            'sql' => "blob NULL"
        ),
        'gallery_orderSRC' => array
        (
            'label' => &$GLOBALS['TL_LANG']['tl_company']['gallery_orderSRC'],
            'sql' => "blob NULL"
        ),
        'published' => array
        (
            'label' => &$GLOBALS['TL_LANG']['tl_company']['published'],
            'flag' => 1,
            'inputType' => 'checkbox',
            'eval' => array('submitOnChange' => true, 'doNotCopy' => true),
            'sql' => "char(1) NOT NULL default ''"
        ),
        'start' => array
        (
            'label' => &$GLOBALS['TL_LANG']['tl_company']['start'],
            'inputType' => 'text',
            'eval' => array('rgxp' => 'datim', 'datepicker' => true, 'tl_class' => 'w50 wizard'),
            'sql' => "varchar(10) NOT NULL default ''"
        ),
        'stop' => array
        (
            'label' => &$GLOBALS['TL_LANG']['tl_company']['stop'],
            'inputType' => 'text',
            'eval' => array('rgxp' => 'datim', 'datepicker' => true, 'tl_class' => 'w50 wizard'),
            'sql' => "varchar(10) NOT NULL default ''"
        )
    )
);