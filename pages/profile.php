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
* File: profile.php
* Filepath: /pages/profile.php
*/
if(!defined("AUTHORIZED")) { die("Access Denied"); }
require_once DIR_INC."PageHeader.php";

$u = $user->getUserInfo($user->isUserDisplayNameUsed($web->getUrlParam(2)));
if(isset($u) && !empty($u) && $u['user_id'] > 0) {
	$title = sprintf($lang->getLocale('P_PROFILE_TITLE'), $user->getUserName($u['user_id']));
	echo $display->createpanel($title, "primary");
	$web->addToTitle(" - ".$title);
	?>
	<div class="media">
	  <div class="media-left">
	    <img class="media-object img-rounded img-responsive" src="<?php echo $user->getUserAvatar($u['user_id']);?>" alt="<?php echo $user->getUserName($u['user_id'])."'s avatar";?>">
	  </div>
	  <div class="media-body">
	    <h4 class="media-heading"></h4>
	    <ul class="list-unstyled">
			<li><?php echo $lang->getLocale('P_PROFILE_L2');?> <?php echo $display->userRole(intval($u['user_rights']));?></li>
			<li><?php echo $lang->getLocale('P_PROFILE_L3');?> <?php echo $display->showUserOnlineStatus($u['user_id']);?></li>
			<?php if($user->getUserSettings($u['user_id'], 'display_email') == true) echo "<li>".$lang->getLocale('P_PROFILE_L5')." <a href='mailto:".$u['user_email']."'>".$u['user_email']."</a></li>";?>
			<li><?php echo $lang->getLocale('P_PROFILE_L1');?> <?php echo $web->showTime($u['user_timestamp_register']);?></li>
		</ul>
		</div>
	</div>

	<?php
	$g = $user->getUserGroups($u['user_id']);
	if(count($g) > 0) {
	?>
	<hr>
	<div class="media">
		<div class="media-body">
			<h4 class="media-heading"><?php echo $lang->getLocale('P_GROUPS_TITLE_2');?></h4><ul class='list-unstyled'><?php
			for($i = 0;$i < count($g);$i ++) {
				echo "<li>".$display->render_userGroup_label($g[$i])." <a href='".$links->getUserGroupLink($g[$i])."'>".htmlentities($user->getUserGroupInfo($g[$i], 'usergroup_name'))."</a></li>";
			}
			?></ul>
	  </div>
	</div>
	<?php
	}
	
	if($user->getUserSettings($u['user_id'], 'desc') == true) {
		?>
		<hr>
		<div class="media">
			<div class="media-body">
				<h4 class="media-heading"><?php echo $lang->getLocale('P_PROFILE_DESC');?></h4>
				<p class='text-left'><?php echo $content->clearUserInput($user->getUserSettings($u['user_id'], 'desc'));?></p>
			</div>
		</div>
		<?php
	}
	?>

	<?php if($user->isUserLogged() && $u['user_id'] != $user->loggedUser()) { ?>
		<hr>
		<div class="pull-right">
			<a href="<?php echo $web->getUrl("messages/new/".$u['user_name']."");?>" class="btn btn-primary"><i class="fa fa-envelope" aria-hidden="true"></i> <?php echo $lang->getLocale('P_PROFILE_B1');?></a>
			<a href="<?php echo $web->getUrl("report/user/".$u['user_name']."");?>" class="btn btn-danger"><i class="fa fa-ban" aria-hidden="true"></i> <?php echo $lang->getLocale('P_PROFILE_B2');?></a>
		</div>
	<?php } ?>

	<?php

	if($user->isUserLogged() && $u['user_id'] == $user->loggedUser()) {
		echo $display->closepanel();
		echo $display->createpanel(null, "primary");
		echo "<a href='".$web->getUrl("settings")."'><i class='fa fa-pencil' aria-hidden='true'></i> ".$lang->getLocale('P_PROFILE_EDIT')."</a>";
	}	
}
else echo "<p>".$lang->getLocale('P_PROFILE_E1')."</p>";
echo $display->closepanel();