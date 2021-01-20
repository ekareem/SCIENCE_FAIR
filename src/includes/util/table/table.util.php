<?php
	//require_once "../../const/config.const.php";
	require_once  $PATH_ABSOLUTE."includes/dbh/retrive.dbh.php";
	require_once  $PATH_ABSOLUTE."includes/const/db.const.php";

	
	$typeinputMap = array('default' => 'text','int'=>'number','varchar'=>'text','date'=>'date','time'=>'time','year'=>'number','tinyint'=>'number','float'=>'number');
	function getSchema($table)
	{
		$quary = "SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '$table'";
		return quaryDB($quary);
	}
	
	function getInputType($column,$typeinputMap,$schemas)
	{
		foreach($schemas as $schema)
		{
			if($schema['COLUMN_NAME'] == $column)
				return $typeinputMap[$schema['DATA_TYPE']];
				
		}
		return $typeinputMap['default'];
	}
	
	function getSchemaValue($column,$schemas,$schemaColumn)
	{
		foreach($schemas as $schema)
		{
			if($schema['COLUMN_NAME'] == $column)
				return $schema[$schemaColumn];
		}
		return '';
	}
	
	function getPrimeryKey($table)
	{
		$schemas = getSchema($table);
		foreach($schemas as $schema)
		{
			if($schema['COLUMN_KEY'] == 'PRI')
				return $schema['COLUMN_NAME'];
		}
		return '';
	}
	function getRowColumn($db,$table)
	{
		/** column ***/
		$columnQuary = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '".$db."' AND TABLE_NAME = '".$table."'";
		$columnsArray = quaryDB($columnQuary);
		$columns = array();
		for($i=0; $i <count($columnsArray); $i++)
			array_push($columns, $columnsArray[$i]['COLUMN_NAME']);
		
		$rowQuary = "SELECT";
		for($i=0; $i <count($columns); $i++)
		{
			if($i != 0)
				$rowQuary .=",";
			$rowQuary .= " `".$columns[$i]."` ";
		}
		$rowQuary .= "FROM `$table`";
		$rows = quaryDB($rowQuary);
		
		return array("rows" =>$rows,"columns"=>$columns);
	}
	
	function printHeader($columns,$relations,$foreignMap)
	{
		echo '<tr>';
		for($j=0; $j <count($columns); $j++)
		{
			if(isForeign($columns[$j],$relations))
			{
				$value = $columns[$j];
				if(isset($foreignMap[$columns[$j]]))
				{
					$forigns = $foreignMap[$columns[$j]];
					foreach($forigns as $column)
					{
						$reference = getReference($columns[$j],$relations);
						
						$quary ="SELECT `".$column."` FROM `".$reference['REFERENCED_TABLE_NAME']."` WHERE `".$reference['REFERENCED_COLUMN_NAME']."` = '".$value."'";
						$result = quaryDB($quary);
						echo '<th> '.$column.' </th>';
					}
				}
				else
					echo '<th>'.$columns[$j].'</th>';
			}
			else
				echo '<th>'.$columns[$j].'</th>';
		}
		echo '<th><a href="?quary=add" class="glyphicon glyphicon-plus"></a></th>';
		echo '</tr>';
	}
	
	function printBody($rows,$columns,$idname,$relations,$foreignMap)
	{
		for($i=0; $i <count($rows); $i++)
		{
			echo '<tr>';
			for($j=0; $j <count($columns); $j++)
			{
				//print_r ($rows[$i]);
				//print_r $columns[$i];
				if(isForeign($columns[$j],$relations))
				{
					$value = $rows[$i][$columns[$j]];
					if(isset($foreignMap[$columns[$j]]))
					{
						$forigns = $foreignMap[$columns[$j]];
						foreach($forigns as $column)
						{
							$reference = getReference($columns[$j],$relations);
							
							$quary ="SELECT `".$column."` FROM `".$reference['REFERENCED_TABLE_NAME']."` WHERE `".$reference['REFERENCED_COLUMN_NAME']."` = '".$value."'";
							$result = quaryDB($quary);
							echo '<td> '.$result[0][$column].' </td>';
						}
					}
					else
						echo '<td>'.$rows[$i][$columns[$j]].'</td>';
				}
				else if($columns[$j] == 'Abstract')
				{
					echo'<td><textarea row = "10" style=" width: 300px; height: 50	px;" cols="5" class="form-control" disabled/>'.$rows[$i][$columns[$j]].'</textarea></td>';
					
				}
				else if($columns[$j] == 'Active')
				{
					if($rows[$i][$columns[$j]] == 0)
					{
						echo '<td><input id ="'.$i.'" type = "checkbox"  	disabled></input>';
						echo '<label for="'.$i.'"></label></td>';
					}
					else
					{
						echo '<td><input id ="'.$i.'" type = "checkbox"checked disabled></input>';
						echo '<label for="'.$i.'"></label></td>';
					}
						
				}
				/*else if($columns[$j] == 'Password')
				{
					$password = $rows[$i][$columns[$j]];
		
					for($l = 0; $l <  strlen($password); $l++)
						$password[$l] = "*";

					echo '<td>'.$password.'</td>';
				}*/
				else
					echo '<td>'.$rows[$i][$columns[$j]].'</td>';
				
			}
			//align=right
			echo '<td >';
			echo '<a href="?'.$idname.'='.$rows[$i][$idname].'&quary=edit" class="glyphicon glyphicon-edit"></a>';
			echo '<a href="?'.$idname.'='.$rows[$i][$idname].'&quary=delete" class="glyphicon glyphicon-trash"></a>';
			echo '</td>';
			echo '</tr>';
		}
	}
	
	function getRelation($table)
	{
		$relationQuary = "select * from INFORMATION_SCHEMA.KEY_COLUMN_USAGE where TABLE_NAME='$table' AND REFERENCED_TABLE_NAME is NOT NULL";
		//print_r($relationQuary);
		//print_r(quaryDB($relationQuary));
		return quaryDB($relationQuary);
	}
	
	function form($table,$rows,$columns,$relations,$idname,$foreignMap,$typeinputMap,$schemas)
	{	
		$scopes = ['YES'=>'','NO'=>'required'];
		$quary ="";
		$result = "";
		
		if(isset($_GET['quary']) && isset($_GET[$idname]) )
			$quary = "SELECT * FROM `$table` WHERE `$idname` = ".$_GET[$idname];
		
		$result = quaryDB($quary);
		if (!empty($result))
		{
			$result = $result[0];
		}
		foreach($columns as $column)
		{
			$value = "";
			if(!empty($result))
			{
				$value = $result[$column];
			}
			if(isForeign($column,$relations))
			{
				$reference = getReference($column,$relations);
				if(isset($foreignMap[$column]))
				{
					$useColumn = $foreignMap[$column];
					
					$quary ="SELECT `".$reference['REFERENCED_COLUMN_NAME']."`";
					foreach($useColumn as $use)
					{
						$quary .=",`".$use."`";
					}
					$quary .=  "FROM `".$reference['REFERENCED_TABLE_NAME']."`";
					$foreigns = quaryDB($quary);
					//print_r($foreigns);
					echo '<div class="form-group">';
					echo '<label>'.$column.'</label>';
					echo '<select  name = '.$column.' id = "'.$column.'">';
					$ar = 0;
					foreach($foreigns as $foreign)
					{
						print_r($foreign);
						//echo '<br>';
						//echo '<br>';
						$answer = "";
						foreach($useColumn as $ans)
						{
							$answer .= $foreign[$ans].' ';
							//print(" ----------".$ans." -----------");
							
						}
						//echo $answer;
						//echo '<br>';
						$selected = ($foreign[$reference['REFERENCED_COLUMN_NAME']] == $value)? 'selected' : '';
						//echo $selected;
						$disabled = '';
						
						if($column != "AdminLevelID" || (($column == "AdminLevelID" && $selected != '' && $ar == 0) || (isset($_GET['quary']) && $_GET['quary'] == 'add')))
						{
							echo '<option value="'.$foreign[$reference['REFERENCED_COLUMN_NAME']].'"'.$selected.' '.$disabled.'>'.$answer.'</option>';
							$ar = 1;
						}	
					}
					echo '</select>';
					echo'</div>';
					//print_r($quary);
					//print_r($foreigns);
					
					//echo '<td> '.$result[0][$column].' </td>';
					//SELECT COLUMN_NAME, DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'ADMIN_LEVEL'
				}
				else 
				{
					
					$quary ="SELECT `".$reference['REFERENCED_COLUMN_NAME']."` FROM `".$reference['REFERENCED_TABLE_NAME']."`";
					$foreigns = quaryDB($quary);
						echo '<div class="form-group">';
					echo '<label>'.$column.'</label>';
					echo '<select  name = '.$column.' id = "'.$column.'">';
					
					foreach($foreigns as $foreign)
					{
						$selected = ($foreign[$reference['REFERENCED_COLUMN_NAME']] == $value)? 'selected' : '';
						echo $selected;
						echo '<option value="'.$foreign[$reference['REFERENCED_COLUMN_NAME']].'"'.$selected.'>'.$foreign[$reference['REFERENCED_COLUMN_NAME']].'</option>';
					}
					echo '</select>';
					echo'</div>';
				}
			}
			else if($column == $idname)
			{
				echo '<div class="form-group">
				<label>'.$column.'</label>
				<input name = "'.$column.'" type="text" class="form-control" value = "'.$value.'" readonly>
				</div>';
			}
			else if($column == 'Active')
			{
				$checked = "";
				if(isset($result[$column]))
					$checked = ($result[$column] == 0)?'' : 'checked';
				echo '<div class="form-group">
				<label>'.$column.'</label>
				<input name = "'.$column.'" type="hidden" class="form-control" value = "0" >
				<input id = "Active" name = "'.$column.'" type="checkbox" class="form-control" value = "1" '.$checked.'>
				<label for="Active"></label>
				</div>';
			}
			else if($column == 'Abstract')
			{
				echo '<div class="form-group">
				<label>'.$column.'</label>
				<textarea row = "4"  name = "'.$column.'" class="form-control" '.$scopes[getSchemaValue($column,$schemas,'IS_NULLABLE')].'/>'.$value.'</textarea>
				</div>';
			}
			/*else if($column == 'Password')
			{
				echo '<div class="form-group">
				<label>'.$column.'</label>
				<input name = "'.$column.'" type="password" class="form-control" value = "'.$value.'" '.$scopes[getSchemaValue($column,$schemas,'IS_NULLABLE')].'>
				</div>';
			}*/
			else if($column == 'Password')
			{
				echo '<div class="form-group">
				<label>'.$column.'</label>
				<input name = "'.$column.'" type="password" class="form-control" value = "'.$value.'" '.$scopes[getSchemaValue($column,$schemas,'IS_NULLABLE')].'>
				</div>';
			}
			
			else if($column == 'AverageRanking')
			{
				echo '<div class="form-group">
				<label>'.$column.'</label>
				<input name = "'.$column.'" type="number" class="form-control" value = "'.$value.'" '.$scopes[getSchemaValue($column,$schemas,'IS_NULLABLE')].'readonly>
				</div>';
			}
			else 
			{
				echo '<div class="form-group">
				<label>'.$column.'</label>
				<input name = "'.$column.'" type="'.getInputType($column,$typeinputMap,$schemas).'" class="form-control" value = "'.$value.'" '.$scopes[getSchemaValue($column,$schemas,'IS_NULLABLE')].'>
				</div>';
			}
			
			
		}
	}
	
	function isForeign($column,$relations)
	{
		//echo $column.'<br>';
		//print_r($relations);
		//echo '<----------------------------------------->';
		foreach($relations as $key =>$relation)
			if ($relation['COLUMN_NAME'] == $column)
				return true;
			
		return false;
	}
	
	function getReference($column,$relations)
	{
		foreach($relations as $relation)
			if ($relation['COLUMN_NAME'] == $column)
				return array( 'REFERENCED_TABLE_NAME' => $relation['REFERENCED_TABLE_NAME'],'REFERENCED_COLUMN_NAME'=>$relation['REFERENCED_COLUMN_NAME']);
			
		return array();
	}

	function add($table,$columns,$id,$url)
	{

		$quary = "INSERT INTO `$table`(";
		$count = 0;
		foreach($columns as $column)
		{
			
			if ($column != $id)
			{
				if($count != 0)
					$quary .= ",";
				$quary .="`".$column."`";
				$count++;
			}
			
		}
		$quary .= ") VALUES (";
		$count = 0;
		foreach($columns as $column)
		{
			
			if ($column != $id)
			{
				if($count != 0)
					$quary .= ",";
				$quary .="'".$_GET[$column]."'";	
				$count++;
			}
			
		}
		$quary .= ")";
		$run = quaryDB($quary,true);
		if ($run)
		{
			Header('location:'.$url.'.php');
		}
		echo $quary;

	}
	function remove($table,$id,$url)
	{
		
			$quary ="DELETE FROM `$table` WHERE `$table`.`$id` = ".$_GET[$id];
			$run = quaryDB($quary,true);
			if ($run)
			{
				Header('location:'.$url.'.php');
			}
		
	}
	function edit($table,$columns,$idname,$url)
	{
		//SELECT `AdminLevelID`, `LevelName` FROM `ADMIN_LEVEL` WHERE `AdminLevelID` = 1
		//UPDATE `ADMIN_LEVEL` SET `AdminLevelID`=[value-1],`LevelName`=[value-2] WHERE 1
	
		$quary = "UPDATE `$table` SET";
		$count = 0;
		foreach($columns as $column)
		{
			
			if ($column != $idname)
			{
				if($count != 0)
					$quary .= ",";
				print_r($_GET);
				$quary .= " `$column` ='".$_GET[$column]."' ";
				$count++;
			}	
		}
		$quary .= " WHERE `$idname` = '".$_GET[$idname]."'";

		$run = quaryDB($quary,true);
		if ($run)
		{
			Header('location:'.$url.'.php');
		}
	}