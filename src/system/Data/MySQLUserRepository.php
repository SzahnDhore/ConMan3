<?php

namespace Szandor\ConMan\Data;

use \Szandor\ConMan\Data as Data;
use \Szandor\ConMan\Logic as Logic;
use \Szandor\ConMan\View as View;

class MySQLUserRepository implements IUserRepository
{
    public function getEmailByUserId($userId)
    {
        $users_request = array(
            'table' => 'users',
            'limit' => 1,
            'where' => array(
                'col' => 'users_id',
                'values' => $userId,
            ),
        );

        $user_data = Data\Database::read($users_request, false);
        return $user_data[0]['email'];
    }

    public function getNumberOfUnconfirmedUserDetails()
    {
        $unconfirmed_user_details_request = 'SELECT COUNT(*) FROM `szcm3_user_staged_changes`;';
        $tmp = Data\Database::read_raw_sql($unconfirmed_user_details_request, array());
        return $tmp[0]['COUNT(*)'];
    }

    /**
     * Stages new details for a user. Returns true if ok, false if something fails.
     */
    public function stageNewDetailsForUser($userData)
    {
        if (empty($userData)) {
            return false;
        } else {
            $male = 1;
            if (isset($userData['male'])) { $male = $userData['male']; }
            if (isset($userData['gender'])) { $male = $userData['gender']; }
            $users_request = array(
                'table' => 'user_staged_changes',
                'data' => array( array(
                    'given_name' => $userData['given_name'],
                    'family_name' => $userData['family_name'],
                    'address' => $userData['address'],
                    'postal_code' => $userData['postal_code'],
                    'city' => $userData['city'],
                    'male' => $male,
                    'national_id_number' => $userData['national_id_number'],
                    'country' => $userData['country'],
                    'phone_number' => $userData['phone_number'],
                    'email' => $userData['email'],
                    'users_id' => $userData['users_id']
                ))
            );
            $result = Data\Database::create($users_request, false);
            return is_numeric($result);
        }
    }
    
    public function getNumberOfUsers()
    {
        $users_request = 'SELECT COUNT(*) FROM `szcm3_users`;';
        $tmp = Data\Database::read_raw_sql($users_request, array());
        return $tmp[0]['COUNT(*)'];
    }

}

