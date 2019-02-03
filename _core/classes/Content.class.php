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
* File: Content.class.php
* Filepath: /_core/classes/Content.class.php
*/

if(!defined("AUTHORIZED")) { die("Access Denied"); }

class Content {
	private $isckEditorUsed = false;
	private $isColorPickerUsed = false;
	private $isDataTableUsed = false;
	
	public function getContent() {
		global $web, $display, $lang;

		if ($web->getSettings("web:mainteranceLevel") != "a") {
			echo $display->createalert($lang->getLocale("MAINTENANCE_ALERT_T1"), $lang->getLocale('MAINTENANCE_ALERT_T2'), "warning");
		}

		$v = $this->getPage($this->getRequestUrl());
		return $v;
	}
	
	public function getAdminContent($page = null) {
		if(strtolower($this->isAdminPageExists($page))) {
			$this->getAdminPageContent($page);
		}
		else $this->getAdminPageContent("error");
	}

	private function isAdminPageExists($page, $ex = ".php") {
		$pagePath = DIR_ADMIN_PAGES.$page.$ex;
		if(file_exists($pagePath)) {
			return 1;
		}
		else return 0;
	} 
	
	private function getAdminPageContent($page, $ex = ".php") {
		$pagePath = DIR_ADMIN_PAGES.$page.$ex;
		if(file_exists($pagePath)) {
			require_once $pagePath;
		}
	}
	
	public function getPage($page) {
		if(array_search(strtolower($page), $GLOBALS['SYSTEM_PAGES']) != -1 && $this->isSystemPageExists($page)) {
			$this->getSystemPageContent(strtolower($page));
		}
		else if($this->isCustomPageExists($page)) $this->getCustomPageContent($page);
		else if($this->isPluginPageExists($page)) $this->getPluginPage($page);
		else $this->getSystemPageContent("error");
	}

	private function getRequestUrl() {
		global $web;

		$homepage = null;
		if($web->getSettings("content:homepage") == "news") $homepage = "news";
		else {
			$exp = explode("#", $web->getSettings("content:homepage"));
			if($exp[0] == "page" && is_numeric($exp[1]) && $exp[1] > 0) {
				$homepage = $this->getPost($exp[1])['post_url'];
			}
		}

		if(!empty($_SERVER['REQUEST_URI']) && isset($_SERVER['REQUEST_URI'])) {
			$ex = explode("/", $_SERVER['REQUEST_URI']);
			if(isset($ex[1]) && !empty($ex[1]) && strtolower($ex[1]) != "admin") {
				return $ex[1];	
			}
			else return $homepage;
		}
		else return $homepage;
	}

	private function isSystemPageExists($page, $ex = ".php") {
		$pagePath = DIR_PAGES.$page.$ex;
		if(file_exists($pagePath)) {
			return 1;
		}
		else return 0;
	} 
	
	private function getPluginPage($page, $ex = ".php") {
		$pagePath = DIR_PLUGINS.$page."/".$page.$ex;
		if(file_exists($pagePath)) {
			require_once $pagePath;
		}
		else return null;
	}
	
	private function isPluginPageExists($page, $ex = ".php") {
		$pagePath = DIR_PLUGINS.$page."/".$page.$ex;
		if(file_exists($pagePath)) {
			return 1;
		}
		else return 0;
	}

	private function isCustomPageExists($page) {
		global $db;
		if($db->selectRows("posts", array("post_type" => "page", "post_visibility" => "1", "post_url" => $page)) > 0) {
			return 1;
		}
		else return 0;
	}

	private function getSystemPageContent($page, $ex = ".php") {
		$pagePath = DIR_PAGES.$page.$ex;
		if(file_exists($pagePath)) {
			require_once $pagePath;
		}
	}

	private function getCustomPageContent($page) {
		global $db, $web, $display;
		$p = $db->select("posts", array("post_type" => "page", "post_visibility" => "1", "post_url" => $page));
		if(isset($p) && !empty($p)) {
			$web->addToTitle(" - ".htmlentities($p['post_title']));
			echo $display->createpanel(htmlentities($p['post_title']), "default");
			echo $p['post_content'];
			echo $display->closepanel();
			$this->generateMeta($p['post_id']);
		}
	}

	public function getPost($post_id) {
		global $db;
		return @$db->select("posts", array("post_id" => $post_id), "array");
	}
	
	public function getPostsByType($post_type, $query = null) {
		global $db;
		return $db->selectAll("posts", array("post_type" => $post_type), 'array', $query);
	}

	public function getPostIdFromUrl($url) {
		global $db;
		$s = $db->select("posts", array("post_url" => $url));
		if(!empty($s) && isset($s)) return $s['post_id'];
	}

	public function deletePost($post_id) {
		global $db;

		$db->delete("posts", array("post_id" => $post_id));
		$db->delete("comments", array("post_id" => $post_id));
	}

	public function getPostsId($post_type, $user_id = 0) {
		global $db;
		$returnData = array();
		if($post_type == "news") {
			$returnData = $db->querySelectAll("posts", "post_id", "where `post_visibility` > 0 and `post_type` = 'news' order by `post_timestamp_add` desc");
			return $returnData;
		}
		else return null;
	}

	public function getPostName($post_id) {
		global $db;
		return @$db->select("posts",  array("post_id" => $post_id), 'post_title');
	}

	public function updatePost($post_id, $data) {
		global $db;
		return @$db->update("posts", $data, array("post_id" => $post_id));
	}

	public function createPost($post_type, $user_id) {
		global $db, $lang;

		$post_name = null;
		if(strtolower($post_type) == "news") $post_name = $lang->getLocale('ADMIN_DEFAULT_NAME:POST_TYPE_NEWS');
		else if(strtolower($post_type) == "page") $post_name = $lang->getLocale('ADMIN_DEFAULT_NAME:POST_TYPE_PAGE');

		return $db->insert("posts", array(
			"post_title" => $post_name,
			"post_content" => "",
			"post_type" => $post_type,
			"user_id" => $user_id,
			"post_timestamp_add" => time(),
			"edit_user_id" => "",
			"post_timestamp_edit" => "",
			"post_comments" => "0",
			"post_visibility" => "0",
			"post_url" => "",
			"newsCategory_id" => "0"
		));
	}

	private function isPostExists($post_id) {
		global $db;
		return @$db->selectRows("posts", array("post_id" => $post_id));	
	}

	public function getCommentCount($post_id) {
		global $db;
		return @$db->selectRows("comments", array("post_id" => $post_id));	
	}

	public function deleteComment($comment_id) {
		global $db;

		$db->delete("comments", array("comment_id" => $comment_id));
	}

	private function isNewsCategoryExitsts($cat_id) {
		global $db;
		return @$db->selectRows("newsCategories", array("newsCategory_id" => $cat_id));
	}

	public function getNewsCategory($cat_id) {
		global $db;
		return @$db->select("newsCategories", array("newsCategory_id" => $cat_id));
	}

	public function getNewsCategoryIDFromLink($newsCategory_link) {
		global $db;
		return @$db->select("newsCategories", array("newsCategory_url" => $newsCategory_link), "newsCategory_id"); 
	}

	public function getNewsCategoryAll() {
		global $db;
		return @$db->selectAll("newsCategories");
	}

	public function getNewsCategoryList() {
		global $db;
		return @$db->querySelectAll("newsCategories", "array", "where length(`newsCategory_url`) > 0");
	}
	
	public function updateNewsCategory($cat_id, $data) {
		global $db;
		return @$db->update("newsCategories", $data, array("newsCategory_id" => $cat_id));
	}
	
	public function deleteNewsCategory($cat_id) {
		global $db;

		$db->delete("newsCategories", array("newsCategory_id" => $cat_id));
		$db->update("posts", array("newsCategory_id" => "0"), array("newsCategory_id" => $cat_id));
	}

	public function createNewsCategory($url = "", $desc = "", $name = "") {
		global $db, $lang;
	
		$newCat_name = null;
		if($name == null) {
			$newCat_name = $lang->getLocale('ADMIN_DEFAULT_NAME:NEWS_CATEGORY');
		} 
		else $newCat_name = $name;

		return $db->insert("newsCategories", array(
			"newsCategory" => $newCat_name,
			"newsCategory_desc" => $desc,
			"newsCategory_url" => $url
		));
	}

	
	public function getPostsInCategory($cat_id) {
		global $db;
		return @$db->selectRows("posts", array("newsCategory_id" => $cat_id));
	}

	public function render_newsCategory_list() {
		global $links;
		$c = $this->getNewsCategoryList();
		$newsCategory_list = null;
		if(count($c) > 0) {
			$newsCategory_list .= "<ul class='list-unstyled'>";
			for($i = 0;$i < count($c); $i ++) {
				$newsCategory_list .= "<li><a href='".$links->getNewsCategoryLink($c[$i]['newsCategory_id'])."'>".htmlentities($c[$i]['newsCategory'])."</a> <i>(".$this->getPostsInCategory($c[$i]['newsCategory_id'])."x)</i></li>";
			}
			$newsCategory_list .= "</ul>";
		}
		return $newsCategory_list;
	}

	public function render_newsCategory_label($cat_id) {
		global $db, $links;
		if($this->isNewsCategoryExitsts($cat_id)) {
			$c = $this->getNewsCategory($cat_id);
			return "<a href='".$links->getNewsCategoryLink($cat_id)."'>".htmlentities($c['newsCategory'])."</a>";
		}
		else return null;
	}

	public function render_newsCategory($cat_id) {
		global $db;
		if($this->isNewsCategoryExitsts($cat_id)) {
			$c = $this->getNewsCategory($cat_id);
			return htmlentities($c['newsCategory']);
		}
		else return null;
	}

	public function render_comments_button($post_id) {
		global $links, $lang, $web;
		$buttonString = null;

		$p = $this->getPost($post_id);
		if($this->getPostIdFromUrl($web->getUrlParam(2)) == null || $this->getPostIdFromUrl($web->getUrlParam(2)) != "news") { 
			if($p['post_type'] == "news") {
				$buttonString .= "<div class='pull-right'>";
				$buttonString .= "<a href='".$links->getPostLink($post_id)."' class='btn btn-xs btn-".(($p['post_visibility'] == 1) ? "info" : "primary")."'><i class='fa fa-comments'></i> ";
				if($this->getCommentCount($p['post_id']) > 0) {
					$buttonString .= $this->getCommentCount($p['post_id'])." ".$lang->plural_words_locale($this->getCommentCount($p['post_id']), $lang->getLocale('COMMENTS_T2'), $lang->getLocale('COMMENTS_T1'), $lang->getLocale('COMMENTS_T3'));
				}
				else {
					$buttonString .= $lang->getLocale('COMMENTS_T4');
				}
				$buttonString .= "</a>";
				$buttonString .= "</div>";
			}
		}

		return $buttonString;
	}

	public function getAllComments($post_id = 0, $limit = -1) {
		global $db;
		if($post_id == 0) return @$db->selectAll("comments", null, "array", "order by `comment_id` desc".(($limit != -1) ? " limit ".$limit : null));
		else return @$db->selectAll("comments", array("post_id" => $post_id), "array", "order by `comment_id` desc".(($limit != -1) ? " limit ".$limit : null));
	}

	public function getComment($comment_id, $data = 'array') {
		global $db;
		return @$db->select("comments", array("comment_id" => $comment_id), $data);
	}

	public function render_comments($post_id) {
		global $db, $lang, $web, $user, $display, $links;
		$commentsString = null;
		$p = $this->getPost($post_id);
		if($p['post_id'] > 0) {
			$w_count = $web->getUrlParamCount();
			$c = null;

			if($web->getUrlParam($w_count-3) == "comment" && $web->getUrlParam($w_count-2) == "all") $c = $db->selectAll("comments", array("post_id" => $post_id));
			else $c = $db->selectAllBySql("comments", "select * from (select * from %table_name% where `post_id` = ".$post_id." order by comment_id desc limit ".$web->getSettings('comments:num').") a order by comment_id asc");

			$commentsString .= $display->createpanel("<i class='fa fa-comments'></i> ".$lang->getLocale('COMMENTS_T1'), "default");
			if($p['post_comments'] == 1) {
				if(count($c) > 0) {
					if($db->selectRows("comments", array("post_id" => $post_id)) == count($c)) {
						$commentsString .= "<div class='well well-sm text-center'>".$lang->getLocale('COMMENTS_T9')."</div>";
					}
					else if($db->selectRows("comments", array("post_id" => $post_id)) > $web->getSettings('comments:num')) {
						$commentsString .= "<a href='".$links->getPostLinkEx($post_id, "comment/all/")."' class='btn btn-primary btn-sm btn-block'>".$lang->getLocale('COMMENTS_B6')."</a>";
					}

					for($i = 0;$i < count($c);$i ++) {
						$commentsString .= "";
						$commentsString .= "<div class='media'>";
						$commentsString .= "<div class='media-left'>";
						$commentsString .= "<a href='".$links->getUserLink($c[$i]['user_id'])."'><img class='media-object image-responsive' src='".$user->getUserAvatar($c[$i]['user_id'], '48')."' width='48px' height='48px' alt='".$user->getUserName($c[$i]['user_id'])." avatar'>";
						$commentsString .= "</a>";
						$commentsString .= "</div>";
						$commentsString .= "<div class='media-body'>";
						$commentsString .= "<p class='text-justify'><a href='".$links->getUserLink($c[$i]['user_id'])."'><strong>".$user->getUserName($c[$i]['user_id'], 1)."</strong></a>: ";
						
						$commentsString .= $c[$i]['comment'];
					
						/* Comment Date + Options */
						$commentsString .= "<small>";
						$commentsString .= "<i class='fa fa-clock-o'></i> ".$web->showTimeBefore($c[$i]['comment_timestamp']);
						if($c[$i]['comment_timestamp_edit'] > $c[$i]['comment_timestamp']) {
							$commentsString .= " - ".$web->showToolTip("<i>".$lang->getLocale('COMMENTS_L3')."</i>", $web->showTime($c[$i]['comment_timestamp_edit']));
						}
						
						if($user->isUserLogged() && $c[$i]['user_id'] == $user->loggedUser()) {
							$commentsString .= " - <a href='".$links->getPostLinkEx($post_id, "comment/edit/".$c[$i]['comment_id'])."'>".$lang->getLocale('COMMENTS_L4')."</a>";
							$commentsString .= " - <a href='".$links->getPostLinkEx($post_id, "comment/remove/".$c[$i]['comment_id'])."'>".$lang->getLocale('COMMENTS_L5')."</a>";
						}
						
						if($user->isUserLogged() && $c[$i]['user_id'] != $user->loggedUser()) {
							$commentsString .= " - <a href='".$web->getUrl("report/comment/".$c[$i]['comment_id'])."'>".$lang->getLocale('COMMENTS_L6')."</a>";
						}
						$commentsString .= "</small></p>";
						$commentsString .= "</div>";
						$commentsString .= "</div>";
					}

					
					if($user->isUserLogged()) {
						if($web->getUrlParam($w_count-4) == "comment") {
							if($web->getUrlParam($w_count-3) == "edit" || $web->getUrlParam($w_count-3) == "remove") {
								$comment_id = 0;
								if(!is_numeric($web->getUrlParam($w_count-2))) $comment_id = intval($web->getUrlParam($w_count-2));
								else $comment_id = $web->getUrlParam($w_count-2);
								if($comment_id > 0) {
									$cc = $db->select("comments", array("post_id" => $post_id, "comment_id" => $comment_id));
									if(isset($cc) && !empty($cc)) {
										if($web->getUrlParam($w_count-3) == "remove" && $cc['user_id'] == $user->loggedUser()) {
											$commentsString .= "<hr>";
											$commentsString .= "<div class='alert alert-danger'>";
											$commentsString .= sprintf($lang->getLocale('COMMENTS_T7'), $web->showTime((($cc['comment_timestamp_edit'] > 0) ? $cc['comment_timestamp_edit'] : $cc['comment_timestamp'])), $cc['comment']);
											$commentsString .= "<br>";
											$commentsString .= "<form method='post' class='form-inline'>";
											$commentsString .= "<div class='form-group'><input type='submit' value='".$lang->getLocale('COMMENTS_B2')."' name='deleteComment' class='btn btn-xs btn-primary'>&nbsp;";
											$commentsString .= "<a href='".$links->getPostLink($post_id)."' class='btn btn-xs btn-success'>".$lang->getLocale('COMMENTS_B3')."</a>";
											$commentsString .= "</div></form>";
											$commentsString .= "</div>";

											if(@$_POST['deleteComment']) {
												$d = $db->delete("comments", array("post_id" => $post_id, "comment_id" => $comment_id));
												$web->redirect($links->getPostLink($post_id)); 
											}
										}
										else if($web->getUrlParam($w_count-3) == "edit" && $cc['user_id'] == $user->loggedUser()) {
											$commentsString .= "<hr>";
											$commentsString .= "<div class='alert alert-warning'>";	
											$commentsString .= "<h4>".$lang->getLocale('COMMENTS_T8')."</h4>";
											$commentsString .= "<form method='post'>";
											$commentsString .= "<div class='form-group'>";
											$commentsString .= $this->editableInput("edit_comment_text", $lang->getLocale('COMMENTS_L2'), $cc['comment']);
											$commentsString .= "</div>";
											$commentsString .= "<div class='pull-right'>";
											$commentsString .= "<div class='form-group'><input type='submit' class='btn btn-xs btn-primary' name='editCommment' value='".$lang->getLocale('COMMENTS_B4')."'>&nbsp;";
											$commentsString .= "<a href='".$links->getPostLink($post_id)."' class='btn btn-xs btn-success'>".$lang->getLocale('COMMENTS_B5')."</a>";
											$commentsString .= "</div></div>";
											$commentsString .= "</form>";
											$commentsString .= "<br></div>";	

											if(@$_POST['editCommment']) {
												$cc = is_text_ok($_POST['edit_comment_text']);
												if($cc == 1) {
													$ch = $db->update("comments", array(
														"comment" => $this->clearUserInput($_POST['edit_comment_text']),
														"comment_timestamp_edit" => time()
													), array("post_id" => $post_id, "comment_id" => $comment_id));
													//$commentsString .= "<p class='text-success'>".$lang->getLocale('COMMENTS_OK_E')."</p>"; 
													$web->redirect($links->getPostLink($post_id)); 
												}	
												else { 
													$commentsString .= "<p class='text-warning'>".$lang->getLocale('COMMENTS_E1')."</p>";  
													$web->redirect($links->getPostLink($post_id), 2); 
												}
											}
										}
										else $web->redirect($links->getPostLink($post_id)); 
									}
								}
								//
							}
						}
					}
				}
				else $commentsString .= "<p class='text-muted text-center'>".$lang->getLocale('COMMENTS_T5')."</p>";
			}
			else $commentsString .= "<p class='text-muted text-center'>".$lang->getLocale('COMMENTS_T6')."</p>";


			if($user->isUserLogged() && $p['post_comments'] == 1) {
				$commentsString .= "<hr>";
				$commentsString .= "<form method='post'>";
			    $commentsString .= "<div class='media'>";
			    $commentsString .= "<div class='media-left'>";
			    $commentsString .= "<img class='media-object image-responsive' width='64px' height='64px' src='".$user->getUserAvatar($user->loggedUser(), '64')."' alt='".htmlentities($user->getUserInfo($user->loggedUser(), 'user_display_name'))."'>";
			    $commentsString .= "</div>";
			    $commentsString .= "<div class='media-body' style='width:100%'>";
					$commentsString .= $this->editableInput("comment_text", $lang->getLocale('COMMENTS_L2'));
			    $commentsString .= "<div class='pull-right'>";
			    $commentsString .= "<input type='submit' class='btn btn-primary' name='addComment' value='".$lang->getLocale('COMMENTS_B1')."'>";
			    $commentsString .= "</div>";
			    $commentsString .= "</div>";
			    $commentsString .= "</div>";
			    $commentsString .= "</form>";

			    if(@$_POST['addComment']) {
			    	$cc = is_text_ok($_POST['comment_text']);
			    	if($cc == 1) {
			    		$this->addComment($user->loggedUser(), $post_id, $_POST['comment_text']);
			    		//$commentsString .= "<p class='text-success'>".$lang->getLocale('COMMENTS_OK')."</p>"; 
			    		$web->redirect($links->getPostLink($post_id)); 
			    	}
			    	else {
			    		$commentsString .= "<p class='text-warning'>".$lang->getLocale('COMMENTS_E1')."</p>";  
			    		$web->redirect($links->getPostLink($post_id), 2);
			    	} 
			    }
			}
			
			$commentsString .= $display->closepanel();
		}
		return $commentsString;
	}
	
	private function addComment($user_id, $post_id, $comment) {
		global $db, $user;

		if($user->isUserIDExists($user_id) && $this->isPostExists($post_id) > 0) {

			$comment = $this->clearUserInput($comment);
			if(strlen($comment) > 0) {
				return @$db->insert("comments", array(
					"comment" => $this->clearUserInput($comment),
					"comment_timestamp" => time(),
					"comment_timestamp_edit" => "0",
					"post_id" => $post_id,
					"user_id" => $user_id
				));
			}
			else return null;
		}
		else return null;
	}

	public function clearUserInput($text) {
		global $web;
		
		$text = preg_replace("/\<[\/]{0,1}div[^\>]*\>/is", "", $text);
		$text = preg_replace("/\<[\/]{0,1}span[^\>]*\>/is", "", $text);
		$text = preg_replace("/<script\b[^>]*>(.*?)<\/script>/is", "", $text);
		
		/* HTML PURIFER */
		require_once DIR_INC.'htmlpurifer/HTMLPurifier.auto.php';
		$config = HTMLPurifier_Config::createDefault();
		$config->set('Core.Encoding', 'utf-8');
		$config->set('HTML.Allowed', 'a[href], b, blockquote, code, del, em, i, li, ol, p, s, strike, strong, sub, sup, table, tbody, td, tfoot, th, thead, tr, tt, u, ul, iframe[src|width|height], img[src|height|width]'); 
		$config->set('HTML.AllowedAttributes', array('a.href', '*.alt', '*.title', 'iframe.src', 'iframe.height', 'iframe.width', 'img.src', 'img.height', 'img.width'));
		$config->set('HTML.SafeIframe', true);
		$config->set('URI.SafeIframeRegexp', '%^(https?:)?//(www\.youtube(?:-nocookie)?\.com/embed/|player\.vimeo\.com/video/)%');
		$config->set('HTML.Trusted', true);
		$config->set('Filter.YouTube', true);
		$purifier = new HTMLPurifier($config);
		$text = $purifier->purify($text);
		
		if(preg_match_all("/<a\s[^>]*href=(\"??)([^\" >]*?)\\1[^>]*>(.*)<\/a>/siU", $text, $matches, PREG_SET_ORDER)) {
			foreach($matches as $match) {
				$host = parse_url($match[2], PHP_URL_HOST);
				if($web->getSettings("web:url") != $host /* to do */) {
					$text = str_replace($match[2], $web->getUrl($match[2]), $text);
				}
			}
		}

		return $text;
	}

	public function clearUserInputAll($text) {
		global $web;

		$text = preg_replace("/\<[\/]{0,1}div[^\>]*\>/is", "", $text);
		$text = preg_replace("/\<[\/]{0,1}span[^\>]*\>/is", "", $text);
		$text = preg_replace("/<script\b[^>]*>(.*?)<\/script>/is", "", $text);

		/* HTML PURIFER */
		require_once DIR_INC.'htmlpurifer/HTMLPurifier.auto.php';
		$config = HTMLPurifier_Config::createDefault();
		$config->set('Core.Encoding', 'utf-8');
		$purifier = new HTMLPurifier($config);
		$text = $purifier->purify($text);
		
		$text = strip_tags($text);

		return $text;
	}

	public function editableInput($divID = "textarea", $placeholder = null, $value = null, $rows = 2) {
		global $web;
		
		if($this->isckEditorUsed == false) {
			$web->addAditionalScripts("<script type='text/javascript' src='".$web->getUrl("assets/plugins/ckeditor/ckeditor.js")."'></script>");
			$this->isckEditorUsed = true;
		}

		$web->addAditionalJavascript("$(function(){CKEDITOR.replace('".$divID."');});");
		$tString = null;
		$tString .= "<textarea class='form-control' id='".$divID."' name='".$divID."' style='resize: vertical; overflow: auto; margin-top: 0px; margin-bottom: 0px;max-height:200px;' placeholder='".$placeholder."' rows='".$rows."'>".$value."</textarea>";
		
		return $tString;
	}
	
	public function dataTable($divID, $class = "table", $sort = null, $order = null) {
		global $web, $lang;
		
		if($this->isDataTableUsed == false) {
			$web->addAditionalScripts("<script type='text/javascript' src='".$web->getUrl("admin/plugins/datatables/jquery.dataTables.min.js")."'></script>");
			$web->addAditionalScripts("<script type='text/javascript' src='".$web->getUrl("admin/plugins/datatables/dataTables.bootstrap.min.js")."'></script>");
			$this->isDataTableUsed = true;
		}

		$dataTableParamString = '"bPaginate": true, "bLengthChange": false, "bFilter": false, "bSort": true, "bInfo": false, "bAutoWidth": false, "language": {
			"zeroRecords":"'.$lang->getLocale('AS_PLUGIN_DATATABLES_NO_DATA').'",
            "paginate": {
                "previous":"'.$lang->getLocale('AS_PLUGIN_DATATABLES_BTN_PREV').'", 
                "next":"'.$lang->getLocale('AS_PLUGIN_DATATABLES_BTN_NEXT').'",
                "last":"'.$lang->getLocale('AS_PLUGIN_DATATABLES_BTN_LAST').'",
                "first":"'.$lang->getLocale('AS_PLUGIN_DATATABLES_BTN_FIRST').'"
            }}
        ';

        if(!empty($sort) && !empty($order)) $dataTableParamString .= ' ,"order": [[ '.$sort.', "'.$order.'" ]]';
        else $dataTableParamString .= ' ,"order": []'; 
		

		$web->addAditionalJavascript("$('#".$divID."').dataTable({".$dataTableParamString."});");
		$tString = null;
		$tString .= "<table id='".$divID."' class='".$class."'>";
		
		return $tString;
	}

	public function colorInput($name = "colorInput", $class = "form-control", $placeholder = null, $value = null) {
		global $web;

		if($this->isColorPickerUsed == false) {
			$web->addToHead("<link href='".$web->getUrl("admin/plugins/colorpicker/bootstrap-colorpicker.min.css")."' rel='stylesheet'/>");
			$web->addAditionalScripts("<script type='text/javascript' src='".$web->getUrl("admin/plugins/colorpicker/bootstrap-colorpicker.min.js")."'></script>");
			$this->isColorPickerUsed = true;
		}

		$web->addAditionalJavascript("$('.".$name."').colorpicker();");
		$cString = null;
		$cString = "<input type='text' name='".$name."' class='".$class." ".$name."' placeholder='".$placeholder."' value='".$value."' maxlength='7'>";
		return $cString;
	}

	public function removeDiacritics($text) {
		$character_transfer = array('ä'=>'a','Ä'=>'A','á'=>'a','Á'=>'A','à'=>'a','À'=>'A','ã'=>'a','Ã'=>'A','â'=>'a','Â'=>'A','č'=>'c','Č'=>'C','ć'=>'c','Ć'=>'C','ď'=>'d','Ď'=>'D','ě'=>'e','Ě'=>'E','é'=>'e','É'=>'E','ë'=>'e','Ë'=>'E','è'=>'e','È'=>'E','ê'=>'e','Ê'=>'E','í'=>'i','Í'=>'I','ï'=>'i','Ï'=>'I','ì'=>'i','Ì'=>'I','î'=>'i','Î'=>'I','ľ'=>'l','Ľ'=>'L','ĺ'=>'l','Ĺ'=>'L','ń'=>'n','Ń'=>'N','ň'=>'n','Ň'=>'N','ñ'=>'n','Ñ'=>'N','ó'=>'o','Ó'=>'O','ö'=>'o','Ö'=>'O','ô'=>'o','Ô'=>'O','ò'=>'o','Ò'=>'O','õ'=>'o','Õ'=>'O','ő'=>'o','Ő'=>'O','ř'=>'r','Ř'=>'R','ŕ'=>'r','Ŕ'=>'R','š'=>'s','Š'=>'S','ś'=>'s','Ś'=>'S','ť'=>'t','Ť'=>'T','ú'=>'u','Ú'=>'U','ů'=>'u','Ů'=>'U','ü'=>'u','Ü'=>'U','ù'=>'u','Ù'=>'U','ũ'=>'u','Ũ'=>'U','û'=>'u','Û'=>'U','ý'=>'y','Ý'=>'Y','ž'=>'z','Ž'=>'Z','ź'=>'z','Ź'=>'Z'
		);

		$text = strtr($text, $character_transfer);
		return $text;
	}

	/* Auto meta */
	private $WEB_BODY_TAGS = null;
	private $WEB_SITE_END = null;
	private $isMetaGenerated = false;

	private function insertBodyTags($tagsString) {
		$this->WEB_BODY_TAGS = $tagsString;
	}

	private function insertSiteEnd($string) {
		$this->WEB_SITE_END .= $string;
	}

	private function clearSiteEnd() {
		$this->WEB_SITE_END = null;
	}

	public function getBodyTags() {
		return $this->WEB_BODY_TAGS;
	}

	public function getSiteEnd() {
		return $this->WEB_SITE_END;
	}

	public function generateMeta($post_id = 0) {
		global $web, $links, $user;
		if($this->isMetaGenerated == false) {
			$jsonArrayWebSite = array(
				"@context" => "https://schema.org",
				"@type" => "WebSite",
				"@id" => "#website",
				"url" => $web->getUrl(),
				"name" => htmlentities($web->getSettings("web:title"))
			);

			$jsonArrayOrganisation = array(
				"@context" => "https://schema.org",
				"@type" => "Organization",
				"url" => $web->getUrl(),
				"@id" => "#organization",
				"name" => htmlentities($web->getSettings("web:title")),
			);
			$wImg = $web->getSettings("web:image");
			if(!empty($wImg)) $jsonArrayOrganisation["logo"] = $wImg;

			$web->addToHead("<script type='application/ld+json'>".stripslashes(json_encode($jsonArrayWebSite))."</script>");
			$web->addToHead("<script type='application/ld+json'>".stripslashes(json_encode($jsonArrayOrganisation))."</script>");

			if($post_id == 0) $web->addToHead("<meta property='og:type' content='website'/>");
			else {
				$web->addToHead("<meta property='og:type' content='article'/>");
				$p = $this->getPost($post_id);
				$this->insertSiteEnd("<div class='microdata' itemprop=\"mainEntityOfPage\" class=\"entry-title\" itemprop=\"name\">".htmlentities($p['post_title'])."</div>");
				$this->insertSiteEnd("<span class='microdata' itemprop=\"headline\" class=\"entry-title\" itemprop=\"name\">".htmlentities($p['post_title'])."</span>");
				$this->insertSiteEnd("<span class='microdata' itemprop=\"datePublished\" class=\"updated\">".$web->showTime($p['post_timestamp_add'], "c")."</span>");
				$this->insertSiteEnd("<span class='microdata' itemprop=\"dateModified\" class=\"updated\">".$web->showTime($p['post_timestamp_edit'], "c")."</span>");
				$this->insertSiteEnd("<div class='microdata' itemprop=\"articleBody\">".$this->clearUserInputAll($p['post_content'])."</div>");

				// Publisher
				$this->insertSiteEnd('
					<div class="microdata" itemprop="publisher" itemscope itemtype="http://schema.org/Organization">
						<span class="microdata" itemprop="name"><a itemprop="url" href="'.$web->getUrl().'">'.htmlentities($web->getSettings("web:title")).'</a></span>
					</div>
					<span class="microdata" class="vcard author" itemprop="author">
						<span class="microdata" class="fn"><a class="microdata" href="'.$links->getUserLink($p['user_id']).'" title="'.$user->getUserName($p['user_id']).'" rel="author">'.$user->getUserName($p['user_id']).'</a></span>
					</span>
				');
			}

			$this->insertBodyTags("itemscope=\"\" itemtype=\"http://schema.org/NewsArticle\"");

			$this->meta_postKeywords($post_id);
			$this->meta_postDescription($post_id);
			$this->meta_postTags($post_id);

			$this->isMetaGenerated = true;
		}
	}

	private function meta_postKeywords($post_id) {
		global $web;
		$keywords = null;
		if($post_id == 0) {
			$keywords = $web->getSettings("web:keywords");
		}
		else {
			$p = $this->getPost($post_id);
			$keywords = $p['post_keywords'];
		}

		if(strlen($keywords) > 0) {
			$keywords = strtolower($keywords);
			$keywords = str_replace(", ", ",", $keywords);
			$web->addToHead("<meta name='keywords' content='".htmlentities($keywords)."'/>");
		}
	}

	private function meta_postDescription($post_id) {
		global $web;
		$description = null;
		if($post_id == 0) {
			$description = $web->getSettings("web:description");
			if(strlen($description) > 0) {
				$web->addToHead("<meta name='description' content='".htmlentities($description)."'/>");
			}
		}
		else {
			$p = $this->getPost($post_id);
			$description = $p['post_description'];
			if(strlen($description) > 0) {
				$web->addToHead("<meta name='og:description' content='".htmlentities($description)."'/>");
			}
		}
	}

	private function meta_postTags($post_id) {
		global $web;
		$tags = null;
		if($post_id > 0) {
			$p = $this->getPost($post_id);
			$tags = $p['post_tags'];
			$tags = strtolower($tags);
			$tags = str_replace(", ", ",", $tags);
			$t = explode(",", $tags);

			foreach ($t as $tag) {
				$web->addToHead("<meta property='article:tag' content='".htmlentities($tag)."'/>");
			}

			$web->addToHead("<meta property='article:publisher' content='".htmlentities($web->getSettings("web:title"))."'/>");
			if($p['newsCategory_id'] > 0) {
				$c = $this->getNewsCategory($p['newsCategory_id']);
				if(strlen($c['newsCategory']) > 0) $web->addToHead("<meta property='article:section' content='".htmlentities($c['newsCategory'])."'/>");
			}
			$web->addToHead("<meta property='article:published_time' content='".$web->showTime($p['post_timestamp_add'], "c")."'/>");
		}
	}
}
?>