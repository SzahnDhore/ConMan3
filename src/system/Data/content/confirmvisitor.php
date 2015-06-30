<?php

namespace Szandor\ConMan;

use \Szandor\ConMan\Data as Data;
use \Szandor\ConMan\Logic as Logic;
use \Szandor\ConMan\View as View;

$crr = new Data\MySQLConventionRegistrationRepository();
$ur = new Data\MySQLUserRepository();
$registrations = $crr->getRegistrationData(true);
$confirmed_visitors = $crr->getAllVisitorUserIds();

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

$registrations_body = [];
$registrationsJavascriptData = [];
foreach ($registrations as $registration)
{
    $registration_confirmed = false;
    foreach ($confirmed_visitors as $key => $value)
    {
        if ($registration['users_id'] == $value['users_id'])
        {
            $registration_confirmed = true;
            break;
        }
    }
    if ($registration_confirmed) { continue; }

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
    $userIsOrganizer = $ur->userIsOrganizer($registration['users_id']);
    $tmp = Data\User::getGroupsForUser($registration['users_id']);
    $userGroups = array();
    foreach($tmp as $group)
    {
        if ($group['description'] != 'regular user')
        {
            $userGroups[] = $group['description'];
        }
    }

    $infoBox = '';
    if (!empty($userGroups))
    {
        $infoBox = '<div class="alert alert-info" role="alert"><p><strong>OBS!</strong> ' . implode(', ', $userGroups) . '</p></div>';
    }
    else if ($userIsOrganizer)
    {
        $infoBox = '<div class="alert alert-info" role="alert"><p><strong>OBS!</strong> Arrangör</p></div>';
    }
    $registrations_body[] = '
            <form id="form_confirm_visit" name="form_confirm_visit" class="form-horizontal" action="dostuff.php" method="post">
                <input type="hidden" id="form_confirm_visit_convention_registrations_id" name="form_confirm_visit_convention_registrations_id" value="' . $registration['convention_registrations_id'] . '">
                <input type="hidden" id="form_confirm_visit_users_id" name="form_confirm_visit_users_id" value="' . $registration['users_id'] . '">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-3">
                        <p>' . $registration['date_created'] . '</p>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-2">
                        <p>' . (!empty($registration['given_name']) && !empty($registration['family_name']) ? 
                        $registration['given_name'] . ', ' . $registration['family_name'] : '&nbsp;') . '</p>
                    </div>
                    <div class="col-xs-10 col-sm-10 col-md-2">
                        <select class="form-control input-sm" id="form_confirm_visit_entrance_group_' .
                        $registration['users_id'] . '" name="form_confirm_visit_entrance_group_type">
                            <option value="0">Besökare</option>
                            <option value="1">Stab</option>
                            <option value="2">Arrangör</option>
                            <option value="3">Medarrangör</option>
                            <option value="4">Jobbare</option>
                            <option value="5">Fotograf</option>
                            <option value="6">Sommarjobbare</option>
                            <option value="7">Butik</option>
                        </select>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-2">
                        <p><span id="form_confirm_visit_sum_' . $registration['users_id'] . '">0</span> SEK</p>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-3">
                        <button type="submit" class="btn btn-success btn-block" style="margin-bottom:5px" id="visitor_confirmed" name="submit_dostuff" value="visitor_confirmed"><span class="fa fa-check-square-o"></span> Bekräfta inträde</button>
                    </div>

                    <div class="col-xs-2 col-sm-2 col-md-1">
                        <p><b>Inträde</b></p>
                    </div>
                    <div class="col-xs-10 col-sm-10 col-md-2">
                        <select class="form-control input-sm" id="form_confirm_visit_entrance_' .
                        $registration['users_id'] . '" name="form_confirm_visit_entrance_type" disabled=disabled' .
                        '>' . $entranceDropdown . '
                        </select>
                    </div>

                    <div class="col-xs-4 col-sm-4 col-md-2">
                        <div class="checkbox">
                            <label><input type="checkbox" name="member" value="1" id="form_confirm_visit_member_' .
                            $registration['users_id'] . '" class="checkbox_member" disabled=disabled' .
                            '>Medlem - 100kr</label>
                        </div>
                    </div>

                    <div class="col-xs-4 col-sm-4 col-md-2">
                        <div class="checkbox">
                            <label><input type="checkbox" name="mug" value="1" id="form_confirm_visit_mug_' .
                            $registration['users_id'] . '" class="checkbox_mug" disabled=disabled' .
                            '>Konventsmugg - 70kr</label>
                        </div>
                    </div>

                    <div class="col-xs-4 col-sm-4 col-md-2">
                        <div class="checkbox">
                            <label><input type="checkbox" name="sleeping_room" value="1" id="form_confirm_visit_sleeping_room_' .
                            $registration['users_id'] . '" class="checkbox_sleeping_room" disabled=disabled' .
                            '>Sovsal - 0kr</label>
                        </div>
                    </div>

                    <div class="col-xs-4 col-sm-4 col-md-2">
                        ' . $infoBox . '
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

$contents['page_id'] = 'confirmvisitor';
$contents['date_created'] = gmdate("Y-m-d H:i:s", filemtime(__FILE__));
$contents['date_changed'] = gmdate("Y-m-d H:i:s", filemtime(__FILE__));
$contents['required_clearance'] = 'stab';
$contents['name'] = 'Stampla in';
$contents['title'] = 'Stampla in';

$contents['head_local'] = '';

$contents['content_top'] = '';

$contents['content_main'] = '<div class="col-xs-12">
    <h1>Bekräfta inträde</h1>
    <p class="lead">Bekräfta inträde för årets konvent här.</p>
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
            if (data[key][\'member\']) { $(\'#form_confirm_visit_member_\' +key).prop(\'checked\', true); }
            if (data[key][\'mug\']) { $(\'#form_confirm_visit_mug_\' +key).prop(\'checked\', true); }
            if (data[key][\'sleeping_room\']) { $(\'#form_confirm_visit_sleeping_room_\' +key).prop(\'checked\', true); }
            $(\'#form_confirm_visit_entrance_\' +key).val(data[key][\'entrance\']);

            $(\'#form_confirm_visit_entrance_\' +key).change({key: key}, function(event) {
                data[event.data.key][\'entrance\'] = $(this).val();
                updatePrice(event.data.key);
            });

            $(\'#form_confirm_visit_member_\' +key).change({key: key}, function(event) {
                data[event.data.key][\'member\'] = this.checked;
                updatePrice(event.data.key);
            });

            $(\'#form_confirm_visit_mug_\' +key).change({key: key}, function(event) {
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
            $(\'#form_confirm_visit_sum_\' +key).text(sum);
        }
    });
</script>';