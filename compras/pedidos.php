<table border="0" cellspacing="0" cellpadding="0" width="100%">
  <tr>
    <td><h1 class="mas_titulo"><?=_MIS_ORDENES?></h1></td>
  </tr>
</table>
<br />
<?php
if(!isset($_SESSION['sess_usu_id'])){
	echo '<META HTTP-EQUIV="Refresh" CONTENT="0;URL='.$_SESSION['c_base_location'].'user.php">';
	die();
}

$path = "adodb/adodb.inc.php";
include ("admin/var.php");
include ("conexion.php");

$moneda = obtenerDescripcion("TIPO_MONEDA_SELECCIONADA", "", $db);

$variables_ver = variables_metodo("id_pedido");
$id_pedido= 				$variables_ver[0];
	
if($id_pedido==""){
?>

<table width="100%" border="0" cellspacing="3" cellpadding="3">
  <tr align="center">
    <td bgcolor="#dddddd" class="titulo"># Pedido</td>
    <td bgcolor="#dddddd" class="titulo"><?=_SOLICITANTE_CARRITO?></td>
    <td bgcolor="#dddddd" class="titulo"><?=_DATE_CARRITO?></td>
	<td bgcolor="#dddddd" class="titulo"><?=_ESTADO_CARRITO?></td>
    <td bgcolor="#dddddd" class="titulo"><?=_TOTAL_CARRITO?></td>
  </tr>
<?php
	$sql="SELECT id_pedido,id_usuario,id_articulo,nombre,email,fecha_pedido,pais,ciudad,cp,direccion,
	             telefono,estado,metodo_pago,nombre_articulo,cantidad,precio_articulo,impuesto,envio,valor_envio,comentarios
	      FROM ic_pedidos 
		  WHERE id_usuario='".$_SESSION['sess_usu_id']."' GROUP BY id_pedido,id_usuario ORDER BY fecha_pedido DESC";
	$result=$db->Execute($sql);
		
	while(!$result->EOF){
		list($id_pedido,$id_usuario,$id_articulo,$nombre,$email,$fecha_pedido,$pais,$ciudad,$cp,$direccion,
	         $telefono,$estado,$metodo_pago,$nombre_articulo,$cantidad,$precio_articulo,$impuesto,$envio,$valor_envio,$comentarios)=select_format($result->fields);
		
		$estado = obtenerDescripcion("ESTADO_PEDIDO", $estado, $db);
		
		$sql="SELECT SUM( (cantidad * precio_articulo) ) FROM ic_pedidos WHERE id_pedido =".$id_pedido." GROUP BY id_pedido";
		$result_precio_total=$db->Execute($sql);
		list($precio_total)=$result_precio_total->fields;
		
		//---------------------------------------------------
		//---------------------------------------------------
		
		$sql="SELECT tipo_cupon,valor,productos_relacionados FROM ic_cupones WHERE pedido =".$id_pedido."";
		$usa_cupon=$db->Execute($sql);
		
		$total_cupon = 0;
		
		if(!$usa_cupon->EOF){
			list($tipo_cupon,$valor,$productos_relacionados)=$usa_cupon->fields;
			
			if($tipo_cupon=="P"){
		
				if($productos_relacionados==""){
					$total_cupon = ($precio_total * $valor) / 100;
				}else{
					$sql="SELECT id_articulo,precio_articulo FROM ic_pedidos WHERE id_pedido =".$id_pedido." AND id_articulo=".$productos_relacionados."";
					$result_pedido=$db->Execute($sql);
					list($id_articulo,$precio_articulo)=select_format($result->fields);
					
					$total_cupon = ($precio_articulo * $valor) / 100;
				}					
			}else{
				$total_cupon = $valor;
			}
			
			$precio_total = $precio_total - $total_cupon;
		}
		
		//---------------------------------------------------
		//---------------------------------------------------

		$precio_total = $precio_total * ( 1 + ( $impuesto / 100 ) );
?>
  <tr>
    <td align="center" bgcolor="#eeeeee"><a href="ver.php/mod/ordenes/id_pedido/<?=$id_pedido?>">(SB # <?=$id_pedido?>)</a></td>
    <td bgcolor="#eeeeee"><?=$nombre?></td>
    <td align="center" bgcolor="#eeeeee"><?=$fecha_pedido?></td>
	<td bgcolor="#eeeeee"><?=$estado->fields[0]?></td>
    <td align="right" bgcolor="#eeeeee"><?=$moneda->fields[0].formateo_numero($precio_total+$valor_envio)?></td>
  </tr>
<?php

		$result->MoveNext();
	}

?>
</table>
<br /><br />
<?php
}else{

$sql="SELECT id_pedido,id_usuario,id_articulo,nombre,email,fecha_pedido,pais,ciudad,cp,direccion,
	         telefono,estado,metodo_pago,nombre_articulo,cantidad,precio_articulo,impuesto,envio,
			 valor_envio,comentarios
      FROM ic_pedidos WHERE id_pedido =".$id_pedido."";
$result=$db->Execute($sql);

list($id_pedido,$id_usuario,$id_articulo,$nombre,$email,$fecha_pedido,$pais,$ciudad,$cp,$direccion,
	 $telefono,$estado,$metodo_pago,$nombre_articulo,$cantidad,$precio_articulo,$impuesto,$envio,$valor_envio,$comentarios)=select_format($result->fields);

$estado = obtenerDescripcion("ESTADO_PEDIDO", $estado, $db);
//$metodo_pago = obtenerDescripcion("FORMA_PAGO", $metodo_pago, $db);
$pais = explode("-",$pais);

?>
<p align="right"><a href="ver.php/mod/ordenes"><?=_BACK_MIS_ORDENES?></a></p>

<table width="100%" border="0" cellspacing="0" cellpadding="3">
  <tr>
    <td bgcolor="#E6E6E6"><table width="100%" border="0" cellspacing="3" cellpadding="3">
      <tr>
        <td width="150" bgcolor="#FFFFFF"><b>
          <?=_NUMBER_CARRITO?>
        </b></td>
        <td bgcolor="#FFFFFF"><?=$id_pedido?></td>
        <td width="150" bgcolor="#F3A9AB" style="color:#FFF"><b>
          <?=_ESTADO_CARRITO?>
        </b></td>
        <td bgcolor="#FFFFFF"><?=$estado->fields[0]?></td>
      </tr>
      <tr>
        <td bgcolor="#FFFFFF"><b>
          <?=_DATE_CARRITO?>
        </b></td>
        <td bgcolor="#FFFFFF"><?=$fecha_pedido?></td>
        <td bgcolor="#F3A9AB" style="color:#FFF"><b>
          <?=_FORMA_PAGO_PEDIDO?>
        </b></td>
        <td bgcolor="#FFFFFF"><?=$metodo_pago?></td>
      </tr>
      <tr>
        <td bgcolor="#FFFFFF"><b>
          <?=_NOMBRE_COMPLETO?>
          </b></td>
        <td colspan="3" bgcolor="#FFFFFF"><?=$nombre?></td>
      </tr>
      <!--<tr>
        <td bgcolor="#FFFFFF" valign="top"><b>
          <?=_REGUSER_DATPER?>
          </b></td>
        <td colspan="3" bgcolor="#FFFFFF"><?=$telefono?><br /><?=$direccion?><br><?=$ciudad?><br><?=$pais[1]?><br><?=$cp?><br><?=$pais[0]?></td>
      </tr>-->
    </table></td>
  </tr>
  <tr>
    <td bgcolor="#E6E6E6"><table width="100%" border="0" cellspacing="3" cellpadding="3">
      <tr>
        <td bgcolor="#999999"><div align="center"> <b>
          <?=_PRODUCTO_CARRITO?>
        </b></div></td>
        <td width="70" bgcolor="#999999"><div align="center"> <b>
          <?=_CANTIDAD_CARRITO?>
        </b></div></td>
        <td width="140" bgcolor="#999999"><div align="center"> <b>
          <?=_PRECIO_CARRITO?>
        </b></div></td>
        <td width="100" bgcolor="#999999"><div align="center"> <b>
          <?=_TOTAL_CARRITO?>
        </b></div></td>
      </tr>
      <?php
$total_factura = 0;

while(!$result->EOF){
	list($id_pedido,$id_usuario,$id_articulo,$nombre,$email,$fecha_pedido,$pais,$ciudad,$cp,$direccion,
	     $telefono,$estado,$metodo_pago,$nombre_articulo,$cantidad,$precio_articulo,$impuesto,$envio,
	     $valor_envio,$comentarios)=select_format($result->fields);
	
	
	$total_arti = $cantidad*$precio_articulo;
?>
      <tr>
        <td bgcolor="#FFFFFF"><?=$nombre_articulo?> 
        </td>
        <td bgcolor="#FFFFFF"><div align="right">
          <?=$cantidad?>
        </div></td>
        <td bgcolor="#FFFFFF"><div align="right">
          <?=$moneda->fields[0].formateo_numero($precio_articulo)?>
        </div></td>
        <td bgcolor="#FFFFFF"><div align="right">
          <?=$moneda->fields[0].formateo_numero($total_arti)?>
        </div></td>
      </tr>
      <?php
	
	$total_factura = $total_factura + $total_arti;
	
	$result->MoveNext();
}
?>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td bgcolor="#F3A9AB" style="color:#FFF"><div align="right"><b>
          <?=_SUBTOTAL_CARRITO?>
        </b></div></td>
        <td bgcolor="#FFE3CA"><div align="right"> <b>
          <?=$moneda->fields[0]." ".formateo_numero( $total_factura )?>
        </b> </div></td>
      </tr>
<?php
//---------------------------------------------------
//---------------------------------------------------

$sql="SELECT tipo_cupon,valor,productos_relacionados,categoria_relacionada FROM ic_cupones WHERE pedido =".$id_pedido."";
$usa_cupon=$db->Execute($sql);

$total_cupon = 0;

if(!$usa_cupon->EOF){
	list($tipo_cupon,$valor,$productos_relacionados,$categoria_relacionada)=$usa_cupon->fields;
	
	if($tipo_cupon=="P"){

		if($productos_relacionados!=""){
			$sql="SELECT id_articulo,precio_articulo,cantidad 	 FROM ic_pedidos WHERE id_pedido =".$id_pedido." AND id_articulo=".$productos_relacionados."";
			$result_pedido_des=$db->Execute($sql);
			list($id_articulo,$precio_articulo,$cantidad)=select_format($result_pedido_des->fields);
			
			$total_cupon = (($precio_articulo*$cantidad) * $valor) / 100;
		}elseif($categoria_relacionada!=""){
			$sql="SELECT id_articulo,precio_articulo,cantidad 	 FROM ic_pedidos WHERE id_pedido =".$id_pedido." AND id_articulo IN (SELECT id_item FROM ic_catalogo WHERE categoria_item = ".$categoria_relacionada.")";
			$result_pedido_des=$db->Execute($sql);
			list($id_articulo,$precio_articulo,$cantidad)=select_format($result_pedido_des->fields);
			
			$total_cupon = (($precio_articulo*$cantidad) * $valor) / 100;
		}else{
			$total_cupon = (($precio_articulo*$cantidad) * $valor) / 100;
		}	
				
	}else{
		$total_cupon = $valor;
	}
	$total_cupon = $total_cupon * -1;
	$total_factura = $total_factura + $total_cupon;
}

//---------------------------------------------------
//---------------------------------------------------
?> 
<tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td bgcolor="#F3A9AB" style="color:#FFF"><div align="right"><b>
          Discounts
        </b></div></td>
        <td bgcolor="#FFE3CA"><div align="right"> <b>
          <?=$moneda->fields[0]." ".formateo_numero( $total_cupon )?>
        </b> </div></td>
      </tr>
	    
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td bgcolor="#F3A9AB" style="color:#FFF"><div align="right"><b>
          <?=_IMPUESTO?>
        </b></div></td>
        <td bgcolor="#FFE3CA"><div align="right"> <b>
          <?=$moneda->fields[0]." ".formateo_numero( ( ($total_factura * $impuesto ) / 100 ) )?>
        </b> </div></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td bgcolor="#F3A9AB" style="color:#FFF"><div align="right"><b>
          <?=_METODO_ENVIO."</b><br />".$envio?>
        </b></div></td>
        <td bgcolor="#FFE3CA"><div align="right"> <b>
          <?=$moneda->fields[0]." ".formateo_numero( $valor_envio )?>
        </b> </div></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td bgcolor="#F3A9AB" style="color:#FFF"><div align="right"><b>
          <?=_TOTAL_CARRITO?>
        </b></div></td>
        <td bgcolor="#FFE3CA" nowrap="nowrap"><div align="right"> <b>
          <?=$moneda->fields[0]." ".formateo_numero( ( $total_factura + ( ( $total_factura * $impuesto ) / 100 ) + $valor_envio ) )?>
        </b> </div></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td bgcolor="#CCCCCC"><table width="100%" border="0" cellspacing="3" cellpadding="3">
      <tr>
        <td bgcolor="#999999"><b>
          <?=_COMENTARIOS_PEDIDO?>
        </b></td>
      </tr>
      <tr>
        <td bgcolor="#FFFFFF"><?=$comentarios?></td>
      </tr>
    </table></td>
  </tr>
</table>
<?php
}
?>

