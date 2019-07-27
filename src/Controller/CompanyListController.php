<?php


namespace Mindbird\Contao\Company\Controller;

use Contao\Controller;
use Contao\CoreBundle\Controller\FrontendModule\AbstractFrontendModuleController;
use Contao\FilesModel;
use Contao\FrontendTemplate;
use Contao\ModuleModel;
use Contao\PageModel;
use Contao\Pagination;
use Contao\StringUtil;
use Contao\Template;
use Mindbird\Contao\Company\Services\CompanyService;
use Model\Collection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CompanyListController extends AbstractFrontendModuleController
{
    protected $templateCompanyList = 'company_list';
    private $companyService;

    public function __construct(CompanyService $companyService)
    {
        
    }

    public static function getSubscribedServices(): array
    {
        $services = parent::getSubscribedServices();
        $services['mindbird.contao.company_service'] = CompanyService::class;
        return $services;
    }

    protected function getResponse(Template $template, ModuleModel $model, Request $request): Response
    {
        $this->get('mindbird.contao.company_service');
        dump($this->companyService);
        $page = PageModel::findByIdOrAlias($model->jumpTo);

        if ($model->companyTpl) {
            $this->templateCompanyList = $model->companyTpl;
        }

        // Set category if module setting is set
        if ($model->company_category > 0) {
            $this->companyService->setCategory($model->company_category);
        }

        // Filter if not disabled
        if (!$model->company_filter_disabled) {
            if ($this->model->company_category === 0) {
                $this->companyService->setCategory($request->get('filterCategory'));
            }

            $this->companyService->setSearch($request->get('search'));
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
            $template->companies = $this->parseCompanies($companies, $page, StringUtil::deserialize($model->imgSize));
        } else {
            $template->companies = 'Mit den ausgewählten Filterkriterien sind keine Einträge vorhanden.';
        }

        return $template->getResponse();
    }

    /**
     * @param Collection $companies
     * @param PageModel|null $pageModel
     * @param array $imgSize
     * @return string
     */
    protected function parseCompanies(Collection $companies, PageModel $pageModel = null, array $imgSize = null): string
    {
        $return = '';
        while ($companies->next()) {
            if ($companies->company != '') {
                $template = new FrontendTemplate($this->templateCompanyList);
                if ($companies->logo) {
                    $file = FilesModel::findByUuid($companies->logo);
                    if (null !== $file) {
                        $image = array(
                            'singleSRC' => $file->path,
                            'size' => $imgSize,
                            'alt' => $companies->title
                        );
                        Controller::addImageToTemplate($template, $image);
                    }
                }
                $template->company = $companies;
                if ($pageModel !== null) {
                    $template->link = $pageModel->getFrontendUrl('/companyID/' . $companies->id);
                }
                $return .= $template->parse();
            }
        }

        return $return;
    }
}
