<?php

namespace Szandor\ConMan\Data;

use \Szandor\ConMan\Data as Data;
use \Szandor\ConMan\Logic as Logic;
use \Szandor\ConMan\View as View;

/**
 * Global settings for the system.
 */
class Settings
{

    /**
     * Main settings for the system.
     *
     * All system-wide non-specific settings goes here.
     *
     * @param string $setting Name of the setting you want to return.
     * @return string Returns the specified setting value or, if this fails, false.
     */
    public static function main($requested = '')
    {
        // --- Name of the default page.
        $settings['default_page'] = 1;

        // --- Name of the error page.
        $settings['error_page'] = '404';

        // --- String that equals one tab.
        $settings['tab'] = '    ';

        // --- Time in seconds allowed between user actions.
        $settings['logout_time'] = 360;

        // --- Base URL of the system. Should normally not be changed, but if problems arise it can be done here.
        $settings['base_url'] = rtrim('http://' . $_SERVER['SERVER_NAME'] . $_SERVER['SCRIPT_NAME'], substr(strrchr($_SERVER['PHP_SELF'], '/'), 1));

        // --- URL of the system folder, relative to the base URL above.
        $settings['system_url'] = 'system' . DIRECTORY_SEPARATOR;

        $settings['project_folder'] = preg_replace('/system\\' . DIRECTORY_SEPARATOR . 'Data$/', '', __DIR__);

        return ($requested == '' ? $settings : (array_key_exists($requested, $settings) ? $settings[$requested] : false));
    }

    /**
     * All settings related to the database.
     *
     * @param string $setting Name of the setting you want to return.
     * @return string Returns the specified setting value or, if this fails, false.
     */
    public static function db($requested = '')
    {
        // --- If the server is on a specific host, these settings are used....
        if ($_SERVER['HTTP_HOST'] == 'host_address') {

            // --- Hostname of the database.
            $settings['host'] = 'host';

            // --- Database/schema name.
            $settings['dbname'] = 'database';

            // --- Prefix for all tables related to ConMan3.
            $settings['prefix'] = 'szcm3_';

            // --- Default limit for read requests.
            $settings['default_limit'] = 9999;

            // --- Username for the database.
            $settings['username'] = 'username';

            // --- Password for the database.
            $settings['password'] = 'password';

            // --- If not, we assume that localhost is used.
        } else {

            $settings['host'] = 'localhost';
            $settings['dbname'] = 'conman3';
            $settings['prefix'] = 'szcm3_';
            $settings['default_limit'] = 9999;
            $settings['username'] = 'root';
            $settings['password'] = '';

        }

        return ($requested == '' ? $settings : (array_key_exists($requested, $settings) ? $settings[$requested] : false));
    }

    /**
     * All settings related to the mailsender.
     *
     * @param string $setting Name of the setting you want to return.
     * @return string Returns the specified setting value or, if this fails, false.
     */
    public static function mailsender($requested = '')
    {
        // --- If the server is on a specific host, these settings are used....
        if ($_SERVER['HTTP_HOST'] == 'host_address') {

            // --- Hostname of the SMTP server.
            $settings['host'] = 'host';

            // --- SMTP port.
            $settings['port'] = 'port';

            // --- Username for the SMTP server.
            $settings['username'] = 'username';

            // --- Password for the SMTP server.
            $settings['password'] = 'password';

            // --- The sender email address.
            $settings['from_address'] = 'sender';

            // --- The sender name.
            $settings['from_name'] = 'name';

            // --- The email to admin.
            $settings['admin_address'] = 'admin@localhost.com';
            
            // --- If not, we assume that localhost is used.
        } else {

            $settings['host'] = '';
            $settings['port'] = '';
            $settings['username'] = '';
            $settings['password'] = '';
            $settings['from_address'] = '';
            $settings['from_name'] = '';
            $settings['admin_address'] = '';

        }

        return ($requested == '' ? $settings : (array_key_exists($requested, $settings) ? $settings[$requested] : false));
    }

}
