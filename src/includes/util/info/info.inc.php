<?php
    require_once  $PATH_ABSOLUTE."includes/const/config.const.php";
    require_once $PATH_ABSOLUTE."includes/util/info/info.util.php";

    
    //$table = "JUDGE";
    //$id = 1;
    $schemas = getSchema($table);
    $idCol = getPrimeryKeyCol($table);
    $data = getData($DB,$table,$idCol,$id)['rows'][0];
    $rc = getRowColumn($DB,$table);
    $relations = getRelation($table);
    $url = basename($_SERVER["SCRIPT_FILENAME"], '.php');
    $foreignMap = (isset($map[$table]))?$map[$table] : array();

    if(isset($_GET['submit']) && $_GET['submit'] == "add" && isset($_GET['update']) && $_GET['update'] == 'all' ){}
        #add($table,$columns,$id,$url);

    $rows = $rc["rows"];
	$columns = $rc["columns"];

    if(isset($_GET['submit']))
    {
        if($_GET['submit'] == "all")
            edit($table,$columns,$idCol,$url);
        if($_GET['submit'] != "all")
            edit2($table,$_GET['submit'],$idCol,$url,$id);
    }