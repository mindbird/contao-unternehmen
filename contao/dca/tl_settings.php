<?php

$GLOBALS['TL_DCA']['tl_settings']['palettes']['default'] .= ';{company_legend:hide},company_googlemaps_apikey';

$GLOBALS['TL_DCA']['tl_settings']['fields']['company_googlemaps_apikey'] = array
(
    'label' => &$GLOBALS['TL_LANG']['tl_settings']['company_googlemaps_apikey'],
    'inputType' => 'text',
    'eval'      => array('tl_class' => 'w50')
);