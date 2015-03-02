<?php

namespace Szandor\ConMan\Data;

use \Szandor\ConMan\Data as Data;
use \Szandor\ConMan\Logic as Logic;
use \Szandor\ConMan\View as View;

/**
 *
 */
class Event
{
    /**
     *
     */
    public function getData($event = false)
    {
        if ($event === false || !is_numeric($event)) {
            return false;
        } else {

            $event_request = array(
                'table' => 'events',
                'limit' => 1,
                'where' => array(
                    'col' => 'events_id',
                    'values' => $event,
                ),
            );

            $event_data = Data\Database::read($event_request, false);
            $out = (isset($event_data[0]) ? $event_data[0] : false);

            return $out;
        }
    }

    public function getEventSchedule($event = false)
    {
        if ($event === false || !is_numeric($event)) {
            $out = '
        <p>Inga registrerade speltillfällen kunde hittas. <span class="fa fa-frown-o"></span></p>';

            return $out;
        } else {

            $schedule_request = array(
                'table' => 'event_schedule',
                'where' => array(
                    'col' => 'events_id',
                    'values' => $event,
                ),
                'orderby' => 'date_start',
            );

            $schedule = Data\Database::read($schedule_request, false);

            if (isset($schedule[0])) {

                foreach ($schedule as $occasion) {
                    $occasion_duration = (strtotime($occasion['date_end']) - strtotime($occasion['date_start'])) / 60;
                    $weekday_start_no = date('w', strtotime($occasion['date_start']));
                    $weekday_end_no = date('w', strtotime($occasion['date_end']));
                    if ($weekday_start_no === $weekday_end_no || ($occasion_duration / 60) < 12) {
                        $weekday = Toolbox::numberToWeekday($weekday_start_no, true);
                    } else {
                        $weekday_start = Toolbox::numberToWeekday($weekday_start_no, true, true);
                        $weekday_end = Toolbox::numberToWeekday($weekday_end_no, false, true);
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
                    <td style="white-space:nowrap">' . $occasion['location'] . '</td>
                    <td><span class="hidden-xs">' . $occasion_notes . '</span><span class="visible-xs-block' . ($occasion_notes == '&nbsp;' ? '' : ' has-tooltip') . '"' . ($occasion_notes == '&nbsp;' ? '' : 'title="' . $occasion_notes . '"') . '>' . ($occasion_notes == '&nbsp;' ? '' : '<span class="fa fa-comment"></span>') . '</span></td>
                    <td><span class="fa fa-lock has-tooltip" title="Ställ in tillfälle"></span></td>
                    <td><span class="fa fa-times has-tooltip" title="Ta bort tillfälle"></span></td>
                </tr>';

                }

                $out = '
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Dag</th>
                    <th>Tid</th>
                    <th>Plats</th>
                    <th><span style="width:100%" class="hidden-xs">Anteckning</span></th>
                    <th colspan="2">&nbsp;</th>
                </tr>
            </thead>
            <tbody>';
                $out .= implode('', $schedule_body);
                $out .= '
            </tbody>
        </table>';
            } else {
                $out = '
        <p>Inga registrerade speltillfällen kunde hittas. <span class="fa fa-frown-o"></span></p>';
            }

            return $out;
        }
    }

    /**
     *
     */
    public function getAllEvents($function = 1, $list_unapproved = false)
    {
        $function = 1;

        $categories_request = array('table' => 'event_types', );
        $categories_raw = Data\Database::read($categories_request, false);
        foreach ($categories_raw as $key => $category) {
            $categories[$category['event_types_id']] = $category;
        }

        $request = array(
            'table' => 'events',
            'where' => array(
                'col' => 'functions_id',
                'values' => $function,
            ),
            'orderby' => 'title',
        );

        $data = Data\Database::read($request, false);
        if (isset($data[0])) {
            foreach ($data as $key => $value) {
                $value['event_type_text'] = $categories[$value['event_type']]['text'];
                $value['event_type_order'] = $categories[$value['event_type']]['order'];
                $out[$key] = $value;
            }
        } else {
            $out = false;
        }

        return $out;
    }

    public static function getEventTypes($selected = null)
    {
        $selected = ($selected == '' ? null : $selected);
        $categories_request = array(
            'table' => 'event_types',
            'orderby' => '`order`',
        );
        $categories_raw = Data\Database::read($categories_request, false);
        foreach ($categories_raw as $key => $category) {
            $selected_html = ($selected === null ? '' : ($selected == $category['event_types_id'] ? ' selected="selected"' : ''));
            $categories[] = '
                                <option' . $selected_html . ' value="' . $category['event_types_id'] . '">' . $category['text'] . '</option>';
        }

        $out = '
                            <select class="form-control" id="event_edit_type" name="event_edit_type" placeholder="Typ av arr">' . ($selected === null ? '
                                <option selected="selected" disabled="disabled">Typ av arr</option>' : '');
        $out .= implode('', $categories);
        $out .= '
                            </select>';

        return $out;
    }

}
