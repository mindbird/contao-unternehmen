<?php

/**
 * Contao Open Source CMS
 * 
 * @package   Unternehmen
 * @author    mindbird 
 * @license   GNU/LGPL 
 * @copyright mindbird 2014
 */
namespace Company;

class CompanyBackend extends \Backend {
	public function csvImport() {
		$objTemplate = new \BackendTemplate ( 'be_widget' );
		$objTemplate->parse ();
		$u = new \FileUpload ();
		$f = new \FileTree ();
		return $objTemplate->parse ();
	}
	public function refreshCoordinates() {
		$objTemplate = new \BackendTemplate ( 'be_refresh_coordinates' );
		$objTemplate->intArchiveID = \Input::get ( 'id' );
		$strHtml = '';
		$objCompanies = \CompanyModel::findBy ( array ('lng=?',	'lat=?'), array ('','') );
		if ($objCompanies) {
			while ( $objCompanies->next () ) {
				$arrCompany = $objCompanies->row ();
				if ($arrCompany ['street'] != '') {
					$strOptions = str_replace ( ' ', '+', urlencode ( $arrCompany ['street'] . ',' . $arrCompany ['plz'] . '+' . $arrCompany ['city'] ) );
					$objJson = json_decode ( file_get_contents ( 'https://maps.googleapis.com/maps/api/geocode/json?sensor=false&address=' . $strOptions ) );
					if ($objJson->status == 'OK') {
						$objCompanies->lat = $objJson->results [0]->geometry->location->lat;
						$objCompanies->lng = $objJson->results [0]->geometry->location->lng;
						$objCompanies->save ();
						$strHtml .= '<tr><td>' . $arrCompany ['company'] . '</td><td>OK</td></tr>';
					} else {
						$strHtml .= '<tr><td>' . $arrCompany ['company'] . '</td><td>Fehler</td></tr>';
					}
				}
			}
			$objTemplate->strHtml = $strHtml;
			return $objTemplate->parse ();
		}
	}
}
