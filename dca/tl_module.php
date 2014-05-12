<?php
$GLOBALS ['TL_DCA'] ['tl_module'] ['palettes'] ['company_list'] = '{title_legend},name,headline,type;{archiv_legend},company_archiv,jumpTo,company_random,company_filter_disabled,numberOfItems,perPage;{protected_legend:hide},protected;
{expert_legend:hide},guests,cssID,space';


$GLOBALS ['TL_DCA'] ['tl_module'] ['fields'] ['numberOfItems'] ['eval'] ['mandatory'] = false;
$GLOBALS ['TL_DCA'] ['tl_module'] ['fields'] ['company_archiv'] = array (
		'label' => &$GLOBALS ['TL_LANG'] ['tl_module'] ['company_archiv'],
		'default' => '',
		'exclude' => true,
		'inputType' => 'select',
		'foreignKey' => 'tl_company_archive.title',
		
		'eval' => array (
				'mandatory' => true,
				'tl_class' => 'w50' 
		),
		'sql' => "int(10) unsigned NOT NULL default '0'" 
);

$GLOBALS ['TL_DCA'] ['tl_module'] ['fields'] ['company_random'] = array (
		'label' => &$GLOBALS ['TL_LANG'] ['tl_module'] ['company_random'],
		'exclude' => true,
		'inputType' => 'checkbox',
		'eval' => array (
				'tl_class' => 'w50 m12' 
		),
		'sql' => "char(1) NOT NULL default ''" 
);

$GLOBALS ['TL_DCA'] ['tl_module'] ['fields'] ['company_filter_disabled'] = array (
		'label' => &$GLOBALS ['TL_LANG'] ['tl_module'] ['company_filter_disabled'],
		'exclude' => true,
		'inputType' => 'checkbox',
		'eval' => array (
				'tl_class' => 'w50 m12'
		),
		'sql' => "char(1) NOT NULL default ''"
);

$GLOBALS ['TL_DCA'] ['tl_module'] ['palettes'] ['company_detail'] = '{title_legend},name,headline,type;{image_legend},imgSize;{protected_legend:hide},protected;
{expert_legend:hide},guests,cssID,space';

?>