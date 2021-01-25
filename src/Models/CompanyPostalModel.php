<?php

namespace Mindbird\Contao\Company\Models;

use Contao\Model;

class CompanyPostalModel extends Model
{
    protected static $strTable = 'tl_company_postal';

    public static function findByPostal(string $postal, int $pid)
    {
        $options = array ();
        $options['column'][] = 'pid <= ?';
        $options['value'][] = $pid;

        $options['column'][] = 'start <= ?';
        $options['value'][] = $postal;

        $options['column'][] = 'end >= ?';
        $options['value'][] = $postal;

        return static::find($options);
    }
}
