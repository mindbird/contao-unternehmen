<?php

namespace Mindbird\Contao\Company\Services;

use Contao\Controller;
use Contao\FilesModel;
use Contao\FrontendTemplate;
use Contao\PageModel;
use Mindbird\Contao\Company\Models\CompanyArchiveModel;
use Mindbird\Contao\Company\Models\CompanyModel;
use Mindbird\Contao\Company\Models\CompanyPostalModel;
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
        $this->postal = '';
        $this->offset = 0;
        $this->limit = 0;
        $this->order = 'company ASC';
    }

    public function fetchCompanies(int $companyArchiveId)
    {
        if ($this->postal !== '') {
            $companies = CompanyPostalModel::findByPostal($this->postal);
            $companiesIdsWithinPostalRange = [];

            if ($companies !== null) {
                while ($companies->next()) {
                    $companiesIdsWithinPostalRange[] = $companies->pid;
                }
            }
        }

        return CompanyModel::findItems($companyArchiveId, $this->search, $this->category, $this->offset, $this->limit, $this->order, $companiesIdsWithinPostalRange);
    }

    public function setOffsetAndLimit(int $companyArchiveId, int $numberOfItems = 0, int $perPage = 0, int $page = null): void
    {
        // @TODO Filter Postal
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
     * @param int|null $category
     */
    public function setCategory(int $category = null): void
    {
        $this->category = $category;
    }


    /**
     * @param string|null $postal
     */
    public function setPostal(string $postal = null): void
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
                    $template->link = $pageModel->getFrontendUrl('/companyId/' . $companies->id);
                }
                $return .= $template->parse();
            }
        }

        return $return;
    }

    /**
     * @param Collection $companies
     * @return string
     */
    public function parseCompaniesToJson(Collection $companies): string
    {
        $return = [];
        while ($companies->next()) {
            if ($companies->company != '') {
                $return[] = [
                    'name' => $companies->company,
                    'lat' => $companies->lat,
                    'lng' => $companies->lng,
                    'street' => $companies->street,
                    'postal' => $companies->postal_code,
                    'city' => $companies->city
                ];
            }
        }

        return json_encode($return);
    }

    public function calcMapCenter($companies)
    {
        $top = null;
        $bottom = null;
        $left = null;
        $right = null;

        foreach ($companies as $company) {
            if ($top === null || $company->lng > $top) {
                $top = $company->lng;
            }

            if ($bottom === null || $company->lng < $bottom) {
                $bottom = $company->lng;
            }

            if ($right === null || $company->lat > $right) {
                $right = $company->lat;
            }

            if ($left === null || $company->lat < $left) {
                $left = $company->lat;
            }
        }

        $lngCenter = ($bottom + $top) / 2;
        $latCenter = ($left + $right) / 2;

        return ['lat' => $latCenter, 'lng' => $lngCenter];
    }
}
