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

    public function getUsersForGroups()
    {
        $users_request = 'SELECT * FROM (
                            SELECT szcm3_user_groups.user_groups_id, 
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
                            ORDER BY szcm3_user_groups.description, username)
                            AS tbl
                            WHERE
                                tbl.username is not NULL
                            ORDER BY description, username ASC;';
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
                               'users' => array( array(
                                    'users_id' => $row['users_id'],
                                    'username' => $row['username']
                        )));
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

}

