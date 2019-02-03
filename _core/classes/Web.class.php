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
* File: Web.class.php
* Filepath: /_core/classes/Web.class.php
*/

if(!defined("AUTHORIZED")) { die("Access Denied"); }

class Web {
	// config variables
	private $settings			= null;
	// non-config variables
	private $WEB_TITLE			= null;
	private $WEB_HEAD			= null;
	private $WEB_JS				= null;
	private $WEB_SCRIPTS		= null;
	private $WEB_VERSION		= null;
	// config main webUrl
	private $mainWebUrl = "http://dwe7main.domm98.cz";
	private $mainWebFactoryUrl = "http://dwe7main.domm98.cz/factory";

	public function __construct() {
		global $db;
		// fill up config
		$this->settings = array();

		if ($db instanceof Database) {
			$set = @$db->selectAll("settings");
			if(count($set) > 0) {
				for($i = 0;$i < count($set);$i ++) {
					$this->settings[$set[$i]['settings_name']] = $set[$i]['settings_value'];
				}
			} 
		}
		$this->clearTitle();
		$this->fileVersionCheck();
	}

	public function getCronLastStart($cronName) {
		global $cron;
		return @$cron->getCronLastStart($cronName);
	}

	public function loadPlugins() {
		global $db;
		$plugins = $db->selectAll("plugins");
		if(isset($plugins) && count($plugins) > 0) {
			for($i = 0;$i < count($plugins);$i ++) {
				if(file_exists(DIR_PLUGINS.$plugins[$i]['plugin_dir']."/plugin.php")) {
					require_once DIR_PLUGINS.$plugins[$i]['plugin_dir']."/plugin.php";
				}
			}
		}
	}

	public function updatePluginSettings($plugin, $settings, $value) {
		global $db;
		$this->updateSettings("plugin:".$plugin.":".$settings."", $value);
	}

	public function getPluginSettings($plugin, $settings) {
		global $db;
		return @$this->getSettings("plugin:".$plugin.":".$settings."");
	}

	public function getFullParamUrl() {
		if(isset($_SERVER['REQUEST_URI']) && !empty($_SERVER['REQUEST_URI'])) {
			return $_SERVER['REQUEST_URI'];
		}
		else return null;
	}

	public function serializeUrl($url) {
		$isSecure = false;
		if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
		    $isSecure = true;
		}
		elseif (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' || !empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on') {
		    $isSecure = true;
		}
		$REQUEST_PROTOCOL = $isSecure ? 'https' : 'http';
		return $REQUEST_PROTOCOL."://".$_SERVER["SERVER_NAME"]."/".str_replace("&", "&amp;", $url).((strpos($url, ".") === false) ? ((substr($url, -1) != "/") ? "/" : null) : null);
	}

	public function getParamUrlByParam($param_count) {
		if(isset($_SERVER['REQUEST_URI']) && !empty($_SERVER['REQUEST_URI'])) {
			$ex = explode("/", $_SERVER['REQUEST_URI']);
			$string = null;
			if($param_count <= count($ex)) {
				for($i = $param_count;$i < count($ex);$i ++) {
					$string .= "/".$ex[$i];
				}
				if(substr($string, 0, 1) == "/") $string = substr($string, 1);
				return $string;
			}
			else return null;
		}
		else return null;
	}

	public function getUrlParamCount() {
		if(isset($_SERVER['REQUEST_URI']) && !empty($_SERVER['REQUEST_URI'])) {
			$ex = explode("/", $_SERVER['REQUEST_URI']);
			if(count($ex) > 0) return count($ex);
			else return 0;
		}
		else return 0;
	}
	
	public function getUrlParam($param_count) {
		if(isset($_SERVER['REQUEST_URI']) && !empty($_SERVER['REQUEST_URI'])) {
			$ex = explode("/", $_SERVER['REQUEST_URI']);
			if(isset($ex[$param_count])) return $ex[$param_count];
			else return null;
		}
		else return null;
	}

	public function getActualUrl() {
		$urlString = null;
		$urlString .= $this->getSettings("web:protocol").$this->getSettings("web:url")."/";
		if(isset($_SERVER['REQUEST_URI']) && !empty($_SERVER['REQUEST_URI']) && $_SERVER['REQUEST_URI'] != "/") {
			$urlString .= substr($_SERVER['REQUEST_URI'], 1);;
		}
		return $urlString;
	}
	
	public function getActualUrlAndSetParams($params) {
		return $this->getActualUrl().str_replace("&", "&amp;", $params).((strpos($params, ".") === false) ? ((substr($params, -1) != "/") ? "/" : null) : null);	
	}

	private function showTimeDistance($ptime) {
		global $lang;
		$etime = time() - $ptime;
		if ($etime < 1) return $lang->getLocale('T_NOW');

		$a = array(
			365 * 24 * 60 * 60  => $lang->getLocale('T_B_YEAR'),
			30 * 24 * 60 * 60   => $lang->getLocale('T_B_MONTH'),
			24 * 60 * 60        => $lang->getLocale('T_DAY'),
			60 * 60             => $lang->getLocale('T_B_HOUR'),
			60                  => $lang->getLocale('T_B_MINUTE'),
			1                   => $lang->getLocale('T_B_SECOND')
		);

		$a_plural = array( 
			$lang->getLocale('T_B_YEAR') 	=> $lang->getLocale('T_B_YEAR_P'),
			$lang->getLocale('T_B_MONTH') 	=> $lang->getLocale('T_B_MONTH_P'),
			$lang->getLocale('T_DAY') 		=> $lang->getLocale('T_B_DAY_P'),
			$lang->getLocale('T_B_HOUR') 	=> $lang->getLocale('T_B_HOUR_P'),
			$lang->getLocale('T_B_MINUTE') 	=> $lang->getLocale('T_B_MINUTE_P'),
			$lang->getLocale('T_B_SECOND') 	=> $lang->getLocale('T_B_SECOND_P')
		);

		foreach ($a as $secs => $str) {
			$d = $etime / $secs;
			if ($d >= 1) {
				$r = round($d);
				return $lang->getLocale('T_BEFORE')." ".$r." ".($r > 1 ? $a_plural[$str] : $str);
			}
		}
	}

	public function showToolTip($in_tooltiltext, $tooltiptext, $pos = "bottom") {
		$string = null;
		$string = "<span data-toggle='tooltip' data-placement='".$pos."' title='".$tooltiptext."'>".$in_tooltiltext."</span>";
		return $string; 	
	}

	public function showTimeBefore($time, $pos = "bottom") {
		return $this->showToolTip($this->showTimeDistance($time), $this->showTime($time));
	}

	public function showTime($time, $format = "d. m. Y H:i") {
		return date($format, $time);
	}

	public function getTitle() {
		return $this->getSettings("web:title").(!empty($this->WEB_TITLE) ? $this->WEB_TITLE : null);
	}
	
	public function getPageTitle() {
		return $this->getSettings("web:title");
	}
	
	public function addToTitle($text) {
		$this->WEB_TITLE .= $text;
	}
	
	public function clearTitle() {
		$this->WEB_TITLE = null;
	}

	public function getUrl($params = null) {
		if(strlen($this->getSettings("web:url")) > 0) {
			if(empty($params)) return $this->getSettings("web:protocol").$this->getSettings("web:url")."/";
			else if (!filter_var($params, FILTER_VALIDATE_URL) === false) return $this->getSettings("web:protocol").$this->getSettings("web:url")."/"."redirect/".$params;
			else return $this->getSettings("web:protocol").$this->getSettings("web:url")."/".str_replace("&", "&amp;", $params).((strpos($params, ".") === false) ? ((substr($params, -1) != "/") ? "/" : null) : null);
		}
		else return str_replace("&", "&amp;", $params).((strpos($params, ".") === false) ? ((substr($params, -1) != "/") ? "/" : null) : null);
	}

	public function getEmail() {
		return $this->getSettings("web:email");
	}

	public function getSettings($settings_name) {
		if(isset($this->settings[$settings_name])) return $this->settings[$settings_name];
		else return null;
	}

	public function updateSettings($settings_name, $settings_value) {
		global $db;

		if(isset($this->settings[$settings_name])) $this->settings[$settings_name] = $settings_value;

		$s = $db->select("settings", array("settings_name" => $settings_name), 'settings_id');
		if($s > 0) return @$db->update("settings", array("settings_value" => $settings_value), array("settings_name" => $settings_name));
		else return @$db->insert("settings", array("settings_name" => $settings_name, "settings_value" => $settings_value));
	}

	public function updateUserSettings($user_id, $settings, $value) {
		global $db;
		$s = $db->select("userSettings", array("userSettings_name" => $settings, "user_id" => $user_id), "user_id");
		if($s == $user_id) return @$db->update("userSettings", array("userSettings_value" => $value), array("userSettings_name" => $settings, "user_id" => $user_id));
		else return @$db->insert("userSettings", array("userSettings_name" => $settings, "userSettings_value" => $value, "user_id" => $user_id));
	}

	public function getRenderTime() {
		return number_format(((microtime(TRUE) - BUILD_START)), 2);
	}

	public function redirect($url, $time = 0) {
		$url = str_replace("&amp;", "&", $url);
		if($time > 0) {
			$this->addAditionalJavascript("setTimeout(function(){window.location.href = \"".$url."\";}, ".($time * 1000).");");
		}
		else { 
			header("location: ".$url."");
			die(); 
		}
	}

	public function addToHead($script) {
		$this->WEB_HEAD[] .= $script;
	}

	public function clearHead() {
		$this->WEB_HEAD = null;
	}

	public function clearAdditionalJavascript() {
		$this->WEB_JS = null;
	}

	public function clearAdditionalScripts() {
		$this->WEB_SCRIPTS = null;
	}
	
	public function addAditionalJavascript($script) {
		$this->WEB_JS .= $script;
	}
	
	public function addAditionalScripts($script) {
		$this->WEB_SCRIPTS[] = $script;
	}
	
	public function getAdditionalScripts() {
		if(isset($this->WEB_SCRIPTS) && count($this->WEB_SCRIPTS) > 0) {
			$string = null;
			for($i = 0;$i < count($this->WEB_SCRIPTS);$i ++) {
				$string .= $this->WEB_SCRIPTS[$i];
			}
			return $string;
		}
		else return null;
	}

	public function getAdditionalJavascript() {
		if(isset($this->WEB_JS) && !empty($this->WEB_JS) && strlen($this->WEB_JS) > 0) return "<script text='text/javascript'>".$this->WEB_JS."</script>";
		else return null;
	}
	
	public function getAdditionalHead() {
		if(isset($this->WEB_HEAD) && count($this->WEB_HEAD) > 0) {
			$string = null;
			for($i = 0;$i < count($this->WEB_HEAD);$i ++) {
				$string .= $this->WEB_HEAD[$i];
			}
			return $string;
		}
		else return null;
	}
	public function buildMail($type = 'custom', $text = null) {
		global $lang;

		$e_string = null;
		if($type != "custom") {
			switch($type) {
				case "E_REGISTER": 
					$e_string = $lang->getLocale("E_EMAIL_REGISTER");
					break;
				case "E_ACTIVATE":
					$e_string = $lang->getLocale("E_ACTIVATE_ACCOUNT");
					break;
				case "E_ACTIVATED":
					$e_string = $lang->getLocale("E_ACTIVATED_ACCOUNT");
					break;
				case "E_PASSWORD":
					$e_string = $lang->getLocale("E_PASSWORD_LOST");
					break;
				case "E_PASSWORD_CHANGE":
					$e_string = $lang->getLocale("E_PASSWORD_CHANGE");
					break;
				case "E_PASSWORD_CLOSE":
					$e_string = $lang->getLocale("E_PASSWORD_CLOSE");
					break;
				case "E_EMAIL_CHANGE":
					$e_string = $lang->getLocale("E_EMAIL_CHANGE");
					break;
				case "E_EMAIL_CHANGED":
					$e_string = $lang->getLocale("E_EMAIL_CHANGED");
					break;
				case "E_PASS_CHANGE":
					$e_string = $lang->getLocale("E_PASS_CHANGE");
					break;
				case "E_PASS_CHANGED":
					$e_string = $lang->getLocale("E_PASS_CHANGED");
					break;
				case "U_USER_BLOCK":
					$e_string = $lang->getLocale("E_EMAIL_ACCOUNT_BLOCK");
					break;
				case "U_USER_REMOVE":
					$e_string = $lang->getLocale("E_EMAIL_ACCOUNT_REMOVE");
					break;
				default:break;
			}
		}
		else $e_string = $text;

		$e_string .= "<br><br>";
		$e_string .= sprintf($lang->getLocale("E_EMAIL_END"), $this->getPageTitle());

		return $e_string;
	}

	public function sendmail($email, $subject, $msg) {
	  $email_string = null;
	  $email_string = "
	  <html>
	    <head>
	      <title>".$subject."</title>
	    </head>
	    <body>
	      ".$msg."
	    </body>
	  </html>";
	  
	  $headers = null;
	  $headers .= "MIME-Version: 1.0\r\n";
	  $headers .= "Content-type:text/html;charset=UTF-8\r\n";
	  $headers .= "To: ".$email." <".$email.">\r\n";
	  $headers .= "From: ".$this->getPageTitle()." <".$this->getEmail().">\r\n";
	  mail($email, $subject, $email_string, $headers);
	}

	/* Messages */
	public function isMessageExists($message_id) {
		global $db;
		return @$db->select("messages", array("message_id" => $message_id), 'message_id');
	}

	public function getMessageData($message_id) {
		global $db;
		return @$db->select("messages", array("message_id" => $message_id));
	}

	public function createMessage($data = array()) {
		global $db;
		return @$db->insert("messages", $data);
	}

	public function updateMessageData($message_id, $data = array()) {
		global $db;
		return @$db->update("messages", $data, array("message_id" => $message_id));
	}

	/* Keys */
	public function generateKey($lenght = 20) {
		$letters = array();
		
		$letters_a = array();
		$letters_b = array();
		$letters_c = array();

		$letters_a = range("a", "z");
		$letters_b = range("A", "Z");
		$letters_c = range(0, 9);

		for($i = 0;$i < count($letters_a);$i ++) $letters[] = $letters_a[$i];  
  		for($i = 0;$i < count($letters_b);$i ++) $letters[] = $letters_b[$i];  
  		for($i = 0;$i < count($letters_c);$i ++) $letters[] = $letters_c[$i];  

		$key = null;
		for($i = 0;$i < $lenght;$i ++) {
			$key .= $letters[rand(0, count($letters)-1)];
		}

		return $key;
	}

	public function deleteKey($key_id) {
		global $db;
		return @$db->delete("keys", array("key_id" => $key_id));
	}

	public function updateKey($key_id, $data = array()) {
		global $db;
		return @$db->update("keys", $data, array("key_id" => $key_id));
	}

	public function registerKey($key_type, $user_id = 0, $key = null) {
		global $db;
		return @$db->insert("keys", array(
			"key" => (is_set($key) ? $key : $this->generateKey()),
			"key_time" => time(),
			"key_type" => $key_type,
			"user_id" => $user_id
		));
	}
	
	public function registerKeyWithData($key_type, $user_id, $key_data, $key = null) {
		global $db;
		return @$db->insert("keys", array(
			"key" => (is_set($key) ? $key : $this->generateKey()),
			"key_time" => time(),
			"key_type" => $key_type,
			"key_data" => $key_data,
			"user_id" => $user_id
		));
	}

	public function getKey($key_id) {
		global $db;
		return @$db->select("keys", array("key_id" => $key_id), 'key');
	}

	public function getKeyID($key) {
		global $db;
		return @$db->select("keys", array("key" => $key), 'key_id');
	}

	public function getKeyInfo($key_id) {
		global $db;
		return @$db->select("keys", array("key_id" => $key_id));
	}
	
	public function findKeyID($key_data = array()) {
		global $db;
		return @$db->select("keys", $key_data, 'key_id');
	}

	/* Reports */ 
	public function insertReport($data = array()) {
		global $db;
		return @$db->insert("reports", $data);
	}
	
	public function getReports($limit = 0, $type = -1) {
		global $db;
		if($type == -1) return @$db->selectAll("reports", null, "array", "order by `report_id` desc ".(($limit > 0) ? "limit ".$limit : null));
		else return @$db->selectAll("reports", array("report_type" => $type), "array", "order by `report_id` desc ".(($limit > 0) ? "limit ".$limit : null));
	}

	public function getLastUserReport($user_id, $report_type = null) {
		global $db;
		return @$db->select("reports", array("user_id" => $user_id, "report_type" => $report_type), "array", "order by `report_id` desc");
	}

	public function getReport($report_id, $report_type) {
		global $db;
		return @$db->select("reports", array("report_id" => $report_id, "report_type" => $report_type));
	}
	
	public function findReport($report_id) {
		global $db;
		return @$db->select("reports", array("report_id" => $report_id)); 
	}

	/* Bans */
	public function addBan($data = array()) {
		global $db;
		return @$db->insert("bans", $data);
	}

	public function checkForIPBan($user_ip) {

	}

	/* Files function */
	public function countDirFiles($dir, $filesOnly = 0) {
		$filesCount = null;
		if(is_dir($dir)) {
			if ($dh = opendir($dir)){
			    while (($file = readdir($dh)) !== false){
			    	if($file != "." && $file != "..") {	
			    		if($filesOnly == 1) {
			    			 if(filetype($dir.$file) == "file") $filesCount++;
			    		}
		    			else $filesCount++;
			    	}
			    }
			    closedir($dh);	
			    return $filesCount;
			}
		}
		else return 0;
	}

	public function getDirFiles($dir, $filesOnly = 0) {
		$fileArray = null;
		if(is_dir($dir)) {
			if ($dh = opendir($dir)){
			    while (($file = readdir($dh)) !== false){
			    	if($file != "." && $file != ".." && strtolower($file) != ".htaccess" && strtolower($file) != ".htpasswd") {
			    		if($filesOnly == 1) {
			    			 if(filetype($dir.$file) == "file") $fileArray[] = $file;
			    		}
		    			else $fileArray[] = $file;
			    	}
			    }
			    closedir($dh);	
			    return $fileArray;
			}
		}
		else return null;
	}


	public function decode_browser($user_agent)
	{
	  $browser =array();
	  $agent=$user_agent;
	  if(stripos($agent,"firefox")!==false){
	    $browser['browser'] = 'Firefox'; // Set Browser Name
	    $domain = stristr($agent, 'Firefox');
	    $split =explode('/',$domain);
	    $browser['version'] = $split[1]; // Set Browser Version
	  }
	  if(stripos($agent,"Opera")!==false){
	    $browser['browser'] = 'Opera'; // Set Browser Name
	    $domain = stristr($agent, 'Version');
	    $split =explode('/',$domain);
	    $browser['version'] = $split[1]; // Set Browser Version
	  }
	  if(stripos($agent,"MSIE")!==false){
	    $browser['browser'] = 'Internet Explorer'; // Set Browser Name
	    $domain = stristr($agent, 'MSIE');
	    $split =explode(' ',$domain);
	    $browser['version'] = $split[1]; // Set Browser Version
	  }
	  if(stripos($agent,"Chrome")!==false){
	    $browser['browser'] = 'Chrome'; // Set Browser Name
	    $domain = stristr($agent, 'Chrome');
	    $split1 =explode('/',$domain);
	    $split =explode(' ',$split1[1]);
	    $browser['version'] = $split[0]; // Set Browser Version
	  }
	  else if(stripos($agent,"Safari")!==false){
	    $browser['browser'] = 'Safari'; // Set Browser Name
	    $domain = stristr($agent, 'Version');
	    $split1 =explode('/',$domain);
	    $split =explode(' ',$split1[1]);
	    $browser['version'] = $split[0]; // Set Browser Version
	  }
	  return $browser['browser']." ".$browser['version'];
	} 

	public function decode_os($user_agent)
	{
	  $os_platform = "Neznámý operační systém";
	  $os_array = array(
	    /* Other */
	    "/linux/i"              =>  "Linux",
	    "/Unix/i"               =>  "Unix",
	    "/macintosh|mac os x/i" =>  "Mac OS",  
	    /* Windows PC */
	    "/windows/i"            =>  "Windows",
	    "/windows nt 10.0/i"    =>  "Windows 10",
	    "/windows nt 6.3/i"     =>  "Windows 8.1",
	    "/windows nt 6.2/i"     =>  "Windows 8",
	    "/windows nt 6.1/i"     =>  "Windows 7",
	    "/windows nt 6.0/i"     =>  "Windows Vista",
	    /* Android */
	    "/android/i"            => "Android",
	    "/linux; android/i"     => "Android",
	    /* Windows Phone */
	    "/windows phone/i"      =>  "Windows Phone",
	    "/windows phone 10/i"   =>  "Windows Phone 10",
	    "/windows phone 8.1/i"  =>  "Windows Phone 8.1",
	    "/windows phone 8/i"    =>  "Windows Phone 8",
	    /* IOS */
	    "/iphone|ipad/i"        =>  "IOS",
	  );
	  foreach ($os_array as $regex => $value) if (preg_match($regex, $user_agent)) $os_platform = $value;   
	  return $os_platform;
	}

	/* Administration */
	public function getUserAdministrationAccess($user_id, $did, $aid) {
		$kID = $this->findKeyID(array(
			"key_type" => "admin:aid",
			"key" => $aid,
			"user_id" => $user_id,
			"key_data" => $did
		));

		if($kID > 0) {
			$k = $this->getKeyInfo($kID);
			if((time() - $k["key_time"]) < KEY_ADMIN_AID) {
				$this->updateKey($kID, array(
					"key_time" => time()
				));
				return 1;
			}
			else if((time() - $k["key_time"]) >= KEY_ADMIN_AID) {
				return 2;
			}
			else return 0;
		}
		else return 0;
	}

	/* Updates */
	// to do::UPDATES
	
	public function dwe7update($reupdate = false) {
		$updateVersion = $this->checkUpdates();
		if($updateVersion > $this->getDWEVersion() || $reupdate == true) {

			if(!file_exists(DIR_TEMP) || !is_dir(DIR_TEMP)) mkdir(DIR_TEMP);

			if(file_exists(DIR_TEMP.$updateVersion.".zip")) unlink(DIR_TEMP.$updateVersion.".zip");
			$this->getUpdateFile($updateVersion);

			$zip = new ZipArchive;
			$res = $zip->open(DIR_TEMP.$updateVersion.".zip");
			if ($res === TRUE) {
				$zip->extractTo(DIR_TEMP);
				$zip->close();

				if(file_exists(DIR_TEMP.$updateVersion."/dwe7update.php")) {
					require_once DIR_TEMP.$updateVersion."/dwe7update.php";

					$updateItems = null;
					$updateName = "[ ".$update["version"]." #".$update["build"]." ] - ".$update["name"];

					for($i = 0;$i < count($update["file"]);$i++) {

						if($update["file"][$i]['action'] = "UPDATE") {

							if(file_exists(BASEDIR.$update["file"][$i]['path'])) unlink(BASEDIR.$update["file"][$i]['path']);
							$fileString = file_get_contents(DIR_TEMP.$updateVersion."/".$update["file"][$i]['update']);

							$file = fopen(BASEDIR.$update["file"][$i]['path'], "w");
				            fwrite($file, $fileString);
				            fclose($file);

				            $updateItems[] = $update["file"][$i]['path']." - %%%UPDATED_FILEUPDATE%%%";

						}
						else if($update["file"][$i]['action'] = "DELETE") {
							
							if(file_exists(BASEDIR.$update["file"][$i]['path'])) unlink(BASEDIR.$update["file"][$i]['path']);

							$updateItems[] = $update["file"][$i]['path']." - %%%UPDATED_FILEDELETE%%%";

						}
						else if($update["file"][$i]['action'] = "CREATE") {
							
							$fileString = file_get_contents(DIR_TEMP.$updateVersion."/".$update["file"][$i]['update']);

							$file = fopen(BASEDIR.$update["file"][$i]['path'], "w");
				            fwrite($file, $fileString);
				            fclose($file);

				            $updateItems[] = $update["file"][$i]['path']." - %%%UPDATED_FILECREATE%%%";

						}
						else if($update["file"][$i]['action'] = "MKDIR") {
							
							if(!file_exists(BASEDIR.$update["file"][$i]['path'])) {
								mkdir(BASEDIR.$update["file"][$i]['path']);
								$updateItems[] = $update["file"][$i]['path']." - %%%UPDATED_DIRCREATED%%%";
							}

						}
						else if($update["file"][$i]['action'] = "RMDIR") {
							
							if(!file_exists(BASEDIR.$update["file"][$i]['path'])) {
								rrmdir(BASEDIR.$update["file"][$i]['path']);
								$updateItems[] = $update["file"][$i]['path']." - %%%UPDATED_DIRRM%%%";
							}

						}
						else if($update["file"][$i]['action'] = "RMRDIR") {
							
							if(!file_exists(BASEDIR.$update["file"][$i]['path'])) {
								rrmdir(BASEDIR.$update["file"][$i]['path']);
								$updateItems[] = $update["file"][$i]['path']." - %%%UPDATED_DIRRMR%%%";
							}

						}
						else if($update["file"][$i]['action'] = "RUN") {
							
							require_once DIR_TEMP.$updateVersion."/".$update["file"][$i]['update'];
	
							$updateItems[] = $update["file"][$i]['path']." - %%%UPDATED_UPDATESCRIPT%%%";
						}
					}

					// Clear after update
					//rrmdir(DIR_TEMP);


					return array($updateName, $updateItems);
				}
				else return -2;
			} 
			else return -1;

			//unlink(DIR_TEMP.$update.".zip");
		}
		else return 0;
	}

	public function doCheckUpdate() {
		global $cache;

		$newVersion = $this->checkUpdates();
		if($newVersion > 0) $cache->set("updateAvailable", $newVersion);
		else $cache->set("updateAvailable", "0");
	}

	private function checkUpdates() {		
		$lastVersion = $this->getLastestVersion();
		

		if (strpos($lastVersion, '#') !== false) {
			$version = explode("#", $lastVersion);

			if(floatval($this->getDWEVersion()) <= floatval($lastVersion)
			 || floatval($this->getDWEVersion()) == floatval($lastVersion) && $version[1] > $this->getDWEVersionBuild()) return floatval($lastVersion);
			else return 0;
		}
		else {
			if(floatval($this->getDWEVersion()) >= floatval($lastVersion)) return 0;
			else return floatval($lastVersion);
		}
	}

	private function getUpdateFile($fileversion) {
		$fh = fopen(DIR_TEMP.$fileversion.".zip", 'w');
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, ($this->mainWebFactoryUrl."/updates/getVersion.php?version=".$fileversion)); 
		curl_setopt($ch, CURLOPT_FILE, $fh); 
		//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_exec($ch);
		curl_close($ch);
		fclose($fh);
	}
	
	private function getLastestVersion() {
		return @$this->curlRequest($this->mainWebFactoryUrl."/updates/version.php");
	}

	private function getDWEVersion() {
		if(file_exists(DIR_CORE."version.dwe")) {
			$versionInfo = parse_ini_file(DIR_CORE."version.dwe");
			if(is_array($versionInfo)) {
				$version = $versionInfo['version'].".".$versionInfo['subversion'];
			}
		}
		return $version;
	}

	private function getDWEVersionBuild() {
		if(file_exists(DIR_CORE."version.dwe")) {
			$versionInfo = parse_ini_file(DIR_CORE."version.dwe");
			if(is_array($versionInfo)) {
				return $version['build'];
			}
		}	
	}
	
	private function curlRequest($url, $timeout = 10) {
		$data = null;

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER,TRUE);
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $timeout);
		curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)");
		curl_setopt($curl, CURLOPT_FAILONERROR, TRUE);
		//curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
		curl_setopt($curl, CURLOPT_AUTOREFERER, TRUE);
		curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
		$data = curl_exec($curl);
		curl_close($curl);

		return $data;
	}

	/* Instalation */
	public function websiteRegister() {
		$_key = $this->curlRequest($this->mainWebFactoryUrl."/register/website.php?url=".$_SERVER['REQUEST_SCHEME']."://".$_SERVER['HTTP_HOST']."&getAccess");
		return $_key;
	}

	public function websiteActive($key) {
		$_key = $this->curlRequest($this->mainWebFactoryUrl."/register/website.php?url=".$_SERVER['REQUEST_SCHEME']."://".$_SERVER['HTTP_HOST']."&key=".$key."&activeWebsite");
		return $_key;
	}

	public function createConfig($dbServer, $dbName, $dbUser, $dbPass, $dbPrefix, $webid) {
		if(!file_exists(DIR_CORE."config.php")) {
			file_put_contents(DIR_CORE."config.php", trim(
<<<CONFIG
<?php
if(!defined("AUTHORIZED")) { die("Access Denied"); }

// WebID
define("WEB_UID", "$webid"); 

// Database
\$dbconf["DATABASE_SERVER"] = "$dbServer";
\$dbconf["DATABASE_NAME"] = "$dbName";
\$dbconf["DATABASE_USER"] = "$dbUser";
\$dbconf["DATABASE_PASS"] = "$dbPass";   
\$dbconf["DATABASE_PREFIX"] = "$dbPrefix"; 
?>
CONFIG
));
		}
	}

	/* Version */
	private function setVersion($versionData = array()) {
		if(is_array($versionData)) {
			/*
			version = 7
			subversion = 0
			versionname = "alpha"
			build = 1
			*/
			if(file_exists(DIR_CORE."version.dwe")) unlink(DIR_CORE."version.dwe");
			file_put_contents(DIR_CORE."version.dwe", 
"version = ".$versionData['version']."
subversion = ".$versionData['subversion']."
versionname = \"".$versionData['versionname']."\"
build = ".$versionData['build']."");
		}
		else return 0;
	}

	public function getWebVersionAsString() {
		return $this->WEB_VERSION;
	}
	private function fileVersionCheck() {
		if(file_exists(DIR_CORE."version.dwe")) {
			$versionInfo = parse_ini_file(DIR_CORE."version.dwe");
			if(is_array($versionInfo)) {
				$this->WEB_VERSION = "DWE ".$versionInfo['version'].".".$versionInfo['subversion'].(($versionInfo['versionname'] != "public") ? "-".$versionInfo['versionname'].".".$versionInfo['build'] : null);
			}
			else $this->WEB_VERSION = "DWE 7";
			// to do	
		}
		else $this->WEB_VERSION = "DWE 7";
		// to do
	}
	public function getAdministrationName() {
		return "DWE7Admin";
	}
}
?>