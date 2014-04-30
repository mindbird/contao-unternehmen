<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2014 Leo Feyer
 * 
 * @package   Unternehmen
 * @author    mindbird 
 * @license   GNU/LGPL 
 * @copyright mindbird 2014 
 */

/**
 * Namespace
 */
namespace Company;

class CompanyFrontend extends \Frontend {
	
	/**
	 * Hook um Seiten an den Index zu senden
	 *
	 * @param
	 *        	array
	 * @param
	 *        	integer
	 * @param
	 *        	boolean
	 * @return array
	 */
	public function getSearchablePages($arrPages, $intRoot = 0, $blnIsSitemap = false) {
		$arrRoot = array ();
		if ($intRoot > 0) {
			$arrRoot = $this->getChildRecords ( $intRoot, 'tl_page', true );
		}
		
		// Read jump to page details
		$objResult = $this->Database->prepare ( "SELECT jumpTo, company_archiv FROM tl_module WHERE type=?" )->execute ( 'company_list' );
		$arrModules = $objResult->fetchAllAssoc ();
		
		if (count ( $arrModules ) > 0) {
			foreach ( $arrModules as $arrModule ) {
				if (is_array ( $arrRoot ) && count ( $arrRoot ) > 0 && ! in_array ( $arrModule ['jumpTo'], $arrRoot )) {
					continue;
				}
				
				$objParent = \PageModel::findWithDetails ( $arrModule ['jumpTo'] );
				// The target page does not exist
				if ($objParent === null) {
					continue;
				}
				
				// The target page has not been published (see #5520)
				if (! $objParent->published) {
					continue;
				}
				
				// The target page is exempt from the sitemap (see #6418)
				if ($blnIsSitemap && $objParent->sitemap == 'map_never') {
					continue;
				}
				
				// Set the domain (see #6421)
				$domain = ($objParent->rootUseSSL ? 'https://' : 'http://') . ($objParent->domain ?  : \Environment::get ( 'host' )) . TL_PATH . '/';
				
				$arrPids [] = $arrModule ['company_archiv'];
				$objCompanies = \CompanyModel::findByPids ( $arrPids, 0,0, array('order' => 'id ASC') );
				while ( $objCompanies->next () ) {
					$arrCompany = $objCompanies->row ();
					$arrPages [] = $domain . $this->generateFrontendUrl ( $objParent->row (), '/id/' . $arrCompany ['id'], $objParent->language );
				}
			}
		}
		return $arrPages;
	}
}
