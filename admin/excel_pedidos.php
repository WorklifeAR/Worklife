<?php
	
header("Content-Type: application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=Ordenes_de_Compra_".date('d_m_Y').".xls");

	$path = "../adodb/adodb.inc.php";
	include "var.php";
	include "../conexion.php";
	include ("lib/general.php");
	include ("lib/usuarios.php");
	include ("../compras/funciones_carrito.php");
	
	session_permiso("200");
	
	$variables_metodo = variables_metodo("fecha_pedido,estados,departamento,fecha_pedido_end,reseller");
	$fecha_pedido= 			$variables_metodo[0];
	$estados= 				$variables_metodo[1];
	$departamento= 			$variables_metodo[2];
	$fecha_pedido_end= 		$variables_metodo[3];
	
	if($fecha_pedido!=""){
		if($fecha_pedido_end==""){
			$fecha_pedido = " AND fecha_pedido='".$fecha_pedido."'";
		}else{
			$fecha_pedido = " AND (fecha_pedido>='".$fecha_pedido."' AND fecha_pedido<='".$fecha_pedido_end."') ";
		}
	}

	if($estados!=""){
		$estados = " AND estado='".$estados."'";
	}
	
	if($reseller!=""){
		$reseller = " AND estado='".$reseller."'";
	}
	
	if($departamento!=""){
		$departamento = " AND departamento_envio LIKE '%- ".$departamento." -%'";
	}
	
	$sql="SELECT id_pedido,id_usuario,id_articulo,nombre,email,fecha_pedido,pais,ciudad,cp,
				direccion,telefono,estado,metodo_pago,nombre_articulo,
				SUM(cantidad*precio_articulo),impuesto,envio,valor_envio,comentarios,reseller
      FROM ic_pedidos WHERE 1=1 ".$estados.$fecha_pedido.$departamento.$reseller." GROUP BY id_pedido ";
	$result=$db->Execute($sql);

?>

<table border="1" cellspacing="0" cellpadding="0">
  <tr>
    <td bgcolor="#CCCCCC"><div align="center"><strong>Numero</strong></div></td>
    <td bgcolor="#CCCCCC"><div align="center"><strong>Estado</strong></div></td>
    <td bgcolor="#CCCCCC"><div align="center"><strong>Seller</strong></div></td>
    <td bgcolor="#CCCCCC"><div align="center"><strong>Fecha</strong></div></td>
    <td bgcolor="#CCCCCC"><div align="center"><strong>Metodo Pago</strong></div></td>
    <td bgcolor="#CCCCCC"><div align="center"><strong>Nombre Cliente</strong></div></td>
    <td bgcolor="#CCCCCC"><div align="center"><strong>Apellido Cliente</strong></div></td>
    <td bgcolor="#CCCCCC"><div align="center"><strong>Direccion Cliente</strong></div></td>
    <td bgcolor="#CCCCCC"><div align="center"><strong>Ciudad Cliente</strong></div></td>
    <td bgcolor="#CCCCCC"><div align="center"><strong>Provincia/Estado</strong></div></td>
    <td bgcolor="#CCCCCC"><div align="center"><strong>ZIP</strong></div></td>
    <td bgcolor="#CCCCCC"><div align="center"><strong>Pais</strong></div></td>
    <td bgcolor="#CCCCCC"><div align="center"><strong>Telefono</strong></div></td>
    <td bgcolor="#CCCCCC"><div align="center"><strong>E-Mail</strong></div></td>
    <td bgcolor="#CCCCCC"><div align="center"><strong>Valor Pedido</strong></div></td>
    <td bgcolor="#CCCCCC"><div align="center"><strong>Impuesto</strong></div></td>
    <td bgcolor="#CCCCCC"><div align="center"><strong>Metodo Envio</strong></div></td>
    <td bgcolor="#CCCCCC"><div align="center"><strong>Costo de Envio</strong></div></td>
    <td bgcolor="#CCCCCC"><div align="center"><strong>Total</strong></div></td>
    <td bgcolor="#CCCCCC"><div align="center"><strong>Comentarios</strong></div></td>
  </tr>
<?php
$total_factura = 0;
$id_pedido_listado="";

while(!$result->EOF){

	list($id_pedido,$id_usuario,$id_articulo,$nombre,$email,$fecha_pedido,$pais,$ciudad,$cp,$direccion,$telefono,$estado,$metodo_pago,$nombre_articulo,$precio,$impuesto,$envio,$valor_envio,$comentarios,$reseller)=$result->fields;
	
	
	$sql="SELECT valor FROM ic_carrito_compras WHERE campo  ='".$estado."' AND opcion='ESTADO_PEDIDO'";
	$result_estado=$db->Execute($sql);
	list($valor_estado)=$result_estado->fields;
	
	if($id_pedido_listado != $id_pedido){
		$id_pedido_listado = $id_pedido;
	}
	
	/*if($id_usuario!="9999999999"){
		$sql="SELECT us_pais,us_ciudad,us_nombre,us_direccion FROM ic_usuarios WHERE us_id  =".$id_usuario."";
		$result_usuario=$db->Execute($sql);
		list($us_pais,$us_ciudad,$us_nombre,$us_direccion)=$result_usuario->fields;
	}else{
		$us_pais="";
		$us_ciudad="";
		$us_nombre="";
		$us_direccion="";
	}
	*/
	
	$cliente=explode(" ", $nombre);
	$pais=explode("-", $pais);
	
	$vendedor = "Intimate Candy";
		
	if($reseller!="" && $reseller!="0"){
		$sql="SELECT us_nombre FROM ic_usuarios WHERE us_id =".$reseller." ";
		$result_reseller=$db->Execute($sql);
		list($vendedor)=$result_reseller->fields;
	}
?>
  <tr>
    <td><?=$id_pedido?></td>
    <td><?=$valor_estado?></td>
    <td><?=$vendedor?></td>
    <td><?=$fecha_pedido?></td>
    <td><?=$metodo_pago?></td>
    <td><?=$cliente[0]?></td>
	<td><?=$cliente[1]?></td>
    <td><?=$direccion?></td>
    <td><?=$ciudad?></td>
    <td><?=$pais[1]?></td>
    <td><?=$cp?></td>
    <td><?=$pais[0]?></td>
    <td><?=$telefono?></td>
    <td><?=$email?></td>
    <td><?=number_format($precio,2,',','')?></td>
    <td><?=$precio ?></td>
    <td><?=$envio?></td>
    <td><?=$valor_envio?></td>
    <td><?=($precio+$valor_impuesto+$valor_envio)?></td>
    <td><?=$comentarios?></td>
  </tr>

<?php

	$result->MoveNext();
	
	/*list($id_pedido,$id_usuario,$id_articulo,$fecha_pedido,$direccion,$telefono,$estado,$metodo_pago,$nombre_articulo,$cantidad,$precio_articulo,$comentarios,$cliente,$us_email,$valor_impuesto,$envio,$valor_envio,$departamento_envio)=$result->fields;
	
	if($id_pedido_listado != $id_pedido){
		$total_factura = 0;
	}*/
}
?>
</table>
