<?php

namespace Mindbird\Contao\Company\Tables;

use Company\Models\CompanyArchiveModel;
use Company\Models\CompanyCategoryModel;
use Contao\Backend;

class Module extends Backend {
    public function getCompanyTemplates()
    {
        return $this->getTemplateGroup('company_');
    }

    public function getGalleryTemplates()
    {
        return $this->getTemplateGroup('gallery_');
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
