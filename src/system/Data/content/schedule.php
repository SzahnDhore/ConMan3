<?php

namespace Szandor\ConMan;

use \Szandor\ConMan\Data as Data;
use \Szandor\ConMan\Logic as Logic;
use \Szandor\ConMan\View as View;

/**
 * We can do stuff here.
 */

$event = new Data\Event;

/**
 * The following is simple contents.
 */
$contents['page_id'] = 'eventinfo';
$contents['date_created'] = '2015-06-16 02:51:36';
$contents['date_changed'] = gmdate("Y-m-d H:i:s", filemtime(__FILE__));
$contents['required_clearance'] = 'regular user';
$contents['name'] = '';
$contents['title'] = 'tajtel';

$contents['head_local'] = '        <script src="' . Data\Settings::main('base_url') . 'js/daypilot-all.min.js"></script>
        <link href="' . Data\Settings::main('base_url') . 'css/bootstrap-datetimepicker.css" rel="stylesheet" />';

$contents['content_top'] = '';

$contents['content_main'] = '
<div class="col-xs-12">
    <div class="row">
        <div class="col-xs-12">
            <h1>Konventsschema</h1>
        </div>
        <div class="col-xs-12 col-sm-8 col-md-9">
            <div id="dp"></div>
        </div>
    </div>
</div>
';

$contents['content_bottom'] = '
<script type="text/javascript">
  var dp = new DayPilot.Calendar("dp");
  dp.locale = "sv-se";
  dp.timeFormat = "Clock24Hours";
  dp.showCurrentTime = true;
  dp.startDate = "2015-07-03";
  dp.viewType = "Days";
  dp.days = 3;
  dp.heightSpec = "Full";
  dp.width = "100%";
  dp.events.list = [
' . $event->getEventSchedule(true) . '
];
  dp.init();
</script>';
