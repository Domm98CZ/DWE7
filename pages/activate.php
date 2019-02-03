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
* File: activate.php
* Filepath: /pages/activate.php
*/
if(!defined("AUTHORIZED")) { die("Access Denied"); }
require_once DIR_INC."PageHeader.php";


if(!$user->isUserLogged()) {

	$username_display = null;
	if(is_set($web->getUrlParam(2)) && $user->isUserExists($web->getUrlParam(2))) {
		$username_display = $web->getUrlParam(2);
	}
	else if(is_set($web->getUrlParam(2)) && is_set($web->getUrlParam(3)) && $user->isEmailUsed($web->getUrlParam(2)) && $web->getKeyID($web->getUrlParam(3)) > 0) {
		$u = $user->getUserInfo($user->isEmailUsed($web->getUrlParam(2)));
		if(is_set($u) && $u['user_id'] > 0) {
			if($u['user_rights'] == 0) {
				$key = $web->getKeyInfo($web->getKeyID($web->getUrlParam(3)));
				if($key['user_id'] == $u['user_id']) {
					if($key['key_type'] == "account:active") { // if its active key
						echo $display->createpanel($lang->getLocale("P_ACTIVE_TITLE"), "primary");
						if((time() - $key['key_time']) < KEY_ACCOUNT_ACTIVE) { // < 1 day
							$user->updateUserInfo($u['user_id'], array("user_rights" => "1"));
							$web->deleteKey($key['key_id']);
							$web->sendmail($u['user_email'], $lang->getLocale("P_ACTIVE_TITLE"), 
								sprintf($web->buildMail('E_ACTIVATED'), 
									$web->getTitle(),
									$web->getTitle(),
									htmlentities($u['user_name']), 
									htmlentities($u['user_name'])
								)
							);
							$web->redirect($web->getUrl("login"), 5);
							echo sprintf($lang->getLocale("P_ACTIVE_OK2"), htmlentities($u['user_name']));
						}
						else {
							$web->deleteKey($key['key_id']);
							$web->redirect($web->getUrl("activate/".urlencode($u['user_name']).""), 5);
							echo sprintf($lang->getLocale("P_ACTIVE_E5"), $web->getUrl("activate/".urlencode($u['user_name']).""));						
						}
						echo $display->closepanel();
					}
					else header("location: ".$web->getUrl()."");
				}
				else header("location: ".$web->getUrl()."");	
			}
			else header("location: ".$web->getUrl()."");
		}
		else header("location: ".$web->getUrl()."");
	}
	else {
		echo $display->createpanel($lang->getLocale("P_ACTIVE_TITLE"), "primary");
		?>
		<section>
			<div>
			  <form method="post">
			    <div class="form-group col-lg-12">
			      <label for="name"><?php echo $lang->getLocale("P_ACTIVE_L1");?></label>
			      <input type="text" name="user_name" class="form-control" id="name" value="<?php echo (($username_display != null) ? $username_display : null);?>">
			    </div>

			    <div style="float:right;text-align:right;">
			      <a href="<?php echo $web->getUrl("login");?>" class="btn btn-default"><?php echo $lang->getLocale("P_ACTIVE_B1");?></a>
			      <input type="submit" name="active" value="<?php echo $lang->getLocale("P_ACTIVE_B2");?>" class="btn btn-primary">
			    </div>
			  </form>
			  <?php 
			  if(@$_POST['active']) {
			  	if(is_set($_POST['user_name'])) {
			  		$u = $user->getUserInfo($user->isUserExists($_POST['user_name']));
			  		if(is_set($u) && $u['user_id'] > 0) {
		  				if($u['user_rights'] == 0) {
		  					$key_id = $web->registerKey("account:active", $u['user_id']);
							$key = $web->getKeyInfo($key_id);
							$web->sendmail($u['user_email'], $lang->getLocale("P_ACTIVE_TITLE"), 
								sprintf($web->buildMail('E_ACTIVATE'), 
									$web->getTitle(),
									$web->getTitle(),
									htmlentities($u['user_name']), 
									"<a href='".($web->getUrl("activate/".$u['user_email']."/".$key['key'].""))."' target='_blank'>".($web->getUrl("activate/".$u['user_email']."/".$key['key'].""))."</a>"
								)
							);
							echo $lang->getLocale("P_ACTIVE_OK1");
		  				}
		  				else if($u['user_rights'] == -1 || $u['user_rights'] == "-1") echo sprintf($lang->getLocale("P_ACTIVE_E4"), htmlentities($u['user_name']));
		  				else echo $lang->getLocale("P_ACTIVE_E3");
			  		}
			  		else echo sprintf($lang->getLocale("P_ACTIVE_E2"), htmlentities($u['user_name']));
			  	}
			  	else echo $lang->getLocale("P_ACTIVE_E1");
			  }
			  ?>
			</div>
		</section>
		<?php
		echo $display->closepanel();
	}
}
else header("location: ".$web->getUrl()."");
?>