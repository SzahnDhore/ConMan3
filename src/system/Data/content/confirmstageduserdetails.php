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

$contents['content_main'] = '<p>Godkänn medlemsuppgifter här.</p>';

$contents['content_bottom'] = '';
