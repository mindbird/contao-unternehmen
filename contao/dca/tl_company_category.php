<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2013 Leo Feyer
 * 
 * @package   unternehmen 
 * @author    mindbird 
 * @license   GNU/LGPL 
 * @copyright mindbird 2013 
 */

/**
 * Table tl_company_category
 */
$GLOBALS ['TL_DCA'] ['tl_company_category'] = array (
		
		// Config
		'config' => array (
				'dataContainer' => 'Table',
				'enableVersioning' => true,
				'switchToEdit' => true,
				'ptable' => 'tl_company_archive',
				'sql' => array (
						'keys' => array (
								'id' => 'primary',
								'pid' => 'index'
						) 
				) 
		),
		
		// List
		'list' => array (
				'sorting' => array (
						'mode' => 1,
						'fields' => array (
								'title' 
						),
						'flag' => 1,
						'panelLayout' => 'filter;search,limit' 
				),
				'label' => array (
						'fields' => array (
								'title' 
						),
						'format' => '%s' 
				),
				'global_operations' => array (
						'all' => array (
								'label' => &$GLOBALS ['TL_LANG'] ['MSC'] ['all'],
								'href' => 'act=select',
								'class' => 'header_edit_all',
								'attributes' => 'onclick="Backend.getScrollOffset()" accesskey="e"' 
						) 
				),
				'operations' => array (
						
						'edit' => array (
								'label' => &$GLOBALS ['TL_LANG'] ['tl_company_category'] ['edit'],
								'href' => 'act=edit',
								'icon' => 'header.gif' 
						),
						'copy' => array (
								'label' => &$GLOBALS ['TL_LANG'] ['tl_company_category'] ['copy'],
								'href' => 'act=copy',
								'icon' => 'copy.gif'
						),
						'delete' => array (
								'label' => &$GLOBALS ['TL_LANG'] ['tl_company_category'] ['delete'],
								'href' => 'act=delete',
								'icon' => 'delete.gif',
								'attributes' => 'onclick="if(!confirm(\'' . $GLOBALS ['TL_LANG'] ['MSC'] ['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"' 
						)
				) 
		),
		
		// Palettes
		'palettes' => array (
				'default' => '{title_legend},title' 
		),
		// Fields
		'fields' => array (
				'id' => array (
						'sql' => "int(10) unsigned NOT NULL auto_increment" 
				),
				'pid' => array (
						'sql' => "int(10) unsigned NOT NULL default '0'" 
				),
				'tstamp' => array (
						'sql' => "int(10) unsigned NOT NULL default '0'" 
				),
				'title' => array (
						'label' => &$GLOBALS ['TL_LANG'] ['tl_company_category'] ['title'],
						'exclude' => true,
						'search' => true,
						'inputType' => 'text',
						'eval' => array (
								'mandatory' => true,
								'maxlength' => 255 
						),
						'sql' => "varchar(255) NOT NULL default ''" 
				) 
		) 
);

?>