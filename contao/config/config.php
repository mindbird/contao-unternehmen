<?php

/** Backend */
$GLOBALS ['BE_MOD'] ['content'] ['company'] = array(
    'tables' => array(
        'tl_company_archive',
        'tl_company',
        'tl_company_category',
        'tl_company_postal',
        'tl_content'
    ),
    'refresh_coordinates' => array(
        Mindbird\Contao\Company\Backend\Company::class,
        'refreshCoordinates'
    ),
    'exportCSV' => array(
        Mindbird\Contao\Company\Backend\Company::class,
        'exportCSV'
    ),
    'icon' => 'bundles/contaocompany/images/icon.png'
);

/** Models */
$GLOBALS['TL_MODELS']['tl_company'] = \Mindbird\Contao\Company\Models\CompanyModel::class;
$GLOBALS['TL_MODELS']['tl_company_archive'] = \Mindbird\Contao\Company\Models\CompanyArchiveModel::class;
$GLOBALS['TL_MODELS']['tl_company_category'] = \Mindbird\Contao\Company\Models\CompanyCategoryModel::class;
$GLOBALS['TL_MODELS']['tl_company_postal'] = \Mindbird\Contao\Company\Models\CompanyPostalModel::class;
