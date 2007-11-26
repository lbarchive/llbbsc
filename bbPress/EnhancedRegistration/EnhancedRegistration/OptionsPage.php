<?php
/*
 * Copyright 2007 Yu-Jie Lin
 * 
 * This file is part of Enhanced Registration.
 * 
 * Cite this is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License as published by the Free
 * Software Foundation; either version 3 of the License, or (at your option)
 * any later version.
 * 
 * Cite this is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for
 * more details.
 * 
 * You should have received a copy of the GNU General Public License along
 * with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *
 * Author: Yu-Jie Lin
 * Creation Date: 2007-11-26T13:10:02+0800
 */

function EROptions() {
	global $ER_ERROR;
	if (isset($ER_ERROR))
		echo '<div class="error"><p>', $ER_ERROR, '</p></div>';
	if (isset($_POST['UserManage'])) {
		switch($_POST['do']) {
		case 'delete':
			// Check number
			$hours = floor($_POST['due']);
			if ($hours > 0) {
				global $bbdb;
	        	$IDs = $bbdb->get_col("SELECT $bbdb->users.ID FROM $bbdb->users, $bbdb->usermeta WHERE $bbdb->users.ID = $bbdb->usermeta.user_id AND $bbdb->usermeta.meta_key = 'act_code' AND DATE_ADD('1970-01-01', INTERVAL UNIX_TIMESTAMP() SECOND) >= DATE_ADD($bbdb->users.user_registered, INTERVAL $hours HOUR)");
				foreach ($IDs as $ID)
					bb_delete_user($ID);
				echo '<div class="updated"><p>', sizeof($IDs),  ' user(s) have been deleted!</p></div>';
				}
			else
				echo '<div class="updated"><p>', $hours, ' is not a valid number for deleting!</p></div>';	
			break;
			}
		}
	// Render options page
?>
	<h2><?php _e('Enhanced Registration Options', ER_DOMAIN); ?></h2>
		<h3><?php _e('About this plugin', ER_DOMAIN); ?></h3>
		<div>
		<ul>
			<li><?php _e('Plugin\'s Website', ER_DOMAIN); ?> - not ready</li>
			<li><a href="http://groups.google.com/group/llbbsc"><?php _e('Get Support', ER_DOMAIN); ?></a> - <?php _e('Ask questions, submit feedbacks', ER_DOMAIN); ?></li>
			<li><a href="http://www.livibetter.com/"><?php _e('Author\'s Website', ER_DOMAIN); ?></a></li>
		</ul>
		</div>

		<h3><?php _e('User Management', ER_DOMAIN); ?></h3>
		<div>
			<form method="post" action="">
				<p>
					Delete user haven't activated in <input type="text" name="due" value="72" size="5"/> hour(s)<small><?php _e('(Integer number only)', ER_DOMAIN); ?></small>
					<input type="hidden" name="do" value="delete"/>
					<input type="submit" name="UserManage" value="<?php _e('Delete them &raquo;', ER_DOMAIN); ?>" style="font-weight:bold;"/>
					
				</p>
			</form>
		</div>
<?php
	}
?>
