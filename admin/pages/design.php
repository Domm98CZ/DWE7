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
* File: design.php
* Filepath: /admin/pages/design.php
*/
if(!defined("AUTHORIZED")) { die("Access Denied"); }
require_once DIR_INC."PageHeader.php";

if($user->isUserHasRights($user->loggedUser(), 16, "b")) {
	$web->addToTitle(" - ".$lang->getLocale('ADMIN_D_T1'));

	$userAdminDesign = $user->getUserSettings($user->loggedUser(), "admin-design");
	if($userAdminDesign == null) $userAdminDesign = "skin-blue";
	$userMenuDesign = $user->getUserSettings($user->loggedUser(), "admin-menu-design");
	?>
	<!-- Page Heading -->
	<section class="content-header">
	  <h1><?php echo $lang->getLocale("ADMIN_D_T2");?><small><?php echo $web->getAdministrationName();?></small></h1>
	  <ol class="breadcrumb">
	    <li><a href="<?php echo $web->getUrl("admin/adm.php");?>"><i class="fa fa-dashboard"></i> <?php echo $web->getAdministrationName();?></a></li>
	    <li class="active"><?php echo $lang->getLocale('ADMIN_D_T1');?></li>
	  </ol>
	</section>

	<section class="content">
	  <div class="row">
	    <div class="col-xs-12 col-md-12">

	      <div class="box box-success">
	        <div class="box-header">
	          <h3 class="box-title"><?php echo $lang->getLocale("ADMIN_D_T3");?></h3>
	        </div>

	        <form method="post">
	        	<div class="box-body">
	  				<div class="form-group">
						<div class="row">	
							<label class="col-md-2 control-label" for="l_admin_menu_design"><?php echo $lang->getLocale("ADMIN_D_L1");?></label>

							<div class="col-md-10"> 
				                <label class="radio-inline">
			  						<input type="radio" name="admin_menu_design" id="l_admin_menu_design" value="1"<?php echo (($userMenuDesign != true) ? " checked" : null);?>> <?php echo $lang->getLocale("ADMIN_D_L1-1");?>
								</label>
								<label class="radio-inline">
									<input type="radio" name="admin_menu_design" id="l_admin_menu_design" value="2"<?php echo (($userMenuDesign == true) ? " checked" : null);?>> <?php echo $lang->getLocale("ADMIN_D_L1-2");?>
								</label>
							</div>
						</div>
		            </div>

		            <div class="form-group">
		            	<div class="row">
		            		<label class="col-md-2 control-label" for="l_admin_design"><?php echo $lang->getLocale("ADMIN_D_L2");?></label>
		            		<div class="col-md-10"> 
				            	<div class="row">
									<div class="col-md-4">
										<div style="display:block;width:80%;height:30px;border:2px solid #000;" class="bg-light-blue"></div>
										<label class="radio-inline">
											<input type="radio" name="admin_design" id="l_admin_design" value="skin-blue"<?php echo (($userAdminDesign == "skin-blue") ? " checked" : null);?>> <?php echo $lang->getLocale("ADMIN_D_L2-1");?>
										</label>
										<div style="display:block;width:80%;height:30px;border:2px solid #000;background-color:#fefefe;"></div>
										<label class="radio-inline">
											<input type="radio" name="admin_design" id="l_admin_design" value="skin-black"<?php echo (($userAdminDesign == "skin-black") ? " checked" : null);?>> <?php echo $lang->getLocale("ADMIN_D_L2-2");?>
										</label>
									</div>
									<div class="col-md-4">
										<div style="display:block;width:80%;height:30px;border:2px solid #000;" class="bg-purple"></div>
										<label class="radio-inline">
											<input type="radio" name="admin_design" id="l_admin_design" value="skin-purple"<?php echo (($userAdminDesign == "skin-purple") ? " checked" : null);?>> <?php echo $lang->getLocale("ADMIN_D_L2-3");?>
										</label>

										<div style="display:block;width:80%;height:30px;border:2px solid #000;" class="bg-green"></div>
										<label class="radio-inline">
											<input type="radio" name="admin_design" id="l_admin_design" value="skin-green"<?php echo (($userAdminDesign == "skin-green") ? " checked" : null);?>> <?php echo $lang->getLocale("ADMIN_D_L2-4");?>
										</label>
									</div>
									<div class="col-md-4">
										<div style="display:block;width:80%;height:30px;border:2px solid #000;" class="bg-red"></div>
										<label class="radio-inline">
											<input type="radio" name="admin_design" id="l_admin_design" value="skin-red"<?php echo (($userAdminDesign == "skin-red") ? " checked" : null);?>> <?php echo $lang->getLocale("ADMIN_D_L2-5");?>
										</label>

										<div style="display:block;width:80%;height:30px;border:2px solid #000;" class="bg-yellow"></div>
										<label class="radio-inline">
											<input type="radio" name="admin_design" id="l_admin_design" value="skin-yellow"<?php echo (($userAdminDesign == "skin-yellow") ? " checked" : null);?>> <?php echo $lang->getLocale("ADMIN_D_L2-6");?>
										</label>
									</div>
								</div>
							</div>
						</div>
		            </div>
		        </div>
		        <div class="box-footer">
		        	<input type="submit" class="btn btn-success" name="setAdminDesign" value="<?php echo $lang->getLocale("ADMIN_D_B1");?>">
					<input type="submit" class="btn btn-primary" name="setAdminDesignDefault" value="<?php echo $lang->getLocale("ADMIN_D_B2");?>">
		        </div>
			</form>	
	      </div>

	      <?php
	      if(@$_POST["setAdminDesign"]) {
	      	if(isset($_POST["admin_design"]) && !empty($_POST["admin_design"]) && isset($_POST["admin_menu_design"]) && !empty($_POST["admin_menu_design"])) {
	      		if(in_array($_POST["admin_design"], $GLOBALS['ADMIN_ALLOWED_DESIGNS'])) {
	      			$user->updateUserSettings($user->loggedUser(), "admin-design", $_POST["admin_design"]);
	      		}

	      		if($_POST["admin_menu_design"] == "1") $user->deleteUserSettings($user->loggedUser(), "admin-menu-design");
	      		else if($_POST["admin_menu_design"] == "2") $user->updateUserSettings($user->loggedUser(), "admin-menu-design", "light");

	      		$web->redirect($web->getUrl("admin/adm.php?page=design"));
	      	}
	      	else {
	      		echo $display->adminAlert($lang->getLocale("ADMIN_E_C"), $lang->getLocale("ADMIN_D_E1"), "danger");
	      	}
	      }

	      if(@$_POST["setAdminDesignDefault"]) {
	      	$user->updateUserSettings($user->loggedUser(), "admin-design", "skin-blue");
	      	$user->deleteUserSettings($user->loggedUser(), "admin-menu-design");
	      	$web->redirect($web->getUrl("admin/adm.php?page=design"));
	      }
	      ?>

	      <div class="box box-primary">
	        <div class="box-header">
	          <h3 class="box-title"><?php echo $lang->getLocale("ADMIN_D_T4");?></h3>
	        </div>

	        <div class="box-body">
	        <?php
	        $themes = $web->getDirFiles(DIR_THEMES);
	        if(count($themes) > 0) {

	        	if(@$_GET["design"] && isset($_GET["design"]) && !empty($_GET["design"])) {
	        		if(in_array($_GET["design"], $themes)) {
	        			$web->updateSettings("web:theme", $_GET["design"]);
	        			echo $display->adminAlert($lang->getLocale("ADMIN_E_S"), $lang->getLocale("ADMIN_D_OK1"), "success"); 
	        			$web->redirect($web->getUrl("admin/adm.php?page=design"), 2);
	        		}
	        		else $web->redirect($web->getUrl("admin/adm.php?page=design"));
	        	}

	        	?><div class="row"><?php
	        	for($i = 0;$i < count($themes);$i++) {
	        		if(file_exists(DIR_THEMES.$themes[$i]."/config.php") && file_exists(DIR_THEMES.$themes[$i]."/theme.php")) {
	        			require_once(DIR_THEMES.$themes[$i]."/config.php"); 
	        			$config = true;
		        		$previewPath = DIR_THEMES.$themes[$i]."/preview.png";
		        		if(!isset($theme["theme_name"])) {
		        			$theme["theme_name"] = strtoupper($themes[$i]);
		        			$config = false;
		        		}
		        		if(!isset($theme["theme_author"])) {
	        				$theme["theme_author"] = "Unknown";
	        				$config = false;
	        			}
	        			if(!isset($theme["theme_url"])) {
	        				$theme["theme_url"] = "http://dwe.domm98.cz";
	        				$config = false;
	        			}
		        		?>
		        		<div class="col-sm-6 col-md-3">
			                <div class="thumbnail">
			                <?php 
			                if(file_exists($previewPath)) {
			                	?><img src="<?php echo $previewPath;?>" alt="<?php echo htmlentities($theme["theme_name"]);?>"><?php
			                }
			                ?>
			                  <div class="caption">
			                    <h3><?php echo htmlentities($theme["theme_name"]);?></h3>
			                    <ul>
			                        <li><?php echo $lang->getLocale("ADMIN_D_T5");?>: <?php echo htmlentities($theme["theme_author"]);?></li>
			                        <li><a href="<?php echo $theme["theme_url"];?>" target="_blank"><?php echo htmlentities($theme["theme_url"]);?></a></li>
			                    </ul>
		                    	<?php
		                    	// Problems
		                    	if(!file_exists($previewPath)) {
			                		?><p class="text-center text-warning"><?php echo $lang->getLocale("ADMIN_D_T7");?></p><?php
			               	 	}
			               	 	if($config == false) {
			               	 		?><p class="text-center text-warning"><?php echo $lang->getLocale("ADMIN_D_T8");?></p><?php
			               	 	}

		                    	// Button
		                    	if($themes[$i] == $display->getTheme()) {
		                    		?><a class="btn btn-primary btn-block disabled"><?php echo $lang->getLocale("ADMIN_D_B4");?></a><?php
		                    	}
		                    	else {
		                    		?><a href="<?php echo $web->getUrl("admin/adm.php?page=design&design=".$themes[$i]);?>" class="btn btn-success btn-block"><?php echo $lang->getLocale("ADMIN_D_B3");?></a><?php
		                    	}
		                    	?>
			                  </div>
			                </div>
			              </div>
		        		<?php
		        		unset($theme["theme_name"], $theme["theme_author"], $theme["theme_url"], $previewPath);
	        		}
	        		else {
	        			?>
	        			<div class="col-sm-6 col-md-3">
	        				<div class="thumbnail">
		        				<div class="caption">
		        					<h3><?php echo htmlentities(strtoupper($themes[$i]));?></h3>
		        					<p class="text-danger"><?php echo $lang->getLocale("ADMIN_D_T9");?></p>
		        				</div>
	        				</div>
	        			</div>
	        			<?php
	        		}
	        	}
	        	?></div><?php
	        }
	        ?>
	        </div>
	      </div>
	    </div>
	  </div>
	</section>
	<?php
}
else {
  $web->redirect($web->getUrl("admin/adm.php"));
}
?>