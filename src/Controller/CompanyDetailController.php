<?php


namespace Mindbird\Contao\Company\Controller;


use Contao\Config;
use Contao\ContentModel;
use Contao\Input;
use Contao\Module;
use Contao\System;
use Mindbird\Contao\Company\Models\CompanyModel;
use Contao\ContentGallery;
use Contao\Controller;
use Contao\CoreBundle\Controller\FrontendModule\AbstractFrontendModuleController;
use Contao\FilesModel;
use Contao\ModuleModel;
use Contao\StringUtil;
use Contao\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CompanyDetailController extends AbstractFrontendModuleController
{
    protected function getResponse(Template $template, ModuleModel $model, Request $request): Response
    {
        if (!isset($_GET['item']) && Config::get('useAutoItem') && isset($_GET['auto_item'])) {
            Input::setGet('item', Input::get('auto_item'));
        }

        $id = Input::get('companyId');
        $company = CompanyModel::findByPk($id);
        if ($id === null) {
            $alias = Input::get('auto_item');
            $company = CompanyModel::findBy('alias', $alias);
        }

        if ($company !== null) {
            $template->id = $model->id . '_' . $company->id;

            if ($company->logo) {
                $file = FilesModel::findByUuid($company->logo);
                if (null !== $file) {
                    $size = StringUtil::deserialize($model->imgSize);
                    $image = [
                        'singleSRC' => $file->path,
                        'size' => $size,
                        'alt' => $company->title
                    ];
                    Controller::addImageToTemplate($template, $image);
                }
            }

            $size = StringUtil::deserialize($model->gallery_size);
            if ($company->gallery_multiSRC != '' && ($size[0] != '' || $size[1] != '')) {
                $template->gallery = $this->parseGallery($company, $model);
            }

            $categoriesDb = $company->getRelated('category');
            $categories = [];
            $category = '';
            if ($categoriesDb !== null) {
                while ($categoriesDb->next()) {
                    $categories[] = $categoriesDb->title;
                }
                $category = implode(', ', $categories);
            }
            $template->company = $company;
            $template->category = $category;
            $template->imageWidth = $size [0];
            $template->imageHeight = $size [1];
            $template->googlemaps_apikey = $GLOBALS['TL_CONFIG']['company_googlemaps_apikey'];


            $content = '';
            $contentElement = ContentModel::findPublishedByPidAndTable($company->id, 'tl_company');
            if ($contentElement !== null)
            {
                while ($contentElement->next())
                {
                    $content .= Module::getContentElement($contentElement->current());
                }
            }
            $template->content = $content;

        }

        return $template->getResponse();
    }

    /**
     * @param $company
     * @return string
     */
    protected function parseGallery(CompanyModel $company, $moduleModel): string
    {
        $gallery = new ContentGallery($moduleModel);
        $gallery->multiSRC = $company->gallery_multiSRC;
        $gallery->orderSRC = $company->gallery_orderSRC;
        $gallery->size = $moduleModel->gallery_size;
        $gallery->imagemargin = $moduleModel->gallery_imagemargin;
        $gallery->perRow = $moduleModel->gallery_perRow;
        $gallery->perPage = $moduleModel->gallery_perPage;
        $gallery->numberOfItems = $moduleModel->gallery_numberOfItems;
        $gallery->fullsize = $moduleModel->gallery_fullsize;
        $gallery->type = 'gallery';
        if ($company->gallery_orderSRC != '') {
            $gallery->sortBy = 'custom';
        }
        return $gallery->generate();
    }
}
