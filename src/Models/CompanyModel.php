<?php

namespace Mindbird\Contao\Company\Models;

use Contao\Model;

class CompanyModel extends Model {

	protected static $strTable = 'tl_company';

	public static function findItems($pid, $companyName = '', $category = 0, $offset = 0, $limit = 0, $order = 'company ASC', $companiesIdsWithinPostalRange = []) {
	    $options = array ();

	    if ($companiesIdsWithinPostalRange !== []) {
            $options['column'][] = 'id IN (' . implode(', ', $companiesIdsWithinPostalRange) . ')';
        }

		$options['column'][] = 'pid = ?';
		$options['value'][] = $pid;

        $options['column'][] = 'published = ?';
        $options['value'][] = 1;

		if ($companyName != '') {
			$options['column'][] = 'company LIKE ?';
			$options['value'][] = '%' . $companyName . '%';
		}

		if ($category > 0) {
			$options['column'][] = 'category LIKE ?';
			$options['value'][] = '%"' . $category . '"%';
		}

		if ($offset > 0) {
			$options['offset'] = $offset;
		}

		if ($limit > 0) {
			$options['limit'] = $limit;
		}

		$options['order'] = $order;

		return static::find($options);
	}
}
