<?php

/** Backend */
$GLOBALS ['BE_MOD'] ['content'] ['company'] = array(
    'tables' => array(
        'tl_company_archive',
        'tl_company',
        'tl_company_category'
    ),
    'refresh_coordinates' => array(
        'Company\CompanyBackend',
        'refreshCoordinates'
    ),
    'exportCSV' => array(
        'Company\CompanyBackend',
        'exportCSV'
    ),
    'icon' => 'system/modules/unternehmen/assets/images/icon.png'
);

/** Frontend */
array_insert($GLOBALS ['FE_MOD'] ['companies'], 1, array(
    'company_list' => 'Company\CompanyList',
    'company_detail' => 'Company\CompanyDetail'
));

/** Hooks */
$GLOBALS ['TL_HOOKS'] ['getSearchablePages'] [] = array(
    'Company\CompanyBackend',
    'getSearchablePages'
);

/** Models */
$GLOBALS['TL_MODELS']['tl_company'] = 'Company\Model\CompanyModel';
$GLOBALS['TL_MODELS']['tl_company_archive'] = 'Company\Model\CompanyArchiveModel';
$GLOBALS['TL_MODELS']['tl_company_category'] = 'Company\Model\CompanyCategoryModel';
