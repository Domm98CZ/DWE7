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
* File: index.php
* Filepath: index.php
*/

require_once "_core/maincore.php"; ?><!DOCTYPE html>
<html> 
	<head>
		<title>%%%WEB_TITLE%%%</title>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8">
		<meta charset="utf-8">
		<?php echo (($web->getSettings("web:public") == 1) ? '<meta name="robots" content="index, follow">' : '<meta name="robots" content="noindex, nofollow">')."\n";?>
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<meta name="generator" content="<?php echo $web->getWebVersionAsString();?>" />

		<!-- Web Styles -->
		<link href="<?php echo $web->getUrl();?><?php 
		$filePath = $display->getThemePath($display->getTheme());
		if(file_exists($filePath."styles.min.css")) echo $filePath."styles.min.css";
		else echo $filePath."styles.css";
		?>" rel="stylesheet" >
		<link href="<?php echo $web->getUrl();?>assets/css/main.css" rel="stylesheet">
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
        <!-- Seo -->
		<meta property="og:url" content="<?php echo $web->getActualUrl();?>"/>
		<meta property="og:locale" content="<?php echo $lang->getUsedLangCode();?>"/>
		<meta property="og:image" content="<?php echo $web->getSettings("web:image");?>"/>
		
		<meta property="og:title" content="<metatitle>%%%WEB_TITLE%%%</metatitle>"/>
		<meta property="og:site_name" content="<?php echo $web->getTitle();?>"/>
		
		<!-- Other -->
		<pagehead>%%%PAGE_HEAD%%%</pagehead>
	</head>
	<body<bodytags>%%%BODY_TAGS%%%</body<bodytags>>
		<?php echo $display->createpage(); ?>
		<siteend>%%%SITE_END</siteend>
		<!-- Scripts -->
		<script src="<?php echo $web->getSettings("scripts:jquery");?>"></script>
		<pagescripts>%%%PAGE_SCRIPTS%%%</pagescripts>
		<pagejs>%%%PAGE_JAVASCRIPT</pagejs>
	</body>
</html>

<?php
$content->generateMeta();
/* Re-Render Page */
$display->getPageAsString();
$display->clean();
$display->setVarAsPageString($display->replacePageVars());
echo $display->getDisplayString();
$control->webRenderTime($web->getActualUrl(), $web->getRenderTime());
?>