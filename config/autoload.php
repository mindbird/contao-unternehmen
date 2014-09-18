<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2014 Leo Feyer
 *
 * @package Unternehmen
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Register the namespaces
 */
ClassLoader::addNamespaces(array
(
	'Company',
));


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	// Models
	'Contao\CompanyModel'         => 'system/modules/unternehmen/models/CompanyModel.php',
	'Contao\CompanyCategoryModel' => 'system/modules/unternehmen/models/CompanyCategoryModel.php',
	'Contao\CompanyArchiveModel' => 'system/modules/unternehmen/models/CompanyArchiveModel.php',

	// Classes
	'Company\CompanyDetail'       => 'system/modules/unternehmen/classes/CompanyDetail.php',
	'Company\CompanyBackend'      => 'system/modules/unternehmen/classes/CompanyBackend.php',
	'Company\CompanyFrontend'     => 'system/modules/unternehmen/classes/CompanyFrontend.php',
	'Company\CompanyList'         => 'system/modules/unternehmen/classes/CompanyList.php',
));


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'mod_company_list'       => 'system/modules/unternehmen/templates',
	'mod_company_detail'     => 'system/modules/unternehmen/templates',
	'company_list'           => 'system/modules/unternehmen/templates',
	'company_list_filter'    => 'system/modules/unternehmen/templates',
	'be_refresh_coordinates' => 'system/modules/unternehmen/templates',
	'company_detail'         => 'system/modules/unternehmen/templates',
));
