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
		// Read jump to page details
		$objPage = \PageModel::findByIdOrAlias ( $this->jumpTo );
		
		// Check if filter should be displayed
		if (!$this->company_filter_disabled) {
			$objTemplateFilter = new \FrontendTemplate('company_list_filter');
			
			// Filter category
			$intFilterCategory = \Input::get ( 'filterCategory' );
			$strFilterUrl = '';
			if ($intFilterCategory > 0) {
				$strFilterUrl = '&filterCategory=' . $intFilterCategory;
			}
			
			// Filter search
			$strSearch = \Input::get ( 'search' );
			$strSearchUrl = 'search=%s';
			if ($strSearch != '') {
				$objTemplateFilter->strLink = \Environment::get('base') . $this->addToUrl ( sprintf ( $strSearchUrl, $strSearch ) . '&filterCategory=ID', TRUE );
			} else {
				$objTemplateFilter->strLink = \Environment::get('base') . $this->addToUrl ( 'filterCategory=ID', TRUE );
			}
			
			// Generate letters
			$arrAlphabet = range ( 'A', 'Z' );
			$strHtml = '<a href="' . $this->addToUrl ( $strFilterUrl, TRUE ) . '">Alle</a> ';
			for($i = 0; $i < count ( $arrAlphabet ); $i ++) {
				$strHtml .= '<a href="' . $this->addToUrl ( sprintf ( $strSearchUrl, $arrAlphabet [$i] ) . $strFilterUrl, TRUE ) . '">' . $arrAlphabet [$i] . '</a> ';
			}
			$objTemplateFilter->strFilterName = $strHtml;
			
			// Get Categories
			$this->loadLanguageFile ( 'tl_company_category' );
			$objCategories = \CompanyCategoryModel::findBy ( 'pid', $this->company_archiv, array (
					'order' => 'title ASC'
			) );
			$strOptions = '<option value="0">' . $GLOBALS ['TL_LANG'] ['tl_company_category'] ['category'] [0] . '</option>';
			if ($objCategories) {
				while ( $objCategories->next () ) {
					$strOptions .= '<option value="' . $objCategories->id . '"' . ($intFilterCategory != $objCategories->id ? '' : ' selected') . '>' . $objCategories->title . '</option>';
				}
			}
			$objTemplateFilter->strCategoryOptions = $strOptions;
			$this->Template->strFilter = $objTemplateFilter->parse();
		} else {
			$strSearch = '';
			$intFilterCategory = 0;
		}
		
		// Get items to calculate total number of items
		$objCompanies = \CompanyModel::findItems ( $this->company_archiv, $strSearch, $intFilterCategory );
		
		// Pagination
		$intLimit = 0;
		$intOffset = 0;
		$intTotal = 0;
		
		// Set limit to maximum number of items
		if ($this->numberOfItems > 0) {
			$intLimit = $this->numberOfItems;
			$intTotal = $this->numberOfItems;
		} elseif ($objCompanies) {
			$intTotal = $objCompanies->count ();
		}
		
		// If per page is set and maximum number of items greater than per page use Pagination
		if ($objCompanies && $this->perPage > 0 && ($intLimit == 0 || $this->numberOfItems > $this->perPage)) {
			
			// Set limit, page and offset
			$intLimit = $this->perPage;
			$intPage = $this->Input->get ( 'page' ) ? $this->Input->get ( 'page' ) : 1;
			$intOffset = ($intPage - 1) * $intLimit;
			
			// Add pagination menu
			$objPagination = new \Pagination ( $intTotal, $intLimit );
			$this->Template->strPagination = $objPagination->generate ();
		}
		
		// Order
		$objCompanyArchive = \CompanyArchiveModel::findByPk($this->company_archiv);
		
		switch ($objCompanyArchive->sort_order) {
		    case 2:
		        $strOrder = 'sorting ASC';
		        break;
		    case 1:
		    default:
		        $strOrder = $this->company_random ? 'RAND()' : 'company ASC';
		        break;
		    }
		
				
		$objCompanies = \CompanyModel::findItems ( $this->company_archiv, $strSearch, $intFilterCategory, $intOffset, $intLimit, $strOrder );
		
		if ($objCompanies) {
			$this->Template->strCompanies = $this->getCompanies ( $objCompanies, $objPage );
		} else {
			$this->Template->strCompanies = 'Mit den ausgewählten Filterkriterien sind keine Einträge vorhanden.';
		}
	}
	
	/**
	 * Return string/html of all companies
	 *
	 * @param array $arrCompanies
	 *        	DB query rows as array
	 * @return string
	 */
	protected function getCompanies($objCompanies, $objPage = NULL) {
		$strHTML = '';
		while ( $objCompanies->next () ) {
			if ($objCompanies->company != '') {
				$objTemplate = new \FrontendTemplate ( 'company_list' );
				$objFile = \FilesModel::findByPk ( $objCompanies->logo );
				$arrSize = deserialize ( $this->imgSize );
				$objCompanies->logo = \Image::get ( $objFile->path, $arrSize [0], $arrSize [1], $arrSize [2] );
				$objTemplate->objCompany = $objCompanies;
				if ($objPage) {
					$objTemplate->link = $this->generateFrontendUrl ( $objPage->row (), '/companyID/' . $objCompanies->id );
				}
				$strHTML .= $objTemplate->parse ();
			}
		}
		return $strHTML;
	}
}

?>