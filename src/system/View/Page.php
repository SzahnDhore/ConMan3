<?php

namespace Szandor\ConMan\View;

use \Szandor\ConMan\Data as Data;
use \Szandor\ConMan\Logic as Logic;
use \Szandor\ConMan\View as View;

/**
 *
 */
class Page
{
    public function __construct()
    {
    }

    /**
     * Displays contents of a page.
     */
    public function display($page_content, $login)
    {
        $userIsLoggedIn = $login->userIsLoggedIn();

        $loader = new \Twig_Loader_Filesystem('system/Data/templates');
        $twig = new \Twig_Environment($loader, array(
            'debug' => true,
            'strict_variables' => true,
            'autoescape' => false,
        ));
        $template = $twig->loadTemplate('default.tpl');
        $crr = new Data\MySQLConventionRegistrationRepository();
        $ur = new Data\MySQLUserRepository();

        $page_content['user_logged_in'] = $userIsLoggedIn;
        $page_content['username'] = ($userIsLoggedIn ? $_SESSION['user']['info']['data']['username'] : false);
        $page_content['base_url'] = Data\Settings::main('base_url');
        $page_content['this_url'] = substr(strrchr($_SERVER['REQUEST_URI'], '/'), 1);
        $page_content['show_alerts'] = Alerts::display();
        $page_content['show_alerts_body'] = ($page_content['show_alerts'] == '' ? '' : ' class="modal-open"');
        $page_content['show_alerts_javascript'] = Alerts::javaScript();

        $page_content['show_adminpage_confirm_updated_user_information'] = isset($_SESSION['user']) &&
                                                                            in_array('PERM_COMFIRM_NEW_USER_DETAILS',
                                                                                $_SESSION['user']['info']['permissions']);
        $page_content['show_adminpage_nbr_of_unconfirmed_user_details'] = $ur->getNumberOfUnconfirmedUserDetails();

        $page_content['show_adminpage_confirm_payments'] = isset($_SESSION['user']) &&
                                                            in_array('PERM_COMFIRM_USER_PAYMENTS',
                                                                $_SESSION['user']['info']['permissions']);
        $page_content['show_adminpage_nbr_of_unconfirmed_payments'] = $crr->getNumberOfUnconfirmedPayments();

        $page_content['show_adminpage_confirm_visitor'] = isset($_SESSION['user']) &&
                                                                            in_array('PERM_COMFIRM_VISITOR',
                                                                                $_SESSION['user']['info']['permissions']);

        $page_content['show_adminpage_view_statistics'] = isset($_SESSION['user']) &&
                                                            in_array('stab', $_SESSION['user']['info']['groups']);

        $page_content['show_adminpage_view_users_and_groups'] = isset($_SESSION['user']) &&
                                                            in_array('admin', $_SESSION['user']['info']['groups']);

        $page_content['show_adminpage_view_groups_and_permissions'] = isset($_SESSION['user']) &&
                                                            in_array('admin', $_SESSION['user']['info']['groups']);

        $page_content['show_adminpage_nbr_of_tasks'] = 0;
        if ($page_content['show_adminpage_confirm_updated_user_information'])
        {
            $page_content['show_adminpage_nbr_of_tasks'] += $page_content['show_adminpage_nbr_of_unconfirmed_user_details'];
        }
        if ($page_content['show_adminpage_confirm_payments'])
        {
            $page_content['show_adminpage_nbr_of_tasks'] += $page_content['show_adminpage_nbr_of_unconfirmed_payments'];
        }

        $page_content['show_only_my_profile'] = isset($_SESSION['user']) && !$ur->userHasEnteredUserDetails($_SESSION['user']['info']['data']['id']);

        $this->page_content = $page_content;

        $out = $template->render($page_content);
        // $out = preg_replace_callback('/{{(.+).*}}/', 'self::replaceTemplateTags', $template);

        return $out;
    }

    private function replaceTemplateTags($match)
    {
        $out = (isset($this->page_content[$match[1]]) && $this->page_content[$match[1]] != '' ? $this->page_content[$match[1]] : '');

        return $out;
    }

}
