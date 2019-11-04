<?php


namespace Mindbird\Contao\Company\Controller;

use Contao\CoreBundle\Controller\FrontendModule\AbstractFrontendModuleController;
use Contao\ModuleModel;
use Contao\PageModel;
use Contao\Pagination;
use Contao\StringUtil;
use Contao\Template;
use Mindbird\Contao\Company\Services\CompanyService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CompanyListController extends AbstractFrontendModuleController
{
    protected $templateCompanyList = 'company_list';
    private $companyService;

    public function __construct(CompanyService $companyService)
    {
        $this->companyService = $companyService;
    }

    protected function getResponse(Template $template, ModuleModel $model, Request $request): Response
    {
        $page = PageModel::findByIdOrAlias($model->jumpTo);

        if ($model->companyTpl) {
            $this->templateCompanyList = $model->companyTpl;
        }

        // Set category if module setting is set
        if ($model->company_category > 0) {
            $this->companyService->setCategory($model->company_category);
        }

        // Filter if not disabled
        if ($model->company_filter_disabled !== '1') {
            // Filter by category if category is not set in module settings
            if ($model->company_category === '0' && $request->query->get('category') !== null) {
                $this->companyService->setCategory($request->query->get('category'));
            }

            // Filter by search value
            if ($request->query->get('search') !== null) {
                $this->companyService->setSearch($request->query->get('search'));
            }

            // Filter by postal value
            if ($request->query->get('postal') !== null) {
                $this->companyService->setPostal($request->query->get('postal'));
            }
        }

        // Set order
        $this->companyService->setOrder($model->company_archiv, $model->company_random);

        // Pagination
        $this->companyService->setOffsetAndLimit($model->company_archiv, $model->numberOfItems, $model->perPage, $request->get('page'));
        $pagination = new Pagination($this->companyService->getOffset(), $this->companyService->getLimit());
        $template->pagination = $pagination->generate();

        // Fetch companies
        $companies = $this->companyService->fetchCompanies($model->company_archiv);
        if ($companies !== null) {
            $template->companies = $this->companyService->parseCompanies($companies, $page, StringUtil::deserialize($model->imgSize), $this->templateCompanyList);
        } else {
            $template->companies = 'Mit den ausgewählten Filterkriterien sind keine Einträge vorhanden.';
        }

        return $template->getResponse();
    }

}
