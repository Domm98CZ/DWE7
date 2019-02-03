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
* File: Backup.class.php
* Filepath: /_core/classes/Backup.class.php
*/

class Backup {
	public function database($handle, $path = DIR_BACKUPS, $tables = array())
	{
		global $web, $db, $log;
		$time = time();

  		$string = null;
  		$string .= "/* Database Backup: ".$web->showTime($time)." */\n";
  
  		if(empty($tables[0]) || !isset($tables[0]))
  		{
    		$tables_q = $handle->queryAll("SHOW TABLES");
			for($i = 0;$i < count($tables_q);$i ++) {
				$table = array_values($tables_q[$i]);
				$tables[] = $table[0];
			}
  		}
  
  		$tables = array_unique($tables);
  		$string .= "/* Backup Tables: ".implode(", ", $tables)." */\n\n";

		$new_table = false;
		foreach($tables as $table)
		{  
			$new_table = true;
			$scheme_data = $handle->queryAlone("SHOW CREATE TABLE `".$table."`");
			$scheme_data = array_values($scheme_data);
		    
		    $string .= "DROP TABLE  `".$table."`\n";
		    $string .= $scheme_data[2].";\n";
		    $string .= "\n";
		    
		    $table_values = $handle->selectAll($table);
		    if(count($table_values) > 0 && is_set($table_values)) {
		    	for($i = 0;$i < count($table_values);$i++) {
		    		if($new_table == true) {
		    			$fields = array_keys($table_values[$i]);
		    			$string .= "INSERT INTO `".$table."` (`".implode("`, `", $fields)."`) VALUES\n";
		       	 		$new_table = false;
		    		}
		    		$values = array_values($table_values[$i]);   
		      		$string .= "(`".implode("`, `", $values)."`),\n";
		    	}
		    	$string = substr($string, 0, -2);
		    	$string .= ";\n\n";
		    }
		}


		$string .= "/* Generated in ".(time() - $time)."ms */\n";
		  
		$file_name = null;
		$file_name = "BACKUP-DB-".$time."-".md5($time."DWE".implode("#", $tables)).".sql";
		  
		$file_handle = fopen($path.$file_name, 'w+');
		fwrite($file_handle, $string);
		fclose($file_handle);

		$log->write("Database, backup created.", 1);
		return "Database backup create.";
	}

	public function database_schema($handle, $path = DIR_BACKUPS, $tables = array()) {
		global $web, $db, $log;
		$time = time();

  		$string = null;
  		$string .= "/* Database Schema Backup: ".$web->showTime($time)." */\n";
  
  		if(empty($tables[0]) || !isset($tables[0]))
  		{
    		$tables_q = $handle->queryAll("SHOW TABLES");
			for($i = 0;$i < count($tables_q);$i ++) {
				$table = array_values($tables_q[$i]);
				$tables[] = $table[0];
			}
  		}
  
  		$tables = array_unique($tables);
  		$string .= "/* Backup Tables: ".implode(", ", $tables)." */\n\n";

		foreach($tables as $table)
		{  
			$scheme_data = $handle->queryAlone("SHOW CREATE TABLE `".$table."`");
			$scheme_data = array_values($scheme_data);
		    
		    $string .= "DROP TABLE  `".$table."`\n";
		    $string .= $scheme_data[2].";\n";
		    $string .= "\n";
		}


		$string .= "/* Generated in ".(time() - $time)."ms */\n";
		  
		$file_name = null;
		$file_name = "BACKUP-SCHEMA-".$time."-".md5($time."DWE".implode("#", $tables)).".sql";
		  
		$file_handle = fopen($path.$file_name, 'w+');
		fwrite($file_handle, $string);
		fclose($file_handle);

		$log->write("Database schema, backup created.", 1);
	}
}
?>