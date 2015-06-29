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
    
    public static function getEntranceContentForRegistrationPage($registration_id = 0)
    {
        // Since the Database class does not support joins and advanced queries, we have
        // to make multiple calls to the database...
        $reg_per_id = 0;
        if (is_numeric($registration_id) && $registration_id > 0)
        {
            $db_request = array(
                'table' => 'convention_registration_form',
                'select' => 'belongs_to_registration_period',
                'limit' => 1,
                'where' => array(
                    'col' => 'convention_registration_form_id',
                    'values' => $registration_id
                )
            );
            $registration_form_id = Data\Database::read($db_request, false);
            $reg_per_id = $registration_form_id[0]['belongs_to_registration_period'];
        }
        else
        {
            $this_registration_period_request = array(
                'table' => 'convention_registration_periods',
                'select' => 'convention_registration_periods_id',
                'limit' => 1,
                'where' => array(
                    'col' => 'last_registration_date',
                    'query' => 'between',
                    'values' => array(
                        date('Y-m-d H:i:s'), date('Y-m-d H:i:s', strtotime('+1 year'))
                    ),
                ),
                'orderby' => 'last_registration_date'
            );
            $registration_form_id = Data\Database::read($this_registration_period_request, false);
            if (empty($registration_form_id)) { return ''; }
            $reg_per_id = $registration_form_id[0]['convention_registration_periods_id'];
        }

        $this_registration_form_request = array(
            'table' => 'convention_registration_form',
            'where' => array(
                'col' => 'belongs_to_registration_period',
                'values' => $reg_per_id,
            )
        );
        $result = Data\Database::read($this_registration_form_request, false);
        
        $out = array();
        foreach ($result as $entrance_type)
        {
            $out[] = '[\'' . $entrance_type['convention_registration_form_id'] . '\', '
                        . '\'' . $entrance_type['description'] . '\', '
                        . $entrance_type['if_member_price_reduced_by'] . ', '
                        . $entrance_type['price'] . ']';
        }

        return $out;
    }

}
