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
* File: Cron.class.php
* Filepath: /_core/classes/Cron.class.php
*/

if(!defined("AUTHORIZED")) { die("Access Denied"); }

class Cron {

	// Zjistí čas posledního spuštění cronu
	// Parametr $cron_id
	public function getCronLastStart($cronName) {
		global $db;

		$cron = $db->select("crons", array("cron_name" => $cronName), 'cron_time');
		if(is_set($cron)) return $cron;
		else return null;
	}

	// Získání xhr get požadavku, v případě že se má nějaká z plánovaných cron úloh spustit
	public function getCronsLink() {
		global $web, $db;
		$cron = $db->selectAll("crons");
		if(count($cron) > 0) {
			for($i = 0;$i < count($cron);$i ++) {
    			if(($cron[$i]['cron_time'] + $cron[$i]['cron_timer']) <= time()) {
    				$web->addAditionalJavascript("var cron = $.get('".$web->getUrl()."cronmanager.php', function() {}).done(function(data) {});");
    				break;
    			}
    		}
		}
	}

	// Spuštění všech plánovaných cron úloh (v případě, že už mají svůj čas)
	public function getCrons() {
		global $db;
  		$cron = $db->selectAll("crons");
		if(count($cron) > 0) {
			for($i = 0;$i < count($cron);$i ++) {
    			if(($cron[$i]['cron_time'] + $cron[$i]['cron_timer']) <= time()) $this->startCron($cron[$i]['cron_id']);
    		}
    	}
	}

	// Spuštění plánované cron úlohy
	// Parametr $cron_ID 
	private function startCron($cron_id) {
		global $db;
		$c = $db->select("crons", array("cron_id" => $cron_id));
	 	if(is_set($c)) {
	 		echo "Loading cron ".$c['cron_name']." (".$c['cron_path'].")<br>\n";
	    	if(file_exists($c['cron_path'])) {
	    		echo "Loaded cron ".$c['cron_name']."<br>\n";
	    		include($c['cron_path']);
	    	}
	    	else echo "Loading failed ".$c['cron_name']."<br>\n";
	    	$this->updateCron($cron_id);
	    	echo "<br>\n";
		}
	}

	// Změna posledního aktivního času konkrétního cronu
	// Parametr $cron_ID 
	// Získání úspěchu/neúspěchu změny
	private function updateCron($cron_id) {
		global $db;
		$c = $db->select("crons", array("cron_id" => $cron_id));
	 	if(is_set($c)) {
	  		return @$db->update("crons", array("cron_time" => time()), array("cron_id" => $cron_id));
	  	} 
	}

	// Zaregistrování nové cron úlohy
	// parametr $cronInfo
	// Získání ID registrované cron úlohy
	private function registerCron($cronInfo = array()) {
		global $db;
		if(is_array($cronInfo)) {
			return @$db->insert("crons", $cronInfo);
		}
		else return null;
	}
}
?>