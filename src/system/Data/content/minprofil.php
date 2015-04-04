<?php

namespace Szandor\ConMan;

use \Szandor\ConMan\Data as Data;
use \Szandor\ConMan\Logic as Logic;
use \Szandor\ConMan\View as View;

/**
 * We can do stuff here.
 */

// print_r($_SESSION['user']);

$user_info = $_SESSION['user']['info'];

/**
 * The following is simple contents.
 */
$contents['page_id'] = 'anmalningar';
$contents['date_created'] = '2014-11-15 20:53:18';
$contents['date_changed'] = gmdate("Y-m-d H:i:s", filemtime(__FILE__));
$contents['required_clearance'] = 'regular user';
$contents['name'] = 'Din anmälan';
$contents['title'] = 'Din anmälan';
$contents['head_local'] = '<link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet"/>
<script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.min.js"></script>';
$contents['content_top'] = '';

$contents['content_main'] = '
<div class="row">
    <div class="col-xs-12">
        <h1>Profilinformation för ' . $user_info['data']['username'] . '</h1>
        <p class="lead">Här kan du ändra din personliga information. Det är bra att se till att uppgifterna är aktuella.</p>
    </div>
</div>
<div class="row">
    <div class="col-lg-5 col-sm-6 col-xs-12">
        <form id="form_profile" name="form_profile" class="form-horizontal" action="dostuff.php" method="post">
            <h2>Personuppgifter</h2>
            <br />
            <div class="form-group">
                <label for="form_profile_given_name" class="col-sm-4 control-label">Förnamn</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control input-sm" id="form_profile_given_name" name="form_profile_given_name" placeholder="Förnamn" value="' . $user_info['details']['given_name'] . '">
                </div>
            </div>
            <div class="form-group">
                <label for="form_profile_family_name" class="col-sm-4 control-label">Efternamn</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control input-sm" id="form_profile_family_name" name="form_profile_family_name" placeholder="Efternamn" value="' . $user_info['details']['family_name'] . '">
                </div>
            </div>
            <div class="form-group">
                <label for="form_profile_address" class="col-sm-4 control-label">Adress</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control input-sm" id="form_profile_address" name="form_profile_address" placeholder="Adress" value="' . $user_info['details']['address'] . '">
                </div>
            </div>
            <div class="form-group">
                <label for="form_profile_postal_code" class="col-sm-4 control-label">Postnr.</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control input-sm" id="form_profile_postal_code" name="form_profile_postal_code" placeholder="Postnr." value="' . $user_info['details']['postal_code'] . '">
                </div>
            </div>
            <div class="form-group">
                <label for="form_profile_city" class="col-sm-4 control-label">Stad</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control input-sm" id="form_profile_city" name="form_profile_city" placeholder="Stad" value="' . $user_info['details']['city'] . '">
                </div>
            </div>
            <div class="form-group">
                <label for="form_profile_phone_number" class="col-sm-4 control-label">Telenr.</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control input-sm" id="form_profile_phone_number" name="form_profile_phone_number" placeholder="Telenr." value="' . $user_info['details']['phone_number'] . '">
                </div>
            </div>
            <div class="form-group">
                <label for="form_profile_email" class="col-sm-4 control-label">Epost</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control input-sm" id="form_profile_email" name="form_profile_email" placeholder="Epost" value="' . $user_info['data']['email'] . '">
                </div>
            </div>
            <div class="form-group">
                <label for="form_profile_national_id_number" class="col-sm-4 control-label">Personnr.</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control input-sm" id="form_profile_national_id_number" name="form_profile_national_id_number" placeholder="Personnr." value="' . $user_info['details']['national_id_number'] . '">
                </div>
            </div>
            <div class="form-group">
                <label for="form_profile_gender" class="col-sm-4 control-label">Binärt kön</label>
                <div class="col-sm-8">
                    <select class="form-control input-sm" id="form_profile_gender" name="form_profile_gender">
                        <option' . ($user_info['details']['gender'] === 'Kvinna' ? ' selected="selected"' : '') . ' value="0">Kvinna</option>
                        <option' . ($user_info['details']['gender'] === 'Man' ? ' selected="selected"' : '') . ' value="1">Man</option>
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
    <pre>' . print_r($user_info, true) . '</pre>
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
