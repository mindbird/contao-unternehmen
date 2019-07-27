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

        //@TODO get companies as a service
        $this->model = $model;

        if ($model->companyTpl) {
            $this->templateCompanyList = $model->companyTpl;
        }

        if ($model->company_category > 0) {
            $this->filterCategory = $model->company_category;
        }

        if (!$model->company_filter_disabled) {
            $this->setCompaniesFilter();
        }

        $template->pagination = $this->setPagination();

        $companies = CompanyModel::findItems($model->company_archiv, $this->search, $this->filterCategory, $this->offset, $this->limit, $this->getOrder());

        if ($companies) {
            $template->companies = $this->getCompanies($companies);
        } else {
            $template->companies = 'Mit den ausgewählten Filterkriterien sind keine Einträge vorhanden.';
        }

        return $template->getResponse();
    }

    /**
     * @return string
     */
    protected function setCompaniesFilter(): string
    {
        if ($this->model->company_category === 0) {
            $this->filterCategory = Input::get('filterCategory');
        }

        $this->search = Input::get('search');
    }

    /**
     * @return string
     */
    protected function setPagination(): string
    {
        $companies = CompanyModel::findItems($this->model->company_archiv, $this->search, $this->filterCategory);

        if ($this->model->numberOfItems > 0) {
            $this->limit = $this->model->numberOfItems;
            $this->total = $this->model->numberOfItems;
        } elseif ($companies) {
            $this->total = $companies->count();
        }

        if ($companies && $this->model->perPage > 0 && ($this->limit == 0 || $this->model->numberOfItems > $this->model->perPage)) {
            $this->limit = $this->model->perPage;
            $page = Input::get('page') ? Input::get('page') : 1;
            $this->offset = ($page - 1) * $this->limit;

            $pagination = new Pagination($this->total, $this->limit);
            return $pagination->generate();
        }

        return '';
    }

    /**
     * @return string
     */
    protected function getOrder(): string
    {
        $companyArchive = CompanyArchiveModel::findByPk($this->model->company_archiv);
        switch ($companyArchive->sort_order) {
            case 2:
                return 'sorting ASC';
            case 1:
            default:
                return $this->company_random ? 'RAND()' : 'company ASC';
        }
    }

    /**
     * @param Collection $companies
     * @return string
     */
    protected function getCompanies(Collection $companies): string
    {
        $page = PageModel::findByIdOrAlias($this->model->jumpTo);
        $return = '';
        while ($companies->next()) {
            if ($companies->company != '') {
                $template = new FrontendTemplate($this->templateCompanyList);
                if ($companies->logo) {
                    $file = FilesModel::findByUuid($companies->logo);
                    if (null !== $file) {
                        $image = array(
                            'singleSRC' => $file->path,
                            'size' => StringUtil::deserialize($this->model->imgSize),
                            'alt' => $companies->title
                        );
                        Controller::addImageToTemplate($template, $image);
                    }
                }
                $template->company = $companies;
                if ($page) {
                    $template->link = $page->getFrontendUrl('/companyID/' . $companies->id);
                }
                $return .= $template->parse();
            }
        }

        return $return;
    }
}
