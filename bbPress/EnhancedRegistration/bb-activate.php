<?php
require('./bb-load.php');
require_once( BBPATH . BBINC . 'registration-functions.php');

$ref = wp_get_referer();

$re = bb_get_option('uri');

if ( 0 === strpos($ref, bb_get_option( 'uri' )) ) {
	$re = $_POST['re'] ? $_POST['re'] : $_GET['re'];
	if ( 0 !== strpos($re, bb_get_option( 'uri' )) )
		$re = $ref . $re;
}
/*
if ( 0 === strpos($re, bb_get_option( 'uri' ) . 'register.php') )
	$re = bb_get_option( 'uri' );
*/
$re = clean_url( $re );

nocache_headers();

// Can't be a logged user
if (!bb_is_user_logged_in()) {
	$user_login = attribute_escape(bb_user_sanitize(@$_GET['user_login']));
	$actCode = bb_user_sanitize(@$_GET['act_code']);
	if (empty($user_login) || empty($actCode)) {
		$user_login = attribute_escape(bb_user_sanitize(@$_POST['user_login']));
		$actCode = bb_user_sanitize(@$_POST['act_code']);
		}
	$user = bb_get_user_by_name($user_login);
	switch ($_POST['do']) {
		case 'RequestNewCode':
			// TODO Provide messages if no such user or the user is activated already?
			if (ERNeedActivation($user_login))
				if (($userID = $user->ID) != null) {
					ERGenerateActCode($userID);
					ERSendActCode($userID);
					}
			$userActivated = false;
			break;
		case 'GoLogIn':
			break;
		default:
			$userActivated = ERActivate($user_login, $actCode);
		}
	$user_exists = $user !== false;
	$re = $redirect_to = attribute_escape($re);
	if ($_POST['do'] == 'GoLogIn') {
		$user_exists = true;
		bb_load_template('login.php', array('user_exists', 'user_login', 'redirect_to', 're'));
		}
	else {
		bb_load_template('activate.php', array('user_exists', 'userActivated', 'user_login', 'redirect_to', 're'));
		}
	exit;
	}

// We already know it's safe from the above, but we might as well use this anyway.
bb_safe_redirect( $re );
?>
