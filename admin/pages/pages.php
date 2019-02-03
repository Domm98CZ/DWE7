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
* File: pages.php
* Filepath: /admin/pages/pages.php
*/
if(!defined("AUTHORIZED")) { die("Access Denied"); }
require_once DIR_INC."PageHeader.php";

if($user->isUserHasRights($user->loggedUser(), 6, "b")) {
  $web->addToTitle(" - ".$lang->getLocale("ADMIN_P_T1"));

   if(isset($_GET["post"]) && !empty($_GET["post"]) && is_numeric($_GET["post"]) && $_GET["post"] > 0 && isset($_GET["delete"])) {
    $p = $content->getPost($_GET["post"]);
    if(isset($p) && !empty($p) && $p['post_id'] > 0) {
      $web->addToTitle(" - ".$lang->getLocale("ADMIN_NEWS_T10")." - ".htmlentities($p['post_title']));
      ?>
      <!-- Page Heading -->
      <section class="content-header">
        <h1><?php echo $lang->getLocale("ADMIN_P_T1");?><small><?php echo $web->getAdministrationName();?></small></h1>
        <ol class="breadcrumb">
          <li><a href="<?php echo $web->getUrl("admin/adm.php");?>"><i class="fa fa-dashboard"></i> <?php echo $web->getAdministrationName();?></a></li>
          <li><a href="<?php echo $web->getUrl("admin/adm.php?page=pages");?>"><?php echo $lang->getLocale("ADMIN_P_T1");?></a></li>
          <li class="active"><?php echo $lang->getLocale("ADMIN_P_T9")." - ".htmlentities($p['post_title']);?></li>
        </ol>
      </section>

      <section class="content">
          <div class="row">
            <div class="col-xs-12 col-md-12">

              <div class="box box-danger">
                <div class="box-header">
                  <h3 class="box-title"><?php echo $lang->getLocale("ADMIN_P_T9")." - ".htmlentities($p['post_title']);?></h3>
                </div>
                
                <div class="box-body">

                  <p><?php echo sprintf($lang->getLocale('ADMIN_P_T10'), htmlentities($p['post_title']));?></p>

                  <form method="post">
                    <input type="submit" name="deletePost" value="<?php echo $lang->getLocale('ADMIN_P_B6');?>" class="btn btn-danger">
                    <a href="<?php echo $web->getUrl("admin/adm.php?page=pages&post=".$p['post_id']);?>" class="btn btn-primary"><?php echo $lang->getLocale('ADMIN_P_B7');?></a>
                  </form>

                </div>
              </div>

              <?php
              if(@$_POST["deletePost"]) {
                $content->deletePost($p['post_id']);
                echo $display->adminAlert($lang->getLocale("ADMIN_E_S"), $lang->getLocale("ADMIN_P_OK1"), "success"); 
                $web->redirect($web->getUrl("admin/adm.php?page=pages"), 2);
              }
              ?>

            </div>
          </div>
      </section>
      <?php
    }
    else $web->redirect($web->getUrl("admin/adm.php?page=pages"));
  }
  else if(isset($_GET["post"]) && !empty($_GET["post"]) && is_numeric($_GET["post"]) && $_GET["post"] > 0) {
    $p = $content->getPost($_GET["post"]);
    if(isset($p) && !empty($p) && $p['post_id'] > 0) {
      $web->addToTitle(" - ".$lang->getLocale("ADMIN_P_T4")." - ".htmlentities($p['post_title']));
      ?>
      <!-- Page Heading -->
      <section class="content-header">
        <h1><?php echo $lang->getLocale("ADMIN_P_T1");?><small><?php echo $web->getAdministrationName();?></small></h1>
        <ol class="breadcrumb">
          <li><a href="<?php echo $web->getUrl("admin/adm.php");?>"><i class="fa fa-dashboard"></i> <?php echo $web->getAdministrationName();?></a></li>
          <li><a href="<?php echo $web->getUrl("admin/adm.php?page=pages");?>"><?php echo $lang->getLocale("ADMIN_P_T1");?></a></li>
          <li class="active"><?php echo $lang->getLocale("ADMIN_P_T4")." - ".htmlentities($p['post_title']);?></li>
        </ol>
      </section>
    
      <section class="content">
        <form method="post">

          <?php
          if(empty($p['post_url']) || !isset($p['post_url'])) {
            echo $display->adminAlert($lang->getLocale("ADMIN_E_W"), $lang->getLocale("ADMIN_P_T11"), "warning"); 
          }
          ?>

          <div class="row">
            <div class="col-xs-12 col-md-9">

              <div class="box box-primary">
                <div class="box-header">
                  <h3 class="box-title"><?php echo $lang->getLocale("ADMIN_P_T4")." - ".htmlentities($p['post_title']);?></h3>
                  <div class="box-tools pull-right">
                    <a href="<?php echo $web->getUrl("admin/adm.php?page=pages");?>" class="btn btn-xs btn-warning"><?php echo $lang->getLocale("ADMIN_P_B2");?></a>
                  </div>
                </div>
                
                <div class="box-body">

                  <div class="form-group">
                    <label for="l_post_title"><?php echo $lang->getLocale('ADMIN_P_L5');?></label>
                    <input type="text" name="post_title" class="form-control" id="l_post_title" value="<?php echo htmlentities($p['post_title']);?>">
                  </div>

                  <div class="form-group">
                    <label for="posttextarea"><?php echo $lang->getLocale('ADMIN_P_L7');?></label>
                    <?php echo $content->editableInput("posttextarea", null, $p['post_content']); ?>
                  </div>

                  <hr>

                  <div class="form-group">
                    <label for="l_post_tags"><?php echo $lang->getLocale('ADMIN_NEWS_L13');?></label>
                    <input type="text" name="post_tags" class="form-control" id="l_post_tags" value="<?php echo htmlentities($p['post_tags']);?>">
                  </div>

                  <div class="form-group">
                    <label for="l_post_keywords"><?php echo $lang->getLocale('ADMIN_NEWS_L14');?></label>
                    <input type="text" name="post_keywords" class="form-control" id="l_post_keywords" value="<?php echo htmlentities($p['post_keywords']);?>">
                  </div>

                  <div class="form-group">
                    <label for="l_post_description"><?php echo $lang->getLocale('ADMIN_NEWS_L15');?></label>
                    <textarea class="form-control" id="l_post_description" name="post_description" rows="2" style="resize: vertical; overflow: auto; margin-top: 0px; margin-bottom: 0px;"><?php echo htmlentities($p['post_description']);?></textarea>
                  </div>
                </div>

            </div>
          </div>
          <div class="col-xs-12 col-md-3">

            <div class="box box-primary">
              <div class="box-header">
                <h3 class="box-title"><?php echo $lang->getLocale('ADMIN_P_T5');?></h3>
                <div class="box-tools pull-right">
                   <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
              </div>
              
              <div class="box-body">

                <ul class="list-unstyled">
                  <li><b><?php echo $lang->getLocale('ADMIN_P_T6');?></b>: <?php echo $web->showTime($p['post_timestamp_add']);?></li>
                  <li><b><?php echo $lang->getLocale('ADMIN_P_T7');?></b>: <a href="<?php echo $links->getUserLink($p['user_id']);?>" target="_blank"><?php echo $user->getUserName($p['user_id'])?></a></li>
                  <?php
                  if(!empty($p['post_timestamp_edit']) && !empty($p['edit_user_id'])) {
                    ?><li><b><?php echo $lang->getLocale('ADMIN_P_T8');?></b>:  <a href="<?php echo $links->getUserLink($p['edit_user_id']);?>" target="_blank"><?php echo $user->getUserName($p['edit_user_id'])?></a>, <?php echo $web->showTime($p['post_timestamp_edit']);?> </li><?php
                  }
                  ?>
                </ul>

                <hr>
                
                <div class="form-group">
                  <label for="l_post_visibility"><?php echo $lang->getLocale('ADMIN_P_L7');?></label>
                  <select id="l_post_visibility" name="post_visibility" class="form-control">
                    <option value="0"<?php echo ($p['post_visibility'] <= 0) ? " selected" : null;?>><?php echo $lang->getLocale('ADMIN_P_L7-1');?></option>
                    <option value="1"<?php echo ($p['post_visibility'] > 0) ? " selected" : null;?>><?php echo $lang->getLocale('ADMIN_P_L7-2');?></option>
                  </select>
                </div>

                <?php /*<div class="checkbox">
                  <label><input name="post_comments" id="l_postcomments" type="checkbox"<?php echo ($p['post_comments'] > 0) ? " checked" : null;?>> <?php echo $lang->getLocale('ADMIN_P_L9');?></label>
                </div> */ ?>

                  <div class="form-group">
                    <label for="l_post_url"><?php echo $lang->getLocale('ADMIN_P_L10');?></label>
                    <input type="text" name="post_url" class="form-control" id="l_post_url" value="<?php echo $p['post_url'];?>">
                  </div>

                <hr>

                <input type="submit" name="postSave" value="<?php echo $lang->getLocale('ADMIN_P_B4');?>" class="btn btn-block btn-success">
                <?php
                $url = $links->getPostLink($p['post_id']);
                if(!empty($url)) { ?><a href="<?php echo $url;?>" target="_blank" class="btn btn-block btn-primary"><?php echo $lang->getLocale('ADMIN_P_B3');?></a><?php }
                else { ?><a class="btn btn-block btn-primary disabled"><?php echo $lang->getLocale('ADMIN_P_B3');?></a><?php }
                ?>
                <a href="<?php echo $web->getUrl("admin/adm.php?page=pages&post=".$p['post_id']."&delete");?>" class="btn btn-block btn-danger"><?php echo $lang->getLocale('ADMIN_P_B5');?></a>

              </div>
            </div>

          </div>

        </div>
      </form>

      <?php 
      if(@$_POST["postSave"]) {
        if(isset($_POST["post_title"]) && isset($_POST["posttextarea"]) && isset($_POST["post_visibility"]) && isset($_POST["post_url"]) 
        && !empty($_POST["post_title"]) && !empty($_POST["posttextarea"])) {
          
          $post_url = null;
          if(!empty($_POST["post_url"])) $post_url = $content->clearUserInputAll($_POST["post_url"]);
          else $post_url = str_replace(" ", "-", strtolower($content->removeDiacritics($content->clearUserInputAll($_POST["post_title"]))));
          
          $post_visibility = 0;
          if(!$links->isPostLinkExists($post_url, "page", $p["post_id"]) && strlen($post_url) > 4 && $links->isLinkProhibited($post_url, 1) == 0) {
            $post_visibility = $_POST["post_visibility"];
          }
          else {
            $post_url = $p['post_url'];
            $post_visibility = 0;
          }
          
          $content->updatePost($p["post_id"], array(
            "post_title" => $content->clearUserInputAll($_POST["post_title"]),
            "newsCategory_id" => "0",
            "post_content" => $content->clearUserInput($_POST["posttextarea"]),
            "edit_user_id" => $user->loggedUser(),
            "post_timestamp_edit" => time(),
            //"post_comments" => (isset($_POST["post_comments"]) ? "1" : "0"),
            "post_comments" => "0",
            "post_visibility" => $post_visibility,
            "post_url" => $post_url,
            "post_tags" => $content->clearUserInput($_POST["post_tags"]),
            "post_keywords" => $content->clearUserInput($_POST["post_keywords"]),
            "post_description" => $content->clearUserInput($_POST["post_description"])
          ));

          echo $display->adminAlert($lang->getLocale("ADMIN_E_S"), $lang->getLocale("ADMIN_P_OK2"), "success"); 
        }
        else {
          echo $display->adminAlert($lang->getLocale("ADMIN_E_W"), $lang->getLocale("ADMIN_E_E1"), "danger"); 
        }
        $web->redirect($web->getUrl("admin/adm.php?page=pages&post=".$p["post_id"]), 2);
      }
      ?>
    </section>
    <?php
    }
    else $web->redirect($web->getUrl("admin/adm.php?page=pages"));
  }
  else if(isset($_GET["add"])) {
    $pid = $content->createPost("page", $user->loggedUser());
    $web->redirect($web->getUrl("admin/adm.php?page=pages&post=".$pid));
  }
  else {
    ?>
    <!-- Page Heading -->
    <section class="content-header">
      <h1><?php echo $lang->getLocale("ADMIN_P_T1");?><small><?php echo $web->getAdministrationName();?></small></h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo $web->getUrl("admin/adm.php");?>"><i class="fa fa-dashboard"></i> <?php echo $web->getAdministrationName();?></a></li>
        <li class="active"><?php echo $lang->getLocale("ADMIN_P_T1");?></li>
      </ol>
    </section>

    <section class="content">
      <div class="row">
        <div class="col-lg-12">

          <div class="box box-primary">
            <div class="box-header">
              <h3 class="box-title"><?php echo $lang->getLocale("ADMIN_P_T2");?></h3>  
              <div class="box-tools pull-right">
                <a href="<?php echo $web->getUrl("admin/adm.php?page=pages&add");?>" class="btn btn-xs btn-primary"><?php echo $lang->getLocale("ADMIN_P_B1");?></a>
              </div>
            </div>
            
            <div class="box-body no-padding"> 
              <?php echo $content->dataTable("postsTable", "table");?>
              <thead>
                <tr>
                  <th><?php echo $lang->getLocale("ADMIN_P_L1");?></th>
                  <th><?php echo $lang->getLocale("ADMIN_P_L2");?></th>
                  <th><?php echo $lang->getLocale("ADMIN_P_L3");?></th>
                  <th><?php echo $lang->getLocale("ADMIN_P_L4");?></th>
                </tr>
              </thead>
              <tbody>
                <?php
                $n = $content->getPostsByType("page", "order by `post_timestamp_add` desc, `post_timestamp_edit` desc");
                if(isset($n) && count($n) > 0) {
                  for($i = 0;$i < count($n);$i ++) {
                    ?>
                    <tr>
                      <td><a href="<?php echo $web->getUrl("admin/adm.php?page=pages&post=".$n[$i]['post_id']."");?>"><?php echo htmlentities($n[$i]['post_title']);?></a></td>
                      <td><a href="<?php echo $links->getUserLink($n[$i]['user_id']);?>" target="_blank"><?php echo $user->getUserName($n[$i]['user_id'])?></a></td>
                      <td><?php echo $web->showTime($n[$i]['post_timestamp_add']);?></td>
                      <td><?php
                      if(!empty($p['post_timestamp_edit']) && !empty($p['edit_user_id'])) {
                        echo "<a href='".$links->getUserLink($p['edit_user_id'])." target='_blank'>".$user->getUserName($p['edit_user_id'])."</a>, ".$web->showTime($p['post_timestamp_edit'])."";
                      }
                      else echo "<i>".$lang->getLocale('ADMIN_P_T3')."</i>";
                      ?></td>
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