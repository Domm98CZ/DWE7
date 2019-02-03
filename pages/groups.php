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
* File: groups.php
* Filepath: /pages/groups.php
*/
if(!defined("AUTHORIZED")) { die("Access Denied"); }
require_once DIR_INC."PageHeader.php";

$g = $db->select("userGroups", array("usergroup_link" => $web->getUrlParam(2)));
if(isset($g) && !empty($g)) {
  $web->addToTitle(" - ".sprintf($lang->getLocale('P_GROUPS_TITLE_1'), htmlentities($g['usergroup_name'])));
  echo $display->createpanel(sprintf($lang->getLocale('P_GROUPS_TITLE_1'), htmlentities($g['usergroup_name'])), "primary");
  echo (!empty($g['usergroup_desc']) && strlen($g['usergroup_desc'])) ? $g['usergroup_desc'] : $lang->getLocale('P_GROUPS_DESC_NONE');
  
  $u = $user->getUsersByUserGroupID($g['usergroup_id']);
  if(count($u) > 0) {
    ?>
    <hr>
    <ul class="list-unstyled">
    <li><b><?php echo sprintf($lang->getLocale('P_GROUPS_USERS_LIST'), count($u));?></b></li>
    <?php
    for($i = 0;$i < count($u);$i ++) {
      ?>
      <li><a href="<?php echo $links->getUserLink($u[$i]['user_id']);?>"><?php echo $user->getUserName($u[$i]['user_id']);?></a></li>
      <?php
    }
    ?>  
    </ul>  
    <?php
  }
  echo $display->closepanel();
  
  echo $display->createpanel(null, "primary");
	echo "<a href='".$web->getUrl("groups")."'><i class='fa fa-arrow-left' aria-hidden='true'></i> ".$lang->getLocale('P_GROUPS_L1')."</a>";
	echo $display->closepanel();
}
else {
  $web->addToTitle(" - ".$lang->getLocale('P_GROUPS_TITLE_2'));
  echo $display->createpanel($lang->getLocale('P_GROUPS_TITLE_2'), "primary");
  $gs = $db->selectAll("userGroups");
  if(count($gs) > 0) {
    echo "<ul class='list-unstyled'>";
    for($i = 0;$i < count($gs);$i ++) {
      $c = $db->querySelectRows("users", "where `user_groups` LIKE  '%#".$gs[$i]['usergroup_id']."#%'");
      echo "
        <li>".$display->render_userGroup_label($gs[$i]['usergroup_id'])." <a href='".$links->getUserGroupLink($gs[$i]['usergroup_id'])."'>".htmlentities($gs[$i]['usergroup_name'])."</a>
        - <i>(".$c." ".$lang->plural_words_locale($c, $lang->getLocale('P_GROUPS_T2'), $lang->getLocale('P_GROUPS_T1'), $lang->getLocale('P_GROUPS_T3'), null).")</i></li>";
    }
    echo "</ul>";  
  }
  echo $display->closepanel();
}
