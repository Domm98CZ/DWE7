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
* File: comments.php
* Filepath: /admin/pages/comments.php
*/
if(!defined("AUTHORIZED")) { die("Access Denied"); }
require_once DIR_INC."PageHeader.php";

if($user->isUserHasRights($user->loggedUser(), 5, "b")) {
  $web->addToTitle(" - ".$lang->getLocale("ADMIN_P_T1"));

  if(isset($_GET["comment"]) && !empty($_GET["comment"]) && is_numeric($_GET["comment"]) && $_GET["comment"] > 0 && isset($_GET["delete"])) {
    $n = $content->getComment($_GET["comment"]);
    if(isset($n) && !empty($n) && $n['comment_id'] > 0) {
      $web->addToTitle(" - ".$lang->getLocale("ADMIN_C_T5"));
      ?>
      <!-- Page Heading -->
      <section class="content-header">
        <h1><?php echo $lang->getLocale("ADMIN_C_T1");?><small><?php echo $web->getAdministrationName();?></small></h1>
        <ol class="breadcrumb">
          <li><a href="<?php echo $web->getUrl("admin/adm.php");?>"><i class="fa fa-dashboard"></i> <?php echo $web->getAdministrationName();?></a></li>
          <li><a href="<?php echo $web->getUrl("admin/adm.php?page=comments");?>"><?php echo $lang->getLocale("ADMIN_C_T1");?></a></li>
          <li class="active"><?php echo $lang->getLocale("ADMIN_C_T5");?></li>
        </ol>
      </section>

      <section class="content">
          <div class="row">
            <div class="col-xs-12 col-md-12">

              <div class="box box-danger">
                <div class="box-header">
                  <h3 class="box-title"><?php echo $lang->getLocale("ADMIN_C_T5");?></h3>
                </div>
                
                <div class="box-body">

                  <p><?php echo sprintf($lang->getLocale('ADMIN_C_T6'), "<a href='".$links->getUserLink($n['user_id'])."' target='_blank'>".$user->getUserName($n['user_id'])."</a>",  $links->getPostLinkWithName($n['post_id']));?></p>

                  <form method="post">
                    <input type="submit" name="deleteComment" value="<?php echo $lang->getLocale('ADMIN_C_B3');?>" class="btn btn-danger">
                    <a href="<?php echo $web->getUrl("admin/adm.php?page=comments&comment=".$n['comment_id']);?>" class="btn btn-primary"><?php echo $lang->getLocale('ADMIN_C_B4');?></a>
                  </form>

                </div>
              </div>

              <?php
              if(@$_POST["deleteComment"]) {
                $content->deleteComment($n['comment_id']);
                echo $display->adminAlert($lang->getLocale("ADMIN_E_S"), $lang->getLocale("ADMIN_C_T7"), "success"); 
                $web->redirect($web->getUrl("admin/adm.php?page=comments"), 2);
              }
              ?>

            </div>
          </div>
      </section>
      <?php
    }
    else $web->redirect($web->getUrl("admin/adm.php?page=comments"));
  }
  else if(isset($_GET["comment"]) && !empty($_GET["comment"]) && is_numeric($_GET["comment"]) && $_GET["comment"] > 0) {
    $n = $content->getComment($_GET["comment"]);
    if(isset($n) && !empty($n) && $n['comment_id'] > 0) {
      $web->addToTitle(" - ".$lang->getLocale("ADMIN_C_T3"));
      ?>
      <!-- Page Heading -->
      <section class="content-header">
        <h1><?php echo $lang->getLocale("ADMIN_C_T1");?><small><?php echo $web->getAdministrationName();?></small></h1>
        <ol class="breadcrumb">
          <li><a href="<?php echo $web->getUrl("admin/adm.php");?>"><i class="fa fa-dashboard"></i> <?php echo $web->getAdministrationName();?></a></li>
          <li><a href="<?php echo $web->getUrl("admin/adm.php?page=comments");?>"><?php echo $lang->getLocale("ADMIN_C_T1");?></a></li>
          <li class="active"><?php echo $lang->getLocale("ADMIN_C_T3")."";?></li>
        </ol>
      </section>
    
      <section class="content">
      
        <div class="box box-primary">
          <div class="box-header">
            <h3 class="box-title"><?php echo $lang->getLocale("ADMIN_C_T3");?></h3>
            <div class="box-tools pull-right">
              <a href="<?php echo $web->getUrl("admin/adm.php?page=comments");?>" class="btn btn-xs btn-warning"><?php echo $lang->getLocale("ADMIN_C_B1");?></a>
              <a href="<?php echo $web->getUrl("admin/adm.php?page=comments&comment=".$n['comment_id']."&delete");?>" class="btn btn-xs btn-danger"><?php echo $lang->getLocale("ADMIN_C_B2");?></a>
            </div>
          </div>
          <div class="box-body">
          	<b><a href="<?php echo $links->getUserLink($n['user_id']);?>" target="_blank"><?php echo $user->getUserName($n['user_id'])?></a> <?php echo $lang->getLocale('ADMIN_C_T4');?> <?php echo $links->getPostLinkWithName($n['post_id']);?>:</b>
          	<?php echo $n['comment'];?>
          </div>
      </div>

      </section>
      <?php
    }
    else $web->redirect($web->getUrl("admin/adm.php?page=comments"));
  }
  else {
    ?>
    <!-- Page Heading -->
    <section class="content-header">
      <h1><?php echo $lang->getLocale("ADMIN_C_T1");?><small><?php echo $web->getAdministrationName();?></small></h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo $web->getUrl("admin/adm.php");?>"><i class="fa fa-dashboard"></i> <?php echo $web->getAdministrationName();?></a></li>
        <li class="active"><?php echo $lang->getLocale("ADMIN_C_T1");?></li>
      </ol>
    </section>

    <section class="content">
      <div class="row">
        <div class="col-lg-12">

          <div class="box box-primary">
            <div class="box-header">
              <h3 class="box-title"><?php echo $lang->getLocale("ADMIN_C_T2");?></h3>  
            </div>
            
            <div class="box-body no-padding"> 
              <?php echo $content->dataTable("commentsTable", "table");?>
              <thead>
                <tr>
                  <th><?php echo $lang->getLocale("ADMIN_C_L4");?></th>
                  <th><?php echo $lang->getLocale("ADMIN_C_L1");?></th>
                  <th><?php echo $lang->getLocale("ADMIN_C_L3");?></th>
                  <th><?php echo $lang->getLocale("ADMIN_C_L2");?></th>            
                </tr>
              </thead>
              <tbody>
                <?php
                $n = $content->getAllComments();
                if(isset($n) && count($n) > 0) {
                  for($i = 0;$i < count($n);$i ++) {
                    ?>
                    <tr>
                      <td><a href="<?php echo $web->getUrl("admin/adm.php?page=comments&comment=".$n[$i]['comment_id']);?>"><?php
                      echo substr($content->clearUserInputAll($n[$i]['comment']), 0, 100).((strlen($n[$i]['comment']) > 100) ? " .." : null);
                      ?></a></td>
                      <td><a href="<?php echo $links->getUserLink($n[$i]['user_id']);?>" target="_blank"><?php echo $user->getUserName($n[$i]['user_id'])?></a></td>
                      <td>
                      <?php echo $web->showTime($n[$i]['comment_timestamp']);?>
                      <?php if($n[$i]['comment_timestamp_edit'] > 0) echo " - ".$web->showTime($n[$i]['comment_timestamp_edit']);?>  
                      </td>
                      <td><?php echo $links->getPostLinkWithName($n[$i]['post_id']);?></td>
                    </tr>
                    <?php
                  }
                }
                ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </section>
  <?php
  }
}
else {
  $web->redirect($web->getUrl("admin/adm.php"));
}
?>