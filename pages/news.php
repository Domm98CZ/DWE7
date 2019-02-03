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
* File: news.php
* Filepath: /pages/news.php
*/
if(!defined("AUTHORIZED")) { die("Access Denied"); }
require_once DIR_INC."PageHeader.php";

if(is_set($web->getUrlParam(2)) && strtolower($web->getUrlParam(2)) == "categories" && is_set($web->getUrlParam(3))) {
	$c = $content->getNewsCategory($content->getNewsCategoryIDFromLink($web->getUrlParam(3)));
	if(isset($c) && !empty($c)) {
		$web->addToTitle(" - ".htmlentities($c['newsCategory']));

		echo $display->createpanel(htmlentities($c['newsCategory']), "primary");

		if(!empty($c['newsCategory_desc'])) echo "<p class='text-left'>".$c['newsCategory_desc']."</p>";
		else echo "<p class='text-center'>".$lang->getLocale('NEWS_CATEGORIES_T3')."</p>";

		$np = $db->selectAll("posts", array("post_type" => "news", "newsCategory_id" => $c['newsCategory_id']), "array", "order by `post_timestamp_add` desc, `post_id` desc");
		if(count($np) > 0) {
			echo "<hr>";
			echo "<ul class='list-unstyled'>";
			for($i = 0;$i < count($np);$i ++) {
				echo "<li><a href='".$links->getPostLink($np[$i]['post_id'])."'>".htmlentities($np[$i]['post_title'])."</a></li>";
			}
			echo "</ul>";
		}
		else echo "<p class='text-center'>".$lang->getLocale('NEWS_CATEGORIES_T4')."</p>";
		echo $display->closepanel();

		echo $display->createpanel(null, "primary");
		echo "<a href='".$web->getUrl("news/categories")."'><i class='fa fa-arrow-left' aria-hidden='true'></i> ".$lang->getLocale('NEWS_CATEGORIES_T5')."</a>";
		echo $display->closepanel();
	}
	else $web->redirect($web->getUrl("news/categories"));
} 
else if(is_set($web->getUrlParam(2)) && strtolower($web->getUrlParam(2)) == "categories") {
	$web->addToTitle(" - ".htmlentities($lang->getLocale('NEWS_CATEGORIES_T1')));
	echo $display->createpanel($lang->getLocale('NEWS_CATEGORIES_T1'), "primary");
	$display->render_newsCategory_list();
	echo $display->closepanel();
}
else if(is_set($web->getUrlParam(2)) && !is_numeric($web->getUrlParam(2))) {
	$p = $content->getPost($content->getPostIdFromUrl($web->getUrlParam(2)));
	if($p['post_id'] > 0 && $p['post_visibility'] > 0) {
		$web->addToTitle(" - ".htmlentities($p['post_title']));
		$display->render_object($p['post_id']);
		$display->render_comments($p['post_id']);

		echo $display->createpanel(null, "primary");
		echo "<a href='".$web->getUrl("news")."'><i class='fa fa-arrow-left' aria-hidden='true'></i> ".$lang->getLocale('COMMENTS_L1')."</a>";
		echo $display->closepanel();

		$content->generateMeta($p['post_id']);
	}
	else $web->redirect($web->getUrl());
}
else {
	$web->addToTitle(" - ".$lang->getLocale('NEWS_TITLE'));
	$n = $content->getPostsId("news");
	$count = count($n);
	if($count > 0) {
		
		$p = $web->getSettings('content:newsNum');
		$page = 0;
		$pages = round_up($count / $p);

		$s = $web->getUrlParam(2);
		if(is_set($s)) {
			if($s > $pages) $page = $pages;
			else if($s <= 0) $page = 1;
			else $page = $s;
		}
		else $page = 1;

		$start = ($page -1) * $p;
		$end = min(($start + $p), $count);


		for($i = 0;$i < $count;$i ++) {
			if($i >= $start && $i < $end) $display->render_object($n[$i]['post_id']);
		}

		if($pages > 1) {
			$page_back = $page - 1;
  			$page_next = $page + 1;
			$link = $web->getUrl("news/");
			?>
			<hr>
			<div class="pull-right">
				<ul class="pagination">
				<?php
				if($page == 1 || $page == "1") echo "<li class='disabled'><a><i class='fa fa-angle-double-left'></i></a></li>";
			    else echo "<li><a href='".$link."1'><i class='fa fa-angle-double-left'></i></a></li>";
			      
			    if($page == 1 || $page == "1") echo "<li class='disabled'><a><i class='fa fa-angle-left'></i></a></li>";
			    else echo "<li><a href='".$link.$page_back."'><i class='fa fa-angle-left'></i></a></li>";
			      
			    for($i = 1;$i < $pages+1;$i++)
			    {
			      if($i == $page) echo "<li class='active'><a href='".$link.$i."'>".$i."</a></li>";
			      else echo "<li><a href='".$link.$i."'>".$i."</a></li>"; 
			    }
			      
			    if($page == $pages) echo "<li class='disabled'><a><i class='fa fa-angle-right'></i></a></li>";
			    else echo "<li><a href='".$link.$page_next."'><i class='fa fa-angle-right'></i></a></li>";
			      
			    if($page == $pages) echo "<li class='disabled'><a><i class='fa fa-angle-double-right'></i></a></li>";
			    else echo "<li><a href='".$link.$pages."'><i class='fa fa-angle-double-right'></i></a></li>";
				?>
				</ul>
			</div>
			<?php
		}
	}
}
?>