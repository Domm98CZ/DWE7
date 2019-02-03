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
	echo $display->createpanel($lang->getLocale("P_REGISTER_TITLE"), "primary");
	?>
	<section>
		<div>
		  <form method="post">
		    <div class="form-group col-lg-12">
		      <label><?php echo $lang->getLocale("P_REGISTER_L1");?></label>
		      <input type="text" name="user_name" class="form-control" id="" value="">
		    </div>
		      				
		    <div class="form-group col-lg-6">
		      <label><?php echo $lang->getLocale("P_REGISTER_L2");?></label>
		      <input type="password" name="user_pass" class="form-control" id="" value="">
		    </div>
		      				
		    <div class="form-group col-lg-6">
		      <label><?php echo $lang->getLocale("P_REGISTER_L3");?></label>
		      <input type="password" name="user_pass_r" class="form-control" id="" value="">
		    </div>
		      								
		    <div class="form-group col-lg-6">
		      <label><?php echo $lang->getLocale("P_REGISTER_L4");?></label>
		      <input type="text" name="user_email" class="form-control" id="" value="">
		    </div>
		      				
		    <div class="form-group col-lg-6">
		      <label><?php echo $lang->getLocale("P_REGISTER_L5");?></label>
		      <input type="text" name="user_email_r" class="form-control" id="" value="">
		    </div>
		    
		    <div style="float:right;text-align:right;">
		      <a href="<?php echo $web->getUrl("login");?>" class="btn btn-default"><?php echo $lang->getLocale("P_REGISTER_B1");?></a>
		      <input type="submit" name="register" value="<?php echo $lang->getLocale("P_REGISTER_B2");?>" class="btn btn-primary">
		      <p class="text-primary"><?php echo $lang->getLocale("P_REGISTER_T1");?></p>
		    </div>
		  </form>
		  <?php 
		  if(@$_POST['register']) {
		  	if(is_set($_POST['user_name']) && is_set($_POST['user_pass']) && is_set($_POST['user_pass_r']) && is_set($_POST['user_email']) && is_set($_POST['user_email_r'])) {
		  		if(strlen($_POST["user_name"]) > 2 && preg_match("/^[_a-zA-Z0-9-]+$/", $_POST["user_name"])) {
			  		if($_POST['user_pass'] == $_POST['user_pass_r']) {
			  			if(strlen($_POST['user_pass']) > 5) {
				  			if (!filter_var($_POST['user_email'], FILTER_VALIDATE_EMAIL) === false) {
				  				if($_POST['user_email'] == $_POST['user_email_r']) {
				  					if(!$user->isUserExists($_POST['user_name'])) {
				  						if(!$user->isEmailUsed($_POST['user_email'])) {
				  							$user_id = $user->createUser($_POST["user_name"], $_POST['user_pass'], $_POST['user_email']);
                        					$user->setUserDefaultRights($user_id);
				  							$key_id = $web->registerKey("account:active", $user_id);

				  							$censored_pass = null;
					                      	$chars = strlen($_POST["user_pass"]);
					                      	for($i = 0;$i < $chars;$i ++) $censored_pass .= "*";

				  							$key = $web->getKeyInfo($key_id);
				  							$web->sendmail($_POST['user_email'], $lang->getLocale("P_REGISTER_TITLE"), 
				  								sprintf($web->buildMail('E_REGISTER'), 
													$web->getPageTitle(),
													$web->getPageTitle(),
													htmlentities($_POST['user_name']), 
													$censored_pass,
													"<a href='".($web->getUrl("activate/".$_POST['user_email']."/".$key['key'].""))."' target='_blank'>".($web->getUrl("activate/".$_POST['user_email']."/".$key['key'].""))."</a>"
												)
				  							);
				  							$web->redirect($web->getUrl("login"), 5);
				  							echo $lang->getLocale("P_REGISTER_OK");
				  						}
				  						else echo $lang->getLocale("P_REGISTER_E7");
				  					}
				  					else echo $lang->getLocale("P_REGISTER_E6");
				  				}
				  				else echo $lang->getLocale("P_REGISTER_E5");
			  				}
			  				else echo $lang->getLocale("P_REGISTER_E4");
			  			}
			  			else echo $lang->getLocale("P_REGISTER_E8");	
			  		}
			  		else echo $lang->getLocale("P_REGISTER_E3");
		  		}
		  		else echo $lang->getLocale("P_REGISTER_E2");
		  	}
		  	else echo $lang->getLocale("P_REGISTER_E1");
		  }
		  ?>
		</div>
	</section>
	<?php
	echo $display->closepanel();
}
else header("location: ".$web->getUrl()."");
?>