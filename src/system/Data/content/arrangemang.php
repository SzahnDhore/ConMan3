<?php

namespace Szandor\ConMan;

use \Szandor\ConMan\Data as Data;
use \Szandor\ConMan\Logic as Logic;
use \Szandor\ConMan\View as View;

$file_changed = gmdate("Y-m-d H:i:s", filemtime(__FILE__));
$event_class = new Data\Event;
$all_events_raw = $event_class->getAllEvents();

$all_events = [];
foreach ($all_events_raw as $key => $event) {
    if ($event['approved'] == '1' || in_array('PERM_VIEW_ALL_EVENTS', $_SESSION['user']['info']['permissions'], true) || $event['contact'] == $_SESSION['user']['info']['data']['id']) {
        $all_events[$event['event_type_order']][] = $event;
    }
    // $all_events[$event['event_type_order']][] = $event;
}
ksort($all_events);

$schedule_request = array(
    'table' => 'event_schedule',
    'orderby' => 'date_start',
);

$schedule = Data\Database::read($schedule_request, false);

if (isset($schedule[0])) {

    foreach ($schedule as $occasion) {
        $occasion_duration = (strtotime($occasion['date_end']) - strtotime($occasion['date_start'])) / 60;
        $weekday_start_no = date('w', strtotime($occasion['date_start']));
        $weekday_end_no = date('w', strtotime($occasion['date_end']));
        if ($weekday_start_no === $weekday_end_no || ($occasion_duration / 60) < 12) {
            $weekday = Data\Toolbox::numberToWeekday($weekday_start_no, true);
        } else {
            $weekday_start = Data\Toolbox::numberToWeekday($weekday_start_no, true, true);
            $weekday_end = Data\Toolbox::numberToWeekday($weekday_end_no, false, true);
            $weekday = $weekday_start . '-' . $weekday_end;
        }
        $time_start = date('H:i', strtotime($occasion['date_start']));
        $time_end = date('H:i', strtotime($occasion['date_end']));

        $occasion_duration_hours = round($occasion_duration / 60);
        $occasion_duration_minutes = fmod($occasion_duration, 60);
        $occasion_duration = $occasion_duration_hours . 'h' . ($occasion_duration_minutes > 0 ? ' ' . $occasion_duration_minutes . 'm' : '');
        $occasion_notes = ($occasion['cancelled'] === '1' ? 'Inställt!' : '') . ($occasion['cancelled'] === '1' && $occasion['notes'] != '' ? ' ' : '') . ($occasion['notes'] == '' ? '&nbsp;' : $occasion['notes']);

        $schedule_body[] = '
                <tr' . ($occasion['cancelled'] === '1' ? ' class="danger text-danger"' : '') . '>
                    <td style="white-space:nowrap">' . $weekday . '</td>
                    <td style="white-space:nowrap">' . $time_start . '-' . $time_end . ' <small>(' . $occasion_duration . ')</small></td>
                    <td class="visible-xs-block' . ($occasion_notes == '&nbsp;' ? '' : ' has-tooltip') . '"' . ($occasion_notes == '&nbsp;' ? '' : 'title="' . $occasion_notes . '"') . '>' . ($occasion_notes == '&nbsp;' ? '' : '<span class="fa fa-info-circle"></span>') . '</td>
                    <td class="hidden-xs">' . $occasion_notes . '</td>
                    <td style="white-space:nowrap">' . $occasion['location'] . '</td>
                    <td><span class="fa fa-times"></span></td>
                </tr>';

    }
} else {
}

$events_body = [];
foreach ($all_events as $category => $category_events) {
    $events_count = count($category_events);
    $events_body[] = '
    <h2>' . $category_events[0]['event_type_text'] . '</h2>
    <div class="row">';

    foreach ($category_events as $key => $event) {
        $events_body[] = '
        <div class="col-xs-12' . ($event['approved'] == '0' ? ' bg-warning' : '') . '">
            <div class="row">
                <div class="col-xs-12">
                    <h3><a href="' . Data\Settings::main('base_url') . 'index.php?page=eventinfo&event_id=' . $event['events_id'] . '">' . $event['title'] . ' <i class="fa fa-arrow-circle-o-right btn-sm"></i></a></h3>
                </div>
                <div class="col-xs-12 col-sm-9 col-md-10">
                    <p>' . $event['short_description'] . '</p>
                </div>
                <div class="col-xs-12 col-sm-3 col-md-2">
                    <dl>
                        <dt>Kostnad</dt>
                        <dd>' . ($event['fee'] == '0' ? 'Gratis' : $event['fee'] . ' kr') . '</dd>' . ($event['pre_registration'] == '1' ? '
                        <dd>Föranmälan</dd>' : '') . '
                    </dl>
                </div>
            </div>
        </div>';
    }

    $events_body[] = '
    </div>';
}

$contents['page_id'] = 'anmalningar';
$contents['date_created'] = '2014-11-15 20:53:18';
$contents['date_changed'] = $file_changed;
$contents['required_clearance'] = 'regular user';
$contents['name'] = 'Dina anmälningar';
$contents['title'] = 'Dina anmälningar';
$contents['head_local'] = '';

$contents['content_top'] = '';

$contents['content_main'] = '
<div class="col-xs-12">
    <h1>Arrange&shy;mang</h1>
    <p class="lead">Ett arr&shy;ange&shy;mang är något som händer på kon&shy;ventet. Helt enkelt.</p>
    <p>Alla grejer som händer på kon&shy;ventet räknas som arr&shy;ange&shy;mang och alla arr&shy;ange&shy;mang som regi&shy;stre&shy;rats hamnar här. Du kan för&shy;anmäla dig till vissa arr&shy;ange&shy;mang och om du står som ansvarig för ett arr&shy;ange&shy;mang kan du ändra i det.</p>
    <hr />
    <div class="row">
        <div class="col-xs-12 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4">
            <a href="' . Data\Settings::main('base_url') . 'index.php?page=eventedit&event_id=new" class="btn btn-primary btn-block" role="button"><i class="fa fa-plus"></i> Skapa ett eget arrangemang</a>
        </div>
    </div>
    <hr />
</div>
<div class="col-xs-12">
' . implode('', $events_body) . '
</div>
';

$contents['content_main'] = '
<div class="col-xs-12">
    <h1>Arrange&shy;mang</h1>
    <p class="lead">Ett arr&shy;ange&shy;mang är något som händer på kon&shy;ventet. Helt enkelt.</p>
    <p>Alla grejer som händer på kon&shy;ventet räknas som arr&shy;ange&shy;mang och alla arr&shy;ange&shy;mang som regi&shy;stre&shy;rats hamnar här. Du kan för&shy;anmäla dig till vissa arr&shy;ange&shy;mang och om du står som ansvarig för ett arr&shy;ange&shy;mang kan du ändra i det.</p>
    <hr />
    <div class="row">
        <div class="col-xs-12 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4">
            <a href="' . Data\Settings::main('base_url') . 'index.php?page=eventedit&event_id=new" class="btn btn-primary btn-block" role="button"><i class="fa fa-plus"></i> Skapa ett eget arrangemang</a>
        </div>
    </div>
    <hr />
</div>
<div class="col-xs-12">
' . implode('', $events_body) . '
</div>';

$contents['content_bottom'] = '';
