<?php
	session_start();
	$path = "../adodb/adodb.inc.php";
	include "var.php";
	include "../conexion.php";
	include("lib/general.php");
	include("lib/usuarios.php");
	session_permiso("120");
	
	$base=$var_bdname[4];
	
	$tablas="SHOW TABLES FROM ".$base.";";
	$result=$db->execute($tablas);
	
	$texto.="CREATE DATABASE IF NOT EXISTS ".$base.";\n";
	$texto.="USE $base;\n\n\n";
	
	while(!$result->EOF){
		
		list($mitabla)=$result->fields;
		
		$creates="SHOW CREATE TABLE ".$mitabla.";";
		$result_2=$db->execute($creates);
		
		while(!$result_2->EOF){
			list($tabla, $texto_tabla)=$result_2->fields;
			
			$texto .= "-- ------------------------------------------------------------------------------------------ \n\n";
			$texto .= $texto_tabla.";\n\n";
			
			$datos="SELECT * FROM ".$mitabla.";";
			$result_3=$db->execute($datos);
			
			$campos=$db->MetaColumnNames($mitabla);
			
			$regs = $result_3->RecordCount();
			
			for($i=0;$i<$regs;$i++)
			{
				$inserta="INSERT INTO ".$mitabla."(";
				
				foreach ($campos as $clave => $campos_db) {
					$nombre=$campos[$clave];
					$inserta.=$nombre.",";
				}
								
				$inserta=substr($inserta,0,strlen($inserta)-1).") VALUES(";
				
				for($j=0;$j<count($campos);$j++)
				{
					//$tipo=mysql_field_type($datos,$j);
					$valor=$result_3->fields[$j];
					/*switch($tipo)
					{
					case "string":
					case "date":
					case "time":
					$valor="'$valor'";
					break;
					}*/
					$inserta.="'$valor',";
				}
				$inserta=substr($inserta,0,strlen($inserta)-1).");";
				$texto.=$inserta."\n";
				
				$result_3->MoveNext();
			}
			
			$result_2->MoveNext();
		}
		$texto.="\n";
		
		$result->MoveNext();
	}
	
	$db->close();
	
	/*$archivo= $base.".sql";
	header("Content-disposition: attachment;filename=".$archivo."");
	header("Content-Type: text/plain");*/
	echo "<pre>". htmlentities($texto)."</pre>";
?>