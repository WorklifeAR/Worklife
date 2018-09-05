<?php
	
header("Content-Type: application/CSV");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=ListProductsCatalog.csv");

	$path = "../adodb/adodb.inc.php";
	include "var.php";
	include "../conexion.php";
	include "lib/general.php";
	include ("../compras/funciones_carrito.php");
	
	
	$sql="SELECT id_item, nombre_item, previa_item, full_item, categoria_item, peso, precio, descuento, sostiene_descuento, estado, peso_lb, alto, ancho, largo, peso_consolidado, free_shipping, home, valor_envio, marca, genero, keywords
		  FROM ic_catalogo 
		  ORDER BY nombre_item ";
	$result=$db->Execute($sql);

echo "ID_ITEM;NAME;CATEGORIES;ORDER;PRICE;SALE_OFF;ACCUMULATES_DISCOUNT;STATUS;WEIGHT;WIDTH;HEIGHT;LENGTH;POINTS_ORDER;FREE_SHIPPING;VIEW_HOME;SHIPPING_COST;BRAND;GENDER;KEYWORDS\n";

while(!$result->EOF){

	list($id_item,$nombre_item,$previa_item,$full_item,$categoria_item,$peso,$precio,$descuento,$sostiene_descuento,$estado,$peso_lb,$alto,$ancho,$largo,$peso_consolidado,$free_shipping,$home,$valor_envio,$marca, $genero, $keywords)=$result->fields;
	$previa_item = preg_replace("/\r\n+|\r+|\n+|\t+/i", " ", strip_tags($previa_item));
	$previa_item = str_replace(";", ",", trim($previa_item));
	
	$full_item = preg_replace("/\r\n+|\r+|\n+|\t+/i", " ", strip_tags($full_item));
	$full_item = str_replace(";", ",", trim($full_item));
	
	echo $id_item.";".$nombre_item.";".$categoria_item.";".$peso.";".$precio.";".$descuento.";".$sostiene_descuento.";".$estado.";".$peso_lb.";".$alto.";".$ancho.";".$largo.";".$peso_consolidado.";".$free_shipping.";".$home.";".$valor_envio.";"./*$stock.";".$link_doc.";".$video.";".*/$marca.";".$genero.";".$keywords."\n";

	$result->MoveNext();
}
?>