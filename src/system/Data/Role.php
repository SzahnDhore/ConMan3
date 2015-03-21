<?php

namespace Szandor\ConMan\Data;

class Role
{
    const PERM_VIEW_ALL_EVENTS = 'PERM_VIEW_ALL_EVENTS';
    const PERM_CREATE_NEW_EVENTS = 'PERM_CREATE_NEW_EVENTS';
    const PERM_EDIT_ALL_EVENTS = 'PERM_EDIT_ALL_EVENTS';
    const PERM_WITHDRAW_CONFIRMED_EVENTS = 'PERM_WITHDRAW_CONFIRMED_EVENTS';
    const PERM_DELETE_AND_CONFIRM_EVENTS = 'PERM_DELETE_AND_CONFIRM_EVENTS';
    const PERM_ADD_EVENT_TO_SCHEDULE = 'PERM_ADD_EVENT_TO_SCHEDULE';

    protected $permissions;
    protected function __construct() {
        $this->permissions = array();
    }

    // These should be stored in the database?
    public static function getRolePermissions($role_id) {
        $role = new Role();
        // Regular user
        if ($role_id >= 2) {
        }
        // Admin
        if ($role_id >= 3) {
            $role->permissions[Role::PERM_VIEW_ALL_EVENTS] = true;
            $role->permissions[Role::PERM_CREATE_NEW_EVENTS] = true;
            $role->permissions[Role::PERM_EDIT_ALL_EVENTS] = true;
            $role->permissions[Role::PERM_WITHDRAW_CONFIRMED_EVENTS] = true;
            $role->permissions[Role::PERM_DELETE_AND_CONFIRM_EVENTS] = true;
            $role->permissions[Role::PERM_ADD_EVENT_TO_SCHEDULE] = true;
        }
        return $role;
    }

    public function hasPermission($permission) {
        return isset($this->permissions[$permission]);
    }
}
