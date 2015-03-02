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

        $page_content['user_logged_in'] = $userIsLoggedIn;
        $page_content['username'] = ($userIsLoggedIn ? $_SESSION['user']['info']['data']['username'] : false);
        $page_content['base_url'] = Data\Settings::main('base_url');
        $page_content['this_url'] = substr(strrchr($_SERVER['REQUEST_URI'], '/'), 1);
        $page_content['show_alerts'] = Alerts::display();
        $page_content['show_alerts_body'] = ($page_content['show_alerts'] == '' ? '' : ' class="modal-open"');
        $page_content['show_alerts_javascript'] = Alerts::javaScript();

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
