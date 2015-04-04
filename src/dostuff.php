<?php

namespace Szandor\ConMan;

use \Szandor\ConMan\Data as Data;
use \Szandor\ConMan\Logic as Logic;
use \Szandor\ConMan\View as View;

session_start();

if (isset($_GET['resetsession'])) {
    session_destroy();
    unset($_SESSION);
}

require_once 'system/Data/Settings.php';
// require_once 'lang/class.language.php';
require_once Data\Settings::main('system_url') . 'Autoloader.php';

/**
 * Feltestnivå:
 *   `0` = ingen feltestning
 *   `1` = feltestning
 *   `2` = feltestning med databashantering
 */

$debug = 0;

error_reporting($debug > 0 ? -1 : 0);
$use_db = ($debug === 1 ? false : true);

if (isset($_GET['submit_dostuff'])) {
    $_POST['submit_dostuff'] = $_GET['submit_dostuff'];
}

switch ($_POST['submit_dostuff']) {

    case 'event_new' :
        $go_to_page = Dostuff::event_new();
        break;

    case 'event_edit' :
        $go_to_page = Dostuff::event_edit();
        break;

    case 'event_status_godkann' :
        $event_id = Dostuff::event_status_godkann();
        $go_to_page = 'index.php?page=eventinfo&event_id=' . $event_id;
        break;

    case 'event_status_ej_godkann' :
        $event_id = Dostuff::event_status_godkann(true);
        $go_to_page = 'index.php?page=eventinfo&event_id=' . $event_id;
        break;

    case 'event_delete' :
        Dostuff::event_delete(true);
        $go_to_page = 'index.php?page=arrangemang';
        break;

    case 'event_add_occassion_submit' :
        $event_id = Dostuff::event_add_occassion_submit(true);
        $go_to_page = 'index.php?page=eventinfo&event_id=' . $event_id;
        break;

    case 'new_user_submit' :
        $go_to_page = Dostuff::new_user_submit();
        break;

    case 'user_login_submit' :
        $go_to_page = Dostuff::user_login_submit();
        break;

    case 'social_login_return' :
        Dostuff::social_login($_GET['provider']);
        $go_to_page = 'index.php?page=arrangemang';
        break;

    case 'social_login_google' :
        Dostuff::social_login('Google');
        $go_to_page = 'index.php?page=arrangemang';
        break;

    case 'social_login_twitter' :
        Dostuff::social_login('Twitter');
        $go_to_page = 'index.php?page=arrangemang';
        break;

    case 'social_login_facebook' :
        Dostuff::social_login('Facebook');
        $go_to_page = 'index.php?page=arrangemang';
        break;

    case 'update_profile' :
        Dostuff::update_profile();
        $go_to_page = 'index.php?page=minprofil';
        break;

    case 'register_convention' :
        Dostuff::register_convention();
        $go_to_page = 'index.php?page=anmalningar';
        break;

        default :
        break;
}

/**
 * En klass för de olika grejerna som kan göras.
 */
class Dostuff
{
    public static function event_new()
    {
        $event_title_request = array(
            'table' => 'events',
            'limit' => 1,
            'where' => array(
                'query' => 'and',
                'col' => array(
                    'title',
                    'functions_id',
                ),
                'values' => array(
                    $_POST['event_edit_title'],
                    '1',
                ),
            ),
        );
        $event_title_check = Data\Database::read($event_title_request, false);

        $validation['event_edit_title_duplicate'] = (isset($event_title_check[0]) ? false : true);
        $validation['event_edit_title_length'] = (strlen($_POST['event_edit_title']) > 0 ? true : false);
        $validation['event_edit_type'] = (isset($_POST['event_edit_type']) ? true : false);
        $validation['event_edit_short_description'] = (strlen($_POST['event_edit_short_description']) > 0 ? true : false);
        $validation['event_edit_long_description'] = (strlen($_POST['event_edit_long_description']) > 0 ? true : false);
        $validation['event_edit_long_description_shorter'] = (strlen($_POST['event_edit_short_description']) < strlen($_POST['event_edit_long_description']) ? true : false);
        $validation['event_edit_email'] = (filter_var($_POST['event_edit_email'], FILTER_VALIDATE_EMAIL) === false ? false : true);

        $error_text['event_edit_title_duplicate'] = 'Ett arrangemang med det namnet finns redan. Var god välj ett annat.';
        $error_text['event_edit_title_length'] = 'Du verkar inte ha skrivit in något namn på arrangemanget.';
        $error_text['event_edit_type'] = 'Du måste ange vilken typ av arrangemang det är.';
        $error_text['event_edit_short_description'] = 'Du verkar inte ha skrivit en kort beskrivning av arrangemanget.';
        $error_text['event_edit_long_description'] = 'Du verkar inte ha skrivit en längre beskrivning av arrangemanget.';
        $error_text['event_edit_long_description_shorter'] = 'Din långa beskrivning verkar vara kortare än din långa.';
        $error_text['event_edit_email'] = 'Den angivna epostadressen verkar inte vara giltig.';

        foreach ($validation as $item => $value) {
            $valid = (isset($valid) ? $valid : true);
            $valid = ($valid === false ? false : $value);

            if ($value === false) {
                View\Alerts::set('warning', $error_text[$item]);
                $errors[$item] = true;
            }
        }

        $return_url = '';
        if (!$valid) {
            foreach ($error_text as $type => $value) {
                $return_url .= (isset($errors[$type]) ? '&form_error_' . $type . '=1' : '');
            }

            foreach ($_POST as $key => $value) {
                if ($key === 'event_id' || $key === 'submit_dostuff') {
                } else {
                    $return_url .= '&form_data_' . $key . '=' . $value;
                }
            }
        } else {
            $return_url = '&new_user_submit_success=true';
        }

        if ($valid) {
            $request = array(
                'table' => 'events',
                'data' => array( array(
                        'title' => $_POST['event_edit_title'],
                        'short_description' => $_POST['event_edit_short_description'],
                        'long_description' => $_POST['event_edit_long_description'],
                        'event_type' => $_POST['event_edit_type'],
                        'contact' => $_SESSION['user']['info']['data']['id'],
                        'functions_id' => 1,
                        'approved' => 0,
                        'fee' => $_POST['event_edit_fee'],
                        'pre_registration' => (isset($_POST['event_edit_pre_registration']) && $_POST['event_edit_pre_registration'] == '1' ? '1' : '0'),
                        'contact_email' => $_POST['event_edit_email'],
                    )),
            );
            $event_id = Data\Database::create($request, false);
            $go_to_page = 'index.php?page=eventinfo&event_id=' . $event_id;
        } else {
            $go_to_page = 'index.php?page=eventedit&event_id=new' . $return_url;
        }

        return $go_to_page;
    }

    public static function event_edit()
    {
        $event_title_request = array(
            'table' => 'events',
            'limit' => 1,
            'where' => array(
                'query' => 'and',
                'col' => array(
                    'title',
                    'functions_id',
                ),
                'values' => array(
                    $_POST['event_edit_title'],
                    '1',
                ),
            ),
        );
        $event_title_check = Data\Database::read($event_title_request, false);

        $validation['event_edit_title_duplicate'] = (isset($event_title_check[0]) ? false : true);
        $validation['event_edit_title_length'] = (strlen($_POST['event_edit_title']) > 0 ? true : false);
        $validation['event_edit_type'] = (isset($_POST['event_edit_type']) ? true : false);
        $validation['event_edit_short_description'] = (strlen($_POST['event_edit_short_description']) > 0 ? true : false);
        $validation['event_edit_long_description'] = (strlen($_POST['event_edit_long_description']) > 0 ? true : false);
        $validation['event_edit_long_description_shorter'] = (strlen($_POST['event_edit_short_description']) < strlen($_POST['event_edit_long_description']) ? true : false);
        $validation['event_edit_email'] = (filter_var($_POST['event_edit_email'], FILTER_VALIDATE_EMAIL) === false ? false : true);

        $error_text['event_edit_title_duplicate'] = 'Ett arrangemang med det namnet finns redan. Var god välj ett annat.';
        $error_text['event_edit_title_length'] = 'Du verkar inte ha skrivit in något namn på arrangemanget.';
        $error_text['event_edit_type'] = 'Du måste ange vilken typ av arrangemang det är.';
        $error_text['event_edit_short_description'] = 'Du verkar inte ha skrivit en kort beskrivning av arrangemanget.';
        $error_text['event_edit_long_description'] = 'Du verkar inte ha skrivit en längre beskrivning av arrangemanget.';
        $error_text['event_edit_long_description_shorter'] = 'Din långa beskrivning verkar vara kortare än din långa.';
        $error_text['event_edit_email'] = 'Den angivna epostadressen verkar inte vara giltig.';

        foreach ($validation as $item => $value) {
            $valid = (isset($valid) ? $valid : true);
            $valid = ($valid === false ? false : $value);

            if ($value === false) {
                View\Alerts::set('warning', $error_text[$item]);
                $errors[$item] = true;
            }
        }

        $return_url = '';
        if (!$valid) {
            foreach ($error_text as $type => $value) {
                $return_url .= (isset($errors[$type]) ? '&form_error_' . $type . '=1' : '');
            }

            foreach ($_POST as $key => $value) {
                if ($key === 'event_id' || $key === 'submit_dostuff') {
                } else {
                    $return_url .= '&form_data_' . $key . '=' . $value;
                }
            }
        } else {
            $return_url = '&new_user_submit_success=true';
        }

        if ($valid) {
            $request = array(
                'table' => 'events',
                'id' => $_POST['event_id'],
                'values' => array(
                    'title' => $_POST['event_edit_title'],
                    'short_description' => $_POST['event_edit_short_description'],
                    'long_description' => $_POST['event_edit_long_description'],
                    'event_type' => $_POST['event_edit_type'],
                    'contact' => $_SESSION['user']['info']['data']['id'],
                    'fee' => $_POST['event_edit_fee'],
                    'pre_registration' => (isset($_POST['event_edit_pre_registration']) && $_POST['event_edit_pre_registration'] == '1' ? '1' : '0'),
                    'contact_email' => $_POST['event_edit_email'],
                ),
            );
            Data\Database::update($request, false);

            $go_to_page = 'index.php?page=eventinfo&event_id=' . $_POST['event_id'];
        } else {
            $go_to_page = 'index.php?page=eventedit&event_id=new' . $return_url;
        }

        return $go_to_page;
    }

    public static function event_status_godkann($unapprove = false)
    {
        $request = array(
            'table' => 'events',
            'id' => $_POST['events_id'],
            'values' => array('approved' => ($unapprove ? 0 : 1), ),
        );

        Data\Database::update($request, false);

        return $_POST['events_id'];
    }

    public static function event_delete()
    {
        $schedule_find_request = array(
            'table' => 'event_schedule',
            'where' => array(
                'col' => 'events_id',
                'values' => $_POST['events_id'],
            ),
        );
        $schedule_find_results = Data\Database::read($schedule_find_request, false);

        if (isset($schedule_find_results[0])) {
            foreach ($schedule_find_results as $event_occurance) {
                $schedule_request = array(
                    'table' => 'event_schedule',
                    'id' => $event_occurance['event_schedule_id'],
                );
                echo Data\Database::delete($schedule_request, false);
            }
        }

        $event_request = array(
            'table' => 'events',
            'id' => $_POST['events_id'],
        );
        Data\Database::delete($event_request, false);
    }

    public static function event_add_occassion_submit()
    {
        $request = array(
            'table' => 'event_schedule',
            'data' => array( array(
                    'events_id' => $_POST['events_id'],
                    'date_start' => $_POST['event_add_occassion_time_start'],
                    'date_end' => $_POST['event_add_occassion_time_end'],
                    'location' => $_POST['event_add_occassion_location'],
                    'notes' => $_POST['event_add_occassion_notes'],
                    'cancelled' => 0,
                )),
        );
        Data\Database::create($request, false);

        return $_POST['events_id'];
    }

    /**
     * Adds a new user to the system.
     */
    public static function new_user_submit()
    {
        Logic\Throttle::request();

        $users_request = array(
            'table' => 'users',
            'limit' => 1,
            'where' => array(
                'col' => 'username',
                'values' => $_POST['new_user_username'],
            ),
        );
        $user_data_username = Data\Database::read($users_request, false);

        $email_request = array(
            'table' => 'users',
            'limit' => 1,
            'where' => array(
                'col' => 'email',
                'values' => $_POST['new_user_email'],
            ),
        );
        $user_data_email = Data\Database::read($email_request, false);

        $validation['username_duplicate'] = (isset($user_data_username[0]) ? false : true);
        $validation['username_length'] = (strlen($_POST['new_user_username']) > 0 ? true : false);
        $validation['email_duplicate'] = (isset($user_data_email[0]) ? false : true);
        $validation['email_valid'] = (filter_var($_POST['new_user_email'], FILTER_VALIDATE_EMAIL) === false ? false : true);
        $validation['email_match'] = ($_POST['new_user_email'] === $_POST['new_user_website'] ? true : false);
        $validation['password_length'] = (strlen($_POST['new_user_password']) > 4 ? true : false);
        $validation['password_match'] = ($_POST['new_user_password'] === $_POST['new_user_comment'] ? true : false);

        $error_text['username_duplicate'] = 'Användarnamnet är redan taget. Välj ett annat.';
        $error_text['username_length'] = 'Du verkar inte ha angett något användarnamn. Det måste du göra.';
        $error_text['email_duplicate'] = 'En användare med den epostadressen finns redan registrerad.';
        $error_text['email_valid'] = 'Epostadressen du angivit verkar inte vara en korrekt epostadress.';
        $error_text['email_match'] = 'Epostadresserna matchar inte varandra.';
        $error_text['password_length'] = 'Lösenordet måste vara minst 5 tecken långt. Helst skall det vara runt 15 tecken, men du gör som du vill.';
        $error_text['password_match'] = 'Lösenorden matchar inte varandra.';

        foreach ($validation as $item => $value) {
            $valid = (isset($valid) ? $valid : true);
            $valid = ($valid === false ? false : $value);

            if ($value === false) {
                View\Alerts::set('warning', $error_text[$item]);
                $errors[$item] = true;
            }
        }

        $return_url = '';
        if (!$valid) {
            foreach ($error_text as $type => $value) {
                $return_url .= '&form_error_' . $type . '=' . (isset($errors[$type]) ? '1' : '0');
            }

            foreach ($_POST as $key => $value) {
                if ($key === 'new_user_password' || $key === 'new_user_comment' || $key === 'submit_dostuff') {
                } else {
                    $return_url .= '&form_data_' . $key . '=' . $value;
                }
            }
            $return_url = '?page=createnewuser' . $return_url;
        } else {
            $password = new Logic\Password;
            $create_user_request = array(
                'table' => 'users',
                'data' => array( array(
                        'username' => $_POST['new_user_username'],
                        'email' => $_POST['new_user_email'],
                        'password' => $password->hashPass($_POST['new_user_password'])
                    )),
            );
            $user_id = Data\Database::create($create_user_request, false);
            
            $regular_user_group_id = Data\Database::read(array(
                'table' => 'user_groups',
                'select' => 'user_groups_id',
                'limit' => 1,
                'where' => array(
                    'col' => 'description',
                    'values' => 'regular user',
                )), false);
            Data\Database::create(array (
                'table' => 'user_and_group_connection',
                'data' => array(array(
                    'users_id' => $user_id,
                    'user_groups_id' => $regular_user_group_id[0]['user_groups_id']))
            ), false);

            $create_details_request = array(
                'table' => 'user_details',
                'data' => array( array('users_id' => $user_id, )),
            );
            Data\Database::create($create_details_request, false);

            $return_url = '?new_user_submit_success=true';

            Data\Login::tryLogin($_POST['new_user_username'], $_POST['new_user_password']);
            View\Alerts::set('success', 'Du har skapat en ny användare med användarnamn "' . $_POST['new_user_username'] . '". Du är automagiskt inloggad.', 'Lycka och glädje!');
        }

        $go_to_page = 'index.php' . $return_url;
        return $go_to_page;
    }

    public static function user_login_submit()
    {
        $username = $_POST['user_login_username'];
        $password = $_POST['user_login_password'];

        // --- To make it harder for evil people to crack the website the script pauses for a short time here.
        Logic\Throttle::request();

        Data\Login::tryLogin($username, $password);

        $go_to_page = $_POST['user_login_url'];
        return $go_to_page;
    }

    public static function social_login($provider)
    {
        $login = new Data\Login;
        $logged_in = $login->socialLogin($provider, array('hauth_return_to' => Data\Settings::main('base_url') . 'dostuff.php?submit_dostuff=social_login_return&provider=' . $provider . '&hauth.done=' . $provider, ));

        $user_request = array(
            'table' => 'user_social',
            'limit' => 1,
            'where' => array(
                'query' => 'and',
                'col' => array(
                    'provider',
                    'social_id',
                ),
                'values' => array(
                    $provider,
                    $logged_in->identifier,
                ),
            ),
        );

        $user_check = Data\Database::read($user_request, false);
        if (isset($user_check[0])) {
            $username_request = array(
                'table' => 'users',
                'limit' => 1,
                'where' => array(
                    'query' => 'and',
                    'col' => 'users_id',
                    'values' => $user_check[0]['users_id'],
                ),
            );
            $username_check = Data\Database::read($username_request, false);

            $login->doLogin($username_check[0]['username']);
        } else {
            // --- To make it harder for evil people to crack the website the script pauses for a short time here.
            usleep(500000);
            $user['username'] = (!isset($logged_in->username) || $logged_in->username == '' ? $logged_in->displayName : $logged_in->username);
            $user['email'] = ($logged_in->emailVerified == '' ? $logged_in->email : $logged_in->emailVerified);
            $user['given_name'] = $logged_in->firstName;
            $user['family_name'] = $logged_in->lastName;
            $user['address'] = $logged_in->address;
            $user['postal_code'] = $logged_in->zip;
            $user['city'] = $logged_in->city;
            $user['male'] = ($logged_in->gender == 'male' ? '1' : '0');
            $user['national_id_number'] = substr($logged_in->birthYear, -2) . $logged_in->birthMonth . $logged_in->birthDay;
            $user['national_id_number'] = (strlen($user['national_id_number']) === 6 ? $user['national_id_number'] : '');
            $user['country'] = $logged_in->country;
            $user['phone_number'] = $logged_in->phone;
            $user['social_id'] = $logged_in->identifier;
            $user['provider'] = $provider;

            $match = true;

            while ($match === true) {
                $i = (isset($i) ? $i : 1);
                $users_request = array(
                    'table' => 'users',
                    'limit' => 1,
                    'where' => array(
                        'col' => 'username',
                        'values' => $user['username'],
                    ),
                );
                $user_data_username = Data\Database::read($users_request, false);
                if (isset($user_data_username[0])) {
                    $user['username'] = $user['username'] . '_' . $i;
                } else {
                    $match = false;
                }
            }

            $email_request = array(
                'table' => 'users',
                'limit' => 1,
                'where' => array(
                    'col' => 'email',
                    'values' => $user['email'],
                ),
            );
            $user_data_email = Data\Database::read($email_request, false);
            if (isset($user_data_email[0])) {
                View\Alerts::set('warning', 'Det finns redan en användare registrerad med samma epostadress. Kontakta admin om du vill ha hjälp med det här.');
                header('Location: ' . Data\Settings::main('base_url') . 'index.php');
                exit ;
            }

            $create_user_request = array(
                'table' => 'users',
                'data' => array( array(
                        'username' => $user['username'],
                        'email' => ($user['email'] == '' ? null : $user['email'])
                    )),
            );
            $user['user_id'] = Data\Database::create($create_user_request, false);
            
            $regular_user_group_id = Data\Database::read(array(
                'table' => 'user_groups',
                'select' => 'user_groups_id',
                'limit' => 1,
                'where' => array(
                    'col' => 'description',
                    'values' => 'regular user',
                )), false);
            Data\Database::create(array (
                'table' => 'user_and_group_connection',
                'data' => array(array(
                    'users_id' => $user['user_id'],
                    'user_groups_id' => $regular_user_group_id[0]['user_groups_id']))
            ), false);

            $create_details_request = array(
                'table' => 'user_details',
                'data' => array( array(
                        'given_name' => $user['given_name'],
                        'family_name' => $user['family_name'],
                        'address' => $user['address'],
                        'postal_code' => $user['postal_code'],
                        'city' => $user['city'],
                        'male' => $user['male'],
                        'national_id_number' => $user['national_id_number'],
                        'country' => $user['country'],
                        'phone_number' => $user['phone_number'],
                        'users_id' => $user['user_id'],
                    )),
            );
            Data\Database::create($create_details_request, false);

            $create_details_request = array(
                'table' => 'user_social',
                'data' => array( array(
                        'social_id' => $user['social_id'],
                        'provider' => $user['provider'],
                        'users_id' => $user['user_id'],
                    )),
            );
            Data\Database::create($create_details_request, false);
            $login->doLogin($user['username']);
        }
    }

    public static function update_profile()
    {
        $user_email_request = array(
            'table' => 'users',
            'limit' => 1,
            'where' => array(
                'col' => 'email',
                'values' => $_POST['form_profile_email'],
            ),
        );
        $user_email = Data\Database::read($user_email_request, false);

        if (isset($user_email[0]) && $_POST['form_profile_email'] !== $_SESSION['user']['info']['data']['email']) {
            View\Alerts::set('warning', 'Den här epostadressen är redan upptagen.');
        } else {
            $update_email_request = array(
                'table' => 'users',
                'id' => $_SESSION['user']['info']['data']['id'],
                'values' => array('email' => $_POST['form_profile_email'], ),
            );
            Data\Database::update($update_email_request, false);

            // $user_id_request = array(
            // 'table' => 'users',
            // 'limit' => 1,
            // 'where' => array(
            // 'col' => 'email',
            // 'values' => $_POST['form_profile_email'],
            // ),
            // );
            // $user_email = Data\Database::read($user_email_request, false);

            // --- TODO: Add code to find the correct ID for the user_details.

            // $update_profile_request = array(
            // 'table' => 'user_details',
            // 'id' => $_SESSION['user']['info']['data']['id'],
            // 'values' => array(
            // 'given_name' => $_POST['form_profile_given_name'],
            // 'family_name' => $_POST['form_profile_family_name'],
            // 'address' => $_POST['form_profile_address'],
            // 'postal_code' => $_POST['form_profile_postal_code'],
            // 'city' => $_POST['form_profile_city'],
            // 'male' => $_POST['form_profile_gender'],
            // 'national_id_number' => $_POST['form_profile_national_id_number'],
            // 'phone_number' => $_POST['form_profile_phone_number'],
            // ),
            // );
            // Data\Database::update($update_profile_request, false);
        }

        Data\Login::doLogin($_SESSION['user']['info']['data']['username']);
        View\Alerts::set('success', 'Dina personuppgifter har sparats.');

        return true;
    }
    
    public static function register_convention()
    {
        $request = array(
            'table' => 'convention_registrations',
            'data' => array( array(
                    'users_id' => $_SESSION['user']['info']['data']['id'],
                    'entrance_type' => $_POST['entrance_type'],
                    'member' => (isset($_POST['member']) && $_POST['member'] == '1' ? '1' : 0),
                    'mug' => (isset($_POST['mug']) && $_POST['mug'] == '1' ? '1' : 0),
                    'payment_registered' => 0
                )),
            );
        $registration_id = Data\Database::create($request, false);
        if (is_numeric($registration_id))
        {
            Data\MailSender::notifyAdmin("Ny föranmälan", "En ny föranmälan har skapats.");
            View\Alerts::set('success', 'Dina anmälan har registrerats.');
        }
        else if (is_array($registration_id) && $registration_id[1] === 1062)
        {
            Data\MailSender::notifyAdmin("Problem med föranmälan", "Ett försök att skapa en föranmälan har misslyckats. En föranmälan har redan gjorts för users_id: " .$_SESSION['user']['info']['data']['id']);
            View\Alerts::set('warning', 'Din anmälan har redan registrerats. Vänligen kontakta oss om detta är ett fel.');
        }
        else
        {
            View\Alerts::set('info', 'Dina anmälan kunde inte registreras. Försök igen senare eller kontakta oss.');
        }
    }

}

if ($debug === 0) {
    header('Location: ' . Data\Settings::main('base_url') . (isset($go_to_page) ? $go_to_page : 'index.php'));
    exit ;
}

if ($debug > 0) {
    echo '<html>
    <body>
        <pre style="white-space: pre-wrap">' . (!isset($go_to_page) ? '' : '

$go_to_page
---------------------------
<a href="' . Data\Settings::main('base_url') . (isset($go_to_page) ? $go_to_page : 'index.php') . '">' . htmlspecialchars(print_r(Data\Settings::main('base_url') . (isset($go_to_page) ? $go_to_page : 'index.php'), true)) . '</a>

        ') . '

$_GET
---------------------------
' . htmlspecialchars(print_r($_GET, true)) . '



$_POST
---------------------------
' . htmlspecialchars(print_r($_POST, true)) . '



$_SESSION
---------------------------
' . htmlspecialchars(print_r($_SESSION, true)) . '



$_SERVER
---------------------------
' . htmlspecialchars(print_r($_SERVER, true)) . '

---------------------------
Page request time was ' . round(microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'], 2) . ' seconds.
        </pre>
    </body>
</html>
';
}
