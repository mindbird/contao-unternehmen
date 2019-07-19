<?php

namespace Mindbird\Contao\Company\Tables;

use Contao\Backend;
use Contao\BackendTemplate;
use Contao\Database;
use Contao\DataContainer;
use Contao\FilesModel;
use Contao\Image;
use Contao\Input;
use Contao\StringUtil;
use Mindbird\Contao\Company\Models\CompanyArchiveModel;
use Mindbird\Contao\Company\Models\CompanyCategoryModel;

class Company extends Backend
{

    public function generateLabel($row, $label): string
    {
        $return = '';
        $file = FilesModel::findByPk(deserialize($row['logo']));
        if ($file !== null && $file->path !== '') {
            $return = '<figure style="float: left; margin-right: 1em;"><img src="' . Image::get($file->path, 80, 50, 'center_center') . '"></figure>';
        }

        $return .= '<div>' . $label . '</div>';

        return $return;
    }

    public function buttonCoordinates(): string
    {
        $template = new BackendTemplate('be_company_refresh_button');
        $template->googlemaps_apikey = $GLOBALS['TL_CONFIG']['company_googlemaps_apikey'];
        return $template->parse();
    }

    public function listCompany($row): string
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

    public function optionsCallbackCategory($dc): array
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

    public function toggleIcon($row, $href, $label, $title, $icon, $attributes): string
    {
        if (Input::get('tid') != '') {
            Database::getInstance()->prepare("UPDATE tl_company SET tstamp=" . time() . ", published='" . (Input::get('state') == 1 ? '1' : '') . "' WHERE id=?")->execute(Input::get('tid'));
            self::redirect($this->getReferer());
        }

        $href .= '&amp;tid=' . $row['id'] . '&amp;state=' . ($row['published'] ? '' : 1);
        if (!$row['published']) {
            $icon = 'invisible.gif';
        }

        return '<a href="' . $this->addToUrl($href, false) . '" title="' . StringUtil::specialchars($title) . '"' . $attributes . '>' . Image::getHtml($icon,
                $label, 'data-state="' . ($row['published'] ? 1 : 0) . '"') . '</a> ';
    }

    public function postalIcon($row, $href, $label, $title, $icon, $attributes): string
    {
        $href .= '&amp;cid=' . $row['id'];

        return '<a href="' . $this->addToUrl($href, false) . '" title="' . StringUtil::specialchars($title) . '"' . $attributes . '>' . Image::getHtml($icon,
                $label) . '</a> ';
    }
}
