<?php

namespace Szandor\ConMan;

use \Szandor\ConMan\Data as Data;
use \Szandor\ConMan\Logic as Logic;
use \Szandor\ConMan\View as View;

$crr = new Data\MySQLConventionRegistrationRepository();
$tdrcapr3 = $crr->getTimeDifferenceRegistrationCreatedAndPaymentRegistered(3);
$tdrcapr1 = $crr->getTimeDifferenceRegistrationCreatedAndPaymentRegistered(1);
$tdrcapr100 = $crr->getTimeDifferenceRegistrationCreatedAndPaymentRegistered(100);

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
        <h1 style="text-align: center;">Statistik för systemet här</h1>
        <h3>Tid för att godkänna en anmälan</h3>
        <p>Format: månader:dagar timmar:minuter:sekunder</p>
        <h4>1 dag</h4>
        <dl class="dl-horizontal">
            <dt>Minsta tid</dt>
            <dd>' . ($tdrcapr1["min"] > 0 ? date('m:d H:i:s', $tdrcapr1["min"]) : 'data saknas') . '</dd>
            <dt>Genomsnittlig tid</dt>
            <dd>' . ($tdrcapr1["average"] > 0 ? date('m:d H:i:s', $tdrcapr1["average"]) : 'data saknas') . '</dd>
            <dt>Längsta tid</dt>
            <dd>' . ($tdrcapr1["max"] > 0 ? date('m:d H:i:s', $tdrcapr1["max"]) : 'data saknas') . '</dd>
        </dl>
        <h4>3 dagar</h4>
        <dl class="dl-horizontal">
            <dt>Minsta tid</dt>
            <dd>' . ($tdrcapr3["min"] > 0 ? date('m:d H:i:s', $tdrcapr3["min"]) : 'data saknas') . '</dd>
            <dt>Genomsnittlig tid</dt>
            <dd>' . ($tdrcapr3["average"] > 0 ? date('m:d H:i:s', $tdrcapr3["average"]) : 'data saknas') . '</dd>
            <dt>Längsta tid</dt>
            <dd>' . ($tdrcapr3["max"] > 0 ? date('m:d H:i:s', $tdrcapr3["max"]) : 'data saknas') . '</dd>
        </dl>
        <h4>100 dagar</h4>
        <dl class="dl-horizontal">
            <dt>Minsta tid</dt>
            <dd>' . ($tdrcapr100["min"] > 0 ? date('m:d H:i:s', $tdrcapr100["min"]) : 'data saknas') . '</dd>
            <dt>Genomsnittlig tid</dt>
            <dd>' . ($tdrcapr100["average"] > 0 ? date('m:d H:i:s', $tdrcapr100["average"]) : 'data saknas') . '</dd>
            <dt>Längsta tid</dt>
            <dd>' . ($tdrcapr100["max"] > 0 ? date('m:d H:i:s', $tdrcapr100["max"]) : 'data saknas') . '</dd>
        </dl>
    </div>
</div>';

$contents['content_bottom'] = '';
