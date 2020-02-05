<?php


namespace Mindbird\Contao\Company\Controller;

use Contao\CoreBundle\Controller\FrontendModule\AbstractFrontendModuleController;
use Contao\Environment;
use Contao\Frontend;
use Contao\Input;
use Contao\ModuleModel;
use Contao\PageModel;
use Contao\Template;
use Mindbird\Contao\Company\Models\CompanyCategoryModel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CompanyFilterController extends AbstractFrontendModuleController
{
    protected $model;

    protected function getResponse(Template $template, ModuleModel $model, Request $request): Response
    {
        $template->category = $request->query->get('category');
        $template->search = $request->query->get('search');
        $template->postal = $request->query->get('postal');

        $categories = CompanyCategoryModel::findBy('pid', $model->company_archiv, array(
            'order' => 'title ASC'
        ));
        $template->categories = [];
        if ($categories !== null) {
            $template->categories = $categories;
        }

        $template->url = '';
        if ($model->jumpTo !== '') {
            $page = PageModel::findByPk($model->jumpTo);

            if ($page !== null) {
                $template->url = $page->getFrontendUrl();
            }

        }

        return $template->getResponse();
    }
}
