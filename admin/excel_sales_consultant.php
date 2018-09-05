<?php
	
	header("Content-Type: application/vnd.ms-excel");
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("content-disposition: attachment;filename=Sales_Consultant_".date('dmY').".xls");

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

	
	$sql="SELECT reseller,pais,LOWER(ciudad),sum(cantidad),sum(precio_articulo) FROM ic_pedidos
	      WHERE reseller>0 ".$estados.$fecha_pedido_w.$fecha_pedido_end_w.$reseller." 
		  GROUP BY reseller,pais,LOWER(ciudad) ORDER BY reseller,pais,LOWER(ciudad)";
	$result=$db->Execute($sql);

?>
<table border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td colspan="4">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="4"><h1>Akai Sports - Reseller Report</h1></td>
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
    <td bgcolor="#CCCCCC">Consultant</td>
    <td bgcolor="#CCCCCC">City</td>
    <td bgcolor="#CCCCCC">State</td>
    <td bgcolor="#CCCCCC">Quantity or Products</td>
    <td bgcolor="#CCCCCC">Total Sales</td>
    <td bgcolor="#CCCCCC">Sales Tax</td>
    <td bgcolor="#CCCCCC">Shipping Charges</td>
    <td bgcolor="#CCCCCC">Total</td>
  </tr>
<?php
$totales_impu = 0;
$totales_envi = 0;
$totales_todos = 0;
$totales_ventas = 0;

while(!$result->EOF){
	list($reseller,$pais,$ciudad,$cantidad,$total)=$result->fields;
	
	$total_all = 0;
	
	$sql="SELECT us_nombre FROM ic_usuarios WHERE us_id='".$reseller."'";
	$result_usu=$db->Execute($sql);
	list($reseller)=$result_usu->fields;
	
	$sql="SELECT sum(envios.valor_envio) AS total_envios 
	      FROM (SELECT distinct a.id_pedido,a.valor_envio,a.pais,a.reseller,a.ciudad FROM ic_pedidos a) envios 
		  WHERE envios.pais='".$pais."' AND envios.reseller='".$reseller."' AND LOWER(envios.ciudad)='".$ciudad."'";
	$result_envios=$db->Execute($sql);
	list($total_envios)=$result_envios->fields;
	
	$sql="SELECT sum(impuestos.valor_impuesto) AS total_impuesto 
	      FROM (SELECT distinct a.id_pedido,a.valor_impuesto,a.pais,a.reseller,a.ciudad FROM ic_pedidos a) impuestos 
		  WHERE impuestos.pais='".$estado."' AND impuestos.reseller='".$reseller."' AND LOWER(impuestos.ciudad)='".$ciudad."'";
	$result_impuesto=$db->Execute($sql);
	list($total_impuesto)=$result_impuesto->fields;
	
	$totales_impu += $total_impuesto;
	$totales_envi += $total_envios;
	$totales_ventas += $total;
	
	$total_all = $total_impuesto + $total_envios + $total;
	$totales_todos += $total_all;
?>
  <tr>
    <td><?=$reseller?></td>
    <td><?=$pais?></td>
    <td><?=$ciudad?></td>
    <td align="right"><?=formateo_numero($cantidad)?></td>
    <td align="right"><?=formateo_numero($total)?></td>
    <td align="right"><?=formateo_numero($total_impuesto)?></td>
    <td align="right"><?=formateo_numero($total_envios)?></td>
    <td align="right"><?=formateo_numero($total_all)?></td>
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
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td align="right"><strong>Grand Total</strong></td>
    <td align="right"><strong><?=formateo_numero($totales_ventas)?></strong></td>
    <td align="right"><strong><?=formateo_numero($totales_impu)?></strong></td>
    <td align="right"><strong><?=formateo_numero($totales_envi)?></strong></td>
    <td align="right"><strong><?=formateo_numero($totales_todos)?></strong></td>
  </tr>
</table>
