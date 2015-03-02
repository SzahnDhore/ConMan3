<?php
/**
 * HybridAuth
 * http://hybridauth.sourceforge.net | http://github.com/hybridauth/hybridauth
 * (c) 2009-2014, HybridAuth authors | http://hybridauth.sourceforge.net/licenses.html
 */

// ----------------------------------------------------------------------------------------
//	HybridAuth Config file: http://hybridauth.sourceforge.net/userguide/Configuration.html
// ----------------------------------------------------------------------------------------

require_once ('system/Data/Settings.php');

return array(
    "base_url" => Szandor\ConMan\Data\Settings::main('base_url') . "lib/hybridauth/",

    "providers" => array(
        // openid providers
        "OpenID" => array("enabled" => false),

        "Yahoo" => array(
            "enabled" => false,
            "keys" => array(
                "key" => "",
                "secret" => "",
            ),
        ),

        "AOL" => array("enabled" => false),

        "Google" => array(
            "enabled" => true,
            "keys" => array(
                "id" => "838834214219-n5uao3t6f4g5gaibm56bno44qj6n419a.apps.googleusercontent.com",
                "secret" => "keoFYlu2BLqUTgqc65pmtXYr",
                "scope" => "https://www.googleapis.com/auth/userinfo.profile",
            ),
        ),

        "Facebook" => array(
            "enabled" => true,
            "keys" => array(
                "id" => "937754216249917",
                "secret" => "e07759669c0a37718d289219d16e0d1e",
            ),
            "trustForwarded" => false,
            "scope" => "public_profile, email",
        ),

        "Twitter" => array(
            "enabled" => false,
            "keys" => array(
                "key" => "m8MCIYMmTorM96b4fEgmfw",
                "secret" => "prTJeC3dfQS8FGkjS4SKZGUjjWT4APmyKkO1b0OfA",
            )
        ),

        // windows live
        "Live" => array(
            "enabled" => false,
            "keys" => array(
                "id" => "",
                "secret" => "",
            )
        ),

        "LinkedIn" => array(
            "enabled" => false,
            "keys" => array(
                "key" => "",
                "secret" => "",
            )
        ),

        "Foursquare" => array(
            "enabled" => false,
            "keys" => array(
                "id" => "",
                "secret" => "",
            )
        ),
    ),

    // If you want to enable logging, set 'debug_mode' to true.
    // You can also set it to
    // - "error" To log only error messages. Useful in production
    // - "info" To log info and error messages (ignore debug messages)
    "debug_mode" => false,

    // Path to file writable by the web server. Required if 'debug_mode' is not false
    "debug_file" => "",
);
