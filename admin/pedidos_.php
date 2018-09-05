<?php

session_permiso("200");


$variables_metodo = variables_metodo("fecha_pedido,estados,estado,comentarios,id_pedido,funcion,mail_usuario,fecha_pedido,fecha_pedido_end,departamento,reseller");

$fecha_pedido= 			$variables_metodo[0];
$estados= 				$variables_metodo[1];
$estado= 				$variables_metodo[2];
$comentarios= 			$variables_metodo[3];
$id_pedido= 			$variables_metodo[4];
$funcion= 				$variables_metodo[5];
$mail_usuario=			$variables_metodo[6];
$fecha_pedido= 			$variables_metodo[7];
$fecha_pedido_end= 		$variables_metodo[8];
$departamento= 			$variables_metodo[9];
$reseller= 				$variables_metodo[10];

if ($funcion == "actualizar"){
	if($id_pedido!=""){
	/*Funcion para guardar los datos del formulario*/	
		guardar($estado,$comentarios,$id_pedido,$mail_usuario);
	}	
}

/****************************************************************************************************************************/
function guardar($estado,$comentarios,$id_pedido,$mail_usuario)
{
	$path = "adodb/adodb.inc.php";
	include "var.php";
	include "conexion.php";
	
	$result=update_bd_format(array("estado","comentarios"), "ic_pedidos", array($estado,$comentarios), "WHERE id_pedido='".$id_pedido."'", $db);
	
	if ($result != false) {
		$mensaje = "1";
		
		if($mail_usuario!=""){
			$MailTo=$mail_usuario;
			$fecha=date("m.d.Y");
			$hora=date("H:i:s");
			$des_estado = obtenerDescripcion("ESTADO_PEDIDO", $estado, $db);
			
			//variables para envio de notificacion de email
			$asunto = obtenerDescripcion("EMAIL_ASUNTO", "", $db);
			$contenido1 = obtenerDescripcion("EMAIL_CONTENIDO1", "", $db);
			$contenido2 = obtenerDescripcion("EMAIL_CONTENIDO2", "", $db);
			
			$contenido = "";
			$contenido .= $contenido1->fields[0];
			$contenido .= "\n\n"._MSG_PEDIDO_ESTADO_ADMIN." \n "._MSG_PEDIDO_EST_ADMIN.": ".$des_estado->fields[0]." \n "._MSG_PEDIDO_COMEN_ADMIN.": \n ".$comentarios."" . "\n\n";
			$contenido .= $contenido2->fields[0];
			
			mail($MailTo, $asunto->fields[0], $contenido, "");
		}
	}else{
		$mensaje  = "0";
	}
	
	//funcion para registrar la modificacion hecha por el usuario
	registrar_cambios($db, date('Y-m-d'), $_SESSION['sess_nombre'], '200', '0', "PEDIDO-".$id_pedido, 'ACTUALIZAR');
	
	echo"<META HTTP-EQUIV='Refresh' CONTENT='0;URL=admin.php?mod=200&mensaje=".$mensaje."'>";
	die();	
}
/****************************************************************************************************************************/

if(!isset($_GET['id_pedido'])){
?>

<table border="0" align="center" cellpadding="5" cellspacing="0">
<form action="" name="filtro" id="filtro" method="post">
  <tr>
    <td colspan="7"><div align="center" class="titulo">Orders <br />
    </div>
      <div align="center"><a href="admin.php?mod=201">Settings Shopping Cart</a></div></td>
  </tr>
  <tr>
    <td colspan="7">&nbsp;</td>
  </tr>
  <tr>
    <td width="100">&nbsp;</td>
    <td width="1">&nbsp;</td>
    <td width="1">&nbsp;</td>
    <td width="100">&nbsp;</td>
    <td width="100">&nbsp;</td>
    <td width="1">&nbsp;</td>
    <td width="1">&nbsp;</td>
  </tr>
  <tr>
    <td>Date start</td>
    <td width="1"><input name="fecha_pedido" type="text" id="fecha_pedido" size="10" value="<?=$fecha_pedido?>" style="vertical-align:middle" /></td>
    <td width="1"><a href="javascript:;" onclick="showCalendar('',document.getElementById('fecha_pedido'),document.getElementById('fecha_pedido'),'','holder6',-10,5,1)"> <img src="admin/calendario/icono_calendario.jpg" style="vertical-align:middle;" width="18" height="17" border="0" align="bottom" /></a></td>
    <td>&nbsp;</td>
    <td>Date End</td>
    <td><input name="fecha_pedido_end" type="text" id="fecha_pedido_end" size="10" value="<?=$fecha_pedido_end?>" style="vertical-align:middle" /></td>
    <td width="1"><a href="javascript:;" onclick="showCalendar('',document.getElementById('fecha_pedido_end'),document.getElementById('fecha_pedido_end'),'','holder6',-10,5,1)"> <img src="admin/calendario/icono_calendario.jpg" style="vertical-align:middle;" width="18" height="17" border="0" align="bottom" /></a></td>
  </tr>
  <tr>
    <td><span id="result_box"><span title="estado del pedido">Status</span></span></td>
    <td><select name="estados" id="estados">
      <?php	
	cargar_lista("ic_carrito_compras","campo,valor","","1",$estados," WHERE opcion='ESTADO_PEDIDO' AND l_lang='".$_SESSION['idioma']."' ",$db);
?>
    </select></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3"><input type="submit" name="fitrar2" value="Create Report" onclick="javascript:document.getElementById('filtro').action='admin/excel_pedidos.php';"/></td>
    <td align="right">&nbsp;</td>
    <td align="right">&nbsp;</td>
    <td colspan="2" align="right"><input type="submit" name="fitrar" value="Search"/></td>
    </tr>
  </form>
</table>


<br />

<table width="100%" border="1" cellspacing="0" cellpadding="5" bordercolor="#CCCCCC" style="border-collapse:collapse;">
  <tr bgcolor="#E6E6E6" align="center">
    <td>Order #</td>
    <td>Customer</td>
    <td>Date</td>
	<td>Status</td>
    <td>Total</td>
  </tr>
<?php

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
	
	if($departamento!=""){
		$departamento = " AND pais LIKE '%- ".$departamento."'";
	}


	$sql="SELECT id_pedido,id_usuario,id_articulo,nombre,email,fecha_pedido,pais,ciudad,cp,direccion,
	             telefono,estado,metodo_pago,nombre_articulo,cantidad,precio_articulo,impuesto,envio,
				 valor_envio,comentarios
	      FROM ic_pedidos 
		  WHERE 1=1 ".$estados.$fecha_pedido.$reseller." group by id_pedido,id_usuario ORDER BY id_pedido DESC";
	$result=$db->Execute($sql);
	
	$moneda = obtenerDescripcion("TIPO_MONEDA_SELECCIONADA", "", $db);
	$estado_pedido = obtenerDescripcionWhere("ESTADO_PEDIDO", " AND valor_1='1' ", $db);
	
	while(!$result->EOF){
		list($id_pedido,$id_usuario,$id_articulo,$nombre,$email,$fecha_pedido,$pais,$ciudad,$cp,$direccion,
	         $telefono,$estado,$metodo_pago,$nombre_articulo,$cantidad,$precio_articulo,$impuesto,$envio,
			 $valor_envio,$comentarios)=select_format($result->fields);
		
		$color="";
		
		/*if($estado==$estado_pedido->fields[1]){
			$color="#DBECD9";
		}
		*/
		if($estado=="CO"){
			$color="#DBECD9";
		}
		if($estado=="CA"){
			$color="#FBCAD3";
		}
		if($estado=="RE"){
			$color="#FEFDDA";
		}
		
		$estado = obtenerDescripcion("ESTADO_PEDIDO", $estado, $db);
		
		$sql="SELECT SUM( (cantidad * precio_articulo) ) FROM ic_pedidos WHERE id_pedido =".$id_pedido." GROUP BY id_pedido";
		$result_precio_total=$db->Execute($sql);
		list($precio_total)=$result_precio_total->fields;
				
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
    <td align="center"><a href="admin.php?mod=200&id_pedido=<?=$id_pedido?>">Order - # <?=$id_pedido?></a></td>
    <td><?=$nombre?></td>
    <td align="center"><?=$fecha_pedido?></td>
	<td bgcolor="<?=$color?>"><?=$estado->fields[0]?></td>
    <td align="right"><?=$moneda->fields[0]." ".formateo_numero( ($precio_total+$valor_envio) )?></td>
  </tr>
<?php

		$result->MoveNext();
	}

?>
</table>

<?php
}else{

$sql="SELECT id_pedido,id_usuario,id_articulo,nombre,email,fecha_pedido,pais,ciudad,cp,direccion,
	         telefono,estado,metodo_pago,nombre_articulo,cantidad,precio_articulo,impuesto,envio,
			 valor_envio,comentarios
      FROM ic_pedidos WHERE id_pedido =".$_GET['id_pedido']."";
$result=$db->Execute($sql);

list($id_pedido,$id_usuario,$id_articulo,$nombre,$email,$fecha_pedido,$pais,$ciudad,$cp,$direccion,
	 $telefono,$estado,$metodo_pago,$nombre_articulo,$cantidad,$precio_articulo,$impuesto,$envio,
	 $valor_envio,$comentarios)=select_format($result->fields);


$moneda = obtenerDescripcion("TIPO_MONEDA_SELECCIONADA", "", $db);
$detalle_envio = obtenerDescripcion("MEDIO_ENVIO", $envio, $db);
$pais = explode("-",$pais);
?>
<table border="0" align="center" cellpadding="5" cellspacing="0">
  <tr>
    <td colspan="5"><div align="center" class="titulo">Order Information </div></td>
  </tr>
</table>

<?php
if($mensaje==1){ 
	$mensaje = "<center><span id='confirmacion'>Information saved</span></center>";
}elseif(($mensaje==0)&&($mensaje!="")){
	$mensaje  = "<center><span id='error'>An error has occurred and is not saved the information.</span></center>"; 
}
else {
	$mensaje  = "";
}
echo $mensaje;
?>

<div class="titulo" align="right"><a href="admin.php?mod=200"><?=_BACK?></a></div>
<table width="700" border="0" align="center" cellpadding="2" cellspacing="0" bgcolor="#f4f4f4">
<form action="" name="guardar" id="guardar" method="post">
  <tr>
    <td>
	<table width="100%" border="0" cellspacing="4" cellpadding="2">
  <tr>
    <td bgcolor="#FFFFFF"><b>Number</b></td>
    <td bgcolor="#FFFFFF"><?=$id_pedido?></td>
    <td bgcolor="#FDDEC8"><b>Status</b></td>
    <td bgcolor="#FDDEC8">
	<select name="estado" id="estado">
<?php	
	cargar_lista("ic_carrito_compras","campo,valor","","",$estado," WHERE opcion='ESTADO_PEDIDO' AND l_lang='".$_SESSION['idioma']."' ",$db);
?>
	</select>    </td>
  </tr>
  <tr>
    <td bgcolor="#FFFFFF"><b>Date</b></td>
    <td bgcolor="#FFFFFF"><?=$fecha_pedido?></td>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td bgcolor="#FFFFFF"><b>Name</b></td>
    <td bgcolor="#FFFFFF"><?=$nombre?></td>
    <td bgcolor="#FFFFFF"><span id="result_box2" lang="en" xml:lang="en">Method of payment</span></td>
    <td bgcolor="#FFFFFF"><?=$metodo_pago?></td>
    </tr>
  <tr>
    <td bgcolor="#FFFFFF"><b>Phone</b></td>
    <td bcolor="#FFFFFF"><?=$telefono?></td>
    <td bgcolor="#FFFFFF"><b>E-mail</b></td>
    <td bgcolor="#FFFFFF"><?=$email?></td>
    </tr>
  <tr>
    <td bgcolor="#FFFFFF"><b>Address</b></td>
    <td colspan="3" bgcolor="#FFFFFF"><?=$direccion?></td>
  </tr>
  <tr>
    <td bgcolor="#FFFFFF"><b>City</b></td>
    <td colspan="3" bgcolor="#FFFFFF"><?=$ciudad?></td>
    </tr>
	<tr>
    <td bgcolor="#FFFFFF"><b>State</b></td>
    <td colspan="3" bgcolor="#FFFFFF"><?=$pais[1]?></td>
    </tr>
     <tr>
    <td bgcolor="#FFFFFF"><b>Zip</b></td>
    <td colspan="3" bgcolor="#FFFFFF"><?=$cp?></td>
    </tr>
  <tr>
    <td bgcolor="#FFFFFF"><b>Country</b></td>
    <td colspan="3" bgcolor="#FFFFFF"><?=$pais[0]?></td>
    </tr>
  
  
</table>	</td>
  </tr>
  <tr>
    <td><table width="100%" border="0" cellspacing="4" cellpadding="2">
      <tr>
        <td bgcolor="#CCCCCC"><div align="center">
          <b>
          Item
          </b></div></td>
        <td bgcolor="#CCCCCC"><div align="center">
          <b>
          Cantity
          </b></div></td>
        <td bgcolor="#CCCCCC"><div align="center">
          <b>
          Value
          </b></div></td>
        <td bgcolor="#CCCCCC"><div align="center">
          <b>
          Total
          </b></div></td>
      </tr>
<?php
$total_factura = 0;

while(!$result->EOF){
	list($id_pedido,$id_usuario,$id_articulo,$nombre,$email,$fecha_pedido,$pais,$ciudad,$cp,$direccion,
	 $telefono,$estado,$metodo_pago,$nombre_articulo,$cantidad,$precio_articulo,$impuesto,$envio,$valor_envio,$comentarios)=$result->fields;
	
	$total_arti = $cantidad*$precio_articulo;
?>
      <tr>
        <td bgcolor="#FFFFFF"><?=$nombre_articulo?></td>
        <td bgcolor="#FFFFFF"><div align="right">
          <?=$cantidad?>
        </div></td>
        <td bgcolor="#FFFFFF"><div align="right">
          <?=$moneda->fields[0]." ".formateo_numero($precio_articulo)?>
        </div></td>
        <td bgcolor="#FFFFFF"><div align="right">
          <?=$moneda->fields[0]." ".formateo_numero($total_arti)?>
        </div></td>
      </tr>
<?php
	
	$total_factura = $total_factura + $total_arti;
	
	$result->MoveNext();
}
?>
      <tr>
        <td bgcolor="#FFFFFF">&nbsp;</td>
        <td bgcolor="#FFFFFF">&nbsp;</td>
        <td bgcolor="#FDDEC8"><div align="right"><b>Sub-Total</b></div></td>
        <td bgcolor="#FDDEC8"><div align="right">
          <b><?=$moneda->fields[0]." ".formateo_numero( $total_factura )?></b>
        </div></td>
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
			$result_pedido_cat=$db->Execute($sql);
			
			while(!$result_pedido_cat->EOF){
				list($id_articulo,$precio_articulo,$cantidad)=select_format($result_pedido_cat->fields);
				
				$total_cupon = $total_cupon + (($precio_articulo*$cantidad) * $valor) / 100;
				
				$result_pedido_cat->MoveNext();
			}
		}else{
			$total_cupon = (($total_factura) * $valor) / 100;
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
        <td bgcolor="#FFA655"><div align="right"><span lang="ES-TRAD" xml:lang="ES-TRAD"><strong>Discounts</strong></span></div></td>
        <td bgcolor="#FFE3CA"><div align="right"> <b>
          <?=$moneda->fields[0]." ".formateo_numero( $total_cupon )?>
        </b> </div></td>
      </tr>
      <tr>
        <td bgcolor="#FFFFFF">&nbsp;</td>
        <td bgcolor="#FFFFFF">&nbsp;</td>
        <td bgcolor="#FDDEC8"><div align="right"><b>Tax</b></div></td>
        <td bgcolor="#FDDEC8"><div align="right">
          <b><?=$moneda->fields[0]." ".formateo_numero( ( ($total_factura * $impuesto ) / 100 ) )?></b>
        </div></td>
      </tr>
      <tr>
        <td bgcolor="#FFFFFF">&nbsp;</td>
        <td bgcolor="#FFFFFF">&nbsp;</td>
        <td bgcolor="#FDDEC8"><div align="right">Shipping Method 
          <?="</b><br />".$envio?></div></td>
        <td bgcolor="#FDDEC8"><div align="right">
          <b><?=$moneda->fields[0]." ".formateo_numero( $valor_envio )?></b>
        </div></td>
      </tr>
      <tr>
        <td bgcolor="#FFFFFF">&nbsp;</td>
        <td bgcolor="#FFFFFF">&nbsp;</td>
        <td bgcolor="#FDDEC8"><div align="right"><b>Total</b></div></td>
        <td bgcolor="#FDDEC8"><div align="right">
          <b><?=$moneda->fields[0]." ".formateo_numero( ( $total_factura + ( ( $total_factura * $impuesto ) / 100 ) + $valor_envio ) )?></b>
        </div></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td><table width="100%" border="0" cellspacing="5" cellpadding="5">
      <tr>
        <td bgcolor="#CCCCCC"><b>Comments</b></td>
      </tr>
      <tr>
        <td bgcolor="#FFFFFF"><textarea name="comentarios" id="comentarios" cols="85" rows="3"><?=reemplazar_escape($comentarios)?></textarea></td>
      </tr>

    </table></td>
  </tr>
  <tr>
    <td><div align="right">
      <input type="hidden" name="id_pedido" id="id_pedido" value="<?=$id_pedido?>" />
      <input type="hidden" name="mail_usuario" id="mail_usuario" value="<?=$email?>" />
      <input type="hidden" name="funcion" id="funcion" value="actualizar" />
      <input type="submit" name="button" id="button" value="Save" />
    </div></td>
  </tr>
  </form>
</table>
<?php
}
?>