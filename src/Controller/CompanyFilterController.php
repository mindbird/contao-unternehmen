<?php


namespace Mindbird\Contao\Company\Controller;

use Contao\CoreBundle\Controller\FrontendModule\AbstractFrontendModuleController;
use Contao\Environment;
use Contao\Frontend;
use Contao\Input;
use Contao\ModuleModel;
use Contao\Template;
use Mindbird\Contao\Company\Models\CompanyCategoryModel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CompanyFilterController extends AbstractFrontendModuleController
{
    protected $templateCompanyList = 'company_list';
    protected $search = '';
    protected $filterCategory = 0;
    protected $limit = 0;
    protected $offset = 0;
    protected $total = 0;
    protected $model;

    protected function getResponse(Template $template, ModuleModel $model, Request $request): Response
    {
        if ($this->model->company_category === 0) {
            $this->filterCategory = Input::get('filterCategory');
        }

        $filterUrl = '';
        if ($this->filterCategory > 0) {
            $filterUrl = '&filterCategory=' . $this->filterCategory;
        }
        $this->search = Input::get('search');
        $searchUrl = 'search=%s';

        $template->link = $this->search != '' ? Environment::get('base') . Frontend::addToUrl(sprintf($searchUrl,
                    $this->search) . '&filterCategory=ID',
                true) : Environment::get('base') . Frontend::addToUrl('filterCategory=ID', true);

        $alphabet = range('A', 'Z');
        $filterName = '<a href="' . Frontend::addToUrl($filterUrl, true) . '">Alle</a> ';
        foreach ($alphabet as $iValue) {
            $filterName .= '<a href="' . Frontend::addToUrl(sprintf($searchUrl, $iValue) . $filterUrl,
                    true) . '">' . $iValue . '</a> ';
        }
        $template->filterName = $filterName;

        $categories = CompanyCategoryModel::findBy('pid', $this->company_archiv, array(
            'order' => 'title ASC'
        ));
        $options = '<option value="0">Kategorie auswählen</option>';
        if ($categories) {
            while ($categories->next()) {
                $options .= '<option value="' . $categories->id . '"' . ($this->filterCategory != $categories->id ? '' : ' selected') . '>' . $categories->title . '</option>';
            }
        }
        $template->categoryOptions = $options;

        return $template->getResponse();
    }
}
