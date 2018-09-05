<?php
	session_start();
	
	if(isset($_POST['opcion']) && $_POST['opcion']=="carrito_compras"){
		
		$nombre_carrito = "";
		
		if(isset($_SESSION['reseller'])){
			$nombre_carrito = $_SESSION['reseller'];
		}
		
		///////////////////////////////////
		
		if(isset($_SESSION['carrito'.$nombre_carrito])){			
			$carrito=$_SESSION['carrito'.$nombre_carrito];
			$adicional=$_SESSION['adicional'.$nombre_carrito];
		}else{
			$carrito="";
			$adicional="";		
		}
		
		//////////////////////////////////
		
		include ("../language/language".$_SESSION['idioma'].".php");		
		$path = "../adodb/adodb.inc.php";
		include ("../admin/var.php");
		include ("../conexion.php");
		
		$ref=explode("-",$_POST['referencia']);
		$sql="SELECT stock FROM ic_referencias_items WHERE id_articulo='".$_POST['id_producto']."' AND id='".$ref[0]."'";
		$result=$db->Execute($sql);
		
		list($stock_real)=$result->fields;
		
		if($stock_real<$_POST['cantidad_producto']){
			$_SESSION['carrito'.$nombre_carrito]=$carrito;
			$_SESSION['adicional'.$nombre_carrito]=$adicional;
					
			echo "ERROR**There are only ".$stock_real." articles available, please select another number.";	
		}else{
			$mensaje = "";
		
			if($_POST['cantidad_producto']=="" || $_POST['cantidad_producto']=="0"){
				
				if(isset($carrito[$_POST['id_producto']])){
					
					unset($carrito[$_POST['id_producto']]);
					unset($adicional[$_POST['id_producto']]);
					
					$mensaje = _PRODUCTO_ELIMINADO;
				}else{
					$mensaje = _PRODUCTO_VACIO;
				}
			}else{
				$carrito[$_POST['id_producto']]=$_POST['cantidad_producto'];
				
				if(isset($_POST['referencia']) && $_POST['referencia']!=""){
					$adicional[$_POST['id_producto']]=$_POST['referencia'];
				}else{
					$adicional[$_POST['id_producto']]="";
				}
				$mensaje = _PRODUCTO_ADICIONADO;
			}
			
			$_SESSION['carrito'.$nombre_carrito]=$carrito;
			$_SESSION['adicional'.$nombre_carrito]=$adicional;
					
			echo "OK**".$mensaje;	
		}
	}
	
?>