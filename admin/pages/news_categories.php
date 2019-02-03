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
* File: news_categories.php
* Filepath: /admin/pages/news_categories.php
*/
if(!defined("AUTHORIZED")) { die("Access Denied"); }
require_once DIR_INC."PageHeader.php";

if($user->isUserHasRights($user->loggedUser(), 4, "b")) {
	$web->addToTitle(" - ".$lang->getLocale("ADMIN_NC_T1"));

	if(isset($_GET["category"]) && !empty($_GET["category"]) && is_numeric($_GET["category"]) && $_GET["category"] > 0 && isset($_GET["delete"])) {
	  $c = $content->getNewsCategory($_GET["category"]);
	  if(isset($c) && !empty($c) && $c['newsCategory_id'] > 0) {
	    $web->addToTitle(" - ".$lang->getLocale("ADMIN_NC_T4")." - ".htmlentities($c['newsCategory']));
	    ?>
	    <!-- Page Heading -->
	    <section class="content-header">
	      <h1><?php echo $lang->getLocale("ADMIN_NC_T1");?><small><?php echo $web->getAdministrationName();?></small></h1>
	      <ol class="breadcrumb">
	        <li><a href="<?php echo $web->getUrl("admin/adm.php");?>"><i class="fa fa-dashboard"></i> <?php echo $web->getAdministrationName();?></a></li>
	        <li><a href="<?php echo $web->getUrl("admin/adm.php?page=news_categories");?>"><?php echo $lang->getLocale("ADMIN_NC_T1");?></a></li>
	        <li class="active"><?php echo $lang->getLocale("ADMIN_NC_T4")." - ".htmlentities($c['newsCategory']);?></li>
	      </ol>
	    </section>


	    <section class="content">
	        <div class="row">
	          <div class="col-xs-12 col-md-12">

	            <div class="box box-danger">
	              <div class="box-header">
	                <h3 class="box-title"><?php echo $lang->getLocale("ADMIN_NC_T4")." - ".htmlentities($c['newsCategory']);?></h3>
	              </div>
	              
	              <div class="box-body">

	                <p><?php echo sprintf($lang->getLocale('ADMIN_NC_T5'), htmlentities($c['newsCategory']));?></p>

	                <form method="post">
	                  <input type="submit" name="deleteNewsCategory" value="<?php echo $lang->getLocale('ADMIN_NC_B6');?>" class="btn btn-danger">
	                  <a href="<?php echo $web->getUrl("admin/adm.php?page=news_categories&category=".$c['newsCategory_id']);?>" class="btn btn-primary"><?php echo $lang->getLocale('ADMIN_NC_B7');?></a>
	                </form>

	              </div>
	            </div>

	            <?php
	            if(@$_POST["deleteNewsCategory"]) {
	              $content->deleteNewsCategory($c['newsCategory_id']);
	              echo $display->adminAlert($lang->getLocale("ADMIN_E_S"), $lang->getLocale("ADMIN_NC_OK1"), "success"); 
	              $web->redirect($web->getUrl("admin/adm.php?page=news_categories"), 2);
	            }
	            ?>

	          </div>
	        </div>
	    </section>
	    <?php
	  }
	  else $web->redirect($web->getUrl("admin/adm.php?page=news_categories"));
	}
	else if(isset($_GET["category"]) && !empty($_GET["category"]) && is_numeric($_GET["category"]) && $_GET["category"] > 0) {
		$c = $content->getNewsCategory($_GET["category"]);
		if(isset($c) && !empty($c) && $c['newsCategory_id'] > 0) {
		    $web->addToTitle(" - ".$lang->getLocale("ADMIN_NC_T3")." - ".htmlentities($c['newsCategory']));
		    ?>
		    <!-- Page Heading -->
			<section class="content-header">
			  <h1><?php echo $lang->getLocale("ADMIN_NC_T1");?><small><?php echo $web->getAdministrationName();?></small></h1>
			  <ol class="breadcrumb">
			    <li><a href="<?php echo $web->getUrl("admin/adm.php");?>"><i class="fa fa-dashboard"></i> <?php echo $web->getAdministrationName();?></a></li>
			    <li><a href="<?php echo $web->getUrl("admin/adm.php?page=news_categories");?>"> <?php echo $lang->getLocale("ADMIN_NC_T1");?></a></li>
			    <li class="active"><?php echo $lang->getLocale("ADMIN_NC_T3")." - ".htmlentities($c['newsCategory']);?></li>
			  </ol>
			</section>

			<section class="content">
			  <div class="row">
			    <div class="col-lg-12">

			    	<?php
	         		if(empty($c['newsCategory_url']) || !isset($c['newsCategory_url'])) {
	     				echo $display->adminAlert($lang->getLocale("ADMIN_E_W"), $lang->getLocale("ADMIN_NC_T6"), "warning"); 
	         		}
	         		?>

					<div class="box box-primary">
						<div class="box-header">
							<h3 class="box-title"><?php echo $lang->getLocale("ADMIN_NC_T3")." - ".htmlentities($c['newsCategory']);?></h3>  
							<div class="box-tools pull-right">
								<a href="<?php echo $web->getUrl("admin/adm.php?page=news_categories");?>" class="btn btn-xs btn-warning"><?php echo $lang->getLocale("ADMIN_NC_B2");?></a>
							</div>
						</div>

						<form method="post">
							<div class="box-body">

								<div class="form-group">
									<label for="l_newsCategory"><?php echo $lang->getLocale('ADMIN_NC_L6');?></label>
									<input type="text" name="newsCategory" class="form-control" id="l_newsCategory" value="<?php echo htmlentities($c['newsCategory']);?>">
								</div>

								<div class="form-group">
									<label for="l_newsCategory_url"><?php echo $lang->getLocale('ADMIN_NC_L7');?></label>
									<input type="text" name="newsCategory_url" class="form-control" id="l_newsCategory_url" value="<?php echo $c['newsCategory_url'];?>">
								</div>

								<div class="form-group">
									<label for="newsCategory_desc"><?php echo $lang->getLocale('ADMIN_NC_L8');?></label>
									<?php echo $content->editableInput("newsCategory_desc", null, $c['newsCategory_desc']); ?>
								</div>

							</div>
							<div class="box-footer">
								<input type="submit" name="saveCategory" value="<?php echo $lang->getLocale('ADMIN_NC_B4');?>" class="btn btn-success">
								<?php
								$url = $links->getNewsCategoryLink($c['newsCategory_id']);
								if(!empty($url) && !empty($c['newsCategory_url'])) { ?><a href="<?php echo $url;?>" target="_blank" class="btn btn-primary"><?php echo $lang->getLocale('ADMIN_NC_B3');?></a><?php }
								else { ?><a class="btn btn-primary disabled"><?php echo $lang->getLocale('ADMIN_NC_B3');?></a><?php }
								?>
								<a href="<?php echo $web->getUrl("admin/adm.php?page=news_categories&category=".$c['newsCategory_id']."&delete");?>" class="btn btn-danger"><?php echo $lang->getLocale('ADMIN_NC_B5');?></a>
							</div>
						</form>
					</div>

					<?php
					if(@$_POST["saveCategory"]) {
						if(isset($_POST["newsCategory"]) && !empty($_POST["newsCategory"])) {
							
							/* News Category Title */
							$newsCategory = $content->clearUserInputAll($_POST["newsCategory"]);
							$newsCategory_desc = $content->clearUserInput($_POST["newsCategory_desc"]);
							
							/* News Category URL */
							$newsCategory_url = null;
							if(empty($_POST["newsCategory_url"]) || !isset($_POST["newsCategory_url"])) {
								$newsCategory_url = str_replace(array(" ", "%20"), array("-","-"), strtolower($content->removeDiacritics($content->clearUserInputAll($c['newsCategory_url']))));
							}
							else $newsCategory_url = str_replace(array(" ", "%20"), array("-","-"), strtolower($content->removeDiacritics($content->clearUserInputAll($_POST["newsCategory_url"]))));
							
							if($links->isNewsCategoryLinkExists($newsCategory_url, $c['newsCategory_id']) != 0) {
								$newsCategory_url = "";
							}
							
							$content->updateNewsCategory($c['newsCategory_id'], array(
								"newsCategory" => $newsCategory,
								"newsCategory_url" => $newsCategory_url, 
								"newsCategory_desc" => $newsCategory_desc
							));
							
							echo $display->adminAlert($lang->getLocale("ADMIN_E_S"), $lang->getLocale("ADMIN_NC_OK2"), "success"); 
						}
						else {
							echo $display->adminAlert($lang->getLocale("ADMIN_E_W"), $lang->getLocale("ADMIN_E_E1"), "danger"); 
						}
						$web->redirect($web->getUrl("admin/adm.php?page=news_categories&category=".$c['newsCategory_id']), 2);
					}
					?>

			    </div>
			  </div>
			</section>
		    <?php
		}
		else $web->redirect($web->getUrl("admin/adm.php?page=news_categories"));
	}
	else if(isset($_GET["add"])) {
	  $cid = $content->createNewsCategory();
	  $web->redirect($web->getUrl("admin/adm.php?page=news_categories&category=".$cid));
	}
	else {
		?>
		<!-- Page Heading -->
		<section class="content-header">
		  <h1><?php echo $lang->getLocale("ADMIN_NC_T1");?><small><?php echo $web->getAdministrationName();?></small></h1>
		  <ol class="breadcrumb">
		    <li><a href="<?php echo $web->getUrl("admin/adm.php");?>"><i class="fa fa-dashboard"></i> <?php echo $web->getAdministrationName();?></a></li>
		    <li class="active"><?php echo $lang->getLocale("ADMIN_NC_T1");?></li>
		  </ol>
		</section>

		<section class="content">
		  <div class="row">
		    <div class="col-lg-12">

		      <div class="box box-primary">
		        <div class="box-header">
		          <h3 class="box-title"><?php echo $lang->getLocale("ADMIN_NC_T2");?></h3>  
		          <div class="box-tools pull-right">
		            <a href="<?php echo $web->getUrl("admin/adm.php?page=news_categories&add");?>" class="btn btn-xs btn-primary"><?php echo $lang->getLocale("ADMIN_NC_B1");?></a>
		          </div>
		        </div>

		        <div class="box-body no-padding">
		          
		          <?php echo $content->dataTable("postsTable", "table");?>
		          <thead>
		              <tr>
		                <th><?php echo $lang->getLocale("ADMIN_NC_L1");?></th>
		                <th><?php echo $lang->getLocale("ADMIN_NC_L2");?></th>
		                <th><?php echo $lang->getLocale("ADMIN_NC_L3");?></th>
		              </tr>
		            </thead>
		            <tbody>
		            <?php
		            $n = $content->getNewsCategoryAll();
		            if(isset($n) && count($n) > 0) {
		              for($i = 0;$i < count($n);$i ++) {
		                ?>
		                <tr>
		                  <td><a href="<?php echo $web->getUrl("admin/adm.php?page=news_categories&category=".$n[$i]['newsCategory_id']."");?>"><?php echo htmlentities($n[$i]['newsCategory']);?></a></td>
		                  <td><?php echo $content->getPostsInCategory($n[$i]['newsCategory_id'])." ".$lang->plural_words_locale($content->getPostsInCategory($n[$i]['newsCategory_id']), $lang->getLocale('ADMIN_NC_L4-2'), $lang->getLocale('ADMIN_NC_L4-1'), $lang->getLocale('ADMIN_NC_L4-3'), $lang->getLocale('ADMIN_NC_L4-3'));?></td>
		                  <td><?php echo ($n[$i]['newsCategory_desc'] == true) ? htmlspecialchars(strip_tags(($content->clearUserInputAll($n[$i]['newsCategory_desc'])))) : "<i>".$lang->getLocale('ADMIN_NC_L5')."</i>";?></td>
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