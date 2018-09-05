<?php
	session_start();
	
	$path = "../adodb/adodb.inc.php";
	include ("../admin/var.php");
	include ("../conexion.php");
	include ("../language/language".$_SESSION['idioma'].".php");
	include ("funciones_carrito.php");
	include ("../admin/lib/general.php");
	include ("../catalogo/funciones_catalogo.php");
		
	$referencia_usuario = $_POST['referencia_usuario'];
	$us_nombre = "";
	
	/////////////////////////////////////////////////////////
	
	$nombre_carrito = "";
		
	if(isset($_SESSION['reseller'])){
		$nombre_carrito = $_SESSION['reseller'];
	}
	
	/////////////////////////////////////////////////////////
		
	if(isset($_SESSION['sess_nombre']) && isset($_SESSION['sess_last_nombre'])){
		$us_nombre .= $_SESSION['sess_nombre'];
		$us_nombre .= " " . $_SESSION['sess_last_nombre'];
	}
	
	//Consulta de los productos
	$sql="SELECT id_cupon,fecha_creacion,referencia,tipo_cupon,valor,fecha_inicio,fecha_final,productos_relacionados,usuario,fecha_uso,categoria_relacionada
	      FROM ic_cupones 
		  WHERE referencia='".$referencia_usuario."'  AND estado='A'";
	$result=$db->Execute($sql);
	list($id_cupon,$fecha_creacion,$referencia,$tipo_cupon,$valor_cupon,$fecha_inicio,$fecha_final,$productos_relacionados,$usuario,$fecha_uso,$categoria_relacionada)=$result->fields;
	
	if($id_cupon!=""){
		if($fecha_uso==""){
			$fecha_actual = strtotime(date("d-m-Y 00:00:00",time()));
			$fecha_inicial = strtotime("".$fecha_inicio." 00:00:00");
			
			if($fecha_actual >= $fecha_inicial){
				if($fecha_final!=""){
					$fecha_finalizacion = strtotime("".$fecha_final." 00:00:00");
					
					if($fecha_actual > $fecha_finalizacion){
						die(_COUPON_LIMIT);
					}
				}
				
				//--------
				
				$continuar = true;
				
				if($productos_relacionados!=""){
					$carrito = $_SESSION['carrito'.$nombre_carrito];
					
					foreach($carrito as $i => $valor){
						//Consulta de los productos relacionado
						$sql="SELECT productos_relacionados FROM ic_cupones WHERE productos_relacionados LIKE '".$i."' AND referencia='".$referencia_usuario."'";				
						$result=$db->Execute($sql);
						
						if(!$result->EOF){
							$_SESSION['producto_cupon'] = $i;
							$encontro = true;
							break;
						}						
					}
					
					if(!$encontro){
						$continuar = false;
						echo _COUPON_NOITEM;
					}
				}
				
				//------------
				
				if($categoria_relacionada!=""){
					$carrito = $_SESSION['carrito'.$nombre_carrito];
					
					foreach($carrito as $i => $valor){
						//Consulta de los productos relacionado
						$sql="SELECT id_item FROM ic_catalogo WHERE id_item='".$i."' AND categoria_item = '".$categoria_relacionada."'";				
						$result=$db->Execute($sql);
						
						if(!$result->EOF){
							$_SESSION['categoria_cupon'] = $categoria_relacionada;
							$encontro = true;
							break;
						}
					}
					
					if(!$encontro){
						$continuar = false;
						echo _COUPON_NOITEM;
					}
				}
				
				//------------
				
				if($continuar){				
					
					$_SESSION['tipo_cupon'] = $tipo_cupon;
					$_SESSION['valor_cupon'] = $valor_cupon;
					$_SESSION['ref_cupon'] = $referencia_usuario;
					
					echo "OK|"._COUPON_OK;
				}
				
				//--------
			}else{
				echo "-"._COUPON_LIMIT;
			}
		}else{
			echo _COUPON_NOVALI;
		}
	}else{
		echo _COUPON_NOVALI;
	}
?>