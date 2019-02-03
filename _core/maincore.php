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
* File: maincore.php
* Filepath: /_core/maincore.php
*/
if(preg_match("/_core\/maincore.php/i", $_SERVER["PHP_SELF"])) { die(); }

ob_start();

// BASE DIR
$folder_level = ""; 
$i = 0;
while(!file_exists($folder_level."index.php")) {
	$folder_level .= "../"; 
	$i ++;
	if ($i == 7) { die("index.php file not found"); }
}

// Main definitions
define("AUTHORIZED", 		true);
define("BASEDIR",         	$folder_level);
define("BUILD_START",   	microtime(true));

// Dir definitions
define("DIR_CORE",    		BASEDIR."_core/");
define("DIR_LOGS",    		BASEDIR."_core/logs/");
define("DIR_LANGS",    		BASEDIR."_core/langs/");
define("DIR_INC",    		BASEDIR."_core/includes/");
define("DIR_BACKUPS", 		BASEDIR."_core/backup/");
define("DIR_ASSETS",  		BASEDIR."assets/");
define("DIR_THEMES",  		BASEDIR."themes/");
define("DIR_PAGES",   		BASEDIR."pages/");
define("DIR_PLUGINS",  		BASEDIR."plugins/");
define("DIR_UPLOADS",  		BASEDIR."uploads/");
define("DIR_TEMP",  		BASEDIR."temp/");
define("DIR_ADMIN",			BASEDIR."admin/");
define("DIR_ADMIN_PAGES",	BASEDIR."admin/pages/");
define("DIR_ADMIN_PLUGINS",	BASEDIR."admin/plugins/");

// Definitions
define("USER_TIME_ONLINE", 		120); // 2 min - User online
define("USER_TIME_AFK", 		600); // 10 min - Move user to afk
define("USER_TIME_REPORT", 		300); // 1 min - report next


define("KEY_ACCOUNT_ACTIVE", 	86400); // 1 day - Account active key, lifetime
define("KEY_ACCOUNT_EMAIL_C", 	86400); // 1 day - Account email change, lifetime
define("KEY_ACCOUNT_PASS_C", 	86400); // 1 day - Account password change, lifetime
define("KEY_ADMIN_AUTH", 		300); // 5 min - Admin Auth key, lifetime
define("KEY_ADMIN_AID", 		3600); // 1 hour - AdminID, lifetime

define("CLEAR_UP___CAN_REMOVE_DEVICE", 	2592000); // 30 days - Remove old devices
define("CLEAR_UP___CAN_REMOVE_LOGS", 	604800); // 7 days - Remove old logs

// Include main files
if(file_exists(DIR_CORE."config.php")) require_once DIR_CORE."config.php";
require_once DIR_CORE."functions.php";

// PHP Settings
ini_set("error_reporting", 	E_ALL);
ini_set("display_errors", 	"On");

// Class loading
function DWE7ClassLoader($class) {
    $path = DIR_CORE."classes/".$class.".class.php";
    if(file_exists($path) && filesize($path) > 0) require_once $path;
    else die("Class was not found : ".$path."");
}
//spl_autoload_register("DWE7ClassLoader");

/* DWE7 AutoLoader*/
DWE7ClassLoader("Database");
DWE7ClassLoader("Cache");
DWE7ClassLoader("Web");
DWE7ClassLoader("Display");
DWE7ClassLoader("User");
DWE7ClassLoader("Language");
DWE7ClassLoader("Links");
DWE7ClassLoader("Content");
DWE7ClassLoader("Log");
DWE7ClassLoader("Cron");
DWE7ClassLoader("Backup");
DWE7ClassLoader("Debug");
DWE7ClassLoader("WebControl");

$SYSTEM_PAGES = array("news", "error", "activate", "login", "logout", "password", "register", "home", "redirect", "profile", "groups", "settings", "messages", "administration");
$SYSTEM_ADMIN_PAGES = array("auth", "test", "dashboard");
$SYSTEM_LANGS = array("English", "Czech");
$SYSTEM_LANG_CODES = array("English", "cs" => "Czech", "cz" => "Czech");

$PROHIBITED_URI = array("_core", "admin", "plugins", "assets", "cache", "pages", "themes", "uploads", "setup", "maintenance", "cronmanager");
$PROHIBITED_URI = array_merge($PROHIBITED_URI, $SYSTEM_PAGES);

$ADMIN_ALLOWED_DESIGNS = array("skin-blue", "skin-black", "skin-red", "skin-yellow", "skin-green", "skin-purple");

// Database setup
if(isset($dbconf)) {
	$db = new Database($dbconf["DATABASE_SERVER"], $dbconf["DATABASE_USER"], $dbconf["DATABASE_PASS"], $dbconf["DATABASE_NAME"], $dbconf["DATABASE_PREFIX"]);
	unset($dbconf);
}

// Cache setup
$cache = new Cache();
// Web setup
$web = new Web();
// Display setup
$display = new Display();
// User setup
$user = new User();
// Language setup
$lang = new Language();
// Links setup
$links = new Links();
// Content setup
$content = new Content();
// Log setup
$log = new Log();
// Crons setup 
$cron = new Cron();
// Backup setup 
$backup = new Backup();
// Debug setup
$debug = new Debug();
// WebDeffender
$control = new WebControl();

if($db instanceof Database) {
	/* Load All Crons */
	$cron->getCronsLink();
	/* Load All Plugins */
	$web->loadPlugins();
}
// Debug
$debug->log("Core Loaded.");

// Latte
$debug->log("Latte module loaded.");

// DWE Setup
if(!($db instanceof Database)) {
	$last = explode("/", $_SERVER['PHP_SELF']);
	if(strtolower($last[count($last)-1]) != "setup.php") {
		if(file_exists(BASEDIR."setup.php")) header("location: ".BASEDIR."setup.php");
		else die("DWE Setup is missing.");
	}
}

if($db instanceof Database) {
	/* Mainterance */
	if($web->getSettings("web:maintenanceLevel") == "a" && strtolower($web->getUrlParam(1)) == "maintenance.php") $web->redirect($web->getUrl());
	else if($web->getSettings("web:maintenanceLevel") != null && $web->getSettings("web:maintenanceLevel") != "a" && strtolower($web->getUrlParam(1)) != "maintenance.php") {
		if($user->isUserLogged()) {
			if($user->isUserHasRights($user->loggedUser(), 1, $web->getSettings("web:maintenanceLevel")) == false) { 
				$web->redirect($web->getUrl("maintenance.php"));	
			}
		}
		else $web->redirect($web->getUrl("maintenance.php"));		
	}

	/* User Active */
	if($user->isUserLogged()) { 
		$user->updateUserInfo($user->loggedUser(), array("user_timestamp_active" => time()));
		$r = $user->updateDevice($user->loggedUser(), $user->getUserDeviceAuthKey($user->loggedUser()));
		if($r != 1) $web->redirect($web->getUrl("logout"));

	}
}
?> 