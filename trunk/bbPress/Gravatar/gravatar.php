<?php
/*
Plugin Name: Gravatar Plugin
Plugin URI: http://code.google.com/p/llbbsc/wiki/GravatarPlugin
Description: A simple Gravatar plugin for bbPress
Author: Yu-Jie Lin
Author URI: http://www.livibetter.com/
Version: 0.1.3
Creation Date: 2007-10-18 12:13:25 UTC+8
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

// Decide the default image URI

$GA_DEFAULT_IMAGE = 'http://www.gravatar.com/avatar.php';
if     (file_exists(dirname(__FILE__) . '/gravatar-default.jpg'))
    $GA_DEFAULT_IMAGE = bb_path_to_url(dirname(__FILE__) . '/gravatar-default.jpg');
elseif (file_exists(dirname(__FILE__) . '/gravatar-default.gif'))
    $GA_DEFAULT_IMAGE = bb_path_to_url(dirname(__FILE__) . '/gravatar-default.gif');
elseif (file_exists(dirname(__FILE__) . '/gravatar-default.png'))
    $GA_DEFAULT_IMAGE = bb_path_to_url(dirname(__FILE__) . '/gravatar-default.png');
elseif (file_exists(bb_get_active_theme_folder() . '/gravatar-default.jpg'))
    $GA_DEFAULT_IMAGE = bb_path_to_url(bb_get_active_theme_folder() . '/gravatar-default.jpg');
elseif (file_exists(bb_get_active_theme_folder() . '/gravatar-default.gif'))
    $GA_DEFAULT_IMAGE = bb_path_to_url(bb_get_active_theme_folder() . '/gravatar-default.gif');
elseif (file_exists(bb_get_active_theme_folder() . '/gravatar-default.png'))
    $GA_DEFAULT_IMAGE = bb_path_to_url(bb_get_active_theme_folder() . '/gravatar-default.png');

define(GA_DEFAULT_SIZE, 64);

/* Template stuff
======================================== */

// retrun src for img tag
function GAGetImageURI($id=0, $size=GA_DEFAULT_SIZE) {
    global $GA_DEFAULT_IMAGE;
    if ($id==0 || $id===null)
        if (is_topic())
           $id = get_post_author_id();

    // Check Size
    if ($size<1) $size = GA_DEFAULT_SIZE;
    if ($size>80) $size = 80;

    if (!$user = bb_get_user(bb_get_user_id($id)))
        return "$GA_DEFAULT_IMAGE?size=$size";
    if (!GAVerified($user->ID))
        return "$GA_DEFAULT_IMAGE?size=$size";

    if (isset($user->gravatar['md5']) && $user->gravatar_email['md5'] != '') {
        $default = '';
        if ($GA_DEFAULT_IMAGE!='')
            $default = "&amp;default=".urlencode($GA_DEFAULT_IMAGE);
        //return $GA_DEFAULT_IMAGE;
        return "http://www.gravatar.com/avatar.php?gravatar_id={$user->gravatar['md5']}$default&amp;size=$size";
        }
    return "$GA_DEFAULT_IMAGE&amp;size=$size";
    }

// echo version of GAGetImageURI
function GAImageURI($id=0, $size=GA_DEFAULT_SIZE) {
    echo GAGetImageURI($id, $size);
    }

// return preset complete img tag
function GAGetImage($id=0, $size=GA_DEFAULT_SIZE, $style='border: 1px solid black', $class='', $link=true) {
	global $GA_DEFAULT_IMAGE;
	if ($id==0 || $id===null)
		if (is_topic())
		 $id = get_post_author_id();
	
	// Check Size
	if ($size<1) $size = GA_DEFAULT_SIZE;
	if ($size>80) $size = 80;
	// Check style and class
	if ($style!='')
		$style = " style=\"$style\"";
	if ($class!='')
		$class = " class=\"$class\"";

	if (!$user = bb_get_user(bb_get_user_id($id)))
		return "<img$style$class width=\"{$size}px\" height=\"{$size}px\" src=\"$GA_DEFAULT_IMAGE?size=$size\" alt=\"\"/>";

	$img = "<img$style$class width=\"{$size}px\" height=\"{$size}px\" src=\"" . GAGetImageURI($user->ID, $size) . '" alt="' .
		(($user->display_name) ? $user->display_name : $user->user_login) . '"/>';
	if ($link)
		return '<a href="' . attribute_escape(get_user_profile_link($user->ID)) . "\">$img</a>";
	return $img;
	}

// echo version of GAGetImage without link
function GAImage($id=0, $size=GA_DEFAULT_SIZE, $style='border: 1px solid black', $class='') {
    echo GAGetImage($id, $size, $style, $class, false);
    }

// echo version of GAGetImage with link
function GAImageLink($id=0, $size=GA_DEFAULT_SIZE, $style='border: 1px solid black', $class='') {
    echo GAGetImage($id, $size, $style, $class);
    }

/* Internal stuff
======================================== */

function GAVerified($userID) {
    $gravatarEmail = bb_get_usermeta($userID, 'gravatar_email');
    $gravatarVCode = bb_get_usermeta($userID, 'gravatar_vcode');
    // No such usermetas?
    if (!$gravatarEmail || !$gravatarVCode) return false;
    $gravatar = bb_get_usermeta($userID, 'gravatar');
    return ($gravatarEmail == $gravatar['email'] &&
            $gravatarVCode == $gravatar['vcode']);
    }

// Make profile editing page adds a field Gravatar Email, and block other users.
function GAHook_get_profile_info_keys($keys) {
    global $user_id;
    if (is_bb_profile()) {
        $currentUserID = bb_get_current_user_info( 'id' );
        // A powerful user or user him/herself
        if (bb_current_user_can('edit_user', $user_id)) {
            $keys['gravatar_email'] = array(0, __('Gravatar Email'));
            $keys['gravatar_vcode'] = array(0, __('Gravatar Verification Code'));
            }
        }
    elseif (bb_get_location() == 'register-page') {
        $keys['gravatar_email'] = array(0, __('Gravatar Email'));
        }
    return $keys;
    }

// Send the Verification Code
function GASendVCode($userID) {
    $gravatar = bb_get_usermeta($userID, 'gravatar');
    $message = "New Gravatar Email is %1\$s.\nNew Verification Code is %2\$s\n\nIt is an 8-letter string. Note that before you successfully verify your new Gravatar Email, your Gravatar will not work.\n\n%3\$s\n%4\$s";
    if (false === bb_mail(
            bb_get_user_email($userID),
            bb_get_option('name') . ': ' . __('Your Gravatar usage verification code'),
            sprintf($message, $gravatar['new_email'],$gravatar['new_vcode'], bb_get_option('name'), bb_get_option('uri'))))
        error_log("bPress GA: Failed to send notification mail (user ID $userID)");
    }

// Generate the Verification Code
function GAGenerateVCode($userID) {
    $gravatar = bb_get_usermeta($userID, 'gravatar');
    $gravatar['new_vcode'] = bb_random_pass(8); // from registration-functions.php
    bb_update_usermeta($userID, 'gravatar', $gravatar);
    }

// Check gravatar_email usermeta after profile edited or new user registered
function GAHook_profile_edited($userID) {
    $gravatarEmail = bb_get_usermeta($userID, 'gravatar_email');
    $gravatarVCode = bb_get_usermeta($userID, 'gravatar_vcode');
    $gravatar = bb_get_usermeta($userID, 'gravatar');

    // No email inputed
    if ($gravatarEmail === null || $gravatarEmail == '') {
        bb_delete_usermeta($userID, 'gravatar_email');
        bb_delete_usermeta($userID, 'gravatar');
        return;
        }

    // New email?
    if (!GAVerified($userID))
        if (isset($gravatar['new_email']) &&
            isset($gravatar['new_vcode'])) {
            if ($gravatarEmail == $gravatar['new_email'] &&
                $gravatarVCode == $gravatar['new_vcode']) {
                // New email verified, update with news
                $gravatar['email'] = $gravatar['new_email'];
                $gravatar['vcode'] = $gravatar['new_vcode'];
                $gravatar['md5'] = md5($gravatar['email'] );
                unset($gravatar['new_email'], $gravatar['new_vcode']);
                bb_update_usermeta($userID, 'gravatar', $gravatar);
                }
            elseif ($gravatarEmail != $gravatar['new_email']) {
                // Save newer email
                $gravatar['new_email'] = $gravatarEmail;
                bb_update_usermeta($userID, 'gravatar', $gravatar);
                bb_update_usermeta($userID, 'gravatar_vcode', 'Newer code should be in you mail box.');
                 // New email has been changed again, regenerate the vcode
                GAGenerateVCode($userID);
                GASendVCode($userID);
                }
            else {
                // New email has not been changed, send vcode again
                bb_update_usermeta($userID, 'gravatar_vcode', 'Code has been sent again.');
                GASendVCode($userID);
                }
            }
        else {
            // Verify the email format
            if (bb_verify_email($gravatarEmail) === false) {
                bb_update_usermeta($userID, 'gravatar_vcode', 'Not a Valid Email!');
                bb_delete_usermeta($userID, 'gravatar');
                return;
                }
            // Generate new verification code
            $gravatar['new_email'] = $gravatarEmail;
            bb_update_usermeta($userID, 'gravatar_vcode', 'Check Your Mailbox.');
            bb_update_usermeta($userID, 'gravatar', $gravatar);
            GAGenerateVCode($userID);
            GASendVCode($userID);
            }
    }

// Detects Registration page
function GAHook_get_location ($prevResult, $src) {
    if (bb_find_filename($src) == 'register.php')
        return 'register-page';
    return $prevResult;
    }

/* Hooks
======================================== */

add_filter('get_profile_info_keys', 'GAHook_get_profile_info_keys');
add_action('profile_edited', 'GAHook_profile_edited');
add_action('register_user', 'GAHook_profile_edited');
add_filter('bb_get_location', 'GAHook_get_location', 10, 2);
?>
