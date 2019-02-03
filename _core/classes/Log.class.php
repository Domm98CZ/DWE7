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
* File: Log.class.php
* Filepath: /_core/classes/Log.class.php
*/
if(!defined("AUTHORIZED")) { die("Access Denied"); }

class Log {
	// rework, to do
	private $logfiles = true;
	private $logsettings = array(
		"prefix" => "log_",
		"timeFormat" => "d. m. Y H:i:s",
		"ex" => "dwelog"
	);
	private $loglevels = array(
		-1 => "DEBUG",
		0 => "",
		1 => "INFO",
		2 => "WARNING",
		3 => "ERROR",
		4 => "CRITICAL"
	);

	public function write($string, $loglevel = 0) {
		global $web, $db;

		$msgString = null;
		$msgString .= "[".$web->showTime(time(), $this->logsettings['timeFormat'])."]";

		if($loglevel > 0 && $loglevel < count($this->loglevels)) {
			$msgString .= " [".$this->loglevels[$loglevel]."]";
		}

		$msgString .= " ".$string;

		$db->insert("log", array(
			"log_level" => $loglevel,
			"log_time" => time(),
			"log_text" => $string,
		));

		if($this->logfiles) {
			$file_handle = fopen(DIR_LOGS.$this->logsettings['prefix']."main.".$this->logsettings['ex'], 'a+');
			fwrite($file_handle, $msgString."\r\n");
			fclose($file_handle);
		}
	}
}
?>