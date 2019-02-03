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
* File: Database.class.php
* Filepath: /_core/classes/Database.class.php
*/

if(!defined("AUTHORIZED")) { die("Access Denied"); }

class Database {
	// Database Variables
	private $DATABASE_SERVER 	= null;
	private $DATABASE_USER 		= null;
	private $DATABASE_PASS		= null;
	private $DATABASE_TABLE		= null;
	private $DATABASE_PREFIX	= null;
	private $DATABASE 			= null;
	private $DATABASE_DATA		= null;
	private $DATABASE_ERROR		= null;

	public function __construct($_DATABASE_SERVER, $_DATABASE_USER, $_DATABASE_PASS, $_DATABASE_TABLE, $_DATABASE_PREFIX = null) {
		try {
		    $this->DATABASE = new PDO("mysql:host=".$_DATABASE_SERVER.";dbname=".$_DATABASE_TABLE, "".$_DATABASE_USER."", "".$_DATABASE_PASS."", array(
		      PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
		      PDO::MYSQL_ATTR_INIT_COMMAND => "SET CHARACTER SET utf8",
		    )); 
		    $this->DATABASE->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		   	if(!empty($_DATABASE_PREFIX)) $this->DATABASE_PREFIX = $_DATABASE_PREFIX;
		   	unset($_DATABASE_SERVER, $_DATABASE_USER, $_DATABASE_PASS, $_DATABASE_TABLE, $_DATABASE_PREFIX);
		} catch (PDOException $e) {
		    $this->DATABASE_ERROR = $e->getMessage();
		}	
	}

	public function isConnected() {
        if (!empty($this->DATABASE)) return @mysql_ping($this->DATABASE);
        else return false;     
    }

    public function query($_query, $_params = null) {
    	if (!empty($_params) && !is_array($_params) && !is_null($_params)) {
            $_params = array($_params);
        }
    	$r = $this->DATABASE->prepare($_query);
    	return $r->execute((!empty($_params) ? $_params : null));
    }

    public function queryAlone($query, $parameters = array()) {
        $result = $this->DATABASE->prepare($query);
        $result->execute($parameters);
        return $result->fetch();
    }

    public function queryAll($query, $parameters = array()) {
        $result = $this->DATABASE->prepare($query);
        $result->execute($parameters);
        return $result->fetchAll();
    }

    public function queryNum($query, $parameters = array()) {
        $result = $this->DATABASE->prepare($query);
        $result->execute($parameters);
        return $result->rowCount();
    }

    public function selectRows($_tableName, $_params = array()) {
    	$sql = null;          
		if(isset($_params) && !empty($_params) && count($_params) > 0) {
			$param_str = null;
			$sql .= "SELECT * FROM `".$this->prefix($_tableName)."` WHERE ";  
			$param_key = array_keys($_params);
			$param_value = array_values($_params);
			for($i = 0;$i < count($_params);$i++) $param_str .= "`".$param_key[$i]."` = ? AND";
			$param_str = substr($param_str, 0, -4);
			$sql .= $param_str; 
		}
		else { $sql .= "SELECT * FROM `".$this->DATABASE_PREFIX.$_tableName."`";	}
		$r = $this->DATABASE->prepare($sql);
		$r->execute(array_values($_params));
		return $r->rowCount();  
    }
	
	public function querySelectRows($_tableName, $_sql) {
    	$sql = null;          
			$sql .= "SELECT * FROM `".$this->DATABASE_PREFIX.$_tableName."`";	
			if(!empty($_sql)) $sql .= " ".$_sql;
			$r = $this->DATABASE->prepare($sql);
			$r->execute();
			return $r->rowCount();  
    }

    public function selectAll($_tableName, $_params = array(), $_returnData = 'array', $_order = null) {
		$sql = null;
		$param_str = null;
		$sql .= "SELECT * FROM `".$this->prefix($_tableName)."`";  
		$param_key = array_keys($_params);
		$param_value = array_values($_params);
		if(count($param_key) > 0) {
			$sql .= " WHERE ";
			for($i = 0;$i < count($_params);$i++) $param_str .= "`".$param_key[$i]."` = ? AND";
			$param_str = substr($param_str, 0, -4);
			$sql .= $param_str;
		}
		if(!empty($_order)) $sql .= " ".$_order;
		$r = $this->DATABASE->prepare($sql);
		$r->execute(array_values($_params));
		$data_array = null;
		while ($data = $r->fetch(PDO::FETCH_ASSOC)) { 
			if($_returnData == "array") $data_array[] = $data;
			else $data_array[][$_returnData] = $data[$_returnData]; 
		}
		return $data_array;
    }

    public function querySelect($_tableName, $_returnData = 'array', $_sql = null) {
		$sql = null;
		$param_str = null;
		$sql .= "SELECT * FROM `".$this->prefix($_tableName)."`";  
		if(!empty($_sql)) $sql .= " ".$_sql;
		$r = $this->DATABASE->prepare($sql);
		$r->execute();
		$data = $r->fetch();
		if(isset($data) && !empty($data) && count($data) > 0) { 
			if($_returnData == "array") return $data;
			else return $data[$_returnData];
		}
		else return null;
    }

    public function querySelectAll($_tableName, $_returnData = 'array', $_sql = null) {
    	$sql = null;
		$param_str = null;
		$sql .= "SELECT * FROM `".$this->prefix($_tableName)."`";  
		if(!empty($_sql)) $sql .= " ".$_sql;
		$r = $this->DATABASE->prepare($sql);
		$r->execute();
		$data_array = null;
		while ($data = $r->fetch(PDO::FETCH_ASSOC)) { 
			if($_returnData == "array") $data_array[] = $data;
			else $data_array[][$_returnData] = $data[$_returnData]; 
		}
		return $data_array;
    }

     public function select($_tableName, $_params = array(), $_returnData = 'array', $_order = null) {
		$sql = null;
		$param_str = null;
		$sql .= "SELECT * FROM `".$this->prefix($_tableName)."`";  
		$param_key = array_keys($_params);
		$param_value = array_values($_params);
		if(count($param_key) > 0) {
			$sql .= " WHERE ";
			for($i = 0;$i < count($_params);$i++) $param_str .= "`".$param_key[$i]."` = ? AND";
			$param_str = substr($param_str, 0, -4);
			$sql .= $param_str;
		}
		if(!empty($_order)) $sql .= " ".$_order;
		$r = $this->DATABASE->prepare($sql);
		$r->execute(array_values($_params));
		$data = $r->fetch();
		if(isset($data) && !empty($data) && count($data) > 0) { 
			if($_returnData == "array") return $data;
			else return $data[$_returnData];
		}
		else return null;
    }

    public function selectBySql($_tableName = null, $sql = null, $_returnData = 'array') {
    	$r = $this->DATABASE->prepare(str_replace("%table_name%", $this->prefix($_tableName), $sql));
    	$r->execute();
    	$data = $r->fetch();
    	if(isset($data) && !empty($data) && count($data) > 0) { 
			if($_returnData == "array") return $data;
			else return $data[$_returnData];
		}
		else return null;
    }

    public function selectAllBySql($_tableName = null, $sql = null, $_returnData = 'array') {
    	$r = $this->DATABASE->prepare(str_replace("%table_name%", $this->prefix($_tableName), $sql));
    	$r->execute();
    	$data_array = null;
    	while ($data = $r->fetch(PDO::FETCH_ASSOC)) { 
			if($_returnData == "array") $data_array[] = $data;
			else $data_array[][$_returnData] = $data[$_returnData]; 
		}
		return $data_array;
    }


    public function insert($_tableName, $_insertData = array()) {
		$sql = null;
		$sql .= "INSERT INTO `".$this->prefix($_tableName)."` (`";
		$sql .= implode('`, `', array_keys($_insertData));
		$sql .= "`) VALUES (";
		$sql .= str_repeat('?,', count($_insertData) - 1);
		$sql .= "?)";  
		$r = $this->DATABASE->prepare($sql);
		$r->execute(array_values($_insertData));
		return $this->DATABASE->lastInsertId();
    }

    public function update($_tableName, $_data = array(), $_params = array()) {
		$data_str = null;
		$param_str = null;
		$sql = null;
		$execute_data = array();
		$sql .= "UPDATE `".$this->prefix($_tableName)."` SET "; 
		$data_key = array_keys($_data);
		$data_value = array_values($_data);
		for($i = 0;$i < count($_data);$i++) {
			$data_str .= "`".$data_key[$i]."` = ?, ";
			$execute_data[] = $data_value[$i];
		}
		$data_str = substr($data_str, 0, -2); 
		$sql .= $data_str;
		$sql .= " WHERE "; 
		$param_key = array_keys($_params);
		$param_value = array_values($_params);
		for($i = 0;$i < count($_params);$i++) {
			$param_str .= "`".$param_key[$i]."` = ? AND";
			$execute_data[] = $param_value[$i];
		}
		$param_str = substr($param_str, 0, -4);
		$sql .= $param_str;  

		$r = $this->DATABASE->prepare($sql);
		$r->execute(array_values($execute_data));
    	return $r->rowCount(); 
    }

    public function delete($_tableName, $_params = array()) {
    	$sql = null;
    	$param_str = null;
		$sql .= "DELETE FROM `".$this->prefix($_tableName)."` WHERE ";  
		$param_key = array_keys($_params);
		$param_value = array_values($_params);
		for($i = 0;$i < count($_params);$i++) {
			$param_str .= "`".$param_key[$i]."` = ? AND";
			$execute_data[] = $param_value[$i];
		}
		$param_str = substr($param_str, 0, -4);
		$sql .= $param_str;

		$r = $this->DATABASE->prepare($sql);
		$r->execute(array_values($execute_data)); 	
    	return $r->rowCount();
    }

    public function deleteQuery($_tableName, $_sql) {
    	$sql = null;
		$param_str = null;
		$sql .= "DELETE FROM `".$this->prefix($_tableName)."`";  
		if(!empty($_sql)) $sql .= " ".$_sql;
		$r = $this->DATABASE->prepare($sql);
		$r->execute();
    }

	/*
	public function rowCount() {
		return $this->DATABASE_DATA->rowCount();
	}

	public function lastInsertId() {
		return $this->DATABASE->lastInsertId();
	}
	*/
	private function prefix($tableName) {
		if(strpos($tableName, $this->DATABASE_PREFIX) === false) {
			return $this->DATABASE_PREFIX.$tableName;
		}
		else return $tableName;
	}

	public function getError() {
		return $this->DATABASE_ERROR;
	}

	public function __destruct() {
		unset($this->DATABASE, $this->DATABASE_TABLE, $this->DATABASE_USER, $this->DATABASE_PASS, $this->DATABASE_SERVER, $this->DATABASE_PREFIX, $this->DATABASE_DATA, $this->DATABASE_ERROR);
	}
}

?>