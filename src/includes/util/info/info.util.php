<?php
    //require_once "../../const/config.const.php";
	require_once  $PATH_ABSOLUTE."includes/dbh/retrive.dbh.php";
    require_once  $PATH_ABSOLUTE."includes/const/db.const.php";
    require_once  $PATH_ABSOLUTE."includes/util/table/table.util.php";
    ;
    
    $typeinputMap = array('default' => 'text','int'=>'number','varchar'=>'text','date'=>'date','time'=>'time','year'=>'number','tinyint'=>'number');
    

    function getPrimeryKeyCol($table)
	{
		$schemas = getSchema($table);
		foreach($schemas as $schema)
		{
			if($schema['COLUMN_KEY'] == 'PRI')
				return $schema['COLUMN_NAME'];
		}
		return '';
    }

    function getData($db,$table,$idCol,$id)
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
        $rowQuary .= "FROM `$db`.`$table` WHERE `$idCol` = $id";

		$rows = quaryDB($rowQuary);
		
		return array("rows" =>$rows,"columns"=>$columns);
    }
    
    function printHeaderInfo()
	{
        echo '<tr>';
        echo '<th>FIELD</th>';
        echo '<th>ENTRY</th>';
		echo '<th><a href="?update=all" class="glyphicon glyphicon-edit"></a></th>';
		echo '</tr>';
    }
    
    function printBodyInfo($datas,$id,$relations,$foreignMap)
	{
        $i = 0;

        foreach($datas as $col =>$data)
        {
            echo '<tr>';
            echo "<td>$col</td>";
            
            if(isForeign($col,$relations))
            {
                //$value = $rows[$i][$columns[$j]];
                if(isset($foreignMap[$col]))
                {
                    $forigns = $foreignMap[$col];
                    foreach($forigns as $column)
                    {
                        $reference = getReference($col,$relations);
                        
                        $quary ="SELECT `".$column."` FROM `".$reference['REFERENCED_TABLE_NAME']."` WHERE `".$reference['REFERENCED_COLUMN_NAME']."` = '".$data."'";
                        $result = quaryDB($quary);
                        echo '<td> '.$result[0][$column].' </td>';
                    }
                }
                else if($col== 'Active')
				{
					if($data == 0)
					{
						echo '<td><input id ="'.$i.'" type = "checkbox"  disabled></input>';
						echo '<label for="'.$i.'"></label></td>';
					}
					else
					{
						echo '<td><input id ="'.$i.'" type = "checkbox"checked disabled></input>';
						echo '<label for="'.$i.'"></label></td>';
					}
						
				}
                else
                    
                    echo "<td>$data</td>";
            }
            else
            {

                echo "<td>$data</td>";
               
            }
            
            echo "<th><a href=\"?update=$col\" class=\"glyphicon glyphicon-edit\"></a></th>";
            echo '</tr>';
            $i ++;
        }
    }

    function edit2($table,$column,$idname,$url,$id)
	{
		//SELECT `AdminLevelID`, `LevelName` FROM `ADMIN_LEVEL` WHERE `AdminLevelID` = 1
		//UPDATE `ADMIN_LEVEL` SET `AdminLevelID`=[value-1],`LevelName`=[value-2] WHERE 1
	
		$quary = "UPDATE `$table` SET";
		$count = 0;

        if ($column != $idname)
        {
            if($count != 0)
                $quary .= ",";
            $quary .= " `$column` ='".$_GET[$column]."' ";
            $count++;
        }	
		$quary .= " WHERE `$idname` = '".$id."'";

        print($quary);
		$run = quaryDB($quary,true);
		if ($run)
		{
			Header('location:'.$url.'.php');
		}
	}

    function formInfo($table,$rows,$columns,$relations,$idname,$foreignMap,$typeinputMap,$schemas,$data,$id)
	{	
		$scopes = ['YES'=>'','NO'=>'required'];
		$quary ="";
		$result = "";
		
		if(isset($_GET['update']) && $_GET['update'] == 'all' )
			$quary = "SELECT * FROM `$table` WHERE `$idname` = '$id'";
        
        
        $result = quaryDB($quary);
    
        //echo $quary;
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
					foreach($foreigns as $foreign)
					{
						print_r($foreign);
						$answer = "";
						foreach($useColumn as $ans)
						{
							$answer .= $foreign[$ans].' ';
							//print(" ----------".$ans." -----------");
							
						}
						$selected = ($foreign[$reference['REFERENCED_COLUMN_NAME']] == $value)? 'selected' : '';
						echo '<option value="'.$foreign[$reference['REFERENCED_COLUMN_NAME']].'"'.$selected.'>'.$answer.'</option>';
					}
					echo '</select>';
					echo'</div>';
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
			else 
			{
				echo '<div class="form-group">
				<label>'.$column.'</label>
				<input name = "'.$column.'" type="'.getInputType($column,$typeinputMap,$schemas).'" class="form-control" value = "'.$value.'" '.$scopes[getSchemaValue($column,$schemas,'IS_NULLABLE')].'>
				</div>';
			}
			
			
		}
	}
    
    function formInfo2($table,$column,$relations,$idname,$foreignMap,$typeinputMap,$schemas,$data,$id)
	{	
		$scopes = ['YES'=>'','NO'=>'required'];
		$quary ="";
		$result = "";
		
		if(isset($_GET['update']) && $_GET['update'] != 'all' )
			$quary = "SELECT * FROM `$table` WHERE `$idname` = '$id'";
        
        
        $result = quaryDB($quary);
    
		if (!empty($result))
		{
			$result = $result[0];
		}

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
                foreach($foreigns as $foreign)
                {
                    print_r($foreign);
                    $answer = "";
                    foreach($useColumn as $ans)
                    {
                        $answer .= $foreign[$ans].' ';
                        //print(" ----------".$ans." -----------");
                        
                    }
                    $selected = ($foreign[$reference['REFERENCED_COLUMN_NAME']] == $value)? 'selected' : '';
                    echo '<option value="'.$foreign[$reference['REFERENCED_COLUMN_NAME']].'"'.$selected.'>'.$answer.'</option>';
                }
                echo '</select>';
                echo'</div>';
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
        else 
        {
            echo '<div class="form-group">
            <label>'.$column.'</label>
            <input name = "'.$column.'" type="'.getInputType($column,$typeinputMap,$schemas).'" class="form-control" value = "'.$value.'" '.$scopes[getSchemaValue($column,$schemas,'IS_NULLABLE')].'>
            </div>';
        }
        
	}