<?php


namespace Mindbird\Contao\Company\Controller;

use Contao\CoreBundle\Controller\FrontendModule\AbstractFrontendModuleController;
use Contao\CoreBundle\DependencyInjection\Attribute\AsFrontendModule;
use Contao\Environment;
use Contao\Frontend;
use Contao\Input;
use Contao\ModuleModel;
use Contao\PageModel;
use Contao\System;
use Contao\Template;
use Mindbird\Contao\Company\Models\CompanyCategoryModel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

#[AsFrontendModule(CompanyFilterController::TYPE, category: 'company')]
class CompanyFilterController extends AbstractFrontendModuleController
{
    const TYPE = 'mod_company_filter';

    protected function getResponse(Template $template, ModuleModel $model, Request $request): Response
    {
        System::loadLanguageFile('tl_company_frontend');
        $template->langSearch = $GLOBALS['TL_LANG']['tl_company_frontend']['search'];
        $template->langSelectCategory = $GLOBALS['TL_LANG']['tl_company_frontend']['selectCategory'];
        $template->langPostal = $GLOBALS['TL_LANG']['tl_company_frontend']['postal'];


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
