<?php

namespace Szandor\ConMan\Data;

use \Szandor\ConMan\Data as Data;
use \Szandor\ConMan\Logic as Logic;
use \Szandor\ConMan\View as View;

/**
 *
 */
class Content
{

    public function getContent($page = '')
    {
        $default_page = Data\Settings::main('error_page');
        $page_id = ($page == '' ? $default_page : $page);

        // --- The following will be replaced with a database call later on.
        $file = __DIR__ . DIRECTORY_SEPARATOR . 'content' . DIRECTORY_SEPARATOR . $page_id . '.php';

        if (is_readable($file)) {
            require_once $file;
        } else {
            $file = __DIR__ . DIRECTORY_SEPARATOR . 'content' . DIRECTORY_SEPARATOR . $default_page . '.php';
            require_once $file;
        }
        $out = (isset($contents) ? $contents : false);

        return $out;
    }

    public function getTemplate($template = false)
    {
        $template = 'default';

        $out = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . $template . '.tpl');
        return $out;
    }

}
