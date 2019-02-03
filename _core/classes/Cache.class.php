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
* File: Cache.class.php
* Filepath: /_core/classes/Cache.class.php
*/

if(!defined("AUTHORIZED")) { die("Access Denied"); }

class Cache {
	private $cacheArray = null;

	public function __construct() {
		global $db;
		$this->cacheArray = null;

		if ($db instanceof Database) {
			$cache = @$db->selectAll("cache");
			if(count($cache) > 0) {
				for($i = 0;$i < count($cache);$i ++) {
					$this->cacheArray[$cache[$i]['cache_name']]['value'] = $cache[$i]['cache_value'];
					$this->cacheArray[$cache[$i]['cache_name']]['time'] = $cache[$i]['cache_time'];
				}
			} 
		}
	}

	public function get($cache_name, $value = 1) {
		if($value == 1) return @$this->cacheArray[$cache_name]['value'];
		if($value == -1) return @$this->cacheArray[$cache_name];
		else return @$this->cacheArray[$cache_name]['time'];
	}

	public function set($cache_name, $cache_value) {
		global $db;
		$time = time();

		$this->cacheArray[$cache_name]['value'] = $cache_value;
		$this->cacheArray[$cache_name]['time'] = $time;

		$s = $db->select("cache", array("cache_name" => $cache_name), 'cache_id');
		if($s > 0) return @$db->update("cache", array("cache_value" => $cache_value, "cache_time" => $time), array("cache_name" => $cache_name));
		else return @$db->insert("cache", array("cache_name" => $cache_name, "cache_value" => $cache_value, "cache_time" => $time));
	}
}
?>