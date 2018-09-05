<?php
	
	header("Content-Type: application/vnd.ms-excel");
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("content-disposition: attachment;filename=Sales_Products_".date('dmY').".xls");

	$path = "../adodb/adodb.inc.php";
	include "var.php";
	include "../conexion.php";
	include ("lib/general.php");
	include ("lib/usuarios.php");
	include ("../compras/funciones_carrito.php");
	
	//session_permiso("200");
	
	$variables_metodo = variables_metodo("fecha_pedido,estados,fecha_pedido_end,reseller");
	$fecha_pedido= 			$variables_metodo[0];
	$estados= 				$variables_metodo[1];
	$fecha_pedido_end= 		$variables_metodo[2];
	$reseller= 				$variables_metodo[3];
	
	$fecha_pedido_w="";
	$fecha_pedido_end_w="";
	
	if($fecha_pedido!=""){
		$fecha_pedido_w = " AND fecha_pedido>='".$fecha_pedido."'";
	}
	
	if($fecha_pedido_end!=""){	
		$fecha_pedido_end_w = " AND fecha_pedido<='".$fecha_pedido_end."' ";
	}

	if($estados!=""){
		$estados = " AND estado='".$estados."'";
	}
	
	if($reseller!=""){
		$reseller = " AND reseller='".$reseller."'";
	}

	//
	$sql="SELECT id_articulo, cantidad, total, descuento FROM (
	        SELECT  id_articulo, sum( cantidad ) AS cantidad, sum( cantidad * precio_articulo ) AS total, sum( descuento ) AS descuento
			 FROM ic_pedidos
	         WHERE 1=1 ".$estados.$fecha_pedido_w.$fecha_pedido_end_w.$reseller." 
		     GROUP BY id_articulo 
		    UNION
		    SELECT id_item, '0' AS cantidad, '0' AS pedidos, '0' AS descuento
		     FROM ic_catalogo
		  ) tabla
		  ORDER BY cantidad DESC";
	$result=$db->Execute($sql);

?>
<table border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td colspan="4">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="4"><h1>Intimate Candy - Product Sales Report</h1></td>
  </tr>
  <tr>
    <td colspan="4">&nbsp;</td>
  </tr>
</table>

<table border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><strong>Date range:</strong></td>
    <td><?=$fecha_pedido?>/<?=$fecha_pedido_end?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>

<table border="1" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center" bgcolor="#CCCCCC"><strong>Products</strong></td>
    <td align="center" bgcolor="#CCCCCC"><strong>Quantity Sold</strong></td>
    <td align="center" bgcolor="#CCCCCC"><strong>Actual Price</strong></td>
    <td align="center" bgcolor="#CCCCCC"><strong>Sale Price Total</strong></td>
    <td align="center" bgcolor="#CCCCCC"><strong>Average Amount</strong></td>
    <td align="center" bgcolor="#CCCCCC"><strong>Discount Total</strong></td>
    <td align="center" bgcolor="#CCCCCC"><strong>Average Discount</strong></td>
    <td align="center" bgcolor="#CCCCCC"><strong>Stock</strong></td>
  </tr>
<?php
$totales_arti = 0;
$totales_vent = 0;
$totales_desc = 0;

while(!$result->EOF){
	list($id_articulo,$cantidad,$total_vendido,$total_descuento)=$result->fields;
	
	$sql="SELECT nombre_item,precio,peso_consolidado FROM ic_catalogo WHERE id_item='".$id_articulo."'";
	$arti=$db->Execute($sql);
	list($nombre_item,$precio,$peso_consolidado)=$arti->fields;
	
	if($nombre_item==""){
		$sql="SELECT nombre_articulo,precio_articulo FROM ic_pedidos WHERE id_articulo='".$id_articulo."' ";
		$arti=$db->Execute($sql);
		list($nombre_item,$precio)=$arti->fields;
		
		$nombre_item = $nombre_item."(Article does not exist)";
	}
	
	/////////////////////////////////////////
	
	$sql="SELECT count(*) FROM ic_pedidos WHERE id_articulo='".$id_articulo."' AND descuento>0";
	$arti=$db->Execute($sql);
	list($cantidad_descuento)=$arti->fields;
	
	if($cantidad_descuento=="" || $cantidad_descuento==0){
		$cantidad_descuento = 1;	
	}
	
	$totales_arti += $cantidad;
	$totales_vent += $total_vendido;
	$totales_desc += $total_descuento;
?>
  <tr>
    <td width="200"><?=$nombre_item?></td>
    <td align="right"><?=formateo_numero($cantidad)?></td>
    <td align="right"><?=formateo_numero($precio)?></td>
    <td align="right"><?=formateo_numero($total_vendido)?></td>
    <td align="right"><?=formateo_numero($total_vendido/(($cantidad==0)?"1":$cantidad))?></td>
    <td align="right"><?=formateo_numero($total_descuento)?></td>
    <td align="right"><?=formateo_numero(($total_descuento/$cantidad_descuento))?></td>
    <td align="right"><?=$peso_consolidado?></td>
  </tr>

<?php

	$result->MoveNext();
}
?>
</table>
<table border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
<table border="1" cellspacing="0" cellpadding="0">
  <tr>
    <td align="right"><strong>Grand Total</strong></td>
    <td align="right"><strong><?=formateo_numero($totales_arti)?></strong></td>
    <td align="right">&nbsp;</td>
    <td align="right"><strong><?=formateo_numero($totales_vent)?></strong></td>
    <td align="right">&nbsp;</td>
    <td align="right"><strong><?=formateo_numero($totales_desc)?></strong></td>
    <td align="right">&nbsp;</td>
  </tr>
</table>
