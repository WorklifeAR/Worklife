<?php
$path = "adodb/adodb.inc.php";
include ("admin/var.php");
include ("conexion.php");
include ("admin/lib/general.php");

header('Content-Type: application/json');

$sql="SELECT `cat_id` AS id, `cat_titulo` AS titulo, 'Categoría' AS tipo FROM `ic_categoria` WHERE id_tipo=7 
UNION 
SELECT `id_directorio` AS id, `nombre` AS titulo, 'Publicación' AS tipo FROM `ic_directorio` WHERE activo=1 ORDER BY titulo ASC";
$result=$db->Execute($sql);

$datos = array();

while(!$result->EOF){
	list($cat_id,$cat_titulo,$tipo)=select_format($result->fields);
	
	$cat_titulo = str_replace("'","",$cat_titulo);
	
	array_push($datos, array("id"=>$cat_id,"label"=>($cat_titulo),"cate"=>($tipo)));
	//array_push($datos, $cat_titulo);
	$result->MoveNext();
}

echo json_encode($datos);
?>