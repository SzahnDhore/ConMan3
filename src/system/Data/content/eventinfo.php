<?php

namespace Szandor\ConMan;

use \Szandor\ConMan\Data as Data;
use \Szandor\ConMan\Logic as Logic;
use \Szandor\ConMan\View as View;

/**
 * We can do stuff here.
 */
$event_id = (isset($_GET['event_id']) ? ($_GET['event_id'] * 1) : false);

if ($event_id === false) {

} else {
    $event = new Data\Event;
    $event_info = $event->getData($event_id);
    if ($event_info !== false) {
        $event_user = new Data\User;
        $contact = $event_user->getData($event_info['contact']);
    }
}

/**
 * The following is simple contents.
 */
$contents['page_id'] = 'eventinfo';
$contents['date_created'] = $event_info['date_created'];
$contents['date_changed'] = $event_info['date_updated'];
$contents['required_clearance'] = 'regular user';
$contents['name'] = '';
$contents['title'] = $event_info['title'];

$contents['head_local'] = '        <script src="' . Data\Settings::main('base_url') . 'js/locales/bootstrap-datetimepicker.sv.js"></script>
        <script src="' . Data\Settings::main('base_url') . 'js/bootstrap-datetimepicker.min.js"></script>
        <link href="' . Data\Settings::main('base_url') . 'css/bootstrap-datetimepicker.css" rel="stylesheet" />';

$contents['content_top'] = '';

if ($event_info !== false) {

    $contents['content_main'] = '
<div class="col-xs-12">
    <div class="row">
        <div class="col-xs-12">
            <h1>' . $event_info['title'] . '</h1>
        </div>
        <div class="col-xs-12 col-sm-8 col-md-9">
            <p class="lead">' . $event_info['short_description'] . '</p>
            <p>' . $event_info['long_description'] . '</p>
        </div>
        <div class="col-xs-12 col-sm-4 col-md-3">
            <form role="form" id="event_status" name="event_status" action="' . Data\Settings::main('base_url') . 'dostuff.php" method="post">
                <div class="row">' . ($event_info['approved'] == '0' ? '
                    <div class="col-xs-12">
                        <div class="alert alert-warning" role="alert">
                            <p><strong>OBS!</strong> Det här evene&shy;manget väntar fort&shy;farande på god&shy;kän&shy;nande.</p>
                        </div>' . (in_array('PERM_DELETE_AND_CONFIRM_EVENTS', $_SESSION['user']['info']['permissions'], true) ? '
                        <div class="row">
                            <div class="col-xs-5 col-sm-12 col-lg-6">
                                <button type="button" class="btn btn-danger btn-block" style="margin-bottom:5px" id="event_status_tabort" data-toggle="modal" data-target="#event_status_confirm_tabort"><span class="fa fa-exclamation-triangle"></span> Ta bort</button>
                            </div>
                            <div class="col-xs-5 col-xs-offset-2 col-sm-offset-0 col-sm-12 col-lg-6">
                                <button type="submit" class="btn btn-success btn-block" style="margin-bottom:5px" id="event_status_godkann" name="submit_dostuff" value="event_status_godkann"><span class="fa fa-check-square-o"></span> God&shy;känn</button>
                            </div>
                        </div>' : '') . '
                    </div>' : (in_array('PERM_WITHDRAW_CONFIRMED_EVENTS', $_SESSION['user']['info']['permissions'], true) ? '
                    <div class="col-xs-12">
                        <button type="submit" class="btn btn-warning btn-block" id="event_status_ej_godkann" name="submit_dostuff" value="event_status_ej_godkann"><span class="fa fa-times"></span> Dra tillbaka<span class="hidden-sm hidden-md"> godkännande</span></button>
                    </div>' : '')) . '
                </div>
                <input type="hidden" name="events_id" value="' . $event_info['events_id'] . '" />
            </form>
            <hr />
            <div class="row">
                <div class="col-xs-12">
                    <dl class="dl">
                        <dt>Kontakt&shy;person</dt>
                        <dd>' . $contact['details']['given_name'] . ' ' . $contact['details']['family_name'] . '</dd>
                        <dd><a href="mailto:' . $contact['data']['email'] . '"></span>' . $contact['data']['email'] . '</a></dd>
                        <dt>Kost&shy;nad</dt>
                        <dd>' . ($event_info['fee'] == '0' ? 'Gratis' : $event_info['fee'] . ' kr') . '</dd>
                        <dt>För&shy;an&shy;mälan</dt>
                        <dd>' . ($event_info['pre_registration'] == '1' ? 'Ja' : 'Nej') . '</dd>
                    </dl>
                </div>
            </div>' . (in_array('PERM_EDIT_ALL_EVENTS', $_SESSION['user']['info']['permissions'], true) || $event_info['contact'] === $_SESSION['user']['info']['data']['id'] ? '
            <div class="row">
                <div class="col-xs-12">
                    <a class="btn btn-primary btn-block" style="margin-bottom:5px" id="event_edit_submit" name="submit_dostuff" value="event_edit_submit" href="' . Data\Settings::main('base_url') . 'index.php?page=eventedit&event_id=' . $event_id . '"><span class="fa fa-edit"></span> Ändra arrangemanget</a>
                </div>
            </div>' : '') . '
        </div>
        <div class="col-xs-12">
            <h2>Speltillfällen</h2>
' . $event->getEventSchedule($event_id) . (in_array('PERM_ADD_EVENT_TO_SCHEDULE', $_SESSION['user']['info']['permissions'], true) ? '
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#event_add_occassion"><span class="fa fa-plus"></span> Lägg till spel&shy;till&shy;fälle</button>' : '') . '
        </div>
    </div>
</div>

<div class="modal fade" id="event_status_confirm_tabort" tabindex="-1" role="dialog" aria-labelledby="event_status_confirm_tabort" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form role="form" id="form_event_delete" name="form_event_delete" action="' . Data\Settings::main('base_url') . 'dostuff.php" method="post">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Stäng</span></button>
                    <h4 class="modal-title" id="myModalLabel">Ta bort arrangemanget</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-xs-12">
                            <p>Är du helt säker på att du verk&shy;ligen verk&shy;ligen vill ta bort det här arr&shy;ange&shy;manget? Det går inte att ångra sig efteråt näm&shy;ligen. Visst, det går ju alltid att lägga in det igen, men då måste du ju veta precis vad som stod i texten eller få den som först skrev in den att skriva om den. Fast det är klart; måste det bort så måste det ju bort, så känn dig fri att plocka bort arr&shy;ange&shy;manget för all fram&shy;tid. Om du verk&shy;ligen vill.</p>
                            <input type="hidden" name="events_id" value="' . $event_info['events_id'] . '" />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" data-dismiss="modal"><span class="fa fa-arrow-circle-left"></span> Nej! Behåll!</button>
                    <button type="submit" class="btn btn-danger" id="event_delete" name="submit_dostuff" value="event_delete"><span class="fa fa-trash"></span> Ja! Plocka bort!</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="event_add_occassion" tabindex="-1" role="dialog" aria-labelledby="event_add_occassion" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form role="form" id="form_event_add_occassion" name="form_event_add_occassion" action="' . Data\Settings::main('base_url') . 'dostuff.php" method="post">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Stäng</span></button>
                    <h4 class="modal-title" id="myModalLabel">Lägg till speltillfälle</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-xs-12">
                            <div class="row">
                                <div class="col-xs-6 col-sm-3">
                                    <div class="form-group">
                                        <label for="event_add_occassion_time_start">Starttid</label> <span class="fa fa-question-circle fa-fw has-tooltip" data-placement="top" title="Start&shy;tid för spel&shy;till&shy;fället."></span>
                                        <div class="input-group form_datetime">
                                            <div class="input-group-addon"><span class="fa fa-clock-o"></span></div>
                                            <input type="text" class="form-control" id="event_add_occassion_time_start" name="event_add_occassion_time_start" placeholder="Starttid" readonly="readonly" style="cursor:pointer;background-color:#fff" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-6 col-sm-3">
                                    <div class="form-group">
                                        <label for="event_add_occassion_time_end">Sluttid</label> <span class="fa fa-question-circle fa-fw has-tooltip" data-placement="top" title="Slut&shy;tid för spel&shy;till&shy;fället."></span>
                                        <div class="input-group form_datetime">
                                            <div class="input-group-addon"><span class="fa fa-clock-o"></span></div>
                                            <input type="text" class="form-control" id="event_add_occassion_time_end" name="event_add_occassion_time_end" placeholder="Sluttid" readonly="readonly" style="cursor:pointer;background-color:#fff" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6">
                                    <div class="form-group">
                                        <label for="event_add_occassion_location">Plats</label> <span class="fa fa-question-circle fa-fw has-tooltip" data-placement="top" title="Var någonstans speltillfället sker."></span>
                                        <input type="text" class="form-control" id="event_add_occassion_location" name="event_add_occassion_location" placeholder="Plats" />
                                    </div>
                                </div>
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label for="event_add_occassion_notes">Notering</label> <span class="fa fa-question-circle fa-fw has-tooltip" data-placement="top" title="En kort och enkel notering. Behövs inte alltid."></span>
                                        <input type="text" class="form-control" id="event_add_occassion_notes" name="event_add_occassion_notes" placeholder="Notering" />
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="events_id" value="' . $event_info['events_id'] . '" />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-ban"></span> Avbryt</button>
                    <button type="submit" class="btn btn-primary" id="event_add_occassion_submit" name="submit_dostuff" value="event_add_occassion_submit"><span class="fa fa-plus"></span> Lägg till</button>
                </div>
            </form>
        </div>
    </div>
</div>
';

} else {

    $contents['content_main'] = '
<div class="col-xs-12">
    <h1>Arrangemanget kunde inte hittas...</h1>
    <p class="lead">Det verkar inte som om arrangemanget med ID# ' . $event_id . ' finns registrerat.</p>
    <p>Kanske är det så att arrangemanget har plockats bort av någon anledning, kanske blev det något fel när vi skulle hämta data, kanske är helt enkelt länken fel. I vilket fall som helst så finns inte arrangemanget du letar efter här. Gå tillbaka till framsidan och leta vidare.</p>
</div>
';

}

$contents['content_bottom'] = '
<script type="text/javascript">
    $("#event_add_occassion_time_start,#event_add_occassion_time_end").datetimepicker({
        format: "yyyy-mm-dd hh:ii",
        autoclose: true,
        startDate: "2015-07-03 10:00",
        endDate: "2015-07-05 16:00",
        weekStart: 1,
        language: "sv",
        minuteStep: 15,
    });

    $("#fullkomligt_radera_order,#fullkomligt_radera_order_cancel").click(function() {
        $("#confirm_delete").modal("toggle");
    });
</script>';
