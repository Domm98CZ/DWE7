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
* File: users.php
* Filepath: /admin/pages/users.php
*/
if(!defined("AUTHORIZED")) { die("Access Denied"); }
require_once DIR_INC."PageHeader.php";

if($user->isUserHasRights($user->loggedUser(), 8, "b")) {
  $web->addToTitle(" - ".$lang->getLocale("ADMIN_U_T1"));

  if(isset($_GET["user"]) && !empty($_GET["user"]) && is_numeric($_GET["user"]) && $_GET["user"] > 0 && isset($_GET["reset_avatar"])) {
    $u = $user->getUserInfo($_GET["user"]);
    if(!empty($u) && isset($u) && $u['user_id'] > 0) {
      $web->addToTitle(" - ".$user->getUserName($u['user_id'])." - ".$lang->getLocale('ADMIN_U_T4'));
      ?>
      <!-- Page Heading -->
      <section class="content-header">
        <h1><?php echo $lang->getLocale("ADMIN_U_T1");?><small><?php echo $web->getAdministrationName();?></small></h1>
        <ol class="breadcrumb">
          <li><a href="<?php echo $web->getUrl("admin/adm.php");?>"><i class="fa fa-dashboard"></i> <?php echo $web->getAdministrationName();?></a></li>
          <li><a href="<?php echo $web->getUrl("admin/adm.php?page=users");?>"><?php echo $lang->getLocale("ADMIN_U_T1");?></a></li>
          <li><a href="<?php echo $web->getUrl("admin/adm.php?page=users&user=".$u['user_id']);?>"><?php echo $user->getUserName($u['user_id']);?></a></li>
          <li class="active"><?php echo $lang->getLocale('ADMIN_U_T4');?></li>
        </ol>
      </section>

      <section class="content">
        <div class="row">
          <div class="col-xs-12 col-md-12">

            <div class="box box-warning">
              <div class="box-header">
                <h3 class="box-title"><?php echo $lang->getLocale("ADMIN_U_T4")." - ".$user->getUserName($u['user_id']);?></h3>
              </div>

              <div class="box-body">
                <form method="post">
                  <p><?php echo sprintf($lang->getLocale('ADMIN_U_T5'), $user->getUserName($u['user_id']));?></p> 
                  <div class="form-group">
                    <label for="l_message_text"><?php echo $lang->getLocale('ADMIN_U_L12');?></label>
                    <?php echo $content->editableInput("message_text");?>
                  </div>
                  <input type="submit" name="resetAvatar" value="<?php echo $lang->getLocale('ADMIN_U_B2');?>" class="btn btn-warning">
                  <a href="<?php echo $web->getUrl("admin/adm.php?page=users&user=".$u['user_id']);?>" class="btn btn-primary"><?php echo $lang->getLocale('ADMIN_U_B6');?></a>
                </form>
              </div>
            </div>
  					
  			<?php
  			if(@$_POST["resetAvatar"]) {
  				if(isset($_POST["message_text"]) && !empty($_POST["message_text"])) {
  					$path = DIR_UPLOADS."avatars/".$user->getUserSettings($u['user_id'], 'avatar_file');
  					if(is_file($path) && file_exists($path)) unlink($path);
  					$user->deleteUserSettings($u['user_id'], 'avatar');
  					$user->deleteUserSettings($u['user_id'], 'avatar_file'); 

  					$cc = is_text_ok($_POST['message_text']);
  					$m_content = $content->clearUserInput($_POST['message_text']); 

  					if(strlen($m_content) > 0) {
  						$web->createMessage(array(
  								"message_subject" => $lang->getLocale("ADMIN_U_T4"),
  								"message_text" => $m_content,
  								"message_type" => "1",
  								"user_id_s" => $user->loggedUser(),
  								"user_id_r" => $u['user_id'],
  								"message_timestamp_send" => time(),
  								"message_timestamp_showed" => 0
  							));

  						echo $display->adminAlert($lang->getLocale("ADMIN_E_S"), $lang->getLocale("ADMIN_U_OK1"), "success"); 
  						$web->redirect($web->getUrl("admin/adm.php?page=users&user=".$u['user_id']), 2);
  					}
  				}
  				else {
  					echo $display->adminAlert($lang->getLocale("ADMIN_E_W"), $lang->getLocale("ADMIN_E_E1"), "danger"); 
  					$web->redirect($web->getUrl("admin/adm.php?page=users&user=".$u['user_id']."&reset_avatar"), 2);
  				}
  			}
  			?>
          </div>
        </div>
      </section>
      <?php
    }
    else $web->redirect($web->getUrl("admin/adm.php?page=users"));
  }
  else if(isset($_GET["user"]) && !empty($_GET["user"]) && is_numeric($_GET["user"]) && $_GET["user"] > 0 && isset($_GET["block"])) {
    $u = $user->getUserInfo($_GET["user"]);
    if(!empty($u) && isset($u) && $u['user_id'] > 0) {
      $u = $user->getUserInfo($_GET["user"]);
      $web->addToTitle(" - ".$user->getUserName($u['user_id'])." - ".$lang->getLocale('ADMIN_U_T6'));
      ?>
      <!-- Page Heading -->
      <section class="content-header">
        <h1><?php echo $lang->getLocale("ADMIN_U_T1");?><small><?php echo $web->getAdministrationName();?></small></h1>
        <ol class="breadcrumb">
          <li><a href="<?php echo $web->getUrl("admin/adm.php");?>"><i class="fa fa-dashboard"></i> <?php echo $web->getAdministrationName();?></a></li>
          <li><a href="<?php echo $web->getUrl("admin/adm.php?page=users");?>"><?php echo $lang->getLocale("ADMIN_U_T1");?></a></li>
          <li><a href="<?php echo $web->getUrl("admin/adm.php?page=users&user=".$u['user_id']);?>"><?php echo $user->getUserName($u['user_id']);?></a></li>
          <li class="active"><?php echo $lang->getLocale('ADMIN_U_T6');?></li>
        </ol>
      </section>

      <section class="content">
        <div class="row">
          <div class="col-xs-12 col-md-12">

            <div class="box box-danger">
              <div class="box-header">
                <h3 class="box-title"><?php echo $lang->getLocale("ADMIN_U_T6")." - ".$user->getUserName($u['user_id']);?></h3>
              </div>

              <div class="box-body">
                <form method="post">
                  <p><?php echo sprintf($lang->getLocale('ADMIN_U_T7'), $user->getUserName($u['user_id']));?></p> 
                  <div class="form-group">
                    <label for="l_message_text"><?php echo $lang->getLocale('ADMIN_U_L14');?></label>
                    <?php echo $content->editableInput("message_text");?>
                  </div>
                  <input type="submit" name="blockUser" value="<?php echo $lang->getLocale('ADMIN_U_B8');?>" class="btn btn-danger">
                  <a href="<?php echo $web->getUrl("admin/adm.php?page=users&user=".$u['user_id']);?>" class="btn btn-primary"><?php echo $lang->getLocale('ADMIN_U_B9');?></a>
                </form>
              </div>
            </div>
  					
  			<?php
  			if(@$_POST["blockUser"]) {
  				if(isset($_POST["message_text"]) && !empty($_POST["message_text"])) {
  					$user->setUserRole($u['user_id'], -1);
  					$web->sendmail($u['user_email'], $lang->getLocale("ADMIN_U_T6"), sprintf(
  						$web->buildMail('U_USER_BLOCK'), 
  						$user->getUserName($u['user_id']),
  						$user->getUserName($u['user_id']),
  						$web->getPageTitle(),
  						$content->clearUserInput($_POST['message_text'])	
  					));
  					echo $display->adminAlert($lang->getLocale("ADMIN_E_S"), $lang->getLocale("ADMIN_U_OK2"), "success"); 
  					$web->redirect($web->getUrl("admin/adm.php?page=users&user=".$u['user_id']), 2);
  				}
  				else {
  					echo $display->adminAlert($lang->getLocale("ADMIN_E_W"), $lang->getLocale("ADMIN_E_E1"), "danger"); 
  					$web->redirect($web->getUrl("admin/adm.php?page=users&user=".$u['user_id']."&block"), 2);
  				}
  			}
  			?>
          </div>
        </div>
      </section>
      <?php
    }
    else $web->redirect($web->getUrl("admin/adm.php?page=users"));
  }
  else if(isset($_GET["user"]) && !empty($_GET["user"]) && is_numeric($_GET["user"]) && $_GET["user"] > 0 && isset($_GET["delete"])) {
    $u = $user->getUserInfo($_GET["user"]);
    if(!empty($u) && isset($u) && $u['user_id'] > 0) {
      $web->addToTitle(" - ".$user->getUserName($u['user_id'])." - ".$lang->getLocale('ADMIN_U_T8'));
      ?>
      <!-- Page Heading -->
      <section class="content-header">
        <h1><?php echo $lang->getLocale("ADMIN_U_T1");?><small><?php echo $web->getAdministrationName();?></small></h1>
        <ol class="breadcrumb">
          <li><a href="<?php echo $web->getUrl("admin/adm.php");?>"><i class="fa fa-dashboard"></i> <?php echo $web->getAdministrationName();?></a></li>
          <li><a href="<?php echo $web->getUrl("admin/adm.php?page=users");?>"><?php echo $lang->getLocale("ADMIN_U_T1");?></a></li>
          <li><a href="<?php echo $web->getUrl("admin/adm.php?page=users&user=".$u['user_id']);?>"><?php echo $user->getUserName($u['user_id']);?></a></li>
          <li class="active"><?php echo $lang->getLocale('ADMIN_U_T8');?></li>
        </ol>
      </section>

      <section class="content">
        <div class="row">
          <div class="col-xs-12 col-md-12">

            <div class="box box-danger">
              <div class="box-header">
                <h3 class="box-title"><?php echo $lang->getLocale("ADMIN_U_T8")." - ".$user->getUserName($u['user_id']);?></h3>
              </div>

              <div class="box-body">
                <form method="post">
                  <p><?php echo sprintf($lang->getLocale('ADMIN_U_T9'), $user->getUserName($u['user_id']));?></p> 
                  <div class="form-group">
                    <label for="l_message_text"><?php echo $lang->getLocale('ADMIN_U_L15');?></label>
                    <?php echo $content->editableInput("message_text");?>
                  </div>
                  <input type="submit" name="deleteUser" value="<?php echo $lang->getLocale('ADMIN_U_B10');?>" class="btn btn-danger">
                  <a href="<?php echo $web->getUrl("admin/adm.php?page=users&user=".$u['user_id']);?>" class="btn btn-primary"><?php echo $lang->getLocale('ADMIN_U_B11');?></a>
                </form>
              
              </div>
            </div>

            <?php
            if(@$_POST["deleteUser"]) {
              if(isset($_POST["message_text"]) && !empty($_POST["message_text"])) {
                  $user->deleteUser($u['user_id']);
                 	$web->sendmail($u['user_email'], $lang->getLocale("ADMIN_U_T8"), sprintf(
          					$web->buildMail('U_USER_REMOVE'), 
          					$user->getUserName($u['user_id']),
          					$user->getUserName($u['user_id']),
          					$web->getPageTitle(),
          					$content->clearUserInput($_POST['message_text'])	
          				));
          				echo $display->adminAlert($lang->getLocale("ADMIN_E_S"), $lang->getLocale("ADMIN_U_OK3"), "success"); 
          				$web->redirect($web->getUrl("admin/adm.php?page=users"), 2);
              }
              else {
            		echo $display->adminAlert($lang->getLocale("ADMIN_E_W"), $lang->getLocale("ADMIN_E_E1"), "danger"); 
  				      $web->redirect($web->getUrl("admin/adm.php?page=users&user=".$u['user_id']."&delete"), 2);
              }
            }
            ?>
          </div>
        </div>
      </section>
      <?php
    }
    else $web->redirect($web->getUrl("admin/adm.php?page=users"));
  }
  else if(isset($_GET["user"]) && !empty($_GET["user"]) && is_numeric($_GET["user"]) && $_GET["user"] > 0) {
    $u = $user->getUserInfo($_GET["user"]);
    if(!empty($u) && isset($u) && $u['user_id'] > 0) {
      $web->addToTitle(" - ".$user->getUserName($u['user_id']));
      
      ?>
      <!-- Page Heading -->
      <section class="content-header">
        <h1><?php echo $lang->getLocale("ADMIN_U_T1");?><small><?php echo $web->getAdministrationName();?></small></h1>
        <ol class="breadcrumb">
          <li><a href="<?php echo $web->getUrl("admin/adm.php");?>"><i class="fa fa-dashboard"></i> <?php echo $web->getAdministrationName();?></a></li>
          <li><a href="<?php echo $web->getUrl("admin/adm.php?page=users");?>"><?php echo $lang->getLocale("ADMIN_U_T1");?></a></li>
          <li class="active"><?php echo $user->getUserName($u['user_id']);?></li>
        </ol>
      </section>
      <?php

      if($user->getUserInfo($user->loggedUser(), "user_rights") >= $u["user_rights"] || $user->loggedUser() == $u["user_id"]) {
      ?>
      <section class="content">
        <div class="row">
          <div class="col-xs-12 col-md-12">

            <div class="box box-primary">
              <div class="box-header">
                <h3 class="box-title"><?php echo $lang->getLocale("ADMIN_U_T3")." - ".$user->getUserName($u['user_id']);?></h3>
                <div class="box-tools pull-right">
                  <a href="<?php echo $web->getUrl("admin/adm.php?page=users");?>" class="btn btn-xs btn-warning"><?php echo $lang->getLocale("ADMIN_U_B1");?></a>
                </div>
              </div>

              <form method="post">
                <div class="box-body">
                
                  <div class="form-group">
                    <label for="l_user_name"><?php echo $lang->getLocale('ADMIN_U_L6');?></label>
                    <input type="text" name="user_name" class="form-control" id="l_user_name" value="<?php echo htmlentities($u['user_name']);?>" readonly disabled>
                  </div>
                  
                  <div class="form-group">
                    <label for="l_user_display_name"><?php echo $lang->getLocale('ADMIN_U_L7');?></label>
                    <input type="text" name="user_display_name" class="form-control" id="l_user_display_name" value="<?php echo $user->getUserName($u['user_id']);?>">
                  </div>

                  <div class="form-group">
                    <label for="l_user_email"><?php echo $lang->getLocale('ADMIN_U_L9');?></label>
                    <input type="text" name="user_email" class="form-control" id="l_user_email" value="<?php echo htmlentities($u['user_email']);?>">
                  </div>
                  
                  <div class="form-group">
                    <label for="l_user_rights"><?php echo $lang->getLocale('ADMIN_U_L13');?></label>
                    <?php
                    $uRT = $user->getUserRoleTable();
                    if(count($uRT) > 0) {
                      ?><select id="l_user_rights" name="user_rights" class="form-control"><?php
                      for($i = -1;$i < count($uRT)-1;$i++) {
                        echo "<option value='".$i."'".(($i == $u['user_rights']) ? " selected" : null).">".$lang->getLocale("USER_ROLE_".$uRT[$i])."</option>";
                      }
                      ?></select><?php
                    }
                    ?>
                  </div>
                  
                  <div class="form-group">
                    <label for="l_primary_group"><?php echo $lang->getLocale('ADMIN_U_L11');?></label>
                    <?php
                    $ug = $user->getUserGroups($u['user_id']);
                    $usrg = $user->getUserSettings($u['user_id'], "group");
                    if(count($ug) > 1) {
                      ?><select id="l_primary_group" name="user_primary_group" class="form-control"><?php
                      for($i = 0;$i < count($ug);$i ++) {
                        echo "<option value='".$ug[$i]."'".(($ug[$i] == $usrg) ? " selected" : null).">".$user->getUserGroupName($ug[$i])."</option>";
                      }
                      ?></select><?php
                    }
                    else if(count($ug) == 1) {
                      ?><p class="text-muted"><?php echo sprintf($lang->getLocale('ADMIN_U_L16'), $user->getUserGroupName($ug[0]));?></p><?php
                    }
                    else {
                      ?><p class="text-muted"><?php echo $lang->getLocale('ADMIN_U_L17');?></p><?php
                    }
                    ?>
                  </div>
                  
                  <div class="form-group">
                    <label for="user_desc">
                      <?php 
                      echo $lang->getLocale('ADMIN_U_L10');
                      if($user->getUserSettings($u['user_id'], 'desc') != true) {
                        echo " <i>(".$lang->getLocale('P_SETTINGS_L11-1').")</i>";
                      }
                      ?>
                    </label>

                    <?php
                    echo $content->editableInput("user_desc", $lang->getLocale('P_SETTINGS_L11-1'), 
                      (($user->getUserSettings($u['user_id'], 'desc') == true) ? $user->getUserSettings($u['user_id'], 'desc') : null)
                    );
                    ?>
                  </div>            
                </div>
                <div class="box-footer">
                  <input type="submit" name="userSave" class="btn btn-success" value="<?php echo $lang->getLocale('ADMIN_U_B7');?>">
                  <a href="<?php echo $links->getUserLink($u['user_id']);?>" target="_blank" class="btn btn-primary"><?php echo $lang->getLocale('ADMIN_U_B3');?></a>
                  <a href="<?php echo $web->getUrl("admin/adm.php?page=users&user=".$u['user_id']."&reset_avatar");?>" class="btn btn-warning"><?php echo $lang->getLocale('ADMIN_U_B2');?></a>
          				<?php
          				if($u['user_rights'] != -1) {
          					?><a href="<?php echo $web->getUrl("admin/adm.php?page=users&user=".$u['user_id']."&block");?>" class="btn btn-danger"><?php echo $lang->getLocale('ADMIN_U_B4');?></a><?php	
          				}
          				else {
          					?><a class="btn btn-danger disabled"><?php echo $lang->getLocale('ADMIN_U_B4');?></a><?php
          				}
          				?>
                  <a href="<?php echo $web->getUrl("admin/adm.php?page=users&user=".$u['user_id']."&delete");?>" class="btn btn-danger"><?php echo $lang->getLocale('ADMIN_U_B5');?></a>
                </div>
              </form>
            </div>
  					<?php
  					if(@$_POST["userSave"]) {
  						if(isset($_POST["user_display_name"]) && isset($_POST["user_email"]) && isset($_POST["user_rights"]) &&
  							!empty($_POST["user_display_name"]) && !empty($_POST["user_email"]) && !empty($_POST["user_rights"])) {
  							
                if(filter_var($_POST['user_email'], FILTER_VALIDATE_EMAIL) !== false) {
                  if(!$user->isEmailUsed($_POST["user_email"])) {
                    $user->updateUserInfo($u['user_id'], array("user_email" => $display->displaytext($_POST["user_email"])));
                  }
                }

								if(strlen($_POST["user_display_name"]) > 2 && preg_match("/^[_a-zA-Z0-9-]+$/", $_POST["user_display_name"])) {
									
									if($user->getUserInfo($user->loggedUser(), 'user_display_name') != $_POST['user_display_name']) {
										$uid = $user->isUserDisplayNameUsed($_POST['user_display_name']);
										if($uid > 0 && $uid == $u['user_id'] || $uid <= 0) { 
											$user->updateUserInfo($u['user_id'], array("user_display_name" => $display->displaytext($_POST['user_display_name'])));
										}
									}

								}
									
								if($_POST['user_rights'] != $user->getUserRole($u['user_id'])) {
									if(array_key_exists($_POST['user_rights'], $user->getUserRoleTable())) {
                    if($_POST['user_rights'] <= $user->getUserRole($user->loggedUser()) && $user->getUserRole($user->loggedUser()) >= 4) {
                      $user->setUserRole($u['user_id'], $_POST['user_rights']);
                    }
									}
								}
								
								if(!empty($_POST['user_desc']) && is_text_ok($_POST['user_desc'])) {
									$user->updateUserSettings($u['user_id'], 'desc', $content->clearUserInput($_POST['user_desc']));
								}

								if(isset($_POST["user_primary_group"]) && !empty($_POST["user_primary_group"])) {
									if($_POST["user_primary_group"] != $user->getUserSettings($u['user_id'], "group") && $user->getUserGroupMembership($u['user_id'], $_POST["user_primary_group"])) {
										$user->updateUserSettings($u['user_id'], 'group', $_POST["user_primary_group"]);
									}
								}
								
								echo $display->adminAlert($lang->getLocale("ADMIN_E_S"), $lang->getLocale("ADMIN_U_OK4"), "success"); 
								$web->redirect($web->getUrl("admin/adm.php?page=users&user=".$u['user_id']), 2);
  						}
  						else {
  							echo $display->adminAlert($lang->getLocale("ADMIN_E_W"), $lang->getLocale("ADMIN_E_E1"), "danger"); 
  							$web->redirect($web->getUrl("admin/adm.php?page=users&user=".$u['user_id']), 2);
  						}
  					} 
  					?>
            
            
            <?php if($user->isUserHasRights($user->loggedUser(), 19, "z")) { ?>

            <div class="box box-default">
  						<div class="box-header">
  							<h3 class="box-title"><?php echo $lang->getLocale("ADMIN_R_T1")." - ".$user->getUserName($u['user_id']);?></h3>
  						</div>

              <form method="post">
  							<div class="box-body">
  								
  								<div class="form-group">
  									<div class="row">	
  										<label class="col-md-4 control-label" for="r_rights_0"><?php echo $lang->getLocale('ADMIN_R_L0');?></label>
  										<div class="col-md-8"> 
  											<label for="radios-0-1" class="radio-inline">
  												<input type="radio" name="r_rights_0" id="radios-0-1" value="1" disabled>
  												<?php echo $lang->getLocale('ADMIN_R_R:NONE');?>
  											</label>
  											<label for="radios-0-2" class="radio-inline">
  												<input type="radio" name="r_rights_0" id="radios-0-2" value="2" disabled>
  												<?php echo $lang->getLocale('ADMIN_R_R:CUSTOM');?>

  											</label>
  											<label for="radios-0-3" class="radio-inline">
  												<input type="radio" name="r_rights_0" id="radios-0-3" value="3" disabled>
  												<?php echo $lang->getLocale('ADMIN_R_R:FULL');?>
  											</label>
  											<p class="text-muted">
  												<?php echo $lang->getLocale('ADMIN_R_T2');?>
  											</p>
  										</div>
  									</div>
  								</div>
  								<hr>
  								
  								<?php
  								$rt = $user->getUserRightsTable();

  								for($i = 1;$i < $user->getUserRightsCount();$i++) {
  									$x = $user->getUserRights($u['user_id'], $i);
  									?>
  									<div class="form-group">
  										<div class="row">				
  											<label class="col-md-4 control-label" for="r_rights_<?php echo $i;?>"><?php echo $lang->getLocale('ADMIN_R_'.$rt[$i]);?></label>
  											<div class="col-md-8"> 
  												<label for="radios-<?php echo $i;?>-1" class="radio-inline">
  													<input type="radio" name="r_rights_<?php echo $i;?>" id="radios-<?php echo $i;?>-1" value="1"<?php echo (($x == 'a' || $x == null) ? " checked='checked'" : null) ?>>
  													<?php echo $lang->getLocale('ADMIN_R_R:NONE');?>
  												</label>
  												<label for="radios-<?php echo $i;?>-2" class="radio-inline">
  													<input type="radio" name="r_rights_<?php echo $i;?>" id="radios-<?php echo $i;?>-2" value="2" <?php echo (($x != 'a' && $x != 'z' && $x != null) ? " checked='checked'" : null) ?>>
  													<select name="r_rights_<?php echo $i;?>_c">
  													<?php
  													$a = range("b", "y");
  													foreach($a as $char) {
  														echo "<option value='".$char."'".(($char == $x) ? " selected" : null).">".$char."</option>";
  													}
  													?>
  													</select>

  												</label>
  												<label for="radios-<?php echo $i;?>-3" class="radio-inline">
  													<input type="radio" name="r_rights_<?php echo $i;?>" id="radios-<?php echo $i;?>-3" value="3"<?php echo (($x == 'z') ? " checked='checked'" : null) ?>>
  													<?php echo $lang->getLocale('ADMIN_R_R:FULL');?>
  												</label>
  											</div>
  										</div>
  									</div>
  									<?php
  								}
  								?>
  							</div>
                <div class="box-footer">
                  <input type="submit" class="btn btn-success" value="<?php echo $lang->getLocale('ADMIN_R_B1');?>" name="saveRights">
                  <input type="submit" class="btn btn-primary" value="<?php echo $lang->getLocale('ADMIN_R_B2');?>" name="defaultRights">
                </div>
              </form>
  					</div>
            
            <?php
            if(@$_POST["defaultRights"]) {
              $user->setUserDefaultRights($u['user_id']);
              echo $display->adminAlert($lang->getLocale("ADMIN_E_W"), $lang->getLocale("ADMIN_R_OK"), "success"); 
              $web->redirect($web->getUrl("admin/adm.php?page=users&user=".$u['user_id']), 2);
            }
            
            if(@$_POST["saveRights"]) {
              $rights = array(null);
              for($i = 1;$i < $user->getUserRightsCount();$i++) {
                if(isset($_POST["r_rights_".$i.""]) && !empty($_POST["r_rights_".$i.""])) {
                  if($_POST["r_rights_".$i.""] == 1) $rights[] = "a";
                  else if($_POST["r_rights_".$i.""] == 3) $rights[] = "z";
                  else {
                    if(isset($_POST["r_rights_".$i."_c"]) && !empty($_POST["r_rights_".$i."_c"])) $rights[] = $_POST["r_rights_".$i."_c"];
                    else $rights[] = "a";
                  }
                }
              }
              
              $user->updateUserInfo($u['user_id'], array("user_rights_detail" => implode(".",$rights)));
        			echo $display->adminAlert($lang->getLocale("ADMIN_E_W"), $lang->getLocale("ADMIN_R_OK"), "success"); 
              $web->redirect($web->getUrl("admin/adm.php?page=users&user=".$u['user_id']), 2);
            }
            ?>

            <?php } ?>
            
          </div>
        </div>
      </section>
      <?php
      }
      else {
        ?>
        <section class="content">
          <div class="row">
            <div class="col-xs-12 col-md-12">

              <div class="box box-danger">
                <div class="box-header">
                  <h3 class="box-title"><?php echo $lang->getLocale("ADMIN_U_T10");?></h3>
                </div>
                <div class="box-body">
                  <p><?php echo $lang->getLocale("ADMIN_U_T11");?></p>
                  <a href="<?php echo $web->getUrl("admin/adm.php?page=users");?>" class='btn btn-xs btn-danger'><?php echo $lang->getLocale("ADMIN_U_B12");?></a>
                </div>
              </div>
            </div>
          </div>
        </section>
        <?php
      }
    }
    else $web->redirect($web->getUrl("admin/adm.php?page=users"));
  }
  else {
    ?>
    <!-- Page Heading -->
    <section class="content-header">
      <h1><?php echo $lang->getLocale("ADMIN_U_T1");?><small><?php echo $web->getAdministrationName();?></small></h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo $web->getUrl("admin/adm.php");?>"><i class="fa fa-dashboard"></i> <?php echo $web->getAdministrationName();?></a></li>
        <li class="active"><?php echo $lang->getLocale("ADMIN_U_T1");?></li>
      </ol>
    </section>

    <section class="content">
      <div class="row">
        <div class="col-xs-12 col-md-12">

          <div class="box box-primary">
            <div class="box-header">
              <h3 class="box-title"><?php echo $lang->getLocale("ADMIN_U_T2");?></h3>
            </div>
            <div class="box-body no-padding">
              <?php echo $content->dataTable("usersTable", "table");?>
              <thead>
                  <tr>
                    <th width="5%">#</th>
                    <th><?php echo $lang->getLocale("ADMIN_U_L1");?></th>
                    <th><?php echo $lang->getLocale("ADMIN_U_L2");?></th>
                    <th><?php echo $lang->getLocale("ADMIN_U_L3");?></th>
                    <th><?php echo $lang->getLocale("ADMIN_U_L4");?></th>
                  </tr>
                </thead>
                <tbody>
                <?php
                $u = $user->getAllUsers();
                if(isset($u) && count($u) > 0) {
                  for($i = 0;$i < count($u);$i ++) {
                    ?>
                    <tr>
                      <td><?php echo $u[$i]['user_id'];?></td>
                      <td><a href="<?php echo $web->getUrl("admin/adm.php?page=users&user=".$u[$i]['user_id']);?>"><?php echo $user->getUserName($u[$i]['user_id']);?> <i>(<?php echo htmlentities($u[$i]['user_name']);?>)</i></a></td>
                      <td><?php 
  										$group_id = -1;
                      $g = $user->getUserSettings($u[$i]['user_id'], 'group');
                      if(!empty($g)) $group_id = $g;
  										else {
  											$user_groups = $user->getUserInfo($u[$i]['user_id'], "user_groups");
  											$user_group = explode("#", $user_groups);
  											if(!empty($user_group[1])) $group_id = $user_group[1];
  										}
  										
  										if($group_id > 0 && $user->getUserGroupMembership($u[$i]['user_id'], $group_id)) echo $display->render_userGroup_label($group_id);
                      else echo "<i>".$lang->getLocale('ADMIN_U_L5')."</i>";
                      ?></td>
                      <td><?php echo $web->showTime($u[$i]['user_timestamp_register']);?></td>
                      <td><?php echo $display->showUserOnlineStatus($u[$i]['user_id']);?></td>
                    </tr>
                    <?php
                  }
                }
                ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </section>
    <?php
  }
}
else {
  $web->redirect($web->getUrl("admin/adm.php"));
}
?>