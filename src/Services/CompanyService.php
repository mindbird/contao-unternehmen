<?php

namespace Mindbird\Contao\Company\Services;

use Mindbird\Contao\Company\Models\CompanyArchiveModel;
use Mindbird\Contao\Company\Models\CompanyModel;

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
        //@TODO add postal filter
        return CompanyModel::findItems($companyArchiveId, $this->search, $this->category, $this->offset, $this->limit, $this->order);
    }

    /**
     * @return string
     */
    public function setOffsetAndLimit(int $companyArchiveId, int $numberOfItems = 0, int $perPage = 0, int $page = null): string
    {
        $companies = CompanyModel::findItems($companyArchiveId, $this->search, $this->category);

        if ($numberOfItems > 0) {
            $this->limit = $numberOfItems;
            $this->total = $numberOfItems;
        } elseif ($companies) {
            $this->total = $companies->count();
        }

        if ($companies && $perPage > 0 && ($this->limit == 0 || $numberOfItems > $perPage)) {
            $this->limit = $perPage;
            $page = $page !== null ? $page : 1;
            $this->offset = ($page - 1) * $this->limit;
        }
    }

    public function setOrder(int $companyArchiveId, bool $sortRandom): ?string
    {
        $companyArchive = CompanyArchiveModel::findByPk($companyArchiveId);
        switch ($companyArchive->sort_order) {
            case 2:
                $this->order = 'sorting ASC';
                break;
            case 1:
            default:
                $this->order = $sortRandom === true ? 'RAND()' : 'company ASC';
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
     * @param string $search
     */
    public function setSearch(string $search): void
    {
        $this->search = $search;
    }

    /**
     * @param int $offset
     */
    public function setOffset(int $offset): void
    {
        $this->offset = $offset;
    }

    /**
     * @param int $limit
     */
    public function setLimit(int $limit): void
    {
        $this->limit = $limit;
    }

    /**
     * @return int
     */
    public function getOffset(): int
    {
        return $this->offset;
    }

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }
}
