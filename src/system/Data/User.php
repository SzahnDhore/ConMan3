<?php

namespace Szandor\ConMan\Data;

use \Szandor\ConMan\Data as Data;
use \Szandor\ConMan\Logic as Logic;
use \Szandor\ConMan\View as View;

/**
 * Fetches and returns specified user data.
 * Note: This class will undergo a major refactoring in the future.
 *       The class will be a simple entity class with the ability to validate
 *       properties. All communication with the database will be moved to a
 *       class implementing a IUserRepository-interface.
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
     * Checks the users table first and then the staged table. Returns
     * false if no user id was found.
     */
    public static function getUserIdByEmail($email, $includeStagedChanges = false)
    {
        if (empty($email)) {
            return false;
        } else {
            $users_request = array(
                'table' => 'users',
                'limit' => 1,
                'where' => array(
                    'col' => 'email',
                    'values' => $email,
                ),
            );
            $result = Data\Database::read($users_request, false);
            if (isset($result[0]['users_id'])) { return $result[0]['users_id']; }

            if (!$includeStagedChanges) { return false; }
            $users_request = array(
                'table' => 'user_staged_changes',
                'limit' => 1,
                'where' => array(
                    'col' => 'email',
                    'values' => $email,
                ),
            );
            $result = Data\Database::read($users_request, false);
            if (isset($result[0]['users_id'])) { return $result[0]['users_id']; }

            return false;
        }
    }

    /**
     * Returns staged changes for a user. Returns false if no changes are staged.
     */
    public static function getStagedChangesForUserId($userId)
    {
        if ($userId === false || !is_numeric($userId)) {
            return false;
        } else {
            $users_request = array(
                'table' => 'user_staged_changes',
                'limit' => 1,
                'where' => array(
                    'col' => 'users_id',
                    'values' => $userId,
                ),
            );
            $result = Data\Database::read($users_request, false);
            if (isset($result[0]['users_id'])) { return $result[0]; }

            return false;
        }
    }

    /**
     * Stages an update for the details for a user. Returns true if ok, false if something fails.
     */
    public static function updateStagedDetailsForUser($userData)
    {
        if (empty($userData)) {
            return false;
        } else {
            $male = 1;
            if (isset($userData['male'])) { $male = $userData['male']; }
            if (isset($userData['gender'])) { $male = $userData['gender']; }
            $users_request = array(
                'table' => 'user_staged_changes',
                'id' => $userData['user_staged_changes_id'],
                'values' => array(
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
                )
            );
            $result = Data\Database::update($users_request, false);
            return $result;
        }
    }

    /**
     * functions below should stay in this class..
     */
    public static function isValidEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Syntax: YYYYMMDDNNNN
     * TODO: Add validation for the checksum.
     */
    public static function isValidNationalIdNumber($national_id_number) {
        $valid = true;
        $valid = ($valid && strlen($national_id_number) == 12) ? true : false;
        $valid = ($valid && self::isValidDate(substr($national_id_number, 0, 8))) ? true : false;
        return $valid;
    }

    private static function isValidDate($date) {
        return $date == date('Ymd', strtotime($date));
    }
}
