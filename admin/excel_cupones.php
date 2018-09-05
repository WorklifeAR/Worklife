<?php
	session_start();
	
	header("Content-Type: application/vnd.ms-excel");
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("content-disposition: attachment;filename=Cupones_".date('Y_m_d').".xls");

	$path = "../adodb/adodb.inc.php";
	include "var.php";
	include "../conexion.php";
	include ("lib/general.php");
	include ("lib/usuarios.php");
	include ("../compras/funciones_carrito.php");
	
	session_permiso("501");

	$sql = "SELECT id_cupon,fecha_creacion,referencia,tipo_cupon,valor,fecha_inicio,fecha_final,productos_relacionados,usuario,fecha_uso,estado,pedido,categoria_relacionada
			FROM ic_cupones WHERE 1=1 ORDER BY id_cupon DESC";
	$result=$db->Execute($sql);
	
?>
<table border="1" cellspacing="0" cellpadding="0">
    <tr>
      <th bgcolor="#f4f4f4" >Codigo</th>
      <th bgcolor="#f4f4f4" >Creacion</th>
	  <th bgcolor="#f4f4f4" >Tipo</th>
	  <th bgcolor="#f4f4f4" >Valor</th>
	  <th bgcolor="#f4f4f4" >Inicio</th>
      <th bgcolor="#f4f4f4" >Terminacion</th>
	  <th bgcolor="#f4f4f4" >Producto Relacionado</th>
	  <th bgcolor="#f4f4f4" >Categoria Relacionada</th>
      <th bgcolor="#f4f4f4" >Estado</th>
      <th bgcolor="#f4f4f4" >Usado (usuario)</th>
    </tr>
<?php
while(!$result->EOF){
	list($id_cupon,$fecha_creacion,$referencia,$tipo_cupon,$valor,$fecha_inicio,$fecha_final,$productos_relacionados,$usado_por,$fecha_uso,$estado,$pedido,$categoria_relacionada)=select_format($result->fields);
	
	if($estado=="A"){
		$estado="Activo";	
	}
	if($estado=="I"){
		$estado="Inactivo";	
	}
	
	if($tipo_cupon=="V"){
		$tipo_cupon="Valor";	
	}
	if($tipo_cupon=="P"){
		$tipo_cupon="Porcentaje";	
	}

	//Consulta de los productos
	$sql="SELECT nombre_item FROM ic_catalogo WHERE id_item='".str_replace("*","",$productos_relacionados)."' ";
	$result_arti=$db->Execute($sql);
	
	list($productos_relacionados)=$result_arti->fields;
	
	$sql="SELECT cat_titulo FROM ic_categoria WHERE cat_id='".str_replace("*","",$categoria_relacionada)."' ";
	$result_cat=$db->Execute($sql);
	
	list($categoria_relacionada)=$result_cat->fields;
?>
    <tr>
      <td valign="top" nowrap="nowrap" style="font-size:10px"><?=$referencia?></td>
      <td valign="top" nowrap="nowrap" style="font-size:10px"><div align="center"><?=$fecha_creacion?></div></td>
	  <td valign="top" nowrap="nowrap" style="font-size:10px"><?=$tipo_cupon?></td>
      <td valign="top" nowrap="nowrap" style="font-size:10px"><?=$valor?></td>
	  <td valign="top" nowrap="nowrap" style="font-size:10px"><div align="center"><?=$fecha_final?></div></td>
	  <td valign="top" nowrap="nowrap" style="font-size:10px"><div align="center"><?=$fecha_final?></div></td>
	  <td valign="top" nowrap="nowrap" style="font-size:10px"><?=$productos_relacionados?></td>
	  <td valign="top" nowrap="nowrap" style="font-size:10px"><?=$categoria_relacionada?></td>
      <td valign="top" nowrap="nowrap" style="font-size:10px"><div align="center"><?=$estado?></div></td>
      <td valign="top" nowrap="nowrap" style="font-size:10px"><?php if($usado_por!=""){ echo $usado_por." (Order".$pedido.")"; }?></td>
    </tr>
<?php
$result->MoveNext();
}
?>
</table>