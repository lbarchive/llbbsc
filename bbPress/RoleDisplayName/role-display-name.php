<?php
/*
Plugin Name: Role Display Name
Plugin URI: http://code.google.com/p/llbbsc/wiki/RoleDisplayNamePlugin
Description: Changing Role Display Name.
Author: Yu-Jie Lin
Author URI: http://www.livibetter.com/
Version: 0.1.1
Creation Date: 2007-10-16 06:49:00 UTC+8
*/
/*
 * Copyright 2007 Yu-Jie Lin
 * 
 * This program is free software; you can redistribute it and/or modify it
 * under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or (at your
 * option) any later version.
 * 
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE.  See the GNU Lesser General Public
 * License for more details.
 * 
 * You should have received a copy of the GNU General Public License along
 * with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

function ChangeRoleDisplayName($roles) {
    $roles['keymaster'    ]['name'] = __("Toilet Cleaner");
    $roles['administrator']['name'] = __("Head of IT");
    $roles['moderator'    ]['name'] = __("The Geek");
    $roles['member'       ]['name'] = __("Employee");
    $roles['inactive'     ]['name'] = __("Sleeper");
    $roles['blocked'      ]['name'] = __("Hole on Ass");
    return $roles;
    }

add_filter('get_roles', 'ChangeRoleDisplayName');
?>
