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
* Filepath: /plugins/googleanalytics/sidebar.php
*/
if(!defined("AUTHORIZED")) { die("Access Denied"); }
require_once "config.php";
include DIR_INC."PluginHeader.php";
?>
<form method="post">
	<div class="form-group">
		<label for="l_googleanalytics_service_id">Service ID</label>
		<input type="text" name="googleanalytics_service_id" class="form-control" id="l_googleanalytics_service_id" value="<?php echo htmlentities($web->getPluginSettings($p['plugin_dir'], "ga-id"));?>">
	</div>  
  	<input type="submit" name="pluginGoogleAnalyticsSettingsSave" class="btn btn-success" value="<?php echo $lang->getLocale('ADMIN_MS_B1');?>">
</form>
<br>
<?php
if(@$_POST["pluginGoogleAnalyticsSettingsSave"]) {
	$web->updatePluginSettings($p['plugin_dir'], "ga-id", $content->clearUserInputAll($_POST["googleanalytics_service_id"]));
	echo $display->adminAlert($lang->getLocale("ADMIN_E_S"), $lang->getLocale("ADMIN_MS_OK"), "success"); 
	$web->redirect($web->getActualUrl(), 2);
}	
?>