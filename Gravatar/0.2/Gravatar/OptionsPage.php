<?php
/*
 * Copyright 2007 Yu-Jie Lin
 * 
 * This file is part of Cite this.
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
 * Creation Date: 2007-10-29T07:43:42+0800
 */

function GAOptions() {
	$options = bb_get_option('GAOptions');
 
	if (isset($_POST['manage'])) {
		switch($_POST['manage']) {
		case 'Reset All Options':
			$options = GAGetAllDefaultOptions();
			bb_update_option('GAOptions', $options);
			echo '<div class="updated"><p>All options are resetted!</p></div>';
			break;
		case 'Deactivate Plugin':
			$plugin_file = dirname(bb_plugin_basename(__FILE__)) . '/Gravatar.php';
			wp_redirect(str_replace('&#038;', '&', bb_nonce_url("plugins.php?action=deactivate&plugin=$plugin_file", "deactivate-plugin_$plugin_file")) . '&by=plugin');
			break;
			}
		}
	elseif (isset($_POST['updateGeneralOptions'])) {
		switch($_POST['updateGeneralOptions']) {
		case 'Save':
			$newOptions = array();
			$newOptions['useRegisteredEmail'] = ($_POST['useRegisteredEmail'] == 'true') ? true : false;
			$newOptions['rating'] = $_POST['rating'];
			$newOptions['size']   = $_POST['size'];
			$options = array_merge($options, $newOptions);
			bb_update_option('GAOptions', $options);
			echo '<div class="updated"><p>General options saved!</p></div>';
			break;
		case 'Reset':
			$options = array_merge($options, GAGetDefaultGeneralOptions());
			bb_update_option('GAOptions', $options);
			echo '<div class="updated"><p>General options reseted!</p></div>';
			break;
			}
		}
	elseif (isset($_POST['updateImageOptions'])) {
		switch($_POST['updateImageOptions']) {
		case 'Save':
			$newOptions = array();
			$newOptions['defaultImage'] = $_POST['defaultImage'];
			// Find default image URIs for roles
			foreach ($_POST as $name => $value) {
				if (preg_match('/defaultImage\-(.+)/', $name, $match)) {
					if (empty($_POST[$name]))
						unset($options['defaultRoleImages'][$match[1]]);
					else
						$newOptions['defaultRoleImages'][$match[1]] = $value;
					}
				}
			$options = array_merge($options, $newOptions);
			bb_update_option('GAOptions', $options);
			echo '<div class="updated"><p>Image options saved!</p></div>';
			break;
		case 'Reset':
			$options = array_merge($options, GAGetDefaultImageOptions());
			unset($options['defaultRoleImages']);
			bb_update_option('GAOptions', $options);
			echo '<div class="updated"><p>Image options reseted!</p></div>';
			break;
			}
		}
	// Render options page
?>
	<h2>Gravatar Options</h2>
		<h3>About this plugin</h3>
		<div>
		<ul>
			<li><a href="http://code.google.com/p/llbbsc/wiki/GravatarPlugin">Plugin's Website</a> - Documentations</li>
			<li><a href="http://groups.google.com/group/llbbsc">Get Support</a> - Ask question, submit feedbacks</li>
			<li><a href="http://www.livibetter.com/">Author's Website</a></li>
		</ul>
		</div>

		<h3>Management</h3>
		<div>
			<form method="post" action="">
				<p>
					<input type="submit" name="manage" value="Reset All Options" style="font-weight:bold;"/>
					<small>Reverts all options to defaults.</small>
				</p>
				<p>
					<input type="submit" name="manage" value="Deactivate Plugin" style="font-weight:bold;"/>
					<small>Be careful! This will remove all your settings for this plugin! If you don't want to lose settings, please use Plugins page to deactivate this plugin.</small>
				</p>
			</form>
		</div>

		<h3>General Options</h3>
		<div>
			<form method="post" action="">
			<table><tbody>
				<tr>
					<td><label for="useRegisteredEmail">Use Registered Email?</labal></td>
					<td>
						<select name="useRegisteredEmail" id="useRegisteredEmail">
						<option <?php if($options['useRegisteredEmail']) echo 'selected'; ?> value="false">No</option>
						<option <?php if($options['useRegisteredEmail']) echo 'selected'; ?> value="true">Yes</option>
						</select>
						<em><small>Use registered emails as Gravatar emails.</small></em>
					<td>
				</tr>
				<tr>
					<td><label for="rating">Rating:</label></td>
					<td>
						<select name="rating" id="rating">
						<option <?php if(empty($options['rating']) ) echo 'selected'; ?>   value=""></option>
						<option <?php if($options['rating'] ==  'G') echo 'selected'; ?>  value="G">G</option>
						<option <?php if($options['rating'] == 'PG') echo 'selected'; ?> value="PG">PG</option>
						<option <?php if($options['rating'] ==  'R') echo 'selected'; ?>  value="R">R</option>
						<option <?php if($options['rating'] ==  'X') echo 'selected'; ?>  value="X">X</option>
						</select>
					</td> 
				</tr>
				<tr>
					<td><label for="size">Avatar Size:</label></td>
					<td>
						<input name="size" type="text" id="size" value="<?php echo $options['size']; ?>" size="3"/>
						<em><small>1 to 80 (pixels).</small></em>
					</td>
				</tr>
			</tbody></table>
			<div class="submit">
				<input type="submit" name="updateGeneralOptions" value="Save" style="font-weight:bold;"/>
				<input type="submit" name="updateGeneralOptions" value="Reset" style="font-weight:bold;"/>
			</div>
			</form>
		</div>

		<h3 >Default Image URIs</h3>
		<div>
			<form method="post" action="">
			<table><tbody>
				<tr>
					<th>Avatar</th>
					<th>Role Name / Default Image URI</th>
				</tr>
				<tr>
					<td style="text-align: center">
<?php
$imageURI = $options['defaultImage'];
if (!empty($imageURI))
	echo '<img style="border: 1px solid black; width: 64px; height: 64px;" src="' . attribute_escape($imageURI) . '" alt="Default Image"/>';
?>
					</td>
					<td>
						<label for="defaultImage">Default Image - This will apply to all no avatars users with no default avatar role.</label><br/>
						<input name="defaultImage" type="text" id="defaultImage" value="<?php echo htmlspecialchars(stripslashes($options['defaultImage'])); ?>" size="50" />
					</td>
				</tr>
				<tr>
					<td colspan="2">The following role default avatars override the Default Image above. Applies only when users don't have an avatar.</td>
				</tr>
<?php
// List Roles
global $bb_roles;
foreach ($bb_roles->roles as $role => $data) {
	$fieldID = attribute_escape($role);
?>
				<tr>
					<td style="text-align: center">
<?php
$imageURI = $options['defaultRoleImages'][$role];
if (!empty($imageURI))
	echo '<img style="border: 1px solid black; width: 64px; height: 64px;" src="' . attribute_escape($imageURI) . '" alt="' . $bb_roles->role_names[$role] . '"/>';
?>
					</td>
					<td>
						<label for="defaultImage-<?php echo $fieldID; ?>"><?php echo $bb_roles->role_names[$role]; ?></label><br/>
						<input type="text" id="defaultImage-<?php echo $fieldID; ?>" name="defaultImage-<?php echo $fieldID; ?>" value="<?php echo htmlspecialchars(stripslashes($options['defaultRoleImages'][$role])); ?>" size="50">
					</td>
				</tr>
<?php
	}
?>
			</tbody></table>
			<div class="submit">
				<input type="submit" name="updateImageOptions" value="Save" style="font-weight:bold;"/>
				<input type="submit" name="updateImageOptions" value="Reset" style="font-weight:bold;"/>
			</div>
			</form>
		</div>
<?php
	}
?>
