<?php

$path = "../adodb/adodb.inc.php";
include ("../admin/var.php");
include ("../admin/funciones.php");
include ("../conexion.php");

	
	//Variables para la carga de un modulo especifico
	$variables_ver = variables_metodo("imagen");
	$imagen= 				$variables_ver[0];
	
	$result = $db->Execute("UPDATE ic_galeria_imagenes SET visitas_imagen=visitas_imagen+1 WHERE id_imagen='".$imagen."'");
	
	//----------------------------------------------------------------------------------------

	
	$sql = "SELECT nombre_imagen, img_original
		    FROM ic_galeria_imagenes WHERE id_imagen='".$imagen."' ";
	$result = $db->Execute($sql);
	
	list($nombre_imagen, $img_original)=select_format($result->fields);
?>
<img src="../<?=$img_original?>" border="0" title="<?=$nombre_imagen?>" />
