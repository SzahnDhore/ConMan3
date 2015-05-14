<?php

namespace Szandor\ConMan;

use \Szandor\ConMan\Data as Data;
use \Szandor\ConMan\Logic as Logic;
use \Szandor\ConMan\View as View;

$crr = new Data\MySQLConventionRegistrationRepository();
$registrations = $crr->getRegistrationData();

$entranceTypes = $crr->getAllEntranceTypesForAllPeriods();
$entranceJavascriptData = [];
foreach ($entranceTypes as $key => $entrance)
{
    $desc = str_replace('Inträde WSK 2015, ', '', $entrance['description']);
    $desc = ucfirst(str_replace(', jag vill bara stöja föreningen och/eller är under 13 år', '', $desc));
    $entranceTypes[$key]['description'] = $desc;
    $entranceJavascriptData[] = $entrance['convention_registration_form_id'] . 
                                ': { price: ' . $entrance['price'] . 
                                ', member_reduction: ' . $entrance['if_member_price_reduced_by']. ' }';
}

//die(var_dump($registrations));
$registrations_body = [];
$registrationsJavascriptData = [];
foreach ($registrations as $registration)
{
    $entranceDropdown = '';
    foreach ($entranceTypes as $entrance)
    {
        if ($entrance['belongs_to_registration_period'] == $registration['belongs_to_registration_period'])
        {
            $entranceDropdown .= '<option value="' . $entrance['convention_registration_form_id'] .
                                    '">' . $entrance['description'] . '</option>';
        }
    }

    $national_id_number = '';
    if (isset($registration['national_id_number']) && !empty($registration['national_id_number']))
    {
        $national_id_number = $registration['national_id_number'];
    } else {
        $user = new Data\User();
        $stagedChanges = $user->getStagedChangesForUserId($registration['users_id']);
        if ($stagedChanges !== false && isset($stagedChanges['national_id_number']))
        {
            $national_id_number = $stagedChanges['national_id_number'];
        }
    }

    $paymentRegistered = $registration['payment_registered'] != null;
    $registrations_body[] = '
            <form id="form_confirm_payment" name="form_confirm_payment" class="form-horizontal" action="dostuff.php" method="post">
                <input type="hidden" id="form_confirm_payment_convention_registrations_id" name="form_confirm_payment_convention_registrations_id" value="' . $registration['convention_registrations_id'] . '">
                <input type="hidden" id="form_confirm_payment_users_id" name="form_confirm_payment_users_id" value="' . $registration['users_id'] . '">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-3">
                        <p>' . $registration['date_created'] . '</p>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-4">
                        <p>' . (!empty($registration['given_name']) && !empty($registration['family_name']) ? 
                        $registration['given_name'] . ', ' . $registration['family_name'] : '&nbsp;') . '</p>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-2">
                        <p><span id="form_confirm_payment_sum_' . $registration['users_id'] . '">0</span> SEK</p>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-3">' . ($paymentRegistered ?
                        '<button type="submit" class="btn btn-warning btn-block" id="payment_dismissed" name="submit_dostuff" value="payment_dismissed"><span class="fa fa-times"></span> Dra tillbaka<span class="hidden-sm hidden-md"> godkännande</span></button>' :
                        '<button type="submit" class="btn btn-success btn-block" style="margin-bottom:5px" id="payment_confirmed" name="submit_dostuff" value="payment_confirmed"><span class="fa fa-check-square-o"></span> God&shy;känn</button>'
                    ) . 
                    '</div>
                    <div class="col-xs-12 col-sm-12 col-md-3">
                        <p>' . (!empty($registration['username']) ? $registration['username']: '&nbsp;') . '</p>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-2">
                        <p>' . (!empty($national_id_number) ? $national_id_number: '&nbsp;') . '</p>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-7">
                        <p>' . (!empty($registration['email']) ? $registration['email']: '&nbsp;') . '</p>
                    </div>

                    <div class="col-xs-2 col-sm-2 col-md-1">
                        <p><b>Inträde</b></p>
                    </div>
                    <div class="col-xs-10 col-sm-10 col-md-2">
                        <select class="form-control input-sm" id="form_confirm_payment_entrance_' .
                        $registration['users_id'] . '" name="form_confirm_payment_entrance_type"' .
                        ($paymentRegistered ? " disabled=disabled" : "") .
                        '>' . $entranceDropdown . '
                        </select>
                    </div>

                    <div class="col-xs-4 col-sm-4 col-md-2">
                        <div class="checkbox">
                            <label><input type="checkbox" name="member" value="1" id="form_confirm_payment_member_' .
                            $registration['users_id'] . '" class="checkbox_member"' .
                            ($paymentRegistered ? " disabled=disabled" : "") .
                            '>Medlem - 100kr</label>
                        </div>
                    </div>

                    <div class="col-xs-4 col-sm-4 col-md-2">
                        <div class="checkbox">
                            <label><input type="checkbox" name="mug" value="1" id="form_confirm_payment_mug_' .
                            $registration['users_id'] . '" class="checkbox_mug"' .
                            ($paymentRegistered ? " disabled=disabled" : "") .
                            '>Konventsmugg - 70kr</label>
                        </div>
                    </div>

                    <div class="col-xs-4 col-sm-4 col-md-2">
                        <div class="checkbox">
                            <label><input type="checkbox" name="sleeping_room" value="1" id="form_confirm_payment_sleeping_room_' .
                            $registration['users_id'] . '" class="checkbox_sleeping_room"' .
                            ($paymentRegistered ? " disabled=disabled" : "") .
                            '>Sovsal - 0kr</label>
                        </div>
                    </div>
                </div>
            </form>
            ';
    $registrationsJavascriptData[] = '
                                ' . $registration['users_id'] . 
                                ': { entrance: ' . $registration['convention_registration_form_id'] . 
                                ', member: ' . ($registration['member'] == '1' ? 'true' : 'false') . 
                                ', mug: ' . ($registration['mug'] == '1' ? 'true' : 'false') . 
                                ', sleeping_room: ' . ($registration['sleeping_room'] == '1' ? 'true' : 'false') . ' }';
}

/**
 * The following is simple contents.
 */
$contents['page_id'] = 'confirmpayments';
$contents['date_created'] = gmdate("Y-m-d H:i:s", filemtime(__FILE__));
$contents['date_changed'] = gmdate("Y-m-d H:i:s", filemtime(__FILE__));
$contents['required_clearance'] = 'stab';
$contents['name'] = 'Godkann betalningar';
$contents['title'] = 'Godkann betalningar';

$contents['head_local'] = '';

$contents['content_top'] = '';

$contents['content_main'] = '<div class="col-xs-12">
    <h1>Godkänn betalningar</h1>
    <p class="lead">Godkänn betalningar för årets konvent här.</p>
    <div class="col-xs-12">
        <div class="row">
            <div class="col-xs-12">' . implode('<hr />', $registrations_body) . '
            </div>
        </div>
    </div>
</div>';

$contents['content_bottom'] = '
<script>
    $(document).ready(function() {
        var data = { ' . implode(', ', $registrationsJavascriptData) . ' };
        var entranceData = { ' . implode(', ', $entranceJavascriptData) . ' };

        for (var key in data) {
            if (data[key][\'member\']) { $(\'#form_confirm_payment_member_\' +key).prop(\'checked\', true); }
            if (data[key][\'mug\']) { $(\'#form_confirm_payment_mug_\' +key).prop(\'checked\', true); }
            if (data[key][\'sleeping_room\']) { $(\'#form_confirm_payment_sleeping_room_\' +key).prop(\'checked\', true); }
            $(\'#form_confirm_payment_entrance_\' +key).val(data[key][\'entrance\']);

            $(\'#form_confirm_payment_entrance_\' +key).change({key: key}, function(event) {
                data[event.data.key][\'entrance\'] = $(this).val();
                updatePrice(event.data.key);
            });

            $(\'#form_confirm_payment_member_\' +key).change({key: key}, function(event) {
                data[event.data.key][\'member\'] = this.checked;
                updatePrice(event.data.key);
            });

            $(\'#form_confirm_payment_mug_\' +key).change({key: key}, function(event) {
                data[event.data.key][\'mug\'] = this.checked;
                updatePrice(event.data.key);
            });
            
            updatePrice(key);
        }
        
        function updatePrice(key) {
            var sum = 0;
            var entranceId = data[key][\'entrance\'];
            sum += entranceData[entranceId][\'price\'];
            if (data[key][\'member\']) {
                sum += 100;
                sum += entranceData[entranceId][\'member_reduction\'];
            }
            if (data[key][\'mug\']) { sum += 70; }
            $(\'#form_confirm_payment_sum_\' +key).text(sum);
        }
    });
</script>';
