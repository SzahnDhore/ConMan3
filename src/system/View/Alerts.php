<?php

namespace Szandor\ConMan\View;

use \Szandor\ConMan\Data as Data;
use \Szandor\ConMan\Logic as Logic;
use \Szandor\ConMan\View as View;

require_once 'lang/class.language.php';

use \HolQaH as HolQaH;

/**
 * This class gathers and displays system alerts.
 */
class Alerts
{
    private static $alert_types = array(
        'success',
        'info',
        'warning',
        'danger',
    );

    /**
     * Sets an alert to be displayed.
     *
     * This function sets an alert that will be displayed when using `display()`. You need to provide an alert type and a
     * text for each alert and, if you've set `$dismiss` to 'no', also an alert_id.
     *
     * The types are based on the Twitter Bootstrap framework. If you add more types you should make sure to have
     * corresponding CSS to style them. Text can be any relevant text. Basic text formatting and inline links are OK too.
     * If a title is specified it will be used, otherwise the type is used.
     *
     * $dismiss decides how the alert is dismissed. You can set it to one of three different strings:
     * * 'click' will make the alert go away when it is clicked or when the page is refreshed.
     * * 'auto' will show the alert for 5 seconds and then dismiss it automatically.
     * * 'no' will display the alert until it is specifically deleted by the `delete()`-function.
     *
     * $alert_id is a string that uniquely identifies the alert. It is really only needed when deleting an alert.
     *
     * @param $type string  What type of alert to set.
     * @param $text string  The text that is shown in the alert.
     * @param $dismiss string  How the alert is dismissed. See text above for more details.
     * @param $title string  The title of the alert. Will default to the alert type.
     * @param $alert_id string  The ID of the alert. Needed when setting persistent alerts.
     *
     * @return void
     */
    public static function set($type, $text, $dismiss = false, $title = false, $alert_id = '')
    {
        if (in_array($type, self::$alert_types)) {
            $lang = new HolQaH\Language;
            $alert['text'] = $text;
            $alert['dismiss'] = ($dismiss === 'click' || $dismiss === 'auto' || $dismiss === 'no' ? ($dismiss === 'no' && $alert_id == '' ? 'click' : $dismiss) : 'click');
            $alert['title'] = ($title ? $title : $lang->phrase('alert_' . $type));
            $alert_id = ($alert_id == '' ? $alert_id : 'ID:' . $alert_id);

            if ($alert_id != '') {
                $_SESSION['system_alerts'][$type][$alert_id] = $alert;
            } else {
                $_SESSION['system_alerts'][$type][] = $alert;
            }
        }
    }

    /**
     * Deletes an alert by ID.
     *
     * @param $alert_id string  The ID of the alert to delete.
     * @param $delete_all bool  If this is set to true, all alerts will be deleted.
     *
     * @return void
     */
    public static function delete($alert_id = '', $delete_all = false)
    {
        if ($delete_all === true) {
            unset($_SESSION['system_alerts']);
        } elseif ($alert_id != '') {
            foreach ($_SESSION['system_alerts'] as $type => $messages) {
                if (isset($_SESSION['system_alerts'][$type]['ID:' . $alert_id])) {
                    unset($_SESSION['system_alerts'][$type]['ID:' . $alert_id]);
                }
            }
        }
    }

    /**
     * Displays alerts.
     *
     * @param void
     *
     * @return string  The HTML for the alerts.
     */
    public static function display()
    {
        if (isset($_SESSION['system_alerts']) && is_array($_SESSION['system_alerts'])) {

            if (Data\Toolbox::hasArray($_SESSION['system_alerts'])) {

                foreach ($_SESSION['system_alerts'] as $type => $messages) {
                    if (Data\Toolbox::hasArray($messages)) {
                        $has_alerts[$type] = true;
                    } else {
                        $has_alerts[$type] = (isset($has_alerts[$type]) ? $has_alerts[$type] : false);
                    }
                }

                foreach ($has_alerts as $set_type => $set) {
                    if ($set === false) {
                        unset($_SESSION['system_alerts'][$set_type]);
                    }
                }
            }

            if (!Data\Toolbox::hasArray($_SESSION['system_alerts'])) {
                unset($_SESSION['system_alerts']);
            } else {

                $out = "\n" . '<div id="system_alerts" class="modal fade in col-xs-12 col-sm-8 col-sm-offset-2 col-lg-6 col-lg-offset-3" aria-hidden="false" style="display: block;">' . "\n";
                foreach ($_SESSION['system_alerts'] as $type => $messages) {
                    foreach ($messages as $number => $message) {
                        $out .= "\t" . '<div class="alert alert-' . $type . ' alert-' . ($message['dismiss'] === 'auto' ? 'auto' : ($message['dismiss'] === 'no' ? 'no' : 'dismissable')) . ' panel-collapse in">' . "\n";
                        // $out .= ($message['auto'] === true ? '' : "\t\t" . '<button type="button" class="close"
                        // aria-hidden="true">&times;</button>' . "\n");
                        $out .= "\t\t" . '<strong>' . $message['title'] . '</strong> &nbsp; ' . $message['text'] . "\n";
                        $out .= "\t" . '</div>' . "\n";

                        if ($message['dismiss'] === 'no') {
                        } else {
                            unset($_SESSION['system_alerts'][$type][$number]);
                        }
                    }
                }

                $out .= '</div>' . "\n";
                return $out;
            }
        }
    }

    /**
     * Prints out the JavaScript that handles dismissing the alerts.
     */
    public static function javaScript()
    {
        if (isset($_SESSION['system_alerts']) && is_array($_SESSION['system_alerts'])) {
            return '<script type="text/javascript">
            window.setTimeout(function() {
                $(".alert-auto").hide("drop");
            }, 5000);
            $(".alert-dismissable").click(function () {
                $(this).hide("drop");
            });
        </script>
        <script>$("#system_alerts").modal();</script>';
        }
    }

}
