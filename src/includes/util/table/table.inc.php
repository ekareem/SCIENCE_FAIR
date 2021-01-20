<?php
	//require_once "../../const/config.const.php";
	require_once $PATH_ABSOLUTE."includes/util/table/table.util.php";
	
	$schemas = getSchema($table);
	$rc = getRowColumn($DB,$table);
	$relations = getRelation($table);
	$rows = $rc["rows"];
	$columns = $rc["columns"];
	$url = basename($_SERVER["SCRIPT_FILENAME"], '.php');
	$id = getPrimeryKey($table);
	$foreignMap = (isset($map[$table]))?$map[$table] : array();
	
	if(isset($_GET['submit']) && $_GET['submit'] == "add")
		add($table,$columns,$id,$url);

	if(isset($_GET['quary']) && $_GET['quary'] == "delete")
		remove($table,$id,$url);

	if(isset($_GET['submit']) && $_GET['submit'] == "edit")
		edit($table,$columns,$id,$url);
	
	