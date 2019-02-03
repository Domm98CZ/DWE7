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
* File: other_settings.php
* Filepath: /admin/pages/other_settings.php
*/
if(!defined("AUTHORIZED")) { die("Access Denied"); }
require_once DIR_INC."PageHeader.php";

if($user->isUserHasRights($user->loggedUser(), 13, "b")) {
  $web->addToTitle(" - ".$lang->getLocale('ADMIN_OS_T1'));
  ?>
  <!-- Page Heading -->
  <section class="content-header">
    <h1><?php echo $lang->getLocale("ADMIN_OS_T1");?><small><?php echo $web->getAdministrationName();?></small></h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo $web->getUrl("admin/adm.php");?>"><i class="fa fa-dashboard"></i> <?php echo $web->getAdministrationName();?></a></li>
      <li class="active"><?php echo $lang->getLocale('ADMIN_OS_T1');?></li>
    </ol>
  </section>

  <section class="content">
    <div class="row">
      <div class="col-xs-12 col-md-12">

        <div class="box box-primary">
          <div class="box-header">
            <h3 class="box-title"><?php echo $lang->getLocale("ADMIN_OS_T2");?></h3>
          </div>

          
          <form method="post">
            <div class="box-body">

              <div class="form-group">
                <label for="l_avatar_size_px"><?php echo $lang->getLocale('ADMIN_OS_L5');?></label>
                <input type="text" name="avatar_size_px" class="form-control" id="l_avatar_size_px" value="<?php echo htmlentities($web->getSettings('web:maxAvatarSizePX'));?>">
              </div>  

              <div class="form-group">
                <label for="l_avatar_size"><?php echo $lang->getLocale('ADMIN_OS_L6');?></label>
                <input type="text" name="avatar_size" class="form-control" id="l_avatar_size" value="<?php echo htmlentities($web->getSettings('web:maxAvatarSize'));?>">
              </div>  

              <div class="form-group">
                <label for="l_comments_num"><?php echo $lang->getLocale('ADMIN_OS_L2');?></label>
                <input type="text" name="comments_num" class="form-control" id="l_comments_num" value="<?php echo htmlentities($web->getSettings('comments:num'));?>">
              </div>  

              <div class="form-group">
                <label for="l_news_num"><?php echo $lang->getLocale('ADMIN_OS_L3');?></label>
                <input type="text" name="news_num" class="form-control" id="l_news_num" value="<?php echo htmlentities($web->getSettings('content:newsNum'));?>">
              </div>  

              <div class="form-group">
                <label for="l_homepage"><?php echo $lang->getLocale('ADMIN_OS_L7');?></label>
                <select name="homepage" class="form-control">
                	<?php
                	if($web->getSettings('content:homepage') == "news") {
                		?><option value='news' selected><?php echo $lang->getLocale('ADMIN_OS_L7-1');?></option><?php
                	}
                	else {
                		?><option value='news'><?php echo $lang->getLocale('ADMIN_OS_L7-1');?></option><?php
                	}

                	$n = $content->getPostsByType("page", "and `post_visibility` = '1' order by `post_timestamp_add` desc, `post_timestamp_edit` desc");
					if(isset($n) && count($n) > 0) {
						for($i = 0;$i < count($n);$i ++) {
							?><option value='page#<?php echo $n[$i]['post_id'];?>'<?php echo (($web->getSettings('content:homepage') == "page#".$n[$i]['post_id']."") ? " selected" : null);?>>[<?php echo $lang->getLocale('ADMIN_OS_L7-2');?>] <?php echo htmlentities($n[$i]['post_title']);?></option><?php
						}
					}
                	?>

                </select>
              </div>  

              <div class="form-group">
                <label class="control-label" for="l_web_protocol"><?php echo $lang->getLocale('ADMIN_MS_L4');?></label>
                <br>
                <label class="radio-inline">
                    <input type="radio" name="web_protocol" id="l_web_protocol" value="http"<?php echo (($web->getSettings('web:protocol') == "http://") ? " checked" : null);?>> http://
                </label>
                <label class="radio-inline">
                  <input type="radio" name="web_protocol" id="l_web_protocol" value="https"<?php echo (($web->getSettings('web:protocol') == "https://") ? " checked" : null);?>> <span class="text-success">https://</span>
                </label>
              </div>
              
              <div class="form-group">
                <label for="l_post_url"><?php echo $lang->getLocale('ADMIN_OS_L1');?></label>

                <div class="radio">
                  <label>
                    <input type="radio" name="post_url_type" value="1"<?php echo (($web->getSettings("content:urlType") == 1) ? " checked" : null);?>>
                    <?php echo $lang->getLocale('ADMIN_NEWS_L12-1');?>
                  </label>
                </div>
                <div class="radio">
                  <label>
                    <input type="radio" name="post_url_type" value="2"<?php echo (($web->getSettings("content:urlType") == 2) ? " checked" : null);?>>
                    <?php echo $lang->getLocale('ADMIN_NEWS_L12-2');?>
                  </label>
                </div>
                <div class="radio">
                  <label>
                    <input type="radio" name="post_url_type" value="3"<?php echo (($web->getSettings("content:urlType") == 3) ? " checked" : null);?>>
                    <?php echo $lang->getLocale('ADMIN_NEWS_L12-3');?>
                  </label>
                </div>
              </div>
              
              <?php $x = $web->getSettings("web:maintenanceLevel"); ?>
              <div class="form-group">
                <label for="l_maintenance"><?php echo $lang->getLocale("ADMIN_OS_L4");?></label>
                <br>
                <label for="l_maintenance-r1" class="radio-inline">
                  <input type="radio" name="maintenance" id="l_maintenance-r1" value="1"<?php echo (($x == 'a' || $x == null) ? " checked='checked'" : null) ?>>
                  <?php echo $lang->getLocale('ADMIN_OS_L4-1');?>
                </label>
                <label for="l_maintenance-r2" class="radio-inline">
                  <input type="radio" name="maintenance" id="l_maintenance-r2" value="2" <?php echo (($x != 'a' && $x != 'z' && $x != null) ? " checked='checked'" : null) ?>>
                  <select name="maintenance-rc">
                  <?php
                  $a = range("b", "y");
                  foreach($a as $char) {
                    echo "<option value='".$char."'".(($char == $x) ? " selected" : null).">".$char."</option>";
                  }
                  ?>
                  </select>

                </label>
                <label for="l_maintenance-r3" class="radio-inline">
                  <input type="radio" name="maintenance" id="l_maintenance-r3" value="3"<?php echo (($x == 'z') ? " checked='checked'" : null) ?>>
                  <?php echo $lang->getLocale('ADMIN_OS_L4-3');?>
                </label>
              </div>
              
              <div class="form-group">
                <label for="maintenance_desc"><?php echo $lang->getLocale('ADMIN_OS_L4l');?></label>
                <?php echo $content->editableInput("maintenance_desc", null, $web->getSettings("web:maintenance")); ?>
              </div>
              
            </div>
            <div class="box-footer">
              <input type="submit" name="otherSettingsSave" class="btn btn-success" value="<?php echo $lang->getLocale('ADMIN_OS_B1');?>">
            </div>
          	</form>
        </div>
        
        <?php
        if(@$_POST["otherSettingsSave"]) {
          if(isset($_POST["comments_num"]) && !empty($_POST["comments_num"]) && isset($_POST["news_num"]) && !empty($_POST["news_num"]) && isset($_POST["avatar_size_px"]) && !empty($_POST["avatar_size_px"]) && isset($_POST["avatar_size"]) && !empty($_POST["avatar_size"]) && isset($_POST["homepage"]) && !empty($_POST["homepage"])) {
              if(is_numeric($_POST["comments_num"]) && is_numeric($_POST["news_num"]) && is_numeric($_POST["avatar_size_px"]) && is_numeric($_POST["avatar_size"])) {
                $web->updateSettings("comments:num", $_POST["comments_num"]);
                $web->updateSettings("content:newsNum", $_POST["news_num"]);
                $web->updateSettings("web:maxAvatarSizePX", $_POST["avatar_size_px"]);
                $web->updateSettings("web:maxAvatarSize", $_POST["avatar_size"]);
                $web->updateSettings("content:homepage", $_POST["homepage"]);
                
                $m = "a";
                if($_POST["maintenance"] == 1) $m = "a";
                else if($_POST["maintenance"] == 3) $m = "z";
                else {
                  if(isset($_POST["maintenance-rc"]) && !empty($_POST["maintenance-rc"])) $m = $_POST["maintenance-rc"];
                  else $m = "a";
                }
                $web->updateSettings("web:maintenanceLevel", $m);
                $web->updateSettings("web:maintenance", $content->clearUserInput($_POST["maintenance_desc"]));
                
                if(strtolower($_POST["web_protocol"]) == "https") $web->updateSettings("web:protocol", "https://");
                else $web->updateSettings("web:protocol", "http://"); 
                
                $web->updateSettings("content:urlType", $_POST["post_url_type"]);
              
                echo $display->adminAlert($lang->getLocale("ADMIN_E_S"), $lang->getLocale("ADMIN_OS_OK"), "success");
              }
              else echo $display->adminAlert($lang->getLocale("ADMIN_E_W"), $lang->getLocale("ADMIN_OS_E1"), "warning");
          }
          else echo $display->adminAlert($lang->getLocale("ADMIN_E_W"), $lang->getLocale("ADMIN_E_E1"), "danger");
          $web->redirect($web->getUrl("admin/adm.php?page=other_settings"), 2);
        }
        ?>
        
      </div>
    </div>
  </section>
  <?php
}
else {
  $web->redirect($web->getUrl("admin/adm.php"));
}
?>