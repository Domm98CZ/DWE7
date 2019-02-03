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
* File: messages.php
* Filepath: /pages/messages.php
*/
if(!defined("AUTHORIZED")) { die("Access Denied"); }
require_once DIR_INC."PageHeader.php";

if($user->isUserLogged()) {
	$p = $web->getUrlParam(2);
	echo $display->createpanel($lang->getLocale('P_MESSAGES_TITLE'), "primary");
	?>
	<div class="row">
		<div class="col-sm-3 col-md-2">
			<ul class="nav nav-pills nav-stacked">
				<li<?php echo ((empty($p) || $p == "read") ? " class='active'" : null);?> role="presentation"><a href="<?php echo $web->getUrl('messages');?>"><?php echo $lang->getLocale('P_MESSAGE_T1');?></a></li>
				<li<?php echo (($p == "unread") ? " class='active'" : null);?> role="presentation"><a href="<?php echo $web->getUrl('messages/unread');?>"><?php echo $lang->getLocale('P_MESSAGE_T2');?></a></li>
				<li<?php echo (($p == "admin") ? " class='active'" : null);?> role="presentation"><a href="<?php echo $web->getUrl('messages/admin');?>"><?php echo $lang->getLocale('P_MESSAGE_T3');?></a></li>
				<li<?php echo (($p == "send" || $p == "new") ? " class='active'" : null);?> role="presentation"><a href="<?php echo $web->getUrl('messages/send');?>"><?php echo $lang->getLocale('P_MESSAGE_T4');?></a></li>
			</ul>
			<hr>
			<a href="<?php echo $web->getUrl('messages/new/');?>" class="btn btn-block btn-success"><?php echo $lang->getLocale('P_MESSAGE_B1');?></a>
		</div>
		<div class="col-sm-9 col-md-10">
		<?php
		if(empty($p)) {
			$web->addToTitle(" - ".$lang->getLocale('P_MESSAGES_TITLE')." - ".$lang->getLocale('P_MESSAGE_T1'));

			$m = $db->selectAll("messages", array("user_id_r" => $user->loggedUser()), 'array', 'order by `message_id` desc');
			echo "<div class='table-responsive'>";
			echo "<table class='table table-hover table-condensed table-bordered table-striped'>";
			echo "
				<tr>
					<th width='50%'>".$lang->getLocale('P_MESSAGE_TABLE_H1')."</th>
					<th width='25%'>".$lang->getLocale('P_MESSAGE_TABLE_H2')."</th>
					<th width='25%'>".$lang->getLocale('P_MESSAGE_TABLE_H3')."</th>
				</tr>
			";
			if(count($m) > 0) {
				for($i = 0;$i < count($m);$i ++) {
					echo "<tr".(($m[$i]['message_type'] == 0) ? null : " class='warning'").">
						<td>".(($m[$i]['message_timestamp_showed'] == 0) ? "<i>(".$lang->getLocale('P_MESSAGE_UNREAD').")</i> " : null)."<a href='".$web->getUrl('messages/read/'.$m[$i]['message_id'])."'>".htmlentities($m[$i]['message_subject'])."</a></td>
						<td><a href='".$links->getUserLink($m[$i]['user_id_s'])."'>".$user->getUserName($m[$i]['user_id_s'])."</a></td>
						<td>".$web->showTime($m[$i]['message_timestamp_send'])."</td>
					</tr>";
				}
			}
			else {
				echo "<tr><td colspan='3' class='text-center'>".$lang->getLocale('P_MESSAGE_NO_MESSAGES_1')."</td></tr>";
			}
			echo "</table>";
			echo "</div>";
		}
		else if($p == "read") {
			$m_id = $web->getUrlParam(3);
			if(isset($m_id) && !empty($m_id) && is_numeric($m_id) && $m_id > 0) {
				if($web->isMessageExists($m_id) == $m_id) {
					$m = $web->getMessageData($m_id);
					if(isset($m) && !empty($m)) {
						if($m['user_id_s'] == $user->loggedUser() || $m['user_id_r'] == $user->loggedUser()) {
							$web->addToTitle(" - ".$lang->getLocale('P_MESSAGES_TITLE')." - ".$lang->getLocale('P_MESSAGE_T1'));

							if($m['user_id_r'] == $user->loggedUser()) {
								?>
								<div class='media'>
								  <div class='media-left'>
								    <a target='_blank' href='<?php echo $links->getUserLink($m['user_id_s']);?>'>
								      <img class='media-object image-responsive' src='<?php echo $user->getUserAvatar($m['user_id_s'], 64);?>' width='64px' height='64px' alt='<?php echo $user->getUserName($m['user_id_s']);?> avatar'>
								    </a>
								  </div>
								  <div class='media-body'>
								  	<h4 class='media-heading'><?php echo $lang->getLocale('P_MESSAGE_T9');?> <a target='_blank' href='<?php echo $links->getUserLink($m['user_id_s']);?>'><?php echo $user->getUserName($m['user_id_s']);?></a></h4>
								  	<?php 
								  	echo $lang->getLocale('P_MESSAGE_T6')." ".$web->showTime($m['message_timestamp_send'])."";

									if($m['message_timestamp_showed'] == 0 && $m['user_id_r'] == $user->loggedUser()) {
										$web->updateMessageData($m['message_id'], array("message_timestamp_showed" => time()));
										echo "<br>".sprintf($lang->getLocale('P_MESSAGE_T11'), $web->showTime(time()));
									}
									else if($m['message_timestamp_showed'] > 0) {
										echo "<br>".sprintf($lang->getLocale('P_MESSAGE_T11'), $web->showTime($m['message_timestamp_showed']));
									}
									?>
								  </div>
								</div>
								<?php
							}
							else if($m['user_id_s'] == $user->loggedUser()) {
								?>
								<div class='media'>
								  <div class='media-left'>
								    <a target='_blank' href='<?php echo $links->getUserLink($m['user_id_r']);?>'>
								      <img class='media-object image-responsive' src='<?php echo $user->getUserAvatar($m['user_id_r'], 64);?>' width='64px' height='64px' alt='<?php echo $user->getUserName($m['user_id_r']);?> avatar'>
								    </a>
								  </div>
								  <div class='media-body'>
								  	<h4 class='media-heading'><?php echo $lang->getLocale('P_MESSAGE_T12');?> <a target='_blank' href='<?php echo $links->getUserLink($m['user_id_r']);?>'><?php echo $user->getUserName($m['user_id_r']);?></a></h4>
								  	<?php 
								  	echo $lang->getLocale('P_MESSAGE_T6')." ".$web->showTime($m['message_timestamp_send'])."";

								  	if($m['message_timestamp_showed'] > 0) {
										echo "<br>".sprintf($lang->getLocale('P_MESSAGE_T10'), "<a target='_blank' href='".$links->getUserLink($m['user_id_r'])."'>".$user->getUserName($m['user_id_r'])."</a>", $web->showTime($m['message_timestamp_showed']));
									}
									else {
										echo "<br><small>".sprintf($lang->getLocale('P_MESSAGE_T13'), "<a target='_blank' href='".$links->getUserLink($m['user_id_r'])."'>".$user->getUserName($m['user_id_r'])."</a>")."</small>";
									}
								  	?>
								  </div>
								</div>
								<?php
							}

							echo "<hr>";
							echo $m['message_text'];

							if($m['message_type'] == 0) {
								?>
								<hr>
								<h4><?php 
								if($m['user_id_r'] == $user->loggedUser()) {
									echo sprintf($lang->getLocale('P_MESSAGE_T15'), "<a target='_blank' href='".$links->getUserLink($m['user_id_s'])."'>".$user->getUserName($m['user_id_s'])."</a>");	
								}
								else if($m['user_id_s'] == $user->loggedUser()) { 
									echo sprintf($lang->getLocale('P_MESSAGE_T16'), "<a target='_blank' href='".$links->getUserLink($m['user_id_r'])."'>".$user->getUserName($m['user_id_r'])."</a>");
								}
								?></h4>
								<form method='post'>
									<div class='form-group'>
										<?php echo $content->editableInput("message_text", $lang->getLocale('P_MESSAGE_T14'));?>
									</div>

									<div class="checkbox">
									    <label>
									      <input name='message_reply' type="checkbox"> <?php echo $lang->getLocale('P_MESSAGE_L1');?>
									    </label>
									  </div>

									<div class="form-group">
										<input type="submit" name="message_reply_send" value="<?php echo $lang->getLocale('P_MESSAGE_B2');?>" class="btn btn-primary">
									</div>
								</form>
								<?php
								if(@$_POST['message_reply_send']) {
									$cc = is_text_ok($_POST['message_text']);
				    				if($cc == 1) {
				    					$messageText = null;
				    					if(isset($_POST['message_reply'])) {
				    						$messageText .= "<blockquote><strong>".($user->getUserName($m['user_id_s']).", ".$web->showTime($m['message_timestamp_send'])."").":</strong> ".$m['message_text']."</blockquote>";
				    					}
				    					$messageText .= $_POST['message_text'];

				    					$subject = $m['message_subject'];
				    					if(substr($subject, 0, 3) != "RE:") $subject = "RE: ".$subject;

				    					$n_id = $web->createMessage(array(
				    						"message_subject" => $subject,
				    						"message_text" => $content->clearUserInput($messageText),
			    							"message_type" => "0",
			    							"user_id_s" => $user->loggedUser(),
			    							"user_id_r" => (($m['user_id_r'] == $user->loggedUser()) ? $m['user_id_s'] : $m['user_id_r']),
			    							"message_timestamp_send" => time(),
			    							"message_timestamp_showed" => 0
				    					));

				    					//echo "<p class='text-success'>".$lang->getLocale('P_MESSAGE_OK1')."</p>";
				    					$web->redirect($web->getUrl('messages/read/'.$n_id));
				    				}
				    				else {
				    					echo "<p class='text-warning'>".$lang->getLocale('P_MESSAGE_E1')."</p>";
				    					$web->redirect($web->getActualUrl(), 2);
				    				}
								}
							}

							echo "<hr>";
							echo "<a href='".$web->getUrl('messages')."'><i class='fa fa-arrow-left' aria-hidden='true'></i> ".$lang->getLocale('P_MESSAGE_T8')."</a>";

						}
						else $web->redirect($web->getUrl('messages'));
					}
					else $web->redirect($web->getUrl('messages'));
				}
				else $web->redirect($web->getUrl('messages'));
			}
			else $web->redirect($web->getUrl('messages'));
		}
		else if($p == "unread") {
			$web->addToTitle(" - ".$lang->getLocale('P_MESSAGES_TITLE')." - ".$lang->getLocale('P_MESSAGE_T2'));

			$m = $db->selectAll("messages", array("user_id_r" => $user->loggedUser(), "message_timestamp_showed" => "0"), 'array', 'order by `message_id` desc');
			echo "<div class='table-responsive'>";
			echo "<table class='table table-hover table-condensed table-bordered table-striped'>";
			echo "
				<tr>
					<th width='50%'>".$lang->getLocale('P_MESSAGE_TABLE_H1')."</th>
					<th width='25%'>".$lang->getLocale('P_MESSAGE_TABLE_H2')."</th>
					<th width='25%'>".$lang->getLocale('P_MESSAGE_TABLE_H3')."</th>
				</tr>
			";
			if(count($m) > 0) {
				for($i = 0;$i < count($m);$i ++) {
					echo "<tr>
						<td><a href='".$web->getUrl('messages/read/'.$m[$i]['message_id'])."'>".htmlentities($m[$i]['message_subject'])."</a></td>
						<td><a href='".$links->getUserLink($m[$i]['user_id_s'])."'>".$user->getUserName($m[$i]['user_id_s'])."</a></td>
						<td>".$web->showTime($m[$i]['message_timestamp_send'])."</td>
					</tr>";
				}
			}
			else {
				echo "<tr><td colspan='3' class='text-center'>".$lang->getLocale('P_MESSAGE_NO_MESSAGES_3')."</td></tr>";
			}
			echo "</table>";
			echo "</div>";
		} 
		else if($p == "admin") {
			$web->addToTitle(" - ".$lang->getLocale('P_MESSAGES_TITLE')." - ".$lang->getLocale('P_MESSAGE_T3'));

			$m = $db->selectAll("messages", array("user_id_r" => $user->loggedUser()), 'array', 'and `message_type` > 0 order by `message_id` desc');
			echo "<div class='table-responsive'>";
			echo "<table class='table table-hover table-condensed table-bordered table-striped'>";
			echo "
				<tr>
					<th width='50%'>".$lang->getLocale('P_MESSAGE_TABLE_H1')."</th>
					<th width='25%'>".$lang->getLocale('P_MESSAGE_TABLE_H2')."</th>
					<th width='25%'>".$lang->getLocale('P_MESSAGE_TABLE_H3')."</th>
				</tr>
			";
			if(count($m) > 0) {
				for($i = 0;$i < count($m);$i ++) {
					echo "<tr>
						<td><a href='".$web->getUrl('messages/read/'.$m[$i]['message_id'])."'>".htmlentities($m[$i]['message_subject'])."</a></td>
						<td><a href='".$links->getUserLink($m[$i]['user_id_s'])."'>".$user->getUserName($m[$i]['user_id_s'])."</a></td>
						<td>".$web->showTime($m[$i]['message_timestamp_send'])."</td>
					</tr>";
				}
			}
			else {
				echo "<tr><td colspan='3' class='text-center'>".$lang->getLocale('P_MESSAGE_NO_MESSAGES_4')."</td></tr>";
			}
			echo "</table>";
			echo "</div>";
		} 
		else if($p == "send") {
			$web->addToTitle(" - ".$lang->getLocale('P_MESSAGES_TITLE')." - ".$lang->getLocale('P_MESSAGE_T4'));

			$m = $db->selectAll("messages", array("user_id_s" => $user->loggedUser(), "message_type" => "0"), 'array', 'order by `message_id` desc');
			echo "<div class='table-responsive'>";
			echo "<table class='table table-hover table-condensed table-bordered table-striped'>";
			echo "
				<tr>
					<th width='50%'>".$lang->getLocale('P_MESSAGE_TABLE_H1')."</th>
					<th width='25%'>".$lang->getLocale('P_MESSAGE_TABLE_H4')."</th>
					<th width='25%'>".$lang->getLocale('P_MESSAGE_TABLE_H3')."</th>
				</tr>
			";
			if(count($m) > 0) {
				for($i = 0;$i < count($m);$i ++) {
					echo "<tr>
						<td><a href='".$web->getUrl('messages/read/'.$m[$i]['message_id'])."'>".htmlentities($m[$i]['message_subject'])."</a></td>
						<td><a href='".$links->getUserLink($m[$i]['user_id_r'])."'>".$user->getUserName($m[$i]['user_id_r'])."</a></td>
						<td>".$web->showTime($m[$i]['message_timestamp_send'])."</td>
					</tr>";
				}
			}
			else {
				echo "<tr><td colspan='3' class='text-center'>".$lang->getLocale('P_MESSAGE_NO_MESSAGES_2')."</td></tr>";
			}
			echo "</table>";
			echo "</div>";
		}  
		else if($p == "new") {
			$web->addToTitle(" - ".$lang->getLocale('P_MESSAGES_TITLE')." - ".$lang->getLocale('P_MESSAGE_B1'));
			?>
			<form method="post">
				<div class="form-group">
					<label for="l_user_name"><?php echo $lang->getLocale('P_MESSAGE_L3');?></label>				    
					<?php
					$usr = $web->getUrlParam(3);
					$u = $user->getUserInfo($user->isUserExists($usr));
					if(isset($u) && !empty($u) && $u['user_id'] > 0) {
						echo "<input type='text' name='user_name' class='form-control' id='l_user_name' value='".$u['user_name']."' readonly>";
					}
					else {
						echo "<input type='text' name='user_name' class='form-control' id='l_user_name'>";
					}
					?>
				</div>

				<div class="form-group">
					<label for="l_message_subject"><?php echo $lang->getLocale('P_MESSAGE_L4');?></label>
					<input type='text' name='message_subject' class='form-control' id='l_message_subject' maxlength="50">
				</div>

				<div class="form-group">
					<label for="l_message_text"><?php echo $lang->getLocale('P_MESSAGE_L5');?></label>
					<?php echo $content->editableInput("message_text", $lang->getLocale('P_MESSAGE_T14'));?>
				</div>

				<div class="form-group">
					<input type="submit" name="message_send" value="<?php echo $lang->getLocale('P_MESSAGE_B2');?>" class="btn btn-primary">
				</div>
			</form>
			<?php
			if(@$_POST["message_send"]) {
				if(is_set($_POST['user_name']) && is_set($_POST['message_subject']) && is_set($_POST['message_text'])) {
					$cc = is_text_ok($_POST['message_text']);
    				if($cc == 1) {
    					$u_id = $user->isUserExists($_POST['user_name']);
    					if(is_numeric($u_id) && $u_id > 0) {
    						if($u_id != $user->loggedUser()) {

    							$m_title = $content->clearUserInput($_POST['message_subject']);
    							$m_content = $content->clearUserInput($_POST['message_text']); 

    							if(strlen($m_title) > 0) {
    								if(strlen($m_content) > 0) {
										$n_id = $web->createMessage(array(
				    						"message_subject" => $m_title,
				    						"message_text" => $m_content,
			    							"message_type" => "0",
			    							"user_id_s" => $user->loggedUser(),
			    							"user_id_r" => $u_id,
			    							"message_timestamp_send" => time(),
			    							"message_timestamp_showed" => 0
				    					));

				    					//echo "<p class='text-success'>".$lang->getLocale('P_MESSAGE_OK1')."</p>";
		    							$web->redirect($web->getUrl('messages/read/'.$n_id));
    								}
    								else {
				    					echo "<p class='text-warning'>".$lang->getLocale('P_MESSAGE_E1')."</p>";
								    	$web->redirect($web->getActualUrl(), 2);
								    } 
    							}
    							else {
			    					echo "<p class='text-warning'>".$lang->getLocale('P_MESSAGE_E1')."</p>";
							    	$web->redirect($web->getActualUrl(), 2);
							    }
	    					}
	    					else {
	    						echo "<p class='text-warning'>".$lang->getLocale('P_MESSAGE_E4')."</p>";
								$web->redirect($web->getActualUrl(), 2);
	    					}	
    					}
    					else {
    						echo "<p class='text-warning'>".$lang->getLocale('P_MESSAGE_E2')."</p>";
							$web->redirect($web->getActualUrl(), 2);
    					}
    				}
    				else {
    					echo "<p class='text-warning'>".$lang->getLocale('P_MESSAGE_E1')."</p>";
				    	$web->redirect($web->getActualUrl(), 2);
				    }
				}
				else {
					echo "<p class='text-warning'>".$lang->getLocale('P_MESSAGE_E3')."</p>";
					$web->redirect($web->getActualUrl(), 2);
				}
			}
		} 
		else $web->redirect($web->getUrl("messages"));

		echo $display->closepanel();
		?>
		</div>
	</div>
	<?php
}
else $web->redirect($web->getUrl());
?>