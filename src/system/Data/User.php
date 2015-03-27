<?php

namespace Szandor\ConMan\Data;

use \Szandor\ConMan\Data as Data;
use \Szandor\ConMan\Logic as Logic;
use \Szandor\ConMan\View as View;

/**
 * Fetches and returns specified user data.
 */
class User
{
    /**
     * Returns data for a specified user.
     */
    public function getData($user = false)
    {
        if ($user === false || !is_numeric($user)) {
            return false;
        } else {
            $users_request = array(
                'table' => 'users',
                'limit' => 1,
                'where' => array(
                    'col' => 'users_id',
                    'values' => $user,
                ),
            );
            $user_data = Data\Database::read($users_request, false);

            if (isset($user_data[0])) {
                $user_data[0]['id'] = $user_data[0]['users_id'];
                unset($user_data[0]['users_id']);

                $out['data'] = $user_data[0];
                unset($out['data']['password']);

                $user_details_request = array(
                    'table' => 'user_details',
                    'limit' => 1,
                    'where' => array(
                        'col' => 'users_id',
                        'values' => $user,
                    ),
                );
                $user_details = Data\Database::read($user_details_request, false);

                $user_details[0]['id'] = $user_details[0]['users_id'];
                unset($user_details[0]['users_id']);

                $user_details[0]['gender'] = ($user_details[0]['male'] === null ? '' : ($user_details[0]['male'] == '1' ? 'Man' : 'Kvinna'));
                unset($user_details[0]['male']);

                $out['details'] = $user_details[0];

                return $out;
            } else {
                return false;
            }
        }
    }

    /**
     * Returns the ID for a specified username.
     */
    public function username2ID($user)
    {
        $users_request = array(
            'table' => 'users',
            'limit' => 1,
            'where' => array(
                'col' => 'username',
                'values' => $user,
            ),
        );

        $user_data = Data\Database::read($users_request, false);
        return $user_data[0]['users_id'];
    }
    
    /**
     *Returns registration data for a specific user
     */
    public static function getConventionRegistrationData($user = false)
    {
        if ($user === false || !is_numeric($user)) {
            return false;
        } else {
            $users_request = array(
                'table' => 'convention_registrations',
                'limit' => 1,
                'where' => array(
                    'col' => 'users_id',
                    'values' => $user,
                ),
            );
            return Data\Database::read($users_request, false);
        }
    }

}
