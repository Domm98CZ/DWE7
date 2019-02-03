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
* File: usergroups.php
* Filepath: /admin/pages/usergroups.php
*/
if(!defined("AUTHORIZED")) { die("Access Denied"); }
require_once DIR_INC."PageHeader.php";

if($user->isUserHasRights($user->loggedUser(), 9, "b")) {
	$web->addToTitle(" - ".$lang->getLocale("ADMIN_UG_T1"));

	if(isset($_GET["usergroup"]) && !empty($_GET["usergroup"]) && is_numeric($_GET["usergroup"]) && $_GET["usergroup"] > 0 && isset($_GET["delete"])) {
		$ug = $user->getUserGroup($_GET["usergroup"]);
		if(isset($ug) && !empty($ug) && $ug['usergroup_id'] > 0) {
			$web->addToTitle(" - ".$lang->getLocale("ADMIN_UG_T6")." - ".htmlentities($ug['usergroup_name']));
			?>
			<!-- Page Heading -->
			<section class="content-header">
			  <h1><?php echo $lang->getLocale("ADMIN_UG_T1");?><small><?php echo $web->getAdministrationName();?></small></h1>
			  <ol class="breadcrumb">
			    <li><a href="<?php echo $web->getUrl("admin/adm.php");?>"><i class="fa fa-dashboard"></i> <?php echo $web->getAdministrationName();?></a></li>
			    <li><a href="<?php echo $web->getUrl("admin/adm.php?page=usergroups");?>"><?php echo $lang->getLocale("ADMIN_UG_T6");?></a></li>
			    <li class="active"><?php echo htmlentities($ug['usergroup_name']);?></li>
			  </ol>
			</section>

			<section class="content">
	        <div class="row">
	          <div class="col-xs-12 col-md-12">

	            <div class="box box-danger">
	              <div class="box-header">
	                <h3 class="box-title"><?php echo $lang->getLocale("ADMIN_UG_T6")." - ".htmlentities($ug['usergroup_name']);?></h3>
	              </div>
	              
	              <div class="box-body">

	                <p><?php echo sprintf($lang->getLocale('ADMIN_UG_T7'), htmlentities($ug['usergroup_name']));?></p>

	                <form method="post">
	                  <input type="submit" name="deletePost" value="<?php echo $lang->getLocale('ADMIN_UG_B8');?>" class="btn btn-danger">
	                  <a href="<?php echo $web->getUrl("admin/adm.php?page=usergroups&usergroup=".$ug['usergroup_id']);?>" class="btn btn-primary"><?php echo $lang->getLocale('ADMIN_UG_B9');?></a>
	                </form>

	              </div>
	            </div>

	            <?php
	            if(@$_POST["deletePost"]) {
	              $user->deleteUserGroup($ug['usergroup_id']);
	              echo $display->adminAlert($lang->getLocale("ADMIN_E_S"), $lang->getLocale("ADMIN_UG_OK1"), "success"); 
	              $web->redirect($web->getUrl("admin/adm.php?page=usergroups"), 2);
	            }
	            ?>

	          </div>
	        </div>
	    </section>
	    <?php
		}
		else $web->redirect($web->getUrl("admin/adm.php?page=usergroups"));
	}
	else if(isset($_GET["usergroup"]) && !empty($_GET["usergroup"]) && is_numeric($_GET["usergroup"]) && $_GET["usergroup"] > 0 && isset($_GET["remove"]) &&
		isset($_GET["user"]) && !empty($_GET["user"]) && is_numeric($_GET["user"]) && $_GET["user"] > 0) {
		$ug = $user->getUserGroup($_GET["usergroup"]);
		if(isset($ug) && !empty($ug) && $ug['usergroup_id'] > 0) {
			$u = $user->getUserInfo($_GET["user"]); 
			if(isset($u) && !empty($u) && $u['user_id'] > 0) {
				$user->removeUserFromUserGroup($u['user_id'], $ug['usergroup_id']);
				$web->redirect($web->getUrl("admin/adm.php?page=usergroups&usergroup=".$ug['usergroup_id']));
			}
			else $web->redirect($web->getUrl("admin/adm.php?page=usergroups&usergroup=".$ug['usergroup_id']));
		}
		else $web->redirect($web->getUrl("admin/adm.php?page=usergroups"));
	}
	else if(isset($_GET["usergroup"]) && !empty($_GET["usergroup"]) && is_numeric($_GET["usergroup"]) && $_GET["usergroup"] > 0 && isset($_GET["add"])) {
		$ug = $user->getUserGroup($_GET["usergroup"]);
		if(isset($ug) && !empty($ug) && $ug['usergroup_id'] > 0) {
				$web->addToTitle(" - ".$lang->getLocale("ADMIN_UG_T8")." - ".htmlentities($ug['usergroup_name']));
				?>
				<!-- Page Heading -->
				<section class="content-header">
				  <h1><?php echo $lang->getLocale("ADMIN_UG_T1");?><small><?php echo $web->getAdministrationName();?></small></h1>
				  <ol class="breadcrumb">
				    <li><a href="<?php echo $web->getUrl("admin/adm.php");?>"><i class="fa fa-dashboard"></i> <?php echo $web->getAdministrationName();?></a></li>
				    <li><a href="<?php echo $web->getUrl("admin/adm.php?page=usergroups");?>"><?php echo $lang->getLocale("ADMIN_UG_T1");?></a></li>
				    <li><a href="<?php echo $web->getUrl("admin/adm.php?page=usergroups&usergroup=".$ug['usergroup_id']);?>"><?php echo htmlentities($ug['usergroup_name']);?></a></li>
				    <li class="active"><?php echo $lang->getLocale("ADMIN_UG_T8");?></li>
				  </ol>
				</section>

				<section class="content">
	        <div class="row">
	          <div class="col-xs-12 col-md-12">

	            <div class="box box-success">
	              <div class="box-header">
	                <h3 class="box-title"><?php echo $lang->getLocale("ADMIN_UG_T8")." - ".htmlentities($ug['usergroup_name']);?></h3>
	              </div>
	              
	              <div class="box-body">

	                <form method="post">

	                	<div class="form-group">
		                  <label for="l_user_name"><?php echo $lang->getLocale('ADMIN_UG_L12');?></label>
		                  <input type="text" name="user_name" class="form-control" id="l_user_name" placeholder="<?php echo $lang->getLocale('ADMIN_UG_L12-L');?>">
		               </div>

	                  <input type="submit" name="addMember" value="<?php echo $lang->getLocale('ADMIN_UG_B10');?>" class="btn btn-success">
	                  <a href="<?php echo $web->getUrl("admin/adm.php?page=usergroups&usergroup=".$ug['usergroup_id']);?>" class="btn btn-primary"><?php echo $lang->getLocale('ADMIN_UG_B11');?></a>
	                </form>

	              </div>
	            </div>

	            <?php
	            if(@$_POST["addMember"]) {
	              if(isset($_POST["user_name"]) && !empty($_POST["user_name"])) {
	              	$name = $content->clearUserInputAll($_POST["user_name"]);
	              	$u = $user->isUserExists($name);
	              	$user_id = 0;
	              	if(isset($u) && !empty($u) && $u > 0) {
	              		$user_id = $u;
	              	}
	              	else {
	              		$u = $user->isUserDisplayNameUsed($name);
	              		if(isset($u) && !empty($u) && $u > 0) {
	              			$user_id = $u;
	              		}
	              	}

	              	if($user_id > 0) {
	              		echo $display->adminAlert($lang->getLocale("ADMIN_E_S"), $lang->getLocale("ADMIN_UG_OK2"), "success"); 
	              		$user->addUserIntoUserGroup($user_id, $ug['usergroup_id']);
	              		$web->redirect($web->getUrl("admin/adm.php?page=usergroups&usergroup=".$ug['usergroup_id']), 2);
	              	}
	              	else {
	              		echo $display->adminAlert($lang->getLocale("ADMIN_E_W"), $lang->getLocale("ADMIN_UG_E1"), "warning"); 
	              		$web->redirect($web->getUrl("admin/adm.php?page=usergroups&usergroup=".$ug['usergroup_id']."&add"), 2);
	              	}
	              }
	            }
	            ?>
							
	          </div>
	        </div>
	    </section>
	    <?php
		}
		else $web->redirect($web->getUrl("admin/adm.php?page=usergroups"));
	}
	else if(isset($_GET["usergroup"]) && !empty($_GET["usergroup"]) && is_numeric($_GET["usergroup"]) && $_GET["usergroup"] > 0) {
		$ug = $user->getUserGroup($_GET["usergroup"]);
		if(isset($ug) && !empty($ug) && $ug['usergroup_id'] > 0) {
			$web->addToTitle(" - ".htmlentities($ug['usergroup_name']));
			$u = $user->getUsersByUserGroupID($ug['usergroup_id']);
			?>
			<!-- Page Heading -->
			<section class="content-header">
			  <h1><?php echo $lang->getLocale("ADMIN_UG_T1");?><small><?php echo $web->getAdministrationName();?></small></h1>
			  <ol class="breadcrumb">
			    <li><a href="<?php echo $web->getUrl("admin/adm.php");?>"><i class="fa fa-dashboard"></i> <?php echo $web->getAdministrationName();?></a></li>
			    <li><a href="<?php echo $web->getUrl("admin/adm.php?page=usergroups");?>"><?php echo $lang->getLocale("ADMIN_UG_T1");?></a></li>
			    <li class="active"><?php echo htmlentities($ug['usergroup_name']);?></li>
			  </ol>
			</section>

			<section class="content">
		        <div class="row">
		         	<div class="col-xs-12 col-md-9">
		         	
		         		<?php
		         		if(empty($ug['usergroup_link']) || !isset($ug['usergroup_link'])) {
	         				echo $display->adminAlert($lang->getLocale("ADMIN_E_W"), $lang->getLocale("ADMIN_UG_T9"), "warning"); 
		         		}
		         		?>

		         		<div class="box box-primary">
			              <div class="box-header">
			                <h3 class="box-title"><?php echo sprintf($lang->getLocale("ADMIN_UG_T4"), htmlentities($ug['usergroup_name']));?></h3>
			                <div class="box-tools pull-right">
								<a href="<?php echo $web->getUrl("admin/adm.php?page=usergroups");?>" class="btn btn-xs btn-warning"><?php echo $lang->getLocale("ADMIN_UG_B2");?></a>
							</div>
			              </div>

			              <form method="post">
				              <div class="box-body">

				              		<div class="form-group">
					                  <label for="l_usergroup_name"><?php echo $lang->getLocale('ADMIN_UG_L6');?></label>
					                  <input type="text" name="usergroup_name" class="form-control" id="l_usergroup_name" value="<?php echo htmlentities($ug['usergroup_name']);?>">
					                </div>

					                <div class="form-group">
					                  <label for="l_usergroup_label"><?php echo $lang->getLocale('ADMIN_UG_L7');?></label>
					                  <input type="text" name="usergroup_label" class="form-control" id="l_usergroup_label" value="<?php echo htmlentities($ug['usergroup_label']);?>" maxlength="32">
					                </div>

					                <div class="form-group">
					                  <label for="l_usergroup_color_bg"><?php echo $lang->getLocale('ADMIN_UG_L8');?></label>
					                  <?php echo $content->colorInput("usergroup_color_bg", "form-control", null, "#".$ug['usergroup_color_bg']);?>
					                </div>

					                <div class="form-group">
					                  <label for="l_usergroup_color_text"><?php echo $lang->getLocale('ADMIN_UG_L9');?></label>
					                  <?php echo $content->colorInput("usergroup_color_text", "form-control", null, "#".$ug['usergroup_color_text']);?>
					                </div>

					                 <div class="form-group">
					                  <label for="usergroup_desc"><?php echo $lang->getLocale('ADMIN_UG_L11');?></label>
					                  <?php echo $content->editableInput("usergroup_desc", null, $ug['usergroup_desc']); ?>
					                </div>

					                <div class="form-group">
					                  <label for="l_usergroup_link"><?php echo $lang->getLocale('ADMIN_UG_L10');?></label>
					                  <input type="text" name="usergroup_link" class="form-control" id="l_usergroup_link" value="<?php echo $ug['usergroup_link'];?>">
					                </div>

				              </div>
				             	<div class="box-footer">

				             		<input type="submit" name="userGroupSave" value="<?php echo $lang->getLocale('ADMIN_UG_B5');?>" class="btn btn-success">
				             		<?php
					              $url = $links->getUserGroupLink($ug['usergroup_id']);
					              if(!empty($url)) { ?><a href="<?php echo $url;?>" target="_blank" class="btn btn-primary"><?php echo $lang->getLocale('ADMIN_UG_B6');?></a><?php }
					              else { ?><a class="btn btn-primary disabled"><?php echo $lang->getLocale('ADMIN_UG_B6');?></a><?php }
					              ?>
				             		<a href="<?php echo $web->getUrl("admin/adm.php?page=usergroups&usergroup=".$ug['usergroup_id']."&delete");?>" class="btn btn-danger"><?php echo $lang->getLocale('ADMIN_UG_B7');?></a>

				             	</div>
			              </form>
			            </div>

			            <?php
			            if(@$_POST["userGroupSave"]) {
			            	if(isset($_POST['usergroup_name']) && isset($_POST['usergroup_label']) && isset($_POST["usergroup_color_bg"]) && isset($_POST["usergroup_color_text"]) && 
			            	!empty($_POST['usergroup_name']) && !empty($_POST['usergroup_label']) && !empty($_POST["usergroup_color_bg"]) && !empty($_POST["usergroup_color_text"])) {

			            		$usergroup_link = null;
			            		if(!empty($_POST["usergroup_link"])) {
			            			$usergroup_link = $content->clearUserInputAll($_POST['usergroup_link']);
			            		}
			            		else {
			            			$usergroup_link = $content->clearUserInputAll($ug['usergroup_name']);
			            		}

			            		$usergroup_link = str_replace(array(" ", "%20"), array("-","-"), strtolower($content->removeDiacritics($usergroup_link)));

			            		if(!$links->isUserGroupLinkExists($usergroup_link, $ug['usergroup_id']) == 0) {
			            			$usergroup_link = null;
			            		}

			            		$col = $content->clearUserInputAll($_POST["usergroup_color_text"]);
			            		$bcol = $content->clearUserInputAll($_POST["usergroup_color_bg"]);
			            		if(!preg_match('/^#[a-f0-9]{6}$/i', $col)) $col = "#ffffff";
			            		if(!preg_match('/^#[a-f0-9]{6}$/i', $bcol)) $bcol = "#000000";

			            		$user->updateUserGroup($ug['usergroup_id'], array(
			            			"usergroup_name" => $content->clearUserInputAll($_POST['usergroup_name']),
									"usergroup_desc" => !empty($_POST["usergroup_desc"]) ? $content->clearUserInput($_POST["usergroup_desc"]) : "",
									"usergroup_label" => $content->clearUserInputAll($_POST['usergroup_label']),
									"usergroup_color_bg" => str_replace("#", "", $bcol),
									"usergroup_color_text" => str_replace("#", "", $col),
									"usergroup_link" => !empty($usergroup_link) ? $usergroup_link : ""
			            		));
		            			echo $display->adminAlert($lang->getLocale("ADMIN_E_W"), $lang->getLocale("ADMIN_UG_OK3"), "success"); 
			            	}
			            	else {
							        echo $display->adminAlert($lang->getLocale("ADMIN_E_W"), $lang->getLocale("ADMIN_E_E1"), "danger"); 
							}
							$web->redirect($web->getUrl("admin/adm.php?page=usergroups&usergroup=".$ug['usergroup_id']), 2);
			            } 
			            ?>

			            <?php if($user->isUserHasRights($user->loggedUser(), 20, "z")) { ?>
		
						<div class="box box-default">
							<div class="box-header">
								<h3 class="box-title"><?php echo $lang->getLocale("ADMIN_R_T1")." - ".htmlentities($ug['usergroup_name']);?></h3>
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
	  											$x = $user->getUserGroupRights($ug['usergroup_id'], $i);
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
	                  $user->setUserGroupDefaultRights($ug['usergroup_id']);
	                  echo $display->adminAlert($lang->getLocale("ADMIN_E_W"), $lang->getLocale("ADMIN_R_OK"), "success"); 
	                  $web->redirect($web->getUrl("admin/adm.php?page=usergroups&usergroup=".$ug['usergroup_id']), 2);
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
	                  
	                  $user->updateUserGroup($ug['usergroup_id'], array("usergroup_rights" => implode(".",$rights)));
	            			echo $display->adminAlert($lang->getLocale("ADMIN_E_W"), $lang->getLocale("ADMIN_R_OK"), "success"); 
	                  $web->redirect($web->getUrl("admin/adm.php?page=usergroups&usergroup=".$ug['usergroup_id']), 2);
	                }
	                ?>
							
	                <?php } ?>

		        	</div>
		        	<div class="col-xs-12 col-md-3">
		        	
		        		<div class="box box-primary">
			              <div class="box-header">
			                <h3 class="box-title"><?php echo $lang->getLocale("ADMIN_UG_T3");?></h3>
			                <div class="box-tools pull-right">
			                	<?php
			                	$url = $links->getUserGroupLink($ug['usergroup_id']);
			                	if(!empty($url)) { 
			                		?><a href="<?php echo $web->getUrl("admin/adm.php?page=usergroups&usergroup=".$ug['usergroup_id']."&add");?>" class="btn btn-xs btn-success"><?php echo $lang->getLocale("ADMIN_UG_B4");?></a><?php 
			                	}
			                	else {
			                		?><a class="btn btn-xs disabled btn-success"><?php echo $lang->getLocale("ADMIN_UG_B4");?></a><?php
			                	}
			                	?>
												
											</div>
			              </div>
			              
			              <div class="box-body no-padding">

				            <?php echo $content->dataTable("usersTable", "table");?>
					          <thead>
					              <tr>
					                <th width="70%"><?php echo $lang->getLocale("ADMIN_UG_L4");?></th>
					                <th width="30%"><?php echo $lang->getLocale("ADMIN_UG_L5");?></th>
					              </tr>
					            </thead>
					            <tbody>
					            <?php
					            if(isset($u) && count($u) > 0) {
					              for($i = 0;$i < count($u);$i ++) {
					                ?>
					                <tr>
					                	<td><a href="<?php echo $links->getUserLink($u[$i]['user_id']);?>" target="_blank"><?php echo $user->getUserName($u[$i]['user_id'])?></a></td>
					                	<td>
					                		<a class="btn btn-xs btn-danger" href="<?php echo $web->getUrl('admin/adm.php?page=usergroups&usergroup='.$ug['usergroup_id'].'&user='.$u[$i]['user_id'].'&remove');?>"><?php echo $lang->getLocale('ADMIN_UG_B3');?></a>

					                	</td>
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
		else $web->redirect($web->getUrl("admin/adm.php?page=usergroups"));
	}
	else if(isset($_GET["add"])) {
	  $ugid = $user->createUserGroup();
	  $user->setUserGroupDefaultRights($ugid);
	  $web->redirect($web->getUrl("admin/adm.php?page=usergroups&usergroup=".$ugid));
	}
	else {
		$web->addToTitle(" - ".$lang->getLocale("ADMIN_UG_T2"));
		?>
		<!-- Page Heading -->
		<section class="content-header">
		  <h1><?php echo $lang->getLocale("ADMIN_UG_T1");?><small><?php echo $web->getAdministrationName();?></small></h1>
		  <ol class="breadcrumb">
		    <li><a href="<?php echo $web->getUrl("admin/adm.php");?>"><i class="fa fa-dashboard"></i> <?php echo $web->getAdministrationName();?></a></li>
		    <li class="active"><?php echo $lang->getLocale("ADMIN_UG_T1");?></li>
		  </ol>
		</section>

		<section class="content">
		  <div class="row">
		    <div class="col-lg-12">

		      <div class="box box-primary">
		        <div class="box-header">
		          <h3 class="box-title"><?php echo $lang->getLocale("ADMIN_UG_T2");?></h3>  
		          <div class="box-tools pull-right">
		            <a href="<?php echo $web->getUrl("admin/adm.php?page=usergroups&add");?>" class="btn btn-xs btn-primary"><?php echo $lang->getLocale("ADMIN_UG_B1");?></a>
		          </div>
		        </div>

		        <div class="box-body no-padding">
		          
		          <?php echo $content->dataTable("userGroupsTable", "table");?>
		          <thead>
		              <tr>
		                <th width="25%"><?php echo $lang->getLocale("ADMIN_UG_L1");?></th>
		                <th width="25%"><?php echo $lang->getLocale("ADMIN_UG_L2");?></th>
		                <th width="50%"><?php echo $lang->getLocale("ADMIN_UG_L3");?></th>
		              </tr>
		            </thead>
		            <tbody>
		            <?php
		            $ug = $user->getUserGroupsAll();
		            if(isset($ug) && count($ug) > 0) {
		              for($i = 0;$i < count($ug);$i ++) {
		              	$u = $user->getUsersByUserGroupID($ug[$i]['usergroup_id'], 'user_id');
		              	$users = null;
		              	if(count($u) > 0) {
			              	// fix output
			              	foreach($u as $uu) { $users[] = $uu['user_id']; }
		              	}

		                ?>
		                <tr>
		                	<td><a href="<?php echo $web->getUrl("admin/adm.php?page=usergroups&usergroup=".$ug[$i]['usergroup_id']);?>"><?php echo htmlentities($ug[$i]['usergroup_name']);?></a></td>
		                	<td><?php echo $display->render_userGroup_label($ug[$i]['usergroup_id']);?></td>
		                	<td><?php echo (count($u) > 0) ? $display->renderUserNamesOutput($users, 3, 2) : "<i>".$lang->getLocale("ADMIN_UG_T5")."</i>";?></td>
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