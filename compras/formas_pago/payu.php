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

 <form action="https://gateway.payulatam.com/ppp-web-gateway/" method="post" id="formularioPayu">
  <input name="merchantId"    type="hidden"  value="525853"   >
  <input name="accountId"     type="hidden"  value="527616" > 
  
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
			
		$precio_final = $precio_final + ($carrito[$i] * ($precio));
		$texto_final .= "- ".$nombre_item . " (".$itemref[1].") ";

		$result->MoveNext();
	}
}

if(isset($_SESSION['ref_cupon'])){
	$precio_final = $precio_final - ($_SESSION['total_cupon']);
}

if(isset($_SESSION['envio_seleccionado']) && isset($_SESSION['envio_seleccionado_detalle'])){
	$envio = explode("|", $_SESSION['envio_seleccionado_detalle']);
	$precio_final = $precio_final + $envio[1];
	$texto_final = $texto_final . " // Envio: " . $envio[0];
}

echo '<input type="hidden" name="description" value="'.$texto_final.'">';
echo '<input type="hidden" name="referenceCode" value="'.$_SESSION["id_pedido"].'">';
echo '<input type="hidden" name="amount" value="'.($precio_final).'">';
	
if(isset($_SESSION['impuesto_valor']) && $_SESSION['impuesto_valor']!="" && $_SESSION['impuesto_valor']!="0"){
	echo '<input type="hidden" name="tax" value="'.(sumarImpuesto("0", $_SESSION['impuesto_valor'])).'">';
}

//ApiKey~merchantId~referenceCode~amount~currency

$signature = "8qQ3XatifgJm440aCK7Uk001eL~525853~".$_SESSION["id_pedido"]."~".($precio_final)."~COP";

?>
  <input name="taxReturnBase" type="hidden"  value="0" > 
  <input name="currency"      type="hidden"  value="COP" >
  <input name="signature"     type="hidden"  value="<?=md5($signature)?>"  >
  
  <input name="buyerEmail"    type="hidden"  value="<?=$_SESSION['sess_usu_email']?>" >
  <input name="payerFullName"    type="hidden"  value="<?=utf8_decode($_SESSION['sess_nombre'])?>" >
  
  <input name="responseUrl"    type="hidden"  value="http://74.124.203.27/~webact6/clients/comprayteenvio/index.php" >
  <input name="confirmationUrl"    type="hidden"  value="http://74.124.203.27/~webact6/clients/comprayteenvio/index.php" >
  <input name="Submit" style="visibility:hidden;" type="submit"  value="Enviar" >
</form>

<center>
  <font color="#848E44" face="Arial, Helvetica, sans-serif"><b><font size="3">The system is redirecting to the payment gateway. Please wait.</font></b> <br />
  <font size="2">The system will try to open a popup window, check that this option is enabled on your browser.</font></font>
</center>

<script language="javascript" type="text/javascript">
	document.getElementById("formularioPayu").submit();	//
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
	unset($_SESSION["id_pedido"]);
}else{
	echo "error";
}
?>

</body>

</html>