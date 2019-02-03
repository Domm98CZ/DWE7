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
* File: admin.php
* Filepath: /pages/admin.php
*/
require_once "_core/maincore.php";
if(!defined("AUTHORIZED")) { die("Access Denied"); }
require_once DIR_INC."PageHeader.php";

if($user->isUserLogged()) {
	if($user->isUserHasRights($user->loggedUser(), 2, "b")) {
		$kID = $web->registerKeyWithData("admin:auth", $user->loggedUser(), $user->getUserDeviceAuthKey($user->loggedUser()));
		$web->redirect($web->getUrl("admin/auth.php?d=".$user->getUserDeviceAuthKey($user->loggedUser())."&k=".$web->getKey($kID)));			
	}
	else $web->redirect($web->getUrl());
}
else $web->redirect($web->getUrl());
?>