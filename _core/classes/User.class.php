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
* File: User.class.php
* Filepath: /_core/classes/User.class.php
*/
if(!defined("AUTHORIZED")) { die("Access Denied"); }

class User {
	
	private $userroles = array(
		-1 => "banned", 
		0 => "unactive",
		1 => "user",
		2 => "moderator",
		3 => "main_moderator",
		4 => "administrator",
		5 => "main_administrator"
	);
	
	private $rights_table = array(
		0 => null,
		"web_access",
		"administration_access",
		"administration_news",
		"administration_news_categories",
		"administration_comments",
		"administration_pages",
		"administration_uploads",
		"administration_users",
		"administration_usergroups",
		"administration_message",
		"administration_reports",
		"administration_main_settings",
		"administration_other_settings",
		"administration_sidebars",
		"administration_plugins",
		"administration_design",
		"administration_menu",
		"administration_update",
		"administration_users_permissions",
		"administration_usergroups_permissions"
	);
	
	public function __construct() {
		$this->createSessions();
		if($this->getUserLang() == null) $this->autoLangDetection();
	}
	
	public function getIP() {
		if(isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			return $_SERVER['HTTP_X_FORWARDED_FOR'];
		}
		else return $_SERVER['REMOTE_ADDR'];
	}

	public function geAcceptLang() {
		if(isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) && !empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
			return substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
		}
		else return null;
	}
	
	public function getUserRole($user_id) {
		global $db;
		return $db->select("users", array("user_id" => $user_id), 'user_rights');
	}
	
	public function setUserRole($user_id, $role_id) {
		global $db;
		if(array_key_exists($role_id, $this->userroles)) return $db->update("users", array("user_rights" => $role_id), array("user_id" => $user_id));
		else return null;
	}

	public function getUserRoleTable() {
		return $this->userroles;
	}
	
	private function autoLangDetection() {
		global $SYSTEM_LANG_CODES, $SYSTEM_LANGS;
		$l = $this->geAcceptLang();
		if(isset($SYSTEM_LANG_CODES["".strtolower($l).""])) {
			$this->setUserLang($SYSTEM_LANG_CODES["".strtolower($l).""]);
		}
	}
	
	public function setUserLang($lang) {
		if(!isset($_SESSION) || empty($_SESSION)) {
			$this->createSessions();
		}
		$_SESSION['lang'] = $lang;

		if($this->isUserLogged()) $this->updateUserSettings($this->loggedUser(), "lang", $lang);
	}
	
	public function getUserLang() {
		if($this->isUserLogged()) {
			$l = $this->getUserSettings($this->loggedUser(), "lang");
			return $l;
		}
		else if(isset($_SESSION['lang']) && !empty($_SESSION['lang'])) return $_SESSION['lang'];
		else return null;
	}

	public function generateUserSalt($lenght = 20) {
		global $web;
		return $web->generateKey($lenght);
	}

	public function generateUserPassword($pass, $salt = null) {
		global $web;
		$password = null;
		if($salt == null) $salt = $web->generateKey();

		$password .= md5($pass);
		$password .= md5($salt);
		$password = hash("sha512", $salt.hash("sha512", $password).$pass);

		return $password;
	}	

	public function isUserLogged() {
		if($this->getSession("user_id") > 0) {
			return true;
		}
		else false;
	}

	public function loggedUser() {
		return @$this->getSession("user_id");
	}

	public function isEmailUsed($user_email) {
		global $db;
		return @$db->select("users", array("user_email" => $user_email), "user_id"); 
	}

	public function isUserDisplayNameUsed($user_display_name) {
		global $db;
		return @$db->select("users", array("user_display_name" => $user_display_name), "user_id"); 
	}

	public function isUserExists($user_name) {
		global $db;
		return @$db->select("users", array("user_name" => $user_name), "user_id");
	}

	public function isUserIDExists($user_id) {
		global $db;
		return @$db->select("users", array("user_id" => $user_id), "user_name");
	}

	public function getUserInfo($user_id, $info = 'array') {
		global $db;
		return @$db->select("users", array("user_id" => $user_id), $info);
	}

	public function updateUserInfo($user_id, $info = array()) {
		global $db;
		return $db->update("users", $info, array("user_id" => $user_id));
	}

	public function getAllUsers($info = 'array') {
		global $db;
		return @$db->selectAll("users", null, $info);
	}

	public function getUserAvatar($user_id, $size = 90) {
		global $web;
		$avatar = null;
		$avatarType = @$this->getUserSettings($user_id, "avatar");
		if($avatarType == "gravatar") {
			$avatar = 'https://www.gravatar.com/avatar/';
		    $avatar .= md5(strtolower(trim($this->getUserInfo($user_id, "user_email"))));
		    $avatar .= "?s=".$size."&d=mm&r=g";
		}
		else if($avatarType == "file") {
			if(file_exists(DIR_UPLOADS."avatars/".$this->getUserSettings($user_id, "avatar_file"))) {
				$avatar = $web->getUrl("uploads/avatars/").$this->getUserSettings($user_id, "avatar_file");
			}
			else $avatar = $web->getUrl("uploads/avatars/noavatar.png");
		}
		/*else if($avatarType == "url") {
			$avatar = @$this->getUserSettings($user_id, "avatar_ex");
		}*/
		else {
			$avatar = $web->getUrl("uploads/avatars/noavatar.png");
		} 
		return $avatar;
	}

	public function getUserGroupInfo($usergroup_id, $info) {
		global $db;
		return @$db->select("userGroups", array("usergroup_id" => $usergroup_id), $info);
	}

	public function getUserGroupName($usergroup_id) {
		global $db;
		return @$db->select("userGroups", array("usergroup_id" => $usergroup_id), 'usergroup_name');	
	}
	
	public function getUserGroup($usergroup_id) {
		global $db;
		return @$db->select("userGroups", array("usergroup_id" => $usergroup_id));
	}

	public function getUserGroupMembership($user_id, $usergroup_id) {
		$g = $this->getUserGroup($usergroup_id);
		if(isset($g) && !empty($g)) {
			$ug = $this->getUserGroups($user_id);
			if(in_array($g["usergroup_id"], $ug) || $g["usergroup_id"] == $ug) {
				return 1;
			}
			else return null;
		}
		else return null;
	}
	
	public function getUserGroups($user_id) {
		$gstring = $this->getUserInfo($user_id, "user_groups");
		$g = explode("#", $gstring);
		$rg = null;
		if(count($g) > 2) {
			if(empty($g[0]) && is_numeric($g[1]) && empty($g[count($g)-1])) {
				for($i = 1;$i < count($g);$i ++) {
					if(isset($g[$i]) && !empty($g[$i])) $rg[] = $g[$i];
				}
				return $rg;
			}
			else return null;
		}
		else return null;
	}

	public function addUserIntoUserGroup($user_id, $group_id) {
		$gstring = $this->getUserInfo($user_id, "user_groups");
		// fix string
		if(substr($gstring, -1) == "#") $gstring = substr($gstring, 0, -1); 
		$g = explode("#", $gstring); 
		if(!in_array($group_id, $g)) {
			$g[] = $group_id;
			$gstring = implode("#", $g);
			// fix string
			if(substr($gstring, -1) != "#") $gstring .= "#";
			$this->updateUserInfo($user_id, array("user_groups" => $gstring));
		}
	}

	public function removeUserFromUserGroup($user_id, $group_id) {
		$gstring = $this->getUserInfo($user_id, "user_groups");
		$g = explode("#", $gstring);
		if(($key = array_search($group_id, $g)) !== false) { unset($g[$key]); }
		if(array_sum($g) > 0) { 
			$gstring = implode("#", $g);
		}
		else { $gstring = ""; } 
		if($this->getUserSettings($user_id, "group") == $group_id) $this->deleteUserSettings($user_id, "group");
		$this->updateUserInfo($user_id, array("user_groups" => $gstring));
	}

	public function getUserGroupsAll() {
		global $db;
		return @$db->selectAll("userGroups", null, 'array', 'order by `usergroup_id` desc');
	}

	public function getUsersByUserGroupID($usergroup_id, $data = 'array') {
		global $db;
		return @$db->querySelectAll("users", $data, "where `user_groups` LIKE  '%#".$usergroup_id."#%'");
	} 

	public function createUserGroup() {
		global $db, $lang;

		return $db->insert("userGroups", array(
			"usergroup_name" => $lang->getLocale("ADMIN_DEFAULT_NAME:USER_GROUP"),
			"usergroup_desc" => "",
			"usergroup_label" => $lang->getLocale("ADMIN_DEFAULT_NAME:USER_GROUP"),
			"usergroup_color_bg" => "fff",
			"usergroup_color_text" => "000",
			"usergroup_link" => "",
		));
	}

	public function updateUserGroup($usergroup_id, $data) {
		global $db;
		return @$db->update("userGroups", $data, array("usergroup_id" => $usergroup_id));
	}

	public function deleteUserGroup($userGroup_id) {
		global $db;

		$db->delete("userGroups", array("usergroup_id" => $userGroup_id));
		$db->delete("userSettings", array("userSettings_name" => "group", "userSettings_value" => $userGroup_id));
		$u = $this->getUsersByUserGroupID($userGroup_id);
		if(count($u) > 0) {
			for($i = 0;$i < count($u);$i ++) {
				$this->removeUserFromUserGroup($u[$i]['user_id'], $userGroup_id);
			}
		}
	}

	public function getUserName($user_id, $opt = 0, $pos = 0) {
		global $display, $lang, $links;
		if($this->isUserIDExists($user_id)) {
			if($opt == 0) return htmlentities($this->getUserInfo($user_id, "user_display_name"));
			else if($opt == 1) {
				$labelstring = null;

				$user_groups = $this->getUserInfo($user_id, "user_groups");
				$user_group = explode("#", $user_groups);

				if(!empty($user_groups) && isset($user_groups)) {
					if(is_array($user_group) && count($user_group) > 3) {
						if(in_array($this->getUserSettings($user_id, "group"), $user_group)) $labelstring = $display->render_userGroup_label($this->getUserSettings($user_id, "group"));
						else $labelstring = null;
					}
					else $labelstring = $display->render_userGroup_label($user_group[1]);
				}
				$namestring = "<a href='".$links->getUserLink($user_id)."'>".htmlentities($this->getUserInfo($user_id, "user_display_name"))."</a>";
				
				if($pos == 0) return (strlen($labelstring) > 0 ? $labelstring."&nbsp;" : null).$namestring;
				else return $namestring.(strlen($labelstring) > 0 ? "&nbsp;".$labelstring : null);
			}
		}
		else return $lang->getLocale('PROFILE_NOTEXISTS');
	}

	public function updateUserSettings($user_id, $settings, $value) {
		global $db;
		$s = $db->select("userSettings", array("userSettings_name" => $settings, "user_id" => $user_id), "user_id");
		if($s == $user_id) return @$db->update("userSettings", array("userSettings_value" => $value), array("userSettings_name" => $settings, "user_id" => $user_id));
		else return @$db->insert("userSettings", array("userSettings_name" => $settings, "userSettings_value" => $value, "user_id" => $user_id));
	}

	public function getUserSettings($user_id, $settings) {
		global $db;
		return $db->select("userSettings", array("userSettings_name" => $settings, "user_id" => $user_id), "userSettings_value");
	}

	public function deleteUserSettings($user_id, $settings) {
		global $db;
		return @$db->delete("userSettings", array("userSettings_name" => $settings, "user_id" => $user_id));
	}

	public function createUser($userName, $userPass, $userEmail, $userSalt = null, $rights = "0", $account_type = "web") {
		global $db;
		if(empty($userSalt)) $userSalt = $this->generateUserSalt();
		return $db->insert("users", array(
			"user_name" => $userName,
			"user_pass" => $this->generateUserPassword($userPass, $userSalt),
			"user_salt" => $userSalt,
			"user_display_name" => $userName,
			"user_email" => $userEmail,
			"user_timestamp_register" => time(),
			"user_timestamp_login" => 0,
			"user_timestamp_active" => 0,
			"user_rights" => $rights,
			"user_rights_detail" => $this->getDefaultRightsString(),
			"user_login_type" => $account_type
		));
	}

	public function deleteUser($user_id) {
		global $db;
		if($this->isUserIDExists($user_id) != null) { 
			$db->delete("users", array("user_id" => $user_id));
			$d = $this->getUserDevice($user_id);
			for($i = 0;$i < count($d);$i++) {
				$this->deleteDevice($d[$i]['device_id'], $user_id);
			}
			$db->delete("userSettings", array("user_id" => $user_id));
			$db->delete("keys", array("user_id" => $user_id));
			$db->delete("comments", array("user_id" => $user_id));
			$db->delete("messages", array("user_id_s" => $user_id));
			$db->update("posts", array("user_id" => "0"), array("user_id" => $user_id));
		}
	}

	public function tryAuthUser($user_name, $user_pass) {
		if(is_set($user_name) && is_set($user_pass)) {
			$u = $this->getUserInfo($this->isUserExists($user_name));
			if(is_set($u) && $u['user_id'] > 0) {
				if($u['user_pass'] == $this->generateUserPassword($user_pass, $u['user_salt'])) {
					if($u['user_rights'] >= 1 || $u['user_rights'] >= "1") {
						return 1;
					}
					else return -1;
				}
				else return -2;
			}
			else return -3;
		}
		else return -4;
	}

	public function tryAutoLoginUser() {
		global $web, $db;
		if(!$this->isUserLogged()) {
			if (isset($_COOKIE['dwe7autologinKey']) && isset($_COOKIE['dwe7autologinUser']) && !empty($_COOKIE['dwe7autologinKey']) && !empty($_COOKIE['dwe7autologinUser'])) {
				$device = @$db->select("devices", array('user_id' => intval($_COOKIE['dwe7autologinUser']), 'device_login_key' => $_COOKIE['dwe7autologinKey']));
				if($device['device_id'] > 0 && $device['user_id'] > 0) {
					$r = $this->loginUser($device['user_id']);
					if($r == 1) { $web->redirect($web->getUrl()); }
				}
			}
		}
	}

	public function loginUser($user_id, $loginType = "web") {
		// Device
		if($this->getUserInfo($user_id, "user_login_type") == $loginType) {

			$s = $this->registerUserDevice($user_id);
			$r = $this->updateDevice($user_id, $s);
			if($r == 1) {
				// Sessions
				$this->destroySessions();
				$this->createSessions();
				$this->deleteSession("user_id");
				$this->updateSession("user_id", $user_id);
				$this->deleteSession("device");
				$this->updateSession("device", $s);

				// User Update
				$this->updateUserInfo($user_id, array(
					"user_timestamp_login" => time(),
					"user_timestamp_active" => time()
				));

				return 1; // User Logged
			}
			else return 0;
		}
		else return 0;;
	}

	public function logoutUser($user_id) {
		// Database user logout time update
		$this->updateUserInfo($user_id, array(
			"user_timestamp_active" => time()
		));

		// Cookies
		if (isset($_COOKIE['dwe7autologinKey'])) {
			setcookie("dwe7autologinKey", null, -1, "/");
			unset($_COOKIE['dwe7autologinKey']);
		}
		if (isset($_COOKIE['dwe7autologinUser'])) {
			setcookie("dwe7autologinUser", null, -1, "/");
			unset($_COOKIE['dwe7autologinUser']);
		}

		// Sessions
		$this->deleteSession("user_id");
		$this->deleteSession("device");
		if(isset($_SESSION['access_token'])) unset($_SESSION['access_token']);
		$this->destroySessions();
	}

	// Rights
	public function setUserDefaultRights($user_id) {
		$rights = array(null);
		for($i = 1;$i < $this->getUserRightsCount();$i++) {
		  $rights[] = "a";
		}
		$this->updateUserInfo($user_id, array("user_rights_detail" => implode(".",$rights)));
	}

	public function setUserGroupDefaultRights($userGroup_id) {
		$rights = array(null);
		for($i = 1;$i < $this->getUserRightsCount();$i++) {
		  $rights[] = "a";
		}
		$this->updateUserGroup($userGroup_id, array("usergroup_rights" => implode(".",$rights)));
	}

	public function getUserRightsCount() {
		return count($this->rights_table);
	}
	
	public function getUserRightsTable() {
		return $this->rights_table;
	}
	
	private function getUserRightsString($user_id) {
		global $db;
		return @$db->select("users", array("user_id" => $user_id), "user_rights_detail");
	}

	private function getDefaultRightsString() {
		return ".a";
	}

	public function getUserRights($user_id, $rights_id = 'rs') {
		$rs = $this->getUserRightsString($user_id);
		$r = explode(".", $rs);
		if($rights_id == "rs") return $rs; // return (string)rightsstring
		else {
			if(isset($r[$rights_id])) return $r[$rights_id]; // return (string)*char
			else return null;	
		}		
	}

	public function getUserGroupRights($usergroup_id, $rights_id = 'rs') {
		$rs = $this->getUserGroupInfo($usergroup_id, "usergroup_rights");
		$r = explode(".", $rs);
		if($rights_id == "rs") return $rs; // return (string)rightsstring
		else {
			if(isset($r[$rights_id])) return $r[$rights_id]; // return (string)*char
			else return null;	
		}	
	}

	public function isUserHasRights($user_id, $rights_id, $rights_need) {
		$hasRights = false;
		$a = range("a", "z");

		$r = $this->getUserRights($user_id, $rights_id);

		if($r == "z") $hasRights = true;
		else if($r == null) $hasRights = false;

		// User Rights
		if(array_search($rights_need, $a) <= array_search($r, $a)) $hasRights = true;
		// User Group Rights
		$ug = $this->getUserGroups($user_id);
		if(count($ug) > 0) {
			for($i = 0;$i < count($ug);$i ++) {
					if(array_search($rights_need, $a) <= array_search($this->getUserGroupRights($ug[$i], $rights_id), $a)) {
						$hasRights = true;
						break;
					}
			}
		}		
		return $hasRights;
	}

	// Sessions
	private function createSessions() {
		session_start();
		$this->updateSession("user_timestamp", time());
	}

	private function getSession($name) {
		$prefix = "dwe7";
		$val = (isset($_SESSION[$prefix."_".$name]) ? $_SESSION[$prefix."_".$name] : 0);
		return $val;
	}

	private function updateSession($name, $value) {
		$prefix = "dwe7";
		$_SESSION[$prefix."_".$name] = $value;
	}

	private function deleteSession($name) {
		$prefix = "dwe7";
		if(isset($_SESSION[$prefix."_".$name])) unset($_SESSION[$prefix."_".$name]);
	}
	
	private function destroySessions() {
		$opt = array_keys($_SESSION);
		for($i = 0;$i < count($opt);$i++) unset($_SESSION[$opt[$i]]);
		session_destroy();
	}

	// Devices
	public function getUserDeviceAuthKey($user_id) {
		if($this->isUserLogged()) {
			if($this->loggedUser() == $user_id) {
				return @$this->getSession("device");
			}
			else return null;
		}
		else return null;
	}

	public function countUserDevices($user_id) {
		global $db;
		return @$db->selectRows("devices", array("user_id" => $user_id));
	}

	public function getUserDevice($user_id) {
		global $db;
		$s = $db->selectAll("devices", array("user_id" => $user_id), 'array', 'order by `device_timestamp_active` DESC');
		if(isset($s) && !empty($s) && count($s) > 0) {
			return $s;
		}
		else return null;
	}
	
	public function getUserActualDevice($user_id) {
		if($this->isUserLogged()) {
			if($this->loggedUser() == $user_id) {
				global $db;
				$devices = $this->getUserDevice($user_id);
				$auths = null;
				foreach($devices as $d) $auths[] = $d['device_auth_key'];
				$a = array_search($this->getUserDeviceAuthKey($this->loggedUser()), $auths);
				if($a > 0 && $a != false) {
					$device_id = $this->getUserDeviceIDByDeviceKey($user_id, $auths[$a]);
					//$device = $this->getUserDeviceData($device_id);
					return $device_id;
				}
				else return null;
			}
		}
	}

	private function getUserDeviceIDByDeviceKey($user_id, $device_key) {
		global $db;
		if($user_id != -1) { return @$db->select("devices", array('user_id' => $user_id, 'device_auth_key' => $device_key), 'device_id'); }
		else { return @$db->select("devices", array('device_auth_key' => $device_key), 'device_id'); }
	}
	
	public function getUserDeviceData($device_id) {
		global $db;
		return @$db->select("devices", array('device_id' => $device_id));
	}
	
	public function deleteDevice($device_id, $user_id) {
		global $db;
		return @$db->delete("devices", array("device_id" => $device_id, "user_id" => $user_id));
	}

	public function updateDeviceData($device_id, $user_id, $data = array()) {
		global $db;
		return @$db->update("devices", $data, array("device_id" => $device_id, "user_id" => $user_id));
	}

	public function updateDevice($user_id, $auth) {
		global $db;
		$s = $db->select('devices', array('user_id' => $user_id, 'device_auth_key' => $auth));
		if(isset($s) && !empty($s)) {
			if($s['device_status'] == 2) return -1;

			$db->update("devices", array(
				"device_ip" => $this->getIP(),	
				"session_id" => session_id(),
				"device_agent" => $_SERVER['HTTP_USER_AGENT'],
				"device_timestamp_active" => time()
			), array("device_id" => $s['device_id']));
			return 1;
		}
		else return 0;
	}

	private function registerUserDevice($user_id) {
		global $db, $web;
		if($this->isUserIDExists($user_id) != null) {
			$k = $web->generateKey(20);
			$s = $db->select('devices', array('user_id' => $user_id, 'device_agent' => $_SERVER['HTTP_USER_AGENT']));
			if(isset($s) && !empty($s)) {
				$db->update("devices", array(
					"device_ip" => $this->getIP(),
					"session_id" => session_id(),
					"device_auth_key" => $k, 
					"device_timestamp_active" => time()
				), array("device_id" => $s['device_id']));
				return $k;
			}
			else {
				$db->insert("devices", array(
					"device_ip" => $this->getIP(),
					"device_agent" => $_SERVER['HTTP_USER_AGENT'],
					"session_id" => session_id(),
					"device_auth_key" => $k, 
					"device_timestamp_register" => time(),
					"device_timestamp_active" => time(),
					"user_id" => $user_id,
					"device_status" => "0"
				));
			}
			return $k;
		}
		else return null;
	}
	
	// Debug
	public function displaySessions() {
		if(is_set($_SESSION)) print_r($_SESSION);
		else null;
	}
}
?>