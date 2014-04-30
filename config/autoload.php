<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2013 Leo Feyer
 *
 * @package Person
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
	// Classes
	'Company\CompanyList'			=> 'system/modules/unternehmen/classes/CompanyList.php',
	'Company\CompanyDetail'			=> 'system/modules/unternehmen/classes/CompanyDetail.php',
	'Company\CompanyBackend'		=> 'system/modules/unternehmen/classes/CompanyBackend.php',
	'Company\CompanyFrontend'		=> 'system/modules/unternehmen/classes/CompanyFrontend.php',
	// Models
	'Company\CompanyModel'        	=> 'system/modules/unternehmen/models/CompanyModel.php',
	'Company\CompanyCategoryModel'	=> 'system/modules/unternehmen/models/CompanyCategoryModel.php',
));

/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'company_list'				=> 'system/modules/unternehmen/templates',
	'company_detail'			=> 'system/modules/unternehmen/templates',
	'mod_company_list'			=> 'system/modules/unternehmen/templates',
	'mod_company_detail'		=> 'system/modules/unternehmen/templates',
	'be_refresh_coordinates'	=> 'system/modules/unternehmen/templates',
));
