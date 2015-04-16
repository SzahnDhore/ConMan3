<?php
/**
 * This is the index file for the system. It is a Single Point of Entry, with every other file being called from this one
 * in one way or another. All other files (most of them anyway) will reside in directories protected by .htaccess, making
 * them unreachable from outside of this file and all code apart from the code here will reside in classes.
 */

/**
 * The namespace of the system. Must be included on top of every PHP-file. This makes using external libaries easier
 * since there could be conflicting class names. By using namespaces I can get around that problem.
 */
namespace Szandor\ConMan;

/**
 * I use sub-namespaces to organize my code a bit. This means that I can two classes with the same name doing different
 * stuff depending on what they're for. A class named `Data\Page` for instance could fetch the contents of a page while a
 * class named `View\Page` could layout it for me.
 *
 * The following lines of code makes sure I don't have to declare the base namespace. They are actually reduntant in any
 * file that only has the base namespace, but must be present in every other file.
 */
use \Szandor\ConMan\Data as Data;
use \Szandor\ConMan\Logic as Logic;
use \Szandor\ConMan\View as View;

/**
 * All errors should be reported during development. When the system is in use however, no errors should be shown since
 * malicious users who see errors might be able to exploit them.
 */
error_reporting(-1);

/**
 * System wide settings should be loaded as soon as possible.
 */
if (!file_exists('system/Data/Settings.php')) { copy('system/Data/Settings_template.php', 'system/Data/Settings.php'); }
require_once 'system/Data/Settings.php';

/**
 * The autoloader will automagically `require_once` any file with the needed class, provided that classes and files are
 * named in a certain way. It is therefore, with a few exceptions, the only file we need to load manually.
 */
require_once Data\Settings::main('system_url') . 'Autoloader.php';

/**
 * If we need to register any other autoloaders we do that here.
 */
require_once 'lib/Twig/Autoloader.php';
\Twig_Autoloader::register();

/**
 * The `login` class takes care of everything login-related and the `check()` function will perform a few basic checks
 * each time it's called. Since the system makes heavy use of sessions, I'll make sure a session is always in progress
 * here. This means I can always assume that a session is in progress in the rest of the code.
 */

// print_r($_SESSION);
// exit ;
$login = new Data\Login;
$userIsLoggedIn = $login->check();

/**
 * This gets the contents for the page.
 */
$url = new Data\URL;
$page = $url->getPageID();

/**
 * This gets the contents and the template for the page.
 */
$content = new Data\Content;
$html_content = $content->getContent($page);
// $template = $content->getTemplate();

/**
 * If the page we asked for isn't available, a 404 error page is sent instead. If the error page is sent we also need to
 * set a 404 header so that search engines know it's a missing page.
 */
if ($html_content['page_id'] === Data\Settings::main('error_page')) {
    header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
}

/**
 * If the requested page requires that the user is logged in, we need to check for that and redirect the user back to the
 * default page if not logged in.
 */
if ($userIsLoggedIn === false) {
    $user_clearance = array('guest');
} else {
    $user_clearance = $_SESSION['user']['info']['groups'];
    $user_clearance[] = 'guest';
}

if (!in_array($html_content['required_clearance'], $user_clearance, true)) {
    header('Location: ' . Data\Settings::main('base_url'));
}

/**
 * We might as well set all other headers while we're at it.
 */
header('Content-Type: text/html; charset=utf-8');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s', strtotime($html_content['date_changed'])) . ' GMT');

/**
 * This displays the actual content.
 */
$page = new View\Page;
echo $page->display($html_content, $login);
echo '<!-- Page request time was ' . round(microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'], 2) . ' seconds. -->';
