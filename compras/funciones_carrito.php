<?php

//////////////////////////////////////////////////////////////////////////////////////////////

function calcularValorCupon($producto, $cantidad, $valor_producto, $total_pedido, $db){
	if($producto!=""){
		if(isset($_SESSION['producto_cupon']) && isset($_SESSION['ref_cupon'])){
			if($_SESSION['producto_cupon'] == $producto){				
				if($_SESSION['tipo_cupon']=="P"){
					$_SESSION['total_cupon'] = (($valor_producto*$cantidad) * $_SESSION['valor_cupon']) / 100;
				}else{
					$_SESSION['total_cupon'] = $_SESSION['valor_cupon'];
				}				
			}
		}
	}
	
	//------------------------------------------------------
	
	$nombre_carrito = "";
		
	if(isset($_SESSION['reseller'])){
		$nombre_carrito = $_SESSION['reseller'];
	}
	
	//////////////////////

	if(isset($_SESSION['categoria_cupon']) && isset($_SESSION['ref_cupon'])){
		
		$carrito = $_SESSION['carrito'.$nombre_carrito];
		$valor_articulos = 0;
		
		foreach($carrito as $i => $valor){
			//Consulta de los productos relacionado
			$sql="SELECT id_item FROM ic_catalogo WHERE id_item='".$i."' AND categoria_item = '".$_SESSION['categoria_cupon']."'";				
			$result=$db->Execute($sql);
			
			if(!$result->EOF){
				$valor_articulos = $valor_articulos + (precioFinalCarrito($i, $db) * $carrito[$i]);
			}
		}
		
		if($_SESSION['tipo_cupon']=="P"){
			$_SESSION['total_cupon'] = (($valor_articulos) * $_SESSION['valor_cupon']) / 100;
		}else{
			$_SESSION['total_cupon'] = $_SESSION['valor_cupon'];
		}
	}
	
	//------------------------------------------------------
	
	if($producto=="" && $total_pedido!="" && !isset($_SESSION['categoria_cupon']) && !isset($_SESSION['producto_cupon'])){
		if(!isset($_SESSION['producto_cupon']) ){
			if(isset($_SESSION['tipo_cupon']) &&$_SESSION['tipo_cupon']=="P"){
				$_SESSION['total_cupon'] = ($total_pedido * $_SESSION['valor_cupon']) / 100;
			}else{
				if(isset($_SESSION['valor_cupon'])){
					$_SESSION['total_cupon'] = $_SESSION['valor_cupon'];
				}
			}	
		}
	}
}

//////////////////////////////////////////////////////////////////////////////////////////////

function obtenerDescripcion($opcion, $campo, $db){
	
	$contenido_campo="";
	
	if($campo!=""){
		$contenido_campo= " AND campo='".$campo."' ";
	}
	
	$sql="SELECT valor,campo,valor_1 FROM ic_carrito_compras
		  WHERE l_lang='".$_SESSION['idioma']."' AND opcion='".$opcion."' ".$contenido_campo;
	$result=$db->Execute($sql);
	
	return $result;
}

//////////////////////////////////////////////////////////////////////////////////////////////

function sumarImpuesto($total_pedido_impuesto, $total_impuesto){
	
	//verifica si se debe sumar el impuesto al estado destino del usuario
	$total_pedido_impuesto += $total_impuesto;
	
	return $total_pedido_impuesto;
}
//////////////////////////////////////////////////////////////////////////////////////////////

function obtenerImpuesto($db){
	
	//consultar la nueva tabla por si el estado del usuario en session tiene impuesto...
	
	//Sino envia defecto...
	$impuesto = obtenerDescripcion("IMPUESTO", "", $db);
	
	return $impuesto;
}
//////////////////////////////////////////////////////////////////////////////////////////////

function obtenerDescripcionValor1($opcion, $valor1, $db){
		
	$sql="SELECT valor,campo,valor_1 FROM ic_carrito_compras
		  WHERE l_lang='".$_SESSION['idioma']."' AND opcion='".$opcion."' AND valor_1='".$valor1."' ";
	$result=$db->Execute($sql);
	
	return $result;
}

//////////////////////////////////////////////////////////////////////////////////////////////

function obtenerDescripcionWhere($opcion, $where, $db){
	
	$contenido_campo="";
	
	$sql="SELECT valor,campo,valor_1 FROM ic_carrito_compras
		  WHERE l_lang='".$_SESSION['idioma']."' AND opcion='".$opcion."' ".$where;
	$result=$db->Execute($sql);
	
	return $result;
}

//////////////////////////////////////////////////////////////////////////////////////////////

function formateo_numero($numero){
	
	//$numero=str_replace(".",",",$numero);
	
	if($numero==NULL || $numero==""){
		$numero="0";
	}
	
	$numero = number_format($numero, 0, ',', '.');

	return $numero;
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function resumen_carrito(){
	$variables_ver = variables_metodo("item,mod");
	$item= 		$variables_ver[0];
	$mod= 		$variables_ver[1];
	
	$items_carro = "";
	
	$path = "adodb/adodb.inc.php";
	include ("admin/var.php");
	include ("conexion.php");
	
	$cant_art=0;
	$total_art = 0;
	$moneda = obtenerDescripcion("TIPO_MONEDA_SELECCIONADA", "", $db);
	
	$nombre_carrito = "";
		
	if(isset($_SESSION['reseller'])){
		$nombre_carrito = $_SESSION['reseller'];
	}
	
	//////////////////////
	
	if(isset($_SESSION['carrito'.$nombre_carrito])){
		$carrito = $_SESSION['carrito'.$nombre_carrito];		
		$cant_art = 0;

		foreach($carrito as $i => $valor){
			//Consulta de los productos
			$sql="SELECT id_item,nombre_item,precio FROM ic_catalogo WHERE id_item='".$i."' ";
			$result=$db->Execute($sql);			
			list($id_item,$nombre_item,$precio)=$result->fields;
			
			$cant_art = $cant_art + $carrito[$i];
			$total_art = $total_art + ($carrito[$i] * precioFinalCarrito($id_item, "", $db));
		}	
	}
	
	$items_carro = '<a href="shopping-cart.html" style="color:#000; font-size:16px;">'.$cant_art.' Item (s) in cart. Total '.$moneda->fields[0].formateo_numero($total_art).'</a>';
	
	
	echo $items_carro;
}

?>