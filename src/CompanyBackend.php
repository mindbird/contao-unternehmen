<?php

namespace Company;

use Contao\BackendTemplate;
use Company\Models\CompanyArchiveModel;
use Company\Models\CompanyModel;
use Contao\Backend;
use Contao\Database;
use Contao\FilesModel;
use Contao\Input;
use Contao\PageModel;

class CompanyBackend extends Backend
{

    /**
     * Get geo coordinates from address
     *
     * @return string
     */
    public function refreshCoordinates()
    {
        $template = new BackendTemplate ('be_refresh_coordinates');
        $template->intArchiveID = Input::get('id');
        $html = '';
        $companies = CompanyModel::findBy(array('lng=?', 'lat=?'), array('', ''));
        if ($companies) {
            while ($companies->next()) {
                $company = $companies->row();
                if ($company ['street'] != '') {
                    $options = str_replace(' ', '+', urlencode($company ['street'] . ',' . $company ['plz'] . '+' . $company ['city']));
                    $json = json_decode(file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?sensor=false&address=' . $options));
                    if ($json->status == 'OK') {
                        $companies->lat = $json->results [0]->geometry->location->lat;
                        $companies->lng = $json->results [0]->geometry->location->lng;
                        $companies->save();
                        $html .= '<tr><td>' . $company ['company'] . '</td><td>OK</td></tr>';
                    } else {
                        $html .= '<tr><td>' . $company ['company'] . '</td><td>Fehler</td></tr>';
                    }
                }
            }
            $template->strHtml = $html;

            return $template->parse();
        }

        return '';
    }

    /**
     * Hook for searchable pages
     *
     * @param array $pages
     * @param number $intRoot
     * @param string $isSitemap
     * @return string
     */
    public function getSearchablePages($pages, $intRoot = 0, $isSitemap = false)
    {
        $db = Database::getInstance();
        $root = array();
        if ($intRoot > 0) {
            $root = $db->getChildRecords($intRoot, 'tl_page', true);
        }

        // Read jump to page details
        $result = $db->prepare("SELECT jumpTo, company_archiv FROM tl_module WHERE type=?")->execute('company_list');
        $modules = $result->fetchAllAssoc();

        if (count($modules) > 0) {
            $pids = array();
            foreach ($modules as $module) {
                if (is_array($root) && count($root) > 0 && !in_array($module['jumpTo'], $root)) {
                    continue;
                }

                $parent = PageModel::findWithDetails($module['jumpTo']);
                // The target page does not exist
                if ($parent === null) {
                    continue;
                }

                // The target page has not been published (see #5520)
                if (!$parent->published) {
                    continue;
                }

                // The target page is exempt from the sitemap (see #6418)
                if ($isSitemap && $parent->sitemap == 'map_never') {
                    continue;
                }

                // Set the domain (see #6421)
                $domain = ($parent->rootUseSSL ? 'https://' : 'http://') . ($parent->domain ?: \Environment::get('host')) . TL_PATH . '/';

                $pids [] = $module ['company_archiv'];
                $companies = CompanyModel::findByPids($pids, [
                    'order' => 'id ASC'
                ]);
                while ($companies->next()) {
                    $arrCompany = $companies->row();
                    $pages [] = $domain . $this->generateFrontendUrl($parent->row(), '/companyID/' . $arrCompany ['id'], $parent->language);
                }
            }
        }

        return $pages;
    }

    public function exportCSV()
    {
        $columns = array(
            'id',
            'company',
            'contact_person',
            'street',
            'postal_code',
            'city',
            'lat',
            'lng',
            'phone',
            'fax',
            'email',
            'homepage',
            'logo',
            'information'
        );
        $this->loadLanguageFile('tl_company');
        $csv = array();
        foreach ($columns as $column) {
            $csv[0][] = $GLOBALS['TL_LANG']['tl_company'][$column][0];
        }

        $pid = Input::get('id');
        $companyArchive = CompanyArchiveModel::findByPk($pid);
        $company = CompanyModel::findBy('pid', $pid);
        if ($company) {
            while ($company->next()) {
                $arrTemp = array();
                foreach ($columns as $column) {
                    if ($column == 'logo') {
                        $arrTemp[] = FilesModel::findByUuid($company->$column)->name;
                    } else {
                        $arrTemp[] = $company->$column;
                    }
                }
                $csv[] = $arrTemp;
            }
        }

        ob_start();
        $file = fopen("php://output", 'w');
        fputcsv($file, $this->arrFields);
        foreach ($csv as $arrRow) {
            fputcsv($file, $arrRow);
        }
        fclose($file);
        $csv = ob_get_clean();

        header("Content-Type: application/force-download");
        header("Content-Disposition: attachment; filename=\"" . $companyArchive->title . ".csv\"");
        header("Content-Length: " . strlen($csv));

        echo $csv;
        exit();
    }
}
