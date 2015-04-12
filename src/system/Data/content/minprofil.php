<?php

namespace Szandor\ConMan;

use \Szandor\ConMan\Data as Data;
use \Szandor\ConMan\Logic as Logic;
use \Szandor\ConMan\View as View;

/**
 * We can do stuff here.
 */
$staged_changes = Data\User::getStagedChangesForUserId($_SESSION['user']['info']['data']['id']);
$user_has_staged_changes = false;
if (is_array($staged_changes) && !empty($staged_changes)) {
    $user_info = $staged_changes;
    $user_info['gender'] = $staged_changes['male'];
    unset($user_info['male']);
    $user_has_staged_changes = true;
} else {
    $user_info = $_SESSION['user']['info']['details'];
    $user_info['email'] = $_SESSION['user']['info']['data']['email'];
    $user_info['users_id'] = $_SESSION['user']['info']['details']['id'];
    unset($user_info['id']);
}

foreach ($_GET as $key => $value) {
    $userInfoKey = str_replace('form_data_form_profile_', '', $key);
    if (array_key_exists($userInfoKey, $user_info)) {
        $user_info[$userInfoKey] = $_GET[$key];
    }
}

/**
 * The following is simple contents.
 */
$contents['page_id'] = 'minprofil';
$contents['date_created'] = '2014-11-15 20:53:18';
$contents['date_changed'] = gmdate("Y-m-d H:i:s", filemtime(__FILE__));
$contents['required_clearance'] = 'regular user';
$contents['name'] = 'Min profil';
$contents['title'] = 'Min profil';
$contents['head_local'] = '<link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet"/>
<script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.min.js"></script>';
$contents['content_top'] = '';

$contents['content_main'] = '
<div class="row">
    <div class="col-xs-12">
        <h1>Profilinformation för ' . $_SESSION['user']['info']['data']['username'] . '</h1>
        <p class="lead">Här kan du ändra din personliga information. Det är bra att se till att uppgifterna är aktuella.</p>' . ($user_has_staged_changes ? '<div class="alert alert-warning" role="alert">
                <p><strong>OBS!</strong> Dina nya uppgifter måste godkännas av WSK innan de gäller i systemet. Om du har angivit en ny email, kan du alltså inte logga in med den innan den är godkänd. Om denna text inte syns nästa gång du besöker den här sidan, så har dina uppgifter blivit godkända.</p>
            </div>' : '') . '        
    </div>
</div>
<div class="row">
    <div class="col-lg-5 col-sm-6 col-xs-12">
        <form id="form_profile" name="form_profile" class="form-horizontal" action="dostuff.php" method="post">
            <h2>Personuppgifter</h2>
            <input type="hidden" id="form_profile_users_id" name="form_profile_users_id" value="' . $user_info['users_id'] . '">
            <br />
            <div class="form-group' . (isset($_GET['form_error_given_name']) ? ' has-error' : '') . '">
                <label for="form_profile_given_name" class="col-sm-4 control-label">Förnamn</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control input-sm" id="form_profile_given_name" name="form_profile_given_name" placeholder="Förnamn" value="' . $user_info['given_name'] . '">
                </div>
            </div>
            <div class="form-group' . (isset($_GET['form_error_family_name']) ? ' has-error' : '') . '">
                <label for="form_profile_family_name" class="col-sm-4 control-label">Efternamn</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control input-sm" id="form_profile_family_name" name="form_profile_family_name" placeholder="Efternamn" value="' . $user_info['family_name'] . '">
                </div>
            </div>
            <div class="form-group' . (isset($_GET['form_error_address']) ? ' has-error' : '') . '">
                <label for="form_profile_address" class="col-sm-4 control-label">Adress</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control input-sm" id="form_profile_address" name="form_profile_address" placeholder="Adress" value="' . $user_info['address'] . '">
                </div>
            </div>
            <div class="form-group' . (isset($_GET['form_error_postal_code']) ? ' has-error' : '') . '">
                <label for="form_profile_postal_code" class="col-sm-4 control-label">Postnr.</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control input-sm" id="form_profile_postal_code" name="form_profile_postal_code" placeholder="Postnr." value="' . $user_info['postal_code'] . '">
                </div>
            </div>
            <div class="form-group' . (isset($_GET['form_error_city']) ? ' has-error' : '') . '">
                <label for="form_profile_city" class="col-sm-4 control-label">Stad</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control input-sm" id="form_profile_city" name="form_profile_city" placeholder="Stad" value="' . $user_info['city'] . '">
                </div>
            </div>
            <div class="form-group' . (isset($_GET['form_error_phone_number']) ? ' has-error' : '') . '">
                <label for="form_profile_phone_number" class="col-sm-4 control-label">Telenr.</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control input-sm" id="form_profile_phone_number" name="form_profile_phone_number" placeholder="Telenr." value="' . $user_info['phone_number'] . '">
                </div>
            </div>
            <div class="form-group' . (isset($_GET['form_error_email']) ? ' has-error' : '') . '">
                <label for="form_profile_email" class="col-sm-4 control-label">Epost</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control input-sm" id="form_profile_email" name="form_profile_email" placeholder="Epost" value="' . $user_info['email'] . '">
                </div>
            </div>
            <div class="form-group' . (isset($_GET['form_error_national_id_number']) ? ' has-error' : '') . '">
                <label for="form_profile_national_id_number" class="col-sm-4 control-label">Personnr.</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control input-sm" id="form_profile_national_id_number" name="form_profile_national_id_number" placeholder="ÅÅÅÅMMDDNNNN" value="' . $user_info['national_id_number'] . '">
                </div>
            </div>
            <div class="form-group">
                <label for="form_profile_gender" class="col-sm-4 control-label">Binärt kön</label>
                <div class="col-sm-8">
                    <select class="form-control input-sm" id="form_profile_gender" name="form_profile_gender">
                        <option' . ($user_info['gender'] === '0' ? ' selected="selected"' : '') . ' value="0">Kvinna</option>
                        <option' . ($user_info['gender'] === '1' ? ' selected="selected"' : '') . ' value="1">Man</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-8 col-sm-offset-4">
                    <button type="submit" class="btn btn-primary" id="form_profile_submit" name="submit_dostuff" value="update_profile"><i class="fa fa-check-square-o"></i> Spara personuppgifter</button>
                </div>
            </div>
        </form>
    </div>
    <div class="col-lg-7 col-sm-6 col-xs-12">
        <h2>FAQ</h2>
        <h3>Varför måste jag fylla i så mycket personlig info?</h3>
            <p>Vi behöver informationen av flera anledningar:</p>
            <p><strong>Konventets aktiviteter</strong> är generellt sett bara till för betalande deltagare. Därför behöver vi en lista på vilka som har betalat och för vad.</p>
            <p><strong>Räddningstjänsten</strong> behöver en namnlista uppsatt på allmänt tillgänglig plats på skolan över vilka personer som sover var. Därför är det också jätteviktigt att du anmäler dig som sovande på konventet. Folk som sover på konventet utan att ha anmält sig kan bli bötfällda.</p>
            <p><strong>Sverok</strong> får en lista på alla föreningens medlemmar via eBas. Om du inte vill bli medlem i föreningen så hamnar du inte i eBas.</p>
        <h3>Jag vill byta användarnamn - hur gör jag det?</h3>
            <p>Normalt sett kan du inte byta ditt användarnamn, men skicka ett mail till oss med en god motivering om varför så skall vi se vad vi kan göra.</p>
        <h3>Varför kan jag bara välja mellan två kön?</h3>
            <p>För att vi rapporterar in datan till system som inte klarar av annat än en binär könsuppdelning.</p>
    </div>
</div>';

$contents['content_bottom'] = '';
