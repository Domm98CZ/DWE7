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
* File: password.php
* Filepath: /pages/password.php
*/
if(!defined("AUTHORIZED")) { die("Access Denied"); }
require_once DIR_INC."PageHeader.php";

if(!$user->isUserLogged()) {
	echo $display->createpanel($lang->getLocale("P_PASSWORD_TITLE"), "primary");
	if(is_set($web->getUrlParam(2)) && is_set($web->getUrlParam(3)) && $user->isEmailUsed($web->getUrlParam(2)) && $web->getKeyID($web->getUrlParam(3)) > 0) {
		$u = $user->getUserInfo($user->isEmailUsed($web->getUrlParam(2)));
		if(is_set($u) && $u['user_id'] > 0) {
			if($u['user_rights'] >= 0) {
				$key = $web->getKeyInfo($web->getKeyID($web->getUrlParam(3)));
				if($key['user_id'] == $u['user_id']) {
					if($key['key_type'] == "account:password") { // if its active key
						if((time() - $key['key_time']) < 86400) { // < 1 day
							?>
							<section>
								<div>
							  		<form method="post">
									    <div class="form-group col-lg-12">
									      <label for="pass"><?php echo $lang->getLocale("P_PASSWORD_L3");?></label>
									      <input type="password" name="user_password" class="form-control" id="pass" value="">
									    </div>

									    <div class="form-group col-lg-12">
									      <label for="pass_r"><?php echo $lang->getLocale("P_PASSWORD_L4");?></label>
									      <input type="password" name="user_password_r" class="form-control" id="pass_r" value="">
									    </div>

									    <div style="float:right;text-align:right;">
									      <input type="submit" name="password" value="<?php echo $lang->getLocale("P_PASSWORD_B3");?>" class="btn btn-default">
									      <input type="submit" name="password_change" value="<?php echo $lang->getLocale("P_PASSWORD_B4");?>" class="btn btn-primary">
									    </div>
							   		</form>
							   		<?php 
							   		if(@$_POST['password']) {
							   			$web->deleteKey($key['key_id']);

							   			$web->sendmail($u['user_email'], $lang->getLocale("P_PASSWORD_TITLE"), 
											sprintf($web->buildMail('E_PASSWORD_CLOSE'), 
												$web->getTitle(),
												$web->getTitle()
											)
										);

							   			$web->redirect($web->getUrl(), 3);
							   			echo $lang->getLocale("P_PASSWORD_T2");
							   		}
							   		if(@$_POST['password_change']) {
							   			if(is_set($_POST['user_password']) && is_set($_POST['user_password_r'])) {
							   				if($_POST['user_password'] == $_POST['user_password_r']) {
							   					$salt = $user->generateUserSalt();
						   						$user->updateUserInfo($u['user_id'], array(
						   							"user_pass" => $user->generateUserPassword($_POST['user_password'], $salt),
						   							"user_salt" => $salt
						   						));

						   						$web->sendmail($u['user_email'], $lang->getLocale("P_PASSWORD_TITLE"), 
													sprintf($web->buildMail('E_PASSWORD_CHANGE'), 
														$web->getTitle(),
														$web->getTitle()
													)
												);

						   						$web->deleteKey($key['key_id']);	
							   					$web->redirect($web->getUrl("login"), 5);
							   					echo $lang->getLocale("P_PASSWORD_OK2");
							   				}
							   				else echo $lang->getLocale("P_PASSWORD_E6");
							   			}
							   			else echo $lang->getLocale("P_PASSWORD_E5");
							   		}
							   		?>
								</div>
							</section>
							<?php
						}
						else {
							$web->deleteKey($key['key_id']);
							$web->redirect($web->getUrl("password"), 5);
							echo sprintf($lang->getLocale("P_PASSWORD_E4"), $web->getUrl("password"));						
						}
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
		?>
		<section>
			<div>
			  <form method="post">
			    <p class="text-center text-muted"><?php echo $lang->getLocale("P_PASSWORD_T1");?></p>
			    <div class="form-group col-lg-12">
			      <label for="name"><?php echo $lang->getLocale("P_PASSWORD_L1");?></label>
			      <input type="text" name="user_name" class="form-control" id="name" value="">
			    </div>

			    <div class="form-group col-lg-12">
			      <label for="email"><?php echo $lang->getLocale("P_PASSWORD_L2");?></label>
			      <input type="text" name="user_email" class="form-control" id="email" value="">
			    </div>

			    <div style="float:right;text-align:right;">
			      <a href="<?php echo $web->getUrl("login");?>" class="btn btn-default"><?php echo $lang->getLocale("P_PASSWORD_B1");?></a>
			      <input type="submit" name="password" value="<?php echo $lang->getLocale("P_PASSWORD_B2");?>" class="btn btn-primary">
			    </div>
			  </form>
			  <?php 
			  if(@$_POST['password']) {
			  	if(is_set($_POST['user_name']) || is_set($_POST['user_email'])) {
			  		$u = null;
			  		if(is_set($_POST['user_name'])) {
			  			$u = $user->getUserInfo($user->isUserExists($_POST['user_name']));
			  		}
			  		else if(is_set($_POST['user_email'])) {
			  			$u = $user->getUserInfo($user->isEmailUsed($_POST['user_email']));
			  		}

			  		if(is_set($u) && $u['user_id'] > 0) {
			  			if($u['user_rights'] >= 0) {
		  					$key_id = $web->registerKey("account:password", $u['user_id']);
							$key = $web->getKeyInfo($key_id);
							$web->sendmail($u['user_email'], $lang->getLocale("P_PASSWORD_TITLE"), 
								sprintf($web->buildMail('E_PASSWORD'), 
									$web->getTitle(),
									$web->getTitle(),
									htmlentities($u['user_name']), 
									"<a href='".($web->getUrl("password/".$u['user_email']."/".$key['key'].""))."' target='_blank'>".($web->getUrl("password/".$u['user_email']."/".$key['key'].""))."</a>"
								)
							);
							echo $lang->getLocale("P_PASSWORD_OK1");
			  			}
			  			else echo $lang->getLocale("P_PASSWORD_E3");
			  		}
			  		else echo $lang->getLocale("P_PASSWORD_E2");
			  	}
			  	else echo $lang->getLocale("P_PASSWORD_E1");
			  }
			  ?>
			</div>
		</section>
		<?php
	}
	echo $display->closepanel();
}
else header("location: ".$web->getUrl()."");