<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (C) 2005-2013 Leo Feyer
 *
 * @package unternehmen
 * @author mindbird
 * @license GNU/LGPL
 * @copyright mindbird 2013
 */

/**
 * Table tl_company
 */
$GLOBALS ['TL_DCA'] ['tl_company'] = array (
		
		// Config
		'config' => array (
				'dataContainer' => 'Table',
				'ptable' => 'tl_company_archive',
				'switchToEdit' => true,
				'enableVersioning' => true,
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
						'flag' => 1,
						'fields' => array (
								'company' 
						),
						
						'headerFields' => array (
								'title' 
						),
						'child_record_callback' => array (
								'tl_company',
								'listCompany' 
						),
						'panelLayout' => 'sort,filter,search,limit' 
				),
				'label' => array (
						'fields' => array (
								'company' 
						),
						'format' => '%s',
						'label_callback' => array (
								'tl_company',
								'generateLabel' 
						) 
				),
				'global_operations' => array (
						'category' => array (
								'label' => &$GLOBALS ['TL_LANG'] ['tl_company'] ['category'],
								'href' => 'table=tl_company_category',
								'icon' => 'drag.gif' 
						),
						'refreshCoordinates' => array (
								'label' => &$GLOBALS ['TL_LANG'] ['tl_company'] ['refresh_coordinates'],
								'href' => 'key=refresh_coordinates',
								'icon' => 'system/modules/unternehmen/assets/images/arrow_refresh.png',
								'attributes' => 'onclick="Backend.getScrollOffset();"' 
						),
						'all' => array (
								'label' => &$GLOBALS ['TL_LANG'] ['MSC'] ['all'],
								'href' => 'act=select',
								'class' => 'header_edit_all',
								'attributes' => 'onclick="Backend.getScrollOffset();"' 
						),
						/*'import' => array (
								'label' => &$GLOBALS ['TL_LANG'] ['tl_company'] ['import'],
								'href' => 'key=import',
								'icon' => 'theme_import.gif',
								'attributes' => 'onclick="Backend.getScrollOffset();"' 
						) */
				),
				'operations' => array (
						'edit' => array (
								'label' => &$GLOBALS ['TL_LANG'] ['tl_company'] ['edit'],
								'href' => 'act=edit',
								'icon' => 'edit.gif' 
						),
						'copy' => array (
								'label' => &$GLOBALS ['TL_LANG'] ['tl_company'] ['copy'],
								'href' => 'act=copy',
								'icon' => 'copy.gif' 
						),
						'delete' => array (
								'label' => &$GLOBALS ['TL_LANG'] ['tl_company'] ['delete'],
								'href' => 'act=delete',
								'icon' => 'delete.gif',
								'attributes' => 'onclick="if(!confirm(\'' . $GLOBALS ['TL_LANG'] ['MSC'] ['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"' 
						),
						'show' => array (
								'label' => &$GLOBALS ['TL_LANG'] ['tl_company'] ['show'],
								'href' => 'act=show',
								'icon' => 'show.gif' 
						) 
				) 
		),
		
		// Palettes
		'palettes' => array (
				'default' => '{company_legend},company,contact_person;{category_legend},category; {address_legend}, street, postal_code, city; {coordinates_legend}, button_coordinates, lat, lng; {contact_legend}, phone, fax, email, homepage; {logo_legend}, logo; {information_legend}, information;' 
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
				'sorting' => array (
						'sql' => "int(10) unsigned NOT NULL default '0'" 
				),
				'company' => array (
						'label' => &$GLOBALS ['TL_LANG'] ['tl_company'] ['company'],
						'exclude' => true,
						'search' => true,
						'inputType' => 'text',
						'eval' => array (
								'mandatory' => true,
								'tl_class' => 'w50',
								'maxlength' => 255 
						),
						'sql' => "varchar(255) NOT NULL default ''" 
				),
				'contact_person' => array (
						'label' => &$GLOBALS ['TL_LANG'] ['tl_company'] ['contact_person'],
						'exclude' => true,
						'search' => true,
						'inputType' => 'text',
						'eval' => array (
								'maxlength' => 255
						),
						'sql' => "varchar(255) NOT NULL default ''"
				),
				'street' => array (
						'label' => &$GLOBALS ['TL_LANG'] ['tl_company'] ['street'],
						'exclude' => true,
						'search' => true,
						'inputType' => 'text',
						'eval' => array (
								'mandatory' => true,
								'tl_class' => 'w50',
								'maxlength' => 255 
						),
						
						'sql' => "varchar(255) NOT NULL default ''" 
				),
				'postal_code' => array (
						'label' => &$GLOBALS ['TL_LANG'] ['tl_company'] ['postal_code'],
						'exclude' => true,
						'search' => true,
						'inputType' => 'text',
						'eval' => array (
								'mandatory' => true,
								'tl_class' => 'w50',
								'maxlength' => 5 
						),
						
						'sql' => "varchar(5) NOT NULL default ''" 
				),
				'city' => array (
						'label' => &$GLOBALS ['TL_LANG'] ['tl_company'] ['city'],
						'exclude' => true,
						'search' => true,
						'inputType' => 'text',
						'eval' => array (
								'mandatory' => true,
								'tl_class' => 'w50',
								'maxlength' => 255 
						),
						'sql' => "varchar(255) NOT NULL default ''" 
				),
				'button_coordinates' => array (
						'label' => &$GLOBALS ['TL_LANG'] ['tl_company'] ['button_coordinates'],
						'exclude' => true,
						'inputType' => 'text',
						'input_field_callback' => array (
								'tl_company',
								'buttonCoordinates' 
						),
						'eval' => array () 
				),
				'lat' => array (
						'label' => &$GLOBALS ['TL_LANG'] ['tl_company'] ['lat'],
						'exclude' => true,
						'inputType' => 'text',
						'eval' => array (
								'tl_class' => 'w50',
								'maxlength' => 32 
						),
						
						'sql' => "varchar(32) NOT NULL default ''" 
				),
				'lng' => array (
						'label' => &$GLOBALS ['TL_LANG'] ['tl_company'] ['lng'],
						'exclude' => true,
						'inputType' => 'text',
						'eval' => array (
								'tl_class' => 'w50',
								'maxlength' => 32 
						),
						
						'sql' => "varchar(32) NOT NULL default ''" 
				),
				'phone' => array (
						'label' => &$GLOBALS ['TL_LANG'] ['tl_company'] ['phone'],
						'exclude' => true,
						'inputType' => 'text',
						'eval' => array (
								'tl_class' => 'w50',
								'maxlength' => 255,
								'rgxp' => 'phone' 
						),
						'sql' => "varchar(255) NOT NULL default ''" 
				),
				'fax' => array (
						'label' => &$GLOBALS ['TL_LANG'] ['tl_company'] ['fax'],
						'exclude' => true,
						'inputType' => 'text',
						'eval' => array (
								'tl_class' => 'w50',
								'maxlength' => 255,
								'rgxp' => 'phone' 
						),
						'sql' => "varchar(255) NOT NULL default ''" 
				),
				'email' => array (
						'label' => &$GLOBALS ['TL_LANG'] ['tl_company'] ['email'],
						'exclude' => true,
						'search' => true,
						'inputType' => 'text',
						'eval' => array (
								'tl_class' => 'w50',
								'maxlength' => 255,
								'rgxp' => 'email' 
						),
						'sql' => "varchar(255) NOT NULL default ''" 
				),
				'homepage' => array (
						'label' => &$GLOBALS ['TL_LANG'] ['tl_company'] ['homepage'],
						'exclude' => true,
						'search' => true,
						'inputType' => 'text',
						'eval' => array (
								'tl_class' => 'w50',
								'maxlength' => 255,
								'rgxp' => 'url' 
						),
						'sql' => "varchar(255) NOT NULL default ''" 
				),
				'logo' => array (
						'label' => &$GLOBALS ['TL_LANG'] ['tl_company'] ['logo'],
						'exclude' => true,
						'search' => false,
						'inputType' => 'fileTree',
						'eval' => array (
								'filesOnly' => true,
								'fieldType' => 'radio',
								'tl_class' => 'clr',
								'extensions' => 'jpg, jpeg, png, gif' 
						),
						'sql' => "binary(16) NULL" 
				),
				'category' => array (
						'label' => &$GLOBALS ['TL_LANG'] ['tl_company'] ['category'],
						'exclude' => true,
						'inputType' => 'checkbox',
						'filter' => true,
						'foreignKey' => 'tl_company_category.title',
						'eval' => array (
								'mandatory' => true,
								'multiple' => true 
						),
						'sql' => "blob NULL",
						'relation' => array('type'=>'hasMany', 'load' => 'eagerly')
				),
				'information' => array (
						'label' => &$GLOBALS ['TL_LANG'] ['tl_company'] ['information'],
						'exclude' => true,
						'inputType' => 'textarea',
						'eval' => array (
								'rte' => 'tinyMCE',
						),
						'sql' => "text NULL"
				)
		) 
);
class tl_company extends Backend {
	public function generateLabel($row, $label) {
		$objFile = \FilesModel::findByPk ( deserialize ( $row ['logo'] ) );
		if ($objFile->path != '') {
			$sReturn = '<figure style="float: left; margin-right: 1em;"><img src="' . Image::get ( $objFile->path, 80, 50, 'center_center' ) . '"></figure>';
		}
		
		$sReturn .= '<div>' . $label . '</div>';
		return $sReturn;
	}
	public function buttonCoordinates(DataContainer $dc) {
		$strHTML = '<script src="http://maps.google.com/maps/api/js?sensor=false"></script>
				<script>
				$("generateCoordinates").addEvent("click", function (){
					var geocoder = new google.maps.Geocoder();
					var address = $("ctrl_street").get("value") + ", " + $("ctrl_postal_code").get("value") + " " + $("ctrl_city").get("value");
					if (geocoder) {
      					geocoder.geocode({ "address": address }, function (results, status) {
         					if (status == google.maps.GeocoderStatus.OK) {
								$("ctrl_lat").set("value", results[0].geometry.location.lat());
								$("ctrl_lng").set("value", results[0].geometry.location.lng());
         					} else {
            					alert("Fehler beim generieren der Koordinaten. Bitte überprüfen Sie Straße, Postleitzahl und Ort.");
         					}
      					});
   					}
				});
				</script>';
		return '<div style="padding-top: 15px;"><a class="tl_submit" id="generateCoordinates">Koordinaten generieren</a></div>' . $strHTML;
	}
}

?>