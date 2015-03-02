<?php

namespace Szandor\ConMan\Data;

use \Szandor\ConMan\Data as Data;
use \Szandor\ConMan\Logic as Logic;
use \Szandor\ConMan\View as View;

require_once 'lang/class.language.php';

use \HolQaH as HolQaH;

/**
 *
 */
class Login
{
    public function __construct()
    {
        $this->config = Data\Settings::main('project_folder') . 'lib/hybridauth/config.php';
        require_once (Data\Settings::main('project_folder') . 'lib/hybridauth/Hybrid/Auth.php');
        $this->ha = new \Hybrid_Auth($this->config);
    }

    /**
     * Does login-related checks each time the function is called, which should be once on top of every page. Since
     * index.php is our Single Point of Entry we only need to call it from there.
     */
    public function check()
    {
        // --- Makes sure a session is always in progress.
        if (!isset($_SESSION['session_has_started']) || $_SESSION['session_has_started'] != true) {
            @ session_start();
            session_regenerate_id();
            $_SESSION['session_has_started'] = true;
        } else {
            if (session_status() == PHP_SESSION_NONE) {
                @ session_start();
            }
        }

        // --- Checks if a logout has been requested.
        if (isset($_GET['logout']) || isset($_POST['logout'])) {
            $this->doLogout();
            header('Location: ' . Data\Settings::main('base_url'));
            exit ;
        }

        // --- Checks if the user is logged in.
        $logged_in = (isset($_SESSION['user']['user_is_logged_in']) && $_SESSION['user']['user_is_logged_in'] === true ? true : false);

        // --- Checks for how long the user has been logged in since the last action.
        if ($logged_in) {
            if (isset($_SESSION['user']['last_action_timestamp'])) {
                $elapsed = time() - $_SESSION['user']['last_action_timestamp'];
                $timeout = Data\Settings::main('logout_time') * 60;
                if ($elapsed > $timeout) {
                    $this->doLogout();
                    View\Alerts::set('warning', 'Du har har blivit utloggad på grund av inaktivitet.');
                    header('Location: ' . Data\Settings::main('base_url'));
                    exit ;
                }
            }
            $_SESSION['user']['last_action_timestamp'] = time();
        }

        return $logged_in;
    }

    /**
     * Checks to see if the user is logged in or not.
     */
    public static function userIsLoggedIn()
    {
        return (isset($_SESSION['user']['user_is_logged_in']) && $_SESSION['user']['user_is_logged_in'] === true ? true : false);
    }

    /**
     * Logs in through social media.
     */
    public function socialLogin($provider, $params = null)
    {
        // --- To make it harder for evil people to crack the website the script pauses for a short time here.
        usleep(500000);
        $t = $this->ha->authenticate($provider, $params);
        $user_profile = $t->getUserProfile();

        return $user_profile;
    }

    /**
     * Tries to log in the user.
     *
     * This might be a bit too hard-coded at the moment, but it will be made more flexible in time.
     */
    public static function tryLogin($user, $pass)
    {
        // --- If the user is already logged in, nothing happens.
        if (isset($_SESSION['user']['user_is_logged_in']) && $_SESSION['user']['user_is_logged_in'] === true) {
            View\Alerts::set('info', $user . ' är redan inloggad.');
        } else {
            // --- If either user or password is empty we skip the login check.
            if ($user == '' || $pass == '') {
                if ($user == '') {
                    View\Alerts::set('warning', 'Du måste ange ett användarnamn.');
                }
                if ($pass == '') {
                    View\Alerts::set('warning', 'Du måste ange ett lösenord.');
                }
            } else {
                // --- To make it harder for evil people to crack the website the script pauses for a short time here.
                usleep(500000);
                // --- The provided password must be checked against the password hash in the database.
                $password = new Logic\Password;
                $check_pass = $password->checkPass($user, $pass);
                if ($check_pass !== false) {
                    // --- If it checks out the user is logged in.
                    self::doLogin($check_pass);
                    return true;
                } else {
                    // --- If not, we make sure the user is logged out.
                    View\Alerts::set('danger', 'The provided username/email and password did not match. Please make sure you entered them correctly and try again.');
                    return false;
                }
            }
        }
    }

    /**
     * This checks access rights.
     *
     * It's much too simplistic at the moment. It will be rewritten to work with more advanced user rights and it will
     * probably be placed in the `User` class instead.
     */
    public static function access($required_role)
    {
        $actual_role = (self::userIsLoggedIn() ? 'user' : 'visitor');

        if ($required_role == 'visitor' || $actual_role === $required_role) {
            return true;
        } else {
            $base_url = Settings::main('base_url');
            View\Alerts::set('warning', 'Sorry, but you don\'t have access to that page.', 'Access denied.');
            header('Location: ' . $base_url);
        }
    }

    /**
     * Completely nukes the current session, effectively logging the user out.
     */
    public function doLogout()
    {
        $this->ha->logoutAllProviders();
        $_SESSION['user']['user_is_logged_in'] = false;
        unset($_SESSION);
        session_destroy();        session_start();
    }

    /**
     * Logs in the user.
     */
    public static function doLogin($username)
    {
        $user = new Data\User;
        $user_id = $user->username2ID($username);
        session_regenerate_id();
        $_SESSION['user']['user_is_logged_in'] = true;
        $_SESSION['user']['info'] = $user->getData($user_id);
        $_SESSION['user']['last_action_timestamp'] = time();
    }

}
