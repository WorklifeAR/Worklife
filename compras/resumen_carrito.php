<table width="100%" border="0" cellspacing="0" cellpadding="10">
          <tr>
            <td><h3>Shopping Bag</h3></td>
          </tr>
        </table>
<div style="border-radius:8px; background:#FFF; padding:10px;">
<table width="100%" border="0" cellspacing="0" cellpadding="2" style="font-size:14px;">
  <tr>
    <td>
<?php
	$total_pedido = 0;
	
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
				$free_shipping = "<br /><span class='free'>&igrave;"._ENVIOGRATIS."!</span>";
			}else{
				$free_shipping="";
			}
			
			$adicional = "";
			
			if($_SESSION['adicional'.$nombre_carrito][$i]!=""){
				$itemref = explode("-", $_SESSION['adicional'.$nombre_carrito][$i]);
				$adicional = "Ref: ".$itemref[1] . " - USD$" . $itemref[2]."<br>";
			}
?>
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="1" valign="top">
            <img src="<?=$img_previa?>" style="margin-right: 5px;" width="30" alt="<?=$nombre_item?>"/>
            </td>
            <td valign="top">
            <a href="<?=organizar_nombre_link("item", $nombre_item)?>"><?=$nombre_item?></a><br>
            <span  style="font-size:11px"><?=$adicional?>
            <?=_QTY?>
            : <?=$carrito[$i]?>            
            <?=$free_shipping?></span>
            <br />
            <div align="right">
            <input type="text" name="total_arti_<?=$id_item?>" id="total_arti_<?=$id_item?>" value="<?=$moneda->fields[0]." ".formateo_numero($total_arti)?>" class="valor_pedido" readonly="readonly"/>
            </div>
            </td>
          </tr>
          <tr>
            <td colspan="3" align="center"><hr size="1" width="90%" color="#CCCCCC" style="margin: 20px 0;"/></td>
          </tr>
        </table>
<?php
			$result->MoveNext();
			$cantidad_total_articulo = $cantidad_total_articulo + $carrito[$i];
		}
	}
?>
    </td>
  </tr>
</table>
</div>
<div style="border-radius:8px; background:#FFF; padding:0;">
<table width="100%" border="0" cellpadding="0" cellspacing="0" style="font-size:14px;">
    <tr>
      <td width="90%" align="right"><?=_SUBTOTAL_CARRITO?></td>
      <td width="10%" align="center">
      <input type="text" name="total_compra" id="total_compra" value="<?=$moneda->fields[0]." ".formateo_numero($total_pedido)?>" class="valor_pedido" readonly="readonly"/>
      </td>
    </tr>
<?php

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
      <td width="90%" align="right"><?=_TOTAL_DESC?></td>
      <td width="10%" align="center">
      <input type="text" name="descuento" id="descuento" value="<?=((isset($_SESSION['total_cupon']))?$moneda->fields[0]." ".'-'.formateo_numero($_SESSION['total_cupon']):"")?>" class="valor_pedido_descuento" readonly="readonly"/>
      </td>
    </tr>
<?php
if(isset($_SESSION['total_cupon'])){
	$total_pedido = $total_pedido - $_SESSION['total_cupon'];
}

//******************************************************************************************

//Impuesto
if($impuesto->fields[0]!="" && $impuesto->fields[0]!="0"){
    
    $total_impuesto = ((($impuesto->fields[0]/100) + 1) * $total_pedido ) - $total_pedido;
?>
    <tr>
      <td width="90%" align="right"><?=_IMPUESTO.' ('.$impuesto->fields[0].'%)'?></td>
      <td width="10%" align="center">
      <input type="text" name="total_compra"  id="total_compra" value="<?=$moneda->fields[0]." ".formateo_numero($total_impuesto)?>" class="valor_pedido" readonly="readonly"/>
      </td>
    </tr>
<?php
	$_SESSION['impuesto_valor'] = $total_impuesto;
	$_SESSION['impuesto'] = $impuesto->fields[0];
}

//******************************************************************************************

$total_pedido_impuesto = $total_pedido;
$total_pedido_impuesto = sumarImpuesto($total_pedido_impuesto, $total_impuesto);
$total_pedido_impuesto += (($envioSeleccionado[1]=="")?"0":$envioSeleccionado[1]);

//******************************************************************************************
?>
    <tr>
      <td align="right">Envío</td>
      <td align="center">
      <input type="text" name="total_envio" id="<?=$moneda->fields[0]." ".formateo_numero($envioSeleccionado[1])?>" value="<?=$moneda->fields[0]." ".formateo_numero($envioSeleccionado[1])?>" class="valor_pedido" readonly="readonly"/>
      </td>
    </tr>
    <tr>
      <td width="90%" align="right"><?=_TOTAL_CARRITO?></td>
      <td width="10%" align="center">
      <input type="text" name="total_compra" id="total_compra" value="<?=$moneda->fields[0]." ".formateo_numero($total_pedido_impuesto)?>" class="valor_pedido" readonly="readonly"/>
      </td>
    </tr>
</table>
</div>