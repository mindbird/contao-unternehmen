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

	public static function findByPids($arrPids, $intLimit = 0, $intOffset = 0, array $arrOptions = array()) {
		if (! is_array ( $arrPids ) || empty ( $arrPids )) {
			return null;
		}
		
		$t = static::$strTable;
		$arrColumns = array (
				"$t.pid IN(" . implode ( ',', array_map ( 'intval', $arrPids ) ) . ")" 
		);
		
		if (! isset ( $arrOptions ['order'] )) {
			$arrOptions ['order'] = "$t.company ASC";
		}
		
		$arrOptions ['limit'] = $intLimit;
		$arrOptions ['offset'] = $intOffset;
		
		return static::findBy ( $arrColumns, null, $arrOptions );
	}
}