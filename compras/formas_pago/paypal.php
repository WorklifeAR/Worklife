<?php
session_start();

$path = "../../adodb/adodb.inc.php";
include ("../../admin/var.php");
include ("../../conexion.php");
include ("../funciones_carrito.php");
include ("../../admin/lib/general.php");
include ("../../catalogo/funciones_catalogo.php");

$nombre_carrito = "";
		
if(isset($_SESSION['reseller'])){
	$nombre_carrito = $_SESSION['reseller'];
}
		
if(isset($_SESSION['carrito'.$nombre_carrito])){
	$carrito = $_SESSION['carrito'.$nombre_carrito];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin ttulo</title>
</head>
<body>

<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" src="https://www.googleadservices.com/pagead/conversion/1028071882/?value=14&amp;label=NcliCNb6pgIQysOc6gM&amp;guid=ON&amp;script=0"/>
</div>
</noscript>
<form action="https://www.paypal.com/cgi-bin/webscr" method="post" id="formularioPaypal">

<input type="hidden" name="cmd" value="_cart"> 
<input type="hidden" name="upload" value="1">

<input type="hidden" name="business" value="luispbedon@yahoo.com" />

<input type="hidden" name="return" value="http://www.akaisports.us/">

<input type="hidden" name="first_name" value="<?=utf8_decode($_SESSION['sess_nombre'])?>">
<input type="hidden" name="last_name" value="<?=utf8_decode($_SESSION['sess_usu_direccion'])?>">
<input type="hidden" name="address1" value="<?=utf8_decode($_SESSION['sess_usu_direccion_envio'])?> <?=utf8_decode($_SESSION['sess_usu_sexo'])?>">
<input type="hidden" name="city" value="<?=$_SESSION['sess_usu_ciudad']?>">
<input type="hidden" name="state" value="<?=$_SESSION['sess_usu_departamento']?>">
<input type="hidden" name="zip" value="<?=$_SESSION['sess_usu_codigo_ref']?>">
<input type="hidden" name="country" value="US">
<input type="hidden" name="email" value="<?=$_SESSION['sess_usu_email']?>">

<input type="hidden" name="address2" value="-">
<input type="hidden" name="night_phone_a" value="-">
<input type="hidden" name="night_phone_b" value="-">
<input type="hidden" name="night_phone_c" value="-">

<?php

$item = 1;
$precio_final = 0;
$texto_final = "";

foreach($carrito as $i => $valor){

	//Consulta de los productos
	$sql="SELECT id_item,fecha_creacion,nombre_item,previa_item,full_item,categoria_item,img_previa,img_full,precio,convinado,cantidad_articulos
		FROM ic_catalogo
		WHERE id_item='".$i."' ";
	$result=$db->Execute($sql);

	//Result del listado de los productos visualizado en dos columnas
	while (!$result->EOF){

		list($id_item,$fecha_creacion,$nombre_item,$previa_item,$full_item,$categoria_item,$img_previa,$img_full,$precio,$convinado,$cantidad_articulos)=$result->fields;
		
		$precio = precioFinalCarrito($id_item, "", $db);

		
		//******************************************************
		
		$itemref = explode("-", $_SESSION['adicional'.$nombre_carrito][$i]);
			
		$precio_final = $precio_final + ($carrito[$i] * formateo_numero($precio));
		$texto_final .= "- ".$nombre_item . " (".$itemref[1].") ";

		$result->MoveNext();
	}
}

if(isset($_SESSION['ref_cupon'])){
	$precio_final = $precio_final - formateo_numero($_SESSION['total_cupon']);
}

echo '<input type="hidden" name="item_name_'.$item.'" value="'.$texto_final.'">';
echo '<input type="hidden" name="item_number_'.$item.'" value="'.$item.'">';
echo '<input type="hidden" name="amount_'.$item.'" value="'.formateo_numero($precio_final).'">';
echo '<input type="hidden" name="quantity_'.$item.'" value="1">';
$item++;
		
/*if(isset($_SESSION['ref_cupon'])){
	
	echo '<input type="hidden" name="item_name_'.$item.'" value="COUPON">';
	echo '<input type="hidden" name="item_number_'.$item.'" value="'.$item.'">';
	echo '<input type="hidden" name="amount_'.$item.'" value="- '.formateo_numero($_SESSION['total_cupon']).'">';
	echo '<input type="hidden" name="quantity_'.$item.'" value="1">';
		
	$item++;
}*/

if(isset($_SESSION['envio_seleccionado']) && isset($_SESSION['envio_seleccionado_detalle'])){

	$envio = explode("|", $_SESSION['envio_seleccionado_detalle']);
	
	if($envio[1]!="0"){
		echo '<input type="hidden" name="item_name_'.$item.'" value="'.$envio[0].'">';
		echo '<input type="hidden" name="item_number_'.$item.'" value="'.$item.'">';
		echo '<input type="hidden" name="amount_'.$item.'" value="'.formateo_numero($envio[1]).'">';
		echo '<input type="hidden" name="quantity_'.$item.'" value="1">';
	
		$item++;
	}
}

if(isset($_SESSION['impuesto_valor']) && $_SESSION['impuesto_valor']!="" && $_SESSION['impuesto_valor']!="0"){
	
	echo '<input type="hidden" name="item_name_'.$item.'" value="TAX">';
	echo '<input type="hidden" name="item_number_'.$item.'" value="'.$item.'">';
	echo '<input type="hidden" name="amount_'.$item.'" value="'.formateo_numero(sumarImpuesto("0", $_SESSION['impuesto_valor'])).'">';
	echo '<input type="hidden" name="quantity_'.$item.'" value="1">';
		
	$item++;
}


?>

</form>

<center>
  <font color="#848E44" face="Arial, Helvetica, sans-serif"><b><font size="3">The system is redirecting to the payment gateway. Please wait.</font></b> <br />
  <font size="2">The system will try to open a popup window, check that this option is enabled on your browser.</font></font>
</center>

<script language="javascript" type="text/javascript">
	document.getElementById("formularioPaypal").submit();
</script>

<?php

	unset($_SESSION['carrito'.$nombre_carrito]);
	unset($_SESSION['adicional'.$nombre_carrito]);
	unset($_SESSION['impuesto']);
	unset($_SESSION['envio_seleccionado']);
	unset($_SESSION['envio_seleccionado_detalle']);
	unset($_SESSION['valor_envio']);
	unset($_SESSION['impuesto_valor']);
	
	unset($_SESSION['tipo_cupon']);
	unset($_SESSION['valor_cupon']);
	unset($_SESSION['ref_cupon']);
	unset($_SESSION['total_cupon']);

}else{
	echo "error";
}
?>

</body>

</html>