<?php


namespace Mindbird\Contao\Company\Controller;


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
        $id = $request->companyId;
        $company = CompanyModel::findByPk($id);
        if ($company !== null) {
            $template->id = $model->id . '_' . $id;

            if ($company->logo) {
                $file = FilesModel::findByUuid($company->logo);
                if (null !== $file) {
                    $size = StringUtil::deserialize($this->imgSize);
                    $image = [
                        'singleSRC' => $file->path,
                        'size' => $size,
                        'alt' => $company->title
                    ];
                    Controller::addImageToTemplate($template, $image);
                }
            }

            $size = StringUtil::deserialize($this->gallery_size);
            if ($company->gallery_multiSRC != '' && ($size[0] != '' || $size[1] != '')) {
                $template->gallery = $this->parseGallery($company);
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

            return $template->getResponse();
        }
    }

    /**
     * @param $company
     * @return string
     */
    protected function parseGallery($company): string
    {
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
        return $gallery->generate();
    }
}
