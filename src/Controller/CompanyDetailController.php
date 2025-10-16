<?php


namespace Mindbird\Contao\Company\Controller;


use Contao\Config;
use Contao\ContentModel;
use Contao\ContentModule;
use Contao\CoreBundle\DependencyInjection\Attribute\AsFrontendModule;
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
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

#[AsFrontendModule(category: 'company')]
class CompanyDetailController extends AbstractFrontendModuleController
{
    public function __construct(private readonly LoggerInterface $contaoErrorLogger)
    {

    }
    protected function getResponse(Template $template, ModuleModel $model, Request $request): Response
    {
        $alias = Input::get('auto_item');
        $company = CompanyModel::findBy('alias', $alias);

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
                    $template->figure($image['singleSRC'], $image['size']);
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
                    try {
                        $content .= Module::getContentElement($contentElement->current());
                    } catch (\Exception $exception) {
                        $this->contaoErrorLogger->error('Can not generate conten element #' . $contentElement->id . ': ' . $exception->getMessage(), __FUNCTION__, 'company');
                    }
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
