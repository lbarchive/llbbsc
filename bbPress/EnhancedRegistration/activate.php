<?php bb_get_header(); ?>

<h3 class="bbcrumb"><a href="<?php bb_option('uri'); ?>"><?php bb_option('name'); ?></a> &raquo; <?php _e('Activate Account'); ?></h3>

<h2><?php _e('Activation'); ?></h2>

<?php if (empty($user_login) || !$user_exists || ERNeedActivation($user_login)) : ?>
<form method="post" action="<?php bb_option('uri'); ?>bb-activate.php">
<table width="50%">
	<tr valign="top" class="error">
		<th scope="row"><?php _e('Username:'); ?></th>
		<td><input name="user_login" type="text" value="<?php echo $user_login; ?>" /><br />
			<?php if (!empty($user_login) && !$user_exist) _e('This username does not exist'); ?>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row"><?php _e('Code:'); ?></th>
		<td><input name="act_code" type="text" /></td>
	</tr>
	<tr>
		<th scope="row">&nbsp;</th>
		<td>
			<input name="re" type="hidden" value="<?php echo $redirect_to; ?>" />
			<input type="submit" value="<?php echo (isset($_POST['user_login']) ? __('Try Again &raquo;'): __('Activate &raquo;') ); ?>" />
			<?php wp_referer_field(); ?>
		</td>
	</tr>
</table>
</form>
<?php else: ?>
<form method="post" action="<?php bb_option('uri'); ?>bb-activate.php">
<p><?php echo ($userActivated) ? __('The account has been activated.') : __('The account is activated already'); ?><br />
<input name="user_login" type="hidden" value="<?php echo $user_login; ?>" />
<input name="do" type="hidden" value="GoLogIn" />
<input type="submit" value="<?php echo attribute_escape( __('Continue to Log In &raquo;') ); ?>" /></p>
</form>
<?php endif; ?>

<?php if ($_POST['do'] == 'RequestNewCode') : ?>
<hr />
The new activation code has been sent to your registered email address.
<?php elseif (ERNeedActivation($user_login) && $user_exists): ?>
<hr />
<form method="post" action="<?php bb_option('uri'); ?>bb-activate.php">
<p><?php _e('If you would like to request new activation code, you may use the following button to start the requesting process:'); ?><br />
<input name="user_login" type="hidden" value="<?php echo $user_login; ?>" />
<input name="do" type="hidden" value="RequestNewCode" />
<input type="submit" value="<?php echo attribute_escape( __('Request New Code &raquo;') ); ?>" /></p>
</form>
<?php endif; ?>

<?php bb_get_footer(); ?>
