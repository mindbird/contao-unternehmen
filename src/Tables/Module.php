<?php

namespace Company\Tables;


use Contao\Backend;

class Moduleextends extends Backend {
    public function getCompanyTemplates()
    {
        return $this->getTemplateGroup('company_');
    }
}