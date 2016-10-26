<?php

namespace Company;

use Company\Models\CompanyModel;
use Contao\BackendTemplate;
use Contao\FilesModel;
use Contao\FrontendTemplate;
use Contao\Input;
use Contao\Module;

class CompanyDetail extends Module {
	
	/**
	 * Template
	 *
	 * @var string
	 */
	protected $strTemplate = 'mod_company_detail';
	
	public function generate() {
		if (TL_MODE == 'BE') {
			$objTemplate = new BackendTemplate ( 'be_wildcard' );
				
			$objTemplate->wildcard = '### UNTERNEHEMEN DETAILS ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;
				
			return $objTemplate->parse ();
		}
	
		return parent::generate ();
	}
	
	protected function compile() {
        $db = \Database::getInstance();
		$intID = Input::get ( 'companyID' );
		$objCompany = CompanyModel::findByPk ( $intID );
		if ($objCompany) {
			global $objPage;
			$objPage->pageTitle = $objCompany->company;
			
			$objTemplate = new FrontendTemplate ( 'company_detail' );
			$objFile = FilesModel::findByPk ( $objCompany->logo );
			$arrSize = deserialize ( $this->imgSize );
			
			// Get Categories
            $strCategory = '';
			$arrCategories = deserialize ( $objCompany->category );
			if (count ( $arrCategories ) > 0) {
                $arrCategory = array();
				$objCompanyCategories = $db->prepare ( "SELECT * FROM tl_company_category WHERE id IN(" . implode ( ',', $arrCategories ) . ")" )->execute (  );
				while ( $objCompanyCategories->next () ) {
					$arrCategory [] = $objCompanyCategories->title;
				}
				$strCategory = implode ( ', ', $arrCategory );
			}
			$objTemplate->company = $objCompany->company;
			$objTemplate->contact_person = $objCompany->contact_person;
			$objTemplate->category = $strCategory;
			$objTemplate->street = $objCompany->street;
			$objTemplate->postal_code = $objCompany->postal_code;
			$objTemplate->city = $objCompany->city;
			$objTemplate->phone = $objCompany->phone;
			$objTemplate->fax = $objCompany->fax;
			$objTemplate->email = $objCompany->email;
			$objTemplate->homepage = $objCompany->homepage;
			$objTemplate->lat = $objCompany->lat;
			$objTemplate->lng = $objCompany->lng;
			$objTemplate->logo = \Image::get ( $objFile->path, $arrSize [0], $arrSize [1], $arrSize [2] );
			$objTemplate->imageWidth = $arrSize [0];
			$objTemplate->imageHeight = $arrSize [1];
			$objTemplate->information = $objCompany->information;
			
			$this->Template->strHtml = $objTemplate->parse ();
		}
	}
}
