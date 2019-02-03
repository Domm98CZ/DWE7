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
* File: message.php
* Filepath: /admin/pages/message.php
*/
if(!defined("AUTHORIZED")) { die("Access Denied"); }
require_once DIR_INC."PageHeader.php";

if($user->isUserHasRights($user->loggedUser(), 10, "b")) {
	$web->addToTitle(" - ".$lang->getLocale("ADMIN_M_T1"));
	?>
	<!-- Page Heading -->
	<section class="content-header">
	  <h1><?php echo $lang->getLocale("ADMIN_M_T1");?><small><?php echo $web->getAdministrationName();?></small></h1>
	  <ol class="breadcrumb">
	    <li><a href="<?php echo $web->getUrl("admin/adm.php");?>"><i class="fa fa-dashboard"></i> <?php echo $web->getAdministrationName();?></a></li>
	    <li class="active"><?php echo $lang->getLocale('ADMIN_M_T1');?></li>
	  </ol>
	</section>
	  
	<section class="content">
		<div class="row">
			<div class="col-xs-12 col-md-12">

				<div class="box box-danger">
					<div class="box-header">
						<h3 class="box-title"><?php echo $lang->getLocale("ADMIN_M_T2");?></h3>
					</div>

					<form method="post">
						<div class="box-body">

							<div class="form-group">
								<label for="l_message_subject"><?php echo $lang->getLocale('P_MESSAGE_L4');?></label>
								<input type='text' name='message_subject' class='form-control' id='l_message_subject' maxlength="50">
							</div>

							<div class="form-group">
								<label for="l_message_text"><?php echo $lang->getLocale('P_MESSAGE_L5');?></label>
								<?php echo $content->editableInput("message_text", $lang->getLocale('P_MESSAGE_T14'));?>
							</div>

						</div>
						<div class="box-footer">
							<input type="submit" name="message_send" value="<?php echo $lang->getLocale('P_MESSAGE_B2');?>" class="btn btn-primary">
						</div>
					</form>
				</div>

				<?php
				if(@$_POST["message_send"]) {
					if(is_set($_POST['message_subject']) && is_set($_POST['message_text'])) {
						$cc = is_text_ok($_POST['message_text']);

						$m_title = $content->clearUserInput($_POST['message_subject']);
						$m_content = $content->clearUserInput($_POST['message_text']); 

						if(strlen($m_title) > 0) {
							if(strlen($m_content) > 0) {

								$u = $user->getAllUsers("user_id");
								if(count($u) > 0) {
									for($i = 0;$i < count($u);$i++) {
										$web->createMessage(array(
				    						"message_subject" => $m_title,
				    						"message_text" => $m_content,
			    							"message_type" => "2",
			    							"user_id_s" => $user->loggedUser(),
			    							"user_id_r" => $u[$i]['user_id'],
			    							"message_timestamp_send" => time(),
			    							"message_timestamp_showed" => 0
				    					));
									}
								}
								echo $display->adminAlert($lang->getLocale("ADMIN_E_S"), $lang->getLocale("ADMIN_M_OK1"), "success"); 
		    				}
		    			}
		    		}
		    		else {
		    			echo $display->adminAlert($lang->getLocale("ADMIN_E_W"), $lang->getLocale("ADMIN_E_E1"), "danger");
		    		}
		    		$web->redirect($web->getUrl("admin/adm.php?page=message"), 2);
		    	}
				?>
				
				<div class="box box-primary">
					<div class="box-header">
						<h3 class="box-title"><?php echo $lang->getLocale("ADMIN_M_T3");?></h3>
					</div>
					<div class="box-body">
					<?php
					$m = $db->selectAll("messages", array("user_id_r" => $user->loggedUser(), "message_type" => "2"), 'array', 'order by `message_id` desc limit 10');
					if(count($m) > 0) {
						for($i = 0;$i < count($m);$i++) {
							?>
							<div class="media">
							  <div class="media-body">
							    <b class="media-heading"><a href='<?php echo $web->getUrl('messages/read/'.$m[$i]['message_id']);?>' target='_blank'><i class='fa fa-envelope'></i> <?php echo htmlentities($m[$i]['message_subject']);?></a> - <?php echo $web->showTime($m[$i]['message_timestamp_send']);?></b><br>
							    <?php echo $m[$i]['message_text'];?>
							  </div>
							</div>
							<?php
	        			}
					}
					else echo "<p class='text-warning'>".$lang->getLocale('ADMIN_M_E1')."</p>";
	        		
					?>	
					</div>
			</div>
		</div>
	</section>
	<?php
}
else {
  $web->redirect($web->getUrl("admin/adm.php"));
}
?>