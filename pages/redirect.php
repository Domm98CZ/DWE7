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
* File: redirect.php
* Filepath: /pages/redirect.php
*/
if(!defined("AUTHORIZED")) { die("Access Denied"); }
require_once DIR_INC."PageHeader.php";

$request_url = urldecode($web->getParamUrlByParam(2));

echo $display->createpanel($lang->getLocale("P_REDIRECT_TITLE"), "warning");
if(filter_var($request_url, FILTER_VALIDATE_URL)) {
	?>
	<p class='text-muted text-center'><?php echo $lang->getLocale("P_REDIRECT_T1");?></p>
	<p class='text-muted text-center'><a href="<?php echo $request_url;?>"><?php echo $request_url;?></a></p>
	<p class='text-muted text-center'><?php echo $lang->getLocale("P_REDIRECT_T2");?></p>
	<p class='text-muted text-center'><a href="<?php echo $request_url;?>" class="btn btn-warning"><?php echo $lang->getLocale("P_REDIRECT_B1");?></a></p>
	<?php
}
else {
	if(isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])) {
		?>
		<p class='text-muted text-center'><?php echo $lang->getLocale("P_REDIRECT_T3");?></p>
		<p class='text-muted text-center'><a href='<?php echo $_SERVER['HTTP_REFERER'];?>' class='btn btn-warning'><?php echo $lang->getLocale("P_REDIRECT_B2");?></a></p>
		<?php
	}
	else header("location:javascript://history.go(-1)");
}
echo $display->closepanel();
?>