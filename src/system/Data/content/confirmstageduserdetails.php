<?php

namespace Szandor\ConMan;

use \Szandor\ConMan\Data as Data;
use \Szandor\ConMan\Logic as Logic;
use \Szandor\ConMan\View as View;

/**
 * The following is simple contents.
 */
$contents['page_id'] = 'confirmstageduserdetails';
$contents['date_created'] = gmdate("Y-m-d H:i:s", filemtime(__FILE__));
$contents['date_changed'] = gmdate("Y-m-d H:i:s", filemtime(__FILE__));
$contents['required_clearance'] = 'stab';
$contents['name'] = 'Godkann medlemsuppgifter';
$contents['title'] = 'Godkann medlemsuppgifter';

$contents['head_local'] = '';

$contents['content_top'] = '';

$contents['content_main'] = '<h1>Godkänn medlemsuppgifter här.</h1>
    <p class="lead">Godkänn medlemsuppgifter för systemets användare här.</p>
    <div class="alert alert-warning" role="alert">
        <p><strong>OBS!</strong> Denna sidan är inte implementerad ännu, och du kan därmed inte se några medlemsuppgifter ännu.</p>
    </div>';

$contents['content_bottom'] = '';
