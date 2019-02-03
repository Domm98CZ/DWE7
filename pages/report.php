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
* File: report.php
* Filepath: /pages/report.php
*/
if(!defined("AUTHORIZED")) { die("Access Denied"); }
require_once DIR_INC."PageHeader.php";

$param = $web->getUrlParam(2);
$reported = $web->getUrlParam(3);
$redirect_uri = (isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER'] : $web->getUrl();

$web->addToTitle(" - ".$lang->getLocale('P_REPORT_T1'));

if(isset($param) && !empty($param) && isset($reported) && !empty($reported)) {

  echo $display->createpanel($lang->getLocale('P_REPORT_T1'), "danger");
  if(strtolower($param) == "user") {
    $u = $user->getUserInfo($user->isUserDisplayNameUsed($reported));
    if(isset($u) && !empty($u) && $u['user_id'] > 0) {
      ?>
      <form method="post">
        <div class="form-group">
          <label for="l_report_reported"><?php echo $lang->getLocale('P_REPORT_L1');?></label>
          <input type="text" class="form-control" name="report_reported" id="l_report_reported" value="<?php echo htmlentities($u['user_display_name']);?>" readonly>
        </div>
        <div class="form-group">
          <label for="report_content"><?php echo $lang->getLocale('P_REPORT_L3');?></label>
          <?php echo $content->editableInput("report_content"); ?>
        </div>   
        <hr>
        <div class="pull-right">
          <input type="submit" name="reportUser" value="<?php echo $lang->getLocale('P_REPORT_B1');?>" class="btn btn-danger">
          <a href="<?php echo $redirect_uri;?>" class="btn btn-primary"><?php echo $lang->getLocale('P_REPORT_B2');?></a>
        </div>          
      </form>
      <?php
      if(@$_POST["reportUser"]) {
        if(isset($_POST["report_content"]) && !empty($_POST["report_content"])) {
        	$lastR = $web->getLastUserReport($user->isUserLogged(), "user");
          $canReport = false;
          if(isset($lastR) && $lastR['report_id'] > 0) {
            if($lastR['report_type'] == "user") {
              if($lastR['report_reported'] == $u['user_id']) {
                if($lastR['report_admin'] > 0 && $lastR['message_id'] > 0 && $lastR['report_timestamp_solved'] > 0 && $lastR['report_timestamp_show'] > 0) {
                  if((time() - $lastR['report_timestamp']) >= USER_TIME_REPORT) {
                    $canReport = true;
                  }
                  else $canReport = false;
                }
                else $canReport = false;
              } 
              else $canReport = true;
            }
            else $canReport = true;
          }
          else $canReport = true;
          
          if($canReport == true) {
            $reportContent = $content->clearUserInput($_POST["report_content"]);

            $web->insertReport(array(
              "report_type" => "user",
              "report_content" => $reportContent,
              "report_timestamp" => time(),
              "report_timestamp_show" => "0",
              "report_timestamp_solved" => "0",
              "report_admin" => "0",
              "report_reported" => $u['user_id'],
              "user_id" => $user->isUserLogged(),
              "message_id" => "0"
            ));
            echo "<p class='text-success'>".$lang->getLocale('P_REPORT_OK')."</p>";
            $web->redirect($redirect_uri, 2);
          }
          else echo "<p class='text-danger'>".$lang->getLocale('P_REPORT_E1')."</p>";
        }
        else echo "<p class='text-warning'>".$lang->getLocale('REPORTS_E1')."</p>";
      }
    }
    else $web->redirect($web->redirect($redirect_uri));
  }
  else if(strtolower($param) == "comment") {
    $n = $content->getComment($reported);
    if(isset($n) && !empty($n) && $n['comment_id'] > 0) {
      ?>
      <form method="post">
        <div class="form-group">
          <label for="l_report_reported"><?php echo $lang->getLocale('P_REPORT_L2');?></label>
          <div class='media'>
            <div class='media-left'>
              <a href='<?php echo $links->getUserLink($n['user_id']);?>'><img class='media-object image-responsive' src='<?php echo $user->getUserAvatar($n['user_id'], '48');?>' width='48px' height='48px' alt='<?php echo $user->getUserName($n['user_id']);?> avatar'></a>
            </div>
            <div class='media-body'>
            <p class='text-justify'><a href='<?php echo $links->getUserLink($n['user_id']);?>'><strong><?php echo $user->getUserName($n['user_id'], 1);?></strong></a>: 
            <?php echo $n['comment'];?>
            </p>
            </div>
          </div>
        </div>
      
        <div class="form-group">
          <label for="report_content"><?php echo $lang->getLocale('P_REPORT_L3');?></label>
          <?php echo $content->editableInput("report_content"); ?>
        </div>   
        <hr>
        <div class="pull-right">
          <input type="submit" name="reportComment" value="<?php echo $lang->getLocale('P_REPORT_B1');?>" class="btn btn-danger">
          <a href="<?php echo $redirect_uri;?>" class="btn btn-primary"><?php echo $lang->getLocale('P_REPORT_B2');?></a>
        </div>          
      </form>
      <?php
      if(@$_POST["reportComment"]) {
        if(isset($_POST["report_content"]) && !empty($_POST["report_content"])) {
        	$lastR = $web->getLastUserReport($user->isUserLogged(), "comment");
          $canReport = false;
          if(isset($lastR) && $lastR['report_id'] > 0) {
            if($lastR['report_type'] == "comment") {
              if($lastR['report_reported'] == $reported) {
                if($lastR['report_admin'] > 0 && $lastR['message_id'] > 0 && $lastR['report_timestamp_solved'] > 0 && $lastR['report_timestamp_show'] > 0) {
                  if((time() - $lastR['report_timestamp']) >= USER_TIME_REPORT) {
                    $canReport = true;
                  }
                  else $canReport = false;
                }
                else $canReport = false;
              } 
              else $canReport = true;
            }
            else $canReport = true;
          }
          else $canReport = true;
          
          if($canReport == true) {
            $reportContent = $content->clearUserInput($_POST["report_content"]);

            $web->insertReport(array(
              "report_type" => "comment",
              "report_content" => $reportContent,
              "report_timestamp" => time(),
              "report_timestamp_show" => "0",
              "report_timestamp_solved" => "0",
              "report_admin" => "0",
              "report_reported" => $reported,
              "user_id" => $user->isUserLogged(),
              "message_id" => "0"
            ));
            echo "<p class='text-success'>".$lang->getLocale('P_REPORT_OK')."</p>";
            $web->redirect($redirect_uri, 2);
          }
          else echo "<p class='text-danger'>".$lang->getLocale('P_REPORT_E1')."</p>";
        }
        else echo "<p class='text-warning'>".$lang->getLocale('REPORTS_E1')."</p>";
      }
    }
    else $web->redirect($web->redirect($redirect_uri));
  }
  
  echo $display->closepanel();
}
else $web->redirect($redirect_uri); 
?>