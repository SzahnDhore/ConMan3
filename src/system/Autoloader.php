<?php

namespace Szandor\ConMan;

use \Szandor\ConMan\Data as Data;
use \Szandor\ConMan\Logic as Logic;
use \Szandor\ConMan\View as View;

spl_autoload_register('Szandor\ConMan\Autoloader');

function Autoloader($class)
{
    $system_pre = __NAMESPACE__ . '\\';
    $system_dir = Data\Settings::main('system_url');

    $len = strlen($system_pre);
    if (strncmp($system_pre, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $file = $system_dir . str_replace('\\', DIRECTORY_SEPARATOR, $relative_class) . '.php';

    if (is_readable($file)) {
        $match = true;
        require_once $file;
    } else {
        $match = false;
    }
}
