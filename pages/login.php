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
* File: register.php
* Filepath: /pages/register.php
*/
if(!defined("AUTHORIZED")) { die("Access Denied"); }
require_once DIR_INC."PageHeader.php";

if(!$user->isUserLogged()) {
	echo $display->createpanel($lang->getLocale("P_LOGIN_TITLE"), "success");
	?>
	<section>
		<div>
			<form method="post">
				<div class="form-group">
					<label><?php echo $lang->getLocale("P_LOGIN_L1");?></label>
					<input type="text" name="user_name" class="form-control" id="" value="">
				</div>
						
				<div class="form-group">
					<label><?php echo $lang->getLocale("P_LOGIN_L2");?></label>
					<input type="password" name="user_pass" class="form-control" id="" value="">
				</div>

				<div class="checkbox">
			    	<label><input name="user_auto_login" type="checkbox" value="<?php echo $lang->getLocale("P_LOGIN_L3");?>"> <?php echo $lang->getLocale("P_LOGIN_L3");?></label>
		    </div>

		    <div style="float:right;text-align:right;">
		    	<a href="<?php echo $web->getUrl("register");?>" class="btn btn-default"><?php echo $lang->getLocale("P_LOGIN_B1");?></a>
		      <a href="<?php echo $web->getUrl("password");?>" class="btn btn-warning"><?php echo $lang->getLocale("P_LOGIN_B3");?></a>
		    	<input class="btn btn-success" type="submit" name="login" value="<?php echo $lang->getLocale("P_LOGIN_B2");?>">
		    </div>
			</form>
			<?php
			/* AutoLogin */
			$user->tryAutoLoginUser();
			/* Login */
			if(@$_POST["login"]) {
				if(is_set($_POST["user_name"]) && is_set($_POST["user_pass"])) {
					$u = $user->getUserInfo($user->isUserExists($_POST['user_name']));
					if(is_set($u) && $u['user_id'] > 0 && $u['user_login_type' == 'web']) {
						if($u['user_pass'] == $user->generateUserPassword($_POST['user_pass'], $u['user_salt'])) {
							if($u['user_rights'] >= 1 || $u['user_rights'] >= "1") {
								$r = $user->loginUser($u['user_id']);
								if($r == 1) {
									if(isset($_POST["user_auto_login"])) {
										$device_id = $user->getUserActualDevice($u['user_id']);
										$device = $user->getUserDeviceData($device_id);
										$autoLoginKey = null;
										if(isset($device['device_login_key'])) $autoLoginKey = $device['device_login_key'];
										else {
											$autoLoginKey = $web->generateKey(64);
											$user->updateDeviceData($device_id, $u['user_id'], array("device_login_key" => $autoLoginKey));
										}
										setcookie("dwe7autologinKey", $autoLoginKey, 2147483647, "/");
										setcookie("dwe7autologinUser", $u['user_id'], 2147483647, "/");
									}
									else {
										if (isset($_COOKIE['dwe7autologinKey'])) {
											setcookie("dwe7autologinKey", null, -1, "/");
											unset($_COOKIE['dwe7autologinKey']);
										}
										if (isset($_COOKIE['dwe7autologinUser'])) {
											setcookie("dwe7autologinUser", null, -1, "/");
											unset($_COOKIE['dwe7autologinUser']);
										}
									}
									
									if(is_set($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], '/login') !== false) {
										echo sprintf($lang->getLocale("P_LOGIN_OK"), htmlentities($u['user_name']));
										$web->redirect($web->getUrl(), 3);
									}
									else $web->redirect($web->getUrl());
								}		
								else { 
									echo "<p class='text-danger'>".$lang->getLocale('P_DEVICE_BLOCKED')."</p>";
									$web->redirect($web->getUrl('logout'), 2);
								} 
							}
							else if($u['user_rights'] == -1 || $u['user_rights'] == "-1") echo sprintf($lang->getLocale("P_LOGIN_E4"), htmlentities($u['user_name']));
							else echo sprintf($lang->getLocale("P_LOGIN_E3"), htmlentities($u['user_name']), $web->getUrl("activate/".urlencode($u['user_name']).""));
						}
						else echo $lang->getLocale("P_LOGIN_E2"); 
					}
					else echo $lang->getLocale("P_LOGIN_E2"); 
				}
				else echo $lang->getLocale("P_LOGIN_E1");
			}
			?>
		</div>
	</section>
	<?php
	echo $display->closepanel();
}
else header("location: ".$web->getUrl()."");
?>