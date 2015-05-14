<?php

namespace Szandor\ConMan\Data;

interface IUserRepository
{
    public function getEmailByUserId($userId);
    public function getNumberOfUnconfirmedUserDetails();
    public function stageNewDetailsForUser($userData);
    public function getNumberOfUsers();
    public function getUsersForGroups();
    public function getUsernamesAndId();
    public function getGroupnamesAndId();
    public function getPermissionnamesAndId();
    public function findUserGroupConnection($userId, $groupId);
    public function findPermissionGroupConnection($permissionId, $groupId);
    public function addUserToGroup($userId, $groupId);
    public function addPermissionToGroup($permissionId, $groupId);
    public function removeUserFromGroup($userId, $groupId);
    public function removePermissionFromGroup($permissionId, $groupId);
    public function getPermissionsForGroups();
    public function userHasEnteredUserDetails($userId);
}
