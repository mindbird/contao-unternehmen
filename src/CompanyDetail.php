<?php

namespace Company;

use Company\Models\CompanyModel;
use Contao\BackendTemplate;
use Contao\ContentGallery;
use Contao\Controller;
use Contao\Database;
use Contao\FilesModel;
use Contao\FrontendTemplate;
use Contao\Input;
use Contao\Module;

class CompanyDetail extends Module
{
    protected $strTemplate = 'mod_company_detail';

    public function generate()
    {
        if (TL_MODE == 'BE') {
            $template = new BackendTemplate('be_wildcard');
            $template->wildcard = '### UNTERNEHEMEN DETAILS ###';
            $template->title = $this->headline;
            $template->id = $this->id;
            $template->link = $this->name;
            $template->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

            return $template->parse();
        }

        return parent::generate();
    }

    protected function compile()
    {
        $db = Database::getInstance();
        $id = Input::get('companyID');
        $company = CompanyModel::findByPk($id);
        if ($company) {
            $template = new FrontendTemplate ('company_detail');
            if ($company->logo) {
                $file = FilesModel::findByUuid($company->logo);
                $size = deserialize($this->imgSize);
                $image = array(
                    'singleSRC' => $file->path,
                    'size' => $size,
                    'alt' => $company->title
                );
                Controller::addImageToTemplate($template, $image);
            }

            $size = deserialize($this->gallery_size);
            if ($company->gallery_multiSRC != '' && $size[0] != '' && $size[1] != '' && $size[2] != '') {
                $gallery = new ContentGallery($this->objModel);
                $gallery->multiSRC = $company->gallery_multiSRC;
                $gallery->orderSRC = $company->gallery_orderSRC;
                $gallery->size = $this->gallery_size;
                $gallery->imagemargin = $this->gallery_imagemargin;
                $gallery->perRow = $this->gallery_perRow;
                $gallery->perPage = $this->gallery_perPage;
                $gallery->numberOfItems = $this->gallery_numberOfItems;
                $gallery->fullsize = $this->gallery_fullsize;
                $gallery->type = 'gallery';
                if ($company->gallery_orderSRC != '') {
                    $gallery->sortBy = 'custom';
                }
                $template->gallery = $gallery->generate();
            }

            $category = '';
            $categories = deserialize($company->category);
            if (count($categories) > 0) {
                $arrCategory = array();
                $companyCategories = $db->prepare("SELECT * FROM tl_company_category WHERE id IN(" . implode(',',
                        $categories) . ")")->execute();
                while ($companyCategories->next()) {
                    $arrCategory[] = $companyCategories->title;
                }
                $category = implode(', ', $arrCategory);
            }
            $template->company = $company->company;
            $template->contact_person = $company->contact_person;
            $template->category = $category;
            $template->street = $company->street;
            $template->postal_code = $company->postal_code;
            $template->city = $company->city;
            $template->phone = $company->phone;
            $template->fax = $company->fax;
            $template->email = $company->email;
            $template->homepage = $company->homepage;
            $template->lat = $company->lat;
            $template->lng = $company->lng;
            $template->imageWidth = $size [0];
            $template->imageHeight = $size [1];
            $template->information = $company->information;
            $template->googlemaps_apikey = $GLOBALS['TL_CONFIG']['company_googlemaps_apikey'];

            $this->Template->strHtml = $template->parse();
        }
    }
}