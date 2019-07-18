<?php

namespace Mindbird\Contao\Company\Tables;

use Contao\Backend;
use Mindbird\Contao\Company\Models\CompanyCategoryModel;

class Module extends Backend {
    public function getCompanyTemplates()
    {
        return self::getTemplateGroup('company_');
    }

    public function getGalleryTemplates()
    {
        return self::getTemplateGroup('gallery_');
    }

    public function getCategoryOptions($dc)
    {
        $options = [0 => 'Alle anzeigen'];
        $categories = CompanyCategoryModel::findBy('pid', $dc->activeRecord->company_archiv, ['order' => 'title ASC']);
        if ($categories !== null) {
            while ($categories->next()) {
                $options[$categories->id] = $categories->title;
            }
        }

        return $options;
    }
}
