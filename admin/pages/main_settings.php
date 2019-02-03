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
* File: main_settings.php
* Filepath: /admin/pages/main_settings.php
*/
if(!defined("AUTHORIZED")) { die("Access Denied"); }
require_once DIR_INC."PageHeader.php";

if($user->isUserHasRights($user->loggedUser(), 12, "b")) {
  $web->addToTitle(" - ".$lang->getLocale('ADMIN_MS_T1'));
  ?>
  <!-- Page Heading -->
  <section class="content-header">
    <h1><?php echo $lang->getLocale("ADMIN_MS_T1");?><small><?php echo $web->getAdministrationName();?></small></h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo $web->getUrl("admin/adm.php");?>"><i class="fa fa-dashboard"></i> <?php echo $web->getAdministrationName();?></a></li>
      <li class="active"><?php echo $lang->getLocale('ADMIN_MS_T1');?></li>
    </ol>
  </section>

  <section class="content">
    <div class="row">
      <div class="col-xs-12 col-md-12">

        <div class="box box-primary">
          <div class="box-header">
            <h3 class="box-title"><?php echo $lang->getLocale("ADMIN_MS_T2");?></h3>
          </div>

          
          <form method="post">
            <div class="box-body">
          		<div class="form-group">
                    <label for="l_web_title"><?php echo $lang->getLocale('ADMIN_MS_L1');?></label>
                    <input type="text" name="web_title" class="form-control" id="l_web_title" value="<?php echo htmlentities($web->getSettings('web:title'));?>">
                  </div>

                  <div class="form-group">
                    <label for="l_web_email"><?php echo $lang->getLocale('ADMIN_MS_L2');?></label>
                    <input type="text" name="web_email" class="form-control" id="l_web_email" value="<?php echo htmlentities($web->getSettings('web:email'));?>">
                  </div>  
              
                  <div class="form-group">
                    <label><?php echo $lang->getLocale('ADMIN_MS_L5');?></label>
                    <select name="lang" class="form-control">
                    <?php
                    $l = $lang->getInstalledLangsTitles(2);
                    if(count($l) > 0) {
                      for($i = 0;$i < count($l);$i ++) {
                        echo "<option value='".$l[$i][0]."'".(($web->getSettings('web:lang') == $l[$i][0]) ? " selected" : null).">".$l[$i][0]." - ".$l[$i][1]."</option>";
                      }
                    }
                    ?>
                    </select>
                </div>
              
                <div class="form-group">
                  <label for="l_web_url"><?php echo $lang->getLocale('ADMIN_MS_L3');?></label>
                  <input type="text" name="web_url" class="form-control" id="l_web_url" value="<?php echo htmlentities($web->getSettings('web:url'));?>">
                </div>
                
                <div class="form-group">
                  <label for="l_web_keywords"><?php echo $lang->getLocale('ADMIN_MS_L6');?></label>
                  <input type="text" name="web_keywords" class="form-control" id="l_web_keywords" value="<?php echo htmlentities($web->getSettings('web:keywords'));?>">
                </div>

                <div class="form-group">
                  <label for="l_web_description"><?php echo $lang->getLocale('ADMIN_MS_L7');?></label>
                  <textarea class="form-control" id="l_web_description" name="web_description" rows="2" style="resize: vertical; overflow: auto; margin-top: 0px; margin-bottom: 0px;"><?php echo htmlentities($web->getSettings('web:description'));?></textarea>
                </div>
              </div>
            <div class="box-footer">
              <input type="submit" name="mainSettingsSave" class="btn btn-success" value="<?php echo $lang->getLocale('ADMIN_MS_B1');?>">
            </div>
          	</form>
        </div>
        <?php
        if(@$_POST["mainSettingsSave"]) {
          if(isset($_POST["web_title"]) && !empty($_POST["web_title"]) && isset($_POST["web_email"]) && !empty($_POST["web_email"]) && isset($_POST["lang"]) && !empty($_POST["lang"]) && isset($_POST["web_url"]) && !empty($_POST["web_url"])) {
            if(!filter_var($_POST["web_email"], FILTER_VALIDATE_EMAIL) === false) {

              $url = strtolower($_POST["web_url"]);
              $url = str_replace(array("https://", "http://"), array("", ""), $url);
              $url = $web->getSettings("web:protocol").$url;
              if(!filter_var($url, FILTER_VALIDATE_URL) === false) {

                $web->updateSettings("web:title", $content->clearUserInputAll($_POST["web_title"]));
                $web->updateSettings("web:email", $content->clearUserInputAll($_POST["web_email"]));
                $web->updateSettings("web:keywords", $content->clearUserInput($_POST["web_keywords"]));
                $web->updateSettings("web:description", $content->clearUserInput($_POST["web_description"]));
                $web->updateSettings("web:lang", $_POST["lang"]);
                $web->updateSettings("web:url", $content->clearUserInputAll($_POST["web_url"]));
                echo $display->adminAlert($lang->getLocale("ADMIN_E_S"), $lang->getLocale("ADMIN_MS_OK"), "success"); 
              }
              else echo $display->adminAlert($lang->getLocale("ADMIN_E_W"), $lang->getLocale("ADMIN_MS_E2"), "warning");
            }
            else echo $display->adminAlert($lang->getLocale("ADMIN_E_W"), $lang->getLocale("ADMIN_MS_E1"), "warning");
          }
          else echo $display->adminAlert($lang->getLocale("ADMIN_E_W"), $lang->getLocale("ADMIN_E_E1"), "danger");
          $web->redirect($web->getUrl("admin/adm.php?page=main_settings"), 2);
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