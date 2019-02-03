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
* File: mainterance.php
* Filepath: mainterance.php
*/
require_once "_core/maincore.php"; 

if($web->getSettings("web:maintenance") == null) header("location: ".$web->getUrl()."");
?><!DOCTYPE html>
<html> 
	<head>
		<title>%%%WEB_TITLE%%%</title>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8">
		<meta charset="utf-8">
		<?php echo (($web->getSettings("web:public") == 1) ? '<meta name="robots" content="index, follow">' : '<meta name="robots" content="noindex, nofollow">')."\n";?>
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<meta name="generator" content="<?php echo $web->getWebVersionAsString();?>" />

		<!-- Web Styles -->
		<link href="<?php echo $web->getUrl();?>assets/css/main.css" rel="stylesheet">
		<link href="<?php echo $web->getUrl();?><?php 
		$filePath = $display->getThemePath($display->getTheme());
		if(file_exists($filePath."styles.min.css")) echo $filePath."styles.min.css";
		else echo $filePath."styles.css";
		?>" rel="stylesheet" >
		<!-- Custom Fonts --> 
		<link href="<?php echo $web->getSettings("scripts:font-awesome");?>" rel="stylesheet">
		<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
	    <!--[if lt IE 9]>
	        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
	        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	    <![endif]-->
		<!-- Seo -->
		<meta name="description" content="<?php echo $web->getSettings("web:description");?>"/>
		<meta name="keywords" content="<?php echo $web->getSettings("web:keywords");?>"/>
		<meta property="og:url" content="<?php echo $web->getActualUrl();?>"/>
		<meta property="og:type" content="website"/>
		<meta property="og:locale" content="<?php echo $lang->getUsedLangCode();?>"/>
		<meta property="og:image" content="<?php echo $web->getSettings("web:image");?>"/>
		
		<meta property="og:title" content="<?php echo $web->getTitle();?>"/>
		<meta property="og:site_name" content="<?php echo $web->getTitle();?>"/>

		<meta property="og:description" content="<?php echo $web->getSettings("web:description");?>"/>
		
		<!-- Other -->
		<pagehead>%%%PAGE_HEAD%%%</pagehead>
	</head>
	<body>

		<!-- Mainterance -->
		<div class='container-fluid'>
			<div style="width:100%;height:100px;"></div>

			<div class="row">
				<div class='col-md-2'></div>
				<div class='col-md-8'>
					
				<div class="jumbotron">
					<div class="container">
						<h1><?php echo $web->getPageTitle();?> - <?php echo $lang->getLocale('MAINTENANCE_T1');?></h1>
				  		<p><?php echo $web->getSettings("web:maintenance");?></p>
				  		<p class="text-muted text-right"><small>Powered by <a href='http://dwe.domm98.cz'>DWE</a>. <i>(<?php echo $web->getWebVersionAsString();?>)</i></small></p>
				  	</div>
				</div>		

				<div class="panel panel-default">
					<div class="panel-body">
						<?php
						if($user->isUserLogged()) {
							?>
							<form method="post" class="form-inline pull-right">
								<?php 
								echo sprintf($lang->getLocale('MAINTENANCE_T2'), htmlentities($user->getUserInfo($user->loggedUser(), 'user_display_name')))." ";
								if($user->isUserHasRights($user->loggedUser(), 1, $web->getSettings("web:maintenanceLevel")) == true) { 
									echo "<a href='".$web->getUrl()."' class='btn btn-xs btn-success'>".$lang->getLocale('MAINTENANCE_B2')."</a>";
								}
								?>
								<input class="btn btn-xs btn-warning" type="submit" name="logout" value="<?php echo $lang->getLocale("MAINTENANCE_B1");?>">
							</form>
							<?php
							if(@$_POST['logout']) {
								$user->logoutUser($user->loggedUser());
								$web->redirect($web->getUrl("maintenance.php"));	
							}
						}
						else {
							$user->tryAutoLoginUser();
							?>
							<form method="post" class="form-inline pull-right">
							  <div class="form-group">
							    <label class="sr-only" for='user_name'><?php echo $lang->getLocale("P_LOGIN_L1");?></label>
							    <input type="text" class="form-control input-sm" name="user_name" id="user_name" value="" placeholder="<?php echo $lang->getLocale("P_LOGIN_L1");?>">
							  </div>
							  <div class="form-group">
							    <label class="sr-only" for="user_pass"><?php echo $lang->getLocale("P_LOGIN_L2");?></label>
							    <input type="password" class="form-control input-sm" name="user_pass" id="user_pass" value="" placeholder="<?php echo $lang->getLocale("P_LOGIN_L2");?>">
							  </div>
					 		  <input class="btn btn-sm btn-success" type="submit" name="login" value="<?php echo $lang->getLocale("P_LOGIN_B2");?>">
							</form>
							<?php
							if(@$_POST['login']) {
								$r = $user->tryAuthUser($_POST["user_name"], $_POST["user_pass"]);
								if($r == 1) {
									$user->loginUser($user->isUserExists($_POST['user_name']));
									$web->redirect($web->getUrl("maintenance.php"));	
								}
							}
						}
						?>
					</div>
				</div>

				</div>
				<div class='col-md-2'></div>
			</div>
		</div>

		<!-- Scripts -->
		<script src="<?php echo $web->getSettings("scripts:jquery");?>"></script>
		<pagescripts>%%%PAGE_SCRIPTS%%%</pagescripts>
		<pagejs>%%%PAGE_JAVASCRIPT</pagejs>
	</body>
</html>

<?php
/* Re-Render Page */
$display->getPageAsString();
$display->clean();
$display->setVarAsPageString($display->replacePageVars());
echo $display->getDisplayString();
?>