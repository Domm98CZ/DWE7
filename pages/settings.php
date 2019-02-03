<?php
/*
------------------------------------------------------------------------
MIT License

Copyright (c) 2016 - 2017 Dominik Procházka

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
------------------------------------------------------------------------
* Author: Dominik Procházka
* File: settings.php
* Filepath: /pages/settings.php
*/
if(!defined("AUTHORIZED")) { die("Access Denied"); }
require_once DIR_INC."PageHeader.php";

if($user->isUserLogged()) {
	$p = $web->getUrlParam(2);
	echo $display->createpanel($lang->getLocale('P_SETTINGS_TITLE'), "primary");
	?>
	<ul class="nav nav-tabs">
	  <li role="presentation"<?php echo (empty($p) ? " class='active'" : null);?>><a href="<?php echo $web->getUrl('settings');?>"><?php echo $lang->getLocale('P_SETTINGS_T1');?></a></li>
	  <li role="presentation"<?php echo (($p == "password") ? " class='active'" : null);?>><a href="<?php echo $web->getUrl('settings/password');?>"><?php echo $lang->getLocale('P_SETTINGS_T2');?></a></li>
	  <li role="presentation"<?php echo (($p == "avatar") ? " class='active'" : null);?>><a href="<?php echo $web->getUrl('settings/avatar');?>"><?php echo $lang->getLocale('P_SETTINGS_T3');?></a></li>
	  <li role="presentation"<?php echo (($p == "profile") ? " class='active'" : null);?>><a href="<?php echo $web->getUrl('settings/profile');?>"><?php echo $lang->getLocale('P_SETTINGS_T4');?></a></li>
	  <li role="presentation"<?php echo (($p == "devices") ? " class='active'" : null);?>><a href="<?php echo $web->getUrl('settings/devices');?>"><?php echo $lang->getLocale('P_SETTINGS_T5');?> <span class="badge"><?php echo $user->countUserDevices($user->loggedUser());?></span></a></li>
	</ul>
	<br>
	<?php
	if(empty($p)) {
		$web->addToTitle(" - ".$lang->getLocale('P_SETTINGS_T1'));
		?>
		<form method="post">
			<div class="form-group">
				<label for="l_username"><?php echo $lang->getLocale('P_SETTINGS_L1');?></label>
			    <input type="text" class="form-control" id="l_username" placeholder="<?php echo $lang->getLocale('P_SETTINGS_L1');?>" value="<?php echo $user->getUserInfo($user->loggedUser(), 'user_name');?>" disabled readonly>
			</div>
			<div class="form-group">
				<label for="l_displayname"><?php echo $lang->getLocale('P_SETTINGS_L2');?></label>
			    <input type="text" class="form-control" name="user_display_name" id="l_displayname" placeholder="<?php echo $lang->getLocale('P_SETTINGS_L2');?>" value="<?php echo htmlentities($user->getUserInfo($user->loggedUser(), 'user_display_name'));?>">
			</div>
			<div class="form-group">
				<label for="l_usermail"><?php echo $lang->getLocale('P_SETTINGS_L3');?></label>
				<?php
				if($user->getUserInfo($user->loggedUser(), 'user_login_type') == "web") {
					if($user->getUserSettings($user->loggedUser(), 'mailchangeblock') == true) {
						?>
						<input type="email" class="form-control" name="user_email" id="l_usermail" placeholder="<?php echo $lang->getLocale('P_SETTINGS_L3');?>" value="<?php echo $user->getUserInfo($user->loggedUser(), 'user_email');?>" readonly disabled>
			  			<p class="text-danger text-left">*<?php echo $lang->getLocale('P_SETTINGS_EMAIL_BLOCK');?> <a class='pull-right text-right' href="<?php echo $web->getUrl("settings/email-change-stop");?>"><?php echo $lang->getLocale('P_SETTINGS_EMAIL_CLOSE');?></a></p>
						<?php
					}
					else {
						?>
						<input type="email" class="form-control" name="user_email" id="l_usermail" placeholder="<?php echo $lang->getLocale('P_SETTINGS_L3');?>" value="<?php echo $user->getUserInfo($user->loggedUser(), 'user_email');?>">
				  		<p class="text-primary text-left">*<?php echo $lang->getLocale('P_SETTINGS_EMAIL');?></p>
						<?php
					}
				}
				else {
					?>
					<input type="email" class="form-control" name="user_email" id="l_usermail" placeholder="<?php echo $lang->getLocale('P_SETTINGS_L3');?>" value="<?php echo $user->getUserInfo($user->loggedUser(), 'user_email');?>" readonly disabled>
		  			<p class="text-warning text-left">*<?php echo $lang->getLocale('P_SETTINGS_EMAIL_ACCOUNT_TYPE');?></p>
					<?php
				}
				?>
			  
			</div>
			<div class="form-group">
				<label for="l_lang"><?php echo $lang->getLocale('P_SETTINGS_L9');?></label>
			    <select id="l_lang" name="user_lang" class="form-control">
			    <?php 
			    $l = $lang->getInstalledLangs();
			    for($i = 0;$i < count($l);$i ++) {
			    	echo "<option value='".$l[$i]."'".(($user->getUserSettings($user->loggedUser(), "lang") == $l[$i]) ? " selected" : null).">".$l[$i]."</option>";
			    }
			    ?>
				</select>
			</div>
			<div class="form-group">
				<input type="submit" name="settings_main" value="<?php echo $lang->getLocale('P_SETTINGS_B1');?>" class="btn btn-primary">
			</div>

		</form>
		<?php
		if(@$_POST['settings_main']) {
			if(is_text_ok($_POST['user_display_name']) == 1) {
				if(strlen($_POST["user_display_name"]) > 2 && preg_match("/^[_a-zA-Z0-9-]+$/", $_POST["user_display_name"])) {
						
					if($user->getUserInfo($user->loggedUser(), 'user_display_name') != $_POST['user_display_name']) {
						$uid = $user->isUserDisplayNameUsed($_POST['user_display_name']);
						if($uid > 0 && $uid == $user->loggedUser() || $uid <= 0) { 
							$user->updateUserInfo($user->loggedUser(), array("user_display_name" => $display->displaytext($_POST['user_display_name'])));
						}
					}

					if(isset($_POST['user_email']) && !empty($_POST['user_email'])) {
						if(!filter_var($_POST['user_email'], FILTER_VALIDATE_EMAIL) === false) {
							if($user->getUserInfo($user->loggedUser(), 'user_email') != $_POST['user_email']) {
								$user->updateUserSettings($user->loggedUser(), 'mailchangeblock', 'true');
								$key_id = $web->registerKeyWithData("account:emailchange", $user->loggedUser(), $_POST['user_email']);
								
								$key = $web->getKeyInfo($key_id);
								$web->sendmail($_POST['user_email'], $lang->getLocale("P_SETTINGS_EMAIL_CHANGE_E"), 
									sprintf($web->buildMail('E_EMAIL_CHANGE'),
										$web->getPageTitle(),
										$web->getPageTitle(),
										htmlentities($user->getUserName($user->loggedUser())),
										$_POST['user_email'],
										"<a href='".($web->getUrl("settings/email-change/".$_POST['user_email']."/".$key['key'].""))."' target='_blank'>".($web->getUrl("settings/email-change/".$_POST['user_email']."/".$key['key'].""))."</a>"
								));
								
								echo "<p class='text-info'>".$lang->getLocale('P_SETTINGS_EMAIL_CHANGE')."</p>";
							}
						}
						else echo "<p class='text-warning'>".$lang->getLocale('P_SETTINGS_E3')."</p>";
					}

					if($user->getUserSettings($user->loggedUser(), 'lang') != $_POST['user_lang']) {
						$l = $lang->getInstalledLangs();
						if(in_array($_POST['user_lang'], $l)) {
							$user->setUserLang($_POST['user_lang']);
						}
					}

					echo "<p class='text-success'>".$lang->getLocale('P_SETTINGS_OK1')."</p>";
					$web->redirect($web->getUrl("settings"), 2);

				}
				else echo "<p class='text-warning'>".$lang->getLocale('P_SETTINGS_E2')."</p>";
			}
			else echo "<p class='text-warning'>".$lang->getLocale('P_SETTINGS_E1')."</p>";
		}
	}
	else if($p == "email-change") {
		if($user->getUserSettings($user->loggedUser(), 'mailchangeblock') == true) {
			$key = $web->getKeyInfo($web->getKeyID($web->getUrlParam(4)));
			$u = $user->getUserInfo($key['user_id']);
			if(is_set($u) && is_set($key)) {
				if($u['user_rights'] > 0) {
					if($u['user_id'] == $key['user_id']) {
						if($key['key_type'] == "account:emailchange") { 
							if((time() - $key['key_time']) < KEY_ACCOUNT_EMAIL_C) {
								$web->sendmail($key['key_data'], $lang->getLocale("P_SETTINGS_EMAIL_CHANGE_E"), 
								sprintf($web->buildMail('E_EMAIL_CHANGED'),
									$web->getPageTitle(),
									$web->getPageTitle(),
									htmlentities($key['key_data'])
								));
								
								$user->updateUserInfo($u['user_id'], array("user_email" => $key['key_data']));
								$user->deleteUserSettings($u['user_id'], 'mailchangeblock');
								$web->deleteKey($key['key_id']);
							}
						}
					}
				}
			}
		}
		$web->redirect($web->getUrl("settings"));
	}
	else if($p == "email-change-stop") {
		if($user->getUserSettings($user->loggedUser(), 'mailchangeblock') == true) {
			$user->deleteUserSettings($user->loggedUser(), 'mailchangeblock');
			
			$web->deleteKey($web->findKeyID(array(
				"key_type" => "account:emailchange",
				"user_id" => $user->loggedUser()
			)));
		}
		$web->redirect($web->getUrl("settings"));
	}
	else if($p == "password") {
		$web->addToTitle(" - ".$lang->getLocale('P_SETTINGS_T2'));
		if($user->getUserInfo($user->loggedUser(), 'user_login_type') == "web") {
			if($user->getUserSettings($user->loggedUser(), 'passchangeblock') == true) {
				echo "<p class='text-center text-primary'>".$lang->getLocale('P_SETTINGS_PASS_BLOCK')."</p>";
				echo "<p class='text-center'><a class='text-danger text-center' href='".$web->getUrl('settings/password-change-stop/')."'>".$lang->getLocale('P_SETTINGS_PASS_CLOSE')."</a></p>";
			}
			else {
				?>
				<form method="post">
					<div class="form-group">
						<label for="l_password_1"><?php echo $lang->getLocale('P_SETTINGS_L4');?></label>
					    <input type="password" name="user_password_1" class="form-control" id="l_password_1">
					</div>
					<hr>
					<div class="form-group">
						<label for="l_password_2"><?php echo $lang->getLocale('P_SETTINGS_L5');?></label>
					    <input type="password" name="user_password_2" class="form-control" id="l_password_2">
					</div>
					<div class="form-group">
						<label for="l_password_3"><?php echo $lang->getLocale('P_SETTINGS_L6');?></label>
					    <input type="password" name="user_password_3" class="form-control" id="l_password_3">
					</div>

					<div class="form-group">
						<input type="submit" name="settings_password" value="<?php echo $lang->getLocale('P_SETTINGS_B2');?>" class="btn btn-primary">
						<p class="text-primary text-left">*<?php echo $lang->getLocale('P_SETTINGS_EMAIL_NOTICE');?></p>
					</div>

				</form>
				<?php
				if(@$_POST['settings_password']) {
					if(!empty($_POST["user_password_1"]) && !empty($_POST["user_password_2"]) && !empty($_POST["user_password_3"])) {
						$u = $user->getUserInfo($user->loggedUser());
						if($u['user_pass'] == $user->generateUserPassword($_POST['user_password_1'], $u['user_salt'])) {
							if($_POST['user_password_2'] == $_POST['user_password_3']) {
					  			if(strlen($_POST['user_password_2']) > 5) {

					  				$user->updateUserSettings($u['user_id'], 'passchangeblock', 'true');
					  				$salt = $web->generateKey();
					  				$hash = $user->generateUserPassword($_POST['user_password_2'], $salt);
									$key_id = $web->registerKeyWithData("account:passchange", $u['user_id'], $hash."#|#".$salt);				
									$key = $web->getKeyInfo($key_id);

									$web->sendmail($u['user_email'], $lang->getLocale("P_SETTINGS_PASS_CHANGE_E"), 
									sprintf($web->buildMail('E_PASS_CHANGE'),
										$web->getPageTitle(),
										$web->getPageTitle(),
										htmlentities($user->getUserName($u['user_id'])),
										"<a href='".($web->getUrl("settings/password-change/".$u['user_email']."/".$key['key'].""))."' target='_blank'>".($web->getUrl("settings/password-change/".$u['user_email']."/".$key['key'].""))."</a>"
									));


				  					echo "<p class='text-success'>".$lang->getLocale('P_SETTINGS_OK2')."</p>";
									$web->redirect($web->getUrl("settings/password"), 2);
					  			}
					  			else echo "<p class='text-warning'>".$lang->getLocale('P_SETTINGS_E6')."</p>";
					  		}
					  		else echo "<p class='text-warning'>".$lang->getLocale('P_SETTINGS_E5')."</p>";
						}
						else echo "<p class='text-warning'>".$lang->getLocale('P_SETTINGS_E4')."</p>";
					}
					else echo "<p class='text-warning'>".$lang->getLocale('P_SETTINGS_E1')."</p>";
				}
			}
		}
		else {
			echo "<p class='text-center text-primary'>".$lang->getLocale('P_SETTINGS_PASS_ACCOUNT_TYPE')."</p>";
		}
	}
	else if($p == "password-change") {
		if($user->getUserSettings($user->loggedUser(), 'passchangeblock') == true) {
			$key = $web->getKeyInfo($web->getKeyID($web->getUrlParam(4)));
			$u = $user->getUserInfo($key['user_id']);
			if(is_set($u) && is_set($key)) {
				if($u['user_rights'] > 0) {
					if($u['user_id'] == $key['user_id']) {
						if($key['key_type'] == "account:passchange") { 
							if((time() - $key['key_time']) < KEY_ACCOUNT_PASS_C) {
								$web->sendmail($u['user_email'], $lang->getLocale("P_SETTINGS_EMAIL_CHANGE_E"), 
								sprintf($web->buildMail('E_PASS_CHANGED'),
									$web->getPageTitle(),
									$web->getPageTitle()
								));
								
								$x = explode("#|#", $key['key_data']);
								$user->updateUserInfo($u['user_id'], array("user_pass" => $x[0], "user_salt" => $x[1]));
								$user->deleteUserSettings($u['user_id'], 'passchangeblock');
								$web->deleteKey($key['key_id']);
							}
						}
					}
				}
			}
		}
		$web->redirect($web->getUrl("settings/password"));
	}
	else if($p == "password-change-stop") {
		if($user->getUserSettings($user->loggedUser(), 'passchangeblock') == true) {
			$user->deleteUserSettings($user->loggedUser(), 'passchangeblock');
		
			$web->deleteKey($web->findKeyID(array(
				"key_type" => "account:passchange",
				"user_id" => $user->loggedUser()
			)));
		}
		$web->redirect($web->getUrl("settings/password"));
	}
	else if($p == "avatar") {
		$web->addToTitle(" - ".$lang->getLocale('P_SETTINGS_T3'));
		?>
		<div class="row">
			<div class="col-md-3">
				<center>
					<label for="l_avatar"><?php echo $lang->getLocale('P_SETTINGS_L8');?></label><br>
					<img class='media-object image-responsive' src='<?php echo $user->getUserAvatar($user->loggedUser(), '90');?>' width='90px' height='90px'>
				</center>
			</div>
			<div class="col-md-9">
				<form method="post" enctype="multipart/form-data">
					<label for="l_avatar_method"><?php echo $lang->getLocale('P_SETTINGS_L7');?></label>
					<div class="radio">
					  <label>
					    <input type="radio" name="avatar_method" id="l_avatar_method" value="1"<?php echo ($user->getUserSettings($user->loggedUser(), "avatar") == "default" || $user->getUserSettings($user->loggedUser(), "avatar") == null) ? " checked": null;?>>
					    <?php echo $lang->getLocale('P_SETTINGS_L7-1');?>
					  </label>
					</div>
					<div class="radio">
					  <label>
					    <input type="radio" name="avatar_method" id="l_avatar_method" value="2"<?php echo ($user->getUserSettings($user->loggedUser(), "avatar") == "gravatar") ? " checked": null;?>>
					    <?php echo sprintf($lang->getLocale('P_SETTINGS_L7-2'), $web->getUrl('http://gravatar.com'), $user->getUserInfo($user->loggedUser(), 'user_email'));?>
					  </label>
					</div>
					<div class="radio">
					  <label>
					    <input type="radio" name="avatar_method" id="l_avatar_method" value="3"<?php echo ($user->getUserSettings($user->loggedUser(), "avatar") == "file") ? " checked": null;?>>
					    <?php echo sprintf($lang->getLocale('P_SETTINGS_L7-3'), fileSizeReadAble($web->getSettings('web:maxAvatarSize')), $web->getSettings('web:maxAvatarSizePX'), $web->getSettings('web:maxAvatarSizePX'));?> <input type="file" class="form-control input-sm" name="avatar_file" id="avatar_file">
					  </label>
					</div>
					<div class="form-group">
						<input type="submit" name="settings_avatar" value="<?php echo $lang->getLocale('P_SETTINGS_B3');?>" class="btn btn-primary">
					</div>
				</form>

			</div>
		</div>
		<?php
		if(@$_POST['settings_avatar']) {
			if(!empty($_POST['avatar_method'])) {
				$avatarDir = DIR_UPLOADS."avatars/";
				if($_POST['avatar_method'] == "1") {
					$user->deleteUserSettings($user->loggedUser(), 'avatar');
					$path = $avatarDir.$user->getUserSettings($user->loggedUser(), 'avatar_file');
					if(is_file($path) && file_exists($path)) unlink($path);
					$user->deleteUserSettings($user->loggedUser(), 'avatar_file');
					echo "<p class='text-success'>".$lang->getLocale('P_SETTINGS_OK3')."</p>";
				}
				else if($_POST['avatar_method'] == "2") {
					$user->updateUserSettings($user->loggedUser(), 'avatar', 'gravatar');
					$path = $avatarDir.$user->getUserSettings($user->loggedUser(), 'avatar_file');
					if(is_file($path) && file_exists($path)) unlink($path);
					$user->deleteUserSettings($user->loggedUser(), 'avatar_file');
					echo "<p class='text-success'>".$lang->getLocale('P_SETTINGS_OK3')."</p>";
				}
				else if($_POST['avatar_method'] == "3") {
					$avatarName = $web->generateKey(50).".".pathinfo($_FILES["avatar_file"]["name"], PATHINFO_EXTENSION);
					$avatarPath = $avatarDir.$avatarName;

					$check = getimagesize($_FILES["avatar_file"]["tmp_name"]);
					if($check !== false) {
						if ($_FILES["avatar_file"]["error"] > 0) {
							echo "Image upload Error <i>(".$_FILES["avatar_file"]["error"].")</i>";
						}
						else {
							$allowedTypes = array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF);
	                        $detectedType = exif_imagetype($_FILES['avatar_file']['tmp_name']);
	                        $typeok = in_array($detectedType, $allowedTypes);
	                        if($typeok) {
	                        	if($check[0] <= $web->getSettings('web:maxAvatarSizePX') && $check[1] <= $web->getSettings('web:maxAvatarSizePX')) {
		                        	if($_FILES["avatar_file"]["type"] == "image/png" || $_FILES["avatar_file"]["type"] == "image/jpeg" || $_FILES["avatar_file"]["type"] == "image/jpg" || $_FILES["avatar_file"]["type"] == "image/gif") {
			                        	if($_FILES["avatar_file"]["size"] <= $web->getSettings('web:maxAvatarSize')) {
											if(move_uploaded_file($_FILES['avatar_file']['tmp_name'], $avatarPath)) {
												$path = $avatarDir.$user->getUserSettings($user->loggedUser(), 'avatar_file');
												if(is_file($path) && file_exists($path)) unlink($path);
												
												$user->updateUserSettings($user->loggedUser(), 'avatar', 'file');
												$user->updateUserSettings($user->loggedUser(), 'avatar_file', $avatarName);	
												echo "<p class='text-success'>".$lang->getLocale('P_SETTINGS_OK3')."</p>";
											}
											else echo "<p class='text-danger'>".$lang->getLocale('P_SETTINGS_E11')."</p>";
										}
										else echo "<p class='text-danger'>".sprintf($lang->getLocale('P_SETTINGS_E10'), fileSizeReadAble($web->getSettings('web:maxAvatarSize')))."</p>";
									}
									else echo "<p class='text-danger'>".$lang->getLocale('P_SETTINGS_E9')."</p>";
								}
								else echo "<p class='text-danger'>".sprintf($lang->getLocale('P_SETTINGS_E12'), $web->getSettings('web:maxAvatarSizePX'), $web->getSettings('web:maxAvatarSizePX'))."</p>";
							}
							else echo "<p class='text-danger'>".$lang->getLocale('P_SETTINGS_E9')."</p>";
						}	
					}	
					else echo "<p class='text-danger'>".$lang->getLocale('P_SETTINGS_E8')."</p>";
				}
			}
			else echo "<p class='text-warning'>".$lang->getLocale('P_SETTINGS_E7')."</p>"; 
			$web->redirect($web->getUrl("settings/avatar"), 2);
		}
	}
	else if($p == "profile") {
		$web->addToTitle(" - ".$lang->getLocale('P_SETTINGS_T4'));
		?>
		<form method="post">
			<div class="form-group">
				<label for="l_display_email"><?php echo $lang->getLocale('P_SETTINGS_L10');?></label>

				<label class="checkbox-inline">
					<input type="radio" id="l_display_email" name="display_email" value="y"<?php echo ($user->getUserSettings($user->loggedUser(), "display_email") == "true") ? " checked" : null;?>> <?php echo $lang->getLocale('P_SETTINGS_L10-1');?>
				</label>
				<label class="checkbox-inline">
					<input type="radio" id="l_display_email" name="display_email" value="n"<?php echo ($user->getUserSettings($user->loggedUser(), "display_email") == "false" || $user->getUserSettings($user->loggedUser(), "display_email") == null) ? " checked" : null;?>> <?php echo $lang->getLocale('P_SETTINGS_L10-2');?>
				</label>
			</div>

			<div class="form-group">
				<label for="l_primary_group"><?php echo $lang->getLocale('P_SETTINGS_L12');?></label>
				<?php
				$ug = $user->getUserGroups($user->loggedUser());
				$usrg = $user->getUserSettings($user->loggedUser(), "group");
				if(count($ug) > 1) {
					?><select id="l_primary_group" name="user_primary_group" class="form-control"><?php
					for($i = 0;$i < count($ug);$i ++) {
						echo "<option value='".$ug[$i]."'".(($ug[$i] == $usrg) ? " selected" : null).">".$user->getUserGroupName($ug[$i])."</option>";
					}
					?></select><?php
				}
				else if(count($ug) == 1) {
					?><p class="text-muted"><?php echo sprintf($lang->getLocale('P_SETTINGS_L12-1'), $user->getUserGroupName($ug[0]));?></p><?php
				}
				else {
					?><p class="text-muted"><?php echo $lang->getLocale('P_SETTINGS_L12-2');?></p><?php
				}
				?>
			</div>
			

			<div class="form-group">
				<label for="user_desc">
					<?php 
					echo $lang->getLocale('P_SETTINGS_L11');
					if($user->getUserSettings($user->loggedUser(), 'desc') != true) {
						echo " <i>(".$lang->getLocale('P_SETTINGS_L11-1').")</i>";
					}
					?>
				</label>

				<?php
				echo $content->editableInput("user_desc", $lang->getLocale('P_SETTINGS_L11-1'), 
					(($user->getUserSettings($user->loggedUser(), 'desc') == true) ? $user->getUserSettings($user->loggedUser(), 'desc') : null)
				);
				?>
			</div>

			<div class="form-group">
				<input type="submit" name="settings_profile" value="<?php echo $lang->getLocale('P_SETTINGS_B4');?>" class="btn btn-primary">
			</div>
		</form>
		<?php
		if(@$_POST['settings_profile']) {
			if(!empty($_POST['user_desc']) && is_text_ok($_POST['user_desc'])) {
				$user->updateUserSettings($user->loggedUser(), 'desc', $content->clearUserInput($_POST['user_desc']));
			}
			else $user->deleteUserSettings($user->loggedUser(), 'desc');

			if($_POST['display_email'] == "y") $user->updateUserSettings($user->loggedUser(), 'display_email', "true");
			if($_POST['display_email'] == "n") $user->updateUserSettings($user->loggedUser(), 'display_email', "false");


			if(!empty($_POST["user_primary_group"])) {
				if($user->getUserGroupMembership($user->loggedUser(), $_POST["user_primary_group"])) {
					$user->updateUserSettings($user->loggedUser(), 'group', $_POST["user_primary_group"]);
				}
			}

			//echo "<p class='text-success'>".$lang->getLocale('P_SETTINGS_OK4')."</p>";
			$web->redirect($web->getUrl("settings/profile"));
		}
	}
	else if($p == "devices") {
		$web->addToTitle(" - ".$lang->getLocale('P_SETTINGS_T5'));
		$c = $user->countUserDevices($user->loggedUser());
		if($c > 0) {
			?>
			<div class="table-responsive">
				<table class="table table-hover table-condensed table-bordered table-striped">
					<thead>
						<tr>
							<th width="35%"><?php echo $lang->getLocale('P_SETTINGS_DEVICE_TABLE_H1');?></th>
							<th width="35%"><?php echo $lang->getLocale('P_SETTINGS_DEVICE_TABLE_H2');?></th>
							<th width="30%"><?php echo $lang->getLocale('P_SETTINGS_DEVICE_TABLE_H3');?></th>
						</tr>
					</thead>
					<tbody>
					<?php 
					$s = $user->getUserDevice($user->loggedUser());
					for($i = 0;$i < $c;$i ++) {
						$statusClass = null;
						if($s[$i]['device_status'] == 1) $statusClass = " class='success'";
						else if($s[$i]['device_status'] == 2) $statusClass = " class='danger'"; 

						$deviceString = null;
						if($user->getUserDeviceAuthKey($user->loggedUser()) == $s[$i]['device_auth_key']) {
							$statusClass = " class='info'";
							$deviceString .= $web->showToolTip("<i class='fa fa-user' aria-hidden='true'></i>", $lang->getLocale('P_DEVICE_USED'))." ";
						}
						$deviceString .= $web->showToolTip($web->decode_os($s[$i]['device_agent'])." (".$web->decode_browser($s[$i]['device_agent']).")", "IP: ".$s[$i]['device_ip']."");
						/*
						echo "
						<tr".$statusClass.">
							<td>".$deviceString."</td>
							<td>".$web->showToolTip($web->showTime($s[$i]['device_timestamp_register']), $lang->getLocale('P_SETTINGS_DEVICE_T1'))." - ".$web->showToolTip($web->showTime($s[$i]['device_timestamp_active']), $lang->getLocale('P_SETTINGS_DEVICE_T2'))."</td>
							<td>
								".(($s[$i]['device_status'] != 1) ? "<a href='".$web->getUrl('settings/devices/trust/'.$s[$i]['device_id'])."' class='btn btn-xs btn-success'>".$lang->getLocale('P_SETTINGS_DEVICE_ACTION_1')."</a>" : null)."
								<a href='".$web->getUrl('settings/devices/delete/'.$s[$i]['device_id'])."' class='btn btn-xs btn-warning'>".$lang->getLocale('P_SETTINGS_DEVICE_ACTION_2')."</a>
								".(($s[$i]['device_status'] != 2) ? "<a href='".$web->getUrl('settings/devices/block/'.$s[$i]['device_id'])."' class='btn btn-xs btn-danger'>".$lang->getLocale('P_SETTINGS_DEVICE_ACTION_3')."</a>" : null)."
							</td>
						</tr>";
						*/
						echo "
						<tr".$statusClass.">
							<td>".$deviceString."</td>
							<td>".$web->showToolTip($web->showTime($s[$i]['device_timestamp_register']), $lang->getLocale('P_SETTINGS_DEVICE_T1'))." - ".$web->showToolTip($web->showTime($s[$i]['device_timestamp_active']), $lang->getLocale('P_SETTINGS_DEVICE_T2'))."</td>
							<td>
								<a href='".$web->getUrl('settings/devices/delete/'.$s[$i]['device_id'])."' class='btn btn-xs btn-warning'>".$lang->getLocale('P_SETTINGS_DEVICE_ACTION_2')."</a>
								<a href='".$web->getUrl('settings/devices/block/'.$s[$i]['device_id'])."' class='btn btn-xs btn-danger'>".$lang->getLocale('P_SETTINGS_DEVICE_ACTION_3')."</a>
							</td>
						</tr>";
					}
					?>
					</tbody>
				</table>
			</div>
			<?php
			if($web->getUrlParam(2) == "devices") {
				$device_id = $web->getUrlParam(4);
				$p = $web->getUrlParam(3);
				if(is_numeric($device_id) && isset($p) && !empty($p)) {
					/*if($p == "trust") {
						$user->updateDeviceData($device_id, $user->loggedUser(), array("device_status" => "1"));
					}
					else */if($p == "block") {
						$user->updateDeviceData($device_id, $user->loggedUser(), array("device_status" => "2"));
					}
					else if($p == "delete") {
						$user->deleteDevice($device_id, $user->loggedUser());	
					}
					$web->redirect($web->geturl("settings/devices"));
				}
			}
			
		}
		else echo "<p class='text-primary text-center'>".$lang->getLocale('P_SETTINGS_NO_DEVICE')."</p>";
	}
	else $web->redirect($web->getUrl());
	echo $display->closepanel();
}
else $web->redirect($web->getUrl());
?>