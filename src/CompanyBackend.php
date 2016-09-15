<?php

namespace Company;

class CompanyBackend extends \Backend
{

    /**
     * Get geo coordinates from address
     *
     * @return string
     */
    public function refreshCoordinates()
    {
        $objTemplate = new \BackendTemplate ('be_refresh_coordinates');
        $objTemplate->intArchiveID = \Input::get('id');
        $strHtml = '';
        $objCompanies = \CompanyModel::findBy(array('lng=?', 'lat=?'), array('', ''));
        if ($objCompanies) {
            while ($objCompanies->next()) {
                $arrCompany = $objCompanies->row();
                if ($arrCompany ['street'] != '') {
                    $strOptions = str_replace(' ', '+',
                        urlencode($arrCompany ['street'] . ',' . $arrCompany ['plz'] . '+' . $arrCompany ['city']));
                    $objJson = json_decode(file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?sensor=false&address=' . $strOptions));
                    if ($objJson->status == 'OK') {
                        $objCompanies->lat = $objJson->results [0]->geometry->location->lat;
                        $objCompanies->lng = $objJson->results [0]->geometry->location->lng;
                        $objCompanies->save();
                        $strHtml .= '<tr><td>' . $arrCompany ['company'] . '</td><td>OK</td></tr>';
                    } else {
                        $strHtml .= '<tr><td>' . $arrCompany ['company'] . '</td><td>Fehler</td></tr>';
                    }
                }
            }
            $objTemplate->strHtml = $strHtml;

            return $objTemplate->parse();
        }
    }

    /**
     * Hook for searchable pages
     *
     * @param unknown $arrPages
     * @param number $intRoot
     * @param string $blnIsSitemap
     * @return string
     */
    public function getSearchablePages($arrPages, $intRoot = 0, $blnIsSitemap = false)
    {
        $arrRoot = array();
        if ($intRoot > 0) {
            $arrRoot = $this->getChildRecords($intRoot, 'tl_page', true);
        }

        // Read jump to page details
        $objResult = $this->Database->prepare("SELECT jumpTo, company_archiv FROM tl_module WHERE type=?")->execute('company_list');
        $arrModules = $objResult->fetchAllAssoc();

        if (count($arrModules) > 0) {
            $arrPids = array();
            foreach ($arrModules as $arrModule) {
                if (is_array($arrRoot) && count($arrRoot) > 0 && !in_array($arrModule ['jumpTo'], $arrRoot)) {
                    continue;
                }

                $objParent = \PageModel::findWithDetails($arrModule ['jumpTo']);
                // The target page does not exist
                if ($objParent === null) {
                    continue;
                }

                // The target page has not been published (see #5520)
                if (!$objParent->published) {
                    continue;
                }

                // The target page is exempt from the sitemap (see #6418)
                if ($blnIsSitemap && $objParent->sitemap == 'map_never') {
                    continue;
                }

                // Set the domain (see #6421)
                $domain = ($objParent->rootUseSSL ? 'https://' : 'http://') . ($objParent->domain ?: \Environment::get('host')) . TL_PATH . '/';

                $arrPids [] = $arrModule ['company_archiv'];
                $objCompanies = \CompanyModel::findByPids($arrPids, 0, 0, array(
                    'order' => 'id ASC'
                ));
                while ($objCompanies->next()) {
                    $arrCompany = $objCompanies->row();
                    $arrPages [] = $domain . $this->generateFrontendUrl($objParent->row(),
                            '/companyID/' . $arrCompany ['id'], $objParent->language);
                }
            }
        }

        return $arrPages;
    }

    public function exportCSV()
    {
        $arrColumns = array(
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
        $arrCsv = array();
        foreach ($arrColumns as $strColumn) {
            $arrCsv[0][] = $GLOBALS['TL_LANG']['tl_company'][$strColumn][0];
        }

        $intPid = \Input::get('id');
        $objCompanyArchive = \CompanyArchiveModel::findByPk($intPid);
        $objCompany = \CompanyModel::findBy('pid', $intPid);
        if ($objCompany) {
            while ($objCompany->next()) {
                $arrTemp = array();
                foreach ($arrColumns as $strColumn) {
                    if ($strColumn == 'logo') {
                        $arrTemp[] = \FilesModel::findByUuid($objCompany->$strColumn)->name;
                    } else {
                        $arrTemp[] = $objCompany->$strColumn;
                    }
                }
                $arrCsv[] = $arrTemp;
            }
        }

        ob_start();
        $objFile = fopen("php://output", 'w');
        fputcsv($objFile, $this->arrFields);
        foreach ($arrCsv as $arrRow) {
            fputcsv($objFile, $arrRow);
        }
        fclose($objFile);
        $strCsv = ob_get_clean();

        header("Content-Type: application/force-download");
        header("Content-Disposition: attachment; filename=\"" . $objCompanyArchive->title . ".csv\"");
        header("Content-Length: " . strlen($strCsv));

        echo $strCsv;
        exit();

    }
}
