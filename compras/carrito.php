<?php

$path = "adodb/adodb.inc.php";
include ("admin/var.php");
include ("conexion.php");

/***********************************************************************************/

//Variables
$variables_ver = variables_metodo("step");
$step= 				$variables_ver[0];
	
$step_1 = "display:none";
$step_2 = "display:none";
$step_3 = "display:none";
$step_4 = "display:none";

$step_bg_1 = "#cccccc";
$step_bg_2 = "#cccccc";
$step_bg_3 = "#cccccc";
$step_bg_4 = "#cccccc";

if($step=="" || $step=="1"){
	$step_1 = "display:inline";
	$step_bg_1 = "#E12328";
}else{
	if($step=="2"){
		$step_2 = "display:inline";
		$step_bg_2 = "#E12328";
	}
	if($step=="3"){
		$step_3 = "display:inline";
		$step_bg_3 = "#E12328";
	}
	if($step=="4"){
		$step_4 = "display:inline";
		$step_bg_4 = "#E12328";
	}
}

/////////////////////////////////////////////////////////

$nombre_carrito = "";
		
if(isset($_SESSION['reseller'])){
	$nombre_carrito = $_SESSION['reseller'];
}

/////////////////////////////////////////////////////////

$envio = (isset($_SESSION['envio_seleccionado']))?$_SESSION['envio_seleccionado']:"";

/***********************************************************************************/

?>
  <h1 class="mas_titulo">Shopping Cart</h1>
    
<?php

if(isset($_SESSION['sess_nombre'])){
	echo "<p align='left'>".$_SESSION['sess_nombre']." "._BIENVENIDO."</p>";
}
?>
<script type="text/javascript" language="javascript" src="compras/ajax.js"></script>

<?php
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

if(isset($_SESSION['carrito'.$nombre_carrito])){

	$carrito = $_SESSION['carrito'.$nombre_carrito];
	$total_pedido=0;
	$moneda = obtenerDescripcion("TIPO_MONEDA_SELECCIONADA", "", $db);
	$impuesto = obtenerImpuesto($db);
	
	//$envioSeleccionado = (isset($_SESSION['envio_seleccionado_detalle']))?explode("|",$_SESSION['envio_seleccionado_detalle']):"";
	if(isset($_SESSION['envio_seleccionado_detalle'])){
		$envioSeleccionado = explode("|", $_SESSION['envio_seleccionado_detalle']);
	}else{
		$envioSeleccionado = array("","");
	}
	$cantidad_total_articulo=0;
?>
<br /><br />

<div id="paso1" style="<?=$step_1?>">
    <table width="100%" border="0" cellspacing="0" cellpadding="10">
      <tr>
        <td width="60%" bgcolor="#000"><h3 style="color:#FFF">SHOPPING BAG</h3></td>
        <td width="10%">&nbsp;</td>
        <td width="30%" align="center" bgcolor="#E6C349">
        	<a href="javascript:;" onClick="steps(2, 'right','<?=$_SESSION['c_base_location']?>');"><h3 style="color:#000">CHECKOUT</h3></a>
        </td>
      </tr>
    </table>
    <br />
    <div style="border-radius:8px; background:#FFF; padding:10px;">
<?php
	foreach($carrito as $i => $valor){

		//Consulta de los productos
		$sql="SELECT id_item,fecha_creacion,nombre_item,previa_item,full_item,categoria_item,img_previa,img_full,precio,convinado,cantidad_articulos,free_shipping
			FROM ic_catalogo WHERE id_item='".$i."' ";

		$result=$db->Execute($sql);
		
		//Result del listado de los productos visualizado en dos columnas
		while (!$result->EOF){

			list($id_item,$fecha_creacion,$nombre_item,$previa_item,$full_item,$categoria_item,$img_previa,$img_full,$precio,$combinado,$cantidad_articulos,$free_shipping)=select_format($result->fields);
			
			$precio = precioFinalCarrito($id_item, "", $db);
			
			//----------------------------------------
		
			//Calcula el valor del cupon si aplica para un producto solamente -- producto,total_pedido
			calcularValorCupon($i, $carrito[$i], $precio, "", $db);
			
			//----------------------------------------
			
			$total_arti = 0;
			$total_arti = $carrito[$i] * ($precio);

			$total_pedido = $total_pedido + $total_arti;
			
			if($free_shipping=="Y"){
				$free_shipping = "<br /><span class='free'>"._ENVIOGRATIS."</span>";
			}else{
				$free_shipping="";
			}
			
			$adicional = "";
			
			if($_SESSION['adicional'.$nombre_carrito][$i]!=""){
				$itemref = explode("-", $_SESSION['adicional'.$nombre_carrito][$i]);				
				$adicional = "Ref: ".$itemref[1] . " - $" . formateo_numero($itemref[2]) .'<input type="hidden" name="referencias_'.$i.'" id="referencias_'.$i.'" value="'.$_SESSION['adicional'.$nombre_carrito][$i].'" />';
			}
?>
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="1">
            <img src="<?=$img_previa?>" width="80" alt="<?=$nombre_item?>" style="margin-right:10px;"/>
            </td>
            <td width="50%" valign="top">
            <a href="<?=organizar_nombre_link("item", $nombre_item)?>"><?=$nombre_item?></a>
            <br />
            <?=$adicional?>            
            <table border="0" cellspacing="0" cellpadding="3">
              <tr>
                <td><?=_QTY?>:</td>
                <td><input type="text" name="carrito_cantidad_<?=$id_item?>" id="carrito_cantidad_<?=$id_item?>" value="<?=$carrito[$i]?>" size="5" style="text-align:center; background-color:#FFFFFF; color:#000;" onKeyPress="return acceptNum(event)"/> </td>
                <td>
                <a href="javascript:;" onclick="adicionarProductoCarrito('<?=$id_item?>');">
            		<img src="images/cesta_cambio.gif" width="25" />
            	</a>
                <input type="hidden" name="carrito_id_<?=$id_item?>" id="carrito_id_<?=$id_item?>" value="<?=$id_item?>"/>
				<input type="hidden" name="adicionar_<?=$id_item?>" id="adicionar_<?=$id_item?>" value="<?=$id_item?>"/>
            </td>
              </tr>
            </table>
            <?=$free_shipping?>
            </td>
            <td width="15%" align="center" valign="top">
            <a href="javascript:;" onclick="removerProductoCarrito('<?=$id_item?>');" style="color:#900">delete</a>
            </td>
            <td align="right" valign="top" width="80">
            <div style="width:80px; float:right; display:block; margin-right:10px;">
            <input type="hidden" name="precio_<?=$id_item?>" id="precio_<?=$id_item?>" value="<?=$moneda->fields[0]." ".formateo_numero($precio)?>"  readonly="readonly"/>
            </div>
            </td>
            <td align="right" valign="top" width="80">
            <div style="width:80px; float:right; display:block; margin-right:10px;">
            <input type="text" name="total_arti_<?=$id_item?>" id="total_arti_<?=$id_item?>" value="<?=$moneda->fields[0]." ".formateo_numero($total_arti)?>"   class="valor_pedido"  readonly="readonly"/>
            </div>
            </td>
          </tr>
          <tr>
            <td colspan="7" align="center"><hr size="1" width="90%" color="#CCCCCC" style="margin: 20px 0;"/></td>
          </tr>
        </table>
<?php
			$result->MoveNext();
			$cantidad_total_articulo = $cantidad_total_articulo + $carrito[$i];
		}
	}
?>
    </div>

  <div style="border-radius:8px; background:#FFF; padding:10px;">
    <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td align="right"><?=_SUBTOTAL_CARRITO?></td>
          <td align="center">
          <input type="text" name="total_compra" id="total_compra" value="<?=$moneda->fields[0]." ".formateo_numero($total_pedido)?>" class="valor_pedido" readonly="readonly"/>
          </td>
        </tr>
<?php
//******************************************************************************************
	
$_SESSION['total_carrito'] = $total_pedido;

//******************************************************************************************

$desc = "";
$disableddesc = "";

if(isset($_SESSION['ref_cupon'])){
	$desc = $_SESSION['ref_cupon'];
	$disableddesc = " disabled='disabled' ";
}

//----------------------------------------
	
//Calcula el valor del cupon si aplica para un producto solamente -- producto,total_pedido
calcularValorCupon("", "", "", $total_pedido, $db);

//----------------------------------------
?>
        <tr>
          <td>&nbsp;</td>
          <td colspan="3" align="right">
            
            
            <div class="descu1">
<?php
	if(!isset($_SESSION['ref_cupon'])){	
?>
				<a href="javascript:;" onclick="validarCupon(document.getElementById('referencia_descuento'));">Apply Coupon!</a>
<?php 
	}else{
?>
				<a href="javascript:;" onclick="eliminarCupon();">Delete Coupon</a>
<?php
	}
?>
            </div>
            <div class="descu2">
				<input type="text" name="referencia_descuento" id="referencia_descuento" value="<?=$desc?>" <?=$disableddesc?> style="width: 80px; height: 25px;"/>
            </div>
            <div class="descu3"><?=_TOTAL_DESC?></div>
            
          </td>
          <td>
          <input type="text" name="descuento" id="descuento" value="<?=((isset($_SESSION['total_cupon']))?$moneda->fields[0]." ".'-'.formateo_numero($_SESSION['total_cupon']):"")?>" class="valor_pedido_descuento" readonly="readonly"/>
          </td>
        </tr>
<?php
	if(isset($_SESSION['total_cupon'])){
		$total_pedido = $total_pedido - $_SESSION['total_cupon'];
	}
	$_SESSION['total_carrito'] = $total_pedido;
	
	//******************************************************************************************
	
	$total_impuesto = 0;
	
	//Impuesto
	if($impuesto->fields[0]!="" && $impuesto->fields[0]!="0"){
		
		$total_impuesto = ((($impuesto->fields[0]/100) + 1) * $total_pedido ) - $total_pedido;

		$_SESSION['impuesto_valor'] = $total_impuesto;
		$_SESSION['impuesto'] = $impuesto->fields[0];
	}

	//******************************************************************************************
	
	$total_pedido_impuesto = $total_pedido;
	$total_pedido_impuesto = sumarImpuesto($total_pedido, $total_impuesto);

	//******************************************************************************************
?>
        <tr>
          <td width="10%">&nbsp;</td>
          <td width="50%">&nbsp;</td>
          <td width="10%">&nbsp;</td>
          <td width="15%" align="right"><?=_TOTAL_CARRITO?></td>
          <td width="15%" align="center">
          <input type="text" name="total_compra" id="total_compra" value="<?=$moneda->fields[0]." ".formateo_numero($total_pedido_impuesto)?>" class="valor_pedido" readonly="readonly"/>
          <input name="total_pedido" type="hidden" id="total_pedido" value="<?=$_SESSION['total_carrito']?>" />
          </td>
        </tr>
      </table>
    </div>
    <p>&nbsp;</p>
    <table width="100%" border="0" cellspacing="0" cellpadding="10">
      <tr>        
        <td width="60%" bgcolor="#000"><a href="ver.php/mod/catalogo"><h3 style="color:#fff;">&laquo; Continue Shopping</h3></a></td>
        <td width="10%">&nbsp;</td>
        <td width="30%" align="center" bgcolor="#E6C349">
        	<a href="javascript:;" onClick="steps(2, 'right','<?=$_SESSION['c_base_location']?>');"><h3 style="color:#000;">CHECKOUT</h3></a>
        </td>
      </tr>
    </table>
</div>

<div id="paso2" style="<?=$step_2?> ">
<table width="100%" border="0" cellspacing="0" cellpadding="10">
  <tr>
    <td width="60%" bgcolor="#000"><a href="ver.php/mod/carrito/step/1"><h3 style="color:#fff;">&laquo; SHOPPING BAG</h3></a></td>
    <td width="10%">&nbsp;</td>
    <td width="30%" align="center" bgcolor="#E6C349">
    <a href="javascript:;" onClick="activarPersiana(true); steps(3, 'right','<?=$_SESSION['c_base_location']?>');"><h3 style="color:#000;">CONTINUE &raquo;</h3></a></td>
  </tr>
</table>
<p>&nbsp;</p>
    <div class="blockCart1">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td valign="top" >
    <?php
        if(!isset($_SESSION['sess_usu_id'])){
    ?>
        <table width="100%" border="0" cellspacing="5" cellpadding="5">
          <tr>
            <td><h3>Shipping Information</h3></td>
          </tr>
        </table>
        <br />
        <div style="border-radius:8px; background:#FFF; padding:20px;">
        <table border="0" style="margin:0 auto;" cellpadding="4" cellspacing="4">
        <form action="" method="post" name="login" id="login" >
          <tr>
            <td>
              <?=_USUARIO?>
            </td>
            </tr>
          <tr>
            <td align="right"><input type="text" name="us_login" id="us_login" style="width:300px;"/></td>
            </tr>
          <tr>
            <td>Password</td>
            </tr>
          <tr>
            <td align="right"><input type="password" name="us_pass" id="us_pass" style="width:300px;"/></td>
            </tr>
          <tr>
            <td align="right"><input type="submit" name="Submit" value="<?=_INICIAR_SESION?>" style="width:100%;" /></td>
          </tr>
		  <tr>
            <td>&nbsp;</td>
            </tr>
		  <tr>
            <td align="right">
			<input type="button" name="olvido" value="<?=_RECORDAR_PASS?>" style="width:100%; background:#000; cursor:pointer;" onclick="window.location.href='recordar-clave.html';"/>
			</td>
          </tr>
          <tr>
            <td align="left">
			  <input type="button" name="nuevo" value="<?=_NUEVO_USUARIO?>" style="width:100%; background:#000; cursor:pointer;" onclick="window.location.href='user.php?op_usu=nuevo_usuario';"/>
              <input type="hidden" name="funcion" value="login_usuario" />
              <input type="hidden" name="destino" value="true" />
			</td>
            </tr>
          </form>
        </table>
        </div>
        <br />
    <?php
        }
    ?>
        <table width="100%" border="0" cellspacing="0" cellpadding="10">
          <tr>
            <td><h3>
    <?php
        if(!isset($_SESSION['sess_usu_id'])){ echo "Check out as Guest"; }else{ echo "Shipping Information"; } 
    ?>
            </h3>
            </td>
          </tr>
        </table>
        <div style="border-radius:8px; background:#FFF; padding:20px;">
        <table width="100%" border="0" cellpadding="3" cellspacing="0">
          <tr>
            <td valign="top" nowrap="nowrap"><input name="error" type="hidden"  id="error" value="" /></td>
            </tr>
          <tr>
            <td align="left" valign="top" nowrap="nowrap"><div align="left">
              <?=_NOMBRE_COMPLETO?>
              (*):</div></td>
            </tr>
          <tr>
            <td align="left" valign="top" nowrap="nowrap"><input name="us_nombre" type="text"  id="us_nombre" style="width:100%; box-sizing:border-box;" title="<?=_NOMBRE_COMPLETO?>"
                         value="<?=isset($_SESSION['sess_nombre'])?$_SESSION['sess_nombre']:""?>" /></td>
            </tr>
          <tr>
            <td align="left" valign="top" nowrap="nowrap"><div align="left">
              <?=_REGUSER_LASTNAME?>
              (*):</div></td>
            </tr>
          <tr>
            <td align="left" valign="top" nowrap="nowrap"><input name="us_last_name" type="text"  id="us_last_name" style="width:100%; box-sizing:border-box;" title="Last Name"
                         value="<?=isset($_SESSION['sess_last_nombre'])?$_SESSION['sess_last_nombre']:""?>" /></td>
            </tr>
          <tr>
            <td align="left" valign="top" nowrap="nowrap"><?=_DIRECCION?>
              (*): </td>
            </tr>
          <tr>
            <td align="left" nowrap="nowrap"><i><font size="1">
              <input name="us_direccion" type="text"  id="us_direccion" title="<?=_DIRECCION?>"
                         value="<?=isset($_SESSION['sess_usu_direccion'])?$_SESSION['sess_usu_direccion']:""?>" style="width:100%; box-sizing:border-box;" />
            </font></i></td>
            </tr>
          <tr>
            <td align="left" nowrap="nowrap"><?=_REGUSER_APART?>
              :</td>
            </tr>
          <tr>
            <td align="left" nowrap="nowrap"><input name="us_appartment" type="text" id="us_appartment" title="<?=_REGUSER_APART?>"
                         value="<?=isset($_SESSION['sess_usu_appartment'])?$_SESSION['sess_usu_appartment']:""?>" style="width:100%; box-sizing:border-box;"/></td>
            </tr>
          <tr>
            <td align="left" nowrap="nowrap"><div align="left">
              <?=_MSG_CONTACTO_5?>
              (*): </div></td>
            </tr>
          <tr>
            <td align="left" nowrap="nowrap"><input name="us_ciudad" type="text" id="us_ciudad" title="<?=_MSG_CONTACTO_5?>"
                         value="<?=isset($_SESSION['sess_usu_ciudad'])?$_SESSION['sess_usu_ciudad']:""?>" style="width:100%; box-sizing:border-box;"/></td>
            </tr>
          <?php /*<tr>
            <td align="left" nowrap="nowrap"><div align="left">
              <?=_REGUSER_ZIP?> (*)
              : </div></td>
            </tr>
          <tr>
            <td align="left" nowrap="nowrap"><input name="us_postal" type="text" id="us_postal" title="<?=_REGUSER_ZIP?>" style="width:100%; box-sizing:border-box;" value="<?=isset($_SESSION['sess_usu_postal'])?$_SESSION['sess_usu_postal']:""?>" size="10"/></td>
            </tr>*/?>
          <tr>
            <td align="left" nowrap="nowrap"><div align="left">
              <?="Departamento"//_REGUSER_STAT?>
              (*)
              : </div></td>
            </tr>
          <tr>
            <td align="left" nowrap="nowrap">
			<input name="us_estado_us" type="text" id="us_estado_us" title="<?="Departamento"//_REGUSER_STAT?>" value="<?=isset($_SESSION['sess_usu_estado_us'])?$_SESSION['sess_usu_estado_us']:""?>" style="width:100%; box-sizing:border-box;"/>
						 
			</td>
            </tr>
          <tr>
            <td align="left" valign="top" nowrap="nowrap"><?=_PAIS?>
              (*):</td>
            </tr>
          <tr>
            <td align="left" valign="top" nowrap="nowrap">
			<input name="us_pais" type="text" id="us_pais" title="<?=_PAIS?>" value="Colombia" style="width:100%; box-sizing:border-box;" readonly />
			
			<?php /*<select name="us_pais" id="us_pais" style="width:100%; box-sizing:border-box;" title="<?=_PAIS?>">
              <?= listado_paises(isset($_SESSION['sess_usu_estado_us'])?$_SESSION['sess_usu_pais']:"","1") ?>
            </select>	*/ ?>		
			</td>
            </tr>
          <tr>
            <td align="left" valign="top" nowrap="nowrap"><div align="left">
              <?=_EMAIL?>
              (*): </div></td>
            </tr>
          <tr>
            <td align="left" valign="top" nowrap="nowrap"><input name="us_email" type="text" id="us_email" title="<?=_EMAIL?>"
                         value="<?=isset($_SESSION['sess_usu_email'])?$_SESSION['sess_usu_email']:""?>" style="width:100%; box-sizing:border-box;"/></td>
            </tr>
          <tr>
            <td align="left" valign="top" nowrap="nowrap"><div align="left">
              <?=_TELEFONO?>
              (*)	  : </div></td>
            </tr>
          <tr>
            <td align="left" valign="top" nowrap="nowrap"><input name="us_telefono" type="text" id="us_telefono"  title="<?=_TELEFONO?>"
                         value="<?=isset($_SESSION['sess_usu_telefono'])?$_SESSION['sess_usu_telefono']:""?>" style="width:100%; box-sizing:border-box;"/></td>
            </tr>
          <tr>
            <td align="left" valign="top" nowrap="nowrap"><div align="left"> Comentarios de envío
              : </div></td>
            </tr>
          <tr>
            <td align="left" valign="top" nowrap="nowrap"><textarea name="us_comments" id="us_comments" style="width:100%; box-sizing:border-box;" rows="2"><?=isset($_SESSION['sess_usu_comm'])?$_SESSION['sess_usu_comm']:""?></textarea></td>
            </tr>
        </table>   
        </div> 
        </td>
      </tr>
    </table>
    </div>
    <div class="blockCart2">
        <?php include("compras/resumen_carrito.php"); ?>
    </div>
    
    <div style="width:100%; display:block; float:left; margin:20px 0;">
        <table width="100%" border="0" cellspacing="0" cellpadding="10">
          <tr>
            <td width="60%" bgcolor="#000"><a href="ver.php/mod/carrito/step/1"><h3 style="color:#fff;">&laquo; SHOPPING BAG</h3></a></td>
            <td width="10%">&nbsp;</td>
            <td width="30%" align="center" bgcolor="#E6C349">
            <a href="javascript:;" onClick="activarPersiana(true); steps(3, 'right','<?=$_SESSION['c_base_location']?>');"><h3 style="color:#000;">CONTINUE &raquo;</h3></a></td>
          </tr>
        </table>
    </div>
</div>

<div id="paso3" style="<?=$step_3?>">
<table width="100%" border="0" cellspacing="0" cellpadding="10">
  <tr>
    <td width="60%" bgcolor="#000"><a href="ver.php/mod/carrito/step/2"><h3 style="color:#fff;">&laquo; SHIPPING INFO</h3></a></td>
    <td width="10%">&nbsp;</td>
    <td width="30%" align="center">&nbsp;</td>
  </tr>
</table>
<br />
<div class="blockCart1">
<table width="100%" border="0" cellspacing="0" cellpadding="10">
  <tr>
    <td valign="top">
    <table width="100%" border="0" cellspacing="0" cellpadding="10">
      <tr>
        <td><h3>Delivery Options</h3></td>
      </tr>
    </table>
    <div style="border-radius:8px; background:#FFF; padding:20px;">
<?php
	
	$carrito = $_SESSION['carrito'.$nombre_carrito];
	$no_tiene_free = true;
	$valor_envio_total = 0;
	
	foreach($carrito as $i => $valor){

		//Consulta de los productos
		$sql="SELECT free_shipping,valor_envio FROM ic_catalogo WHERE id_item='".$i."' ";
		$result=$db->Execute($sql);
		
		list($free_shipping,$valor_envio)=$result->fields;
		
		$valor_envio_total = $valor_envio_total + $valor_envio;
		
		if($free_shipping!="Y"){
			$no_tiene_free = false;
		}
	}
	
	$free_shipping_tienda = obtenerDescripcion("FREE_SHIPPING_STOREWIDE", "", $db);
	$free_shipping_valor = obtenerDescripcion("FREE_SHIPPING", "", $db);
	
	if($no_tiene_free || $free_shipping_tienda->fields[0]=="S" || ($free_shipping_valor->fields[0]!=0 && $free_shipping_valor->fields[0]<=$_SESSION['total_carrito'])){
		$_SESSION['envio_seleccionado'] = 0;
		$_SESSION['envio_seleccionado_detalle'] = "Free Shipping|0";
?>
	<div align="center"><br /><h3><?=_FREESHIPPIPNGINFO?></h3></div>
    <input type="radio" name="fields_metodo_seleccionado" id="fields_metodo_seleccionado" value="99" checked style="display:none;"/>
	<input type="hidden" name="detalle_envio_99" id="detalle_envio_99" value="Free Shipping" />
	<input type="hidden" name="valor_envio_99" id="valor_envio_99" value="0" />
<?php
	}else{	
?>    
      <table border="0" cellspacing="6" align="center" cellpadding="6">
      <tr>
        <td colspan="2" nowrap="nowrap">
        <input type="hidden" name="fields_metodo_seleccionado" id="fields_metodo_seleccionado" value="<?=((isset($_SESSION['envio_seleccionado']))?$_SESSION['envio_seleccionado']:'0')?>"/>
        </td>
      </tr>
<?php	
	$medios_envio = obtenerDescripcion("MEDIO_ENVIO", "", $db);
	
	while(!$medios_envio->EOF){
		list($valor,$campo,$valor_1)=select_format($medios_envio->fields);
		
		if($valor_envio_total > $valor){
			$valor = $valor_envio_total;
		}
		
		echo '<tr>';
		echo '<td width="1">
		        <input type="radio" name="metodo" id="metodo" onclick="javascript:document.getElementById(\'fields_metodo_seleccionado\').value=this.value;" value="'.$valor_1.'" '.((isset($_SESSION['envio_seleccionado']) && $_SESSION['envio_seleccionado']==$valor_1)?'checked="checked"':'').'/>
				<input type="hidden" name="detalle_envio_'.$valor_1.'" id="detalle_envio_'.$valor_1.'" value="'.$campo.'" />
				<input type="hidden" name="valor_envio_'.$valor_1.'" id="valor_envio_'.$valor_1.'" value="'.$valor.'" />
			  </td>';
		echo '<td align="left">'.$campo.'</td>';
		echo '<td align="left" nowrap="nowrap"><b>'.$moneda->fields[0]." ".formateo_numero($valor).'</b></td>';
		echo '</tr>';
		
		$medios_envio->MoveNext();
	}
?>
</table>	
<?php
	}
?>
        <p>&nbsp;</p>
        <div style="background: #E6C349; width: 180px; border-radius:8px; padding: 20px; margin: 0 auto;" align="center">
            <a href="javascript:;" onClick="steps(4, 'right','<?=$_SESSION['c_base_location']?>');"><h3 style="color:#000;">Checkout</h3></a>
        </div>
    </div>
    </td>
  </tr>
</table>
</div>

    <div class="blockCart2">
        <?php include("compras/resumen_carrito.php"); ?>
    </div>
    
</div>

<div id="paso4" style="<?=$step_4?>">
<table width="100%" border="0" cellspacing="0" cellpadding="10">
  <tr>
    <td width="60%" bgcolor="#000">
    <a href="javascript:;" onClick="activarPersiana(true); steps(3, 'right','<?=$_SESSION['c_base_location']?>');"><h3 style="color:#fff">&laquo; DELIVERY OPTIONS</h3></a>
    </td>
    <td width="10%">&nbsp;</td>
    <td width="30%" align="center">&nbsp;</td>
  </tr>
</table>

<div class="blockCart1">
<table width="100%" border="0" cellspacing="0" cellpadding="10">
  <tr>
    <td valign="top">
    
    <table width="100%" border="0" cellspacing="0" cellpadding="5">
      <tr>
        <td><h3>Proceed to Checkout</h3></td>
      </tr>
    </table>
    
    <div style="border-radius:8px; background:#FFF; padding:20px;">
    <table align="center" width="100%" border="0" cellspacing="4" cellpadding="4">
<?php
$cont=0;
$formas_pago = obtenerDescripcion("FORMA_PAGO", "", $db);

while(!$formas_pago->EOF){
?>
  <tr>
    <td bgcolor="#FFFFFF">
      <div align="center" style="border:1px solid #ccc; border-radius:10px; padding:10px 0 5px 0;">
        <?=reemplazar_escape($formas_pago->fields[0])?>
        </div>
        </td>
  </tr>
<?php
	$formas_pago->MoveNext();
	$cont++;
}
?>
    <tr>
    	<td><input type="checkbox" name="terminos" id="terminos" title="Terms and conditions" /> I accept the shipping terms and conditions</td>
    </tr>
    <tr>
    	<td><input type="checkbox" name="regalo" id="regalo" title="<?=_REGALO?>" value="S" /> <?=_REGALO?></td>
    </tr>
	<tr>
      <td>
<?php
	if($_SESSION["idioma"]=="_en"){
		echo '<div style="margin:10px 0; width:100%;"><a href="ver-content.php?mod=contenido&identificador=3" rel="shadowbox[shpter];height=400;width=600;">To view our shipping terms and conditions click here</a></div>';
		
		echo '<div style="margin:10px 0; width:100%;"><a href="ver-content.php?mod=contenido&identificador=4" rel="shadowbox[expol];height=400;width=600;">To View our return/exchange policy click here</a></div>';
	}
	
	/*if($_SESSION["idioma"]=="_es"){
		echo '<div style="margin:10px 0; width:100%;"><a href="ver-content.php?mod=contenido&identificador=16" rel="shadowbox[shpter];height=400;width=600;">Para ver nuestros terminos de envio haga click aqui</a></div>';
		
		echo '<div style="margin:10px 0; width:100%;"><a href="ver-content.php?mod=contenido&identificador=17" rel="shadowbox[expol];height=400;width=600;">Para ver nuestra politica de devoluciones y reembolsos haga click aqui</a></div>';
	}*/
?>
	  </td>
    </tr>
    </table>
    </div>
    </td>
  </tr>
</table>
</div>

	<div class="blockCart2">
        <?php include("compras/resumen_carrito.php"); ?>
    </div>
</div>



<form action="" method="post" id="enviarPago" target="_blank"></form>

<?php

}else{
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
?>
<br />
<div align="center"><?=_NO_ITEMS_CART?></div>
<?php
}
?>