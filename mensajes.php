<?php
	$variables_ver = variables_metodo("id_cotizacion,mensaje_nuevo,id_directorio,tipo,funcion,id_destino");
	$id_coti= 				$variables_ver[0];
	$mensaje_nuevo= 		$variables_ver[1];
	$id_directorio= 		$variables_ver[2];
	$tipo= 					$variables_ver[3];
	$funcion= 				$variables_ver[4];
	$id_destino= 			$variables_ver[5];
	
	//////////////////////////////////
	
	if ($funcion == "guardar" && $id_coti!="") {
		/* Funcion para guardar los datos del formulario */
        guardar($id_coti, $mensaje_nuevo, $id_directorio, $id_destino, $db);
	}
	
	/************************************************************************************************************************** */

	function guardar($id_coti, $mensaje_nuevo, $id_directorio, $id_destino, $db) {
		
		if($mensaje_nuevo!=""){
			$sql = "INSERT INTO ic_respuesta_cotizacion (id_coti, id_usuario_envia, id_usuario_destino, fecha, respuesta, estado) 
					VALUES ('".$id_coti."',
							'".$_SESSION['sess_usu_id']."',
							'".$id_destino."',
							'".date("Y-m-d H:i:s")."',
							'".$mensaje_nuevo."',
							'UNREAD');";
			$result = $db->Execute($sql);
			
			/////////////////////
		
			if ($result != false)
				$mensaje = "1";
			else
				$mensaje = "0";
				
			$sql = "UPDATE ic_cotizaciones SET estado_coti='A' WHERE id_coti='".$id_coti."' AND estado_coti='P' AND id_usuario!='".$_SESSION['sess_usu_id']."';";
			$result = $db->Execute($sql);
			
			
			////////////////////////
			
			$sql="SELECT us_nombre, us_email FROM ic_usuarios WHERE us_id ='".$id_destino."' ";					
			$result_usu=$db->Execute($sql);		
			list($us_nombre, $us_email)=select_format($result_usu->fields);
				 
			$adicional_formato="<a href='cotizaciones.html'>Ver mensajes</a>";
			enviar_notificacion($us_email, $us_nombre, "NUEVO_MENSAJE", "1", $adicional_formato) ;
			
			////////////////////////
			
			echo"<META HTTP-EQUIV='Refresh' CONTENT='0;URL=cotizaciones.html'>";
			die();
		}
	}
	
	/************************************************************************************************************************** */
	
	$sql = "UPDATE ic_respuesta_cotizacion SET estado='READ' WHERE id_coti='".$id_coti."' AND id_usuario_destino='".$_SESSION['sess_usu_id']."';";
	$result = $db->Execute($sql);
	
	//////////////////////////////////
	
	
	$sql = "SELECT id_directorio, nombre, categoria, descripcion, direccion, ciudad, estado, zip, telefono, web, info_adicional, email, 
					sponsor, logo, latitud, longitud, orden, id_usuario, activo, destacado, img1, img2, img3, video, fecha, visualizaciones 
			FROM ic_directorio WHERE id_directorio='" . $id_directorio . "'";
	$result = $db->Execute($sql);

	list($id_directorio, $nombre, $categoria, $descripcion, $direccion, $ciudad, $estado, $zip, $telefono, $web, $info_adicional, $email, $sponsor, $logo, $latitud, 
		 $longitud, $orden,$id_usuario_directorio, $activo, $destacado, $img1, $img2, $img3, $video, $fecha, $visualizaciones) = select_format($result->fields);
		 
?>
<div class="row">
    <div class="col-sm-2 col-xs-6">
        <div style="text-align:center; width:100%; background:#7C4793; padding:3px 0; margin:5px 0;">
            <a href="info-usuario.html" style="color:#fff; font-size:16px;"><i class="fa fa-user" aria-hidden="true"></i> Mi perfil</a>
        </div>            
    </div>        
    <div class="col-sm-2 col-xs-6">
        <div style="text-align:center; width:100%; background:#7C4793; padding:3px 0; margin:5px 0;">
            <a href="mis-publicaciones.html" style="color:#fff; font-size:16px;"><i class="fa fa-list" aria-hidden="true"></i> Publicaciones</a>
        </div>
    </div>  
    <div class="col-sm-2 col-xs-6">
        <div style="text-align:center; width:100%; background:#FAA532; padding:3px 0; margin:5px 0;">
            <a href="cotizaciones.html" style="color:#fff; font-size:16px;"><i class="fa fa-list-alt" aria-hidden="true"></i> Cotizaciones</a>
        </div>
    </div>         
    <div class="col-sm-2 col-xs-6">
        <div style="text-align:center; width:100%; background:#7C4793; padding:3px 0; margin:5px 0;">
            <a href="publicaciones-favoritas.html" style="color:#fff; font-size:16px;"><i class="fa fa-heart" aria-hidden="true"></i> Mi favoritos</a>
        </div>
    </div>     
    <div class="col-sm-2 col-xs-6">
        <div style="text-align:center; width:100%; background:#7C4793; padding:3px 0; margin:5px 0;">
            <a href="mi-reputacion.html" style="color:#fff; font-size:16px;"><i class="fa fa-star" aria-hidden="true"></i>  Mi Reputación</a>
        </div>
    </div>  
    
    <div class="col-sm-2 col-xs-6">
        <div style="text-align:center; width:100%; background:#7C4793; padding:3px 0; margin:5px 0;">
            <a href="contenido/planes-worklife.html" style="color:#fff; font-size:16px;"><i class="fa fa-briefcase" aria-hidden="true"></i>  Cambiar mi plan</a>
        </div>
    </div>  
</div>
<br /><br />

<h1><i class="fa fa-list-alt" aria-hidden="true"></i> Mensajes</h1>
<h2>Mensajes <?=(($tipo=="solicitudes")?"solicitud":"cotización")?> #<?=$id_coti?>: <?=$nombre?></h2>
<br />    
<br />
<a name="end"></a> 
<?php
	$sql = "SELECT id_respuesta, id_coti, id_usuario_envia, id_usuario_destino, fecha, respuesta, estado ,
				   (CONCAT(b.us_nombre,' ',b.us_last_name)) AS nom_envia, (CONCAT(c.us_nombre,' ',c.us_last_name)) AS nom_destino,
				   b.us_foto AS foto_envia, c.us_foto AS foto_destino
			FROM ic_respuesta_cotizacion a, ic_usuarios b, ic_usuarios c
			WHERE id_coti='" . $id_coti . "' AND a.id_usuario_envia=b.us_id AND a.id_usuario_destino=c.us_id ORDER BY id_respuesta ASC";
	$result_respuesta = $db->Execute($sql);

	$count = 0;
	
	while(!$result_respuesta->EOF){
		list($id_respuesta, $id_coti, $id_usuario_envia, $id_usuario_destino, $fecha, $respuesta, $estado, $nom_envia, $nom_destino, $foto_envia, $foto_destino) = select_format($result_respuesta->fields);
		
		$origen = "";
		if($id_usuario_envia==$_SESSION['sess_usu_id']){
			$origen = "<strong>Yo</strong> ";
			$us_foto = $foto_envia;
		}else{
			$origen = "<strong>".$nom_envia."</strong> ";
			$us_foto = $foto_envia;
		}
		
		$color="#eee";
		if($count==0){
			$color="#ddd";
		}
		
		if($us_foto==""){
			$us_foto = "images/profile.jpg";
		}
		
?>
	<div class="row" style="margin:15px 0; padding:15px 10px; border-radius:10px; background:<?=$color?>;">
    	<div class="col-sm-2 col-xs-12 text-center">
        	<img src="<?= $us_foto ?>" style="width:40%; border-radius:300px;" />
            <h4><?= $origen ?></h4>
        </div>
        <div class="col-sm-10 col-xs-12 text-left" style="border-left:2px solid #ccc;">
        	<?=$fecha?>
            <br />
            <?=$respuesta?>
        </div>        
    </div>
<?php	
		$count++;
		
		if($count==2){
			$count=0;
		}
		
		$result_respuesta->MoveNext();
	}
?>
	<div style="width:100%; border-top:5px solid #ccc; margin:30px 0;"></div>
    
    <div class="row">
    	
    	<form method="post" action="user.php#end">
    	<div class="col-sm-10 col-xs-12 text-left">
            Escribir mensaje:<br /><textarea id="mensaje_nuevo" name="mensaje_nuevo" class="form-control"></textarea>
        </div>
        <div class="col-sm-2 col-xs-12 text-center">
        	<br />
        	<input type="submit" class="btn btn-primary" value="Enviar Mensaje" style="width:100%;" />
            <input type="hidden" name="funcion" value="guardar" />
            <input type="hidden" name="op_usu" value="mensajes" />
            <input type="hidden" name="tipo" value="<?=$tipo?>" />
            <input type="hidden" name="id_cotizacion" value="<?=$id_coti?>" />
            <input type="hidden" name="id_destino" value="<?=$id_usuario_directorio?>" />
            <input type="hidden" name="id_directorio" value="<?=$id_directorio?>" />
        </div>
        </form>
    </div>
    