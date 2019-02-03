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
* File: Display.class.php
* Filepath: /_core/classes/Display.class.php
*/

if(!defined("AUTHORIZED")) { die("Access Denied"); }

class Display {
	private $PAGE_STRING = null;
	private $THEME_DEFAULT = 'default';

	public function __construct() {
		$this->getThemeFunctionFile($this->getTheme());	
	}

	public function setVarAsPageString($var) {
		$this->PAGE_STRING = $var;
	}

	public function getPageAsString() {
		$this->PAGE_STRING = ob_get_contents();
	}

	public function clean() {
		return ob_end_clean();
	}

	public function replacePageVars($administration = 0) {
		global $web, $content;
		if($administration == 0) $this->themeInit();
		
		$pageString = $this->PAGE_STRING;

		// Web Title
		$pageString = str_replace("<title>%%%WEB_TITLE%%%</title>", "<title>".$web->getTitle()."</title>", $pageString);
		$pageString = str_replace("<metatitle>%%%WEB_TITLE%%%</metatitle>", $web->getTitle(), $pageString);
		
		// Page Head
		$pageString = str_replace("<pagehead>%%%PAGE_HEAD%%%</pagehead>", $web->getAdditionalHead(), $pageString);
		$pageString = str_replace("<bodytags>%%%BODY_TAGS%%%</body<bodytags>", " ".$content->getBodyTags(), $pageString);

		// Javascript 
		$pageString = str_replace("<pagescripts>%%%PAGE_SCRIPTS%%%</pagescripts>", $web->getAdditionalScripts(), $pageString);
		$pageString = str_replace("<pagejs>%%%PAGE_JAVASCRIPT</pagejs>", $web->getAdditionalJavascript(), $pageString);

		// Other
		$pageString = str_replace("<rendertime>%%%WEB_RENDER_TIME%%%%</rendertime>", $web->getRenderTime(), $pageString);
		$pageString = str_replace("<siteend>%%%SITE_END</siteend>", $content->getSiteEnd(), $pageString);


		// minify
		$search = array('/\>[^\S ]+/s', '/[^\S ]+\</s', '/(\s)+/s');
		$replace = array('>','<','\\1');
		if (preg_match("/\<html/i", $pageString) == 1 && preg_match("/\<\/html\>/i", $pageString) == 1) {
			$pageString = preg_replace($search, $replace, $pageString);
		}

		return $pageString;
	}

	public function getDisplayString() {
		return $this->PAGE_STRING;
	}

	public function getThemeSettings($settings) {
		global $web;
		return $web->getSettings("theme:".$settings."");
	}

	public function setThemeSettings($settings, $value) {
		global $web;
		return $web->updateSettings("theme:".$settings."", $value);
	}

	public function getTheme() {
		global $web;
		return $web->getSettings("web:theme");
	}

	public function getDefaultTheme() {
		return $this->THEME_DEFAULT;
	}

	public function getThemePath($theme = null) {
		return DIR_THEMES.(($theme != null) ? $theme : $this->getDefaultTheme())."/";
	}

	private function getThemeFunctionFile($theme = null) {
		$filePath = $this->getThemePath($theme)."/theme.php";
		if(file_exists($filePath)) {
			include $filePath;
		}
		else return null;
	}

	public function countSidebars($pos) {
		global $db;
		return $db->selectRows("sidebars", array("sidebar_visibility" => "1", "sidebar_pos" => $pos));
	}

	public function getSidebars($pos) {
		global $db, $display;
		$sidebar = $db->selectAll("sidebars", array("sidebar_visibility" => "1", "sidebar_pos" => $pos), "array", "order by `sidebar_order`");
		if(count($sidebar) > 0) {
			for($i = 0;$i < count($sidebar);$i ++) {
				if($sidebar[$i]['sidebar_content_type'] == "text") { 
					echo $display->createpanel((!empty($sidebar[$i]['sidebar_title']) ? $display->displaytext($sidebar[$i]['sidebar_title']) : null), (!empty($sidebar[$i]['sidebar_class']) ? $sidebar[$i]['sidebar_class'] : "default"));
					echo $sidebar[$i]['sidebar_content'];
					echo $display->closepanel();
				}
				else if($sidebar[$i]['sidebar_content_type'] == "plugin") {
					if($db->selectRows("plugins", array("plugin_dir" => $sidebar[$i]['sidebar_content'])) > 0) {
						if(file_exists(DIR_PLUGINS.$sidebar[$i]['sidebar_content']."/sidebar.php")) {
							require_once DIR_PLUGINS.$sidebar[$i]['sidebar_content']."/sidebar.php";
						}
					}
				} 
			}
		}
	}

	public function getRenderTime() {
		global $lang;
		return $lang->getLocale("RENDERTIME");
	}

	public function getCopyRight() {
		$copyrightString = null;
		$copyrightString .= "Powered by <a target='_blank' href='http://dwe.domm98.cz'>DWE</a>.";
		return $copyrightString;
	}

	// Re-Register Theme functions+
	private function themeInit() {
		if(function_exists("theme_init")) return theme_init();
		else return null;
	}
	
	public function createpage() {
		if(function_exists("theme_page")) return theme_page();
		else {
			global $log, $web;
			$log->write("Theme - ".$this->getTheme()." >> theme_page() does not exist.", 4);
			if($this->getTheme() != "default") $web->updateSettings("web:theme", "default");
		}
	}

	public function userRole($user_role_id) {
		global $lang, $user;
		$rT = $user->getUserRoleTable();
		if(isset($rT[$user_role_id]) && !empty($rT[$user_role_id])) return $lang->getLocale("USER_ROLE_".$rT[$user_role_id]);
		else return null;
	}
	
	public function createnavigation($data) {
		return theme_navigation($data);
	}

	public function render_newscategory_list() {
		global $content;
		echo $content->render_newsCategory_list();
	}

	public function render_comments($object_id) {
		global $content;
		echo $content->render_comments($object_id);
	}

	public function render_object($object_id) {
		return theme_render_object($object_id);
	}

	public function render_userGroup_label($usergroup_id) {
		global $user, $display, $links;
		$g = $user->getUserGroup($usergroup_id);
		if(isset($g) && !empty($g)) {
			$lString = null;
			$link = $links->getUserGroupLink($usergroup_id);
			if(!empty($link)) {
				$lString .= "<a href='".$links->getUserGroupLink($usergroup_id)."' class='label' style='text-decoration:none;background-color:#".((!empty($g['usergroup_color_bg'])) ? $g['usergroup_color_bg'] : "777").";color:#".((!empty($g['usergroup_color_text'])) ? $g['usergroup_color_text'] : "fff")."'>";
			}
			else {
				$lString .= "<a class='label' style='text-decoration:none;background-color:#".((!empty($g['usergroup_color_bg'])) ? $g['usergroup_color_bg'] : "777").";color:#".((!empty($g['usergroup_color_text'])) ? $g['usergroup_color_text'] : "fff")."'>";
			}

			$lString .= $this->displayhtml($this->displaytext($g['usergroup_label']));
			$lString .= "</a>";
			return $lString;
		}
		else return null;
	}
	
	public function showUserStatus($user_id) {
		global $web, $user, $lang;
		$u = $user->getUserInfo($user_id);
		if(isset($u) && !empty($u)) {
			if ((time() - $u['user_timestamp_active']) <= USER_TIME_ONLINE) return "<span class='text-success'>".$web->showToolTip($lang->getLocale('P_PROFILE_ONLINE'), $web->showTime($u['user_timestamp_active']))."</span>";
			else if((time() - $u['user_timestamp_active']) <= USER_TIME_AFK) return "<span class='text-warning'>".$web->showToolTip($lang->getLocale('P_PROFILE_AFK'), $web->showTime($u['user_timestamp_active']))."</span>";
			else return "<span class='text-danger'>".$web->showToolTip($lang->getLocale('P_PROFILE_OFFLINE'), $web->showTime($u['user_timestamp_active']))."</span>";
		}		
	}
	
	public function showUserOnlineStatus($user_id) {
		global $web, $user, $lang;
		$u = $user->getUserInfo($user_id);
		if(isset($u) && !empty($u)) {
			return ((time() - $u['user_timestamp_active']) <= USER_TIME_ONLINE) ? "<span class='text-success'>".$web->showToolTip($lang->getLocale('P_PROFILE_ONLINE'), $web->showTime($u['user_timestamp_active']))."</span>" : $web->showTime($u['user_timestamp_login']);
		}
		else return null;
	}

	public function renderUserNamesOutput($user_ids = array(), $displaynames = 3, $allowlinks = 0, $shuffle = 1) {
		if(is_array($user_ids)) {
			if($shuffle == 1) shuffle($user_ids);
			$cc = count($user_ids);
			if($cc > 0) {
				global $links, $user, $lang, $web;
				$string = null;
				$maxDisplay = $displaynames;
				if($maxDisplay > $cc) $maxDisplay = $cc;
				for($i = 0;$i < $maxDisplay;$i ++) {
					if($allowlinks > 0) $string .= "<a href='".$links->getUserLink($user_ids[$i])."'".(($allowlinks == 2) ? " target='_blank'" : null).">";
					$string .= $user->getUserName($user_ids[$i]);
					if($allowlinks > 0) $string .= "</a>";
					$string .= ", ";
				}
				$string = substr($string, 0, -2);
				if($cc > $maxDisplay) {
					$cu = $cc - $maxDisplay;
					$tooltipstring = null;
					for($y = $maxDisplay;$y < $cc;$y ++) {
						$tooltipstring .= $user->getUserName($user_ids[$y]).", ";
					}
					$tooltipstring = substr($tooltipstring, 0, -2);
					$string .= " ".$web->showToolTip(sprintf($lang->plural_words_locale($cu, $lang->getLocale("OTHER_1-3"), $lang->getLocale("OTHER_1-2"), $lang->getLocale("OTHER_1-1")), $cu), $tooltipstring);				
				}
				return $string;
			}
		}
		else return null;
	}

	public function panel($title = null, $text, $additionalParameter = null, $footer = null) {
		$widgetString = null;
		$widgetString .= $this->createpanel($title, $additionalParameter);
		$widgetString .= htmlentities($text);
		$widgetString .= $this->closepanel($footer);
		return $widgetString;
	}

	public function createpanel($title = null, $additionalParameter = null) {
		return theme_createpanel($title, $additionalParameter);
	}

	public function closepanel($footer = null) {
		return theme_closepanel($footer);
	}

	public function createalert($title, $text, $additionalParameter = null) {
		return theme_createalert($title, $text, $additionalParameter);
	}

	public function displaytext($text) {
		return stripslashes(strip_tags($text));
	}

	public function displayhtml($text) {
		return htmlentities($text);
	}

	public function text($text) {
		$text = $this->displayhtml($this->displaytext($text));
		$text = nl2br($text);
		return $text;
	}

	public function adminAlert($title, $text, $type = "info", $icon = null) {
		$adminAlertString = null;
		$adminAlertString .= "<div class='alert alert-".$type." alert-dismissable'>";
		$adminAlertString .= "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>";
		$adminAlertString .= "<h4>".(($icon != null) ? $icon." ": null).htmlentities($title)."</h4>";
		$adminAlertString .= htmlentities($text);
		$adminAlertString .= "</div>";
		return $adminAlertString;
	}

	public function adminCallout($title, $text, $type="info") {
		$adminCalloutString = null;
		$adminCalloutString .= "<div class='callout callout-".$type."'>";
        $adminCalloutString .= "<h4>".htmlentities($title)."</h4>";
        $adminCalloutString .= "<p>".htmlentities($text)."</p>";
      	$adminCalloutString .= "</div>";
      	return $adminCalloutString;
	}
}	
?>