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

class CompanyList extends \Module {
	
	/**
	 * Template
	 *
	 * @var string
	 */
	protected $strTemplate = 'mod_company_list';
	
	public function generate() {
		if (TL_MODE == 'BE') {
			$objTemplate = new \BackendTemplate ( 'be_wildcard' );
	
			$objTemplate->wildcard = '### UNTERNEHEMEN LISTE ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;
	
			return $objTemplate->parse ();
		}
	
		return parent::generate ();
	}

	protected function compile() {
		$intFilterCategory = \Input::get ( 'filterCategory' );
		$strFilterUrl = '';
		if ($intFilterCategory > 0) {
			$strFilterUrl = '&filterCategory=' . $intFilterCategory;
		}
		
		$strSearch = \Input::get ( 'search' );
		$strSearchUrl = 'search=%s';
		if ($strSearch != '') {
			$this->Template->strLink = $this->addToUrl ( sprintf ( $strSearchUrl, $strSearch ) . '&filterCategory=ID', TRUE );
		} else {
			$this->Template->strLink = $this->addToUrl ( 'filterCategory=ID', TRUE );
		}
		
		// Read jump to page details
		$objPage = \PageModel::findByIdOrAlias ( $this->jumpTo );
		
		$arrAlphabet = range ( 'A', 'Z' );
		$strHtml = '<a href="' . $this->addToUrl ( $strFilterUrl, TRUE ) . '">Alle</a> ';
		for($i = 0; $i < count ( $arrAlphabet ); $i ++) {
			$strHtml .= '<a href="' . $this->addToUrl ( sprintf ( $strSearchUrl, $arrAlphabet [$i] ) . $strFilterUrl, TRUE ) . '">' . $arrAlphabet [$i] . '</a> ';
		}
		$this->Template->strFilter = $strHtml;
		
		// Get Categories
		$this->loadLanguageFile ( 'tl_company_category' );
		$objCategories = $this->Database->prepare ( "SELECT * FROM tl_company_category WHERE pid=? ORDER BY title ASC" )->execute ( $this->company_archiv );
		$strOptions = '<option value="0">' . $GLOBALS ['TL_LANG'] ['tl_company_category'] ['category'] [0] . '</option>';
		if ($objCategories) {
		while ( $objCategories->next () ) {
			$arrCategory = $objCategories->row ();
			$strOptions .= '<option value="' . $objCategories->id . '"' . ($intFilterCategory != $objCategories->id ? '' : ' selected') . '>' . $objCategories->title . '</option>';
		}
		}
		$this->Template->strOptions = $strOptions;
		
		// Apply filter params to sql
		$strQueryWhere = "";
		if ($strSearch != '') {
			$strQueryWhere .= " AND company LIKE '" . $strSearch . "%' ";
		}
		if ($intFilterCategory > 0) {
			$strQueryWhere .= " AND category LIKE '%\"" . $intFilterCategory . "\"%'";
		}
		
		$objResult = $this->Database->prepare ( "SELECT COUNT(*) AS count FROM tl_company WHERE pid=?" . $strQueryWhere )->execute ( $this->company_archiv );
		$intTotal = $objResult->count;
		
		$offset = 0;
		$limit = null;
		$total = $intTotal - $offset;
		$arrPids [] = $this->company_archiv;
		
		// Split the results
		if ($this->perPage > 0 && (! isset ( $limit ) || $this->numberOfItems > $this->perPage)) {
			// Adjust the overall limit
			if (isset ( $limit )) {
				$total = min ( $limit, $total );
			}
			
			// Get the current page
			$page = \Input::get ( 'page' ) ?  : 1;
			
			// Do not index or cache the page if the page number is outside the range
			if ($page < 1 || $page > max ( ceil ( $total / $this->perPage ), 1 )) {
				global $objPage;
				$objPage->noSearch = 1;
				$objPage->cache = 0;
				
				// Send a 404 header
				header ( 'HTTP/1.1 404 Not Found' );
				return;
			}
			
			// Set limit and offset
			$limit = $this->perPage * 1;
			$offset += (max ( $page, 1 ) - 1) * $this->perPage;
			
			// Overall limit
			if ($offset + $limit > $total) {
				$limit = $total - $offset;
			}
			
			// Add the pagination menu
			$objPagination = new \Pagination ( $total, $this->perPage, $GLOBALS ['TL_CONFIG'] ['maxPaginationLinks'] );
			$this->Template->strPagination = $objPagination->generate ();
		}
		
		// Get the items
		if (isset ( $limit )) {
			$objResult = $this->Database->prepare ( "SELECT * FROM tl_company WHERE pid=? " . $strQueryWhere . " ORDER BY company ASC LIMIT ?, ?" )->execute ( $this->company_archiv, $offset, $limit );
		} else {
			$objResult = $this->Database->prepare ( "SELECT * FROM tl_company WHERE pid=? " . $strQueryWhere . " ORDER BY company ASC LIMIT ?, ?" )->execute ( $this->company_archiv, $offset, 0 );
		}
		
		$this->Template->strCompanies = $this->getCompanies ( $objResult, $objPage );
	}
	
	/**
	 * Return string/html of all companies
	 *
	 * @param array $arrCompanies
	 *        	DB query rows as array
	 * @return string
	 */
	protected function getCompanies($objCompanies, $objPage) {
		$strHTML = '';
		while ( $objCompanies->next () ) {
			$arrCompany = $objCompanies->row ();
			if ($arrCompany ['company'] != '') {
				$objTemplate = new \FrontendTemplate ( 'company_list' );
				$objTemplate->company = $arrCompany ['company'];
				$objTemplate->link = $this->generateFrontendUrl ( $objPage->row (), '/companyID/' . $arrCompany ['id'] );
				$strHTML .= $objTemplate->parse ();
			}
		}
		return $strHTML;
	}
}

?>