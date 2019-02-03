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
* File: admin/leave.php
* Filepath: admin/leave.php
*/

require_once "../_core/maincore.php"; 

$web->clearHead();
$web->clearAdditionalJavascript();
$web->clearAdditionalScripts();

if($user->isUserLogged() && isset($_SESSION["aid"]) && !empty($_SESSION["aid"])) { 
	$a = $web->getUserAdministrationAccess($user->loggedUser(), $user->getUserDeviceAuthKey($user->loggedUser()), $_SESSION["aid"]);
	if($a == 1 || $a == 2) {
		// OK
	}
	else $web->redirect($web->getUrl()); 
}
else $web->redirect($web->getUrl());
?><!DOCTYPE html>
<html> 
	<head>
		<title>%%%WEB_TITLE%%%</title>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8">
		<meta charset="utf-8">
		<meta name="robots" content="noindex, nofollow">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<meta name="generator" content="<?php echo $web->getWebVersionAsString();?>" />

		<!-- Web Styles -->
		<link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />   
	    <link href="assets/css/main.css" rel="stylesheet" type="text/css" />    
	    <link href="//code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet" type="text/css" />    
	    <link href="assets/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
	    <link href="assets/css/skins/_all-skins.min.css" rel="stylesheet" type="text/css" />
	    <link href="plugins/iCheck/flat/blue.css" rel="stylesheet" type="text/css" />
	    <link href="plugins/morris/morris.css" rel="stylesheet" type="text/css" />
	    <link href="plugins/jvectormap/jquery-jvectormap-1.2.2.css" rel="stylesheet" type="text/css" />
	    <link href="plugins/datepicker/datepicker3.css" rel="stylesheet" type="text/css" />
	    <link href="plugins/daterangepicker/daterangepicker-bs3.css" rel="stylesheet" type="text/css" />
	    <link href="plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css" rel="stylesheet" type="text/css" />  

    	<script src="<?php echo $web->getSettings("scripts:jquery");?>"></script>
		<?php
		if($web->getSettings("web:favicon")) echo "<link rel='shortcut icon' href='".$web->getSettings("web:favicon")."'/>\n";
		else echo "<link rel='shortcut icon' href='".$web->getUrl()."assets/images/favicon.png'/>\n";
		?>
		<!-- Custom Fonts --> 
		<link href="<?php echo $web->getSettings("scripts:font-awesome");?>" rel="stylesheet">
		<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
	    <!--[if lt IE 9]>
	        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
	        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	    <![endif]-->
		
		<!-- Other -->
		<pagehead>%%%PAGE_HEAD%%%</pagehead>
	</head>
	<body class="login-page">
	    <div class="login-box">
	      <div class="login-logo">
	      	<?php echo $lang->getLocale('ADMIN_T1');?>
	      </div>
	      <div class="login-box-body">
      		
      		<?php
      		$kID = $web->findKeyID(array(
				"key_type" => "admin:aid",
				"key" => $_SESSION["aid"],
				"user_id" => $user->loggedUser(),
				"key_data" => $user->getUserDeviceAuthKey($user->loggedUser())
			));
			if($kID > 0) $web->deleteKey($kID);
			if(isset($_SESSION["aid"])) unset($_SESSION["aid"]);

      		if($a == 1) {
      			?><p class='text-primary'><?php echo $lang->getLocale('ADMIN_LEAVE_1');?></p><?php
      		}
      		else {
      			?><p class='text-primary'><?php echo $lang->getLocale('ADMIN_LEAVE_2');?></p><?php
      		}
      		?>
      		<p class='text-info'><?php echo $lang->getLocale('ADMIN_N1');?></p>
			<?php $web->redirect($web->getUrl(), 2);?>
			<br>
			<p class="text-right">Powered by <?php echo $web->getWebVersionAsString();?> </p>
	      </div>
    	</div>

	     <!-- Scripts -->
		<pagescripts>%%%PAGE_SCRIPTS%%%</pagescripts>
		<pagejs>%%%PAGE_JAVASCRIPT</pagejs>
	</body>
</html>
<?php
/* Re-Render Page */
$display->getPageAsString();
$display->clean();
$display->setVarAsPageString($display->replacePageVars(1));
echo $display->getDisplayString();
$control->webRenderTime($web->getActualUrl(), $web->getRenderTime());
?>
