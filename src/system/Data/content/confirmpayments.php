<?php

namespace Szandor\ConMan;

use \Szandor\ConMan\Data as Data;
use \Szandor\ConMan\Logic as Logic;
use \Szandor\ConMan\View as View;

$crr = new Data\MySQLConventionRegistrationRepository();
$registrations = $crr->getRegistrations();

$registrations_body = [];
foreach ($registrations as $registration)
{
    $sum = intval($registration['price']);
    if ($registration['member']) {
        $sum += 100;
        $sum += intval($registration['if_member_price_reduced_by']);
    }
    if ($registration['mug']) {
        $sum += 70;
    }
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
                        <p>' . $sum . ' SEK</p>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-3">' . ($registration['payment_registered'] != null ?
                        '<button type="submit" class="btn btn-warning btn-block" id="payment_dismissed" name="submit_dostuff" value="payment_dismissed"><span class="fa fa-times"></span> Dra tillbaka<span class="hidden-sm hidden-md"> godkännande</span></button>' :
                        '<button type="submit" class="btn btn-success btn-block" style="margin-bottom:5px" id="payment_confirmed" name="submit_dostuff" value="payment_confirmed"><span class="fa fa-check-square-o"></span> God&shy;känn</button>'
                    ) . 
                    '</div>
                    <div class="col-xs-12 col-sm-12 col-md-3">
                        <p>' . (!empty($registration['username']) ? $registration['username']: '&nbsp;') . '</p>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-2">
                        <p>' . (!empty($registration['national_id_number']) ? $registration['national_id_number']: '&nbsp;') . '</p>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-7">
                        <p>' . (!empty($registration['email']) ? $registration['email']: '&nbsp;') . '</p>
                    </div>
                </div>
            </form>
            ';
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

$contents['content_bottom'] = '';
