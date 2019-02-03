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
* File: dashboard.php
* Filepath: /admin/pages/dashboard.php
*/
if(!defined("AUTHORIZED")) { die("Access Denied"); }
require_once DIR_INC."PageHeader.php";

$web->addToTitle(" - ".$lang->getLocale("ADMIN_DASHBOARD_T1"));
?>

<!-- Page Heading -->
<section class="content-header">
  <h1><?php echo $lang->getLocale("ADMIN_DASHBOARD_T1");?><small><?php echo $web->getAdministrationName();?></small></h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo $web->getUrl("admin/adm.php");?>"><i class="fa fa-dashboard"></i> <?php echo $web->getAdministrationName();?></a></li>
    <li class="active"><?php echo $lang->getLocale("ADMIN_DASHBOARD_T1");?></li>
  </ol>
</section>

<section class="content">
	<div class="row">

		<div class="col-lg-3 col-md-6">
			<div class="info-box bg-aqua">
				<span class="info-box-icon"><i class="fa fa-user"></i></span>
				<div class="info-box-content">
					<span class="info-box-text"><?php echo $lang->getLocale('ADMIN_DASHBOARD_T2');?></span>
					<span class="info-box-number"><?php echo number_format($db->selectRows("users"));?></span>
					<div class="progress">
						<div class="progress-bar" style="width: 100%"></div>
					</div>
					<span class="progress-description">
						<a style="color:#fff;" href="<?php echo $web->getUrl('adm.php?page=users');?>" class="small-box-footer"><?php echo $lang->getLocale('ADMIN_DASHBOARD_B1');?> <i class="fa fa-arrow-circle-right"></i></a>
					</span>
				</div>
			</div>
		</div>

		<div class="col-lg-3 col-md-6">
			<div class="info-box bg-green">
				<span class="info-box-icon"><i class="fa fa-users"></i></span>
				<div class="info-box-content">
					<span class="info-box-text"><?php echo $lang->getLocale('ADMIN_DASHBOARD_T3');?></span>
					<span class="info-box-number"><?php echo number_format($db->selectRows("userGroups"));?></span>
					<div class="progress">
						<div class="progress-bar" style="width: 100%"></div>
					</div>
					<span class="progress-description">
						<a style="color:#fff;" href="<?php echo $web->getUrl('adm.php?page=usergroups');?>" class="small-box-footer"><?php echo $lang->getLocale('ADMIN_DASHBOARD_B1');?> <i class="fa fa-arrow-circle-right"></i></a>
					</span>
				</div>
			</div>
		</div>

		<div class="col-lg-3 col-md-6">
			<div class="info-box bg-red">
				<span class="info-box-icon"><i class="fa fa-pencil-square" aria-hidden="true"></i></span>
				<div class="info-box-content">
					<span class="info-box-text"><?php echo $lang->getLocale('ADMIN_DASHBOARD_T4');?></span>
					<span class="info-box-number"><?php echo number_format($db->selectRows("posts"));?></span>
					<div class="progress">
						<div class="progress-bar" style="width: 100%"></div>
					</div>
					<span class="progress-description">
						<a style="color:#fff;" href="<?php echo $web->getUrl('adm.php?page=news');?>" class="small-box-footer"><?php echo $lang->getLocale('ADMIN_DASHBOARD_B1');?> <i class="fa fa-arrow-circle-right"></i></a>
					</span>
				</div>
			</div>
		</div>

		<div class="col-lg-3 col-md-6">
			<div class="info-box bg-yellow">
				<span class="info-box-icon"><i class="fa fa-home"></i></span>
				<div class="info-box-content">
					<span class="info-box-text"><?php echo $lang->getLocale('ADMIN_DASHBOARD_T5');?></span>
					<span class="info-box-number"><?php echo $web->getWebVersionAsString();?></span>
					<div class="progress">
						<div class="progress-bar" style="width: 100%"></div>
					</div>
					<span class="progress-description">
						<a style="color:#fff;" href="<?php echo $web->getUrl('adm.php?page=update');?>" class="small-box-footer"><?php echo $lang->getLocale('ADMIN_DASHBOARD_B1');?> <i class="fa fa-arrow-circle-right"></i></a>
					</span>
				</div>
			</div> 
		</div>
	</div>
	<div class="row">
		<div class="col-lg-3 col-md-6">
			<div class="box box-primary">
				<div class="box-header">
					<h3 class="box-title"><i class="fa fa-user"></i> <?php echo $lang->getLocale('ADMIN_DASHBOARD_T6');?></h3>
				</div>

				<div class="box-body">
					<ul>
						<?php 
						$userid = $db->select("users", array("user_rights" => "1"), "user_id", "order by `user_id` desc");
						?>
						<li><?php echo $lang->getLocale('ADMIN_DASHBOARD_L1');?>: <?php echo number_format($db->selectRows("users"));?></li>
						<li><?php echo $lang->getLocale('ADMIN_DASHBOARD_L2');?>: <?php echo number_format($db->selectRows("users", array("user_rights" => "-1")));?></li>
						<li><?php echo $lang->getLocale('ADMIN_DASHBOARD_L3');?>: <?php echo number_format($db->selectRows("users", array("user_rights" => "0")));?></li>
						<li><?php echo $lang->getLocale('ADMIN_DASHBOARD_L4');?>: <a target='_blank' href="<?php echo $links->getUserLink($userid);?>"><?php echo $user->getUserName($userid);?></a></li>
					</ul>
				</div>
			</div>
		</div>
		<div class="col-lg-3 col-md-6">
			<div class="box box-success">
				<div class="box-header">
					<h3 class="box-title"><i class="fa fa-file"></i> <?php echo $lang->getLocale('ADMIN_DASHBOARD_T7');?></h3>
				</div>

				<div class="box-body">
					<ul>
						<?php
						$files = $web->getDirFiles(DIR_UPLOADS);
						$size = 0;
						?>
						<li><?php echo $lang->getLocale('ADMIN_DASHBOARD_L5');?>: <?php echo number_format(count($files));?></li>
						<li><?php echo $lang->getLocale('ADMIN_DASHBOARD_L6');?>: <?php
						foreach($files as $file) {
							if(file_exists(DIR_UPLOADS.$file)) {
								$size += filesize(DIR_UPLOADS.$file);
							}
						}
						echo fileSizeReadAble($size);
						?></li>
					</ul>
				</div>
			</div>
		</div>
		<div class="col-lg-3 col-md-6">
			<div class="box box-danger">
				<div class="box-header">
					<h3 class="box-title"><i class="fa fa-pencil-square-o"></i> <?php echo $lang->getLocale('ADMIN_DASHBOARD_T8');?></h3>
				</div>

				<div class="box-body">
					<ul>
						<li><?php echo $lang->getLocale('ADMIN_DASHBOARD_L7');?>: <?php echo number_format($db->selectRows("posts", array("post_type" => "news")));?></li>
						<li><?php echo $lang->getLocale('ADMIN_DASHBOARD_L12');?>: <?php echo number_format($db->selectRows("posts", array("post_type" => "page")));?></li>
						<li><?php echo $lang->getLocale('ADMIN_DASHBOARD_L8');?>: <?php echo number_format($db->selectRows("comments"));?></li>
					</ul>
				</div>
			</div>
		</div>
		<div class="col-lg-3 col-md-6">
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
</section>