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
namespace Company;

class CompanyModel extends \Model {
		
	/**
	 * Table name
	 *
	 * @var string
	 */
	protected static $strTable = 'tl_company';
	
	/**
	 * Find items by their parent ID
	 *
	 * @param array $arrPids
	 *        	An array of archive IDs
	 * @param integer $intLimit
	 *        	An optional limit
	 * @param integer $intOffset
	 *        	An optional offset
	 * @param array $arrOptions
	 *        	An optional options array
	 *        	
	 * @return \Model\Collection null collection of models or null if there are no items
	 */
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