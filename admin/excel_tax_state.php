<?php
	
	header("Content-Type: application/vnd.ms-excel");
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("content-disposition: attachment;filename=Tax_State_".date('dmY').".xls");

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

	
	$sql="SELECT pais,sum(cantidad*precio_articulo) FROM ic_pedidos
	      WHERE 1=1 ".$estados.$fecha_pedido_w.$fecha_pedido_end_w.$reseller." GROUP BY pais ORDER BY pais";
	$result=$db->Execute($sql);

?>
<table border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td colspan="4">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="4"><h1>Intimate Candy - Tax Report</h1></td>
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
    <td bgcolor="#CCCCCC"><div align="center"><strong>State</strong></div></td>
    <td bgcolor="#CCCCCC"><div align="center"><strong>Total Sales</strong></div></td>
    <td bgcolor="#CCCCCC"><div align="center"><strong>Sales Tax</strong></div></td>
    <td bgcolor="#CCCCCC"><div align="center"><strong>Shipping Charges</strong></div></td>
  </tr>
<?php
$totales_impu = 0;
$totales_envi = 0;

while(!$result->EOF){
	list($estado,$total_pedido)=$result->fields;
	
	$sql="SELECT sum(envios.valor_envio) AS total_envios 
	      FROM (SELECT distinct a.id_pedido,a.valor_envio,a.pais FROM ic_pedidos a) envios 
		  WHERE envios.pais='".$estado."'";
	$result_envios=$db->Execute($sql);
	list($total_envios)=$result_envios->fields;
	
	$sql="SELECT sum(impuestos.valor_impuesto) AS total_impuesto 
	      FROM (SELECT distinct a.id_pedido,a.valor_impuesto,a.pais FROM ic_pedidos a) impuestos 
		  WHERE impuestos.pais='".$estado."'";
	$result_impuesto=$db->Execute($sql);
	list($total_impuesto)=$result_impuesto->fields;
	
	$totales_impu += $total_impuesto;
	$totales_envi += $total_envios;

?>
  <tr>
    <td width="200"><?=$estado?></td>
    <td align="right"><?=formateo_numero($total_pedido)?></td>
    <td align="right"><?=formateo_numero($total_impuesto)?></td>
    <td align="right"><?=formateo_numero($total_envios)?></td>
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
    <td>&nbsp;</td>
    <td align="right"><strong><?=formateo_numero($totales_impu)?></strong></td>
    <td align="right"><strong><?=formateo_numero($totales_envi)?></strong></td>
  </tr>
</table>
