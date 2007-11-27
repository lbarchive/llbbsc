<?php
/*
Plugin Name: Enhanced Registration
Description: Enhancing bbPress Registration
Author: Yu-Jie Lin
Author URI: http://www.livibetter.com/
Version: 0.0.0.2
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

global $ERRuntimInformation;

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

// Overrides bb_check_login
if ($ERRuntimeInformation['overrided_bb_check_login'] = !function_exists('bb_check_login')):
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
else:
	error_log('bbPress ER: ' . 'Unable to override bb_check_login. The function has been implemented. Please deactivate other plugins.');
endif;

// Send the Activation Code
function ERSendActCode($userID) {
    $actCode = bb_get_usermeta($userID, 'act_code');
    $message = __("Please click the following link to activate your account: \n%1\$sbb-activate.php?user_login=%2\$s&act_code=%3\$s\n\nIf the link above didn't work, please navigate to %1\$sbb-activate.php and enter your login name and this activation code: %3\$s\n\n%4\$s\n%5\$s", ER_DOMAIN);
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

function ERSendReport() {
	$options = bb_get_option('EROptions');
	// TODO BUG May not send report if the forum has really few visitors
	$doSend = false;	
	if ($options['sendReport'] == 'hourly')
		$doSend = gmdate('G', time()) != gmdate('G', $options['lastSent']);
	if ($options['sendReport'] == 'daily')
		$doSend = gmdate('j', time()) != gmdate('j', $options['lastSent']);
	
	if (!$doSend)
		return;
	// Check any report is available to be sent
	$report = '';
	if ($options['deletedUnactivatedIDs']) {
		$report .= "Deleted unactivated users =====\n";
		foreach ($options['deletedUnactivatedIDs'] as $t => $mappedIDLogin)
			$report .= sprintf("%1\$s:\n  %2\$s\n\n", gmdate('r', $t), implode("\n  ", array_map(create_function('$id, $userLogin',
				'return "$id: $userLogin";'), array_keys($mappedIDLogin), array_values($mappedIDLogin))));
		}
	if (empty($report))
		return;
	// Send the report
    $message = __("The report was generated at %1\$s. All times are in UTC.\n\n%2\$s", ER_DOMAIN);
    $result = bb_mail(
        bb_get_option('admin_email'),
        bb_get_option('name') . ": Your {$options[sendReport]} report",
        sprintf($message, gmdate('r', time()), $report));

	if ($result) {
		// Seems send successfully, then Clean up
		$options['lastSent'] = time();
		unset($options['deletedUnactivatedIDs']);
		bb_update_option('EROptions', $options);
		}
	else
        error_log('bbPress ER: Failed to send report!');
	return $result;
	}

/* Options
======================================== */

function ERGetDefaultOptions() {
	return array(
		'autoDeleteUnactivatedOver' => 0,
		'sendReport' => 'daily'
		);
	}

function ERUpgradeOptions() {
	$options = bb_get_option('EROptions');
	if (empty($options)) {
		$options = ERGetDefaultOptions();
		$options['version'] = '0.0.0.2';
		}
	bb_update_option('EROptions', $options);
	return $options;
	}

/* Functions
======================================== */

function ERGetUnactivatedUserCount() {
	global $bbdb;
	return $bbdb->query("SELECT $bbdb->users.ID FROM $bbdb->users, $bbdb->usermeta WHERE $bbdb->users.ID = $bbdb->usermeta.user_id AND $bbdb->usermeta.meta_key = 'act_code'");
	}

function ERDeleteUnactivated($over) {
	$over = floor($over);
	if ($over <= 0)
		return;
	global $bbdb;
	$IDs = $bbdb->get_col("SELECT $bbdb->users.ID, $bbdb->users.user_login FROM $bbdb->users, $bbdb->usermeta WHERE $bbdb->users.ID = $bbdb->usermeta.user_id AND $bbdb->usermeta.meta_key = 'act_code' AND DATE_ADD('1970-01-01', INTERVAL UNIX_TIMESTAMP() SECOND) >= DATE_ADD($bbdb->users.user_registered, INTERVAL $over HOUR)");
	
	if ($IDs) {
		$mapped = array_combine($IDs, $bbdb->get_col(null, 1));
		foreach ($IDs as $ID)
			bb_delete_user($ID);
		// Put these IDs into log
		$options = bb_get_option('EROptions');
		$options['deletedUnactivatedCount'] += sizeof($IDs);
		if (in_array($options['sendReport'], array('hourly', 'daily'))) {
			if (!is_array($options['deletedUnactivatedIDs']))
				$options['deletedUnactivatedIDs'] = array();
			$options['deletedUnactivatedIDs'] = array_merge($options['deletedUnactivatedIDs'], array(time() . '.0' => $mapped));
			}
		else
			unset($options['deletedUnactivatedIDs']);
		bb_update_option('EROptions', $options);
		}
	return $mapped;
	}

// Initializes ER and auto-deletion
function ERHook_bb_init() {
	global $ERRuntimeInformation;
	$options = ERUpgradeOptions();
	// Process auto tasks
	if (time() >= $options['lastRun'] + 3600) {
		$options['lastRun'] = time();
		bb_update_option('EROptions', $options);

		// Unactivated Users
		if (($over = $options['autoDeleteUnactivatedOver']) > 0)
			ERDeleteUnactivated($over);
		}
	ERSendReport();
	}

/* Hooks
======================================== */

add_action('bb_admin_menu_generator', 'ERAdminMenu');
add_action('register_user', 'ERHook_register_user');
add_action('bb_init', 'ERHook_bb_init');
?>
