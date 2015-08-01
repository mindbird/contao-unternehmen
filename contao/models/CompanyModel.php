<?php

/**
 * Contao Open Source CMS
 * 
 * @package   Unternehmen
 * @author    mindbird 
 * @license   GNU/LGPL 
 * @copyright mindbird 2014 
 */

/**
 * Namespace
 */
namespace Contao;

class CompanyModel extends \Model {
	protected static $strTable = 'tl_company';
	public static function findItems($intPid, $strCompanyName = '', $intCategory = 0, $intOffset = 0, $intLimit = 0, $strOrder = 'company ASC') {
		$arrOptions = array ();
		$arrOptions ['column'] [] = 'pid = ?';
		$arrOptions ['value'] [] = $intPid;
		
		if ($strCompanyName != '') {
			$arrOptions ['column'] [] = 'company LIKE ?';
			$arrOptions ['value'] [] = $strCompanyName . '%';
		}
		
		if ($intCategory > 0) {
			$arrOptions ['column'] [] = 'category LIKE ?';
			$arrOptions ['value'] [] = '%"' . $intCategory . '"%';
		}
		
		if ($intOffset > 0) {
			$arrOptions ['offset'] = $intOffset;
		}
		
		if ($intLimit > 0) {
			$arrOptions ['limit'] = $intLimit;
		}
		
		$arrOptions ['order'] = $strOrder;
		
		return static::find ( $arrOptions );
	}
}