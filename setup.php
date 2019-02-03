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
* File: setup.php
* Filepath: setup.php
*/

require_once "_core/maincore.php";
$setupDB = null;
?><!DOCTYPE html>
<html> 
	<head>
		<title><?php echo $lang->getLocale('SETUP_T1');?> DWE7</title>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8">
		<meta charset="utf-8">
		<meta name="robots" content="noindex, nofollow">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<meta name="generator" content="<?php echo $web->getWebVersionAsString();?>" />

		<!-- Web Styles -->
		<link href="admin/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />   
	    <link href="admin/assets/css/main.css" rel="stylesheet" type="text/css" />    
	    <link href="//code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet" type="text/css" />    
	    <link href="admin/assets/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
	    <link href="admin/assets/css/skins/_all-skins.min.css" rel="stylesheet" type="text/css" />
		<!-- Custom Fonts --> 
		<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
	    <!--[if lt IE 9]>
	        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
	        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	    <![endif]-->
	</head>
	<body class="login-page">
	    <div class="login-box">
	    	<div class="login-logo">
	      	<?php echo $lang->getLocale('SETUP_T1');?> <b>DWE7</b>
	      	</div>
	       	<div class="login-box-body">

	       		<?php
	       		if(empty($_GET['step'])) {
	       			// Lang select
	       			?>
		    		<form method="post">

			    		<div class="form-group">
		                    <label><?php echo $lang->getLocale('SETUP_S1_T1');?></label>
		                    <select name="lang" class="form-control">
		                    <option selected disabled><?php echo $lang->getLocale('SETUP_S1_L1');?></option>
				    		<?php
				    		$l = $lang->getInstalledLangsTitles(2);
				    		if(count($l) > 0) {
				    			for($i = 0;$i < count($l);$i ++) {
				    				echo "<option value='".$l[$i][0]."'>".$l[$i][0]." - ".$l[$i][1]."</option>";
				    			}
				    		}
				    		?>
				    		</select>
				    	</div>

				    	<input type="submit" name="setLang" class="btn btn-block btn-flat btn-primary" value="<?php echo $lang->getLocale('SETUP_S1_B1');?>">

			    		<hr><p class="login-box-msg"><?php echo $lang->getLocale('SETUP_S1_H1');?></p>
		    		</form>
	       			<?php
	       			if(@$_POST["setLang"] && isset($_POST["lang"]) && !empty($_POST["lang"])) {
	       				$langs = $lang->getInstalledLangsTitles();
	       				if(in_array($_POST["lang"], $langs)) {
       						$user->setUserLang($_POST["lang"]);
       						$web->redirect($web->serializeUrl("setup.php?step=2"));
       					}
	       			}
	       		}
	       		else if(intval($_GET['step']) == 2) {
	       			// Welcome step
	       			?>
	       			<p class="login-box-msg"><?php echo $lang->getLocale('SETUP_S2_T1');?></p>
	       			<p class="text-info text-justify"><?php echo $lang->getLocale('SETUP_S2_T2');?></p>
	       			<hr>
	       			<form method="post">
	       				<input type="submit" name="setWelcome" class="btn btn-block btn-flat btn-primary" value="<?php echo $lang->getLocale('SETUP_S2_B1');?>">
	       			</form>
	       			<?php
	       			if(@$_POST["setWelcome"]) {
	       				$web->redirect($web->serializeUrl("setup.php?step=3"));
	       			}
	       		}
	       		else if(intval($_GET['step']) == 3) {
	       			// Database install
	       			?>
	       			<p class="login-box-msg"><?php echo $lang->getLocale('SETUP_S3_T1');?></p>
	       			<form method="post">

	       				<div class="form-group">
	                      <label for="l_database_server"><?php echo $lang->getLocale('SETUP_S3_L1');?></label>
	                      <input type="text" class="form-control" name="database_server" id="l_database_server" placeholder="<?php echo $lang->getLocale('SETUP_S3_L1_P');?>">
	                    </div>

	                    <div class="form-group">
	                      <label for="l_database_name"><?php echo $lang->getLocale('SETUP_S3_L2');?></label>
	                      <input type="text" class="form-control" name="database_name" id="l_database_name" placeholder="<?php echo $lang->getLocale('SETUP_S3_L2_P');?>">
	                    </div>

	                    <div class="form-group">
	                      <label for="l_database_user"><?php echo $lang->getLocale('SETUP_S3_L3');?></label>
	                      <input type="text" class="form-control" name="database_user" id="l_database_user" placeholder="<?php echo $lang->getLocale('SETUP_S3_L3_P');?>">
	                    </div>

	                    <div class="form-group">
	                      <label for="l_database_password"><?php echo $lang->getLocale('SETUP_S3_L4');?></label>
	                      <input type="password" class="form-control" name="database_password" id="l_database_password" placeholder="<?php echo $lang->getLocale('SETUP_S3_L4_P');?>">
	                    </div>

	       				<input type="submit" name="setDatabase" class="btn btn-block btn-flat btn-primary" value="<?php echo $lang->getLocale('SETUP_S3_B1');?>">
	       			</form>
	       			<hr>
	       			<p class="text-info"><?php echo $lang->getLocale('SETUP_S3_B1_H');?></p>
	       			<?php
	       			if(@$_POST["setDatabase"]) {
						if(!empty($_POST["database_server"]) && !empty($_POST["database_user"]) && !empty($_POST["database_password"]) && !empty($_POST["database_name"])) {
							$prefix = $web->generateKey(6);
							$setupDB = new Database($_POST["database_server"], $_POST["database_user"], $_POST["database_password"], $_POST["database_name"], $prefix);
							$dbe = $setupDB->getError();
							if(empty($dbe) || !isset($dbe)) {				

								$lines = file(DIR_BACKUPS."schema.sql");
								$schema = null;
								foreach ($lines as $line) {
								  $schema .= $line;
								}
								$schema = str_replace("prefix", $prefix, $schema);
								$r = $setupDB->query($schema);

								if($r == true) {

									$webID = $web->websiteRegister();
									$webID = trim(preg_replace('/\s\s+/', ' ', $webID));
									if(trim(preg_replace('/\s\s+/', ' ', $web->websiteActive($webID))) == $webID) {

										$web->createConfig($_POST["database_server"], $_POST["database_name"], $_POST["database_user"], $_POST["database_password"], $prefix."_", $webID);

										$web->redirect($web->serializeUrl("setup.php?step=4", 2));	
									}
									else {
										echo $display->adminAlert($lang->getLocale("ADMIN_E_C"), sprintf($lang->getLocale("SETUP_S3_E3"), $webID), "danger"); 
		  								$web->redirect($web->serializeUrl("setup.php?step=3"), 2);
									}
								}
								else {
									echo $display->adminAlert($lang->getLocale("ADMIN_E_C"), $lang->getLocale("SETUP_S3_E2"), "danger"); 
	  								$web->redirect($web->serializeUrl("setup.php?step=3"), 2);
								}
							}
							else {
								echo $display->adminAlert($lang->getLocale("ADMIN_E_C"), sprintf($lang->getLocale("SETUP_S3_E1"), $dbe), "danger"); 
	  							$web->redirect($web->serializeUrl("setup.php?step=3"), 2);
							}
						}
						else {
							echo $display->adminAlert($lang->getLocale("ADMIN_E_W"), $lang->getLocale("ADMIN_E_E1"), "danger"); 
							$web->redirect($web->serializeUrl("setup.php?step=3"), 2);		
						}
	       			}
	       		}
	       		else if(intval($_GET['step']) == 4) {
	       			// Website configuration
	       			?>
					<p class="login-box-msg"><?php echo $lang->getLocale('SETUP_S4_T1');?></p>
	       			<form method="post">

	       				<div class="form-group">
	                      <label for="l_website_title"><?php echo $lang->getLocale('SETUP_S4_L1');?></label>
	                      <input type="text" class="form-control" name="website_title" id="l_website_title" placeholder="<?php echo $lang->getLocale('SETUP_S4_L1_P');?>">
	                    </div>

	                    <div class="form-group">
	                      <label for="l_website_email"><?php echo $lang->getLocale('SETUP_S4_L2');?></label>
	                      <input type="email" class="form-control" name="website_email" id="l_website_email" placeholder="<?php echo $lang->getLocale('SETUP_S4_L2_P');?>">
	                    </div>

	                    <div class="form-group">
			              <label class="control-label" for="l_web_protocol"><?php echo $lang->getLocale('ADMIN_MS_L4');?></label>
			              <br>
			              <label class="radio-inline">
			                  <input type="radio" name="web_protocol" id="l_web_protocol" value="http" checked> http://
			              </label>
			              <label class="radio-inline">
			                <input type="radio" name="web_protocol" id="l_web_protocol" value="https"> <span class="text-success">https://</span>
			              </label>
			            </div>

	                    <div class="form-group">
			            	<label for="l_web_url"><?php echo $lang->getLocale('ADMIN_MS_L3');?></label>
			                <input type="text" name="web_url" class="form-control" id="l_web_url" value="<?php echo htmlentities($_SERVER['HTTP_HOST']);?>">
			            </div>


	       				<input type="submit" name="setConfig" class="btn btn-block btn-flat btn-primary" value="<?php echo $lang->getLocale('SETUP_S4_B1');?>">
	       			</form>
	       			<hr>
	       			<?php
	       			if(@$_POST["setConfig"]) {
	       				if(isset($_POST["website_title"]) && !empty($_POST["website_title"]) && isset($_POST["website_email"]) && !empty($_POST["website_email"]) && isset($_POST["web_url"]) && !empty($_POST["web_url"])) {
		       				$web->updateSettings("web:title", $content->clearUserInputAll($_POST["website_title"]));
	              			$web->updateSettings("web:email", $content->clearUserInputAll($_POST["website_email"]));
	              			$web->updateSettings("web:url", $content->clearUserInputAll($_POST["web_url"]));
	              			if(strtolower($_POST["web_protocol"]) == "https") $web->updateSettings("web:protocol", "https://");
              				else $web->updateSettings("web:protocol", "http://"); 

              				// DEFAULT SETTINGS
              				$web->updateSettings("web:theme", $display->getDefaultTheme());
              				$web->updateSettings("web:lang", $user->getUserLang());
              				$web->updateSettings("web:maintenance", "Coming soon..");
              				$web->updateSettings("web:maintenanceLevel", "a");
              				$web->updateSettings("web:menu", "1");
              				$web->updateSettings("web:maxAvatarSize", "5242880");
              				$web->updateSettings("web:maxAvatarSizePX", "250");
              				$web->updateSettings("web:displayRenderTime", "1");
              				
              				$web->updateSettings("content:homepage", "news");
              				$web->updateSettings("content:urlType", "3");
              				$web->updateSettings("content:newsNum", "5");

              				$web->updateSettings("comments:num", "5");

              				$web->updateSettings("theme:sidebarLeft", "1");
              				$web->updateSettings("theme:sidebarRight", "1");
              				$web->updateSettings("theme:container", "0");

              				$web->updateSettings("scripts:jquery", "//code.jquery.com/jquery-2.2.3.min.js");
              				$web->updateSettings("scripts:font-awesome", "//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css");
              				$web->updateSettings("scripts:ckeditor", "//cdn.ckeditor.com/4.5.5/basic/ckeditor.js");

              				$db->insert("menus", array(
              					"menu_name" => "Administration Menu",
              					"menu_pos" => "-1",
              					"menu_sid" => "-1"
              				));

              				$db->insert("menus", array(
              					"menu_name" => "Main Menu",
              					"menu_pos" => "1",
              					"menu_sid" => "1"
              				));

	       					$web->redirect($web->serializeUrl("setup.php?step=5"));
	       				}
	       				else {
							echo $display->adminAlert($lang->getLocale("ADMIN_E_W"), $lang->getLocale("ADMIN_E_E1"), "danger"); 
							$web->redirect($web->serializeUrl("setup.php?step=4"), 2);		
						}
	       			}
	       		}
	       		else if(intval($_GET['step']) == 5) {
	       			// Administrator account create
	       			?>
					<p class="login-box-msg"><?php echo $lang->getLocale('SETUP_S5_T1');?></p>
	       			<form method="post">

	       				<div class="form-group">
	                      <label for="l_user_name"><?php echo $lang->getLocale('SETUP_S5_L1');?></label>
	                      <input type="text" class="form-control" name="user_name" id="l_user_name" placeholder="<?php echo $lang->getLocale('SETUP_S5_L1_P');?>">
	                    </div>

	                    <div class="form-group">
	                      <label for="l_user_email"><?php echo $lang->getLocale('SETUP_S5_L2');?></label>
	                      <input type="email" class="form-control" name="user_email" id="l_user_email" placeholder="<?php echo $lang->getLocale('SETUP_S5_L2_P');?>">
	                    </div>

	                    <div class="form-group">
	                      <label for="l_user_password"><?php echo $lang->getLocale('SETUP_S5_L3');?></label>
	                      <input type="password" class="form-control" name="user_password" id="l_user_password" placeholder="<?php echo $lang->getLocale('SETUP_S5_L3_P');?>">
	                    </div>

	                    <div class="form-group">
	                      <label for="l_user_password_r"><?php echo $lang->getLocale('SETUP_S5_L4');?></label>
	                      <input type="password" class="form-control" name="user_password_r" id="l_user_password_r" placeholder="<?php echo $lang->getLocale('SETUP_S5_L4_P');?>">
	                    </div>

	       				<input type="submit" name="setConfig" class="btn btn-block btn-flat btn-primary" value="<?php echo $lang->getLocale('SETUP_S5_B1');?>">
	       			</form>
	       			<hr>
	       			<p class="text-info"><?php echo $lang->getLocale('SETUP_S5_B1_H');?></p>
	       			<?php
	       			if(@$_POST["setConfig"]) {
	       				if(isset($_POST["user_name"]) && !empty($_POST["user_name"]) && isset($_POST["user_email"]) && !empty($_POST["user_email"]) && isset($_POST["user_password"]) && !empty($_POST["user_password"]) && isset($_POST["user_password_r"]) && !empty($_POST["user_password_r"])) {

	       					if(strlen($_POST["user_name"]) > 2 && preg_match("/^[_a-zA-Z0-9-]+$/", $_POST["user_name"])) {
						  		if($_POST['user_password'] == $_POST['user_password_r']) {
						  			if(strlen($_POST['user_password']) > 5) {
							  			if(!filter_var($_POST['user_email'], FILTER_VALIDATE_EMAIL) === false) {
					  						$userSalt = $user->generateUserSalt();
					  						$rights = array(null);
											for($i = 1;$i < $user->getUserRightsCount();$i++) {
											  $rights[] = "z";
											}
											$uid = $db->insert("users", array(
												"user_name" => $content->clearUserInputAll($_POST["user_name"]),
												"user_pass" => $user->generateUserPassword($content->clearUserInputAll($_POST['user_password']), $userSalt),
												"user_salt" => $userSalt,
												"user_display_name" => $content->clearUserInputAll($_POST['user_name']),
												"user_email" => $content->clearUserInputAll($_POST['user_email']),
												"user_timestamp_register" => time(),
												"user_timestamp_login" => 0,
												"user_timestamp_active" => 0,
												"user_login_type" => "web",
												"user_rights" => 5,
												"user_rights_detail" => implode(".",$rights)
											));

											$user->loginUser($uid);
											$web->redirect($web->serializeUrl("setup.php?step=6"));
							  			}
							  			else {
						  					echo $display->adminAlert($lang->getLocale("ADMIN_E_W"), $lang->getLocale("P_REGISTER_E4"), "warning"); 
											$web->redirect($web->serializeUrl("setup.php?step=5"), 2);	
							  			}
							  		}
							  		else {
					  					echo $display->adminAlert($lang->getLocale("ADMIN_E_W"), $lang->getLocale("P_REGISTER_E8"), "warning"); 
										$web->redirect($web->serializeUrl("setup.php?step=5"), 2);	
					  				}
							  	}
							  	else {
				  					echo $display->adminAlert($lang->getLocale("ADMIN_E_W"), $lang->getLocale("P_REGISTER_E3"), "warning"); 
									$web->redirect($web->serializeUrl("setup.php?step=5"), 2);	
				  				}
		       				}
		       				else {
								echo $display->adminAlert($lang->getLocale("ADMIN_E_W"), $lang->getLocale("P_REGISTER_E2"), "warning"); 
								$web->redirect($web->serializeUrl("setup.php?step=5"), 2);		
							}
		       			}
		       			else {
							echo $display->adminAlert($lang->getLocale("ADMIN_E_W"), $lang->getLocale("ADMIN_E_E1"), "danger"); 
							$web->redirect($web->serializeUrl("setup.php?step=5"), 2);		
						}
	       			}
	       		}
	       		else if(intval($_GET['step']) == 6) {
	       			// Install complete
	       			?>
	       			<p class="login-box-msg"><?php echo $lang->getLocale('SETUP_S6_T1');?></p>
	       			<p class="text-info text-justify"><?php echo $lang->getLocale('SETUP_S6_T2');?></p>
	       			<hr>
	       			<form method="post">
	       				<input type="submit" name="setComplete" class="btn btn-block btn-flat btn-primary" value="<?php echo $lang->getLocale('SETUP_S6_B1');?>">
	       			</form>
	       			<?php
	       			if(@$_POST["setComplete"]) {
	       				// TO DO::MAIN WEB REGISTER
	       				// TO DO::CHECK UPDATES
	       				@unlink(BASEDIR."setup.php");
	       				$web->redirect($web->serializeUrl());
	       			}
	       		}
	       		?>
	       </div>
	    </div>
		<script src="admin/plugins/jQuery/jQuery-2.1.4.min.js"></script>
		<script src="admin/assets/js/bootstrap.min.js" type="text/javascript"></script>
		<script src="admin/plugins/morris/morris.min.js" type="text/javascript"></script>
		<script src="admin/assets/js/app.min.js" type="text/javascript"></script>
	</body>
</html>