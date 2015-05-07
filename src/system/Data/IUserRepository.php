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
    public function findUserGroupConnection($userId, $groupId);
    public function addUserToGroup($userId, $groupId);
    public function removeUserFromGroup($userId, $groupId);
}
