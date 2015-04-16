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

}

