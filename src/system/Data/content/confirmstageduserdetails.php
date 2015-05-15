<?php

namespace Szandor\ConMan;

use \Szandor\ConMan\Data as Data;
use \Szandor\ConMan\Logic as Logic;
use \Szandor\ConMan\View as View;


$ur = new Data\MySQLUserRepository();
$staged_changes = $ur->getAllStagedUserDetails();

$user_details_body = [];

foreach ($staged_changes['staged_data'] as $staged_change)
{
    $current_user_data = array();
    foreach ($staged_changes['current_user_data'] as $key => $value)
    {
        if ($value['user_staged_changes_id'] == $staged_change['user_staged_changes_id'])
        {
            $current_user_data = $value;
            break;
        }
    }
    if (empty($current_user_data)) { continue; }

    $gender = '';
    if ($current_user_data['male'] == '0') { $gender = 'Kvinna'; }
    if ($current_user_data['male'] == '1') { $gender = 'Man'; }
    $user_details_body[] = '
            <form id="form_confirm_user_details" name="form_confirm_user_details" class="form-horizontal" action="dostuff.php" method="post">
                <input type="hidden" id="form_confirm_user_details_id" name="form_confirm_user_details_id" value="' . $staged_change['user_staged_changes_id'] . '">
                <input type="hidden" id="form_confirm_user_details_users_id" name="form_confirm_user_details_users_id" value="' . $staged_change['users_id'] . '">
                <div class="row">
                    ' . generateColumnData('Förnamn', 'given_name', $current_user_data['given_name'], $staged_change['given_name']) . '
                    ' . generateColumnData('Efternamn', 'family_name', $current_user_data['family_name'], $staged_change['family_name']) . '
                    ' . generateColumnData('Adress', 'address', $current_user_data['address'], $staged_change['address']) . '
                    ' . generateColumnData('Postnr', 'postal_code', $current_user_data['postal_code'], $staged_change['postal_code']) . '
                    ' . generateColumnData('Stad', 'city', $current_user_data['city'], $staged_change['city']) . '
                    ' . generateColumnData('Telenr.', 'phone_number', $current_user_data['phone_number'], $staged_change['phone_number']) . '
                    ' . generateColumnData('Epost', 'email', $current_user_data['email'], $staged_change['email']) . '
                    ' . generateColumnData('Personnr', 'national_id_number', $current_user_data['national_id_number'], $staged_change['national_id_number']) . '
                    ' . generateColumnData('Land', 'country', $current_user_data['country'], $staged_change['country']) . '
                    ' . generateColumnData('Binärt kön', 'gender', $gender, $staged_change['male'], array('Kvinna', 'Man')) . '
                </div>
                <div class="row">
                    <div class="col-xs-4 col-sm-3 col-sm-offset-4">
                        <button type="submit" class="btn btn-warning btn-block" id="user_details_dismissed" name="submit_dostuff" value="user_details_dismissed"><span class="fa fa-times"></span> Godkänn ej</button>
                    </div>
                    <div class="col-xs-4 col-xs-offset-4 col-sm-3 col-sm-offset-0">
                        <button type="submit" class="btn btn-success btn-block" style="margin-bottom:5px" id="user_details_confirmed" name="submit_dostuff" value="user_details_confirmed"><span class="fa fa-check-square-o"></span> God&shy;känn</button>
                    </div>
                </div>
            </form>
            ';
}

function generateColumnData($label, $key, $current_data, $staged_data, $options = array())
{
    $dropdownOptions = '';
    for ($i = 0; $i<count($options); $i++)
    {
        $dropdownOptions .= '<option' . ($staged_data == $i ? ' selected="selected"' : '') . ' value="' . $i . '">' . $options[$i] . '</option>';
    }
    
    return '<div class="col-xs-12 col-sm-12">
                <div class="form-group">
                    <label for="form_confirm_user_details_' . $key . '" class="col-xs-12 col-sm-2 control-label">' . $label . '</label>
                    <div class="col-xs-12 col-sm-5">
                        <input type="text" class="form-control input-sm" id="form_confirm_user_details_current_' . $key . '" name="form_confirm_user_details_' . $key . '" placeholder="' . $label . '" value="' . (empty($current_data) ? '---' : $current_data) . '" disabled="disabled">
                    </div>
                    ' . (empty($options) ? 
                    '<div class="col-xs-12 col-sm-5">
                        <input type="text" class="form-control input-sm" id="form_confirm_user_details_' . $key . '" name="form_confirm_user_details_' . $key . '" placeholder="' . $label . '" value="' . $staged_data . '">
                    </div>' : 
                    '<div class="col-xs-12 col-sm-5">
                        <select class="form-control input-sm" id="form_confirm_user_details_' . $key . '" name="form_confirm_user_details_' . $key . '">' . $dropdownOptions . '
                        </select>
                    </div>'
                    ) . '
                </div>
            </div>';
}

$contents['page_id'] = 'confirmstageduserdetails';
$contents['date_created'] = gmdate("Y-m-d H:i:s", filemtime(__FILE__));
$contents['date_changed'] = gmdate("Y-m-d H:i:s", filemtime(__FILE__));
$contents['required_clearance'] = 'stab';
$contents['name'] = 'Godkann medlemsuppgifter';
$contents['title'] = 'Godkann medlemsuppgifter';

$contents['head_local'] = '';

$contents['content_top'] = '';

$contents['content_main'] = '<div class="col-xs-12">
    <h1>Godkänn medlemsuppgifter</h1>
    <p class="lead">Godkänn medlemsuppgifter för systemets användare här.</p>
    <div class="col-xs-12">
        <div class="row">
            <div class="col-xs-12">' . implode('<hr />', $user_details_body) . '
            </div>
        </div>
    </div>
</div>';

$contents['content_bottom'] = '';
