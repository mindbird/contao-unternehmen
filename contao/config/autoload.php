<?php

/**
 * Register the namespaces
 */
ClassLoader::addNamespaces(array
(
	'Company',
));

ClassLoader::addClasses(array(
	'Contao\CompanyArchiveModel' => 'system/modules/unternehmen/models/CompanyArchiveModel.php',
	'Contao\CompanyCategoryModel' => 'system/modules/unternehmen/models/CompanyCategoryModel.php',
	'Contao\CompanyModel' => 'system/modules/unternehmen/models/CompanyArchiveModel.php'
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
