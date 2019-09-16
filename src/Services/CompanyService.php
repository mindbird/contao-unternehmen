<?php

namespace Mindbird\Contao\Company\Services;

use Contao\Controller;
use Contao\FilesModel;
use Contao\FrontendTemplate;
use Contao\PageModel;
use Mindbird\Contao\Company\Controller\CompanyListController;
use Mindbird\Contao\Company\Models\CompanyArchiveModel;
use Mindbird\Contao\Company\Models\CompanyModel;
use Model\Collection;

class CompanyService
{
    private $category;
    private $search;
    private $postal;
    private $offset;
    private $limit;
    private $order;

    public function __construct()
    {
        $this->category = 0;
        $this->search = '';
        $this->offset = 0;
        $this->limit = 0;
        $this->order = 'company ASC';
    }

    public function fetchCompanies(int $companyArchiveId)
    {
        dump($this->search);
        //@TODO add postal filter
        return CompanyModel::findItems($companyArchiveId, $this->search, $this->category, $this->offset, $this->limit, $this->order);
    }

    public function setOffsetAndLimit(int $companyArchiveId, int $numberOfItems = 0, int $perPage = 0, int $page = null): void
    {
        $companies = CompanyModel::findItems($companyArchiveId, $this->search, $this->category);

        if ($numberOfItems > 0) {
            $this->limit = $numberOfItems;
            $this->total = $numberOfItems;
        } elseif ($companies) {
            $this->total = $companies->count();
        }

        if ($companies && $perPage > 0 && ($this->limit === 0 || $numberOfItems > $perPage)) {
            $this->limit = $perPage;
            $page = $page ?? 1;
            $this->offset = ($page - 1) * $this->limit;
        }
    }

    public function setOrder(int $companyArchiveId, bool $sortRandom): void
    {
        $companyArchive = CompanyArchiveModel::findByPk($companyArchiveId);
        if ($companyArchive !== null) {
            switch ($companyArchive->sort_order) {
                case 2:
                    $this->order = 'sorting ASC';
                    break;
                case 1:
                default:
                    $this->order = $sortRandom === true ? 'RAND()' : 'company ASC';
            }
        }
    }

    /**
     * @param int $category
     */
    public function setCategory(int $category): void
    {
        $this->category = $category;
    }


    /**
     * @param string $postal
     */
    public function setPostal(string $postal): void
    {
        $this->postal = $postal;
    }

    /**
     * @param string|null $search
     */
    public function setSearch(string $search = null): void
    {
        $this->search = $search;
    }

    /**
     * @return int
     */
    public function getOffset(): int
    {
        return $this->offset;
    }

    /**
     * @param int $offset
     */
    public function setOffset(int $offset): void
    {
        $this->offset = $offset;
    }

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * @param int $limit
     */
    public function setLimit(int $limit): void
    {
        $this->limit = $limit;
    }

    /**
     * @param Collection $companies
     * @param PageModel|null $pageModel
     * @param array $imgSize
     * @return string
     */
    public function parseCompanies(Collection $companies, PageModel $pageModel = null, array $imgSize = null, $templateList = 'company_list'): string
    {
        $return = '';
        while ($companies->next()) {
            if ($companies->company != '') {
                $template = new FrontendTemplate($templateList);
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
