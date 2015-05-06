<?php

namespace Szandor\ConMan;

use \Szandor\ConMan\Data as Data;
use \Szandor\ConMan\Logic as Logic;
use \Szandor\ConMan\View as View;

$ur = new Data\MySQLUserRepository();
$crr = new Data\MySQLConventionRegistrationRepository();
$tdrcapr3 = $crr->getTimeDifferenceRegistrationCreatedAndPaymentRegistered(3);
$tdrcapr1 = $crr->getTimeDifferenceRegistrationCreatedAndPaymentRegistered(1);
$tdrcapr100 = $crr->getTimeDifferenceRegistrationCreatedAndPaymentRegistered(100);
$registrations = $crr->getRegistrations();
$members = 0;
$mugs = 0;
$sleepingRooms = 0;
foreach ($registrations as $registration)
{
    if ($registration['member'] == '1') { $members += 1; }
    if ($registration['mug'] == '1') { $mugs += 1; }
    if ($registration['sleeping_room'] == '1') { $sleepingRooms += 1; }
}

$registrationsPerDay = $crr->getRegistrationsPerDay();
$registrationsPerDayHTML = '';
foreach ($registrationsPerDay as $reg)
{
    $desc = str_replace('Inträde WSK 2015, ', '', $reg['description']);
    $desc = str_replace(', jag vill bara stöja föreningen och/eller är under 13 år', '', $desc);
    $registrationsPerDayHTML .= '<dt>' . ucfirst($desc) . '</dt><dd>' . $reg['occurrences'] . ' st</dd>';
}

function secondsToTime($seconds) {
    $dtF = new \DateTime("@0");
    $dtT = new \DateTime("@$seconds");

    return $dtF->diff($dtT)->format('%ad %ht %im %ss');
}

/**
 * The following is simple contents.
 */
$contents['page_id'] = 'sitestatistics';
$contents['date_created'] = gmdate("Y-m-d H:i:s", filemtime(__FILE__));
$contents['date_changed'] = gmdate("Y-m-d H:i:s", filemtime(__FILE__));
$contents['required_clearance'] = 'stab';
$contents['name'] = 'Statistik';
$contents['title'] = 'Statistik';

$contents['head_local'] = '';

$contents['content_top'] = '';

$contents['content_main'] = '<div class="row">
    <div class="col-xs-12">
        <div class=row>
            <h1 style="text-align: center;">Statistik</h1>
            <div class="col-sm-6 col-xs-8">
                <h3>Tid för att godkänna en anmälan</h3>
                <h4>1 dag tillbaka</h4>
                <dl class="dl-horizontal">
                    <dt>Minsta tid</dt>
                    <dd>' . ($tdrcapr1["min"] > 0 ? secondsToTime($tdrcapr1["min"]) : 'data saknas') . '</dd>
                    <dt>Genomsnittlig tid</dt>
                    <dd>' . ($tdrcapr1["average"] > 0 ? secondsToTime($tdrcapr1["average"]) : 'data saknas') . '</dd>
                    <dt>Längsta tid</dt>
                    <dd>' . ($tdrcapr1["max"] > 0 ? secondsToTime($tdrcapr1["max"]) : 'data saknas') . '</dd>
                </dl>
                <h4>3 dagar tillbaka</h4>
                <dl class="dl-horizontal">
                    <dt>Minsta tid</dt>
                    <dd>' . ($tdrcapr3["min"] > 0 ? secondsToTime($tdrcapr3["min"]) : 'data saknas') . '</dd>
                    <dt>Genomsnittlig tid</dt>
                    <dd>' . ($tdrcapr3["average"] > 0 ? secondsToTime($tdrcapr3["average"]) : 'data saknas') . '</dd>
                    <dt>Längsta tid</dt>
                    <dd>' . ($tdrcapr3["max"] > 0 ? secondsToTime($tdrcapr3["max"]) : 'data saknas') . '</dd>
                </dl>
                <h4>100 dagar tillbaka</h4>
                <dl class="dl-horizontal">
                    <dt>Minsta tid</dt>
                    <dd>' . ($tdrcapr100["min"] > 0 ? secondsToTime($tdrcapr100["min"]) : 'data saknas') . '</dd>
                    <dt>Genomsnittlig tid</dt>
                    <dd>' . ($tdrcapr100["average"] > 0 ? secondsToTime($tdrcapr100["average"]) : 'data saknas') . '</dd>
                    <dt>Längsta tid</dt>
                    <dd>' . ($tdrcapr100["max"] > 0 ? secondsToTime($tdrcapr100["max"]) : 'data saknas') . '</dd>
                </dl>
            </div>
            <div class="col-sm-6 col-xs-4">
                <h3>Anmälningar</h3>
                <dl class="dl-horizontal">
                    <dt>Anmälningar</dt>
                    <dd>' . count($registrations) . ' st</dd>
                    <dt>Medlemmar</dt>
                    <dd>' . $members . ' st</dd>
                    <dt>Muggar</dt>
                    <dd>' . $mugs . ' st</dd>
                    <dt>Sovsal</dt>
                    <dd>' . $sleepingRooms . ' st</dd>
                </dl>
            </div>
            <div class="col-sm-6 col-xs-4">
                <h3>Typer av anmälningar</h3>
                <dl class="dl-horizontal">
                    ' . $registrationsPerDayHTML . '
                </dl>
            </div>
            <div class="col-sm-6 col-xs-4">
                <h3>Användare</h3>
                <dl class="dl-horizontal">
                    <dt>Totalt antal</dt>
                    <dd>' . $ur->getNumberOfUsers() . ' st</dd>
                </dl>
            </div>
        </div>
    </div>
</div>';

$contents['content_bottom'] = '';
