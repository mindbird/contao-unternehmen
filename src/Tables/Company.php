<?php

namespace Company\Tables;

use Company\Models\CompanyArchiveModel;
use Company\Models\CompanyCategoryModel;
use Contao\Backend;
use Contao\DataContainer;
use Contao\Image;

class Company extends Backend
{

    public function generateLabel($row, $label)
    {
        $sReturn = '';
        $objFile = \FilesModel::findByPk(deserialize($row['logo']));
        if ($objFile->path != '') {
            $sReturn = '<figure style="float: left; margin-right: 1em;"><img src="' . Image::get($objFile->path, 80, 50, 'center_center') . '"></figure>';
        }

        $sReturn .= '<div>' . $label . '</div>';

        return $sReturn;
    }

    public function buttonCoordinates()
    {
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

    public function listCompany($row)
    {
        return '<div>' . $row['company'] . '</div>';
    }

    public function onloadCallback(DataContainer $objDC)
    {
        $objCompanyArchive = CompanyArchiveModel::findByPk($objDC->id);

        switch ($objCompanyArchive->sort_order) {
            case 2:
                $GLOBALS['TL_DCA']['tl_company']['list']['sorting']['mode'] = 4;
                $GLOBALS['TL_DCA']['tl_company']['list']['sorting']['fields'] = array(
                    'sorting'
                );
                $GLOBALS['TL_DCA']['tl_company']['list']['sorting']['headerFields'] = array(
                    'title'
                );
                break;
            case 1:
            default:

                // Nothing to do
                break;
        }
    }

    public function optionsCallbackCategory($dc)
    {
        $categories = CompanyCategoryModel::findBy('pid', $dc->activeRecord->pid, array(
            'order' => 'title ASC'
        ));
        $category = array();
        if ($categories) {
            while ($categories->next()) {
                $category[$categories->id] = $categories->title;
            }
        }

        return $category;
    }
}