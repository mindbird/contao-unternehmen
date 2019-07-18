<?php

namespace Mindbird\Contao\Company\Tables;

use Company\Models\CompanyArchiveModel;
use Company\Models\CompanyCategoryModel;
use Contao\Backend;
use Contao\BackendTemplate;
use Contao\Database;
use Contao\DataContainer;
use Contao\Image;
use Contao\Input;

class Company extends Backend
{

    public function generateLabel($row, $label)
    {
        $return = '';
        $file = \FilesModel::findByPk(deserialize($row['logo']));
        if ($file->path != '') {
            $return = '<figure style="float: left; margin-right: 1em;"><img src="' . Image::get($file->path, 80, 50, 'center_center') . '"></figure>';
        }

        $return .= '<div>' . $label . '</div>';

        return $return;
    }

    public function buttonCoordinates()
    {
        $template = new BackendTemplate('be_company_refresh_button');
        $template->googlemaps_apikey = $GLOBALS['TL_CONFIG']['company_googlemaps_apikey'];
        return $template->parse();
    }

    public function listCompany($row)
    {
        return '<div>' . $row['company'] . '</div>';
    }

    public function onloadCallback(DataContainer $objDC)
    {
        $companyArchive = CompanyArchiveModel::findByPk($objDC->id);

        switch ($companyArchive->sort_order) {
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

    public function toggleIcon($row, $href, $label, $title, $icon, $attributes)
    {
        if (strlen(Input::get('tid'))) {
            Database::getInstance()->prepare("UPDATE tl_company SET tstamp=" . time() . ", published='" . (Input::get('state') == 1 ? '1' : '') . "' WHERE id=?")->execute(Input::get('tid'));
            $this->redirect($this->getReferer());
        }

        $href .= '&amp;tid=' . $row['id'] . '&amp;state=' . ($row['published'] ? '' : 1);
        if (!$row['published']) {
            $icon = 'invisible.gif';
        }

        return '<a href="' . $this->addToUrl($href, false) . '" title="' . specialchars($title) . '"' . $attributes . '>' . Image::getHtml($icon,
                $label, 'data-state="' . ($row['published'] ? 1 : 0) . '"') . '</a> ';
    }
}
