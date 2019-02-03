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
* File: sidebars.php
* Filepath: /admin/pages/sidebars.php
*/
if(!defined("AUTHORIZED")) { die("Access Denied"); }
require_once DIR_INC."PageHeader.php";

if($user->isUserHasRights($user->loggedUser(), 14, "b")) {
	$web->addToTitle(" - ".$lang->getLocale("ADMIN_SB_T1"));

	if(isset($_GET["sidebar"]) && !empty($_GET["sidebar"]) && is_numeric($_GET["sidebar"]) && isset($_GET["delete"])) {
		$sidebar = $db->select("sidebars", array("sidebar_id" => $_GET["sidebar"]));
		if(isset($sidebar) && !empty($sidebar) && $sidebar['sidebar_id'] > 0) {
			$web->addToTitle(" - ".$lang->getLocale("ADMIN_SB_T4"));
			?>
			<!-- Page Heading -->
			<section class="content-header">
				<h1><?php echo $lang->getLocale("ADMIN_SB_T3");?><small><?php echo $web->getAdministrationName();?></small></h1>
				<ol class="breadcrumb">
					<li><a href="<?php echo $web->getUrl("admin/adm.php");?>"><i class="fa fa-dashboard"></i> <?php echo $web->getAdministrationName();?></a></li>
					<li><a href="<?php echo $web->getUrl("admin/adm.php?page=sidebars");?>"><?php echo $lang->getLocale('ADMIN_SB_T1');?></a></li>
					<li class="active"><?php echo $lang->getLocale('ADMIN_SB_T4');?> - <?php echo htmlentities($sidebar['sidebar_name']);?></li>
				</ol>
			</section>

			<section class="content">	
				<div class="box box-danger">
					<div class="box-header">
						<h3 class="box-title"><?php echo $lang->getLocale("ADMIN_SB_T4");?> - <?php echo htmlentities($sidebar['sidebar_name']);?></h3>
					</div>
					<div class="box-body">	
						<p><?php echo sprintf($lang->getLocale('ADMIN_SB_T5'), htmlentities($sidebar['sidebar_name']));?></p>

						<form method="post">
							<input type="submit" name="deleteSidebar" value="<?php echo $lang->getLocale('ADMIN_SB_B3');?>" class="btn btn-danger">
							<a href="<?php echo $web->getUrl("admin/adm.php?page=sidebars&sidebar=".$sidebar['sidebar_id']);?>" class="btn btn-primary"><?php echo $lang->getLocale('ADMIN_SB_B6');?></a>
						</form>
					</div>
				</div>
			<?php
			if(@$_POST["deleteSidebar"]) {
				$db->delete("sidebars", array("sidebar_id" => $sidebar["sidebar_id"]));
				echo $display->adminAlert($lang->getLocale("ADMIN_E_S"), $lang->getLocale("ADMIN_SB_OK2"), "success"); 
				$web->redirect($web->getUrl("admin/adm.php?page=sidebars"), 2);
			}
			?>
			</section>
			<?php
		}
		else $web->redirect($web->getUrl("admin/adm.php?page=sidebars"));
	}
	else if(isset($_GET["sidebar"]) && !empty($_GET["sidebar"]) && is_numeric($_GET["sidebar"])) {
		$sidebar = $db->select("sidebars", array("sidebar_id" => $_GET["sidebar"]));
		if(isset($sidebar) && !empty($sidebar) && $sidebar['sidebar_id'] > 0) {
			$web->addToTitle(" - ".$lang->getLocale("ADMIN_SB_T3"));
			?>
			<!-- Page Heading -->
			<section class="content-header">
				<h1><?php echo $lang->getLocale("ADMIN_SB_T3");?><small><?php echo $web->getAdministrationName();?></small></h1>
				<ol class="breadcrumb">
					<li><a href="<?php echo $web->getUrl("admin/adm.php");?>"><i class="fa fa-dashboard"></i> <?php echo $web->getAdministrationName();?></a></li>
					<li><a href="<?php echo $web->getUrl("admin/adm.php?page=sidebars");?>"><?php echo $lang->getLocale('ADMIN_SB_T1');?></a></li>
					<li class="active"><?php echo $lang->getLocale('ADMIN_SB_T3');?> - <?php echo htmlentities($sidebar['sidebar_name']);?></li>
				</ol>
			</section>

			<section class="content">	
				<div class="box box-warning">
					<div class="box-header">
						<h3 class="box-title"><?php echo $lang->getLocale("ADMIN_SB_T3");?> - <?php echo htmlentities($sidebar['sidebar_name']);?></h3>
					</div>
					<form method="post">
					<div class="box-body">		
							
						<div class="form-group">
		                    <label for="l_sidebar_name"><?php echo $lang->getLocale('ADMIN_SB_L2');?></label>
		                    <input type="text" name="sidebar_name" class="form-control" id="l_sidebar_name" value="<?php echo htmlentities($sidebar['sidebar_name']);?>">
	                  	</div>

	                  	<div class="form-group">
		                    <label for="l_sidebar_title"><?php echo $lang->getLocale('ADMIN_SB_L3');?></label>
		                    <input type="text" name="sidebar_title" class="form-control" id="l_sidebar_title" value="<?php echo htmlentities($sidebar['sidebar_title']);?>">
	                  	</div>

	                  	<?php
	                  	if($sidebar["sidebar_content_type"] == "text") {
	                  		?>
	                  		<div class="form-group">
			                    <label for="sidebar_content"><?php echo $lang->getLocale('ADMIN_SB_L4');?></label>
			                    <?php echo $content->editableInput("sidebar_content", null, $sidebar['sidebar_content']); ?>
			                </div>
	                  		<?php
	                  	}
	                  	else if($sidebar["sidebar_content_type"] == "plugin") {
	                  		$activePlugins = $db->selectAll("plugins");
	                  		if(count($activePlugins) > 0) {
	                  			$sidebarplugin = 0;
	                  			$sidebarString = null;
	                  			foreach($activePlugins as $plugin) {
	                  				if(file_exists(DIR_PLUGINS.strtolower($plugin['plugin_dir'])."/sidebar.php")) {
	                  					$sidebarplugin++;
	                  					$sidebarString .= "<option value='".$plugin['plugin_dir']."'".(($sidebar['sidebar_content'] == $plugin['plugin_dir']) ? " selected" : null).">".htmlentities($plugin['plugin_name'])."</option>";

	                  				}
	                  			}

	                  			if($sidebarplugin > 0) {
	                  				?>
	                  				<div class="form-group">
	                  					<label for="l_sidebar_content"><?php echo $lang->getLocale('ADMIN_SB_L4');?></label>
		                  				<select id="l_sidebar_content" name="sidebar_content" class="form-control">
		                  				<?php echo $sidebarString;?>
		                  				</select>
	                  				</div>
	                  				<?php
	                  			} 
	                  			else echo "<p class='text-warning'>".$lang->getLocale('ADMIN_SB_L4-1')."</p>";

	                  			?><?php
	                  		}
	                  		else echo "<p class='text-warning'>".$lang->getLocale('ADMIN_SB_L4-1')."</p>";
	                  	}
	                  	?>
	                  	<div class="form-group">
          					<label for="l_sidebar_pos"><?php echo $lang->getLocale('ADMIN_SB_L8');?></label>
              				<select id="l_sidebar_pos" name="sidebar_pos" class="form-control">
              					<option value="top"<?php echo (($sidebar['sidebar_pos'] = "top") ? " selected" : null);?>><?php echo $lang->getLocale('ADMIN_SB_POS:top');?></option>
              					<option value="bottom"<?php echo (($sidebar['sidebar_pos'] = "bottom") ? " selected" : null);?>><?php echo $lang->getLocale('ADMIN_SB_POS:bottom');?></option>
              					<option value="left"<?php echo (($sidebar['sidebar_pos'] = "left") ? " selected" : null);?>><?php echo $lang->getLocale('ADMIN_SB_POS:left');?></option>
              					<option value="right"<?php echo (($sidebar['sidebar_pos'] = "right") ? " selected" : null);?>><?php echo $lang->getLocale('ADMIN_SB_POS:right');?></option>
              				</select>
              			</div>

	                  	<div class="form-group">
		                    <label for="l_sidebar_class"><?php echo $lang->getLocale('ADMIN_SB_L5');?></label>
		                    <input type="text" name="sidebar_class" class="form-control" id="l_sidebar_class" value="<?php echo ((!empty($sidebar['sidebar_class'])) ? htmlentities($sidebar['sidebar_class']) : 'default');?>">
	                  	</div>

	                  	<div class="form-group">
		                    <label for="l_sidebar_order"><?php echo $lang->getLocale('ADMIN_SB_L6');?></label>
		                    <input type="text" name="sidebar_order" class="form-control" id="l_sidebar_order" value="<?php echo htmlentities($sidebar['sidebar_order']);?>">
	                  	</div>

	                  	<div class="form-group">
	                        <label><input type="checkbox" name="sidebar_visibility"<?php echo (($sidebar['sidebar_visibility'] == "1") ? " checked" : null);?>> <?php echo $lang->getLocale('ADMIN_SB_L7');?></label>
	                    </div>
						
					</div>
					<div class="box-footer">
						<a href="<?php echo $web->getUrl("admin/adm.php?page=sidebars");?>" class="btn btn-warning"><?php echo $lang->getLocale("ADMIN_SB_B5");?></a>
						<a href="<?php echo $web->getUrl("admin/adm.php?page=sidebars&sidebar=".$sidebar['sidebar_id']."&delete");?>" class="btn btn-danger"><?php echo $lang->getLocale("ADMIN_SB_B3");?></a>
						<input type="submit" name="editSidebar" value="<?php echo $lang->getLocale("ADMIN_SB_B4");?>" class="btn btn-primary">
					</div>
					</form>
				</div>
			<?php
			if(@$_POST["editSidebar"]) {
				if(!empty($_POST["sidebar_name"]) && !empty($_POST["sidebar_title"]) && !empty($_POST["sidebar_pos"]) && !empty($_POST["sidebar_class"]) && is_numeric($_POST["sidebar_order"])) {
					if($sidebar["sidebar_content_type"] == "text") {
						$db->update("sidebars", array(
							"sidebar_name" => $content->clearUserInputAll($_POST["sidebar_name"]),
							"sidebar_title" => $content->clearUserInputAll($_POST["sidebar_title"]),
							"sidebar_class" => $content->clearUserInputAll($_POST["sidebar_class"]),
							"sidebar_order" => $_POST["sidebar_order"],
							"sidebar_content" => $content->clearUserInput($_POST["sidebar_content"]),
							"sidebar_visibility" => ((isset($_POST["sidebar_visibility"])) ? "1" : "0"),
							"sidebar_pos" => $_POST["sidebar_pos"]
						), array("sidebar_id" => $sidebar['sidebar_id']));
					}
					else if($sidebar["sidebar_content_type"] == "plugin") {
						$db->update("sidebars", array(
							"sidebar_name" => $content->clearUserInputAll($_POST["sidebar_name"]),
							"sidebar_title" => $content->clearUserInputAll($_POST["sidebar_title"]),
							"sidebar_class" => $content->clearUserInputAll($_POST["sidebar_class"]),
							"sidebar_order" => $_POST["sidebar_order"],
							"sidebar_content" => $_POST["sidebar_content"],
							"sidebar_visibility" => ((isset($_POST["sidebar_visibility"])) ? "1" : "0"),
							"sidebar_pos" => $_POST["sidebar_pos"]
						), array("sidebar_id" => $sidebar['sidebar_id']));
					}
					echo $display->adminAlert($lang->getLocale("ADMIN_E_S"), $lang->getLocale("ADMIN_SB_OK1"), "success");
				}
				else echo $display->adminAlert($lang->getLocale("ADMIN_E_W"), $lang->getLocale("ADMIN_E_E1"), "danger"); 
				$web->redirect($web->getUrl("admin/adm.php?page=sidebars&sidebar=".$sidebar["sidebar_id"]), 2);
			}
			?>
			</section>
			<?php

		}
		else $web->redirect($web->getUrl("admin/adm.php?page=sidebars"));
	}
	else if(isset($_GET["create"])) {
		$sidebar_id = $db->insert("sidebars", array(
			"sidebar_name" => $lang->getLocale('ADMIN_DEFAULT_NAME:SIDEBAR'),
			"sidebar_title" => $lang->getLocale('ADMIN_DEFAULT_NAME:SIDEBAR'),
			"sidebar_content_type" => "text",
			"sidebar_content" => "",
			"sidebar_pos" => "",
			"sidebar_visibility" => "0",
			"sidebar_class" => "",
			"sidebar_order" => "0"
		));
		$web->redirect($web->getUrl("admin/adm.php?page=sidebars&sidebar=".$sidebar_id));
	}
	else if(isset($_GET["create_plugin"])) {
		$sidebar_id = $db->insert("sidebars", array(
			"sidebar_name" => $lang->getLocale('ADMIN_DEFAULT_NAME:SIDEBAR'),
			"sidebar_title" => $lang->getLocale('ADMIN_DEFAULT_NAME:SIDEBAR'),
			"sidebar_content_type" => "plugin",
			"sidebar_content" => "",
			"sidebar_pos" => "",
			"sidebar_visibility" => "0",
			"sidebar_class" => "",
			"sidebar_order" => "0"
		));
		$web->redirect($web->getUrl("admin/adm.php?page=sidebars&sidebar=".$sidebar_id));
	}
	else {
		$web->addToTitle(" - ".$lang->getLocale("ADMIN_SB_T2"));
		?>
		<!-- Page Heading -->
		<section class="content-header">
			<h1><?php echo $lang->getLocale("ADMIN_SB_T1");?><small><?php echo $web->getAdministrationName();?></small></h1>
			<ol class="breadcrumb">
				<li><a href="<?php echo $web->getUrl("admin/adm.php");?>"><i class="fa fa-dashboard"></i> <?php echo $web->getAdministrationName();?></a></li>
				<li class="active"><?php echo $lang->getLocale('ADMIN_SB_T1');?></li>
			</ol>
		</section>

		<section class="content">	
			<div class="box box-primary">
				<div class="box-header">
					<h3 class="box-title"><?php echo $lang->getLocale("ADMIN_SB_T2");?></h3>
				</div>
				<div class="box-body">
					<ul class="list-group">
		        		<?php
		        		$sidebars = $db->selectAll("sidebars");
		        		if(count($sidebars) > 0) {
		        			foreach($sidebars as $sidebar) {
		        				?><a href="<?php echo $web->getUrl('admin/adm.php?page=sidebars&sidebar='.$sidebar['sidebar_id']);?>" class="list-group-item<?php echo (($sidebar['sidebar_visibility'] == "1") ? " active" : null);?>"><?php echo htmlentities($sidebar['sidebar_name']);?> <?php echo (($sidebar['sidebar_visibility'] == "1") ? "<i>(".$lang->getLocale("ADMIN_SB_L1").")</i>" : null);?></a><?php
		        			}
		        		}
		        		?>
		        	</ul>
		        	<a href="<?php echo $web->getUrl('admin/adm.php?page=sidebars&create');?>" class="btn btn-primary"><?php echo $lang->getLocale('ADMIN_SB_B1');?></a>
		        	<a href="<?php echo $web->getUrl('admin/adm.php?page=sidebars&create_plugin');?>" class="btn btn-primary"><?php echo $lang->getLocale('ADMIN_SB_B2');?></a>
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