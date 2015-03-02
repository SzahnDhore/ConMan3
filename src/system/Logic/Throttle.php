<?php

namespace Szandor\ConMan\Logic;

use \Szandor\ConMan\Data as Data;
use \Szandor\ConMan\Logic as Logic;
use \Szandor\ConMan\View as View;

/**
 * The throttle is a way of making sure that several requests cannot run at the same time. This makes it so that several
 * login attempts cannot be tried at the same time, even when running different sessions. Different channels can be used
 * for requests that are not allowed to be stacked, but are allowed to run alongside other requests.
 */
class Throttle
{
    /**
     * Default settings for the class.
     */
    const DEFAULT_CHANNEL = 'default';
    const DEFAULT_TIME_IN_SECONDS = 0.5;

    /**
     * Begins a request to do something using the throttle function. If a request is already in progress, the request is
     * halted for a short time and then tries again.
     */
    public static function request($channel = self::DEFAULT_CHANNEL, $seconds = self::DEFAULT_TIME_IN_SECONDS)
    {
        $cleared = false;

        $check_throttle_query = array(
            'table' => 'data_throttling',
            'limit' => 1,
            'where' => array(
                'col' => 'channel',
                'values' => $channel,
            ),
            'orderby' => 'in_progress_since',
            'desc' => true,
        );

        while ($cleared === false) {
            $check_throttle = Data\Database::read($check_throttle_query, false);

            // print_r($check_throttle);

            if (isset($check_throttle[0])) {
                $time_last = strtotime($check_throttle[0]['in_progress_since']);
                $time_now = time();
                $time_since = $time_now - $time_last;
            } else {
                $time_since = $seconds + 1;
            }

            if ($time_since > $seconds) {
                $sign_throttle_query['table'] = 'data_throttling';
                $sign_throttle_query['values'] = array(
                    'in_progress_since' => date("Y-m-d H:i:s", time()),
                    'channel' => $channel
                );
                $sign_throttle_query['data'] = array($sign_throttle_query['values']);
                $sign_throttle_query['id'] = (isset($check_throttle[0]['data_throttling_id']) ? $check_throttle[0]['data_throttling_id'] : false);

                if ($sign_throttle_query['id'] === false) {
                    Data\Database::create($sign_throttle_query, false);
                } else {
                    Data\Database::update($sign_throttle_query, false);
                }

                $cleared = true;
            }

            usleep($seconds * 1000000);
            // $n = (isset($n) ? $n + 1 : 1);
            // $cleared = ($n > 0 ? true : false);
        }
    }

    /**
     * Releases a request so that other functions may access it.
     */
    public static function release($channel = self::DEFAULT_CHANNEL)
    {
    }

}
