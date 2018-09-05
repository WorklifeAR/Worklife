<?php
	
header("Content-Type: application/CSV");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=ListRefProducts.csv");

	$path = "../adodb/adodb.inc.php";
	include "var.php";
	include "../conexion.php";
	include "lib/general.php";
	include ("../compras/funciones_carrito.php");
	
	
	$sql="SELECT a.id_item, a.nombre_item, b.id, b.descripcion, b.valor, b.stock, b.id_campo 
		  FROM ic_catalogo a, ic_referencias_items b
		  WHERE b.id_articulo=a.id_item
		  ORDER BY a.nombre_item, b.id_campo ";
	$result=$db->Execute($sql);

echo "ID_ITEM;NAME;ID_REF;DESCRIPTION_REF;PRICE;STOCK;ORDER\n";

while(!$result->EOF){

	list($id_item,$nombre_item,$id,$descripcion,$valor,$stock,$id_campo)=$result->fields;
	
	echo $id_item.";".$nombre_item.";".$id.";".$descripcion.";".$valor.";".$stock.";".$id_campo."\n";

	$result->MoveNext();
}
?>