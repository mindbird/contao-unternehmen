<?php

use Contao\Input;

if (Input::get('do') === 'company') {
    $GLOBALS['TL_DCA']['tl_content']['config']['ptable'] = 'tl_company';
}
