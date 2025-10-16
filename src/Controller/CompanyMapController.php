<?php


namespace Mindbird\Contao\Company\Controller;

use Contao\CoreBundle\Controller\FrontendModule\AbstractFrontendModuleController;
use Contao\CoreBundle\DependencyInjection\Attribute\AsFrontendModule;
use Contao\ModuleModel;
use Contao\PageModel;
use Contao\Template;
use Mindbird\Contao\Company\Services\CompanyService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

#[AsFrontendModule(CompanyMapController::TYPE, category: 'company', template: 'mod_'.CompanyMapController::TYPE)]
class CompanyMapController extends AbstractFrontendModuleController
{
    const string TYPE = 'company_map';
    private CompanyService $companyService;

    public function __construct(CompanyService $companyService)
    {
        $this->companyService = $companyService;
    }

    protected function getResponse(Template $template, ModuleModel $model, Request $request): Response
    {
        $template->id = $model->id;
        $template->googlemaps_apikey = $GLOBALS['TL_CONFIG']['company_googlemaps_apikey'];
        $page = PageModel::findByIdOrAlias($model->jumpTo);

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

        // Fetch companies
        $companies = $this->companyService->fetchCompanies($model->company_archiv);
        if ($companies !== null) {
            $template->companies = $this->companyService->parseCompaniesToJson($companies, $page);
            $template->mapCenter = $this->companyService->calcMapCenter($companies);
        }

        return $template->getResponse();
    }

}
