<?php

namespace Szandor\ConMan\Data;

interface IUserRepository
{
    public function getEmailByUserId($userId);
    public function getNumberOfUnconfirmedUserDetails();
    public function stageNewDetailsForUser($userData);
}
