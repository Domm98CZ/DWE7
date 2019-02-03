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
* File: plugins.php
* Filepath: /admin/pages/plugins.php
*/
if(!defined("AUTHORIZED")) { die("Access Denied"); }
require_once DIR_INC."PageHeader.php";

if($user->isUserHasRights($user->loggedUser(), 15, "b")) {
	$activePlugins = $db->selectAll("plugins");
	$instaledPlugins = $web->getDirFiles(DIR_PLUGINS);
	
	if(isset($_GET["install"]) && !empty($_GET["install"]) && preg_match("/^[_a-zA-Z0-9-]+$/", $_GET["install"])) {
		if(in_array(strtolower($_GET["install"]), $instaledPlugins)) {
			if($db->selectRows("plugins", array("plugin_dir" => strtolower($_GET["install"]))) == 0) {
				$web->addToTitle(" - ".$lang->getLocale('ADMIN_PL_T2'));
				?>
				<!-- Page Heading -->
				<section class="content-header">
					<h1><?php echo $lang->getLocale("ADMIN_PL_T1");?><small><?php echo $web->getAdministrationName();?></small></h1>
					<ol class="breadcrumb">
						<li><a href="<?php echo $web->getUrl("admin/adm.php");?>"><i class="fa fa-dashboard"></i> <?php echo $web->getAdministrationName();?></a></li>
						<li><a href="<?php echo $web->getUrl("admin/adm.php?page=plugins");?>"><?php echo $lang->getLocale('ADMIN_PL_T1');?></a></li>
						<li class="active"><?php echo $lang->getLocale('ADMIN_PL_T2');?></li>
					</ol>
				</section>

				<section class="content">
				
					<div class="box box-primary">
						<div class="box-header">
							<h3 class="box-title"><?php echo $lang->getLocale("ADMIN_PL_T2");?></h3>
						</div>
						<div class="box-body">
							<?php
							if(file_exists(DIR_PLUGINS.strtolower($_GET["install"])."/config.php")) {
								include DIR_PLUGINS.strtolower($_GET["install"])."/config.php";
								
								echo sprintf($lang->getLocale("ADMIN_PL_T3"), htmlentities($plugin['plugin_name']." ".$plugin['plugin_version']))."<br>";
								
								// Instal File
								if(file_exists(DIR_PLUGINS.strtolower($_GET["install"])."/install.php")) {
									echo $lang->getLocale("ADMIN_PL_T4")."<br>";
									include DIR_PLUGINS.strtolower($_GET["install"])."/install.php";
									if(function_exists("plugin_install")) plugin_install($plugin['plugin_dir']);
								}
								
								$r = $db->insert("plugins", array(
									"plugin_name" => htmlentities($plugin['plugin_name']." ".$plugin['plugin_version']),
									"plugin_url" => htmlentities($plugin['plugin_url']),
									"plugin_dir" => htmlentities($plugin['plugin_dir']),
									"plugin_desc" => htmlentities($plugin['plugin_desc'])
								));
								
								if($r > 0) {
									echo "<span class='text-success'>".$lang->getLocale('ADMIN_PL_OK1')."</span>";
									$web->redirect($web->getUrl("admin/adm.php?page=plugins"), 2);
								}
								else {
									if(file_exists(DIR_PLUGINS.strtolower($plugin['plugin_dir'])."/uninstall.php")) {
										echo $lang->getLocale("ADMIN_PL_T6")."<br>";
										include DIR_PLUGINS.strtolower($plugin['plugin_dir'])."/uninstall.php";
									}
									echo "<span class='text-success'>".$lang->getLocale('ADMIN_PL_E1')."</span>";
									$web->redirect($web->getUrl("admin/adm.php?page=plugins"), 2);
								}
								
								unset($plugin);
							}
							?>
						</div>
					</div>
				
				</section>
				<?php
			}
			else $web->redirect($web->getUrl("admin/adm.php?page=plugins"));
		}
		else $web->redirect($web->getUrl("admin/adm.php?page=plugins"));
	}
	else if(isset($_GET["uninstall"]) && !empty($_GET["uninstall"]) && preg_match("/^[_a-zA-Z0-9-]+$/", $_GET["uninstall"])) {
		if(in_array(strtolower($_GET["uninstall"]), $instaledPlugins)) {
			if($db->selectRows("plugins", array("plugin_dir" => strtolower($_GET["uninstall"]))) > 0) {
				$web->addToTitle(" - ".$lang->getLocale('ADMIN_PL_T7'));
				?>
				<!-- Page Heading -->
				<section class="content-header">
					<h1><?php echo $lang->getLocale("ADMIN_PL_T1");?><small><?php echo $web->getAdministrationName();?></small></h1>
					<ol class="breadcrumb">
						<li><a href="<?php echo $web->getUrl("admin/adm.php");?>"><i class="fa fa-dashboard"></i> <?php echo $web->getAdministrationName();?></a></li>
						<li><a href="<?php echo $web->getUrl("admin/adm.php?page=plugins");?>"><?php echo $lang->getLocale('ADMIN_PL_T1');?></a></li>
						<li class="active"><?php echo $lang->getLocale('ADMIN_PL_T7');?></li>
					</ol>
				</section>
				
				<section class="content">	
					
					<div class="box box-primary">
						<div class="box-header">
							<h3 class="box-title"><?php echo $lang->getLocale("ADMIN_PL_T7");?></h3>
						</div>
						<div class="box-body">
						<?php
						if(file_exists(DIR_PLUGINS.strtolower($_GET["uninstall"])."/config.php")) {
							include DIR_PLUGINS.strtolower($_GET["uninstall"])."/config.php";

							echo sprintf($lang->getLocale("ADMIN_PL_T8"), htmlentities($plugin['plugin_name']." ".$plugin['plugin_version']))."<br>";

							// Instal File
							if(file_exists(DIR_PLUGINS.strtolower($_GET["uninstall"])."/uninstall.php")) {
								echo $lang->getLocale("ADMIN_PL_T6")."<br>";
								include DIR_PLUGINS.strtolower($_GET["uninstall"])."/uninstall.php";
							}
							else echo $lang->getLocale("ADMIN_PL_T6-2")."<br>";

							$db->delete("sidebars", array("sidebar_content_type" => "plugin", "sidebar_content" => strtolower($_GET["uninstall"])));
							$r = $db->delete("plugins", array("plugin_dir" => strtolower($_GET["uninstall"])));

							if($r > 0) echo "<span class='text-success'>".$lang->getLocale('ADMIN_PL_OK2')."</span>";
							else echo "<span class='text-danger'>".$lang->getLocale('ADMIN_PL_E2')."</span>";
							$web->redirect($web->getUrl("admin/adm.php?page=plugins"), 2);
							unset($plugin);
						}
						?>	
						</div>
					</div>
					
				</section>
				<?php
			}
			else $web->redirect($web->getUrl("admin/adm.php?page=plugins"));
		}
		else $web->redirect($web->getUrl("admin/adm.php?page=plugins"));
	}
	else if(isset($_GET["admin"]) && !empty($_GET["admin"]) && preg_match("/^[_a-zA-Z0-9-]+$/", $_GET["admin"])) {
		if(in_array(strtolower($_GET["admin"]), $instaledPlugins)) {
			$p = $db->select("plugins", array("plugin_dir" => strtolower($_GET["admin"])));
			if(isset($p) && !empty($p) && $p['plugin_id'] > 0) {
				if(file_exists(DIR_PLUGINS.$p['plugin_dir']."/admin.php")) {
					$web->addToTitle(" - ".$lang->getLocale('ADMIN_PL_T9')." - ".htmlentities($p['plugin_name']));
					?>
					<!-- Page Heading -->
					<section class="content-header">
						<h1><?php echo $lang->getLocale("ADMIN_PL_T1");?><small><?php echo $web->getAdministrationName();?></small></h1>
						<ol class="breadcrumb">
							<li><a href="<?php echo $web->getUrl("admin/adm.php");?>"><i class="fa fa-dashboard"></i> <?php echo $web->getAdministrationName();?></a></li>
							<li><a href="<?php echo $web->getUrl("admin/adm.php?page=plugins");?>"><?php echo $lang->getLocale('ADMIN_PL_T1');?></a></li>
							<li class="active"><?php echo $lang->getLocale('ADMIN_PL_T9')." - ".htmlentities($p['plugin_name']);?></li>
						</ol>
					</section>

					<section class="content">	
					
						<div class="box box-primary">
							<div class="box-header">
								<h3 class="box-title"><?php echo sprintf($lang->getLocale("ADMIN_PL_T10"), htmlentities($p['plugin_name']));?></h3>
							</div>
							<div class="box-body">
							<?php
							require_once DIR_PLUGINS.$p['plugin_dir']."/admin.php";	
							?>
							</div>
							<div class="box-footer">
								<a href="<?php echo $web->getUrl('admin/adm.php?page=plugins');?>" class="btn btn-xs btn-primary"><?php echo $lang->getLocale('ADMIN_PL_B5');?></a>
							</div>
						</div>
					</section>
					<?php
				}
			} 
			else $web->redirect($web->getUrl("admin/adm.php?page=plugins"));
		}
		else $web->redirect($web->getUrl("admin/adm.php?page=plugins"));
	}
	else {
		$web->addToTitle(" - ".$lang->getLocale('ADMIN_PL_T1'));
		?>
		<!-- Page Heading -->
		<section class="content-header">
			<h1><?php echo $lang->getLocale("ADMIN_PL_T1");?><small><?php echo $web->getAdministrationName();?></small></h1>
			<ol class="breadcrumb">
				<li><a href="<?php echo $web->getUrl("admin/adm.php");?>"><i class="fa fa-dashboard"></i> <?php echo $web->getAdministrationName();?></a></li>
				<li class="active"><?php echo $lang->getLocale('ADMIN_PL_T1');?></li>
			</ol>
		</section>

		<section class="content">
		<div class="row">
		<?php
	
		if(count($activePlugins) > 0) {
			for($i = 0;$i < count($activePlugins);$i++) {
				if(file_exists(DIR_PLUGINS.$activePlugins[$i]['plugin_dir']."/config.php")) {

					$instaledPlugins = array_values($instaledPlugins);
					for($y = 0;$y < count($instaledPlugins);$y++) {
						if(isset($instaledPlugins[$y]) && $instaledPlugins[$y] == $activePlugins[$i]['plugin_dir']) unset($instaledPlugins[$y]);
					}
					include DIR_PLUGINS.$activePlugins[$i]['plugin_dir']."/config.php";
					?>
					<div class="col-md-3">
							<div class="box box-primary">
								<div class="box-header with-border">
									<h3 class="box-title"><?php echo htmlentities($plugin['plugin_name']." ".$plugin['plugin_version']);?></h3>
								</div>
								<div class="box-body">
									<p><?php echo htmlentities($plugin['plugin_desc']);?></p>
								</div>
								<div class="box-footer">
									<?php
									if(file_exists(DIR_PLUGINS.$activePlugins[$i]['plugin_dir']."/admin.php")) {
										?><a href="<?php echo $web->getUrl("admin/adm.php?page=plugins&admin=".$activePlugins[$i]['plugin_dir']);?>" class="btn btn-xs btn-info"><?php echo $lang->getLocale('ADMIN_PL_B4');?></a>&nbsp;<?php
									}
									if(isset($plugin['plugin_url']) && !empty($plugin['plugin_url'])) {
										?><a target='_blank' href="<?php echo $plugin['plugin_url'];?>" class="btn btn-xs btn-primary"><?php echo $lang->getLocale('ADMIN_PL_B1');?></a><?php
									}
									?>
									<div class="pull-right">
										<a href="<?php echo $web->getUrl("admin/adm.php?page=plugins&uninstall=".$activePlugins[$i]['plugin_dir']);?>" class="btn btn-xs btn-danger"><?php echo $lang->getLocale('ADMIN_PL_B2');?></a>
									</div>
								</div>
							</div>
						</div>
					<?php
					unset($plugin);
				}
			}
		}

		$instaledPlugins = array_values($instaledPlugins);
		if(count($instaledPlugins) > 0) {
			for($i = 0;$i < count($instaledPlugins);$i++) {
				if(file_exists(DIR_PLUGINS.$instaledPlugins[$i]."/config.php")) {
					include DIR_PLUGINS.$instaledPlugins[$i]."/config.php";
					?>
					<div class="col-md-3">
						<div class="box box-solid">
							<div class="box-header with-border">
								<h3 class="box-title"><?php echo htmlentities($plugin['plugin_name']." ".$plugin['plugin_version']);?></h3>
							</div>
							<div class="box-body">
									<p><?php echo htmlentities($plugin['plugin_desc']);?></p>
								</div>
								<div class="box-footer">
									<?php
									if(isset($plugin['plugin_url']) && !empty($plugin['plugin_url'])) {
										?><a target='_blank' href="<?php echo $plugin['plugin_url'];?>" class="btn btn-xs btn-primary"><?php echo $lang->getLocale('ADMIN_PL_B1');?></a><?php
									}
									?>
									<div class="pull-right">
										<a href="<?php echo $web->getUrl("admin/adm.php?page=plugins&install=".$instaledPlugins[$i]);?>" class="btn btn-xs btn-success"><?php echo $lang->getLocale('ADMIN_PL_B3');?></a>
									</div>
								</div>
							</div>
						</div>
					<?php
					unset($plugin);
				}
			}
		}
		?>
		</div>
		</section>
		<?php
	}
}
else {
  $web->redirect($web->getUrl("admin/adm.php"));
}
?>