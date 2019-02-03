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
* Filepath: /plugins/userpanel/sidebar.php
*/
if(!defined("AUTHORIZED")) { die("Access Denied"); }
include DIR_INC."PluginHeader.php";

if($user->isUserLogged()) {
	echo $display->createpanel(sprintf($lang->getLocale("PLUGIN_USERPANEL_TITLE_2"), htmlentities($user->getUserInfo($user->loggedUser(), 'user_display_name'))), "default");
	?>
	<ul class="list-unstyled">
		<li><a href="<?php echo $web->getUrl("settings");?>"><?php echo $lang->getLocale("PLUGIN_USERPANEL_L4");?></a></li>
		<li><a href="<?php echo $web->getUrl("messages");?>"><?php echo $lang->getLocale("PLUGIN_USERPANEL_L5");?></a></li>
		<?php
		if($user->isUserHasRights($user->loggedUser(), 2, "b")) {
			?><li><a href="<?php echo $web->getUrl("administration");?>"><?php echo $lang->getLocale("PLUGIN_USERPANEL_L6");?></a></li><?php
		} ?>
		<li><a href="<?php echo $web->getUrl("logout");?>" class="text-warning"><?php echo $lang->getLocale("PLUGIN_USERPANEL_L7");?></a></li>
	</ul>
	
	<?php
}
else {
	$user->tryAutoLoginUser();
	echo $display->createpanel($lang->getLocale("PLUGIN_USERPANEL_TITLE_1"), "default");
	?>
	<form method="post" action="<?php echo $web->getUrl("login");?>">
		<div class="form-group">
			<label><?php echo $lang->getLocale("PLUGIN_USERPANEL_L1");?></label>
			<input type="text" name="user_name" class="form-control" id="" value="">
		</div>
				
		<div class="form-group">
			<label><?php echo $lang->getLocale("PLUGIN_USERPANEL_L2");?></label>
			<input type="password" name="user_pass" class="form-control" id="" value="">
		</div>

		<div class="checkbox">
	    	<label><input name="user_auto_login" type="checkbox" value="<?php echo $lang->getLocale("PLUGIN_USERPANEL_L3");?>"> <?php echo $lang->getLocale("PLUGIN_USERPANEL_L3");?></label>
    	</div>

    	<div style="text-align:center;">
      		<a href="<?php echo $web->getUrl("register");?>" class="text-muted"><?php echo $lang->getLocale("PLUGIN_USERPANEL_T1");?></a><br>
      		<a href="<?php echo $web->getUrl("password");?>" class="text-warning"><?php echo $lang->getLocale("PLUGIN_USERPANEL_T2");?></a><br>
    		<input class="btn btn-success" type="submit" name="login" value="<?php echo $lang->getLocale("PLUGIN_USERPANEL_B1");?>">
    	</div>
	</form>
	<?php
}
echo $display->closepanel();
?>