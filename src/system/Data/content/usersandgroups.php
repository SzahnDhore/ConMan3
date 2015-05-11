<?php

namespace Szandor\ConMan;

use \Szandor\ConMan\Data as Data;
use \Szandor\ConMan\Logic as Logic;
use \Szandor\ConMan\View as View;

$ur = new Data\MySQLUserRepository();
$usersandgroups = $ur->getUsersForGroups();

$panelContent = [];
foreach ($usersandgroups as $key => $group) {
    $usersInGroup = [];
    foreach ($group['users'] as $user) {
        if ($group['description'] == 'regular user') {
            $usersInGroup[] = '<li>' . $user['username'] . '</li>';
        } else {
            $usersInGroup[] = '<li>' . $user['username'] . 
                ' <a href="dostuff.php?submit_dostuff=remove_user_from_group&users_id=' . 
                $user['users_id'] . '&group_id=' . $group['user_groups_id'] . 
                '"><i class="fa fa-times fa-dark"></i></a></li>';
        }
    }

    $panelContent[] = '
            <div class="col-xs-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">' . $group['description'] . ' - ' . count($group['users']) . ' st</h>
                    </div>
                    <div class="panel-body">
                        <ul>
                            ' . implode('', $usersInGroup) . '
                        </ul>
                    </div>
                </div>
            </div>';
}

$usersDropdown = [];
foreach ($ur->getUsernamesAndId() as $user) {
    $usersDropdown[] =  '<option value="' . $user['users_id'] . '">' . $user['username'] . '</option>';
}

$groupsDropdown = [];
foreach ($ur->getGroupnamesAndId() as $group) {
    $groupsDropdown[] =  '<option value="' . $group['user_groups_id'] . '">' . $group['description'] . '</option>';
}

/**
 * The following is simple contents.
 */
$contents['page_id'] = 'usersandgroups';
$contents['date_created'] = '2014-11-15 20:53:18';
$contents['date_changed'] = gmdate("Y-m-d H:i:s", filemtime(__FILE__));
$contents['required_clearance'] = 'admin';
$contents['name'] = 'Anvandare och grupper';
$contents['title'] = 'Anvandare och grupper';
$contents['head_local'] = '<link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet"/>
<script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.min.js"></script>';
$contents['content_top'] = '';

$contents['content_main'] = '
    <div class="row">
        <h1 style="text-align: center;">Användare per grupp</h1>
        ' . implode('', $panelContent) . '
    </div>
    <div class="row">
        <div class="col-xs-12">
            <form role="form" id="add_user_to_group" name="add_user_to_group" action="dostuff.php" method="post">
                <h1 style="text-align: center;">Lägg till användare till grupp</h1>
                <div class="well bs-component">
                <fieldset>
                    <div class="row">
                        <div class="col-xs-12 col-sm-4 col-sm-offset-2">
                            <div class="form-group">
                                <label for="add_user_to_group_user">Användare</label>
                                <select class="form-control" id="add_user_to_group_user" name="add_user_to_group_user" placeholder="Användare">
                                    <option selected="selected" disabled="disabled">Användare</option>
                                    ' . implode('', $usersDropdown) . '
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-4">
                            <div class="form-group">
                                <label for="add_user_to_group_group">Grupp</label>
                                <select class="form-control" id="add_user_to_group_group" name="add_user_to_group_group" placeholder="Grupp">
                                    <option selected="selected" disabled="disabled">Grupp</option>
                                    ' . implode('', $groupsDropdown) . '
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-4 col-sm-offset-4">
                            <button type="submit" class="btn btn-success btn-block" id="add_user_to_group_submit" name="submit_dostuff" value="add_user_to_group">Lägg till</button>
                        </div>
                    </div>
                </fieldset>
                </div>
            </form>
        </div>
    </div>';

$contents['content_bottom'] = '';
