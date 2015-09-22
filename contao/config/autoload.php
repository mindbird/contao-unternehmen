<?php

/**
 * Register the namespaces
 */
ClassLoader::addNamespaces(array
(
	'Company',
));

\Contao\ClassLoader::addClasses(array(
	'CompanyArchiveModel' => 'system/modules/unternehmen/models/CompanyArchiveModel.php',
	'CompanyCategoryModel' => 'system/modules/unternehmen/models/CompanyCategoryModel.php',
	'CompanyModel' => 'system/modules/unternehmen/models/CompanyArchiveModel.php'
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
