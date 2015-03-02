<?php

namespace Szandor\ConMan;

use \Szandor\ConMan\Data as Data;
use \Szandor\ConMan\Logic as Logic;
use \Szandor\ConMan\View as View;

/**
 * We can do stuff here.
 */
if (isset($_GET['event_id']) && is_numeric($_GET['event_id'])) {
    $event = new Data\Event;
    $event_info = $event->getData($_GET['event_id']);
    if ($event_info !== false) {
        $_GET['form_data_event_edit_title'] = $event_info['title'];
        $_GET['form_data_event_edit_type'] = $event_info['event_type'];
        $_GET['form_data_event_edit_short_description'] = $event_info['short_description'];
        $_GET['form_data_event_edit_long_description'] = $event_info['long_description'];
        $_GET['form_data_event_edit_pre_registration'] = $event_info['pre_registration'];
        $_GET['form_data_event_edit_fee'] = $event_info['fee'];
        $_GET['form_data_event_edit_email'] = $event_info['contact_email'];
    }
}

if ($_GET['event_id'] !== 'new') {
    if ($event_info['contact'] == $_SESSION['user']['info']['data']['id'] || $_SESSION['user']['info']['data']['account_type'] > 2) {
    } else {
        header('Location: ' . Data\Settings::main('base_url') . 'index.php?page=arrangemang');
        exit ;
    }
}

/**
 * The following is simple contents.
 */
$contents['page_id'] = 'eventinfo';
$contents['date_created'] = '2014-11-15 20:53:18';
$contents['date_changed'] = gmdate("Y-m-d H:i:s", filemtime(__FILE__));
$contents['required_clearance'] = '2';
$contents['name'] = '';
$contents['title'] = 'Skapa nytt arrangemang';
$contents['head_local'] = '';

$contents['content_top'] = '';

$contents['content_main'] = '
<div class="col-xs-12">
    <div class="row">
        <div class="col-xs-12">
            <h1>Skapa nytt arrange&shy;mang</h1>
        </div>
        <div class="col-xs-12 col-sm-8 col-md-9">
            <p class="lead">Fyll i formu&shy;läret nedan och skicka in för att an&shy;mäla ditt arrange&shy;mang till <abbr title="Wexio SpelKonvent" class="initialism">WSK</abbr>.</p>
            <div class="alert alert-warning" role="alert">
                <p><strong>OBS!</strong> Att skicka in ett arrange&shy;mang betyder inte att det auto&shy;matiskt kommer med på WSK2015. Arrange&shy;manget måste först god&shy;kännas av WSK.</p>
            </div>

            <form role="form" id="event_edit" name="event_edit" action="dostuff.php" method="post">

                <div class="well bs-component">
                    <fieldset>

                        <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group' . (isset($_GET['form_error_event_edit_title_duplicate']) || isset($_GET['form_error_event_edit_title_length']) ? ' has-error' : '') . '">
                                    <label for="event_edit_title">Namn på arrangemanget</label> <span class="fa fa-question-circle fa-fw has-tooltip" data-placement="top" title="Något lagom långt som alla kan känna igen och uttala."></span>
                                    <input type="text" class="form-control" id="event_edit_title" name="event_edit_title" placeholder="Namn på arrangemanget" value="' . (isset($_GET['form_data_event_edit_title']) ? $_GET['form_data_event_edit_title'] : '') . '" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-4">
                                <div class="form-group' . (isset($_GET['form_error_event_edit_type']) ? ' has-error' : '') . '">
                                    <label for="event_edit_type">Typ av arr</label> <span class="fa fa-question-circle fa-fw has-tooltip" data-placement="top" title="Vilken sorts arrangemang är det?"></span>' . Data\Event::getEventTypes(isset($_GET['form_data_event_edit_type']) ? $_GET['form_data_event_edit_type'] : null) . '
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-8">
                                <div class="form-group' . (isset($_GET['form_error_event_edit_short_description']) ? ' has-error' : '') . '">
                                    <label for="event_edit_short_description">Kort beskrivning</label> <span class="fa fa-question-circle fa-fw has-tooltip" data-placement="top" title="Kort och kärnfullt. Kan vara flera meningar."></span>
                                    <input type="text" class="form-control" id="event_edit_short_description" name="event_edit_short_description" placeholder="Kort beskrivning" value="' . (isset($_GET['form_data_event_edit_short_description']) ? $_GET['form_data_event_edit_short_description'] : '') . '" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group' . (isset($_GET['form_error_event_edit_long_description']) || isset($_GET['form_error_event_edit_long_description_shorter']) ? ' has-error' : '') . '">
                                    <label for="event_edit_long_description">Lång beskrivning</label> <span class="fa fa-question-circle fa-fw has-tooltip" data-placement="top" title="Du behöver inte vara onödigt detaljerad här, men tänk på att ta med all information som deltagarna behöver veta."></span>
                                    <textarea class="form-control" rows="3" id="event_edit_long_description" name="event_edit_long_description" placeholder="Lång beskrivning">' . (isset($_GET['form_data_event_edit_long_description']) ? $_GET['form_data_event_edit_long_description'] : '') . '</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xs-6 col-sm-6 col-md-3">
                                <label class="checkbox-inline">
                                    <input type="checkbox"' . (isset($_GET['form_data_event_edit_pre_registration']) && $_GET['form_data_event_edit_pre_registration'] == '1' ? ' checked="checked"' : '') . ' id="event_edit_pre_registration" name="event_edit_pre_registration" value="1" /> Föranmälan
                                </label> <span class="fa fa-question-circle fa-fw has-tooltip" data-placement="top" title="Om du vill ha in föranmälningar till ditt arrangemang kan du ange det här."></span>
                            </div>
                            <div class="col-xs-6 col-sm-6 col-md-3">
                                <div class="form-group">
                                    <label for="event_edit_fee" class="sr-only">Kostnad</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control has-tooltip" id="event_edit_fee" name="event_edit_fee" data-placement="top" title="Om arrangemanget kostar något anger du det här. Tänk på att inte ta ut kostnad i onödan." placeholder="Kostnad" value="' . (isset($_GET['form_data_event_edit_fee']) ? $_GET['form_data_event_edit_fee'] : '') . '" />
                                        <span class="input-group-addon">kr</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6">
                                <div class="form-group' . (isset($_GET['form_error_event_edit_email']) ? ' has-error' : '') . '">
                                    <label for="event_edit_email" class="sr-only">Epost</label>
                                    <input type="text" class="form-control has-tooltip" id="event_edit_email" name="event_edit_email" placeholder="Epost" data-placement="top" title="Är oftast samma som din personliga adress, men kan ändras om det skulle behövas." value="' . (isset($_GET['form_data_event_edit_email']) ? $_GET['form_data_event_edit_email'] : $_SESSION['user']['info']['data']['email']) . '" />
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-6">
                                <input type="hidden" id="event_id" name="event_id" value="' . (isset($_GET['event_id']) ? $_GET['event_id'] : false) . '" />
                                <button type="submit" class="btn btn-primary btn-block" id="event_edit_submit" name="submit_dostuff" value="' . ($_GET['event_id'] == 'new' ? 'event_new' : 'event_edit') . '"><i class="fa fa-send"></i> Skicka in<span class="hidden-xs"> arrangemanget</span></button>
                            </div>
                        </div>

                    </fieldset>
                </div>
            </form>

        </div>
        <div class="col-xs-12 col-sm-4 col-md-3">
            <h2><small>Att tänka på:</small></h2>
            <p>Arrange&shy;mang som är uppen&shy;bara spam&shy;arrange&shy;mang raderas utan att du kontaktas.</p>
            <p>Vi kommer att kon&shy;takta dig via epost för att disku&shy;tera din anmälan. Därför är det viktigt att epost&shy;adressen du har angivit är korrekt.</p>
            <p>Du kommer att stå som kontakt&shy;person för arrange&shy;manget. Du kan däremot ange en annan epost&shy;adress för arrange&shy;manget än den du regi&shy;strerat som användare.</p>
            <p>Ditt arrange&shy;mang kommer inte att synas för andra förrän det har godkänts. Om arrange&shy;manget av någon anledning inte skulle godkännas kommer du att kontaktas.</p>
            <p>Om ditt arrange&shy;mang inte har god&shy;känts, du inte har kon&shy;taktats och det har gått två veckor sedan du skickade in det, kontakta oss så vi inte har missat det.</p>
            <p>Alla arrange&shy;mang som inte är god&shy;kända kommer att raderas helt i samband med konventet.</p>
        </div>
    </div>';

$contents['content_bottom'] = '';
