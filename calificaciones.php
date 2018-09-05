<?php
	$variables_ver = variables_metodo("id_cotizacion,mensaje_nuevo,id_directorio,tipo,funcion,calificacion");
	$id_coti= 				$variables_ver[0];
	$mensaje_nuevo= 		$variables_ver[1];
	$id_directorio= 		$variables_ver[2];
	$tipo= 					$variables_ver[3];
	$funcion= 				$variables_ver[4];
	$calificacion= 			$variables_ver[5];
	
	//////////////////////////////////
	
	if ($funcion == "guardar" && $id_coti!="" && $mensaje_nuevo!="" && $calificacion!="") {
		/* Funcion para guardar los datos del formulario */
        guardar($id_coti, $mensaje_nuevo, $id_directorio, $calificacion, $tipo, $db);
	}
	
	/************************************************************************************************************************** */

	function guardar($id_coti, $mensaje_nuevo, $id_directorio, $calificacion, $tipo, $db) {
		
		if($tipo=="incompleto"){
			$tipo="Incompleto";
		}else{
			$tipo="Confirmado";
		}
		
		$sql = "INSERT INTO ic_reputacion (id_cotizacion, tipo, fecha, id_usuario, descripcion, calificacion) 
				VALUES ('".$id_coti."',
						'".$tipo."',
						'".date("Y-m-d")."',
						'".$_SESSION['sess_usu_id']."',
						'".$mensaje_nuevo."',
						'".$calificacion."');";
		$result = $db->Execute($sql);
		
		/////////////////////	
		
		$sql = "UPDATE ic_cotizaciones SET estado_coti='T', calificacion_coti='".$calificacion."' WHERE id_coti='".$id_coti."'";
		$result = $db->Execute($sql);
		
		/////////////////////	
			
		echo"
		<br><br>
		<div align='center'><h1>Estamos procesando tu calificación!</h1></div>
		<br><br><br>
		<META HTTP-EQUIV='Refresh' CONTENT='0;URL=cotizaciones.html'>";
		die();			
	}
	
	/************************************************************************************************************************** */
	
	
	//////////////////////////////////
	
	
	$sql = "SELECT id_directorio, nombre, categoria, descripcion, direccion, ciudad, estado, zip, telefono, web, info_adicional, email, 
					sponsor, logo, latitud, longitud, orden, id_usuario, activo, destacado, img1, img2, img3, video, fecha, visualizaciones 
			FROM ic_directorio WHERE id_directorio='" . $id_directorio . "'";
	$result = $db->Execute($sql);

	list($id_directorio, $nombre, $categoria, $descripcion, $direccion, $ciudad, $estado, $zip, $telefono, $web, $info_adicional, $email, $sponsor, $logo, $latitud, 
		 $longitud, $orden,$id_usuario_directorio, $activo, $destacado, $img1, $img2, $img3, $video, $fecha, $visualizaciones) = select_format($result->fields);
	
	///////////////////
		
	$sql="SELECT 
			 us_id, us_codigo_ref, us_nombre, us_pais, us_telefono, us_email, us_direccion, us_nacimiento,
			 us_last_name, us_sexo, us_descripcion, us_login, us_pass, us_postal, us_ciudad, us_estado_us,us_appartment,us_foto
		  FROM ic_usuarios
		  WHERE us_id ='".$id_usuario_directorio."' ";
			
	$result_usu=$db->Execute($sql);

	list($us_id, $us_codigo_ref, $us_nombre, $us_pais, $us_telefono, $us_email, $us_direccion, $us_nacimiento,
		 $us_last_name, $us_sexo, $us_descripcion, $us_login, $us_pass, $us_postal, $us_ciudad, $us_estado_us, $us_appartment,$us_foto)=select_format($result_usu->fields);
		 
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

<h1><i class="fa fa-star" aria-hidden="true"></i> Calificar</h1>
<h2>Carlificar <?=(($tipo=="solicitudes")?"solicitud":"cotización")?> #<?=$id_coti?>: <?=$nombre?></h2>
<br />    
<br />


<!-- //////////////////////////////////////////////////////////////////////////////////////////// -->

<div class="row">
    <div class="col-sm-6 col-xs-12 text-center">
        <?php 
            $sql = "SELECT id_repu, id_cotizacion, tipo, fecha, id_usuario, descripcion, calificacion FROM ic_reputacion WHERE id_cotizacion='".$id_coti."' AND id_usuario='".$id_usuario_directorio."'";
            $result_califi = $db->Execute($sql);	
            if(!$result_califi->EOF){
                list($id_repu, $id_cotizacion, $tipo, $fecha, $id_usuario, $descripcion, $calificacion) = select_format($result_califi->fields);
        ?>
        <div  style="font-size:40px">
			<?php if($calificacion==1 || $calificacion==2 || $calificacion==3 || $calificacion==4 || $calificacion==5){ ?><i class="fa fa-star" aria-hidden="true"></i><?php }else{ ?><i class="fa fa-star-o" aria-hidden="true"></i><?php } ?>
            <?php if($calificacion==2 || $calificacion==3 || $calificacion==4 || $calificacion==5){ ?><i class="fa fa-star" aria-hidden="true"></i><?php }else{ ?><i class="fa fa-star-o" aria-hidden="true"></i><?php } ?>
            <?php if($calificacion==3 || $calificacion==4 || $calificacion==5){ ?><i class="fa fa-star" aria-hidden="true"></i><?php }else{ ?><i class="fa fa-star-o" aria-hidden="true"></i><?php } ?>
            <?php if($calificacion==4 || $calificacion==5){ ?><i class="fa fa-star" aria-hidden="true"></i><?php }else{ ?><i class="fa fa-star-o" aria-hidden="true"></i><?php } ?>
            <?php if($calificacion==5){ ?><i class="fa fa-star" aria-hidden="true"></i><?php }else{ ?><i class="fa fa-star-o" aria-hidden="true"></i><?php } ?>
        </div>
        <br />
		<div style="font-size:20px;" id="mensajeValoracion">Valorá la prestación del servicio</div>
        <br />
        <div style="font-size:20px; text-transform:uppercase;">ESTADO TRANSACCIÓN: <?=$tipo?></div>
        <br />
        <div style="font-style:italic; color:#7C4793;"><?=$descripcion?></div>
        <br />
        <div style="font-style:italic"><?=$_SESSION['sess_nombre']." ".$_SESSION['sess_last_nombre']?></div>
		
        <?php 
            }else{
        ?>
            <div style="font-size:20px;">SELECCIONA LAS ESTRELLAS QUE DESEAS OTORGAR AL USUARIO POR SU SERVICIO</div>
			<br />
			<a href="javascript:;" onclick="asignarCalificacion('1');" onmouseup="mostrarCalificacion('1');" id="cal1" style="font-size:40px"><i class="fa fa-star" aria-hidden="true"></i></a>
            <a href="javascript:;" onclick="asignarCalificacion('2');" onmouseup="mostrarCalificacion('2');" id="cal2" style="font-size:40px"><i class="fa fa-star" aria-hidden="true"></i></a>
            <a href="javascript:;" onclick="asignarCalificacion('3');" onmouseup="mostrarCalificacion('3');" id="cal3" style="font-size:40px"><i class="fa fa-star" aria-hidden="true"></i></a>  
        	<a href="javascript:;" onclick="asignarCalificacion('4');" onmouseup="mostrarCalificacion('4');" id="cal4" style="font-size:40px"><i class="fa fa-star" aria-hidden="true"></i></a>
            <a href="javascript:;" onclick="asignarCalificacion('5');" onmouseup="mostrarCalificacion('5');" id="cal5" style="font-size:40px"><i class="fa fa-star" aria-hidden="true"></i></a>
            <br />
			<div style="font-size:20px;" id="mensajeValoracion">-- Sin Calificación --</div>
			<br />
			<div style="font-size:23px; text-transform:uppercase;">ESTADO TRANSACCIÓN: <?=$tipo?></div>
            <br /> 
            <form action="" method="post" name="enviarcalificacion">
           		<input type="hidden" name="op_usu" value="calificar" />
                <input type="hidden" name="funcion" value="guardar" />
                <input type="hidden" name="tipo" value="<?=$tipo?>" />
                <input type="hidden" name="calificacion" id="calificacion" value="" />
                <input type="hidden" name="id_cotizacion" value="<?=$id_coti?>" />
                <input type="hidden" name="id_directorio" value="<?=$id_directorio?>" />
                <textarea name="mensaje_nuevo" id="mensaje_nuevo" class="form-control"></textarea>
                <button type="button" onclick="enviarCalificacion();" class="btn btn-success" style="width:100%;"><i class="fa fa-check-square-o" aria-hidden="true"></i> Calificar!</button>
            </form>
            <br />
            
            <script>
				function asignarCalificacion(valor){
					$("#calificacion").val(valor);
				}
				
				/////////////////////////////
				
				function mostrarCalificacion(valor){
					//MALO, REGULAR, NORMAL, MUY BUENO, Y EXCELENTE
					
					if(valor=="1"){
						$("#cal1").css("color", "#FAA532 !important");
						$("#cal2").css("color", "#7C4793 !important");
						$("#cal3").css("color", "#7C4793 !important");
						$("#cal4").css("color", "#7C4793 !important");
						$("#cal5").css("color", "#7C4793 !important");
						
						$("#mensajeValoracion").html('<span style="color:#ff0000;">Valoración: Malo</span>');
					}
					if(valor=="2"){
						$("#cal1").css("color", "#FAA532 !important");
						$("#cal2").css("color", "#FAA532 !important");
						$("#cal3").css("color", "#7C4793 !important");
						$("#cal4").css("color", "#7C4793 !important");
						$("#cal5").css("color", "#7C4793 !important");
						
						$("#mensajeValoracion").html('<span style="color:#ff0000;">Valoración: Regular</span>');
					}
					if(valor=="3"){
						$("#cal1").css("color", "#FAA532 !important");
						$("#cal2").css("color", "#FAA532 !important");
						$("#cal3").css("color", "#FAA532 !important");
						$("#cal4").css("color", "#7C4793 !important");
						$("#cal5").css("color", "#7C4793 !important");
						
						$("#mensajeValoracion").html('<span style="color:#D96D00;">Valoración: Normal</span>');
					}
					if(valor=="4"){
						$("#cal1").css("color", "#FAA532 !important");
						$("#cal2").css("color", "#FAA532 !important");
						$("#cal3").css("color", "#FAA532 !important");
						$("#cal4").css("color", "#FAA532 !important"); 
						$("#cal5").css("color", "#7C4793 !important");
						
						$("#mensajeValoracion").html('<span style="color:#008C00;">Valoración: Muy bueno</span>');
					}
					if(valor=="5"){
						$("#cal1").css("color", "#FAA532 !important");
						$("#cal2").css("color", "#FAA532 !important");
						$("#cal3").css("color", "#FAA532 !important");
						$("#cal4").css("color", "#FAA532 !important");
						$("#cal5").css("color", "#FAA532 !important");
						
						$("#mensajeValoracion").html('<span style="color:#008C00;">Valoración: Excelente</span>');
					}
				}
				
				/////////////////////////////
				
				function enviarCalificacion(){
					if($("#mensaje_nuevo").val()==""){
						var prom = ezBSAlert({
						  messageText: "Debes dejar un comentario sobre la operación!",
						  alertType: "danger"
						});
						
						return;
					}
					
					if($("#calificacion").val()==""){
						var prom = ezBSAlert({
						  messageText: "Debes colocar una calificacion del servicio!",
						  alertType: "danger"
						});
						
						return;
					}
					
					document.enviarcalificacion.submit();
				}
			</script>
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
        <div style="font-size:20px;">USUARIO QUE LO CALIFICO, ESTRELLAS Y CALIFICACION OTORGADA.</div>
		<br>
		<div style="font-size:40px">
		<?php if($calificacion==1 || $calificacion==2 || $calificacion==3 || $calificacion==4 || $calificacion==5){ ?><i class="fa fa-star" aria-hidden="true"></i><?php }else{ ?><i class="fa fa-star-o" aria-hidden="true"></i><?php } ?>
        <?php if($calificacion==2 || $calificacion==3 || $calificacion==4 || $calificacion==5){ ?><i class="fa fa-star" aria-hidden="true"></i><?php }else{ ?><i class="fa fa-star-o" aria-hidden="true"></i><?php } ?>
        <?php if($calificacion==3 || $calificacion==4 || $calificacion==5){ ?><i class="fa fa-star" aria-hidden="true"></i><?php }else{ ?><i class="fa fa-star-o" aria-hidden="true"></i><?php } ?>
        <?php if($calificacion==4 || $calificacion==5){ ?><i class="fa fa-star" aria-hidden="true"></i><?php }else{ ?><i class="fa fa-star-o" aria-hidden="true"></i><?php } ?>
        <?php if($calificacion==5){ ?><i class="fa fa-star" aria-hidden="true"></i><?php }else{ ?><i class="fa fa-star-o" aria-hidden="true"></i><?php } ?>
        </div>
		<div style="font-size:20px;" id="mensajeValoracion">
		<?php if($calificacion==0 || $calificacion==""){ echo "-- Sin Valoración --"; } ?>
		<?php if($calificacion==1){ echo '<span style="color:#ff0000;">Valoración: Malo</span>'; } ?>
		<?php if($calificacion==2){ echo '<span style="color:#ff0000;">Valoración: Regular</span>'; } ?>
		<?php if($calificacion==3){ echo '<span style="color:#D96D00;">Valoración: Normal</span>'; } ?>
		<?php if($calificacion==4){ echo '<span style="color:#008C00;">Valoración: Muy bueno</span>'; } ?>
		<?php if($calificacion==5){ echo '<span style="color:#008C00;">Valoración: Excelente</span>'; } ?>
		</div>
		<br />
        <br />
        <div style="font-size:23px; text-transform:uppercase;">ESTADO TRANSACCIÓN<?=$tipo?></div>
        <br />
        <span style="font-style:italic; color:#7C4793;"><?=$descripcion?></span>
        <?php 
            }else{
        ?>
            <div style="font-size:20px;">USUARIO QUE LO CALIFICO, ESTRELLAS Y CALIFICACION OTORGADA.</div>
			<br>
			<a href="javascript:;" style="font-size:40px"><i class="fa fa-star-o" aria-hidden="true"></i></a>
            <a href="javascript:;" style="font-size:40px"><i class="fa fa-star-o" aria-hidden="true"></i></a>
            <a href="javascript:;" style="font-size:40px"><i class="fa fa-star-o" aria-hidden="true"></i></a>  
        	<a href="javascript:;" style="font-size:40px"><i class="fa fa-star-o" aria-hidden="true"></i></a>
            <a href="javascript:;" style="font-size:40px"><i class="fa fa-star-o" aria-hidden="true"></i></a>
            <br />
			<div style="font-size:20px;" id="mensajeValoracion">
			<?php if($calificacion==0 || $calificacion==""){ echo "-- Sin Valoración --"; } ?>
			<?php if($calificacion==1){ echo '<span style="color:#ff0000;">Valoración: Malo</span>'; } ?>
			<?php if($calificacion==2){ echo '<span style="color:#ff0000;">Valoración: Regular</span>'; } ?>
			<?php if($calificacion==3){ echo '<span style="color:#ff0000;">Valoración: Normal</span>'; } ?>
			<?php if($calificacion==4){ echo '<span style="color:#ff0000;">Valoración: Muy bueno</span>'; } ?>
			<?php if($calificacion==5){ echo '<span style="color:#ff0000;">Valoración: Excelente</span>'; } ?>
			</div>
			<br />
            <div style="font-size:23px; text-transform:uppercase;">Sin calificación.</div>
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