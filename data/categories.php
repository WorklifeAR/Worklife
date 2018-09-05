<?php
	$path = "../adodb/adodb.inc.php";
	include ("../admin/var.php");
	include ("../conexion.php");
	include ("../admin/lib/url.php");
	include ("../admin/lib/general.php");
	include ("../admin/lib/usuarios.php");
	include ("../admin/lib/fechas.php");
	
	header('Content-Type: application/json');
	
	$sql="SELECT cat_id,cat_titulo,cat_conten,cat_creacion,id_tipo,cat_sub_categoria,img_categoria,descuento,orden,cat_content_int,envio_gratis
        FROM ic_categoria WHERE id_tipo=7 AND cat_estatus='A' ORDER BY cat_titulo ASC";
	$result=$db->Execute($sql);
	
	$data=array();
	while(!$result->EOF){
		list($id,$titulo,$full,$fecha,$tipo,$sub_categoria,$imagen,$descuento,$orden,$cat_content_int,$envio_gratis)=select_format($result->fields);
		
		array_push($data, array("value"=>$id, "text"=>$titulo));
		
		$result->MoveNext();
	}
	
	echo json_encode($data);
?>