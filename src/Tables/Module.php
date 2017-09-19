<?php

namespace Company\Tables;

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
}