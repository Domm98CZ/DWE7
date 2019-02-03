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
* File: theme.php
* Filepath: /themes/default/theme.php
*/

if(!defined("AUTHORIZED")) { die("Access Denied"); }

function theme_init() {
	global $web, $display;
	$web->addAditionalScripts("<script src='".$web->getUrl($display->getThemePath($display->getTheme())."bootstrap.min.js")."'></script>");
	$web->addAditionalScripts("<script src='".$web->getUrl($display->getThemePath($display->getTheme())."main.js")."'></script>");
}

function theme_page() {
	global $web, $links, $display, $content;

	// Menu
	echo theme_navigation($links->getMenu());
	echo "<div class='".(($display->getThemeSettings("container") == 1) ? "container-fluid" : "container")."'>";

	$row = 12 + (($display->getThemeSettings("sidebarLeft") && $display->countSidebars("left") > 0) ? -3 : 0) + (($display->getThemeSettings("sidebarRight") && $display->countSidebars("right") > 0) ? -3 : 0);

	// Content Row
	echo "<div class='row'>";


	if($display->getThemeSettings("sidebarLeft") == "1" && $display->countSidebars("left")) {
		echo "<div class='col-md-3'>";
		echo $display->getSidebars("left");
		echo "</div>";
	}

	echo "<div class='col-md-".$row."'>";
	if($display->countSidebars("top")) echo $display->getSidebars("top");
	echo $content->getContent();
	if($display->countSidebars("bottom")) echo $display->getSidebars("bottom");
	echo "</div>";

	if($display->getThemeSettings("sidebarRight") == "1" && $display->countSidebars("right")) {
		echo "<div class='col-md-3'>";
		echo $display->getSidebars("right");
		echo "</div>";
	}

	echo "</div>";

	// Footer
	echo "<div class='row space'>";
	echo theme_createpanel();
	echo $display->getCopyRight();
	
	if($web->getSettings("web:displayRenderTime") == 1) echo "<span class='pull-right'>".$display->getRenderTime()."</span>";
	echo theme_closepanel();
	echo "</div>";

	// End of Content
	echo "</div>";
}

function theme_navigation($data) {
	global $web, $links;

	$menuString = null;
	$menuString .= "<nav class='navbar navbar-default'>";
	$menuString .= "<div class='container'>";
	$menuString .= "<div class='navbar-header'>";
	$menuString .= "<button type='button' class='navbar-toggle collapsed' data-toggle='collapse' data-target='#mainMenu'>";
	$menuString .= "<span class='sr-only'>Toggle navigation</span>";
	$menuString .= "<span class='icon-bar'></span>";
	$menuString .= "<span class='icon-bar'></span>";
	$menuString .= "<span class='icon-bar'></span>";
	$menuString .= "</button>";
	$menuString .= "<a class='navbar-brand' href='".$web->getUrl()."'>".$web->getTitle()."</a>";
	$menuString .= "</div>";
	$menuString .= "<div class='collapse navbar-collapse' id='mainMenu'>";
	$menuString .= "<ul class='nav navbar-nav'>";
	if(!empty($data) && is_array($data) && count($data) > 0) {
		$keys = array_keys($data);
		$values = array_values($data);
		for($i = 0;$i < count($keys);$i ++) {
			if (!filter_var($values[$i], FILTER_VALIDATE_URL) === false) {
				$menuString .= "<li><a href='".$values[$i]."'>".$keys[$i]."</a></li>";
			}
			else if(is_array($values[$i])) {
				$menuString .= "<li class='dropdown'>";
				$menuString .= "<a href='#' class='dropdown-toggle' data-toggle='dropdown' role='button' aria-expanded='false'>".$keys[$i]." <span class='caret'></span></a>";
				$menuString .= "<ul class='dropdown-menu' role='menu'>";
				$dropdown_keys = array_keys($values[$i]);
				$dropdown_values = array_values($values[$i]);
				for($y = 0;$y < count($dropdown_keys);$y ++) {
					if($dropdown_keys[$y] && $dropdown_values[$y] == "---") $menuString .= "<li class='divider'></li>";
					else $menuString .= "<li><a href='".$dropdown_values[$y]."'>".$dropdown_keys[$y]."</a></li>";
				}
				$menuString .= "</ul>";
				$menuString .= "</li>";	
			}
		}
	}
	$menuString .= "</ul>";
	$menuString .= "</div>";
 	$menuString .= "</div>";
	$menuString .= "</nav>";
	return $menuString;
}

function theme_render_object($object_id) {
	global $content, $user, $web, $display, $lang, $links;
	$p = $content->getPost($object_id);
	if($p['post_visibility'] > 0) {
		if($p['post_type'] == "news") {
			if($display->getThemeSettings("newsDesign") == 0) {
				echo theme_createpanel("<a href='".$links->getPostLink($object_id)."'>".htmlentities($p['post_title'])."</a>", (($p['post_visibility'] == 1) ? "info" : "primary"));

			}
			else if($display->getThemeSettings("newsDesign") == 1) {
				echo "<div class='panel panel-".(($p['post_visibility'] == 1) ? "info" : "primary")."'>";
				echo "<div class='panel-heading'>";
				echo "<span class='panel-title'><a href='".$links->getPostLink($object_id)."'>".htmlentities($p['post_title'])."</a></span>";
				echo "<span class='panel-title pull-right'>";
				echo "<a href='".$links->getUserLink($p['user_id'])."'><span class='panel-title'>".$user->getUserName($p['user_id'])."</span></a>";
				echo " <a href='".$links->getUserLink($p['user_id'])."'><img class='img-rounded' src='".($user->getUserAvatar($p['user_id'], 25))."' alt='".$user->getUserName($p['user_id'])." avatar' width='25px' heght='25px'></a>";
				echo "</span>";
				echo "</div>";
				echo "<div class='panel-body'>";
			}

			echo "<p>".$p['post_content']."</p>";

			/* News info */
			echo "<ul class='list-unstyled'>";
			echo "<li><b>".$lang->getLocale('NEWS_AUTHOR')."</b> <a href='".$links->getUserLink($p['user_id'])."'>".$user->getUserName($p['user_id'])."</a></li>";
			echo "<li><b>".$lang->getLocale('NEWS_DATE')."</b> ".$web->showTime($p['post_timestamp_add'])."</li>";
			if($p['newsCategory_id'] > 0) echo "<li><b>".$lang->getLocale('NEWS_CAT')."</b> ".$content->render_newsCategory_label($p['newsCategory_id'])."</li>";
			echo "</ul>";

			/* Comments */
			echo $content->render_comments_button($object_id);
			
			echo theme_closepanel();
		}
	}
	
}

function theme_createpanel($title = null, $style = "default") {
	$panelString = null;
	$panelString .= "<div class='panel panel-".$style."'>";
	if(!empty($title)) {
		$panelString .= "<div class='panel-heading'>";
		$panelString .= "<h3 class='panel-title'>".$title."</h3>";
		$panelString .= "</div>";
	}
	$panelString .= "<div class='panel-body'>";
	return $panelString;
}

function theme_closepanel($footer = null) {
	$panelString = null;
	$panelString .= "</div>";
	if(!empty($footer)) $panelString .= "<div class='panel-footer'>".$footer."</div>";
	$panelString .= "</div>";
	return $panelString;
}

function theme_createalert($title, $text, $style = "warning") {
	$alertString = null;
	$alertString .= "<div class='alert alert-dismissible alert-".$style."'>";
	$alertString .= "<button type='button' class='close' data-dismiss='alert'>&times;</button>";
	$alertString .= "<h4>".$title."</h4>";
	$alertString .= "<p>".$text."</p>";
	$alertString .= "</div>";
	return $alertString;	
}
?>