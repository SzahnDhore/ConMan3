<?php

namespace Szandor\ConMan;

use \Szandor\ConMan\Data as Data;
use \Szandor\ConMan\Logic as Logic;
use \Szandor\ConMan\View as View;

/**
 * We can do stuff here.
 */
$base_url = Data\Settings::main('base_url');

/**
 * The following is simple contents.
 */
$contents['page_id'] = 'eventinfo';
$contents['date_created'] = '2014-12-28 22:09:18';
$contents['date_changed'] = gmdate("Y-m-d H:i:s", filemtime(__FILE__));
$contents['required_clearance'] = '1';
$contents['name'] = '';
$contents['title'] = 'Skapa ny användare';
$contents['head_local'] = '';

$contents['content_top'] = '';

$contents['content_main'] = '
<div class="col-xs-12">
    <div class="row">
        <div class="col-xs-12">
            <h1>Skapa ny användare</h1>
        </div>
        <div class="col-xs-12 col-sm-8 col-md-9">
            <p class="lead">För att kunna anmäla dig till WSK2015 måste du ha en användare registrerad. Enklaste sättet är att använda ett redan befintligt konto, men du kan också skapa en helt ny användare.</p>

            <form role="form" id="new_user" name="new_user" action="dostuff.php" method="post">

                <div class="well bs-component">
                    <fieldset>
                        <legend>Skapa användare via sociala medier <span class="fa fa-question-circle fa-fw has-tooltip" data-placement="top" title="Du kan skapa en ny användare via ett redan befintligt konto på ett socialt media."></span></legend>

                        <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <button class="btn btn-google-plus has-tooltip" title="Google+" name="submit_dostuff" value="social_login_google">
                                        <span class="fa fa-google-plus"></span> | Google+
                                    </button>
                                    <button class="btn btn-facebook has-tooltip" title="Facebook" name="submit_dostuff" value="social_login_facebook">
                                        <span class="fa fa-facebook"></span> | Facebook
                                    </button>
                               <!-- <button class="btn btn-twitter has-tooltip" title="Twitter" name="submit_dostuff" value="social_login_twitter">
                                        <span class="fa fa-twitter"></span> | Twitter
                                    </button>
                                    <button class="btn btn-social-icon btn-openid has-tooltip" title="OpenID" name="submit_dostuff" value="social_login_openid">
                                        <span class="fa fa-openid"></span>
                                    </button>
                                    <button class="btn btn-social-icon btn-yahoo has-tooltip" title="Yahoo" name="submit_dostuff" value="social_login_yahoo">
                                        <span class="fa fa-yahoo"></span>
                                    </button>
                                    <button class="btn btn-social-icon btn-microsoft has-tooltip" title="Microsoft" name="submit_dostuff" value="social_login_microsoft">
                                        <span class="fa fa-windows"></span>
                                    </button>
                                    <button class="btn btn-social-icon btn-linkedin has-tooltip" title="LinkedIn" name="submit_dostuff" value="social_login_linkedin">
                                        <span class="fa fa-linkedin"></span>
                                    </button>
                                    <button class="btn btn-social-icon btn-github has-tooltip" title="Steam" name="submit_dostuff" value="social_login_steam">
                                        <span class="fa fa-steam"></span>
                                    </button> -->
                                </div>
                            </div>
                        </div>

                    </fieldset>
                </div>
                <div class="well bs-component">
                    <fieldset>
                        <legend>Skapa ny användare</legend>

                        <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group' . ((isset($_GET['form_error_username_length']) && $_GET['form_error_username_length'] == '1') || (isset($_GET['form_error_username_duplicate']) && $_GET['form_error_username_duplicate'] == '1') ? ' has-error' : '') . '">
                                    <label for="new_user_username">Användarnamn</label> <span class="fa fa-question-circle fa-fw has-tooltip" data-placement="top" title="Användarnamn"></span>
                                    <input type="text" class="form-control" id="new_user_username" name="new_user_username" placeholder="Användarnamn" value="' . (isset($_GET['form_data_new_user_username']) ? $_GET['form_data_new_user_username'] : '') . '" />
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group' . (isset($_GET['form_error_email_valid']) && $_GET['form_error_email_valid'] == '1' ? ' has-error' : '') . '">
                                    <label for="new_user_email">Epost<span class="hidden-xs">adress</span></label> <span class="fa fa-question-circle fa-fw has-tooltip" data-placement="top" title="Epostadress"></span>
                                    <input type="text" class="form-control" id="new_user_email" name="new_user_email" placeholder="Epostadress" value="' . (isset($_GET['form_data_new_user_email']) ? $_GET['form_data_new_user_email'] : '') . '" />
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group' . ((isset($_GET['form_error_password_length']) && $_GET['form_error_password_length'] == '1') || (isset($_GET['form_error_password_match']) && $_GET['form_error_password_match'] == '1') ? ' has-error' : '') . '">
                                    <label for="new_user_password">Lösen<span class="hidden-xs">ord</span></label> <span class="fa fa-question-circle fa-fw has-tooltip" data-placement="top" title="Lösenord"></span>
                                    <input type="password" class="form-control" id="new_user_password" name="new_user_password" placeholder="Lösenord" />
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group' . ((isset($_GET['form_error_email_valid']) && $_GET['form_error_email_valid'] == '1') || (isset($_GET['form_error_email_match']) && $_GET['form_error_email_match'] == '1') ? ' has-error' : '') . '">
                                    <label for="new_user_website">Bekr<span class="hidden-xs">äfta</span><span class="visible-xs-inline">.</span> epost<span class="hidden-xs hidden-sm">adress</span></label> <span class="fa fa-question-circle fa-fw has-tooltip" data-placement="top" title="Bekräfta epost"></span>
                                    <input type="text" class="form-control" id="new_user_website" name="new_user_website" placeholder="Bekräfta epost" value="' . (isset($_GET['form_data_new_user_website']) ? $_GET['form_data_new_user_website'] : '') . '" />
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group' . ((isset($_GET['form_error_password_length']) && $_GET['form_error_password_length'] == '1') || (isset($_GET['form_error_password_match']) && $_GET['form_error_password_match'] == '1') ? ' has-error' : '') . '">
                                    <label for="new_user_comment">Bekr<span class="hidden-xs">äfta</span><span class="visible-xs-inline">.</span> lösen<span class="hidden-xs hidden-sm">ord</span></label> <span class="fa fa-question-circle fa-fw has-tooltip" data-placement="top" title="Lösenord"></span>
                                    <input type="password" class="form-control" id="new_user_comment" name="new_user_comment" placeholder="Lösenord" />
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-sm-offset-6">
                                <div class="form-group">
                                    <label for="new_user_submit">&nbsp;</label>
                                    <button type="submit" class="btn btn-primary btn-block" id="new_user_submit" name="submit_dostuff" value="new_user_submit">Skapa ny användare</button>
                                </div>
                            </div>
                        </div>

                    </fieldset>
                </div>

            </form>

        </div>
        <div class="col-xs-12 col-sm-4 col-md-3">
            <h2><small>Kort om säkerhet:</small></h2>
            <p>Om du loggar in via ett socialt media så sker all lösen&shy;ords&shy;hantering på mediets egna sida. Det betyder att ditt lösen&shy;ord aldrig sparas hos oss vilket bidrar till ökad säkerhet.</p>
            <p>Om du istäl&shy;let skapar en helt ny använ&shy;dare så krypt&shy;erar vi ditt lösen&shy;ord på ett sätt som gör det prakt&shy;iskt sett omöjligt att de&shy;krypt&shy;era. Det betyder att om du glömmer bort ditt lösen&shy;ord så kan vi inte skicka över det till dig igen, men också att ditt lösen&shy;ord är relativt säkert även om data&shy;basen blir hackad.</p>
        </div>
    </div>';

$contents['content_bottom'] = '';
