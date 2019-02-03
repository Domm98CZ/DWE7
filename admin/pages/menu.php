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
* File: menu.php
* Filepath: /admin/pages/menu.php
*/
if(!defined("AUTHORIZED")) { die("Access Denied"); }
require_once DIR_INC."PageHeader.php";

if($user->isUserHasRights($user->loggedUser(), 17, "b")) {
	$web->addToTitle(" - ".$lang->getLocale("ADMIN_MN_T1"));

	if(isset($_GET["menu"]) && !empty($_GET["menu"]) && is_numeric($_GET["menu"]) && $_GET["menu"] > 0 && isset($_GET["addCategory"])) {
	  $m = $links->getMenuData($_GET["menu"]);
	  if(isset($m) && !empty($m) && $m['menu_sid'] > 0) {
	  	$web->redirect($web->getUrl("admin/adm.php?page=menu&menu=".$m['menu_sid']."&editCategory=".$links->addMenuData($m['menu_sid'], array(
	  		"menu_name" => $lang->getLocale('ADMIN_MN_N:K'),
	  		"menu_link" => "",
	  		"menu_dropdown" => "main",
	  		"menu_sid" => $m['menu_sid'],
	  		"menu_order" => "0"
	  	))));
	  }
	  else $web->redirect("admin/adm.php?page=menu");
	}
	else if(isset($_GET["menu"]) && !empty($_GET["menu"]) && is_numeric($_GET["menu"]) && $_GET["menu"] > 0 && isset($_GET["addLink"])) {
	  $m = $links->getMenuData($_GET["menu"]);
	  if(isset($m) && !empty($m) && $m['menu_sid'] > 0) {
	  	$web->redirect($web->getUrl("admin/adm.php?page=menu&menu=".$m['menu_sid']."&editLink=".$links->addMenuData($m['menu_sid'], array(
	  		"menu_name" => $lang->getLocale('ADMIN_MN_N:L'),
	  		"menu_link" => "",
	  		"menu_dropdown" => (($_GET["addLink"] > 0) ? $_GET["addLink"] : null),
	  		"menu_sid" => $m['menu_sid'],
	  		"menu_order" => "0"
	  	))));
	  }
	  else $web->redirect("admin/adm.php?page=menu");
	}
	else if(isset($_GET["menu"]) && !empty($_GET["menu"]) && is_numeric($_GET["menu"]) && $_GET["menu"] > 0 && isset($_GET["editCategory"])) {
	  $m = $links->getMenuData($_GET["menu"]);
	  if(isset($m) && !empty($m) && $m['menu_sid'] > 0) {
	  	$l = $links->getMenuDataSingle($_GET["editCategory"]);
	  	if(isset($l) && !empty($l) && $l['menu_id'] > 0) {
		  	$web->addToTitle(" - ".$lang->getLocale("ADMIN_MN_T6")." - ".htmlentities($m['menu_name']));
		    ?>
		    <!-- Page Heading -->
			<section class="content-header">
			  <h1><?php echo $lang->getLocale("ADMIN_MN_T1");?><small><?php echo $web->getAdministrationName();?></small></h1>
			  <ol class="breadcrumb">
			    <li><a href="<?php echo $web->getUrl("admin/adm.php");?>"><i class="fa fa-dashboard"></i> <?php echo $web->getAdministrationName();?></a></li>
			    <li><a href="<?php echo $web->getUrl("admin/adm.php?page=menu");?>"><i class="fa fa-dashboard"></i> <?php echo $lang->getLocale('ADMIN_MN_T2');?></a></li>
			    <li><a href="<?php echo $web->getUrl("admin/adm.php?page=menu&menu=".$m['menu_sid']);?>"><?php echo htmlentities($m['menu_name']);?></a></li>
			    <li class="active"><?php echo $lang->getLocale("ADMIN_MN_T11")." - ".htmlentities($l['menu_name']);?></li>
			  </ol>
			</section>

			<section class="content">
				<div class="box box-danger">
					<div class="box-header">
						<h3 class="box-title"><?php echo $lang->getLocale("ADMIN_MN_T11")." - ".htmlentities($l['menu_name']);?></h3>
						<div class="box-tools pull-right">
		                  <a href="<?php echo $web->getUrl("admin/adm.php?page=menu&menu=".$m['menu_sid']);?>" class="btn btn-xs btn-warning"><?php echo $lang->getLocale("ADMIN_MN_B4");?></a>
		                </div>
					</div>
					<form method="post">
						<div class="box-body">
				
							<div class="form-group">
								<label for="l_menu_name"><?php echo $lang->getLocale('ADMIN_MN_L9');?></label>
								<input type='text' name='menu_name' class='form-control' id='l_menu_name' maxlength="64" value="<?php echo htmlentities($l["menu_name"]);?>">
							</div>	

							<div class="form-group">
								<label for="l_menu_order"><?php echo $lang->getLocale('ADMIN_MN_L7');?></label>
								<input type='text' name='menu_order' class='form-control' id='l_menu_order' value="<?php echo htmlentities($l["menu_order"]);?>">
							</div>	
						
						</div>
						<div class="box-footer">
							<div class="pull-right">
								<input type="submit" name="saveCategory" class="btn btn-success" value="<?php echo $lang->getLocale("ADMIN_MN_B9");?>">
								<a href="<?php echo $web->getUrl("admin/adm.php?page=menu&menu=".$m['menu_sid']."&deleteCategory=".$l['menu_id']);?>" class="btn btn-danger"><?php echo $lang->getLocale('ADMIN_MN_B14');?></a>
							</div>
						</div>
					</form>
				</div>
				<?php
				if(@$_POST["saveCategory"]) {
					if(isset($_POST["menu_name"]) && !empty($_POST["menu_name"])) {
						if(is_numeric($_POST["menu_order"])) {
							$links->editMenuDataSingle($l['menu_id'], array(
								"menu_name" => $content->clearUserInputAll($_POST["menu_name"]),
								"menu_order" => $_POST["menu_order"]
							));
							echo $display->adminAlert($lang->getLocale("ADMIN_E_S"), $lang->getLocale("ADMIN_MN_OK8"), "success");	
						}
						else echo $display->adminAlert($lang->getLocale("ADMIN_E_W"), $lang->getLocale("ADMIN_MN_E2"), "warning");	
					}
					else echo $display->adminAlert($lang->getLocale("ADMIN_E_W"), $lang->getLocale("ADMIN_E_E1"), "danger");	
					$web->redirect($web->getUrl("admin/adm.php?page=menu&menu=".$m['menu_sid']."&editCategory=".$l['menu_id']), 2);
				}
				?>
			</section>
			<?php
		}
		else $web->redirect("admin/adm.php?page=menu");
	  }
	  else $web->redirect("admin/adm.php?page=menu");
	}
	else if(isset($_GET["menu"]) && !empty($_GET["menu"]) && is_numeric($_GET["menu"]) && $_GET["menu"] > 0 && isset($_GET["editLink"])) {
	  $m = $links->getMenuData($_GET["menu"]);
	  if(isset($m) && !empty($m) && $m['menu_sid'] > 0) {
	  	$l = $links->getMenuDataSingle($_GET["editLink"]);
	  	if(isset($l) && !empty($l) && $l['menu_id'] > 0) {
		  	$web->addToTitle(" - ".$lang->getLocale("ADMIN_MN_T6")." - ".htmlentities($m['menu_name']));
		    ?>
		    <!-- Page Heading -->
			<section class="content-header">
			  <h1><?php echo $lang->getLocale("ADMIN_MN_T1");?><small><?php echo $web->getAdministrationName();?></small></h1>
			  <ol class="breadcrumb">
			    <li><a href="<?php echo $web->getUrl("admin/adm.php");?>"><i class="fa fa-dashboard"></i> <?php echo $web->getAdministrationName();?></a></li>
			    <li><a href="<?php echo $web->getUrl("admin/adm.php?page=menu");?>"><i class="fa fa-dashboard"></i> <?php echo $lang->getLocale('ADMIN_MN_T2');?></a></li>
			    <li><a href="<?php echo $web->getUrl("admin/adm.php?page=menu&menu=".$m['menu_sid']);?>"><?php echo htmlentities($m['menu_name']);?></a></li>
			    <li class="active"><?php echo $lang->getLocale("ADMIN_MN_T6")." - ".htmlentities($l['menu_name']);?></li>
			  </ol>
			</section>

			<section class="content">
				<div class="box box-danger">
					<div class="box-header">
						<h3 class="box-title"><?php echo $lang->getLocale("ADMIN_MN_T6")." - ".htmlentities($l['menu_name']);?></h3>
						<div class="box-tools pull-right">
		                  <a href="<?php echo $web->getUrl("admin/adm.php?page=menu&menu=".$m['menu_sid']);?>" class="btn btn-xs btn-warning"><?php echo $lang->getLocale("ADMIN_MN_B4");?></a>
		                </div>
					</div>
					<form method="post">
						<div class="box-body">
				
							<div class="form-group">
								<label for="l_menu_name"><?php echo $lang->getLocale('ADMIN_MN_L8');?></label>
								<input type='text' name='menu_name' class='form-control' id='l_menu_name' maxlength="64" value="<?php echo htmlentities($l["menu_name"]);?>">
							</div>	

							<div class="form-group">
								<label for="l_menu_link"><?php echo $lang->getLocale('ADMIN_MN_L6');?></label>
								<input type='text' name='menu_link' class='form-control' id='l_menu_link' value="<?php echo htmlentities($l["menu_link"]);?>">
							</div>	

							<div class="form-group">
								<label for="l_menu_order"><?php echo $lang->getLocale('ADMIN_MN_L7');?></label>
								<input type='text' name='menu_order' class='form-control' id='l_menu_order' value="<?php echo htmlentities($l["menu_order"]);?>">
							</div>	
						
						</div>
						<div class="box-footer">
							<div class="pull-right">
								<input type="submit" name="saveLink" class="btn btn-success" value="<?php echo $lang->getLocale("ADMIN_MN_B8");?>">
								<a href="<?php echo $web->getUrl("admin/adm.php?page=menu&menu=".$m['menu_sid']."&deleteLink=".$l['menu_id']);?>" class="btn btn-danger"><?php echo $lang->getLocale('ADMIN_MN_B13');?></a>
							</div>
						</div>
					</form>
				</div>
				<?php
				if(@$_POST["saveLink"]) {
					if(isset($_POST["menu_name"]) && isset($_POST["menu_link"]) && 
					!empty($_POST["menu_name"]) && !empty($_POST["menu_link"])) {
						if (!filter_var($_POST["menu_link"], FILTER_VALIDATE_URL) === false) {
							if(is_numeric($_POST["menu_order"])) {
								$links->editMenuDataSingle($l['menu_id'], array(
									"menu_name" => $content->clearUserInputAll($_POST["menu_name"]),
									"menu_link" => $_POST["menu_link"],
									"menu_order" => $_POST["menu_order"]
								));
								echo $display->adminAlert($lang->getLocale("ADMIN_E_S"), $lang->getLocale("ADMIN_MN_OK5"), "success");	
							}
							else echo $display->adminAlert($lang->getLocale("ADMIN_E_W"), $lang->getLocale("ADMIN_MN_E2"), "warning");	
						}	
						else echo $display->adminAlert($lang->getLocale("ADMIN_E_W"), $lang->getLocale("ADMIN_MN_E1"), "warning");	
					}
					else echo $display->adminAlert($lang->getLocale("ADMIN_E_W"), $lang->getLocale("ADMIN_E_E1"), "danger");	
					$web->redirect($web->getUrl("admin/adm.php?page=menu&menu=".$m['menu_sid']."&editLink=".$l['menu_id']), 2);
				}
				?>
			</section>
			<?php
		}
		else $web->redirect("admin/adm.php?page=menu");
	  }
	  else $web->redirect("admin/adm.php?page=menu");
	}
	else if(isset($_GET["menu"]) && !empty($_GET["menu"]) && is_numeric($_GET["menu"]) && $_GET["menu"] > 0 && isset($_GET["deleteCategory"])) {
	  $m = $links->getMenuData($_GET["menu"]);
	  if(isset($m) && !empty($m) && $m['menu_sid'] > 0) {
	  	$l = $links->getMenuDataSingle($_GET["deleteCategory"]);
	  	if(isset($l) && !empty($l) && $l['menu_id'] > 0) {
		  	$web->addToTitle(" - ".$lang->getLocale("ADMIN_MN_T6")." - ".htmlentities($m['menu_name']));
		    ?>
		    <!-- Page Heading -->
			<section class="content-header">
			  <h1><?php echo $lang->getLocale("ADMIN_MN_T1");?><small><?php echo $web->getAdministrationName();?></small></h1>
			  <ol class="breadcrumb">
			    <li><a href="<?php echo $web->getUrl("admin/adm.php");?>"><i class="fa fa-dashboard"></i> <?php echo $web->getAdministrationName();?></a></li>
			    <li><a href="<?php echo $web->getUrl("admin/adm.php?page=menu");?>"><i class="fa fa-dashboard"></i> <?php echo $lang->getLocale('ADMIN_MN_T2');?></a></li>
			    <li><a href="<?php echo $web->getUrl("admin/adm.php?page=menu&menu=".$m['menu_sid']);?>"><?php echo htmlentities($m['menu_name']);?></a></li>
			    <li class="active"><?php echo $lang->getLocale("ADMIN_MN_T9")." - ".htmlentities($l['menu_name']);?></li>
			  </ol>
			</section>

			<section class="content">
				<div class="box box-danger">
					<div class="box-header">
						<h3 class="box-title"><?php echo $lang->getLocale("ADMIN_MN_T9")." - ".htmlentities($l['menu_name']);?></h3>
					</div>
					<div class="box-body">

						<p><?php echo sprintf($lang->getLocale('ADMIN_MN_T10'), htmlentities($l['menu_name']));?></p>

			            <form method="post">
			              <input type="submit" name="deleteMenuCategory" value="<?php echo $lang->getLocale('ADMIN_MN_B14');?>" class="btn btn-danger">
			              <a href="<?php echo $web->getUrl("admin/adm.php?page=menu&menu=".$m['menu_sid']."&editCategory=".$l['menu_id']);?>" class="btn btn-primary"><?php echo $lang->getLocale('ADMIN_MN_B12');?></a>
			            </form>

					</div>
				</div>
				<?php
				if(@$_POST["deleteMenuCategory"]) {
					$links->deleteMenuDataSingle($l['menu_id']);
					echo $display->adminAlert($lang->getLocale("ADMIN_E_S"), $lang->getLocale("ADMIN_MN_OK7"), "success"); 
			        $web->redirect($web->getUrl("admin/adm.php?page=menu&menu=".$m['menu_sid']), 2);
				}
				?>
			</section>	
			<?php
			}
			else $web->redirect("admin/adm.php?page=menu");
		}
		else $web->redirect("admin/adm.php?page=menu");
	}
	else if(isset($_GET["menu"]) && !empty($_GET["menu"]) && is_numeric($_GET["menu"]) && $_GET["menu"] > 0 && isset($_GET["deleteLink"])) {
	  $m = $links->getMenuData($_GET["menu"]);
	  if(isset($m) && !empty($m) && $m['menu_sid'] > 0) {
	  	$l = $links->getMenuDataSingle($_GET["deleteLink"]);
	  	if(isset($l) && !empty($l) && $l['menu_id'] > 0) {
		  	$web->addToTitle(" - ".$lang->getLocale("ADMIN_MN_T6")." - ".htmlentities($m['menu_name']));
		    ?>
		    <!-- Page Heading -->
			<section class="content-header">
			  <h1><?php echo $lang->getLocale("ADMIN_MN_T1");?><small><?php echo $web->getAdministrationName();?></small></h1>
			  <ol class="breadcrumb">
			    <li><a href="<?php echo $web->getUrl("admin/adm.php");?>"><i class="fa fa-dashboard"></i> <?php echo $web->getAdministrationName();?></a></li>
			    <li><a href="<?php echo $web->getUrl("admin/adm.php?page=menu");?>"><i class="fa fa-dashboard"></i> <?php echo $lang->getLocale('ADMIN_MN_T2');?></a></li>
			    <li><a href="<?php echo $web->getUrl("admin/adm.php?page=menu&menu=".$m['menu_sid']);?>"><?php echo htmlentities($m['menu_name']);?></a></li>
			    <li class="active"><?php echo $lang->getLocale("ADMIN_MN_T8")." - ".htmlentities($l['menu_name']);?></li>
			  </ol>
			</section>

			<section class="content">
				<div class="box box-danger">
					<div class="box-header">
						<h3 class="box-title"><?php echo $lang->getLocale("ADMIN_MN_T8")." - ".htmlentities($l['menu_name']);?></h3>
					</div>
					<div class="box-body">

						<p><?php echo sprintf($lang->getLocale('ADMIN_MN_T7'), htmlentities($l['menu_name']));?></p>

			            <form method="post">
			              <input type="submit" name="deleteMenuLink" value="<?php echo $lang->getLocale('ADMIN_MN_B13');?>" class="btn btn-danger">
			              <a href="<?php echo $web->getUrl("admin/adm.php?page=menu&menu=".$m['menu_sid']."&editLink=".$l['menu_id']);?>" class="btn btn-primary"><?php echo $lang->getLocale('ADMIN_MN_B12');?></a>
			            </form>

					</div>
				</div>
				<?php
				if(@$_POST["deleteMenuLink"]) {
					$links->deleteMenuDataSingle($l['menu_id']);
					echo $display->adminAlert($lang->getLocale("ADMIN_E_S"), $lang->getLocale("ADMIN_MN_OK6"), "success"); 
			        $web->redirect($web->getUrl("admin/adm.php?page=menu&menu=".$m['menu_sid']), 2);
				}
				?>
			</section>	
			<?php
			}
		}
	}
	else if(isset($_GET["menu"]) && !empty($_GET["menu"]) && is_numeric($_GET["menu"]) && $_GET["menu"] > 0 && isset($_GET["delete"])) {
	  $m = $links->getMenuData($_GET["menu"]);
	  if(isset($m) && !empty($m) && $m['menu_sid'] > 0) {
	  	$web->addToTitle(" - ".$lang->getLocale("ADMIN_MN_T4")." - ".htmlentities($m['menu_name']));
	  	?>
	  	<!-- Page Heading -->
		<section class="content-header">
		  <h1><?php echo $lang->getLocale("ADMIN_MN_T1");?><small><?php echo $web->getAdministrationName();?></small></h1>
		  <ol class="breadcrumb">
		    <li><a href="<?php echo $web->getUrl("admin/adm.php");?>"><i class="fa fa-dashboard"></i> <?php echo $web->getAdministrationName();?></a></li>
		    <li><a href="<?php echo $web->getUrl("admin/adm.php?page=menu");?>"><i class="fa fa-dashboard"></i> <?php echo $lang->getLocale('ADMIN_MN_T2');?></a></li>
		    <li class="active"><?php echo $lang->getLocale("ADMIN_MN_T4")." - ".htmlentities($m['menu_name']);?></li>
		  </ol>
		</section>

		<section class="content">
			<div class="box box-danger">
				<div class="box-header">
					<h3 class="box-title"><?php echo $lang->getLocale("ADMIN_MN_T4")." - ".htmlentities($m['menu_name']);?></h3>
				</div>
				<div class="box-body">

					<p><?php echo sprintf($lang->getLocale('ADMIN_MN_T5'), htmlentities($m['menu_name']));?></p>

	                <form method="post">
	                  <input type="submit" name="deleteMenu" value="<?php echo $lang->getLocale('ADMIN_MN_B11');?>" class="btn btn-danger">
	                  <a href="<?php echo $web->getUrl("admin/adm.php?page=menu&menu=".$m['menu_sid']);?>" class="btn btn-primary"><?php echo $lang->getLocale('ADMIN_MN_B12');?></a>
	                </form>

				</div>
			</div>
			<?php
			if(@$_POST["deleteMenu"]) {
				$links->deleteMenu($m['menu_sid']);
				echo $display->adminAlert($lang->getLocale("ADMIN_E_S"), $lang->getLocale("ADMIN_MN_OK1"), "success"); 
	            $web->redirect($web->getUrl("admin/adm.php?page=menu"), 2);
			}
			?>
		</section>	
	  	<?php
	  }
	}
	else if(isset($_GET["menu"]) && !empty($_GET["menu"]) && is_numeric($_GET["menu"]) && $_GET["menu"] > 0) {
	  $m = $links->getMenuData($_GET["menu"]);
	  if(isset($m) && !empty($m) && $m['menu_sid'] > 0) {
	    $web->addToTitle(" - ".$lang->getLocale("ADMIN_MN_T3")." - ".htmlentities($m['menu_name']));
	    ?>
	    <!-- Page Heading -->
		<section class="content-header">
		  <h1><?php echo $lang->getLocale("ADMIN_MN_T1");?><small><?php echo $web->getAdministrationName();?></small></h1>
		  <ol class="breadcrumb">
		    <li><a href="<?php echo $web->getUrl("admin/adm.php");?>"><i class="fa fa-dashboard"></i> <?php echo $web->getAdministrationName();?></a></li>
		    <li><a href="<?php echo $web->getUrl("admin/adm.php?page=menu");?>"><i class="fa fa-dashboard"></i> <?php echo $lang->getLocale('ADMIN_MN_T2');?></a></li>
		    <li class="active"><?php echo $lang->getLocale("ADMIN_MN_T3")." - ".htmlentities($m['menu_name']);?></li>
		  </ol>
		</section>

		<section class="content">
			<div class="box box-danger">
				<div class="box-header">
					<h3 class="box-title"><?php echo $lang->getLocale("ADMIN_MN_T3")." - ".htmlentities($m['menu_name']);?></h3>
					<div class="box-tools pull-right">
	                  <a href="<?php echo $web->getUrl("admin/adm.php?page=menu");?>" class="btn btn-xs btn-warning"><?php echo $lang->getLocale("ADMIN_MN_B4");?></a>
	                </div>
				</div>
				<div class="box-body">

					<form method="post">
						<div class="form-group">
							<label for="l_menu_name"><?php echo $lang->getLocale('ADMIN_MN_L4');?></label>
							<input type='text' name='menu_name' class='form-control' id='l_menu_name' maxlength="64" value="<?php echo htmlentities($m["menu_name"]);?>">
						</div>	

						<input type="submit" name="saveMenu" class="btn btn-success" value="<?php echo $lang->getLocale("ADMIN_MN_B5");?>">
					</form>

					<hr>

					<?php
					if(count($m['links']) > 0) {
						echo "<ul>";
						for($i = 0;$i < count($m['links']);$i ++) {
							if(isset($m['links'][$i]['menu_dropdown']) && $m['links'][$i]['menu_dropdown'] == "main") {
								echo "<li><b>[".$lang->getLocale('ADMIN_MN_D:K')."]</b> ".htmlentities($m['links'][$i]['menu_name'])." <i class='fa fa-minus' aria-hidden='true'></i> <a href='".$web->getUrl("admin/adm.php?page=menu&menu=".$m['menu_sid']."&editCategory=".$m['links'][$i]['menu_id'])."' class='btn btn-xs btn-warning'>".$lang->getLocale("ADMIN_MN_B9")."</a><ul>";
								$subMenuLinks = $db->selectAll("menu", array("menu_sid" => $m['menu_sid'], "menu_dropdown" => $m['links'][$i]['menu_id']), 'array', "order by `menu_order` asc");
								if(count($subMenuLinks) > 0) {
									for($y = 0;$y < count($subMenuLinks);$y++) {
										echo "<li><b>[".$lang->getLocale('ADMIN_MN_D:L')."]</b> ".htmlentities($subMenuLinks[$y]['menu_name'])." <i class='fa fa-minus' aria-hidden='true'></i> <a href='".$web->getUrl("admin/adm.php?page=menu&menu=".$m['menu_sid']."&editLink=".$subMenuLinks[$y]['menu_id'])."' class='btn btn-xs btn-warning'>".$lang->getLocale("ADMIN_MN_B8")."</a></li>";
									}
								}
								else echo "<li><i>".$lang->getLocale('ADMIN_MN_L5-L')."</i></li>";
								echo "<li><a href='".$web->getUrl("admin/adm.php?page=menu&menu=".$m['menu_sid']."&addLink=".$m['links'][$i]['menu_id'])."' class='btn btn-xs btn-primary'>".$lang->getLocale("ADMIN_MN_B6")."</a></li>";
								echo "</ul></li>";
							}
							else if($m['links'][$i]['menu_dropdown'] == null) { 
								echo "<li><b>[".$lang->getLocale('ADMIN_MN_D:L')."]</b> ".htmlentities($m['links'][$i]['menu_name'])." <i class='fa fa-minus' aria-hidden='true'></i> <a href='".$web->getUrl("admin/adm.php?page=menu&menu=".$m['menu_sid']."&editLink=".$m['links'][$i]['menu_id'])."' class='btn btn-xs btn-warning'>".$lang->getLocale("ADMIN_MN_B8")."</a></li>";
							}
						}
						echo "</ul>";
					}
					else {
						?><p class="text-danger"><?php echo $lang->getLocale('ADMIN_MN_L5');?></p><?php
					}
					?>

					<a href="<?php echo $web->getUrl("admin/adm.php?page=menu&menu=".$m['menu_sid']."&addLink");?>" class="btn btn-xs btn-primary"><?php echo $lang->getLocale("ADMIN_MN_B6");?></a>
					<a href="<?php echo $web->getUrl("admin/adm.php?page=menu&menu=".$m['menu_sid']."&addCategory");?>" class="btn btn-xs btn-primary"><?php echo $lang->getLocale("ADMIN_MN_B7");?></a>
				</div>
				<div class="box-footer">
					<a href="<?php echo $web->getUrl("admin/adm.php?page=menu&menu=".$m['menu_sid']."&delete");?>" class="btn btn-danger pull-right"><?php echo $lang->getLocale('ADMIN_MN_B10');?></a>
				</div>
			</div>

			<?php
			if(@$_POST["saveMenu"]) {
				if(isset($_POST["menu_name"]) && !empty($_POST["menu_name"])) {
					$links->editMenuName($m['menu_sid'], $content->clearUserInputAll($_POST["menu_name"]));
					echo $display->adminAlert($lang->getLocale("ADMIN_E_S"), $lang->getLocale("ADMIN_MN_OK4"), "success");	
				}
				else echo $display->adminAlert($lang->getLocale("ADMIN_E_W"), $lang->getLocale("ADMIN_E_E1"), "danger");	
				$web->redirect($web->getUrl("admin/adm.php?page=menu&menu=".$m['menu_sid']), 2);
			}
			?>
		</section>
	    <?php
	  }
	  else $web->redirect("admin/adm.php?page=menu");
	}
	else {
		?>
		<!-- Page Heading -->
		<section class="content-header">
		  <h1><?php echo $lang->getLocale("ADMIN_MN_T1");?><small><?php echo $web->getAdministrationName();?></small></h1>
		  <ol class="breadcrumb">
		    <li><a href="<?php echo $web->getUrl("admin/adm.php");?>"><i class="fa fa-dashboard"></i> <?php echo $web->getAdministrationName();?></a></li>
		    <li class="active"><?php echo $lang->getLocale('ADMIN_MN_T2');?></li>
		  </ol>
		</section>
		  
		<section class="content">
			<div class="box box-danger">
				<div class="box-header">
					<h3 class="box-title"><?php echo $lang->getLocale("ADMIN_MN_T2");?></h3>
				</div>
				<div class="box-body">

					<form method="post">
						<div class="form-group">
							<label for="l_menus"><?php echo $lang->getLocale('ADMIN_MN_L1');?></label>
							<div class="input-group">
								<select id="l_menus" name="menus" class="form-control">
								<option value="0" selected disabled>- <?php echo $lang->getLocale('ADMIN_MN_L1-L');?> -</option>
								<?php
								$m = $links->getMenus();
								if(count($m) > 0) {
									for($i = 0;$i < count($m);$i ++) {
										if($m[$i]['menu_sid'] != -1) echo "<option value='".$m[$i]['menu_sid']."'>".htmlentities($m[$i]['menu_name'])."</option>";
									}
								}
								?>
								</select>
								<div class="input-group-btn">
									<input type="submit" name="editMenu" class="btn btn-danger" value="<?php echo $lang->getLocale('ADMIN_MN_B1');?>">
								</div>
							</div>
						</div>
					</form>

					<hr>

					<form method="post">
						<div class="form-group">
							<label for="l_primary_menu"><?php echo $lang->getLocale('ADMIN_MN_L2');?></label>
							<div class="input-group">
								<select id="l_primary_menu" name="primary_menu" class="form-control">
								<?php
								$m = $links->getMenus();
								if(count($m) > 0) {
									for($i = 0;$i < count($m);$i ++) {
										if($m[$i]['menu_sid'] != -1) echo "<option value='".$m[$i]['menu_sid']."'".(($web->getSettings("web:menu") == $m[$i]['menu_sid']) ? " selected" : null).">".htmlentities($m[$i]['menu_name'])."</option>";
									}
								}
								?>
								</select>
								<div class="input-group-btn">
									<input type="submit" name="setPrimaryMenu" class="btn btn-primary" value="<?php echo $lang->getLocale('ADMIN_MN_B2');?>">
								</div>
							</div>
						</div>
					</form>

					<hr>

					<form method="post">
						<div class="form-group">
							<label for="l_new_menu"><?php echo $lang->getLocale('ADMIN_MN_L3');?></label>
							<div class="input-group">
								<input type='text' name='new_menu' class='form-control' id='l_new_menu' maxlength="64">
								<div class="input-group-btn">
									<input type="submit" name="createMenu" class="btn btn-success" value="<?php echo $lang->getLocale('ADMIN_MN_B3');?>">
								</div>
							</div>
						</div>
					</form>

				</div>
			</div>

			<?php
			if(@$_POST["editMenu"]) {
				if(isset($_POST["menus"]) && !empty($_POST["menus"])) {
					if($links->isMenuSIDExists($_POST["menus"]) == 1) {
						$web->redirect($web->getUrl("admin/adm.php?page=menu&menu=".$_POST["menus"]));
					}
					else $web->redirect($web->getUrl("admin/adm.php?page=menu"));
				}
				else {
					echo $display->adminAlert($lang->getLocale("ADMIN_E_W"), $lang->getLocale("ADMIN_E_E1"), "danger");	
					$web->redirect($web->getUrl("admin/adm.php?page=menu"), 2);
				}
			}

			if(@$_POST["setPrimaryMenu"]) {
				if(isset($_POST["primary_menu"]) && !empty($_POST["primary_menu"])) {
					if($links->isMenuSIDExists($_POST["primary_menu"]) == 1) {
						$web->updateSettings("web:menu", $_POST["primary_menu"]);
						echo $display->adminAlert($lang->getLocale("ADMIN_E_S"), $lang->getLocale("ADMIN_MN_OK2"), "success");	
					}
				}
				else echo $display->adminAlert($lang->getLocale("ADMIN_E_W"), $lang->getLocale("ADMIN_E_E1"), "danger");	
				$web->redirect($web->getUrl("admin/adm.php?page=menu"), 2);
				
			}

			if(@$_POST["createMenu"]) {
				if(isset($_POST["new_menu"]) && !empty($_POST["new_menu"])) {
					$links->createMenu($content->clearUserInputAll($_POST["new_menu"]));
					echo $display->adminAlert($lang->getLocale("ADMIN_E_S"), $lang->getLocale("ADMIN_MN_OK3"), "success");	
				}
				else echo $display->adminAlert($lang->getLocale("ADMIN_E_W"), $lang->getLocale("ADMIN_E_E1"), "danger");	
				$web->redirect($web->getUrl("admin/adm.php?page=menu"), 2);
			}
			?>
		</section>
		<?php
	}
}
else {
  $web->redirect($web->getUrl("admin/adm.php"));
}
?>