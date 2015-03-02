<?php

namespace Szandor\ConMan\Data;

use \Szandor\ConMan\Data as Data;
use \Szandor\ConMan\Logic as Logic;
use \Szandor\ConMan\View as View;

/**
 *
 */
class URL
{
    public function getPathArray($query_string = '')
    {
        $query_string = ($query_string == '' ? $_SERVER['QUERY_STRING'] : $query_string);

        parse_str($query_string, $query_array);
        $out = $query_array;

        return $out;
    }

    public function getPageID($query_string = '', $page_variable = '')
    {
        $default_page = Data\Settings::main('default_page');

        $query_string = ($query_string == '' ? $_SERVER['QUERY_STRING'] : $query_string);
        $page_variable = ($page_variable == '' ? 'page' : $page_variable);

        parse_str($query_string, $query_array);
        $out = (isset($query_array[$page_variable]) ? $query_array[$page_variable] : $default_page);

        return $out;
    }

}
