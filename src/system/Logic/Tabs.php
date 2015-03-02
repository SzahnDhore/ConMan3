<?php

namespace Szandor\ConMan\Logic;

use \Szandor\ConMan\Data as Data;
use \Szandor\ConMan\Logic as Logic;
use \Szandor\ConMan\View as View;

/**
 *
 */
class Tabs
{
    /**
     * Default settings for the class.
     */
    const DEFAULT_TAB = '    ';

    /**
     * This function returns an array with tabs for HTML output. This makes it easy to format nice HTML.
     */
    public static function get_array($tab = self::DEFAULT_TAB, $level = 0)
    {
        $level = $level * 1;

        $out['one_tab'] = $tab;
        $out[0] = str_repeat($tab, $level);
        for ($i = 0; $i < 20; $i++) {
            $out[$i + 1] = str_repeat($tab, $level + $i + 1);
        }

        return $out;
    }

}
