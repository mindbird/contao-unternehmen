<?php


namespace Mindbird\Contao\Company\Controller;

use Mindbird\Contao\Company\Models\CompanyArchiveModel;
use Mindbird\Contao\Company\Models\CompanyCategoryModel;
use Mindbird\Contao\Company\Models\CompanyModel;
use Contao\Controller;
use Contao\CoreBundle\Controller\FrontendModule\AbstractFrontendModuleController;
use Contao\Environment;
use Contao\FilesModel;
use Contao\Frontend;
use Contao\FrontendTemplate;
use Contao\Input;
use Contao\ModuleModel;
use Contao\PageModel;
use Contao\Pagination;
use Contao\StringUtil;
use Contao\Template;
use Mindbird\Contao\Company\Services\CompanyService;
use Model\Collection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CompanyMapController extends AbstractFrontendModuleController
{
    private $model;
    private $companyService;

    public function __construct(CompanyService $companyService)
    {
        $this->companyService = $companyService;
    }

    protected function getResponse(Template $template, ModuleModel $model, Request $request): Response
    {

        if ($model->companyTpl) {
            $this->templateCompanyList = $model->companyTpl;
        }

        if ($model->company_category > 0) {
            $this->filterCategory = $model->company_category;
        }

        if (!$model->company_filter_disabled) {
            if ($this->model->company_category === 0) {
                $this->companyService->setCategory($request->get('filterCategory'));
            }

            $this->companyService->setSearch($request->get('search'));
        }

        $this->companyService->setOrder($model->company_archiv, $model->company_random);

        $companies = $this->companyService->fetchCompanies($model->company_archiv);
        if ($companies !== null) {
            $template->companies = $this->companyService->parseCompanies($companies, null, StringUtil::deserialize($model->imgSize));
        } else {
            $template->companies = 'Mit den ausgewählten Filterkriterien sind keine Einträge vorhanden.';
        }

        return $template->getResponse();
    }

}
