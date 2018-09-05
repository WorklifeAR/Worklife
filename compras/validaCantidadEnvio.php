<?php
	session_start();
	$path = "../adodb/adodb.inc.php";
	include ("../admin/var.php");
	include ("../conexion.php");
	
	$nombre_carrito = "";
		
	if(isset($_SESSION['reseller'])){
		$nombre_carrito = $_SESSION['reseller'];
	}
	
	if(isset($_POST['envio']) && $_POST['envio']!=""){
		$carrito=$_SESSION['carrito'.$nombre_carrito];
		
		$total = 0;
		
		foreach($carrito as $i => $valor){
			$sql="SELECT id_item,peso_consolidado FROM ic_catalogo WHERE id_item='".$i."' ";	
			$result=$db->Execute($sql);
			list($id_item,$peso_consolidado)=$result->fields;
			
			$peso_consolidado = $peso_consolidado * $carrito[$i];			
			$total = $total + $peso_consolidado;
		}
		
		$sql="SELECT valor FROM ic_carrito_compras WHERE opcion='ITEM_MIN_ENVIO' ";	
		$result=$db->Execute($sql);
		list($valor)=$result->fields;
		
		if($total<$valor){
			echo "Your order is almost complete. Please note the minimum order is any 4 items. Thank you and happy shopping!";
		}else{
			echo "OK";
		}
	}
?>