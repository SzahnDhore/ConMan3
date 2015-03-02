<?php

namespace Szandor\ConMan\Data;

use \Szandor\ConMan\Data as Data;
use \Szandor\ConMan\Logic as Logic;
use \Szandor\ConMan\View as View;

/**
 * Various tools.
 */
class Toolbox
{

    public static function hasArray($array)
    {
        if (is_array($array)) {
            foreach ($array as $value) {
                if (is_array($value)) {
                    return true;
                }
            }
            return false;
        }
        return false;
    }

    public static function numberToWeekday($number, $first_letter_cap = false, $short_name = false)
    {
        $sn = ($short_name === true ? 1 : 0);

        $weekdays = array(
            0 => array(
                0 => 'söndag',
                1 => 'sön',
            ),
            1 => array(
                0 => 'måndag',
                1 => 'mån',
            ),
            2 => array(
                0 => 'tisdag',
                1 => 'tis',
            ),
            3 => array(
                0 => 'onsdag',
                1 => 'ons',
            ),
            4 => array(
                0 => 'torsdag',
                1 => 'tor',
            ),
            5 => array(
                0 => 'fredag',
                1 => 'fre',
            ),
            6 => array(
                0 => 'lördag',
                1 => 'lör',
            ),
        );

        $out = ($first_letter_cap ? ucfirst($weekdays[$number][$sn]) : $weekdays[$number][$sn]);

        return $out;
    }

}
