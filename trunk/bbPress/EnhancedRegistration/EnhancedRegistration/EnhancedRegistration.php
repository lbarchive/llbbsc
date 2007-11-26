<?php
/*
Plugin Name: Enhanced Registration
Description: Enhancing bbPress Registration
Author: Yu-Jie Lin
Author URI: http://www.livibetter.com/
Version: 0.0.0.1
Creation Date: 2007-11-25T12:41:56+0800
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

define('ER_DOMAIN', 'EnhancedRegistration');
load_plugin_textdomain(ER_DOMAIN, dirname(__FILE__) . '/locale');

function ERNeedActivation($userID) {
	if (!is_numeric($userID))
		$userID = bb_get_user_by_name($userID)->ID;
	$actCode = bb_get_usermeta($userID, 'act_code');
	return (empty($actCode)) ? false : $actCode;
	}

function ERActivate($userLogin, $actCode) {
	// No such user
	if (($user = bb_get_user_by_name($userLogin)) === false)
		return false;
	if (($_actCode = ERNeedActivation($user->ID)) === false)
		return false; // This user activate is activated already.
	// Activate this account
	if ($actCode == $_actCode) {
		bb_delete_usermeta($user->ID, 'act_code');
		return true;
		}
	return false;
	}

if (function_exists('bb_check_login')):
	global $ER_ERROR;
	$ER_ERROR = 'Unable to override bb_check_login. The function has been implemented. Please deactivate other plugins.';
	error_log('bbPress ER: ' . $ER_ERROR);
else:
function bb_check_login($user, $pass, $already_md5 = false) {
	// Original bb_check_login function
	function Original_bb_check_login($user, $pass, $already_md5 = false) {
		global $bbdb;
		$user = bb_user_sanitize( $user );
		if ( !$already_md5 ) {
			$pass = bb_user_sanitize( md5( $pass ) );
			return $bbdb->get_row("SELECT * FROM $bbdb->users WHERE user_login = '$user' AND SUBSTRING_INDEX( user_pass, '---', 1 ) = '$pass'");
		} else {
			return $bbdb->get_row("SELECT * FROM $bbdb->users WHERE user_login = '$user' AND MD5( user_pass ) = '$pass'");
		}
	}
	if (($_user = bb_get_user_by_name($user)) === false)
		// No such user
		return null;
	$userID = $_user->ID;
	// Check `act_code` in usermeta
	if (($actCode = ERNeedActivation($userID)) === false)
		// This user account is activated already.
		return Original_bb_check_login($user, $pass, $already_md5);
	// Need Activation
	wp_redirect(bb_get_option('uri') . 'bb-activate.php');
	exit;
	}
endif;

// Send the Activation Code
function ERSendActCode($userID) {
    $actCode = bb_get_usermeta($userID, 'act_code');
    $message = __("Please click the following link to activate your account: \n%1\$sbb-activate.php?user_login=%2\$s&act_code=%3\$s\n\nIf the link above didn't work, please navigate to LINK and enter your login name and this activation code: CODE\n\n%4\$s\n%5\$s", ER_DOMAIN);
    if (false === bb_mail(
        bb_get_user_email($userID),
        bb_get_option('name') . ': ' . __('Your activation code', ER_DOMAIN),
        sprintf($message, bb_get_option('uri'), get_user_name($userID), $actCode, bb_get_option('name'), bb_get_option('uri'))))
        error_log("bbPress ER: Failed to send activation code (user ID $userID)");
    }

// Generate the Activation Code
function ERGenerateActCode($userID) {
    bb_update_usermeta($userID, 'act_code', bb_random_pass(8));
    }

// TODO This could also help verifying new email as well with some additional code
function ERHook_register_user($userID) {
	// Assign this new user a activation code
	ERGenerateActCode($userID);
	// Send the code to this user
	ERSendActCode($userID);
	}

/* Admin
======================================== */

function ERAdminMenu() {
    global $bb_submenu;
    $bb_submenu['plugins.php'][] = array(__('Enhanced Registration', ER_DOMAIN), 'manage_options', 'EROptions');
    }

include_once('OptionsPage.php');

/* Hooks
======================================== */

add_action('bb_admin_menu_generator', 'ERAdminMenu');
add_action('register_user', 'ERHook_register_user');
?>
