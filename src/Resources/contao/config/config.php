<?php

/** Backend */
$GLOBALS ['BE_MOD'] ['content'] ['company'] = array(
    'tables' => array(
        'tl_company_archive',
        'tl_company',
        'tl_company_category',
        'tl_company_postal'
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
/*
@TODO change to event listener?
$GLOBALS ['TL_HOOKS'] ['getSearchablePages'] [] = array(
    'Company\CompanyBackend',
    'getSearchablePages'
);
*/

/** Models */
$GLOBALS['TL_MODELS']['tl_company'] = \Mindbird\Contao\Company\Models\CompanyModel::class;
$GLOBALS['TL_MODELS']['tl_company_archive'] = \Mindbird\Contao\Company\Models\CompanyArchiveModel::class;
$GLOBALS['TL_MODELS']['tl_company_category'] = \Mindbird\Contao\Company\Models\CompanyCategoryModel::class;
