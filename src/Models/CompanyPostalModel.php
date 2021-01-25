<?php

namespace Mindbird\Contao\Company\Models;

use Contao\Model;

class CompanyPostalModel extends Model
{
    protected static $strTable = 'tl_company_postal';

    public static function findByPostal(string $postal, int $companyArchivId)
    {
        $options = array ();
        $options['column'][] = 'tl_company_archive.id = ? AND tl_company_archive.id = tl_company.pid AND tl_company_postal.pid = tl_company.id';
        $options['value'][] = $companyArchivId;

        $options['column'][] = 'start <= ?';
        $options['value'][] = $postal;

        $options['column'][] = 'end >= ?';
        $options['value'][] = $postal;

        return static::find($options);
    }
}
