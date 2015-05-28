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

    public function setNewDetailsForUser($userData)
    {
        if (empty($userData)) {
            return false;
        } else {
            $request = array(
                'table' => 'users',
                'id' => $userData['users_id'],
                'values' => array(
                    'email' => $userData['email']
                ),
            );
            Data\Database::update($request, false);

            // Finding the index for user_details..
            $request = array(
                'table' => 'user_details',
                'limit' => 1,
                'select' => 'user_details_id',
                'where' => array(
                    'col' => 'users_id',
                    'values' => $userData['users_id'],
                ),
            );
            $user_data = Data\Database::read($request, false);

            // Time to update!
            $male = 1;
            if (isset($userData['male'])) { $male = $userData['male']; }
            if (isset($userData['gender'])) { $male = $userData['gender']; }
            $request = array(
                'table' => 'user_details',
                'id' => $user_data[0]['user_details_id'],
                'values' => array(
                    'given_name' => $userData['given_name'],
                    'family_name' => $userData['family_name'],
                    'address' => $userData['address'],
                    'postal_code' => $userData['postal_code'],
                    'city' => $userData['city'],
                    'male' => $male,
                    'national_id_number' => $userData['national_id_number'],
                    'country' => $userData['country'],
                    'phone_number' => $userData['phone_number']
                ),
            );
            Data\Database::update($request, false);
        }
    }

    public function getNumberOfUsers()
    {
        $users_request = 'SELECT COUNT(*) FROM `szcm3_users`;';
        $tmp = Data\Database::read_raw_sql($users_request, array());
        return $tmp[0]['COUNT(*)'];
    }

    public function getUsersForGroups()
    {
        $users_request = '  SELECT szcm3_user_groups.user_groups_id, 
                                szcm3_user_groups.description,
                                szcm3_users.users_id,
                                szcm3_users.username
                            FROM szcm3_user_groups 
                            LEFT JOIN szcm3_user_and_group_connection ON
                                szcm3_user_groups.user_groups_id=
                                szcm3_user_and_group_connection.user_groups_id
                            LEFT JOIN szcm3_users ON
                                szcm3_user_and_group_connection.users_id=
                                szcm3_users.users_id
                            ORDER BY szcm3_user_groups.description, username ASC;';
        $raw_data = Data\Database::read_raw_sql($users_request, array());
        $map = [];
        foreach ($raw_data as $row) {
            $group_index = -1;
            foreach ($map as $key => $value) {
                if ($value['user_groups_id'] == $row['user_groups_id']) {
                    $group_index = $key;
                    break; 
                }
            }
            
            if ($group_index >= 0) {
                $map[$group_index]['users'][] = array(
                    'users_id' => $row['users_id'],
                    'username' => $row['username']
                );
            } else {
                $map[] = array('user_groups_id' => $row['user_groups_id'],
                               'description' => $row['description'],
                               'users' => array());
                if (!is_null($row['users_id']) && !is_null($row['username']))
                {
                    $map[count($map)-1]['users'][] = array(
                        'users_id' => $row['users_id'],
                        'username' => $row['username']
                    );
                }
            }
        }

        return $map;
    }

    public function getUsernamesAndId()
    {
        $users_request = 'SELECT users_id, username FROM `szcm3_users` ORDER BY username ASC;';
        return Data\Database::read_raw_sql($users_request, array());
    }

    public function getGroupnamesAndId()
    {
        $groups_request = 'SELECT user_groups_id, description FROM `szcm3_user_groups` ORDER BY description ASC;';
        return Data\Database::read_raw_sql($groups_request, array());
    }

    public function getPermissionnamesAndId()
    {
        $permissions_request = 'SELECT user_group_permissions_id, description FROM `szcm3_user_group_permissions` ORDER BY description ASC;';
        return Data\Database::read_raw_sql($permissions_request, array());
    }

    public function findUserGroupConnection($userId, $groupId)
    {
        $connection_request = array(
            'table' => 'user_and_group_connection',
            'limit' => 1,
            'where' => array(
                'query' => 'and',
                'col' => array(
                    'users_id',
                    'user_groups_id'
                ),
                'values' => array(
                    $userId,
                    $groupId,
                ),
            )
        );

        return Data\Database::read($connection_request, false);
    }

    public function findPermissionGroupConnection($permissionId, $groupId)
    {
        $connection_request = array(
            'table' => 'user_group_and_group_permission_connection',
            'limit' => 1,
            'where' => array(
                'query' => 'and',
                'col' => array(
                    'user_group_permissions_id',
                    'user_groups_id'
                ),
                'values' => array(
                    $permissionId,
                    $groupId,
                ),
            )
        );

        return Data\Database::read($connection_request, false);
    }

    public function addUserToGroup($userId, $groupId)
    {
        // Check if user is already added.
        if (!empty(self::findUserGroupConnection($userId, $groupId))) { return false; }

        $connection_request = array(
                'table' => 'user_and_group_connection',
                'data' => array( array(
                    'users_id' => $userId,
                    'user_groups_id' => $groupId
                ))
            );
        $result = Data\Database::create($connection_request, false);
        return is_numeric($result);
    }

    public function addPermissionToGroup($permissionId, $groupId)
    {
        // Check if permission is already added.
        if (!empty(self::findPermissionGroupConnection($permissionId, $groupId))) { return false; }

        $connection_request = array(
                'table' => 'user_group_and_group_permission_connection',
                'data' => array( array(
                    'user_group_permissions_id' => $permissionId,
                    'user_groups_id' => $groupId
                ))
            );
        $result = Data\Database::create($connection_request, false);
        return is_numeric($result);
    }

    public function removeUserFromGroup($userId, $groupId)
    {
        $userGroupConn = self::findUserGroupConnection($userId, $groupId);
        if (empty($userGroupConn)) { return; }

        $delete_request = array(
            'table' => 'user_and_group_connection',
            'id' => $userGroupConn[0]['user_and_group_connection_id']
        );
        Data\Database::delete($delete_request, false);
    }

    public function removePermissionFromGroup($permissionId, $groupId)
    {
        $permGroupConn = self::findPermissionGroupConnection($permissionId, $groupId);
        if (empty($permGroupConn)) { return; }

        $delete_request = array(
            'table' => 'user_group_and_group_permission_connection',
            'id' => $permGroupConn[0]['user_group_and_group_permission_connection_id']
        );
        Data\Database::delete($delete_request, false);
    }

    public function getPermissionsForGroups()
    {
        $groups_request = ' SELECT szcm3_user_groups.user_groups_id,
                                szcm3_user_groups.description as group_desc,
                                szcm3_user_group_permissions.user_group_permissions_id,
                                szcm3_user_group_permissions.description as perm_desc
                            FROM szcm3_user_groups 
                            LEFT JOIN szcm3_user_group_and_group_permission_connection ON
                                szcm3_user_groups.user_groups_id=
                                szcm3_user_group_and_group_permission_connection.user_groups_id
                            LEFT JOIN szcm3_user_group_permissions ON
                                szcm3_user_group_and_group_permission_connection.user_group_permissions_id=
                                szcm3_user_group_permissions.user_group_permissions_id
                            ORDER BY szcm3_user_groups.description, szcm3_user_group_permissions.description ASC;';
        $raw_data = Data\Database::read_raw_sql($groups_request, array());
        $map = [];
        foreach ($raw_data as $row) {
            $group_index = -1;
            foreach ($map as $key => $value) {
                if ($value['user_groups_id'] == $row['user_groups_id']) {
                    $group_index = $key;
                    break; 
                }
            }
            
            if ($group_index >= 0) {
                $map[$group_index]['permissions'][] = array(
                    'user_group_permissions_id' => $row['user_group_permissions_id'],
                    'permission_description' => $row['perm_desc']
                );
            } else {
                $map[] = array('user_groups_id' => $row['user_groups_id'],
                               'description' => $row['group_desc'],
                               'permissions' => array());
                if (!is_null($row['user_group_permissions_id']) && !is_null($row['perm_desc']))
                {
                    $map[count($map)-1]['permissions'][] = array(
                        'user_group_permissions_id' => $row['user_group_permissions_id'],
                        'permission_description' => $row['perm_desc']
                    );
                }
            }
            
        }

        return $map;
    }

    public function userHasEnteredUserDetails($userId)
    {
        $users_request = array(
            'table' => 'user_details',
            'limit' => 1,
            'where' => array(
                'col' => 'users_id',
                'values' => $userId,
            ),
        );

        $user_data = Data\Database::read($users_request, false);
        // Only checking given_name and national_id_number is enough for now.
        if (isset($user_data[0]['given_name']) && !empty($user_data[0]['given_name'])
            && isset($user_data[0]['national_id_number']) && !empty($user_data[0]['national_id_number'])) { return true; }

        $user = new Data\User();
        return !empty($user->getStagedChangesForUserId($userId));
    }

    public function getAllStagedUserDetails()
    {
        $result = [];
        $staged_changes_request = array('table' => 'user_staged_changes');
        $result['staged_data'] = Data\Database::read($staged_changes_request, false);
        
        $current_user_data_request = ' SELECT szcm3_user_staged_changes.user_staged_changes_id,
                                            szcm3_users.username, szcm3_users.email,
                                            szcm3_user_details.*
                                       FROM szcm3_user_staged_changes
                                       LEFT JOIN szcm3_users ON
                                            szcm3_user_staged_changes.users_id=
                                            szcm3_users.users_id
                                       LEFT JOIN szcm3_user_details ON
                                            szcm3_user_staged_changes.users_id=
                                            szcm3_user_details.users_id;';
        $result['current_user_data'] = Data\Database::read_raw_sql($current_user_data_request, array());
        
        return $result;
    }

    public function unstageUserDetails($userStagedChangesId)
    {
        if (!is_numeric($userStagedChangesId)) { return; }

        $delete_request = array(
            'table' => 'user_staged_changes',
            'id' => $userStagedChangesId
        );
        Data\Database::delete($delete_request, false);
    }

    public function userIsOrganizer($userId)
    {
        $request = array(
            'table' => 'events',
            'limit' => 1,
            'where' => array(
                'col' => 'contact',
                'values' => $userId,
            ),
        );
        $data = Data\Database::read($request, false);

        return !empty($data);
    }

}

