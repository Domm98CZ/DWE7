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
* File: Links.class.php
* Filepath: /_core/classes/Links.class.php
*/
if(!defined("AUTHORIZED")) { die("Access Denied"); }

class Links {
	//private $WEB_URL = WEB_URL;

	public function getUserGroupLink($usergroup_id) {
		global $web, $user;
		$g = $user->getUserGroup($usergroup_id);
		$userGroupLink = null;
		if(!empty($g) && !empty($g['usergroup_link'])) {
			$userGroupLink = $web->getUrl("groups/".$g['usergroup_link']);
		}
		return $userGroupLink;
	}

	public function getNewsCategoryLink($cat_id) {
		global $web, $content;
		$c = $content->getNewsCategory($cat_id);
		$newsCategoryLink = null;
		if(!empty($c) && !empty($c['newsCategory_url'])) {
			$newsCategoryLink = $web->getUrl("news/categories/".$c['newsCategory_url']."");
		}
		return $newsCategoryLink;
	}

	public function getUserLink($user_id) {
		global $user, $web;
		$userLink = null;
		$u = $user->getUserInfo($user_id, "user_display_name");
		if(!empty($u)) {
			$userLink = $web->getUrl("profile/".$u);
		}
		return $userLink;
	}

	public function getPostLinkEx($post_id, $params) {
		return $this->getPostLink($post_id).str_replace("&", "&amp;", $params).((strpos($params, ".") === false) ? ((substr($params, -1) != "/") ? "/" : null) : null);
	}

	public function getPostLinkWithName($post_id, $blank = true) {
		global $content;
		$p = $content->getPost($post_id);
		return "<a href='".$this->getPostLink($post_id)."'".(($blank == true) ? " target='_blank'" : null).">".htmlentities($p['post_title'])."</a>";
	}
	
	public function getPostLink($post_id) {
		global $web, $content;
		$p = $content->getPost($post_id);
		$postLink = null;

		if($p['post_type'] == "news") {
			if(isset($p['post_url']) && !empty($p['post_url'])) {
				$postLink = $web->getUrl("news/".$p['post_url']);
			}
		}
		else if($p['post_type'] == "page") {
			if(isset($p['post_url']) && !empty($p['post_url'])) {
				$postLink = $web->getUrl($p['post_url']);
			}
		}
	
		return $postLink;
	}

	public function isLinkProhibited($link, $noturl = 0) {
		global $PROHIBITED_URI, $web;
		$uri = null; 
		if($noturl == 0 && filter_var($link, FILTER_VALIDATE_URL) === false) {
			$uri = $web->getUrl($link);
		}
		else { $uri = $link; }
		
		$returnValue = 0;
		for($i = 0;$i < count($PROHIBITED_URI);$i ++) {
			//if (strpos(strtolower($uri), $PROHIBITED_URI[$i]) !== false) {
			if (strtolower($uri) == $PROHIBITED_URI[$i]) {
				$returnValue = 1;
				break;
			}
		}
		
		return $returnValue;
	}
	
	public function isUserGroupLinkExists($link, $usergroup_id) {
		global $db;

		$ugid = 0;
		$ugid = @$db->select("userGroups", array("usergroup_link" => $link), 'usergroup_id');
		if($ugid > 0)
		{
			if($usergroup_id == $ugid) return 0;
			else return $ugid;
		}
		else return 0;
	}

	public function isPostLinkExists($link, $postType = null, $post_id = 0) {
		global $db;

		$pid = 0;
		if($postType == null) $pid = @$db->select("posts", array("post_url" => strtolower($link)), "post_id");
		else $pid = @$db->select("posts", array("post_url" => strtolower($link), "post_type" => $postType), "post_id");

		if($post_id == 0) return $pid;
		else {
			if($post_id == $pid) return 0;
			else return $pid;
		}
	}
	
	public function isNewsCategoryLinkExists($link, $cat_id) {
		global $db;

		$cid = @$db->select("newsCategories", array("newsCategory_url" => strtolower($link)), "newsCategory_id");
		if($cid > 0) {
			if($cat_id == $cid) return 0;
			else return $cid;
		}
		else return 0;
	}

	public function getMenus() {
		global $db;
		return @$db->selectAll("menus");
	}

	public function createMenu($menu_name) {
		global $db;
		$s = $db->selectBySql("menus", "SELECT MAX(`menu_sid`) FROM  `%table_name%`");
		return @$db->insert("menus", array(
			"menu_name" => $menu_name,
			"menu_pos" => 0,
			// SELECT MAX(`menu_sid`) FROM  `dwe7_menu`
			"menu_sid" => ($s +1)
		));
	}

	public function isMenuSIDExists($menu_sid) {
		global $db;
		return @$db->selectRows("menus", array("menu_sid" => $menu_sid));
	}

	public function isMenuIDExists($menu_id) {
		global $db;
		return @$db->selectRows("menus", array("menu_id" => $menu_id));
	}

	public function getMenuData($menu_sid) {
		global $db;
		$menuData = $db->select("menus", array("menu_sid" => $menu_sid));
		$menuData['links'] = $db->selectAll("menu", array("menu_sid" => $menu_sid), 'array', "order by `menu_order` asc");
		return $menuData;
	}

	public function editMenuName($menu_sid, $newName) {
		global $db;
		return @$db->update("menus", array("menu_name" => $newName), array("menu_sid" => $menu_sid));
	}

	public function deleteMenu($menu_sid) {
		global $db;
		$db->delete("menus", array("menu_sid" => $menu_sid));
		$db->delete("menu", array("menu_sid" => $menu_sid));
	}

	public function addMenuData($menu_sid, $data = array()) {
		global $db;
		if($this->isMenuSIDExists($menu_sid) == 1) {
			return @$db->insert("menu", $data);
		}	
		else return null;
	}

	public function getMenuDataSingle($menu_id) {
		global $db;
		return @$db->select("menu", array("menu_id" => $menu_id));
	}

	public function deleteMenuDataSingle($menu_id) {
		global $db;
		if($db->select("menu", array("menu_id" => $menu_id), "menu_dropdown") == "main") {
			$db->delete("menu", array("menu_dropdown" => $menu_id));
		}
		return @$db->delete("menu", array("menu_id" => $menu_id));
	}

	public function editMenuDataSingle($menu_id, $data = array()) {
		global $db;
		return @$db->update("menu", $data, array("menu_id" => $menu_id));
	}

	public function getMenu($menu_id = 0) {
		global $db, $web;

		$names = null;
		$values = null;

		if($menu_id == 0) $menu_id = (($web->getSettings("web:menu") > 0) ? $web->getSettings("web:menu") : 1);

		$menuLinks = $db->selectAll("menu", array("menu_sid" => $menu_id), 'array', "order by `menu_order` asc");
		if(count($menuLinks) > 0) {
			for($i = 0;$i < count($menuLinks);$i ++) {

				if(isset($menuLinks[$i]['menu_dropdown']) && $menuLinks[$i]['menu_dropdown'] == "main") {		
				
					$_names = null;
					$_values = null;

					$subMenuLinks = $db->selectAll("menu", array("menu_sid" => $menu_id, "menu_dropdown" => $menuLinks[$i]['menu_id']), 'array', "order by `menu_order` asc");
					if(count($subMenuLinks) > 0) {
						for($y = 0;$y < count($subMenuLinks);$y++) {
							$_names[] = $subMenuLinks[$y]['menu_name'];  
							$_values[] = $subMenuLinks[$y]['menu_link']; 	
						}
						$names[$i] = $menuLinks[$i]['menu_name'];
						$values[$i] = array_combine($_names, $_values);
					}	
				}
				else if($menuLinks[$i]['menu_dropdown'] == null) { 
					$names[$i] = $menuLinks[$i]['menu_name'];
					$values[$i] = $menuLinks[$i]['menu_link']; 
				}
			}
			return array_combine($names, $values);
		}
		else return null;
	}
}
?>