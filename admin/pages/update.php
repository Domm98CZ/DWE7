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
* File: update.php
* Filepath: /admin/pages/update.php
*/
if(!defined("AUTHORIZED")) { die("Access Denied"); }
require_once DIR_INC."PageHeader.php";

if($user->isUserHasRights($user->loggedUser(), 18, "b")) {
	$web->addToTitle(" - ".$lang->getLocale('ADMIN_UP_T1'));
	?>
	<!-- Page Heading -->
	<section class="content-header">
		<h1><?php echo $lang->getLocale("ADMIN_UP_T1");?><small><?php echo $web->getAdministrationName();?></small></h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo $web->getUrl("admin/adm.php");?>"><i class="fa fa-dashboard"></i> <?php echo $web->getAdministrationName();?></a></li>
			<li class="active"><?php echo $lang->getLocale('ADMIN_UP_T1');?></li>
		</ol>
	</section>

	<section class="content">

		<div class="row">
			<div class="col-md-9">

				<div class="box box-success">
					<div class="box-header">
						<h3 class="box-title"><i class="fa fa-server"></i> <?php echo $lang->getLocale('ADMIN_UP_T1');?></h3>
					</div>

					<div class="box-body">
					
						<ul class="list-unstyled">
							<li><?php echo sprintf($lang->getLocale('ADMIN_UP_T2'), $web->showTime($web->getCronLastStart('UpdateCron')));?></li>
							<?php
							if($cache->get("updateAvailable") > 0) {
								?><li><span class='text-success'><?php echo $lang->getLocale('ADMIN_UP_T4');?></span></li><?php
							}
							else {
								?><li><span class='text-warning'><?php echo $lang->getLocale('ADMIN_UP_T10');?></span></li><?php
							}
							?>
						</ul>

					</div>
					<div class="box-footer">
						<div class="pull-left">
							
							<form method="post">
								<input type="submit" name="findUpdates" value="<?php echo $lang->getLocale('ADMIN_UP_B3');?>" class="btn btn-sm  btn-info">
							</form>

						</div>


						<div class="pull-right">
								
							<form method="post">
								<input type="submit" name="update" value="<?php echo $lang->getLocale('ADMIN_UP_B1');?>" class="btn btn-sm btn-success">
							</form>

						</div>
						<div class="pull-right">
							
							<form method="post">
								<input type="submit" name="reUpdate" value="<?php echo $lang->getLocale('ADMIN_UP_B2');?>" class="btn btn-sm  btn-warning">
							</form>

						</div>

					</div>
				</div>


				<?php
				if(@$_POST["update"]) {
					?>
					<div class="box box-success">
						<div class="box-header">
							<h3 class="box-title"><?php echo $lang->getLocale('ADMIN_UP_T7');?></h3>
						</div>

						<div class="box-body">
							<?php 
							$update = $web->dwe7update(); 
							if($update > 0) { 
								?>
								<ul class='list-unstyled'>
									<li><strong><?php echo $lang->getLocale('ADMIN_UP_T8');?></strong>: <?php echo htmlentities($update[0]);?></li>
									<li><strong><?php echo $lang->getLocale('ADMIN_UP_T9');?></strong>: <ul>
										<?php 
										if(count($update[1]) > 0) {
											for($i = 0;$i < count($update[1]);$i++) {

												$exp = explode(" - ",$update[1][$i]);
												$l = str_replace("%", "", $exp[count($exp)-1]);
												?><li><?php echo htmlentities($exp[0]);?> - <i><?php echo htmlentities($lang->getLocale("ADMIN_UP_".$l));?></i></li><?php
											}
										}
										?>
									</ul></li>
								</ul>
								<?php
							}
							else {
								echo "<span class='text-danger'>".$lang->getLocale("ADMIN_UP_E1")."</span>";
								$web->redirect($web->getUrl("admin/adm.php?page=update"), 5);
							}
							?>
						
						</div>
					</div>
					<?php
				}

				if(@$_POST["reUpdate"]) {
					?>
					<div class="box box-success">
						<div class="box-header">
							<h3 class="box-title"><?php echo $lang->getLocale('ADMIN_UP_T7');?></h3>
						</div>

						<div class="box-body">
							<?php 
							$update = $web->dwe7update(true); 
							if($update > 0) { 
								?>
								<ul class='list-unstyled'>
									<li><strong><?php echo $lang->getLocale('ADMIN_UP_T8');?></strong>: <?php echo htmlentities($update[0]);?></li>
									<li><strong><?php echo $lang->getLocale('ADMIN_UP_T9');?></strong>: <ul>
										<?php 
										if(count($update[1]) > 0) {
											for($i = 0;$i < count($update[1]);$i++) {

												$exp = explode(" - ",$update[1][$i]);
												$l = str_replace("%", "", $exp[count($exp)-1]);
												?><li><?php echo htmlentities($exp[0]);?> - <i><?php echo htmlentities($lang->getLocale("ADMIN_UP_".$l));?></i></li><?php
											}
										}
										?>
									</ul></li>
								</ul>
								<?php
							}
							else {
								echo "<span class='text-danger'>".$lang->getLocale("ADMIN_UP_E1")."</span>";
								$web->redirect($web->getUrl("admin/adm.php?page=update"), 5);
							}
							?>
						
						</div>
					</div>
					<?php
				}
				?>
			</div>

			<div class="col-md-3">

				<div class="box box-warning">
					<div class="box-header">
						<h3 class="box-title"><i class="fa fa-server"></i> <?php echo $lang->getLocale('ADMIN_DASHBOARD_T9');?></h3>
					</div>

					<div class="box-body">
						<ul>
							<li><?php echo $lang->getLocale('ADMIN_DASHBOARD_L9');?>: <?php echo htmlentities($web->getSettings('web:title'));?></li>
							<li><?php echo $lang->getLocale('ADMIN_DASHBOARD_L10');?>: <?php echo htmlentities($web->getSettings("web:theme"));?></li>
							<li><?php echo $lang->getLocale('ADMIN_DASHBOARD_L13');?>: <?php echo htmlentities($web->getWebVersionAsString());?></li>
						</ul>
						<hr>
						<p class="text-warning text-center"><?php echo $lang->getLocale('ADMIN_DASHBOARD_L11');?></p>
					</div>
				</div>

			</div>
		</div>
		<?php

		if(@$_POST["findUpdates"]) {
			$web->doCheckUpdate();
			echo $display->adminAlert($lang->getLocale("ADMIN_UP_T5"), $lang->getLocale("ADMIN_UP_T6"), "info");
			$web->redirect($web->getUrl("admin/adm.php?page=update"), 2);
		}


		?>
	</section>
	<?php
}
else {
  $web->redirect($web->getUrl("admin/adm.php"));
}
?>