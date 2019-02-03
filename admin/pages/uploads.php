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
* File: uploads.php
* Filepath: /admin/pages/uploads.php
*/
if(!defined("AUTHORIZED")) { die("Access Denied"); }
require_once DIR_INC."PageHeader.php";

if($user->isUserHasRights($user->loggedUser(), 7, "b")) {
	$web->addToTitle(" - ".$lang->getLocale("ADMIN_UPL_T1"));

	if(isset($_GET["file"]) && !empty($_GET["file"])) {
		$filePath = DIR_UPLOADS.$_GET["file"];
		if(file_exists($filePath)) {
			?>
			<!-- Page Heading -->
			<section class="content-header">
				<h1><?php echo $lang->getLocale("ADMIN_UPL_T1");?><small><?php echo $web->getAdministrationName();?></small></h1>
				<ol class="breadcrumb">
					<li><a href="<?php echo $web->getUrl("admin/adm.php");?>"><i class="fa fa-dashboard"></i> <?php echo $web->getAdministrationName();?></a></li>
					<li><a href="<?php echo $web->getUrl("admin/adm.php?page=uploads");?>"><?php echo $lang->getLocale('ADMIN_UPL_T3');?></a></li>
					<li class="active"><?php echo sprintf($lang->getLocale('ADMIN_UPL_T4'), htmlentities($_GET["file"]));?></li>
				</ol>
			</section>

			<section class="content">
				<div class="row">
					<div class="col-xs-12 col-md-12">

						<div class="box box-warning">
							<div class="box-header">
								<h3 class="box-title"><?php echo sprintf($lang->getLocale('ADMIN_UPL_T4'), htmlentities($_GET["file"]));?></h3>
							</div>

							<div class="box-body">
								<?php 				
								if(filetype($filePath) == "file") {
									$pathinfo = pathinfo($filePath);
									if($pathinfo["extension"] == "png" || $pathinfo["extension"] == "gif" || $pathinfo["extension"] == "jpg" || $pathinfo["extension"] == "jpeg") {
										echo "<img src='".$filePath."' class='img-thumbnail'><br>";
									}    
									else if($pathinfo["extension"] == "html" || $pathinfo["extension"] == "txt") {
										echo "<p class='text-muted well well-sm no-shadow'>";
										$file = @file($filePath);  
										$maxLines = (count($file) > 1024) ? 1024 : count($file);
										for($i = 0;$i < $maxLines;$i++) echo htmlspecialchars($file[$i])."<br>";
										echo "</p>";
										if(count($file) > 1024) echo "<p class='text-danger'>Error, too long file.</p>";
									} 
									else if($pathinfo["extension"] == "pdf") {
										?>
										<div class="embed-responsive embed-responsive-16by9">
											<object data='<?php echo $filePath;?>' type='application/pdf' class="embed-responsive-item">
											<p>Error, your browser doesn't support this pdf view method, <a href='<?php echo $web->getUrl("uploads/".$_GET["file"]);?>'>here is file link.</a></p>
											</object>
										</div>
										<?php
									} 
									else if($pathinfo["extension"] == "mp3" || $pathinfo["extension"] == "wav") {
										?>
										<audio controls>
										<source src='<?php echo $filePath;?>' type='audio/<?php echo $pathinfo["extension"];?>'>
										Error, your browser doesn't support this audio player, <a href='<?php echo $web->getUrl("uploads/".$_GET["file"]);?>'>here is file link.</a>
										</audio>
										<?php
									} 
									else if($pathinfo["extension"] == "mp4" || $pathinfo["extension"] == "avi") {
										?>
										<video width='100%' height='100%' controls>
										<source src='<?php echo $filePath;?>' type='video/<?php echo $pathinfo["extension"];?>'>
										Error, your browser doesn't support this video player, <a href='<?php echo $web->getUrl("uploads/".$_GET["file"]);?>'>here is file link.</a>
										</video>
										<?php
									}  
									else {
										?><p class='text-warning'><?php echo $lang->getLocale('ADMIN_UP_T5');?></p><?php
									}
									echo "<hr>";
									echo "<b>".$lang->getLocale('ADMIN_UP_L1')."</b>: ".$pathinfo["basename"]."<br>";
									echo "<b>".$lang->getLocale('ADMIN_UP_L2')."</b>: ".$pathinfo["extension"]."<br>";
									echo "<b>".$lang->getLocale('ADMIN_UP_L3')."</b>: <a href='".$web->getUrl("uploads/".$_GET["file"])."' target='_blank'>".$web->getUrl("uploads/".$_GET["file"])."</a><br>";
								}
								?>
							</div>
							<div class="box-footer">
								<a href="<?php echo $web->getUrl("admin/adm.php?page=uploads");?>" class="btn btn-warning"><?php echo $lang->getLocale('ADMIN_UPL_B2')?></a>
								<a href="<?php echo $web->getUrl("admin/adm.php?page=uploads&file=".$_GET["file"]."&delete");?>" class="btn btn-danger"><?php echo $lang->getLocale('ADMIN_UPL_B3');?></a>
							</div>
						</div>	
						</div>
					</div>
			</section>
			<?php
		}
		else $web->redirect($web->getUrl("admin/adm.php?page=uploads"));
	}
	else if(isset($_GET["upload"])) {
		$web->addToTitle(" - ".$lang->getLocale("ADMIN_UPL_T3"));
		?>
		<!-- Page Heading -->
		<section class="content-header">
		  <h1><?php echo $lang->getLocale("ADMIN_UPL_T1");?><small><?php echo $web->getAdministrationName();?></small></h1>
		  <ol class="breadcrumb">
		    <li><a href="<?php echo $web->getUrl("admin/adm.php");?>"><i class="fa fa-dashboard"></i> <?php echo $web->getAdministrationName();?></a></li>
		    <li class="active"><?php echo $lang->getLocale('ADMIN_UPL_T3');?></li>
		  </ol>
		</section>

		<section class="content">
		  <div class="row">
		    <div class="col-xs-12 col-md-12">

		      <div class="box box-warning">
		        <div class="box-header">
		          <h3 class="box-title"><?php echo $lang->getLocale("ADMIN_UPL_T3");?></h3>
		        </div>

		        <div class="box-body">
	        		<form method="post" enctype="multipart/form-data">
	        			<div class="form-group">
	        				<label for="file">
	              			<input type="file" class="form-control" name="file" id="file">
	              		</div>
	              		<a href="<?php echo $web->getUrl('admin/adm.php?page=uploads');?>" class="btn btn-warning"><?php echo $lang->getLocale('ADMIN_UPL_B2');?></a>
	              		<input name="upload_file" type="submit" class="btn btn-primary" value="<?php echo $lang->getLocale('ADMIN_UPL_B1');?>">
	          		</form>
		        </div>
		      </div>
						
					<?php
					if(@$_POST["upload_file"]) {
						$array = explode('.', $_FILES['file']['name']);
						$file_name = $web->generateKey(20).".".end($array);
						$upload_path = DIR_UPLOADS.rand(10000, 99999).$file_name;  
						if ($_FILES["file"]["size"] < 50000000)  
						{
							if ($_FILES["file"]["error"] > 0)
							{
								echo "Error Code: ".$_FILES["file"]["error"]."<br />";
							}
							else
							{
								move_uploaded_file($_FILES["file"]["tmp_name"], $upload_path);
								$web->redirect($web->getUrl("admin/adm.php?page=uploads&file=".$file_name));
							}
						}
					}	
					?>

		    </div>
		  </div>
		</section>
		<?php
	}
	else {
		$web->addToTitle(" - ".$lang->getLocale("ADMIN_UP_T2"));
		?>
		<!-- Page Heading -->
		<section class="content-header">
		  <h1><?php echo $lang->getLocale("ADMIN_UPL_T1");?><small><?php echo $web->getAdministrationName();?></small></h1>
		  <ol class="breadcrumb">
		    <li><a href="<?php echo $web->getUrl("admin/adm.php");?>"><i class="fa fa-dashboard"></i> <?php echo $web->getAdministrationName();?></a></li>
		    <li class="active"><?php echo $lang->getLocale('ADMIN_UPL_T2');?></li>
		  </ol>
		</section>

		<section class="content">
		  <div class="row">
		    <div class="col-xs-12 col-md-12">

		      <div class="box box-warning">
		        <div class="box-header">
		          <h3 class="box-title"><?php echo $lang->getLocale("ADMIN_UPL_T2");?></h3>
		        </div>

		        <div class="box-body">
		        	<ul class="list-group">
		        		<a href="<?php echo $web->getUrl('admin/adm.php?page=uploads&upload');?>" class="list-group-item active"><?php echo $lang->getLocale('ADMIN_UPL_B1');?></a>
		        		<?php
		        		$files= $web->getDirFiles(DIR_UPLOADS, 1);
		        		if(count($files) > 0) {
		        			foreach($files as $file) {
		        				?><a href="<?php echo $web->getUrl('admin/adm.php?page=uploads&file='.urlencode($file));?>" class="list-group-item"><?php echo htmlentities($file);?></a><?php
		        			}
		        		}
		        		?>
		        	</ul>
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