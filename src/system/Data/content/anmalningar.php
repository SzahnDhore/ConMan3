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
$registration_data = Data\User::getConventionRegistrationData($_SESSION['user']['info']['data']['id']);

$registration_content_array = Data\Content::getEntranceContentForRegistrationPage();

/**
 * The following is simple contents.
 */
$contents['page_id'] = 'anmalningar';
$contents['date_created'] = '2014-11-15 20:53:18';
$contents['date_changed'] = gmdate("Y-m-d H:i:s", filemtime(__FILE__));
$contents['required_clearance'] = '2';
$contents['name'] = 'Din anmälan';
$contents['title'] = 'Din anmälan';
$contents['head_local'] = '<link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet"/>
<script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.min.js"></script>';
$contents['content_top'] = '';

$contents['content_main'] = (empty($registration_content_array) ?
'<div class="row">
    <div class="col-xs-12">
        <h1 style="text-align: center;">Anmälan stängd</h1>
        <p class="lead" style="text-align: center;">Anmälan till nästa WSK är ännu inte öppen.</p>
    </div>
</div>' : '
<div class="row">
    <div class="col-xs-12">
        <h1>Din anmälan till konventet</h1>
        <p class="lead">Här kan du se vad du har anmält dig till på konventet. Du kan även se status för din anmälan och betalning.</p>
        <hr />
    </div>
</div>
<div class="row">
    <form id="form_register_convention" name="form_register_convention" class="form-horizontal" action="dostuff.php" method="post">
        <div class="col-sm-6 col-xs-12">
            <div' . (!empty($registration_data) ? " style=\"display: none;\"" : "") . '>
                <h3>Anmälan WSK 2015</h3>
                <p>Barn under 13 år betalar endast medlemsavgift för inträde på konventet. Medlemmar får 150kr i rabatt på inträde.</p>
                <dl class="dl-horizontal">
                    <div id="registration_entrance">
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="member" value="1" id="registration_member">
                            Jag vill bli medlem i WSK - 100kr
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="mug" value="1" id="registration_mug">
                            Jag vill köpa årets konventsmugg - 50kr
                        </label>
                    </div>
                </dl>
            </div>
            <h3>Status</h3>
            <dl class="dl-horizontal">
                <dt>Anmälan</dt>
                <dd>' . (!empty($registration_data) ? "Inskickad" : "Ej inskickad") . '</dd>
                <dt>Betalning</dt>
                <dd>' . (!empty($registration_data) && $registration_data[0]['payment_registered'] == '1' ? "Registrerad" : "Ej registrerad") . '</dd>
            </dl>
        </div>
        <div class="col-sm-6 col-xs-12">
            <h3>Din anmälan</h3>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Namn</th>
                        <th class="text-right">Kostnad</th>
                    </tr>
                </thead>
                <tbody id="registration_details">
                </tbody>
                <tfoot>
                    <tr>
                        <td>Att betala:</td>
                        <td class="text-right" id="registration_sum">0 kr</td>
                    </tr>
                </tfoot>
            </table>
            <div class="row">
                <div class="col-xs-6">
                </div>
                <div class="col-xs-6">
                    <button type="submit" class="btn btn-success btn-block" id="form_register_convention_submit" name="submit_dostuff" value="register_convention"' . (!empty($registration_data) ? " disabled=\"disabled\"" : "") . '>Skicka anmälan</button>
                </div>
            </div>
        </div>
    </form>
</div>
<div class="row">
    <div class="col-xs-12">
        <h2>FAQ</h2>
        <h3>Jag skrev inte mitt personnummer på inbetalningen</h3>
            <p>Då vet vi inte vilken inbetalning som är din och kan inte registrera dig som betald. Tack för din donation till föreningen!</p>
            <p>Skämt åsido så kan vi försöka spåra betalningen så du får tillbaka dina pengar, men vi kan inte lova att vi hittar den och det kan ta lite tid att lösa. Hör av dig i god tid innan konventet drar igång om du skulle komma på att det här har hänt. Om det inte blir löst innan dess får du vara beredd på att betala dubbelt tills dess att vi hunnit sätta tillbaka pengarna.</p>
        <h3>Jag betalade nyss, men står ändå som obetald. Varför?</h3>
            <p>Registreringen av inbetalningar sker manuellt och det kan dröja något innan vi har hunnit hantera din betalning. Om det dröjer mer än två veckor, hör gärna av dig till oss så kollar vi upp vad som hänt.</p>
        <h3>Jag betalade i förväg. Får jag rabatt då?</h3>
            <p>Det kommer att finnas ett rabattsystem för de som betalar in hela summan i förväg. Vilka datum som gäller kommer att dyka upp när de är beslutade. När vi kontrollerar betalningsdatumen så är det bankens registreringsdatum av betalningen som gäller och inget annat.</p>
    </div>
</div>');

$contents['content_bottom'] = (empty($registration_content_array) ? '' : '
<script>
    $(document).ready(function() {
        var member = true;
        var mug = false;
        var sum = 0;

        // pagesetup begin
        var registrationEntranceTypes = [
            ' . implode(',', $registration_content_array) . '
        ];

        var entrance = $("#registration_entrance");
        for (var i = 0; i < registrationEntranceTypes.length; i++) {
            entrance.append("<div class=\"radio\"><label><input type=\"radio\" name=\"entrance_type\" id=\"entrance_type" +i.toString() +"\" value=\"" +registrationEntranceTypes[i][0] +"\">" +registrationEntranceTypes[i][1] +" - " +registrationEntranceTypes[i][3] +"kr</label></div>");
        }
        $(\'#entrance_type0\').prop(\'checked\', true);
        $(\'#registration_member\').prop(\'checked\', true);

        // pagesetup ends
        $("#registration_entrance").change(function() {
            updateTable();
        });
        
        $("#registration_member").change(function() {
            member = this.checked;
            updateTable();
        });

         $("#registration_mug").change(function() {
            mug = this.checked;
            updateTable();
        });

        function updateTable() {
            $("#registration_details").empty();
            sum = 0;
            var entranceIndex = $("#registration_entrance input[type=\'radio\']:checked").prop(\'id\').replace("entrance_type", "");
            addItemToRegistrationSum(registrationEntranceTypes[entranceIndex][1], registrationEntranceTypes[entranceIndex][3]);
            if (member) {
                addItemToRegistrationSum("Medlemsavgift", 100);
                if (registrationEntranceTypes[entranceIndex][2] < 0) {
                    addItemToRegistrationSum("Medlemsrabatt - inträde", registrationEntranceTypes[entranceIndex][2]);
                }
            }
            if (mug) { addItemToRegistrationSum("Mugg", 70); }
            $("#registration_sum").html(sum.toString() +" kr");
            if (sum == 0) {
                $("#form_register_convention_submit").attr("disabled", "disabled");
            } else {
                $("#form_register_convention_submit").attr("disabled", false);
            }
        }

        function addItemToRegistrationSum(description, price) {
            $("#registration_details").append("<tr><td>" +description +"</td><td class=\"text-right\">" +price.toString() +" kr</td></tr>");
            sum += price;
        }

        updateTable();
    });
</script>');
