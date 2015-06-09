<?php

namespace Szandor\ConMan\Data;

use \Szandor\ConMan\Data as Data;
use \Szandor\ConMan\Logic as Logic;
use \Szandor\ConMan\View as View;

class MySQLConventionRegistrationRepository implements IConventionRegistrationRepository
{
    public function getRegistrationByUserId($userId)
    {
        if ($userId === false || !is_numeric($userId)) {
            return false;
        } else {
            $users_request = array(
                'table' => 'convention_registrations',
                'limit' => 1,
                'where' => array(
                    'col' => 'users_id',
                    'values' => $userId,
                ),
            );
            return Data\Database::read($users_request, false);
        }
    }

    public function addRegistration($registrationData)
    {
        $request = array(
            'table' => 'convention_registrations',
            'data' => array( array(
                    'users_id' => $registrationData['users_id'],
                    'convention_registration_form_id' => $registrationData['entrance_type'],
                    'member' => (isset($registrationData['member']) && $registrationData['member'] == '1' ? '1' : 0),
                    'mug' => (isset($registrationData['mug']) && $registrationData['mug'] == '1' ? '1' : 0),
                    'sleeping_room' => (isset($registrationData['sleeping_room']) && $registrationData['sleeping_room'] == '1' ? '1' : 0),
                    'payment_registered' => null
                )),
            );
        $registration_id = Data\Database::create($request, false);
    }

    public function updateRegistration($registrationId, $numberOfUpdates, $registrationData)
    {
        $request = array(
                'table' => 'convention_registrations',
                'id' => $registrationId,
                'values' => array(
                    'users_id' => $registrationData['users_id'],
                    'convention_registration_form_id' => $registrationData['entrance_type'],
                    'member' => (isset($registrationData['member']) && $registrationData['member'] == '1' ? '1' : 0),
                    'mug' => (isset($registrationData['mug']) && $registrationData['mug'] == '1' ? '1' : 0),
                    'sleeping_room' => (isset($registrationData['sleeping_room']) && $registrationData['sleeping_room'] == '1' ? '1' : 0),
                    'payment_registered' => null,
                    'number_of_updates' => intval($numberOfUpdates) + 1
                ),
            );
        return Data\Database::update($request, false);
    }
    
    public function getNumberOfUnconfirmedPayments()
    {
        $unconfirmed_payments_request = 'SELECT COUNT(*) FROM `szcm3_convention_registrations` WHERE payment_registered is NULL;';
        $tmp = Data\Database::read_raw_sql($unconfirmed_payments_request, array());
        return $tmp[0]['COUNT(*)'];
    }

    public function getRegistrations()
    {
        $users_request = array(
            'table' => 'convention_registrations'
        );
        return Data\Database::read($users_request, false);
    }

    public function getRegistrationData()
    {
        $registrations_request = '
                            SELECT szcm3_convention_registrations.*,
                                szcm3_convention_registration_form.if_member_price_reduced_by,
                                szcm3_convention_registration_form.belongs_to_registration_period,
                                szcm3_convention_registration_form.price,
                                szcm3_users.username,
                                szcm3_users.email,
                                szcm3_user_details.given_name,
                                szcm3_user_details.family_name,
                                szcm3_user_details.national_id_number
                            FROM `szcm3_convention_registrations`
                                LEFT JOIN `szcm3_convention_registration_form` ON
                                    szcm3_convention_registrations.convention_registration_form_id=
                                    szcm3_convention_registration_form.convention_registration_form_id
                                LEFT JOIN `szcm3_users` ON
                                    szcm3_convention_registrations.users_id=
                                    szcm3_users.users_id
                                LEFT JOIN `szcm3_user_details` ON
                                    szcm3_convention_registrations.users_id=
                                    szcm3_user_details.users_id
                                WHERE (payment_registered > now() - INTERVAL 3 DAY) OR
                                    payment_registered is NULL
                            ORDER BY szcm3_convention_registrations.date_created DESC;';
            return Data\Database::read_raw_sql($registrations_request, array());
    }
    
    public function getAllEntranceTypesForAllPeriods()
    {
        $request = array(
            'table' => 'convention_registration_form'
        );
        return Data\Database::read($request, false);
    }

    public function confirmPayment($paymentId)
    {
        $request = array(
                'table' => 'convention_registrations',
                'id' => $paymentId,
                'values' => array(
                    'payment_registered' => date('Y-m-d H:i:s')
                ),
            );
        return Data\Database::update($request, false);
    }

    public function dismissPayment($paymentId)
    {
        $request = array(
                'table' => 'convention_registrations',
                'id' => $paymentId,
                'values' => array(
                    'payment_registered' => NULL
                ),
            );
        return Data\Database::update($request, false);
    }
    
    public function getTimeDifferenceRegistrationCreatedAndPaymentRegistered($daysBackInTime)
    {
        if (!is_numeric($daysBackInTime)) { return false; }

        $db_request = 'SELECT * FROM (SELECT TIME_TO_SEC (TIMEDIFF(`payment_registered`, `date_created`)) as diff FROM `szcm3_convention_registrations` WHERE `payment_registered` > now() - INTERVAL ? DAY ) as a WHERE a.diff is not NULL;';
        $statistics = Data\Database::read_raw_sql($db_request, array($daysBackInTime));
        if (empty($statistics)) { return array('min' => 0, 'max' => 0, 'average' => 0); }
        $sum = 0;
        $min = 0;
        $max = 0;
        for ($i = 0; $i < count($statistics); $i++)
        {
            $value = intval($statistics[$i]['diff']);
            $sum += $value;
            if ($value < $min || $min == 0) { $min = $value; }
            if ($value > $max) { $max = $value; }
        }

        return array('min' => $min, 'max' => $max, 'average' => intval($sum / count($statistics)));
    }
    
    public function getRegistrationsPerDay()
    {
        $db_request = 'SELECT szcm3_convention_registration_form.description, count(*) as occurrences
                        FROM `szcm3_convention_registrations`
                        LEFT JOIN `szcm3_convention_registration_form` ON 
                            szcm3_convention_registrations.convention_registration_form_id=
                            szcm3_convention_registration_form.convention_registration_form_id
                        GROUP BY description
                        ORDER BY description DESC;';
        return Data\Database::read_raw_sql($db_request, array());
    }

}

