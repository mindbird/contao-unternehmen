<?php

$GLOBALS['TL_DCA']['tl_module']['fields']['numberOfItems']['eval']['mandatory'] = false;

$GLOBALS['TL_DCA']['tl_module']['fields']['company_archiv'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_module']['company_archiv'],
    'default' => '',
    'exclude' => true,
    'inputType' => 'select',
    'foreignKey' => 'tl_company_archive.title',
    'eval' => array(
        'mandatory' => true,
        'tl_class' => 'w50'
    ),
    'sql' => "int(10) unsigned NOT NULL default '0'"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['company_category'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_module']['company_categorie'],
    'default' => '',
    'exclude' => true,
    'inputType' => 'select',
    'options_callback' => [
        'Company\Tables\Module',
        'getCategoryOptions'
    ],
    'foreignKey' => 'tl_company_category.id',
    'eval' => array(
        'mandatory' => true,
        'tl_class' => 'w50'
    ),
    'sql' => "int(10) unsigned NOT NULL default '0'"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['company_random'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_module']['company_random'],
    'exclude' => true,
    'inputType' => 'checkbox',
    'eval' => array(
        'tl_class' => 'w50 m12'
    ),
    'sql' => "char(1) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['company_filter_disabled'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_module']['company_filter_disabled'],
    'exclude' => true,
    'inputType' => 'checkbox',
    'eval' => array(
        'tl_class' => 'w50 m12'
    ),
    'sql' => "char(1) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['companyTpl'] = array
(
    'label' => &$GLOBALS['TL_LANG']['tl_module']['companyTpl'],
    'exclude' => true,
    'inputType' => 'select',
    'options_callback' => array('Company\Tables\Module', 'getCompanyTemplates'),
    'eval' => array('includeBlankOption' => true, 'chosen' => true, 'tl_class' => 'w50'),
    'sql' => "varchar(64) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['gallery_perRow'] = array
(
    'label' => &$GLOBALS['TL_LANG']['tl_module']['gallery_perRow'],
    'default' => 4,
    'exclude' => true,
    'inputType' => 'select',
    'options' => array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12),
    'eval' => array('tl_class' => 'w50'),
    'sql' => "smallint(5) unsigned NOT NULL default '0'"
);
$GLOBALS['TL_DCA']['tl_module']['fields']['gallery_perPage'] = array
(
    'label' => &$GLOBALS['TL_LANG']['tl_module']['gallery_perPage'],
    'exclude' => true,
    'inputType' => 'text',
    'eval' => array('rgxp' => 'natural', 'tl_class' => 'w50'),
    'sql' => "smallint(5) unsigned NOT NULL default '0'"
);
$GLOBALS['TL_DCA']['tl_module']['fields']['gallery_numberOfItems'] = array
(
    'label' => &$GLOBALS['TL_LANG']['tl_module']['gallery_numberOfItems'],
    'exclude' => true,
    'inputType' => 'text',
    'eval' => array('rgxp' => 'natural', 'tl_class' => 'w50'),
    'sql' => "smallint(5) unsigned NOT NULL default '0'"
);
$GLOBALS['TL_DCA']['tl_module']['fields']['gallery_template'] = array
(
    'label' => &$GLOBALS['TL_LANG']['tl_module']['gallery_template'],
    'exclude' => true,
    'inputType' => 'select',
    'options_callback' => array('Company\Tables\Module', 'getGalleryTemplates'),
    'eval' => array('tl_class' => 'w50'),
    'sql' => "varchar(64) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_module']['fields']['gallery_fullsize'] = array
(
    'label' => &$GLOBALS['TL_LANG']['tl_module']['gallery_fullsize'],
    'exclude' => true,
    'inputType' => 'checkbox',
    'eval' => array('tl_class' => 'w50 m12'),
    'sql' => "char(1) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_module']['fields']['gallery_size'] = array
(
    'label' => &$GLOBALS['TL_LANG']['tl_module']['gallery_size'],
    'exclude' => true,
    'inputType' => 'imageSize',
    'options' => \Contao\System::getImageSizes(),
    'reference' => &$GLOBALS['TL_LANG']['MSC'],
    'eval' => array(
        'rgxp' => 'natural',
        'includeBlankOption' => true,
        'nospace' => true,
        'helpwizard' => true,
        'tl_class' => 'w50'
    ),
    'sql' => "varchar(64) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_module']['fields']['gallery_imagemargin'] = array
(
    'label' => &$GLOBALS['TL_LANG']['tl_module']['gallery_imagemargin'],
    'exclude' => true,
    'inputType' => 'trbl',
    'options' => $GLOBALS['TL_CSS_UNITS'],
    'eval' => array('includeBlankOption' => true, 'tl_class' => 'w50'),
    'sql' => "varchar(128) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['palettes']['company_list'] = '{title_legend},name,headline,type;{archiv_legend},company_archiv,company_category,jumpTo,company_random,company_filter_disabled,numberOfItems,perPage,imgSize,companyTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';
$GLOBALS['TL_DCA']['tl_module']['palettes']['company_detail'] = '{title_legend},name,headline,type;{maps_legend},company_googlemaps_apikey;{image_legend},imgSize;{gallery_legend},gallery_size,gallery_imagemargin,gallery_perRow,gallery_perPage,gallery_numberOfItems,gallery_fullsize,gallery_template;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';
