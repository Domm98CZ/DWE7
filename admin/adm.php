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
* File: admin/adm.php
* Filepath: admin/adm.php
*/

require_once "../_core/maincore.php"; 

$web->clearHead();
$web->clearAdditionalJavascript();
$web->clearAdditionalScripts();

$debug->log("DWE7Admin: Loading..");
$web->addToTitle(" - ".$lang->getLocale("ADMIN_T1"));

if($user->isUserLogged() && isset($_SESSION["aid"]) && !empty($_SESSION["aid"])) {
	if($user->isUserHasRights($user->loggedUser(), 2, "b")) {
		if($web->getUserAdministrationAccess($user->loggedUser(), $user->getUserDeviceAuthKey($user->loggedUser()), $_SESSION["aid"]) == 1) {
			$debug->log("DWE7Admin: Access granted. [ User: ".$user->getUserName($user->loggedUser())." ]");
		}
		else if($web->getUserAdministrationAccess($user->loggedUser(), $user->getUserDeviceAuthKey($user->loggedUser()), $_SESSION["aid"]) == 2) {
			$debug->log("DWE7Admin: Access declined. [ Reason: admin:aid token lifetime. ]");
			$web->redirect($web->getUrl("admin/leave.php"));
		}
		else $web->redirect($web->getUrl());
	}
	else $web->redirect($web->getUrl());
}
else $web->redirect($web->getUrl());

$userAdminDesign = $user->getUserSettings($user->loggedUser(), "admin-design");
if($userAdminDesign == null) $userAdminDesign = "skin-blue";
$userMenuDesign = $user->getUserSettings($user->loggedUser(), "admin-menu-design");
?><!DOCTYPE html>
<html> 
	<head>
		<title>%%%WEB_TITLE%%%</title>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8">
		<meta charset="utf-8">
		<meta name="robots" content="noindex, nofollow">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<meta name="generator" content="<?php echo $web->getWebVersionAsString();?>" />

		<!-- Web Styles -->
		<link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />   
	    <link href="//code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet" type="text/css" />    
	    <link href="assets/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
	    <link href="assets/css/skins/_all-skins.min.css" rel="stylesheet" type="text/css" />
	    <link href="assets/css/main.css" rel="stylesheet" type="text/css" />     

    	<script src="plugins/jQuery/jQuery-2.1.4.min.js" type="text/javascript"></script>

		<?php
		if($web->getSettings("web:favicon")) echo "<link rel='shortcut icon' href='".$web->getSettings("web:favicon")."'/>\n";
		else echo "<link rel='shortcut icon' href='".$web->getUrl()."assets/images/favicon.png'/>\n";
		?>
		<!-- Custom Fonts --> 
		<link href="<?php echo $web->getSettings("scripts:font-awesome");?>" rel="stylesheet">
		<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
	    <!--[if lt IE 9]>
	        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
	        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	    <![endif]-->
		
		<!-- Other -->
		<pagehead>%%%PAGE_HEAD%%%</pagehead>
	</head>
	<body class="<?php echo $userAdminDesign.(($userMenuDesign == true) ? "-".$userMenuDesign : null);?> sidebar-mini">
	    <div class="wrapper">
	      
	      <header class="main-header">
	        <!-- Logo -->
	        <a href="<?php echo $web->getUrl("admin/adm.php");?>" class="logo">
	          <span class="logo-mini"><b>DWE</b></span>
	          <span class="logo-lg"><b>DWE7</b>Admin</span>
	        </a>
	        <!-- Header Navbar: style can be found in header.less -->
	        <nav class="navbar navbar-static-top" role="navigation">
	          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
	            <span class="sr-only">Toggle navigation</span>
	          </a>
	          <!-- Sidebar toggle button-->
	          <div class="navbar-custom-menu">
	            <ul class="nav navbar-nav">
				  <li>
				  	<a href="<?php echo $web->getUrl();?>" target="_blank" title="<?php echo $lang->getLocale('ADMIN_WINDOW_5');?>">
				  		<i class='fa fa-eye' aria-hidden='true'></i>
				  		<span class="hidden-xs hidden-sm">&nbsp;<?php echo $lang->getLocale('ADMIN_WINDOW_5');?></span>
				  	</a>
				  </li>
				  <li>
				  	<a href="<?php echo $web->getUrl("admin/adm.php?page=news&add");?>" title="<?php echo $lang->getLocale('ADMIN_WINDOW_6');?>">
				  		<i class='fa fa-pencil-square-o' aria-hidden='true'></i>
				  		<span class="hidden-xs hidden-sm">&nbsp;<?php echo $lang->getLocale('ADMIN_WINDOW_6');?></span>
				  		</a>
				  </li>
				  <li class="dropdown notifications-menu">
	                <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
	                  <i class="fa fa-flag-o"></i>
	                </a>
	                <ul class="dropdown-menu">
	                  <li>
	                    <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: 200px;">
	                    <ul class="menu" style="overflow: hidden; width: 100%; height: 200px;">
						<?php
						$n = $web->getReports(10);
						if(isset($n) && count($n) > 0) {
							for($i = 0;$i < count($n);$i ++) {
								?><li><a href="<?php echo $web->getUrl("admin/adm.php?page=reports&report=".$n[$i]['report_id']);?>">
								<?php
								if($n[$i]['report_type'] == "user") {
									echo sprintf($lang->getLocale("ADMIN_RP_N_TYPE_user"), $user->getUserName($n[$i]['user_id']), $user->getUserName($n[$i]['report_reported']));
								}
								else if($n[$i]['report_type'] == "comment") {
									echo sprintf($lang->getLocale("ADMIN_RP_N_TYPE_comment"), $user->getUserName($n[$i]['user_id']));
								}
								?></a></li><?php
							}
						}
						?>
	                      
	                    </ul>
	                    <div class="slimScrollBar" style="background: rgb(0, 0, 0); width: 3px; position: absolute; top: 0px; opacity: 0.4; display: none; border-radius: 7px; z-index: 99; right: 1px; height: 195.122px;"></div>
	                    <div class="slimScrollRail" style="width: 3px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; background: rgb(51, 51, 51); opacity: 0.2; z-index: 90; right: 1px;"></div>
	                    </div>
	                  </li>
	                  <li class="footer"><a href="<?php echo $web->getUrl("admin/adm.php?page=reports");?>"><?php echo $lang->getLocale('ADMIN_WINDOW_7');?></a></li>
	                </ul>
	              </li>
				  <li class="dropdown notifications-menu">
	                <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
	                  <i class="fa fa-comments-o"></i>
	                </a>
	                <ul class="dropdown-menu">
	                  <li>
	                    <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: 200px;">
	                    <ul class="menu" style="overflow: hidden; width: 100%; height: 200px;">
						<?php
						$n = $content->getAllComments(0, 10);
						if(isset($n) && count($n) > 0) {
							for($i = 0;$i < count($n);$i ++) {
								?><li><a href="<?php echo $web->getUrl("admin/adm.php?page=comments&comment=".$n[$i]['comment_id']);?>"><?php echo sprintf($lang->getLocale('ADMIN_WINDOW_8'), $user->getUserName($n[$i]['user_id']), htmlentities($content->getPostName($n[$i]['post_id'])));?></a></li><?php
							}
						}
						?>
	                      
	                    </ul>
	                    <div class="slimScrollBar" style="background: rgb(0, 0, 0); width: 3px; position: absolute; top: 0px; opacity: 0.4; display: none; border-radius: 7px; z-index: 99; right: 1px; height: 195.122px;"></div>
	                    <div class="slimScrollRail" style="width: 3px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; background: rgb(51, 51, 51); opacity: 0.2; z-index: 90; right: 1px;"></div>
	                    </div>
	                  </li>
	                  <li class="footer"><a href="<?php echo $web->getUrl("admin/adm.php?page=comments");?>"><?php echo $lang->getLocale('ADMIN_WINDOW_7');?></a></li>
	                </ul>
	              </li>
	              <li class="dropdown user user-menu">
	                <a href="<?php echo $web->getUrl("admin/adm.php?page=profile");?>" class="dropdown-toggle" data-toggle="dropdown">
	                  <img src="<?php echo $user->getUserAvatar($user->loggedUser());?>" class="user-image" alt="User Image"/>
	                  <span class="hidden-xs"><?php echo $user->getUserName($user->loggedUser());?></span>
	                </a>

	              </li>

	              <li>
	              	<a href="<?php echo $web->getUrl("admin/leave.php");?>"><i class="fa fa-sign-out" aria-hidden="true"></i></a>
	              </li>
	            </ul>
	          </div>
	        </nav>
	      </header>

	      
	      <aside class="main-sidebar">
	        <section class="sidebar">

	          <ul class="sidebar-menu">
	          	<?php 
	          	$page = (isset($_GET["page"]) && !empty($_GET["page"]) ? $_GET["page"] : "dashboard");
		        
	          	// Admin Menu
		        $am = $links->getMenu(-1);

		        // Default Admin Menu
		        $def_am = null;

		        

		        $def_am = array(
		        	$lang->getLocale("ADMIN_MENU_1") => "---",
		        	"<i class='fa fa-tachometer' aria-hidden='true'></i>|||".$lang->getLocale("ADMIN_MENU_1_1") => $web->getUrl("admin/adm.php?page=dashboard")
		        );
		     
		        $def_am[$lang->getLocale("ADMIN_MENU_2")] = "---";
		        if($user->isUserHasRights($user->loggedUser(), 3, "b")) {
		        	$def_am["<i class='fa fa-newspaper-o' aria-hidden='true'></i>|||".$lang->getLocale("ADMIN_MENU_2_1")] = $web->getUrl("admin/adm.php?page=news");
		        }
		        if($user->isUserHasRights($user->loggedUser(), 4, "b")) {
		        	$def_am["<i class='fa fa-list-alt' aria-hidden='true'></i>|||".$lang->getLocale("ADMIN_MENU_2_2") ] = $web->getUrl("admin/adm.php?page=news_categories");
		        }
		        if($user->isUserHasRights($user->loggedUser(), 5, "b")) {
		        	$def_am["<i class='fa fa-comments' aria-hidden='true'></i>|||".$lang->getLocale("ADMIN_MENU_2_3")] = $web->getUrl("admin/adm.php?page=comments");
		        }
		        if($user->isUserHasRights($user->loggedUser(), 6, "b")) {
		        	$def_am["<i class='fa fa-file' aria-hidden='true'></i>|||".$lang->getLocale("ADMIN_MENU_2_4")] = $web->getUrl("admin/adm.php?page=pages");
		        }
		        if($user->isUserHasRights($user->loggedUser(), 7, "b")) {
		        	$def_am["<i class='fa fa-upload' aria-hidden='true'></i>|||".$lang->getLocale("ADMIN_MENU_2_5")] = $web->getUrl("admin/adm.php?page=uploads");
		        }

		        $def_am[$lang->getLocale("ADMIN_MENU_3")] = "---";
		        if($user->isUserHasRights($user->loggedUser(), 8, "b")) {
		        	$def_am["<i class='fa fa-user' aria-hidden='true'></i>|||".$lang->getLocale("ADMIN_MENU_3_1")] = $web->getUrl("admin/adm.php?page=users");
		        }
		        if($user->isUserHasRights($user->loggedUser(), 9, "b")) {
		        	$def_am["<i class='fa fa-users' aria-hidden='true'></i>|||".$lang->getLocale("ADMIN_MENU_3_2")] = $web->getUrl("admin/adm.php?page=usergroups");
		        }
		        if($user->isUserHasRights($user->loggedUser(), 10, "b")) {
		        	$def_am["<i class='fa fa-envelope' aria-hidden='true'></i>|||".$lang->getLocale("ADMIN_MENU_3_3")] = $web->getUrl("admin/adm.php?page=message");
		        }
		        if($user->isUserHasRights($user->loggedUser(), 11, "b")) {
		        	$def_am["<i class='fa fa-flag' aria-hidden='true'></i>|||".$lang->getLocale("ADMIN_MENU_3_4") ] = $web->getUrl("admin/adm.php?page=reports");
		        }

		        $def_am[$lang->getLocale("ADMIN_MENU_4")] = "---";
		        if($user->isUserHasRights($user->loggedUser(), 12, "b")) {
		        	$def_am["<i class='fa fa-wrench' aria-hidden='true'></i>|||".$lang->getLocale("ADMIN_MENU_4_1")] = $web->getUrl("admin/adm.php?page=main_settings");
		        }
		        if($user->isUserHasRights($user->loggedUser(), 13, "b")) {
		        	$def_am["<i class='fa fa-cog' aria-hidden='true'></i>|||".$lang->getLocale("ADMIN_MENU_4_2")] = $web->getUrl("admin/adm.php?page=other_settings");
		        }
		        if($user->isUserHasRights($user->loggedUser(), 14, "b")) {
		        	$def_am["<i class='fa fa-list' aria-hidden='true'></i>|||".$lang->getLocale("ADMIN_MENU_4_3")] = $web->getUrl("admin/adm.php?page=sidebars");
		        }
		        if($user->isUserHasRights($user->loggedUser(), 15, "b")) {
		        	$def_am["<i class='fa fa-plug' aria-hidden='true'></i>|||".$lang->getLocale("ADMIN_MENU_4_4")] = $web->getUrl("admin/adm.php?page=plugins");
		        }
		        if($user->isUserHasRights($user->loggedUser(), 16, "b")) {
		        	$def_am["<i class='fa fa-television' aria-hidden='true'></i>|||".$lang->getLocale("ADMIN_MENU_4_5")] = $web->getUrl("admin/adm.php?page=design");
		        }
		        if($user->isUserHasRights($user->loggedUser(), 17, "b")) {
		        	$def_am["<i class='fa fa-link' aria-hidden='true'></i>|||".$lang->getLocale("ADMIN_MENU_4_6")] = $web->getUrl("admin/adm.php?page=menu");
		        }
		        if($user->isUserHasRights($user->loggedUser(), 18, "b")) {
		        	$def_am["<i class='fa fa-refresh' aria-hidden='true'></i>|||".$lang->getLocale("ADMIN_MENU_4_7")] = $web->getUrl("admin/adm.php?page=update");
		        }


						if(count($am) > 0) $am = array_merge($def_am, $am);
						else $am = $def_am;

						if(!empty($am) && is_array($am) && count($am) > 0) {
							$keys = array_keys($am);
							$values = array_values($am);
							for($i = 0;$i < count($keys);$i ++) {
								if (!filter_var($values[$i], FILTER_VALIDATE_URL) === false) {
									$x = explode("|||", $keys[$i]);
									$menuString = null;
									if(count($x) > 1) $menuString = $x[0]." <span>".$x[1]."</span>";
									else $menuString = "<i class='fa fa-asterisk' aria-hidden='true'></i> <span>".$keys[$i]."</span>";
									echo "<li class='treeview".(strtolower($values[$i]) == strtolower($page) ? " active " : null)."'><a href='".$values[$i]."'>".$menuString."</a></li>";

								}
								/*else if(is_array($values[$i])) {

								}*/
								else if($values[$i] == "---") {
									echo "<li class='header'>".$keys[$i]."</li>";
								}
							}
						}
		        ?>
	          </ul>
	        </section>
	      </aside>



	      <div class="content-wrapper">
	       
	        <!---<div id="main_message" class="admin-message">

	          <div class="row">
	            <div class="col-lg-12">
	            </div>
	          </div>

	        </div>-->

			<?php
			echo $content->getAdminContent((isset($_GET["page"]) && !empty($_GET["page"]) ? $_GET["page"] : "dashboard"));
			?>
			</div>

			<footer class="main-footer">
	      		<p class="text-right">Powered by <?php echo $web->getWebVersionAsString();?> <i>(<?php echo $display->getRenderTime();?>)</i></p>
	      	</footer>
      
		<div class='control-sidebar-bg'></div>

		</body>
		<!--- Design Scripts -->
		<script src="assets/js/bootstrap.min.js" type="text/javascript"></script>
		<script src="plugins/morris/morris.min.js" type="text/javascript"></script>
		<script src="assets/js/app.min.js" type="text/javascript"></script>
		<!-- Scripts -->
		<pagescripts>%%%PAGE_SCRIPTS%%%</pagescripts>
		<pagejs>%%%PAGE_JAVASCRIPT</pagejs>
	</body>
</html>
<?php
/* Re-Render Page */
$debug->log("DWE7Admin: Loaded!");
$display->getPageAsString();
$display->clean();
$display->setVarAsPageString($display->replacePageVars(1));
echo $display->getDisplayString();
$control->webRenderTime($web->getActualUrl(), $web->getRenderTime());
?>