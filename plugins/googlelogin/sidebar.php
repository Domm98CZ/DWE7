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
* File: sidebar.php
* Filepath: /plugins/googlelogin/sidebar.php
*/
if(!defined("AUTHORIZED")) { die("Access Denied"); }
require_once "config.php";
include DIR_INC."PluginHeader.php";

// 191386854352-0hm27cdu7t357iik27v1c33hk1ke6el6.apps.googleusercontent.com
// SPPPGQ-jrlDovR-eLSJFJ_ez

if(!$user->isUserLogged()) {

	require_once 'google-api-php-client/src/Google/autoload.php';

	$clientID = $web->getPluginSettings($plugin['plugin_dir'], "clientid");
	$clientSecret = $web->getPluginSettings($plugin['plugin_dir'], "clientsecret");

	if(isset($clientID) && isset($clientSecret) && !empty($clientID) && !empty($clientSecret)) {
		$client = new Google_Client();
		$client->setApplicationName("DWE7Login :: ".$web->getSettings("web:title"));
		$client->setClientId($clientID);
		$client->setClientSecret($clientSecret);
		$client->setRedirectUri($web->getUrl("googlelogin"));
		$client->addScope("email");
		$client->addScope("profile");

		if (isset($_GET['code'])) {
		  $client->authenticate($_GET['code']);
		  $_SESSION['access_token'] = $client->getAccessToken();
		  $web->redirect($web->getUrl());
		}

		if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
		  $client->setAccessToken($_SESSION['access_token']);
		} else {
		  $authUrl = $client->createAuthUrl();
		}

		if ($client->getAccessToken()) {
		  $_SESSION['access_token'] = $client->getAccessToken();
		  $token_data = $client->verifyIdToken()->getAttributes();
		}

		if (isset($authUrl)) {
			?><a href="<?php echo $authUrl;?>" class="btn btn-flat btn-block btn-danger"><?php echo $lang->getLocale('PLUGIN_GOOGLELOGIN_B1');?>&nbsp;<i class="fa fa-google-plus"></i></a><br><?php
		} else {
			$user_id = $user->isEmailUsed($token_data['payload']['email']);
			$u = $user->getUserInfo($user_id);
			if($u['user_id'] > 0 && strtolower($u['user_login_type']) == "googlelogin") {
				$user->loginUser($u['user_id'], "googlelogin");
			}
			else if($u['user_id'] > 0 && strtolower($u['user_login_type']) != "googlelogin") {
				$user->logoutUser($user->loggedUser());	
				$web->redirect($web->getUrl("login"));
			}
			else {
				$user_id = $user->createUser($token_data['payload']['email'], $web->generateKey(50), $token_data['payload']['email'], $web->generateKey(50), "1", "googlelogin");
				$user->setUserDefaultRights($user_id);
				$user->loginUser($user_id, "googlelogin");
			}
		}
	}
}

