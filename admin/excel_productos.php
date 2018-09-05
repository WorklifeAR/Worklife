<?php
	
	header("Content-Type: application/vnd.ms-excel");
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("content-disposition: attachment;filename=Products_".date('dmY').".xls");

	$path = "../adodb/adodb.inc.php";
	include "var.php";
	include "../conexion.php";
	include ("lib/general.php");
	include ("lib/usuarios.php");
	include ("../compras/funciones_carrito.php");
	
	//
	$sql="SELECT id_item,l_lang,nombre_item,peso,precio,descuento,sostiene_descuento,estado,
	             peso_consolidado,free_shipping,home,valor_envio FROM ic_catalogo ORDER BY id_item DESC";
	$result=$db->Execute($sql);

?>
<table border="1" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center" bgcolor="#CCCCCC"><strong>Id</strong></td>
    <td align="center" bgcolor="#CCCCCC"><strong>Language</strong></td>
    <td align="center" bgcolor="#CCCCCC"><strong>Name</strong></td>
    <td align="center" bgcolor="#CCCCCC"><strong>Order</strong></td>
    <td align="center" bgcolor="#CCCCCC"><strong>Price</strong></td>
    <td align="center" bgcolor="#CCCCCC"><strong>Sale Off</strong></td>
    <td align="center" bgcolor="#CCCCCC"><strong>Holding Off</strong></td>
    <td align="center" bgcolor="#CCCCCC"><strong>Status</strong></td>
    <td align="center" bgcolor="#CCCCCC"><strong>Qty Equivalent</strong></td>
    <td align="center" bgcolor="#CCCCCC"><strong>Free Shipping</strong></td>
    <td align="center" bgcolor="#CCCCCC"><strong>View in Home</strong></td>
    <td align="center" bgcolor="#CCCCCC"><strong>Shipping Value</strong></td>
  </tr>
<?php

while(!$result->EOF){
	list($id_item,$l_lang,$nombre_item,$peso,$precio,$descuento,$sostiene_descuento,$estado,
	     $peso_consolidado,$free_shipping,$home,$valor_envio)=select_format($result->fields);
	
?>
  <tr>
    <td><?=$id_item?></td>
    <td><?=$l_lang?></td>
    <td><?=$nombre_item?></td>
    <td><?=$peso?></td>
    <td><?=formateo_numero($precio)?></td>
    <td><?=$descuento?></td>
    <td><?=$sostiene_descuento?></td>
    <td><?=$estado?></td>
    <td><?=$peso_consolidado?></td>
    <td><?=$free_shipping?></td>
    <td><?=$home?></td>
    <td><?=formateo_numero($valor_envio)?></td>
  </tr>
<?php

	$result->MoveNext();
}
?>
</table>