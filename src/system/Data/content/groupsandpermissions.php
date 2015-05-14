<?php

namespace Szandor\ConMan;

use \Szandor\ConMan\Data as Data;
use \Szandor\ConMan\Logic as Logic;
use \Szandor\ConMan\View as View;

$ur = new Data\MySQLUserRepository();
$groupsnadpermissions = $ur->getPermissionsForGroups();

$panelContent = [];
foreach ($groupsnadpermissions as $key => $group) {
    $permissionsInGroup = [];
    foreach ($group['permissions'] as $permission) {
        if ($group['description'] == 'regular user') {
            $permissionsInGroup[] = '<li>' . $permission['permission_description'] . '</li>';
        } else {
            $permissionsInGroup[] = '<li>' . $permission['permission_description'] . 
                ' <a href="dostuff.php?submit_dostuff=remove_permission_from_group&permission_id=' . 
                $permission['user_group_permissions_id'] . '&group_id=' . $group['user_groups_id'] . 
                '"><i class="fa fa-times fa-dark"></i></a></li>';
        }
    }

    $panelContent[] = '
            <div class="col-xs-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">' . $group['description'] . ' - ' . count($group['permissions']) . ' st</h>
                    </div>
                    <div class="panel-body">
                        <ul>
                            ' . implode('', $permissionsInGroup) . '
                        </ul>
                    </div>
                </div>
            </div>';
}

$permissionsDropdown = [];
foreach ($ur->getPermissionnamesAndId() as $permission) {
    $permissionsDropdown[] =  '<option value="' . $permission['user_group_permissions_id'] . '">' . $permission['description'] . '</option>';
}

$groupsDropdown = [];
foreach ($ur->getGroupnamesAndId() as $group) {
    $groupsDropdown[] =  '<option value="' . $group['user_groups_id'] . '">' . $group['description'] . '</option>';
}

/**
 * The following is simple contents.
 */
$contents['page_id'] = 'groupsnadpermissions';
$contents['date_created'] = '2014-11-15 20:53:18';
$contents['date_changed'] = gmdate("Y-m-d H:i:s", filemtime(__FILE__));
$contents['required_clearance'] = 'admin';
$contents['name'] = 'Grupper och rattigheter';
$contents['title'] = 'Grupper och rattigheter';
$contents['head_local'] = '<link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet"/>
<script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.min.js"></script>';
$contents['content_top'] = '';

$contents['content_main'] = '
    <div class="row">
        <h1 style="text-align: center;">Rättigheter per grupp</h1>
        ' . implode('', $panelContent) . '
    </div>
    <div class="row">
        <div class="col-xs-12">
            <form role="form" id="add_permission_to_group" name="add_permission_to_group" action="dostuff.php" method="post">
                <h1 style="text-align: center;">Lägg till rättighet till grupp</h1>
                <div class="well bs-component">
                <fieldset>
                    <div class="row">
                        <div class="col-xs-12 col-sm-4 col-sm-offset-2">
                            <div class="form-group">
                                <label for="add_permission_to_group_permission">Rättighet</label>
                                <select class="form-control" id="add_permission_to_group_permission" name="add_permission_to_group_permission" placeholder="Rättighet">
                                    <option selected="selected" disabled="disabled">Rättighet</option>
                                    ' . implode('', $permissionsDropdown) . '
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-4">
                            <div class="form-group">
                                <label for="add_permission_to_group_group">Grupp</label>
                                <select class="form-control" id="add_permission_to_group_group" name="add_permission_to_group_group" placeholder="Grupp">
                                    <option selected="selected" disabled="disabled">Grupp</option>
                                    ' . implode('', $groupsDropdown) . '
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-4 col-sm-offset-4">
                            <button type="submit" class="btn btn-success btn-block" id="add_permission_to_group_submit" name="submit_dostuff" value="add_permission_to_group">Lägg till</button>
                        </div>
                    </div>
                </fieldset>
                </div>
            </form>
        </div>
    </div>';

$contents['content_bottom'] = '';
