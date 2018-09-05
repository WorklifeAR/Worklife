<?php

session_permiso("201");


$variables_metodo = variables_metodo("correo_admin,impuesto,funcion,campo_fp,valor_fp,valor1_fp,valor_n_fp,valor1_n_fp,tipo,moneda,reservas,campo_v_me,campo_me,valor_me,valor1_me,campo_n_me,valor_n_me,valor1_n_me,campo_ep,valor_ep,valor1_ep,valor_n_ep,valor1_n_ep,asunto,contenido1,contenido2,peso_consolidado,envio_gratis_valor,envio_gratis_tienda,descuento_tienda");

$correo_admin= 			$variables_metodo[0];
$impuesto= 				$variables_metodo[1];
$funcion= 				$variables_metodo[2];
$campo_fp= 				$variables_metodo[3];
$valor_fp= 				$variables_metodo[4];
$valor1_fp= 			$variables_metodo[5];
$valor_n_fp= 			$variables_metodo[6];
$valor1_n_fp= 			$variables_metodo[7];
$tipo= 					$variables_metodo[8];	
$moneda= 				$variables_metodo[9];
$reservas= 				$variables_metodo[10];
$campo_v_me= 			$variables_metodo[11];
$campo_me= 				$variables_metodo[12];
$valor_me= 				$variables_metodo[13];
$valor1_me= 			$variables_metodo[14];
$campo_n_me= 			$variables_metodo[15];
$valor_n_me= 			$variables_metodo[16];
$valor1_n_me= 			$variables_metodo[17];
$campo_ep= 				$variables_metodo[18];
$valor_ep= 				$variables_metodo[19];
$valor1_ep= 			$variables_metodo[20];
$valor_n_ep= 			$variables_metodo[21];
$valor1_n_ep= 			$variables_metodo[22];
$asunto= 				$variables_metodo[23];
$contenido1= 			$variables_metodo[24];
$contenido2= 			$variables_metodo[25];
$peso_consolidado= 		$variables_metodo[26];
$envio_gratis_valor= 	$variables_metodo[27];
$envio_gratis_tienda= 	$variables_metodo[28];
$descuento_tienda= 		$variables_metodo[29];

if ($funcion == "actualizar"){
	/*Funcion para guardar los datos del formulario*/	
	guardar($correo_admin,$impuesto,$campo_fp,$valor_fp,$valor1_fp,$valor_n_fp,$valor1_n_fp,$moneda,$reservas,
	        $campo_v_me,$campo_me,$valor_me,$valor1_me,$campo_n_me,$valor_n_me,$valor1_n_me,$campo_ep,$valor_ep,
			$valor1_ep,$valor_n_ep,$valor1_n_ep,$asunto,$contenido1,$contenido2,$peso_consolidado,$envio_gratis_valor,
			$envio_gratis_tienda,$descuento_tienda);
}elseif($funcion == "borrar"){
	
	$campo="";
	
	if($campo_fp!=""){
		$campo=$campo_fp;
	}elseif($campo_ep!=""){
		$campo=$campo_ep;
	}elseif($campo_me!=""){
		$campo=$campo_me;
	}
	
	borrar($tipo, $campo);
}

/****************************************************************************************************************************/

function borrar($tipo, $id)
{
	$path = "adodb/adodb.inc.php";
	include "var.php";
	include "conexion.php";
	
	//funcion para registrar la modificacion hecha por el usuario
	registrar_cambios($db, date('Y-m-d'), $_SESSION['sess_nombre'], '201', $id, $tipo."-".$id, 'ELIMINAR');
	
	$sql = "DELETE FROM ic_carrito_compras WHERE opcion='".$tipo."' AND campo='".$id."' ";
	$result=$db->Execute($sql);

	if ($result != false) {
		$mensaje = "1";
	}else{
		$mensaje  = "0";
	}
	
	echo"<META HTTP-EQUIV='Refresh' CONTENT='0;URL=admin.php?mod=201&mensaje=".$mensaje."'>";
	die();	
}

/****************************************************************************************************************************/

function guardar($correo_admin,$impuesto,$campo_fp,$valor_fp,$valor1_fp,$valor_n_fp,$valor1_n_fp,$moneda,$reservas,$campo_v_me,$campo_me,
                 $valor_me,$valor1_me,$campo_n_me,$valor_n_me,$valor1_n_me,$campo_ep,$valor_ep,$valor1_ep,$valor_n_ep,$valor1_n_ep,$asunto,
				 $contenido1,$contenido2,$peso_consolidado,$envio_gratis_valor,$envio_gratis_tienda,$descuento_tienda)
{
	$path = "adodb/adodb.inc.php";
	include "var.php";
	include "conexion.php";
	
	$result=update_bd_format(array("valor"), "ic_carrito_compras", array($correo_admin), "WHERE opcion='CORREO_ADMIN'", $db);
	$result=update_bd_format(array("valor"), "ic_carrito_compras", array($impuesto), "WHERE opcion='IMPUESTO'", $db);
	$result=update_bd_format(array("valor"), "ic_carrito_compras", array($moneda), "WHERE opcion='TIPO_MONEDA_SELECCIONADA'", $db);
	$result=update_bd_format(array("valor"), "ic_carrito_compras", array($reservas), "WHERE opcion='CORREO_RESERVA'", $db);
	
	$result=update_bd_format(array("valor"), "ic_carrito_compras", array($asunto), "WHERE opcion='EMAIL_ASUNTO'", $db);
	$result=update_bd_format(array("valor"), "ic_carrito_compras", array($contenido1), "WHERE opcion='EMAIL_CONTENIDO1'", $db);
	$result=update_bd_format(array("valor"), "ic_carrito_compras", array($contenido2), "WHERE opcion='EMAIL_CONTENIDO2'", $db);
	$result=update_bd_format(array("valor"), "ic_carrito_compras", array($peso_consolidado), "WHERE opcion='ITEM_MIN_ENVIO'", $db);
	$result=update_bd_format(array("valor"), "ic_carrito_compras", array($envio_gratis_valor), "WHERE opcion='FREE_SHIPPING'", $db);
	$result=update_bd_format(array("valor"), "ic_carrito_compras", array($envio_gratis_tienda), "WHERE opcion='FREE_SHIPPING_STOREWIDE'", $db);
	$result=update_bd_format(array("valor"), "ic_carrito_compras", array($descuento_tienda), "WHERE opcion='DESCUENTO_TIENDA'", $db);
	
	//------------------------------------------------------------------------------------------------------------------------------------
	//Insercion de las formas de pago
	
	if($valor_n_fp!="" && $valor1_n_fp!=""){
		$campo = "FP";		
		$letras = explode(" ",$valor_n_fp);
		for($k=0; $k<count($letras); $k++){
			$campo .=  strtoupper( substr($letras[$k], 0, 1) );
		}
				
		$result=insert_bd_format("opcion,campo,l_lang,valor,valor_1", "ic_carrito_compras", array('FORMA_PAGO',$campo,$_SESSION['idioma'],$valor_n_fp,$valor1_n_fp), $db);
	}
	
	for($i=0; $i<count($campo_fp);$i++){
		$result=update_bd_format(array("valor","valor_1","campo"), "ic_carrito_compras", array($valor_fp[$i],$valor1_fp[$i],$campo_fp[$i]), "WHERE opcion='FORMA_PAGO' AND campo='".$campo_fp[$i]."'", $db);
	}

	//------------------------------------------------------------------------------------------------------------------------------------

	//------------------------------------------------------------------------------------------------------------------------------------
	//Insercion de las formas de envio
	
	if($campo_n_me!="" && $valor_n_me!="" && $valor1_n_me!=""){		
		$result=insert_bd_format("opcion,campo,l_lang,valor,valor_1", "ic_carrito_compras", array('MEDIO_ENVIO',$campo_n_me,$_SESSION['idioma'],$valor_n_me,$valor1_n_me), $db);
	}
	
	for($i=0; $i<count($campo_v_me);$i++){
		$result=update_bd_format(array("valor","valor_1","campo"), "ic_carrito_compras", array($valor_me[$i],$valor1_me[$i],$campo_me[$i]), "WHERE opcion='MEDIO_ENVIO' AND campo='".$campo_v_me[$i]."'", $db);
	}

	//------------------------------------------------------------------------------------------------------------------------------------

	//------------------------------------------------------------------------------------------------------------------------------------
	//Insercion de los estados de poliza
	
	if($valor_n_ep!=""){
		$campo = "EP";		
		$letras = explode(" ",$valor_n_ep);
		for($k=0; $k<count($letras); $k++){
			$campo .=  strtoupper( substr($letras[$k], 0, 1) );
		}

		if($valor1_n_ep!=""){
			$sql = "UPDATE ic_carrito_compras SET valor_1='0' WHERE opcion='ESTADO_PEDIDO'; ";
			$result=$db->Execute($sql);
			$valor1_ep="";
		}else{
			$valor1_n_ep="0";
		}
		
		$result=insert_bd_format("opcion,campo,l_lang,valor,valor_1", "ic_carrito_compras", array('ESTADO_PEDIDO',$campo,$_SESSION['idioma'],$valor_n_ep,$valor1_n_ep), $db);
	}
	
	for($i=0; $i<count($campo_ep);$i++){
	
		$valor1="0";
		
		if($valor1_ep!="" && $valor1_ep==$campo_ep[$i]){
			$sql = "UPDATE ic_carrito_compras SET valor_1='0' WHERE opcion='ESTADO_PEDIDO'; ";
			$result=$db->Execute($sql);
			$valor1="1";
		}
		
		$result=update_bd_format(array("valor","valor_1","campo"), "ic_carrito_compras", array($valor_ep[$i],$valor1,$campo_ep[$i]), "WHERE opcion='ESTADO_PEDIDO' AND campo='".$campo_ep[$i]."'", $db);
	}

	//------------------------------------------------------------------------------------------------------------------------------------
	
	if ($result != false) {
		$mensaje = "1";
	}else{
		$mensaje  = "0";
	}
	
	//funcion para registrar la modificacion hecha por el usuario
	registrar_cambios($db, date('Y-m-d'), $_SESSION['sess_nombre'], '201', '0', "INFO SHOPPING CART", 'ACTUALIZAR');
	
	echo"<META HTTP-EQUIV='Refresh' CONTENT='0;URL=admin.php?mod=201&mensaje=".$mensaje."'>";
	die();	
}
/****************************************************************************************************************************/


$mail = obtenerDescripcion("CORREO_ADMIN", "", $db);
$impuesto = obtenerDescripcion("IMPUESTO", "", $db);
$forma_pago = obtenerDescripcion("FORMA_PAGO", "", $db);
$moneda = obtenerDescripcion("TIPO_MONEDA_SELECCIONADA", "", $db);
$reservas = obtenerDescripcion("CORREO_RESERVA", "", $db);
$metodos_envio = obtenerDescripcion("MEDIO_ENVIO", "", $db);
$estados_pedidos = obtenerDescripcion("ESTADO_PEDIDO", "", $db);
$asunto = obtenerDescripcion("EMAIL_ASUNTO", "", $db);
$contenido1 = obtenerDescripcion("EMAIL_CONTENIDO1", "", $db);
$contenido2 = obtenerDescripcion("EMAIL_CONTENIDO2", "", $db);
$peso_consolidado = obtenerDescripcion("ITEM_MIN_ENVIO", "", $db);
$envio_gratis = obtenerDescripcion("FREE_SHIPPING", "", $db);
$envio_gratis_tienda = obtenerDescripcion("FREE_SHIPPING_STOREWIDE", "", $db);
$descuento_tienda = obtenerDescripcion("DESCUENTO_TIENDA", "", $db);

?>

<div align="center" class="titulo">Configuracion Cesta de Compras</div>
<br />

<?php
if($mensaje==1){ 
	$mensaje = "<center><span id='confirmacion'>Informacion de configuracion guardada</span></center><br />";
}elseif(($mensaje==0)&&($mensaje!="")){
	$mensaje  = "<center><span id='error'>ha ocurrido un error y no se ha guardado la informacion</span></center><br />"; 
}else {
	$mensaje  = "";
}
echo $mensaje;
?>

<table width="100%" border="0" cellspacing="0" cellpadding="3">
<form action="" method="post" name="guardar" id="guardar">
  <tr>
    <td valign="top"><div align="right"><b>Email Notificacion</b></div></td>
    <td valign="top"><input type="text" name="correo_admin" id="correo_admin" size="40" value="<?=reemplazar_escape($mail->fields[0])?>"/></td>
  </tr>
  <tr>
    <td valign="top"><div align="right"><b>Impuesto Defecto</b> %<br />
        <font size="1">(0 cero inactivo)</font></div></td>
    <td valign="top"><input type="text" name="impuesto" id="impuesto" size="10" value="<?=reemplazar_escape($impuesto->fields[0])?>"/></td>
  </tr>
  <tr>
    <td valign="top"><div align="right"><b>Tipo Moneda</b><br /><font size="1"><i>En formato HTML preferiblemente</i></font></div></td>
    <td valign="top"><input type="text" name="moneda" id="moneda" size="10" value="<?=reemplazar_escape($moneda->fields[0])?>"/>      </td>
  </tr>
  <tr>
    <td valign="top"><div align="right"><strong>Items minimos requeridos para realizar el envio</strong></div></td>
    <td valign="top"><input type="text" name="peso_consolidado" id="peso_consolidado" size="10" value="<?=reemplazar_escape($peso_consolidado->fields[0])?>"/></td>
  </tr>
  <tr>
    <td valign="top"><div align="right"><b>E-mail Reserva</b></div></td>
    <td valign="top"><input type="text" name="reservas" id="reservas" size="40" value="<?=reemplazar_escape($reservas->fields[0])?>"/></td>
  </tr>
  <tr>
    <td valign="top"><div align="right"><b>Descuento en la Tienda (%)</b></div></td>
    <td valign="top"><input type="text" name="descuento_tienda" id="descuento_tienda" size="10" value="<?=reemplazar_escape($descuento_tienda->fields[0])?>"/></td>
  </tr>
  <tr>
    <td valign="top"><div align="right"><b>Envio Gratuito</b><br /><font size="1"><i>Aplica para toda la tienda</i></font></div></td>
    <td valign="top">
    <select name="envio_gratis_tienda" id="envio_gratis_tienda">
		<?php cargar_lista_estatica("S,N","Si,No",1,$envio_gratis_tienda->fields[0]); ?>
    </select>
    </td>
  </tr>
  <tr>
    <td valign="top"><div align="right"><b>Envio gratis desde (valor en $)</b></div></td>
    <td valign="top"><input type="text" name="envio_gratis_valor" id="envio_gratis_valor" size="10" value="<?=reemplazar_escape($envio_gratis->fields[0])?>"/></td>
  </tr>
  <tr>
    <td valign="top"><div align="right"><b>Contenido Email  Confrimacion Compra</b></div></td>
    <td valign="top"><table width="100%" border="0" cellspacing="2" cellpadding="2">
      <tr>
        <td bgcolor="#CCCCCC">Asunto</td>
        <td bgcolor="#CCCCCC"><input type="text" name="asunto" id="asunto" size="40" value="<?=reemplazar_escape($asunto->fields[0])?>"/></td>
      </tr>
      <tr>
        <td bgcolor="#CCCCCC">Contenido Inicial</td>
        <td bgcolor="#CCCCCC"><textarea cols="40" rows="2" name="contenido1" id="contenido_1"><?=reemplazar_escape($contenido1->fields[0])?></textarea></td>
      </tr>
      <tr>
        <td bgcolor="#CCCCCC">Contenido Inicial</td>
        <td bgcolor="#CCCCCC"><textarea cols="40" rows="2" name="contenido2" id="contenido_2"><?=reemplazar_escape($contenido2->fields[0])?></textarea></td>
      </tr>
    </table>      </td>
  </tr>
  <tr>
        <td colspan="2" valign="top"><hr color="#666666" size="1" width="100%" align="center" /></td>
    </tr>
  <tr>
    <td valign="top"><div align="right"><b>Formas de Pago</b></div></td>
    <td valign="top"><table  border="0" cellpadding="2" cellspacing="1">
      <tr>
        <td bgcolor="#CCCCCC"><input name="valor_n_fp" type="text" id="valor_n_fp" value="" size="20"/></td>
        <td bgcolor="#CCCCCC"><input name="valor1_n_fp" type="text" id="valor1_n_fp" value="" size="20" /></td>
        <td colspan="2" bgcolor="#CCCCCC">&nbsp;</td>
        </tr>
      <tr>
        <td align="center"><div align="center"><strong>Nombre</strong></div></td>
        <td align="center"><strong>URL</strong></td>
        <td colspan="2" align="center">&nbsp;</td>
        </tr>
      <?php
$cont = 0;

while (!$forma_pago->EOF)
{
	list($valor,$campo,$valor_1)=$forma_pago->fields;
?>
      <tr>
        <td valign="top"><input type="hidden" name="campo_fp[<?=$cont?>]" value="<?=$campo?>" />
            <textarea name="valor_fp[<?=$cont?>]"><?=reemplazar_escape($valor)?></textarea></td>
        <td valign="top"><div align="center">
            <textarea name="valor1_fp[<?=$cont?>]"><?=reemplazar_escape($valor_1)?></textarea>
        </div></td>
        <td colspan="2" valign="top"><a href="admin.php?mod=201&amp;funcion=borrar&amp;tipo=FORMA_PAGO&amp;campo_fp=<?=$campo?>" title="Borrar"><img src="images/admin/eliminar.png" border="0" /></a></td>
        </tr>
      <?php
	$forma_pago->MoveNext();
	$cont++;
}
?>
    </table></td>
  </tr>
 <tr>
        <td colspan="2" valign="top"><hr color="#666666" size="1" width="100%" align="center" /></td>
    </tr>
  <tr>
    <td valign="top"><div align="right"><b>Fomas de Envio</b></div></td>
    <td valign="top"><table  border="0" cellpadding="2" cellspacing="1">
        <tr>
          <td bgcolor="#CCCCCC"><input name="campo_n_me" type="text" id="campo_n_me" value="" size="20"/></td>
          <td bgcolor="#CCCCCC"><input name="valor_n_me" type="text" id="valor_n_me" value="" size="10"/></td>
          <td bgcolor="#CCCCCC"><input name="valor1_n_me" type="text" id="valor1_n_me" value="" size="10" /></td>
          <td colspan="2" bgcolor="#CCCCCC">&nbsp;</td>
        </tr>
        <tr>
          <td align="center"><div align="center"><strong>Nombre</strong></div></td>
          <td align="center"><strong>Valor</strong></td>
          <td align="center"><strong>ID tipo </strong></td>
          <td colspan="2" align="center">&nbsp;</td>
        </tr>
        <?php
$cont = 0;

while (!$metodos_envio->EOF)
{
	list($valor,$campo,$valor_1)=$metodos_envio->fields;
?>
        <tr>
          <td><input type="hidden" name="campo_v_me[<?=$cont?>]" value="<?=reemplazar_escape($campo)?>" />
              <input type="text" name="campo_me[<?=$cont?>]" size="20" value="<?=reemplazar_escape($campo)?>" /></td>
          <td><div align="center">
              <input name="valor_me[<?=$cont?>]" type="text" value="<?=reemplazar_escape($valor)?>" size="10"/>
          </div></td>
          <td><div align="center">
              <input name="valor1_me[<?=$cont?>]" type="text" value="<?=reemplazar_escape($valor_1)?>" size="10"/>
          </div></td>
          <td colspan="2"><a href="admin.php?mod=201&amp;funcion=borrar&amp;tipo=MEDIO_ENVIO&amp;campo_me=<?=$campo?>" title="Borrar"><img src="images/admin/eliminar.png" border="0" /></a></td>
        </tr>
<?php
	$metodos_envio->MoveNext();
	$cont++;
}
?>
    </table></td>
  </tr>
 <tr>
        <td colspan="2" valign="top"><hr color="#666666" size="1" width="100%" align="center" /></td>
    </tr>
  <tr>
    <td valign="top"><div align="right"><b>Estados de Orden</b></div></td>
    <td valign="top"><table  border="0" cellpadding="2" cellspacing="1">
      <tr>
        <td bgcolor="#CCCCCC"><input name="valor_n_ep" type="text" id="valor_n_ep" value="" size="20"/></td>
        <td bgcolor="#CCCCCC" align="center"><input name="valor1_n_ep" type="radio" id="valor1_n_ep" value="1" /></td>
        <td colspan="2" bgcolor="#CCCCCC">&nbsp;</td>
      </tr>
      <tr>
        <td align="center"><div align="center"><strong>Nombre</strong></div></td>
        <td align="center"><strong>Defecto</strong></td>
        <td colspan="2" align="center">&nbsp;</td>
      </tr>
<?php
$cont = 0;

while (!$estados_pedidos->EOF)
{
	list($valor,$campo,$valor_1)=$estados_pedidos->fields;
?>
      <tr>
        <td><input type="hidden" name="campo_ep[<?=$cont?>]" value="<?=reemplazar_escape($campo)?>" />
            <input type="text" name="valor_ep[<?=$cont?>]" size="20" value="<?=reemplazar_escape($valor)?>" /></td>
        <td><div align="center">
            <input name="valor1_ep" type="radio" value="<?=$campo?>" <?=($valor_1=="1")?"checked='checked'":""?> />
        </div></td>
        <td colspan="2"><a href="admin.php?mod=201&amp;funcion=borrar&amp;tipo=ESTADO_PEDIDO&amp;campo_ep=<?=$campo?>" title="Borrar"><img src="images/admin/eliminar.png" border="0" /></a></td>
      </tr>
      <?php
	$estados_pedidos->MoveNext();
	$cont++;
}
?>
    </table></td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td valign="top">
        <input type="submit" name="button" id="button" value="Guardar Configuracion" />
        <input type="hidden" name="funcion" id="funcion" value="actualizar" />    </td>
  </tr>
</form>
</table>
