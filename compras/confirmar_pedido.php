<?php
	session_start();
	
	$nombre_carrito = "";
	$id_reseller = "";
	
	if(isset($_SESSION['reseller'])){
		$nombre_carrito = $_SESSION['reseller'];
		$id_reseller = $_SESSION['reseller'];
	}
	
	//////////////////////

	if(isset($_POST['opcion']) && $_POST['opcion']=="registrar_compra"){
		
		$path = "../adodb/adodb.inc.php";
		include ("../admin/var.php");
		include ("../conexion.php");
		include ("../language/language".$_SESSION['idioma'].".php");
		include ("funciones_carrito.php");
		include ("../admin/lib/general.php");
		include ("../catalogo/funciones_catalogo.php");
		
		//Recoleccion de variables
		$carrito = $_SESSION['carrito'.$nombre_carrito];
		$detalle_envio = explode("|", $_SESSION['envio_seleccionado_detalle']);
	
		$forma_pago_pedido = obtenerDescripcion("FORMA_PAGO", $_POST['forma_pago'], $db);
		$estado_pedido = obtenerDescripcionWhere("ESTADO_PEDIDO", " AND valor_1='1' ", $db);
		$moneda = obtenerDescripcion("TIPO_MONEDA_SELECCIONADA", "", $db);
		$id_usuario = "0";
		
		if($_SESSION['sess_usu_id']!=""){
			$id_usuario = $_SESSION['sess_usu_id'];
			
			//Actualizo la info de pedido del usuario			
			$result=update_bd_format(array("us_appartment","us_telefono","us_email","us_direccion","us_ciudad","us_postal","us_estado_us"), 
									 "ic_usuarios", 
									 array($_SESSION['sess_usu_appartment'],$_SESSION['sess_usu_telefono'],$_SESSION['sess_usu_email'],$_SESSION['sess_usu_direccion'],
										   $_SESSION['sess_usu_ciudad'],$_SESSION['sess_usu_postal'],$_SESSION['sess_usu_estado_us']), 
									 "WHERE us_id='".$_SESSION['sess_usu_id']."'", 
									 $db);
		}
				
		//Consulta de los productos
		$sql="SELECT MAX(id_pedido) FROM ic_pedidos  ";
		$result=$db->Execute($sql);
		list($id_pedido)=$result->fields;
		$id_pedido = $id_pedido+1;
		$_SESSION["id_pedido"]=$id_pedido;
		
		$valor_cupon = 0;
		
		if(isset($_SESSION['ref_cupon'])){
			$valor_cupon = $_SESSION['total_cupon'];
			
			$sql="SELECT estado,fecha_creacion,referencia,tipo_cupon,valor,fecha_inicio,fecha_final,productos_relacionados,usuario,fecha_uso,pedido,mult_usos,categoria_relacionada
			      FROM ic_cupones 
				  WHERE referencia='".$_SESSION['ref_cupon']."'";
			$result=$db->Execute($sql);
			list($cup_estado,$cup_fecha_creacion,$cup_referencia,$cup_tipo_cupon,$cup_valor,$cup_fecha_inicio,$cup_fecha_final,
			     $cup_productos_relacionados,$cup_usuario,$cup_fecha_uso,$cup_pedido,$cup_mult_usos,$categoria_relacionada)=$result->fields;
			
			if($mult_usos=="N"){
				$result=update_bd_format(array("usuario","fecha_uso","pedido"),
										 "ic_cupones",
										 array($_SESSION['sess_nombre']." ".$_SESSION['sess_last_nombre'], date('Y-m-d'), $id_pedido),
										 "WHERE referencia='".$_SESSION['ref_cupon']."'",
										 $db);
			}else{
					
					$result_insert=insert_bd_format("estado,fecha_creacion,referencia,tipo_cupon,valor,fecha_inicio,fecha_final,productos_relacionados,usuario,fecha_uso,pedido,mult_usos,categoria_relacionada", 
										 "ic_cupones", 
										 array("I",$cup_fecha_creacion,$cup_referencia,$cup_tipo_cupon,$cup_valor,
										       $cup_fecha_inicio,$cup_fecha_final,$cup_productos_relacionados,			     							   
											   $_SESSION['sess_nombre']." ".$_SESSION['sess_last_nombre'],date('Y-m-d'),$id_pedido,
											   $cup_mult_usos,$categoria_relacionada), 
										 $db);
			}
		}
		
		$contenido_pedido="<br><br>"._DATE_CARRITO. ": ".date('m/d/Y')."<br>Order #".$id_pedido."<br><br>"._DETALLE_ARTICULOS."<br>";
		$total_pedido = 0;
		
		foreach($carrito as $i => $valor){
			
			$itemref = explode("-", $_SESSION['adicional'.$nombre_carrito][$i]);
			
			//Consulta de los productos
			$sql="SELECT id_item,l_lang,fecha_creacion,nombre_item,previa_item,full_item,categoria_item,img_previa,img_full,peso,estado,precio,convinado
				FROM ic_catalogo 
				WHERE id_item='".$i."' ";
			$result=$db->Execute($sql);	
			
			////////////////////////////////
			
			$sql="SELECT stock FROM ic_referencias_items WHERE id_articulo='".$i."' AND id='".$itemref[0]."'";
			$result_stock=$db->Execute($sql);
			
			list($stock_real)=$result_stock->fields;
			
			if($stock_real<$carrito[$i]){
				list($id_item,$l_lang,$fecha_creacion,$nombre_item,$previa_item,$full_item,
				     $categoria_item,$img_previa,$img_full,$peso,$estado_articulo,$precio_original)=select_format($result->fields);
					 
				die("Error|There are only (".$stock_real.") ".$nombre_item." available, please select another number.");
			}
			
			/////////////////////////////////
						
			//Result del listado de los productos visualizado en dos columnas
			while (!$result->EOF){
				
				list($id_item,$l_lang,$fecha_creacion,$nombre_item,$previa_item,$full_item,
				     $categoria_item,$img_previa,$img_full,$peso,$estado_articulo,$precio_original)=select_format($result->fields);
						
				$precio = precioFinalCarrito($id_item, "", $db);
				$descuento = $precio_original - $precio;
				
				$adicional = "";
			
				if($_SESSION['adicional'.$nombre_carrito][$i]!=""){					
					$adicional = "Ref: ".$itemref[1] . " - USD$" . $itemref[2];
					
					$db->Execute("UPDATE ic_referencias_items SET stock=(stock-".$carrito[$i].") WHERE id_articulo='".$i."' AND id='".$itemref[0]."'");	
				}
			
				if($_POST['regalo']=="S"){
					$_SESSION['sess_usu_comm'] = _REGALO."\n\n".$_SESSION['sess_usu_comm'];
				}
				
				//Inserto el pedido en la tabla
				$result_insert=insert_bd_format("id_pedido,id_usuario,id_articulo,nombre,email,fecha_pedido,pais,ciudad,cp,direccion,telefono,estado,metodo_pago,nombre_articulo,cantidad,precio_articulo,impuesto,envio,valor_envio,comentarios,descuento,valor_impuesto,cupon,reseller", 
										 "ic_pedidos", 
										 array($id_pedido,$id_usuario,$id_item,$_SESSION['sess_nombre']." ".$_SESSION['sess_last_nombre'],
										       $_SESSION['sess_usu_email'],date('Y-m-d'),$_SESSION['sess_usu_pais']." - ".$_SESSION['sess_usu_estado_us'],
											   $_SESSION['sess_usu_ciudad'],$_SESSION['sess_usu_postal'],
											   $_SESSION['sess_usu_direccion'] . " - " .$_SESSION['sess_usu_appartment'],
											   $_SESSION['sess_usu_telefono'],$estado_pedido->fields[1],
											   $_POST['forma_pago'],$nombre_item."<br>".$adicional,$carrito[$i],$precio,
											   $_SESSION['impuesto'],$detalle_envio[0],$detalle_envio[1],
											   $_SESSION['sess_usu_comm'],$descuento,$_SESSION['impuesto_valor'],
											   $valor_cupon,$id_reseller), 
										 $db);
				
				//Destalle para el correo de la compra
				$precio_producto = ( $precio ) * $carrito[$i];
				$contenido_pedido .= "".$carrito[$i]." ".$nombre_item." ".(($adicional!="")?" (".$adicional.")":"")." ".$moneda->fields[0].formateo_numero($precio_producto)."<br>";				
				$total_pedido = $total_pedido + $precio_producto;
				
				$result->MoveNext();
			}
		}
		
		//Totales
		$contenido_pedido .= "<br>"._IMPUESTO.": ".$moneda->fields[0].formateo_numero(sumarImpuesto("0", $_SESSION['impuesto_valor']));
		$contenido_pedido .= "<br>"._FORMA_ENVIO_PEDIDO.": ".$moneda->fields[0].formateo_numero($detalle_envio[1]);
		
		$total_pedido = $total_pedido + $detalle_envio[1];
		$total_pedido = sumarImpuesto($total_pedido, $_SESSION['impuesto_valor']);
		
		if(isset($_SESSION['ref_cupon'])){
			$contenido_pedido .= "<br>"._TOTAL_DESC.": ".formateo_numero($_SESSION['total_cupon'])."";
			$total_pedido = $total_pedido - formateo_numero($_SESSION['total_cupon']);
		}
		
		$contenido_pedido .= "<br>TOTAL: ".$moneda->fields[0].formateo_numero($total_pedido)."";
		
		$contenido_pedido .= "<br><br>"._FORMA_ENVIO_PEDIDO.": ".$detalle_envio[0];
		//Forma pago y envio
		$contenido_pedido .= "<br>"._FORMA_PAGO_PEDIDO.": ".$forma_pago_pedido->fields[1];
		
		$contenido_pedido .= "<br><br>SHIP TO";
		//Info del comprador
		$contenido_pedido .= "<br>".utf8_decode($_SESSION['sess_nombre'])." ".utf8_decode($_SESSION['sess_last_nombre'])
		                    ."<br>".$_SESSION['sess_usu_telefono']
							."<br>".utf8_decode($_SESSION['sess_usu_direccion'])
							."<br>".$_SESSION['sess_usu_ciudad']
							."<br>".$_SESSION['sess_usu_estado_us']
							."<br>".$_SESSION['sess_usu_postal']
							."<br>".$_SESSION['sess_usu_pais'];
		
		//---------------------------------------------------------------------------
		
		//variables para envio de notificacion de email
		$asunto = obtenerDescripcion("EMAIL_ASUNTO", "", $db);
		$contenido1 = obtenerDescripcion("EMAIL_CONTENIDO1", "", $db);
		$contenido2 = obtenerDescripcion("EMAIL_CONTENIDO2", "", $db);
				
		$contenido = "";
		$contenido .= $contenido1->fields[0];
		$contenido .= $contenido_pedido . "<br><br>";
		$contenido .= $contenido2->fields[0];
		
		$correo_admin = obtenerDescripcion("CORREO_ADMIN", "", $db);
		
		$cabeceras = "MIME-Version: 1.0\r\n"; 
		$cabeceras .= "Content-type: text/html; charset=utf-8\r\n"; 
		$cabeceras .= "From: ".$asunto->fields[0]." <".$correo_admin->fields[0].">\r\n";
		
		//Mail usuario
		mail($_SESSION['sess_usu_email'], $asunto->fields[0], $contenido, $cabeceras);	
		//mail admin
		mail($correo_admin->fields[0], "Admin - ".$asunto->fields[0], $contenido, $cabeceras);
		
		//Definicion de mensaje segun la forma de pago
		$mensaje_alert = "";
		$url_alert = "";
		
		if($forma_pago_pedido->fields[2]==""){
			$mensaje_alert = _PEDIDO_REGISTRO;
			$url_alert = "0";
			
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
			$mensaje_alert = _PEDIDO_REGISTRO_EXTERNO;
			$url_alert = $_SESSION['c_base_location'].$forma_pago_pedido->fields[2];
		}
		
		echo "exito|".$url_alert."|".$mensaje_alert;
	}
?>