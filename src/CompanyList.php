<?php

namespace Company;

use Company\Models\CompanyArchiveModel;
use Company\Models\CompanyCategoryModel;
use Company\Models\CompanyModel;
use Contao\BackendTemplate;
use Contao\Controller;
use Contao\Environment;
use Contao\FilesModel;
use Contao\FrontendTemplate;
use Contao\Image;
use Contao\Input;
use Contao\Module;
use Contao\PageModel;
use Contao\Pagination;

class CompanyList extends Module
{

    protected $strTemplate = 'mod_company_list';

    protected $strTemplateCompanyList = 'company_list';

    protected $search = '';

    protected $filterCategory = 0;

    protected $limit = 0;

    protected $offset = 0;

    protected $total = 0;

    public function generate()
    {
        if (TL_MODE == 'BE') {
            $template = new BackendTemplate ('be_wildcard');
            $template->wildcard = '### UNTERNEHEMEN LISTE ###';
            $template->title = $this->headline;
            $template->id = $this->id;
            $template->link = $this->name;
            $template->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

            return $template->parse();
        }

        if ($this->companyTpl) {
            $this->strTemplateCompanyList = $this->companyTpl;
        }

        return parent::generate();
    }

    protected function compile()
    {
        if ($this->company_category > 0) {
            $this->filterCategory = $this->company_category;
        }
        if (!$this->company_filter_disabled) {
            $this->generateFilter();
        }

        $this->setPagination();

        $companies = CompanyModel::findItems($this->company_archiv, $this->search, $this->filterCategory, $this->offset,
            $this->limit, $this->getOrder());

        if ($companies) {
            $this->Template->strCompanies = $this->getCompanies($companies);
        } else {
            $this->Template->strCompanies = 'Mit den ausgewählten Filterkriterien sind keine Einträge vorhanden.';
        }
    }

    /**
     * Return string/html of all companies
     *
     * @param array $arrCompanies
     *            DB query rows as array
     * @return string
     */
    protected function getCompanies($companies)
    {
        $page = PageModel::findByIdOrAlias($this->jumpTo);
        $return = '';
        while ($companies->next()) {
            if ($companies->company != '') {
                $template = new FrontendTemplate ($this->strTemplateCompanyList);
                if ($companies->logo) {
                    $file = FilesModel::findByUuid($companies->logo);
                    if (null !== $file) {
                        $image = array(
                            'singleSRC' => $file->path,
                            'size' => deserialize($this->imgSize),
                            'alt' => $companies->title
                        );
                        Controller::addImageToTemplate($template, $image);
                    }
                }
                $template->objCompany = $companies;
                if ($page) {
                    $template->link = $this->generateFrontendUrl($page->row(), '/companyID/' . $companies->id);
                }
                $return .= $template->parse();
            }
        }

        return $return;
    }

    protected function generateFilter()
    {
        $templateFilter = new FrontendTemplate('company_list_filter');
        if ($this->company_category === 0) {
            $this->filterCategory = Input::get('filterCategory');
        }

        $filterUrl = '';
        if ($this->filterCategory > 0) {
            $filterUrl = '&filterCategory=' . $this->filterCategory;
        }
        $this->search = Input::get('search');
        $searchUrl = 'search=%s';

        $templateFilter->strLink = $this->search != '' ? Environment::get('base') . $this->addToUrl(sprintf($searchUrl,
                    $this->search) . '&filterCategory=ID',
                true) : Environment::get('base') . $this->addToUrl('filterCategory=ID', true);

        $alphabet = range('A', 'Z');
        $filterName = '<a href="' . $this->addToUrl($filterUrl, true) . '">Alle</a> ';
        for ($i = 0; $i < count($alphabet); $i++) {
            $filterName .= '<a href="' . $this->addToUrl(sprintf($searchUrl, $alphabet [$i]) . $filterUrl,
                    true) . '">' . $alphabet [$i] . '</a> ';
        }
        $templateFilter->strFilterName = $filterName;

        $this->loadLanguageFile('tl_company_category');
        $categories = CompanyCategoryModel::findBy('pid', $this->company_archiv, array(
            'order' => 'title ASC'
        ));
        $options = '<option value="0">' . $GLOBALS ['TL_LANG'] ['tl_company_category'] ['category'] [0] . '</option>';
        if ($categories) {
            while ($categories->next()) {
                $options .= '<option value="' . $categories->id . '"' . ($this->filterCategory != $categories->id ? '' : ' selected') . '>' . $categories->title . '</option>';
            }
        }
        $templateFilter->strCategoryOptions = $options;

        $this->Template->strFilter = $templateFilter->parse();
    }

    protected function setPagination()
    {
        $companies = CompanyModel::findItems($this->company_archiv, $this->search, $this->filterCategory);

        if ($this->numberOfItems > 0) {
            $this->limit = $this->numberOfItems;
            $this->total = $this->numberOfItems;
        } elseif ($companies) {
            $this->total = $companies->count();
        }

        if ($companies && $this->perPage > 0 && ($this->limit == 0 || $this->numberOfItems > $this->perPage)) {
            $this->limit = $this->perPage;
            $page = $this->Input->get('page') ? $this->Input->get('page') : 1;
            $this->offset = ($page - 1) * $this->limit;

            $pagination = new Pagination ($this->total, $this->limit);
            $this->Template->strPagination = $pagination->generate();
        }
    }

    /**
     * @return string
     */
    protected function getOrder()
    {
        $companyArchive = CompanyArchiveModel::findByPk($this->company_archiv);
        switch ($companyArchive->sort_order) {
            case 2:
                $order = 'sorting ASC';
                break;
            case 1:
            default:
                $order = $this->company_random ? 'RAND()' : 'company ASC';
                break;
        }

        return $order;
    }
}
