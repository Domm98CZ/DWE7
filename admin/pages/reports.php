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
* File: reports.php
* Filepath: /admin/pages/reports.php
*/
if(!defined("AUTHORIZED")) { die("Access Denied"); }
require_once DIR_INC."PageHeader.php";

if($user->isUserHasRights($user->loggedUser(), 11, "b")) {
  if(isset($_GET["report"]) && !empty($_GET["report"]) && is_numeric($_GET["report"]) && $_GET["report"] > 0 && isset($_GET["delete"])) {
    $r = $web->findReport($_GET['report']);
    if(isset($r) && !empty($r) && $r['report_id'] > 0) {
      $reported = null;
      if($r['report_type'] == "user") {
      	$reported = $user->getUserName($r['report_reported']); 
      }
      else if($r['report_type'] == "comment") {
      	$c = $content->getComment($r['report_reported']);
      	$reported = substr($content->clearUserInputAll($c['comment']), 0, 30).((strlen($c['comment']) > 30) ? " .." : null);
      }

      $web->addToTitle(" - ".$lang->getLocale("ADMIN_RP_T6")." - ".$reported); 
      ?>
      <!-- Page Heading -->
      <section class="content-header">
        <h1><?php echo $lang->getLocale("ADMIN_RP_T1");?><small><?php echo $web->getAdministrationName();?></small></h1>
        <ol class="breadcrumb">
          <li><a href="<?php echo $web->getUrl("admin/adm.php");?>"><i class="fa fa-dashboard"></i> <?php echo $web->getAdministrationName();?></a></li>
          <li><a href="<?php echo $web->getUrl("admin/adm.php?page=reports");?>"><?php echo $lang->getLocale("ADMIN_RP_T1");?></a></li>
          <li class="active"><?php echo $lang->getLocale('ADMIN_RP_T6');?> - <?php echo $reported;?></li>
        </ol>
      </section>

       <section class="content">
        <div class="row">
          <div class="col-xs-12 col-md-12">

            <div class="box box-danger">
              <div class="box-header">
                <h3 class="box-title"><?php echo $lang->getLocale('ADMIN_RP_T6');?> - <?php echo $reported;?></h3>
              </div>

              <div class="box-body">
                <form method="post">
                  <p><?php echo sprintf($lang->getLocale('ADMIN_RP_T7'), $reported);?></p> 
                  <input type="submit" name="deleteReport" value="<?php echo $lang->getLocale('ADMIN_RP_B2');?>" class="btn btn-danger">
                  <a href="<?php echo $web->getUrl("admin/adm.php?page=reports&report=".$r['report_id']);?>" class="btn btn-primary"><?php echo $lang->getLocale('ADMIN_RP_B3');?></a>
                </form>
              </div>
            </div>
            <?php
            if(@$_POST["deleteReport"]) {
            	$db->delete("reports", array("report_id" => $r['report_id']));
            	echo $display->adminAlert($lang->getLocale("ADMIN_E_S"), $lang->getLocale("ADMIN_RP_OK1"), "success"); 
  			$web->redirect($web->getUrl("admin/adm.php?page=reports"), 2);
            }
            ?>
          </div>
        </div>
      </section>
     	<?php
  	}
    	else $web->redirect($web->getUrl("admin/adm.php?page=reports"));  
  }
  else if(isset($_GET["report"]) && !empty($_GET["report"]) && is_numeric($_GET["report"]) && $_GET["report"] > 0) {
    $r = $web->findReport($_GET['report']);
    if(isset($r) && !empty($r) && $r['report_id'] > 0) {
      $reported = null;
      $u = $user->getUserInfo($r['user_id']);
      if($r['report_type'] == "user") {
      	$ru = $user->getUserInfo($r['report_reported']);
      	$reported = $user->getUserName($r['report_reported']); 
      }
      else if($r['report_type'] == "comment") {
        $c = $content->getComment($r['report_reported']);
        $reported = substr($content->clearUserInputAll($c['comment']), 0, 30).((strlen($c['comment']) > 30) ? " .." : null);
      }
      
      if($r['report_timestamp_show'] == 0) $db->update("reports", array("report_timestamp_show" => time()), array("report_id" => $r['report_id']));

      $web->addToTitle(" - ".$lang->getLocale("ADMIN_RP_T3")." - ".$reported); 
      ?>
      <!-- Page Heading -->
      <section class="content-header">
        <h1><?php echo $lang->getLocale("ADMIN_RP_T1");?><small><?php echo $web->getAdministrationName();?></small></h1>
        <ol class="breadcrumb">
          <li><a href="<?php echo $web->getUrl("admin/adm.php");?>"><i class="fa fa-dashboard"></i> <?php echo $web->getAdministrationName();?></a></li>
          <li><a href="<?php echo $web->getUrl("admin/adm.php?page=reports");?>"><?php echo $lang->getLocale("ADMIN_RP_T1");?></a></li>
          <li class="active"><?php echo $reported;?></li>
        </ol>
      </section>

      <section class="content">
  	  <form method="post">
        <div class="row">
          <div class="col-xs-12 col-md-9">
          	
          	<div class="box box-danger">
          		<div class="box-header">
  					<h3 class="box-title"><?php echo $lang->getLocale("ADMIN_RP_T3");?> - <?php echo $reported;?></h3>  
  				</div>
  				<div class="box-body">

  				<?php
  				if($r['report_type'] == "user") { 
  					?>
  					<div class="media">
  						<div class="media-left">
  							<a href="<?php echo $links->getUserLink($u['user_id']);?>" target="_blank"><img class='media-object image-responsive' src='<?php echo $user->getUserAvatar($u['user_id'], '128');?>' width='128px' height='128px' alt='<?php echo $user->getUserName($u['user_id']);?> avatar'></a>
  							<a href="<?php echo $links->getUserLink($u['user_id']);?>" class='btn btn-flat btn-block btn-xs btn-primary' target="_blank"><?php echo $user->getUserName($u['user_id']);?></a>
  						</div>
  						<div class="media-body">
  							<h4 class="media-heading"><?php echo sprintf($lang->getLocale('ADMIN_RP_N_TYPE_user'), $user->getUserName($u['user_id']), $reported);?></h4>
  							<p class='text-muted'><?php echo $lang->getLocale('ADMIN_RP_L4');?>: <?php echo $web->showTime($r['report_timestamp']);?></p>
  							<?php echo $r['report_content'];?>
  						</div>
  						<div class="media-right">
  							<a href="<?php echo $links->getUserLink($ru['user_id']);?>" target="_blank"><img class='media-object image-responsive' src='<?php echo $user->getUserAvatar($ru['user_id'], '128');?>' width='128px' height='128px' alt='<?php echo $user->getUserName($ru['user_id']);?> avatar'></a>
  							<a href="<?php echo $links->getUserLink($ru['user_id']);?>" class='btn btn-flat btn-block btn-xs btn-primary' target="_blank"><?php echo $user->getUserName($ru['user_id']);?></a>
  						</div>
  					</div>
  					<?php
  				}
  				else if($r['report_type'] == "comment") { 
  					?>
  					<div class="media">
  						<div class="media-left">
  							<a href="<?php echo $links->getUserLink($u['user_id']);?>" target="_blank"><img class='media-object image-responsive' src='<?php echo $user->getUserAvatar($u['user_id'], '128');?>' width='128px' height='128px' alt='<?php echo $user->getUserName($u['user_id']);?> avatar'></a>
  							<a href="<?php echo $links->getUserLink($u['user_id']);?>" class='btn btn-flat btn-block btn-xs btn-primary' target="_blank"><?php echo $user->getUserName($u['user_id']);?></a>
  						</div>
  						<div class="media-body">
  							<h4 class="media-heading"><?php echo sprintf($lang->getLocale('ADMIN_RP_N_TYPE_comment'), $user->getUserName($u['user_id']));?></h4>
  							<?php 
  							echo $r['report_content'];

  							echo "<hr>";
  							
  							if(isset($c) && !empty($c) && $c['comment_id'] > 0) {
  								?>
  								<b><a href="<?php echo $links->getUserLink($c['user_id']);?>" target="_blank"><?php echo $user->getUserName($c['user_id'])?></a> <?php echo $lang->getLocale('ADMIN_C_T4');?> <?php echo $links->getPostLinkWithName($c['post_id']);?>:</b>
          						<?php 
          						echo $c['comment'];	
  							}
  							?>


  							
  						</div>
  					</div>
  					<?php
  				}

  				echo "<hr><p class='text-muted'>";
  				if($r['report_timestamp_solved'] == 0 && $r['report_admin'] == 0) {
  					echo $lang->getLocale('ADMIN_RP_L5-1')." - ".$web->showTime($r['report_timestamp_show']);
  				}
  				else if($r['report_timestamp_solved'] > 0 && $r['report_admin'] > 0) {
  					echo $lang->getLocale('ADMIN_RP_L5-2')." - ".$web->showTime($r['report_timestamp_solved'])." - <a href='".$links->getUserLink($r['report_admin'])."' target='_blank'>".$user->getUserName($r['report_admin'])."</a>";
  				}
  				echo "</p>";
  				?>
  				</div>
          	</div>

          	<div class="box box-info">
          		<div class="box-header">
  					<h3 class="box-title"><?php echo $lang->getLocale("ADMIN_RP_T5");?></h3>  
  				</div>
  				<div class="box-body">

  				<?php
  				if($r['report_timestamp_solved'] == 0 && $r['report_admin'] == 0) {
  					?>
  					<div class="form-group">
  	                  <label for="l_message_text"><?php echo $lang->getLocale('ADMIN_RP_T5-L');?></label>
  	                  <?php echo $content->editableInput("message_text");?>
  	                </div>
  					<?php
  				}
  				else if($r['report_timestamp_solved'] > 0 && $r['report_admin'] > 0) {
  					$m = $web->getMessageData($r['message_id']);
  					if(isset($m) && !empty($m) && $m['message_id'] > 0) {
  						echo $m['message_text'];
  					}
  					else echo "<p class='text-warning'>".$lang->getLocale('ADMIN_RP_E1')."</p>";
  				}
  				?>

  				</div>
          	</div>

          </div>
          <div class="col-xs-12 col-md-3">
          	<div class="box box-danger">
          		<div class="box-header">
  					<h3 class="box-title"><?php echo $lang->getLocale("ADMIN_RP_T4");?></h3>  
  				</div>
  				<div class="box-body">
  					<?php
  					if($r['report_timestamp_solved'] == 0 && $r['report_admin'] == 0) {
  						?><input type="submit" name="reportCheck" value="<?php echo $lang->getLocale('ADMIN_RP_B1');?>" class="btn btn-block btn-success"><?php
  					}
  					else if($r['report_timestamp_solved'] > 0 && $r['report_admin'] > 0) {
  						?><input type="submit" value="<?php echo $lang->getLocale('ADMIN_RP_B1');?>" class="btn disabled btn-block btn-success"><?php
  					}
  					?>
  					<a href="<?php echo $web->getUrl('admin/adm.php?page=reports&report='.$r['report_id'].'&delete');?>" class="btn btn-block btn-danger"><?php echo $lang->getLocale('ADMIN_RP_B2');?></a>
  				</div>
  			</div>
          </div>
        </div>
        </form>

        <?php
        if(@$_POST["reportCheck"]) {
        	if(isset($_POST["message_text"]) && !empty($_POST["message_text"])) {
      		$msg = $web->createMessage(array(
  				"message_subject" => $lang->getLocale("ADMIN_RP_TYPE_".$r['report_type']),
  				"message_text" => sprintf($lang->getLocale("ADMIN_RP_MESSAGE"), 
  					$web->showTime($r['report_timestamp']),
  					$lang->getLocale("ADMIN_RP_TYPE_".$r['report_type']),
  					$r['report_content'],
  					$web->showTime(time()),
  					"<a href='".$links->getUserLink($user->loggedUser())."' target='_blank'>".$user->getUserName($user->loggedUser())."</a>",
  					$content->clearUserInput($_POST["message_text"])
  				),
  				"message_type" => "1",
  				"user_id_s" => $user->loggedUser(),
  				"user_id_r" => $r['user_id'],
  				"message_timestamp_send" => time(),
  				"message_timestamp_showed" => 0
  			));  		

  			$db->update("reports", array(
  				"report_admin" => $user->loggedUser(),
  				"report_timestamp_solved" => time(),
  				"message_id" => $msg
  			), array("report_id" => $r['report_id']));	
  			echo $display->adminAlert($lang->getLocale("ADMIN_E_S"), $lang->getLocale("ADMIN_RP_OK2"), "success"); 
  			$web->redirect($web->getUrl("admin/adm.php?page=reports&report=".$r['report_id']), 2);
        	}
  		else {
  			echo $display->adminAlert($lang->getLocale("ADMIN_E_W"), $lang->getLocale("ADMIN_E_E1"), "danger"); 
  			$web->redirect($web->getUrl("admin/adm.php?page=reports&report=".$r['report_id']), 2);
  		}
        }
        ?>
      </section>
      <?php
    }
    else $web->redirect($web->getUrl("admin/adm.php?page=reports"));  
  }
  else {
    ?>
    <!-- Page Heading -->
    <section class="content-header">
      <h1><?php echo $lang->getLocale("ADMIN_RP_T1");?><small><?php echo $web->getAdministrationName();?></small></h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo $web->getUrl("admin/adm.php");?>"><i class="fa fa-dashboard"></i> <?php echo $web->getAdministrationName();?></a></li>
        <li class="active"><?php echo $lang->getLocale("ADMIN_RP_T1");?></li>
      </ol>
    </section>

    <section class="content">
      <div class="row">
        <div class="col-lg-12">

          <div class="box box-primary">
            <div class="box-header">
              <h3 class="box-title"><?php echo $lang->getLocale("ADMIN_RP_T2");?></h3>  
            </div>
            
            <div class="box-body no-padding"> 
              <?php echo $content->dataTable("reportsTable", "table");?>
              <thead>
                <tr>
                  <th><?php echo $lang->getLocale("ADMIN_RP_L1");?></th>
                  <th><?php echo $lang->getLocale("ADMIN_RP_L2");?></th>
                  <th><?php echo $lang->getLocale("ADMIN_RP_L3");?></th>
                  <th><?php echo $lang->getLocale("ADMIN_RP_L4");?></th>            
                </tr>
              </thead>
              <tbody>
                <?php
                $n = $web->getReports();
                if(isset($n) && count($n) > 0) {
                  for($i = 0;$i < count($n);$i ++) {
                    ?>
                    <tr>
                      <td>
                        <a href="<?php echo $web->getUrl("admin/adm.php?page=reports&report=".$n[$i]['report_id']);?>">
                        <?php
                        if($n[$i]['report_timestamp_solved'] > 0 && $n[$i]['report_admin'] > 0) {
                        	echo $web->showToolTip("<i class='fa fa-check' aria-hidden='true'></i>", $lang->getLocale('ADMIN_RP_T8'))."&nbsp;";
                        }

                        if($n[$i]['report_type'] == "user") {
                          echo $user->getUserName($n[$i]['report_reported']); 
                        }
                        else if($n[$i]['report_type'] == "comment") {
                          $c = $content->getComment($n[$i]['report_reported']);
                          echo substr($content->clearUserInputAll($c['comment']), 0, 100).((strlen($c['comment']) > 100) ? " .." : null);
                        }
                        ?></a>
                      </td>
                      <td><?php echo $lang->getLocale("ADMIN_RP_TYPE_".$n[$i]['report_type']);?></td>
                      <td><a href="<?php echo $links->getUserLink($n[$i]['user_id']);?>" target="_blank"><?php echo $user->getUserName($n[$i]['user_id'])?></a></td>
                      <td><?php echo $web->showTime($n[$i]['report_timestamp']);?></td>
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