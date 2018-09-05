<?php
	session_start();
	
	if(isset($_POST['opcion']) && $_POST['opcion']=="carrito_compras"){

		include ("../language/language".$_SESSION['idioma'].".php");		

		$nombre_carrito = "";
				
		if(isset($_SESSION['reseller'])){
			$nombre_carrito = $_SESSION['reseller'];
		}
		
		//////////////////////
		
		if(isset($_SESSION['carrito'.$nombre_carrito])){
		
			$carrito=$_SESSION['carrito'.$nombre_carrito];
			$adicional=$_SESSION['adicional'.$nombre_carrito];
		
			unset($carrito[$_POST['id_producto']]);
			unset($adicional[$_POST['id_producto']]);
			
			if(count($carrito)>0){
				$_SESSION['carrito']=$carrito;
			}else{
				unset($_SESSION['carrito'.$nombre_carrito]);
			}
			
			echo _PRODUCTO_ELIMINADO;
		}
	}
?>