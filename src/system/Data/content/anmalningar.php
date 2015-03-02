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
$contents['required_clearance'] = '2';
$contents['name'] = 'Din anmälan';
$contents['title'] = 'Din anmälan';
$contents['head_local'] = '<link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet"/>
<script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.min.js"></script>';
$contents['content_top'] = '';

$contents['content_main'] = '
<div class="row">
    <div class="col-xs-12">
        <h1>Din anmälan till konventet</h1>
        <p class="lead">Här kan du se vad du har anmält dig till på konventet. Du kan även se status för din anmälan och betalning.</p>
        <hr />
    </div>
</div>
<div class="row">
    <div class="col-sm-6 col-xs-12">
        <h3>Personuppgifter</h3>
        <dl class="dl-horizontal">
            <dt>Förnamn</dt>
            <dd><a href="#" id="edit_given_name" class="editable-text" data-type="text" data-title="Ange förnamn">' . $user_info['details']['given_name'] . '</a></dd>
            <dt>Efternamn</dt>
            <dd><a href="#" id="edit_family_name" class="editable-text" data-type="text" data-title="Ange efternamn">' . $user_info['details']['family_name'] . '</a></dd>
            <dt>Adress</dt>
            <dd>' . $user_info['details']['address'] . '</dd>
            <dt>Postnr.</dt>
            <dd>' . $user_info['details']['postal_code'] . '</dd>
            <dt>Stad</dt>
            <dd>' . $user_info['details']['city'] . '</dd>
            <dt>Telenr.</dt>
            <dd>' . $user_info['details']['phone_number'] . '</dd>
            <dt>Epost</dt>
            <dd>' . $user_info['data']['email'] . '</dd>
            <dt>Personnr.</dt>
            <dd>' . $user_info['details']['national_id_number'] . '</dd>
            <dt>Binärt kön</dt>
            <dd>' . $user_info['details']['gender'] . '</dd>
        </dl>
        <h3>Status</h3>
        <dl class="dl-horizontal">
            <dt>Anmälan</dt>
            <dd>Ej inskickad</dd>
            <dt>Betalning</dt>
            <dd>Ej registrerad</dd>
        </dl>
    </div>
    <div class="col-sm-6 col-xs-12">
        <h3>Din anmälan</h3>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Namn</th>
                    <th class="text-right">Kostnad</th>
                    <th>&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Inträde (arrangör)</td>
                    <td class="text-right">0 kr</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td>Magic: FNM</td>
                    <td class="text-right">80 kr</td>
                    <td><span class="fa fa-remove"></span></td>
                </tr>
                <tr>
                    <td>Mugg</td>
                    <td class="text-right">50 kr</td>
                    <td><span class="fa fa-remove"></span></td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td>Att betala:</td>
                    <td class="text-right">130 kr</td>
                    <td>&nbsp;</td>
                </tr>
            </tfoot>
        </table>
        <div class="row">
            <div class="col-xs-6">
                <a href="#" class="btn btn-info btn-block">Spara ändringar</a>
            </div>
            <div class="col-xs-6">
                <a href="#" class="btn btn-success btn-block">Skicka anmälan</a>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12">
        <h2>FAQ</h2>
        <h3>Varför måste jag fylla i så mycket personlig info?</h3>
            <p>Vi behöver informationen av flera anledningar:</p>
            <p><strong>Konventets aktiviteter</strong> är generellt sett bara till för betalande deltagare. Därför behöver vi en lista på vilka som har betalat och för vad.</p>
            <p><strong>Räddningstjänsten</strong> behöver en namnlista uppsatt på allmänt tillgänglig plats på skolan över vilka personer som sover var. Därför är det också jätteviktigt att du anmäler dig som sovande på konventet. Folk som sover på konventet utan att ha anmält sig kan bli bötfällda.</p>
            <p><strong>Sverok</strong> får en lista på alla deltagare som vill bli medlemmar. Dessa skrivs in i eBas. Om du inte vill bli medlem i föreningen så hamnar du inte i eBas.</p>
        <h3>Varför kan jag bara välja mellan två kön?</h3>
            <p>Därför att vi rapporterar in datan till system som inte klarar av annat än en binär könsuppdelning.</p>
        <h3>Jag skrev inte mitt personnummer på inbetalningen</h3>
            <p>Då vet vi inte vilken inbetalning som är din och kan inte registrera dig som betald. Tack för din donation till föreningen!</p>
            <p>Skämt åsido så kan vi försöka spåra betalningen så du får tillbaka dina pengar, men vi kan inte lova att vi hittar den och det kan ta lite tid att lösa. Hör av dig i god tid innan konventet drar igång om du skulle komma på att det här har hänt. Om det inte blir löst innan dess får du vara beredd på att betala dubbelt tills dess att vi hunnit sätta tillbaka pengarna.</p>
        <h3>Jag betalade nyss, men står ändå som obetald. Varför?</h3>
            <p>Registreringen av inbetalningar sker manuellt och det kan dröja något innan vi har hunnit hantera din betalning. Om det dröjer mer än två veckor, hör gärna av dig till oss så kollar vi upp vad som hänt.</p>
        <h3>Jag betalade i förväg. Får jag rabatt då?</h3>
            <p>Det kommer att finnas ett rabattsystem för de som betalar in hela summan i förväg. Vilka datum som gäller kommer att dyka upp när de är beslutade. När vi kontrollerar betalningsdatumen så är det bankens registreringsdatum av betalningen som gäller och inget annat.</p>
    </div>
</div>';

$contents['content_bottom'] = '
<script>
    $(document).ready(function() {
        $(".editable-text").editable({
            emptytext: "Tomt",
        });
        $(".edit_ean").editable();
        $(".edit_kategori").editable({
            source: [
                {value: 3, text: "Block & papper"},
                {value: 1, text: "Pennor"},
                {value: 2, text: "Förvaring"},
                {value: 5, text: "Ätbart"},
                {value: 4, text: "Kläder"},
                {value: 0, text: "Övrigt"},
                {value: 6, text: "Kontorsmateriel"},
                {value: 11, text: "Datortillbehör"},
                {value: 12, text: "Tjänster"},
                {value: 7, text: "Väskor"}
            ]
        });
        $(".edit_profilprodukt").editable({
            source: [
                { value: 0, text: "Ej profilprodukt" },
                { value: 1, text: "Profilprodukt" }
            ]
        });
        $(".edit_pris_exkl_moms").editable();
        $(".edit_pris_inkl_moms").editable();
        $(".edit_anteckning").editable({
            rows: 4,
            showbuttons: "bottom"
        });
    });
</script>';
