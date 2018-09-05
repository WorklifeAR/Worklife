<?php
#error_reporting(E_ALL);
#ini_set('display_errors', '1');

//Definicion de parametros de metodos
$variables_metodo = variables_metodo("id_directorio,funcion");
$id_directorio = $variables_metodo[0];
$funcion = $variables_metodo[1];

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

<h1><i class="fa fa-list-alt" aria-hidden="true"></i> Cotizaciones</h1>
<h2>Listado cotizaciones y  mensajes</h2>
<br />    
<br />
<?php
$sql = "SELECT COUNT(*) FROM ic_cotizaciones a, ic_directorio z WHERE a.id_directorio=z.id_directorio AND z.id_usuario='".$_SESSION['sess_usu_id']."'";
$result_sol_total = $db->Execute($sql);
list($sol_total) = select_format($result_sol_total->fields);

$sql = "SELECT count(*) FROM ic_respuesta_cotizacion a, ic_cotizaciones b WHERE a.id_coti=b.id_coti AND b. tipo_reg_coti='SOL' AND a.id_usuario_destino ='" . $_SESSION['sess_usu_id'] . "' AND a.estado='UNREAD'";
$result = $db->Execute($sql);
list($can_msg) = select_format($result->fields);

$msgsol = "";
if ($can_msg > 0) {
	$msgsol = ' <span class="label label-warning">' . $can_msg . '</span>';
}

/////////////////

$sql = "SELECT COUNT(*) FROM ic_cotizaciones a, ic_directorio c WHERE a.id_directorio=c.id_directorio AND a.id_usuario='".$_SESSION['sess_usu_id']."' ";
$result_cot_total = $db->Execute($sql);
list($cot_total) = select_format($result_cot_total->fields);

$sql = "SELECT count(*) FROM ic_respuesta_cotizacion a, ic_cotizaciones b WHERE a.id_coti=b.id_coti AND b. tipo_reg_coti='COT' AND a.id_usuario_destino ='" . $_SESSION['sess_usu_id'] . "' AND a.estado='UNREAD'";
$result = $db->Execute($sql);
list($can_msg) = select_format($result->fields);

$msgcot = "";
if ($can_msg > 0) {
	$msgcot = ' <span class="label label-warning">' . $can_msg . '</span>';
}

?>
   
<ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#mis-coti" aria-controls="mis-coti" role="tab" data-toggle="tab"><b>(<?=$sol_total?>) Cotizaciones recibidas</b><?=$msgsol?></a></li>
    <li role="presentation"><a href="#otras-coti" aria-controls="otras-coti" role="tab" data-toggle="tab"><b>(<?=$cot_total?>) Cotizaciones enviadas</b><?=$msgcot?></a></li>
</ul>
<?php
	$sql = "SELECT a.id_coti, a.nombre_coti, a.email_coti, a.tele_coti, a.mensaje_coti, a.link_coti, a.fecha_coti, a.id_directorio, a.estado_coti, a.calificacion_coti, a.id_usuario, a.tipo_reg_coti,
			       (SELECT COUNT(b.id_respuesta) FROM ic_respuesta_cotizacion b WHERE b.id_coti=a.id_coti) AS total_mensajes,
				   (SELECT COUNT(b.id_respuesta) FROM ic_respuesta_cotizacion b WHERE b.id_coti=a.id_coti AND b.id_usuario_destino='".$_SESSION['sess_usu_id']."' AND b.estado='UNREAD') AS total_noleidos
			FROM ic_cotizaciones a, ic_directorio z
			WHERE a.id_directorio=z.id_directorio AND z.id_usuario='".$_SESSION['sess_usu_id']."' ORDER BY a.fecha_coti DESC";
	$result_sol = $db->Execute($sql);
	
	/////////////////////////////
?>
<div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="mis-coti">
<?php
	while(!$result_sol->EOF){
		list($id_coti, $nombre_coti, $email_coti, $tele_coti, $mensaje_coti, $link_coti, $fecha_coti, $id_directorio, $estado_coti, 
		     $calificacion_coti, $id_usuario_solicitud, $tipo_reg_coti, $total_mensajes, $total_noleidos) = select_format($result_sol->fields);
		
		if($total_noleidos>0){
			$total_noleidos='<span class="label label-warning">'.$total_noleidos.'</span>';
		}else{
			$total_noleidos='';
		}
		
		$fecha_coti = explode(" ", $fecha_coti);
        $fecha_coti = explode("-", $fecha_coti[0]);
		
		$color = "";
		$estado_final = "";
		
		if($estado_coti=="P"){
			$color = "#72BF24";			
			$estado_final = "Sin devolución";
		}elseif($estado_coti=="T"){
			$color = "#949ECF";		
			$estado_final = "Servicio finalizado";	
		}else{
			$color = "#ccc";
			$estado_final = "Cotización en proceso";	
		}
		///////////////////
		
		$sql = "SELECT id_respuesta, id_coti, id_usuario_envia, id_usuario_destino, fecha, respuesta, estado ,
					   (CONCAT(b.us_nombre,' ',b.us_last_name)) AS nom_envia, (CONCAT(c.us_nombre,' ',c.us_last_name)) AS nom_destino
		        FROM ic_respuesta_cotizacion a, ic_usuarios b, ic_usuarios c
				WHERE id_coti='" . $id_coti . "' AND a.id_usuario_envia=b.us_id AND a.id_usuario_destino=c.us_id ORDER BY id_respuesta DESC LIMIT 0,1";
    	$result_respuesta = $db->Execute($sql);
	
		list($id_respuesta, $id_coti, $id_usuario_envia, $id_usuario_destino, $fecha, $respuesta, $estado, $nom_envia, $nom_destino) = select_format($result_respuesta->fields);
		
		/*$origen = "";
		$linkmensajes = "";
		if($id_usuario_envia==$_SESSION['sess_usu_id']){
			$origen = "<strong>Yo:</strong> ";
			$linkmensajes = '<a href="javascript:;" onclick="document.mensajes'.$id_coti.'.submit();"><i class="fa fa-envelope-o" aria-hidden="true"></i> Ver mensajes</a>';
		}else{
			$origen = "<strong>".$nom_envia.":</strong> ";
			$linkmensajes = '<a href="javascript:;" onclick="document.mensajes'.$id_coti.'.submit();"><i class="fa fa-envelope-o" aria-hidden="true"></i> Contestar mensajes</a>';
		}*/
		
		$origen = "";
		if($id_usuario_envia==$_SESSION['sess_usu_id']){
			$origen = "Ver mensajes";
		}else{
			$origen = "Responder mensaje";
		}
		
		///////////////////
		
		$sql = "SELECT id_directorio, nombre, categoria, descripcion, direccion, ciudad, estado, zip, telefono, web, info_adicional, email, 
		 			  	sponsor, logo, latitud, longitud, orden, id_usuario, activo, destacado, img1, img2, img3, video, fecha, visualizaciones 
		 		FROM ic_directorio WHERE id_directorio='" . $id_directorio . "'";
    	$result = $db->Execute($sql);
	
		list($id_directorio, $nombre, $categoria, $descripcion, $direccion, $ciudad, $estado, $zip, $telefono, $web, $info_adicional, $email, $sponsor, $logo, $latitud, 
             $longitud, $orden,$id_usuario_directorio, $activo, $destacado, $img1, $img2, $img3, $video, $fecha, $visualizaciones) = select_format($result->fields);
		
		 if ($logo == "") {
			$logo = "images/publication.jpg";
		}
		
		///////////////////
		
		$sql="SELECT 
				 us_id, us_codigo_ref, us_nombre, us_pais, us_telefono, us_email, us_direccion, us_nacimiento,
				 us_last_name, us_sexo, us_descripcion, us_login, us_pass, us_postal, us_ciudad, us_estado_us,us_appartment,us_foto
			  FROM ic_usuarios
			  WHERE us_id ='".$id_usuario_solicitud."' ";
				
		$result_usu=$db->Execute($sql);
	
		list($us_id, $us_codigo_ref, $us_nombre, $us_pais, $us_telefono, $us_email, $us_direccion, $us_nacimiento,
			 $us_last_name, $us_sexo, $us_descripcion, $us_login, $us_pass, $us_postal, $us_ciudad, $us_estado_us, $us_appartment,$us_foto)=select_format($result_usu->fields);
		
		if($us_foto==""){
			$us_foto = "images/profile.jpg";
		}
		 
		///////////////////
		
?>
		<div class="row" style="padding:15px 0 15px 0; border-bottom:10px solid <?=$color?>; margin:0 0 10px 0;">
                <div class="col-sm-2 col-xs-12 text-center">
                    <img src="<?= $logo ?>" style="width:100%" />
                </div>        
                <div class="col-sm-8 col-xs-12 text-left">
                    <h4><?= $nombre ?></h4>
                    
                    <!--<div style="color:#888; margin:10px 0 15px 0;"><?= strip_tags($descripcion) ?></div>-->
                    
                    <div style="margin:15px 0; border-top: 1px solid #ccc;"></div>
                    
                    <div style="color:#000; margin:10px 0 15px 0;">
                    	Solicitado el  <?= $fecha_coti[2] ?>/<?= $fecha_coti[1] ?> del <?= $fecha_coti[0] ?>
                        <br />
                        <strong>Estado de solicitud:</strong> <?=$estado_final?>
                        <br />
						<?php 
							if($link_coti!=""){ echo 'Archivo adjunto: <a href="'.$link_coti.'" targuet="_blank">[ Descargar ]</a><br />'; }
						?>
						
						<div style="margin:15px 0; border-top: 1px solid #ccc;"></div>
						
						Te contacto:
						<div class="row">
							<div class="col-sm-6 col-xs-12">
								<?=$us_nombre?> <?=$us_last_name?><br>
								<?=$us_ciudad?> <?=$us_estado_us?>. <?=$us_postal?><br>
								<?=$us_direccion?>
							</div>
							<div class="col-sm-6 col-xs-12">
								<?=$us_email?><br>
								<?=$us_telefono?>								
							</div>
						</div>
												
                    </div>
                    <!--div style="color:#000; margin:10px 0 15px 0;">
                    	<strong>Solicitud:</strong><br />
                        <span style="color:#888;"><?=$mensaje_coti?></span>
                        <br />
                        <strong>Mensajes:</strong> <?=$total_mensajes?> mensaje(s), <?=$total_noleidos?> sin leer.                        
                    </div>
                    <div style="color:#000; margin:10px 0 15px 0;">
                    	<strong>Último mensaje:</strong>
                        <br />
                        <?=$origen." ".substr(strip_tags($respuesta),0,50)."...<br><br>"?>                     
                    </div>-->
					
                    <div style="color:#000; margin:10px 0 15px 0;">
						<form action="user.php" id="mensajes<?=$id_coti?>" name="mensajes<?=$id_coti?>" method="post" >
							<input type="hidden" name="op_usu" value="mensajes" />
							<input type="hidden" name="tipo" value="solicitudes" />
							<input type="hidden" name="id_cotizacion" value="<?=$id_coti?>" />
							<input type="hidden" name="id_directorio" value="<?=$id_directorio?>" />
							<button type="submit" class="btn btn-primary"><i class="fa fa-envelope-o" aria-hidden="true"></i> <?=$origen?> <?=$total_noleidos?></button>
						</form>
					</div>					

                    <div style="margin:15px 0; border-top: 1px solid #ccc;"></div>
                    <div class="row">
                    	<div class="col-sm-6 col-xs-12 text-center">
					<?php if($estado_coti=="A"){ ?>
                    <form action="user.php" method="post" >
                    	<input type="hidden" name="op_usu" value="calificar" />
                        <input type="hidden" name="tipo" value="completo" />
                        <input type="hidden" name="id_cotizacion" value="<?=$id_coti?>" />
                        <input type="hidden" name="id_directorio" value="<?=$id_directorio?>" />
                    	<button type="submit" class="btn btn-success" style="width:100%;"><i class="fa fa-check-square-o" aria-hidden="true"></i> Se termino el servicio, a calificar!</button>
                    </form> 
                    <?php } ?>
                    	</div>
                        <div class="col-sm-6 col-xs-12 text-center">
                    <?php if($estado_coti!="T"){ ?>
                     <form action="user.php" method="post" >
                    	<input type="hidden" name="op_usu" value="calificar" />
                        <input type="hidden" name="tipo" value="incompleto" />
                        <input type="hidden" name="id_cotizacion" value="<?=$id_coti?>" />
                        <input type="hidden" name="id_directorio" value="<?=$id_directorio?>" />
                    	<button type="submit" class="btn btn-danger" style="width:100%;"><i class="fa fa-sign-out" aria-hidden="true"></i> No se pudo completar el servicio!</button>
                    </form>
                    <?php } ?>
                    	</div>
                    </div>
                    <?php
						if($estado_coti=="T"){
							
							$sql = "SELECT id_repu, id_cotizacion, tipo, fecha, id_usuario, descripcion, calificacion FROM ic_reputacion WHERE id_cotizacion='".$id_coti."' AND id_usuario='".$_SESSION['sess_usu_id']."'";
							$result_califi = $db->Execute($sql);	
							
							/////////////////////////////////							
					?>
                    <div class="row">
                    	<div class="col-sm-6 col-xs-12 text-center" style="background:#f4f4f4; padding:10px 0;">
                        	<?php 
								if(!$result_califi->EOF){
									list($id_repu, $id_cotizacion, $tipo, $fecha, $id_usuario, $descripcion, $calificacion) = select_format($result_califi->fields);
							?>
                        	<?php if($calificacion==1 || $calificacion==2 || $calificacion==3 || $calificacion==4 || $calificacion==5){ ?><i class="fa fa-star" aria-hidden="true"></i><?php }else{ ?><i class="fa fa-star-o" aria-hidden="true"></i><?php } ?>
							<?php if($calificacion==2 || $calificacion==3 || $calificacion==4 || $calificacion==5){ ?><i class="fa fa-star" aria-hidden="true"></i><?php }else{ ?><i class="fa fa-star-o" aria-hidden="true"></i><?php } ?>
                            <?php if($calificacion==3 || $calificacion==4 || $calificacion==5){ ?><i class="fa fa-star" aria-hidden="true"></i><?php }else{ ?><i class="fa fa-star-o" aria-hidden="true"></i><?php } ?>
                            <?php if($calificacion==4 || $calificacion==5){ ?><i class="fa fa-star" aria-hidden="true"></i><?php }else{ ?><i class="fa fa-star-o" aria-hidden="true"></i><?php } ?>
                            <?php if($calificacion==5){ ?><i class="fa fa-star" aria-hidden="true"></i><?php }else{ ?><i class="fa fa-star-o" aria-hidden="true"></i><?php } ?>
                            <br />
                            <?=$tipo?>
                            <br />
                            <span style="font-style:italic; color:#7C4793;"><?=$descripcion?></span>
                            <?php 
								}else{
							?>
                            	Sin calificación.
                                <br />
                                <a href="calificar.php?id_coti=<?=$id_coti?>&modo=prestado"><i class="fa fa-star" aria-hidden="true"></i> Califica al usuario y el servicio prestado.</a>
                                <br />
                                <span style="font-style:italic"><?=$_SESSION['sess_nombre']." ".$_SESSION['sess_last_nombre']?></span>
                            <?php	
								}
							?>
                        </div>
                    <?php
							$sql = "SELECT id_repu, id_cotizacion, tipo, fecha, id_usuario, descripcion, calificacion FROM ic_reputacion WHERE id_cotizacion='".$id_coti."' AND id_usuario='".$id_usuario_directorio."'";
							$result_califi = $db->Execute($sql);						
							
							/////////////////////////////////
					?>
                        <div class="col-sm-6 col-xs-12 text-center" style="background:#f4f4f4; padding:10px 0;">
                        	<?php 
								if(!$result_califi->EOF){
									list($id_repu, $id_cotizacion, $tipo, $fecha, $id_usuario, $descripcion, $calificacion) = select_format($result_califi->fields);
							?>
                        	<?php if($calificacion==1 || $calificacion==2 || $calificacion==3 || $calificacion==4 || $calificacion==5){ ?><i class="fa fa-star" aria-hidden="true"></i><?php }else{ ?><i class="fa fa-star-o" aria-hidden="true"></i><?php } ?>
							<?php if($calificacion==2 || $calificacion==3 || $calificacion==4 || $calificacion==5){ ?><i class="fa fa-star" aria-hidden="true"></i><?php }else{ ?><i class="fa fa-star-o" aria-hidden="true"></i><?php } ?>
                            <?php if($calificacion==3 || $calificacion==4 || $calificacion==5){ ?><i class="fa fa-star" aria-hidden="true"></i><?php }else{ ?><i class="fa fa-star-o" aria-hidden="true"></i><?php } ?>
                            <?php if($calificacion==4 || $calificacion==5){ ?><i class="fa fa-star" aria-hidden="true"></i><?php }else{ ?><i class="fa fa-star-o" aria-hidden="true"></i><?php } ?>
                            <?php if($calificacion==5){ ?><i class="fa fa-star" aria-hidden="true"></i><?php }else{ ?><i class="fa fa-star-o" aria-hidden="true"></i><?php } ?>
                            <br />
                            <?=$tipo?>
                            <br />
                            <span style="font-style:italic; color:#7C4793;"><?=$descripcion?></span>
                            <?php 
								}else{
							?>
                                Sin calificación.
                                <br />
                                Esperando Respuesta.
                                <br />
                                <span style="font-style:italic"><?=$us_nombre." ".$us_last_name?></span>
                                <br />
                            <?php	
								}
							?>
                        </div>
                        
                        <div class="col-sm-12 col-xs-12 text-right" style="padding:15px 0 0 0;">
                        	<a href="user.php?op_usu=denuncias&id_coti=<?=$id_coti?>&cotizacion=Cotización+No.<?=$id_coti?>&publicacion=<?= $nombre ?>" style="color:#900"><i class="fa fa-ban" aria-hidden="true"></i> Denunciar Calificación</a>
                        </div>
                    </div>
                    <?php
						}
					?>
                </div>  
                <div class="col-sm-2 col-xs-12 text-center">
                    Te contacto:<br> 
					<img src="<?= $us_foto ?>" style="width:50%; border-radius:300px;" />
                    <br><h4><?= $us_nombre ?> <?= $us_last_name ?></h4>
                    <br />
                    
                </div>               
            </div>
<?php
		$result_sol->MoveNext();	
	}
?>
    </div>
    <div role="tabpanel" class="tab-pane" id="otras-coti">
<?php
	
	$sql = "SELECT a.id_coti, a.nombre_coti, a.email_coti, a.tele_coti, a.mensaje_coti, a.link_coti, a.fecha_coti, a.id_directorio, a.estado_coti, a.calificacion_coti, a.id_usuario, a.tipo_reg_coti,
			       (SELECT COUNT(b.id_respuesta) FROM ic_respuesta_cotizacion b WHERE b.id_coti=a.id_coti) AS total_mensajes,
				   (SELECT COUNT(b.id_respuesta) FROM ic_respuesta_cotizacion b WHERE b.id_coti=a.id_coti AND b.id_usuario_destino='".$_SESSION['sess_usu_id']."' AND b.estado='UNREAD') AS total_noleidos
			FROM ic_cotizaciones a, ic_directorio c
			WHERE a.id_directorio=c.id_directorio AND a.id_usuario='".$_SESSION['sess_usu_id']."' ORDER BY a.fecha_coti DESC";
	$result_cot = $db->Execute($sql);
	
	/////////////////////////////
	
	while(!$result_cot->EOF){
		list($id_coti, $nombre_coti, $email_coti, $tele_coti, $mensaje_coti, $link_coti, $fecha_coti, $id_directorio, $estado_coti, 
		     $calificacion_coti, $id_usuario, $tipo_reg_coti, $total_mensajes, $total_noleidos) = select_format($result_cot->fields);
		
		if($total_noleidos>0){
			$total_noleidos='<span class="label label-warning">'.$total_noleidos.'</span>';
		}else{
			$total_noleidos='';
		}
		
		$fecha_coti = explode(" ", $fecha_coti);
        $fecha_coti = explode("-", $fecha_coti[0]);
		
		$color = "";
		$estado_final = "";
		
		if($estado_coti=="P"){
			$color = "#72BF24";			
			$estado_final = "Sin devolución";
		}elseif($estado_coti=="T"){
			$color = "#949ECF";		
			$estado_final = "Servicio finalizado";	
		}else{
			$color = "#ccc";
			$estado_final = "Cotización en proceso";	
		}
		///////////////////
		
		$sql = "SELECT id_respuesta, id_coti, id_usuario_envia, id_usuario_destino, fecha, respuesta, estado ,
					   (CONCAT(b.us_nombre,' ',b.us_last_name)) AS nom_envia, (CONCAT(c.us_nombre,' ',c.us_last_name)) AS nom_destino
		        FROM ic_respuesta_cotizacion a, ic_usuarios b, ic_usuarios c
				WHERE id_coti='" . $id_coti . "' AND a.id_usuario_envia=b.us_id AND a.id_usuario_destino=c.us_id ORDER BY id_respuesta DESC LIMIT 0,1";
    	$result_respuesta = $db->Execute($sql);
	
		list($id_respuesta, $id_coti, $id_usuario_envia, $id_usuario_destino, $fecha, $respuesta, $estado, $nom_envia, $nom_destino) = select_format($result_respuesta->fields);
		
		/*$origen = "";
		$linkmensajes = "";
		if($id_usuario_envia==$_SESSION['sess_usu_id']){
			$origen = "<strong>Yo:</strong> ";
			$linkmensajes = '<a href="javascript:;" onclick="document.mensajes'.$id_coti.'.submit();"><i class="fa fa-envelope-o" aria-hidden="true"></i> Ver mensajes</a>';
		}else{
			$origen = "<strong>".$nom_envia.":</strong> ";
			$linkmensajes = '<a href="javascript:;" onclick="document.mensajes'.$id_coti.'.submit();"><i class="fa fa-envelope-o" aria-hidden="true"></i> Contestar mensajes</a>';
		}*/
		
		$origen = "";
		if($id_usuario_envia==$_SESSION['sess_usu_id']){
			$origen = "Ver mensajes";
		}else{
			$origen = "Responder mensaje";
		}
		
		///////////////////
		
		$sql = "SELECT id_directorio, nombre, categoria, descripcion, direccion, ciudad, estado, zip, telefono, web, info_adicional, email, 
		 			  	sponsor, logo, latitud, longitud, orden, id_usuario, activo, destacado, img1, img2, img3, video, fecha, visualizaciones 
		 		FROM ic_directorio WHERE id_directorio='" . $id_directorio . "'";
    	$result = $db->Execute($sql);
	
		list($id_directorio, $nombre, $categoria, $descripcion, $direccion, $ciudad, $estado, $zip, $telefono, $web, $info_adicional, $email, $sponsor, $logo, $latitud, 
             $longitud, $orden,$id_usuario_directorio, $activo, $destacado, $img1, $img2, $img3, $video, $fecha, $visualizaciones) = select_format($result->fields);
		
		 if ($logo == "") {
			$logo = "images/publication.jpg";
		}
		
		///////////////////
		
		$sql="SELECT 
				 us_id, us_codigo_ref, us_nombre, us_pais, us_telefono, us_email, us_direccion, us_nacimiento,
				 us_last_name, us_sexo, us_descripcion, us_login, us_pass, us_postal, us_ciudad, us_estado_us,us_appartment,us_foto
			  FROM ic_usuarios
			  WHERE us_id ='".$id_usuario_directorio."' ";
				
		$result_usu=$db->Execute($sql);
	
		list($us_id, $us_codigo_ref, $us_nombre, $us_pais, $us_telefono, $us_email, $us_direccion, $us_nacimiento,
			 $us_last_name, $us_sexo, $us_descripcion, $us_login, $us_pass, $us_postal, $us_ciudad, $us_estado_us, $us_appartment,$us_foto)=select_format($result_usu->fields);
		
		if($us_foto==""){
			$us_foto = "images/profile.jpg";
		}
		 
		///////////////////
		
?>
		<div class="row" style="padding:15px 0 15px 0; border-bottom:10px solid <?=$color?>; margin:0 0 10px 0;">
                <div class="col-sm-2 col-xs-12 text-center">
                    <img src="<?= $logo ?>" style="width:100%" />
                </div>        
                <div class="col-sm-8 col-xs-12 text-left">
                    <h4><?= $nombre ?></h4>
                                        
                    <div style="margin:15px 0; border-top: 1px solid #ccc;"></div>
                    
                    <div style="color:#000; margin:10px 0 15px 0;">
                    	Solicitado el  <?= $fecha_coti[2] ?>/<?= $fecha_coti[1] ?> del <?= $fecha_coti[0] ?>
                        <br />
                        <strong>Estado de solicitud:</strong> <?=$estado_final?>
                        <br />
                    </div>
					
					<div style="margin:15px 0; border-top: 1px solid #ccc;"></div>
					
					Contactaste a:
					<div class="row">
						<div class="col-sm-6 col-xs-12">
							<?=$us_nombre?> <?=$us_last_name?><br>
							<?=$us_ciudad?> <?=$us_estado_us?>. <?=$us_postal?><br>
							<?=$us_direccion?>
						</div>
						<div class="col-sm-6 col-xs-12">
							<?=$us_email?><br>
							<?=$us_telefono?>								
						</div>
					</div>
					
                    <!--<div style="color:#000; margin:10px 0 15px 0;">
                    	<strong>Solicitud:</strong><br />
                        <span style="color:#888;"><?=$mensaje_coti?></span>
                        <br />
                        <strong>Mensajes:</strong> <?=$total_mensajes?> mensaje(s), <?=$total_noleidos?> sin leer.                        
                    </div>
                    <div style="color:#000; margin:10px 0 15px 0;">
                    	<strong>Último mensaje:</strong>
                        <br />
                        <?=$origen." ".substr(strip_tags($respuesta),0,50)."...<br><br>".$linkmensajes?>                     
                    </div>-->
					
                    <div style="margin:10px 0 15px 0;">
						<form action="user.php" method="post"  id="mensajes<?=$id_coti?>" name="mensajes<?=$id_coti?>">
							<input type="hidden" name="op_usu" value="mensajes" />
							<input type="hidden" name="tipo" value="cotizaciones" />
							<input type="hidden" name="id_cotizacion" value="<?=$id_coti?>" />
							<input type="hidden" name="id_directorio" value="<?=$id_directorio?>" />
							<button type="submit" class="btn btn-primary"><i class="fa fa-envelope-o" aria-hidden="true"></i> <?=$origen?> <?=$total_noleidos?></button>
						</form>
					</div>
					
                    <div style="margin:15px 0; border-top: 1px solid #ccc;"></div>
                    <div class="row">
                    	<div class="col-sm-6 col-xs-12">
                    <?php if($estado_coti=="A"){ ?>
                    <form action="user.php" method="post" >
                    	<input type="hidden" name="op_usu" value="calificar" />
                        <input type="hidden" name="tipo" value="completo" />
                        <input type="hidden" name="id_cotizacion" value="<?=$id_coti?>" />
                        <input type="hidden" name="id_directorio" value="<?=$id_directorio?>" />
                    	<button type="submit" class="btn btn-success" style="width:100%;"><i class="fa fa-check-square-o" aria-hidden="true"></i> Se termino el servicio, a calificar!</button>
                    </form> 
                    <?php } ?>
                    	</div>
                        <div class="col-sm-6 col-xs-12">
                    <?php if($estado_coti!="T"){ ?>
                     <form action="user.php" method="post" >
                    	<input type="hidden" name="op_usu" value="calificar" />
                        <input type="hidden" name="tipo" value="incompleto" />
                        <input type="hidden" name="id_cotizacion" value="<?=$id_coti?>" />
                        <input type="hidden" name="id_directorio" value="<?=$id_directorio?>" />
                    	<button type="submit" class="btn btn-danger" style="width:100%;"><i class="fa fa-sign-out" aria-hidden="true"></i> No se pudo completar el servicio!</button>
                    </form>                    
                    <?php } ?>
                    	</div>
                    </div>
                    <?php
						if($estado_coti=="T"){
							
							$sql = "SELECT id_repu, id_cotizacion, tipo, fecha, id_usuario, descripcion, calificacion FROM ic_reputacion WHERE id_cotizacion='".$id_coti."' AND id_usuario='".$id_usuario_coti."'";
							$result_califi = $db->Execute($sql);	
							
							/////////////////////////////////							
					?>
                    <div class="row">
                    	<div class="col-sm-6 col-xs-12 text-center" style="background:#f4f4f4; padding:10px 0;">
                        	<?php 
								if(!$result_califi->EOF){
									list($id_repu, $id_cotizacion, $tipo, $fecha, $id_usuario, $descripcion, $calificacion) = select_format($result_califi->fields);
							?>
                        	<?php if($calificacion==1 || $calificacion==2 || $calificacion==3 || $calificacion==4 || $calificacion==5){ ?><i class="fa fa-star" aria-hidden="true"></i><?php }else{ ?><i class="fa fa-star-o" aria-hidden="true"></i><?php } ?>
							<?php if($calificacion==2 || $calificacion==3 || $calificacion==4 || $calificacion==5){ ?><i class="fa fa-star" aria-hidden="true"></i><?php }else{ ?><i class="fa fa-star-o" aria-hidden="true"></i><?php } ?>
                            <?php if($calificacion==3 || $calificacion==4 || $calificacion==5){ ?><i class="fa fa-star" aria-hidden="true"></i><?php }else{ ?><i class="fa fa-star-o" aria-hidden="true"></i><?php } ?>
                            <?php if($calificacion==4 || $calificacion==5){ ?><i class="fa fa-star" aria-hidden="true"></i><?php }else{ ?><i class="fa fa-star-o" aria-hidden="true"></i><?php } ?>
                            <?php if($calificacion==5){ ?><i class="fa fa-star" aria-hidden="true"></i><?php }else{ ?><i class="fa fa-star-o" aria-hidden="true"></i><?php } ?>
                            <br />
                            <?=$tipo?>
                            <br />
                            <span style="font-style:italic; color:#7C4793;"><?=$descripcion?></span>
                            <?php 
								}else{
							?>
                            	Sin calificación.
                                <br />
                                Esperando Respuesta.
                                <br />
                                <span style="font-style:italic"><?=$us_nombre." ".$us_last_name?></span>
                            <?php	
								}
							?>
                        </div>
                    <?php
							$sql = "SELECT id_repu, id_cotizacion, tipo, fecha, id_usuario, descripcion, calificacion FROM ic_reputacion WHERE id_cotizacion='".$id_coti."' AND id_usuario='".$_SESSION['sess_usu_id']."'";
							$result_califi = $db->Execute($sql);						
							
							/////////////////////////////////
					?>
                        <div class="col-sm-6 col-xs-12 text-center" style="background:#f4f4f4; padding:10px 0;">
                        	<?php 
								if(!$result_califi->EOF){
									list($id_repu, $id_cotizacion, $tipo, $fecha, $id_usuario, $descripcion, $calificacion) = select_format($result_califi->fields);
							?>
                        	<?php if($calificacion==1 || $calificacion==2 || $calificacion==3 || $calificacion==4 || $calificacion==5){ ?><i class="fa fa-star" aria-hidden="true"></i><?php }else{ ?><i class="fa fa-star-o" aria-hidden="true"></i><?php } ?>
							<?php if($calificacion==2 || $calificacion==3 || $calificacion==4 || $calificacion==5){ ?><i class="fa fa-star" aria-hidden="true"></i><?php }else{ ?><i class="fa fa-star-o" aria-hidden="true"></i><?php } ?>
                            <?php if($calificacion==3 || $calificacion==4 || $calificacion==5){ ?><i class="fa fa-star" aria-hidden="true"></i><?php }else{ ?><i class="fa fa-star-o" aria-hidden="true"></i><?php } ?>
                            <?php if($calificacion==4 || $calificacion==5){ ?><i class="fa fa-star" aria-hidden="true"></i><?php }else{ ?><i class="fa fa-star-o" aria-hidden="true"></i><?php } ?>
                            <?php if($calificacion==5){ ?><i class="fa fa-star" aria-hidden="true"></i><?php }else{ ?><i class="fa fa-star-o" aria-hidden="true"></i><?php } ?>
                            <br />
                            <?=$tipo?>
                            <br />
                            <span style="font-style:italic; color:#7C4793;"><?=$descripcion?></span>
                            <?php 
								}else{
							?>
                                Sin calificación.
                                <br />
                                <a href="calificar.php?id_coti=<?=$id_coti?>&modo=prestador"><i class="fa fa-star" aria-hidden="true"></i> Califica al usuario.</a>
                                <br />
                                <span style="font-style:italic"><?=$_SESSION['sess_nombre']." ".$_SESSION['sess_last_nombre']?></span>
                                <br />
                            <?php	
								}
							?>
                        </div>
                        
                         <div class="col-sm-12 col-xs-12 text-right" style="padding:15px 0 0 0;">
                        	<a href="user.php?op_usu=denuncias&id_coti=<?=$id_coti?>&cotizacion=Cotización+No.<?=$id_coti?>&publicacion=<?= $nombre ?>" style="color:#900"><i class="fa fa-ban" aria-hidden="true"></i> Denunciar Calificación</a>
                        </div>
                    </div>
                    <?php
						}
					?>
                </div>  
                <div class="col-sm-2 col-xs-12 text-center">
                    Contactaste a:<br>
					<img src="<?= $us_foto ?>" style="width:50%; border-radius:300px;" />
                    <br><h4><?= $us_nombre ?> <?= $us_last_name ?></h4>                    
                </div>               
            </div>
<?php
		$result_cot->MoveNext();	
	}
?>
    </div>
</div>


