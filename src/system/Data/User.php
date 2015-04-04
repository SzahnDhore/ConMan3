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
                // fetching the groups and permisssions for the user
                $groups_tmp = User::getGroupsForUser($user);
                $groups = array();
                $group_ids = array();
                foreach ($groups_tmp as $key=>$group) {
                    $groups[] = $group['description'];
                    $group_ids[] = $group['user_groups_id'];
                }
                $out['groups']=$groups;
                $permissions_tmp = User::getPermissionsForGroups($group_ids);
                $permissions = array();
                foreach ($permissions_tmp as $key=>$perm) { $permissions[] = $perm['description']; }
                $out['permissions'] = $permissions;

                return $out;
            } else {
                return false;
            }
        }
    }
    
    /**
     * Returns the user groups for a specified user.
     */
    public static function getGroupsForUser($user = false)
    {
    if ($user === false || !is_numeric($user)) {
            return false;
        } else {
            $users_request = 'SELECT szcm3_user_groups.* FROM szcm3_user_and_group_connection LEFT JOIN szcm3_user_groups ON szcm3_user_and_group_connection.user_groups_id=szcm3_user_groups.user_groups_id WHERE users_id=?;';
            return Data\Database::read_raw_sql($users_request, array($user));
        }
    }
    
    /**
     * Returns the permissions for an array of groups.
     */
    public static function getPermissionsForGroups($group_ids = array())
    {
    if (empty($group_ids)) {
            return false;
        } else {
            $q = substr_replace(str_repeat('?,',count($group_ids)), "", -1);
            $users_request = 'SELECT szcm3_user_group_permissions.description FROM szcm3_user_group_and_group_permission_connection LEFT JOIN szcm3_user_group_permissions ON szcm3_user_group_and_group_permission_connection.user_group_permissions_id=szcm3_user_group_permissions.user_group_permissions_id WHERE szcm3_user_group_and_group_permission_connection.user_groups_id IN (' . $q . ');';
            return Data\Database::read_raw_sql($users_request, $group_ids);
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
