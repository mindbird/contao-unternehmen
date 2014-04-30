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



class CompanyDetail extends \Module {
	
	/**
	 * Template
	 *
	 * @var string
	 */
	protected $strTemplate = 'mod_company_detail';
	
	/**
	 * Generate the module
	 */
	protected function compile() {
		$intID = \Input::get('id');
		$objResult = $this->Database->prepare ( "SELECT * FROM tl_company WHERE id=?")->execute ( $intID );

		$arrRows = $objResult->fetchAllAssoc ();
		$this->Template->strHtml = $this->getCompanies ( $arrRows );

	}
	
	/**
	 * Return string/html of all companies
	 *
	 * @param array $arrCompanies
	 *        	DB query rows as array
	 * @return string
	 */
	protected function getCompanies($arrCompanies) {
		$strHTML = '';
		foreach ( $arrCompanies as $arrCompany ) {
			if ($arrCompany ['company'] != '') {
				$objTemplate = new \FrontendTemplate ( 'company_detail' );
				$objFile = \FilesModel::findByPk ( $arrCompany ['logo'] );
				$arrSize = deserialize ( $this->imgSize );
				
				// Get Categories
				$arrCategories = deserialize($arrCompany ['category']);
				if (count($arrCategories) > 0) { 
				$objCompanyCategories = \CompanyCategoryModel::findBy(array('id IN(' . implode(',', $arrCategories) . ')'), null);
				while ($objCompanyCategories->next()) {
					$arrCategory[] = $objCompanyCategories->title;
				}
				$strCategory = implode(', ', $arrCategory);
				}
				$objTemplate->company = $arrCompany ['company'];
				$objTemplate->category = $strCategory;
				$objTemplate->street = $arrCompany ['street'];
				$objTemplate->postal_code = $arrCompany ['postal_code'];
				$objTemplate->city = $arrCompany ['city'];
				$objTemplate->phone = $arrCompany ['phone'];
				$objTemplate->email = $arrCompany ['email'];
				$objTemplate->homepage = $arrCompany ['homepage'];
				$objTemplate->lat = $arrCompany ['lat'];
				$objTemplate->lng = $arrCompany ['lng'];
				$objTemplate->logo = \Image::get ( $objFile->path, $arrSize [0], $arrSize [1], $arrSize [2] );
				$objTemplate->imageWidth = $arrSize [0];
				$objTemplate->imageHeight = $arrSize [1];
				
				$strHTML .= $objTemplate->parse ();
			}
		}
		
		return $strHTML;
	}
}
