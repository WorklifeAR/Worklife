<?php if(isset($_SESSION['reseller'])){ echo '<META HTTP-EQUIV="Refresh" CONTENT="0;URL='.$_SESSION['c_base_location'].'reseller-info.html">'; die(); } ?>

<div align="center" id="Contenido_archivo">
        
<?php

	if( (!isset($_SESSION['usuario'])) && ($op_usu=="") )
	{
?>
<br />
<h1>Registraté o Inicia sesión</h1>
<h2>Empezá ya en el mundo de profesionales Worklife</h2>
<br />
<div style="margin:0 auto; max-width:600px;">
    <div class="row">
        <div class="col-sm-6 col-xs-12">
        	<div style="text-align:center; color:#fff; font-weight:700; background:#06C; padding:15px 0;">
            	<i class="fa fa-facebook" aria-hidden="true"></i> Iniciar sesion con Facebook
            </div>
        </div>
        
        <div class="col-sm-6 col-xs-12">
        	<div style="text-align:center; color:#fff; font-weight:700; background:#C30; padding:15px 0;">
            	<i class="fa fa-google-plus" aria-hidden="true"></i> Iniciar sesion con Google
            </div>
        </div>
        <div class="col-sm-12 col-xs-12">
        	<br />
        </div>
        <form action="" method="post" name="login" id="login" >
        <div class="col-sm-12 col-xs-12">
        	<input type="text" name="us_login" id="us_login" class="form-control" placeholder="Email" />
        </div>
        <div class="col-sm-12 col-xs-12">
        	<input type="password" name="us_pass" id="us_pass" class="form-control" placeholder="Password" />
        </div>
        <div class="col-sm-12 col-xs-12">
        	<input type="hidden" name="funcion" value="login_usuario" />
            <input type="button" name="Submit" value="ACCEDER" id="acceso" style="width:100%; margin:20px auto;"/>
        </div>
        </form>
        
        <div class="col-sm-12 col-xs-12 text-center">
        	<a href="recordar-clave.html">[ Recordar mi password ]</a>
        </div>
    </div>
</div>
<br />
<script>

$(document).ready(function() {
    $("#acceso").click(function(){
        var email = $("#us_login").val();
		var passw = $("#us_pass").val();
 		var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
		
        if(email != "" && passw != "")
        {
            if(!emailReg.test( email ))
            {
               alert("Debe ingresar un email válido!");  
            }else{
				document.login.submit();
			}
        } else {
        	alert("Debe completar los campos para continuar!");  
        }
    });
});


</script>
<?php
   }elseif($op_usu=="datos_usuario"){	

	
	if(isset($_SESSION['sess_usu_id'])){
	 
	 	include ("conexion.php");
		
		$sql="SELECT 
				 us_id, us_codigo_ref, us_nombre, us_pais, us_telefono, us_email, us_direccion, us_nacimiento,
				 us_last_name, us_sexo, us_descripcion, us_login, us_pass, us_postal, us_ciudad, us_estado_us,us_appartment,us_foto
			  FROM ic_usuarios
			  WHERE us_id ='".$_SESSION['sess_usu_id']."' ";
				
		$result=$db->Execute($sql);
	
		list($us_id, $us_codigo_ref, $us_nombre, $us_pais, $us_telefono, $us_email, $us_direccion, $us_nacimiento,
			 $us_last_name, $us_sexo, $us_descripcion, $us_login, $us_pass, $us_postal, $us_ciudad, $us_estado_us, $us_appartment,$us_foto)=select_format($result->fields);
	 } 
	 
	 if($us_foto==""){
		 $us_foto = "images/profile.jpg";
	 }
?>
<script type="text/javascript">

function enviar(campos,campos1)
{
	var camposObligatorios = campos.split(",");
	
	for(i=0;i<camposObligatorios.length;i++)
	{	
		if(document.getElementById(camposObligatorios[i]).value==""){
			alert("Campo "+ document.getElementById(camposObligatorios[i]).title +" es obligatoria.");
			return;
		}
	}

	document.cambio_datos.submit();
}

</script>
<br />
<h1>Administrar mi Perfil</h1>
<br />

<div class="row">
        <div class="col-sm-2 col-xs-6">
        	<div style="text-align:center; width:100%; background:#FAA532; padding:8px 0; margin:5px 0;">
            	<a href="info-usuario.html" style="color:#fff; font-size:18px;"><i class="fa fa-user" aria-hidden="true"></i> Mi perfil</a>
            </div>            
        </div>        
        <div class="col-sm-2 col-xs-6">
        	<div style="text-align:center; width:100%; background:#7C4793; padding:8px 0; margin:5px 0;">
            	<a href="mis-publicaciones.htm" style="color:#fff; font-size:18px;"><i class="fa fa-list" aria-hidden="true"></i> Publicaciones</a>
            </div>
        </div>  
        <div class="col-sm-2 col-xs-6">
        	<div style="text-align:center; width:100%; background:#7C4793; padding:8px 0; margin:5px 0;">
            	<a href="cotizaciones.htm" style="color:#fff; font-size:18px;"><i class="fa fa-list-alt" aria-hidden="true"></i> Cotizaciones</a>
            </div>
        </div>         
        <div class="col-sm-2 col-xs-6">
        	<div style="text-align:center; width:100%; background:#7C4793; padding:8px 0; margin:5px 0;">
            	<a href="publicaciones-favoritas.htm" style="color:#fff; font-size:18px;"><i class="fa fa-thumbs-up" aria-hidden="true"></i> Mi favoritos</a>
            </div>
        </div>     
        <div class="col-sm-2 col-xs-6">
        	<div style="text-align:center; width:100%; background:#7C4793; padding:8px 0; margin:5px 0;">
            	<a href="mi-reputacion.htm" style="color:#fff; font-size:18px;"><i class="fa fa-check-circle-o" aria-hidden="true"></i>  Mi Reputación</a>
            </div>
        </div>  
        
        <div class="col-sm-2 col-xs-6">
        	<div style="text-align:center; width:100%; background:#7C4793; padding:8px 0; margin:5px 0;">
            	<a href="planes-worklife.htm" style="color:#fff; font-size:18px;"><i class="fa fa-users" aria-hidden="true"></i>  Cambiar mi plan</a>
            </div>
        </div>  
    </div>
<br />
<h2>Datos de usuario</h2>
<br /><br />
<form action="" method="post" name="cambio_datos" id="cambio_datos" enctype="multipart/form-data">
<div style="margin:0 auto;">
    <div class="row">
        <div class="col-sm-2 col-xs-12 text-center">
        	<img src="<?=$us_foto?>" height="100" style="border-radius:100px;" />
        </div>        
        <div class="col-sm-4 col-xs-12 text-left">
        	Foto de perfil
            <input type="file" name="us_foto" id="us_foto" class="form-control"  />
        </div>
         <div class="col-sm-6 col-xs-12 text-left">
        	Bienvenido a Worklife, recordá que tu información es personal, solo los usuarios que realicen una solicitud podrán ver parte de la información.
        </div>
         <div class="col-sm-12 col-xs-12 text-left">
        	<div style="width:100%; margin:20px 0; border-top:3px solid #eee;"></div>
        </div>
    </div>
	<div class="row">
        <div class="col-sm-6 col-xs-12 text-left">
        	Nombre(s) *
            <input name="us_nombre" type="text" id="us_nombre" class="form-control" title="Nombre" placeholder="Nombre" value="<?=$us_nombre?>" />
        </div>
        
        <div class="col-sm-6 col-xs-12 text-left">
        	Apellido(s) *
            <input name="us_last_name" type="text" id="us_last_name" class="form-control" title="Nombre" placeholder="Apellido" value="<?=$us_last_name?>" />
        </div>
        
        <div class="col-sm-6 col-xs-12 text-left">
        	Dirección *
            <input name="us_direccion" type="text" id="us_direccion" class="form-control" title="Dirección" placeholder="Dirección" value="<?=$us_direccion?>" />
        </div>
        
        <div class="col-sm-6 col-xs-12 text-left">
        	Ciudad *
            <input name="us_ciudad" type="text" id="us_ciudad" class="form-control" title="Ciudad" placeholder="Ciudad" value="<?=$us_ciudad?>" />
        </div>
        
        <div class="col-sm-6 col-xs-12 text-left">
        	Teléfono
            <input name="us_telefono" type="text" id="us_telefono" class="form-control" title="Teléfono" placeholder="Teléfono" value="<?=$us_telefono?>" />
        </div>
        
        <div class="col-sm-6 col-xs-12 text-left">
        	Código Postal
            <input name="us_postal" type="text" id="us_postal" class="form-control" title="Código Postal" placeholder="Código Postal" value="<?=$us_postal?>" />
        </div>
        
        <div class="col-sm-6 col-xs-12 text-left">
        	Fecha Nacimiento
            <input name="us_nacimiento" type="text" id="us_nacimiento" class="form-control" title="Fecha Nacimiento" placeholder="Fecha Nacimiento" value="<?=$us_nacimiento?>" />
        </div>
        
        <div class="col-sm-6 col-xs-12 text-left">
        	Genero
            <select name="us_sexo" id="us_sexo" class="form-control" title="Genero" placeholder="Genero" value="<?=$us_sexo?>">
            	<?=cargar_lista_estatica("H,M","Masculino,Femenino","1",$us_sexo)?>
            </select>
        </div>
        
        <div class="col-sm-12 col-xs-12 text-left">
        	<div style="width:100%; margin:20px 0; border-top:3px solid #eee;"></div>
        </div>
        
        <div class="col-sm-6 col-xs-12 text-left">
        	Email *
            <input name="us_email" type="text" id="us_email" class="form-control" title="Email" placeholder="Email" value="<?=$us_email?>" />
        </div>
        
        <div class="col-sm-6 col-xs-12 text-left">
        	Password *
        	<input name="us_pass" type="password" id="us_pass" class="form-control" title="Password" placeholder="Password" value="<?=$us_pass?>" />
        </div>
        
        <div class="col-sm-12 col-xs-12 text-left">
        	<div style="width:100%; margin:20px 0; border-top:3px solid #eee;"></div>
        </div>
        
        <div class="col-sm-6 col-xs-12 text-left">
        	* Campos obligatorios
        </div>
        
        <div class="col-sm-6 col-xs-12 text-right">
        	<input type="hidden" name="us_id" value="<?=$us_id?>">
            <input type="hidden" name="funcion" value="modificar_usuario">
            <input type="button" onClick="enviar('us_nombre,us_last_name,us_direccion,us_ciudad,us_email,us_pass'); " name="guardar" value="Guardar Informacón Personal" class="boton" />
        </div>
    </div>
</div>
</form>
   
   <script>
   	$(window).load(function() { 
		$('#us_nacimiento').datepicker({
			dateFormat: 'yy-mm-dd'	
		});
	
	 });
   </script>
<?php	
   
	}elseif( (!isset($_SESSION['usuario'])) && ($op_usu=="recordar_password") ){
		
		/*
?>
	   
	   <table width="80%" border="0" align="center" cellpadding="5" cellspacing="0">
	   <form action="" method="post" name="enviar_cuenta" id="enviar_cuenta" >
  <tr>
    <td><h1><?=_RECORDAR_PASS?></h1></td>
  </tr>
  <tr>
    <td><?=_MSG_RECORDAR_PASS?> </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="center"><label>
      <?=_EMAIL?>: 
      <input name="correo_recordar" type="text" id="correo_recordar" size="35" />
	  <input type="hidden" name="funcion" value="recordar_cuenta">
      <input name="Enviar" type="submit" class="boton" value="<?=_ENVIAR?>" />
    </label></td>
  </tr>
  </form>
</table>
<?php	   

*/
	}elseif(isset($_SESSION['usuario'])){
		
	
?>
<br />
<h1>Bienvenido a tu cuenta!</h1>
<h2>Hola <?=$_SESSION['sess_nombre']." ".$_SESSION['sess_last_nombre']?>, estas son tus opciones</h2>
<br />
<div style="margin:0 auto;">
    <div class="row">
        <div class="col-sm-4 col-xs-12">
        	<div style="text-align:center; background:#7C4793; padding:25px 0; margin:5px 0;">
            	<a href="info-usuario.html" style="color:#fff; font-weight:700;"><i class="fa fa-user" aria-hidden="true"></i> Mi perfil</a>
            </div>
<?php
	$sql="SELECT 
			 us_id, us_codigo_ref, us_nombre, us_pais, us_telefono, us_email, us_direccion, us_nacimiento,
			 us_last_name, us_sexo, us_descripcion, us_login, us_pass, us_postal, us_ciudad, us_estado_us,us_appartment
		  FROM ic_usuarios
		  WHERE us_id ='".$_SESSION['sess_usu_id']."' ";
			
	$result=$db->Execute($sql);

	list($us_id, $us_codigo_ref, $us_nombre, $us_pais, $us_telefono, $us_email, $us_direccion, $us_nacimiento,
		 $us_last_name, $us_sexo, $us_descripcion, $us_login, $us_pass, $us_postal, $us_ciudad, $us_estado_us, $us_appartment)=select_format($result->fields);
		 
		 if($us_nombre=="" || $us_last_name=="" || $us_ciudad=="" || $us_sexo=="" || $us_nacimiento==""){
			 echo '<div style="margin:10px 0; min-height:150px; width:100%;">Debes completar tus datos de pefil, ésto genera mayor confianza en tus clientes!</div>';
		 }else{
			 echo '<div style="margin:10px 0; min-height:150px; width:100%;"> Felicitaciones, <i class="fa fa-thumbs-o-up" aria-hidden="true"></i> Tus datos estan completos!</div>'; 
		 }	
?>
            
        </div>        
        <div class="col-sm-4 col-xs-12">
        	<div style="text-align:center; color:#fff; font-weight:700; background:#7C4793; padding:25px 0; margin:5px 0;">
            	<a href="mis-publicaciones.html" style="color:#fff; font-weight:700;"><i class="fa fa-list" aria-hidden="true"></i> Mis Publicaciones</a>
            </div>
            
<?php
	$sql="SELECT nombre,visualizaciones,fecha FROM ic_directorio WHERE id_usuario ='".$_SESSION['sess_usu_id']."' ORDER BY id_directorio DESC LIMIT 0,1";			
	$result=$db->Execute($sql);
		 
	if(!$result->EOF){
		list($nombre,$visualizaciones,$fecha)=select_format($result->fields);
		
		echo '<div style="margin:10px 0; min-height:150px; width:100%;">Tu última publicación: '.$nombre.', '.$visualizaciones.' veces vista.</div>';
	}else{
		echo '<div style="margin:10px 0; min-height:150px; width:100%;"> <i class="fa fa-hand-o-right" aria-hidden="true"></i> No tienes publicaciones, empezá ya!</div>'; 
	}	
?>
        </div>  
        <div class="col-sm-4 col-xs-12">
        	<div style="text-align:center; color:#fff; font-weight:700; background:#7C4793; padding:25px 0; margin:5px 0;">
            	<a href="cotizaciones.html" style="color:#fff; font-weight:700;"><i class="fa fa-list-alt" aria-hidden="true"></i> Cotizaciones</a>
            </div>
            
<?php
	$sql="SELECT a.id_coti, count(id_respuesta) FROM ic_cotizaciones a, ic_directorio b, ic_respuesta_cotizacion c 
	      WHERE id_usuario ='".$_SESSION['sess_usu_id']."' AND a.id_directorio=b.id_directorio GROUP BY a.id_coti";			
	$result=$db->Execute($sql);
		 
	if(!$result->EOF){
		$respuestas = 0;
		
		while(!$result->EOF){
			list($id_coti,$id_respuesta)=select_format($result->fields);
			$respuestas += $id_respuesta;
			$result->MoveNext();
		}
		
		if($respuestas==0){
			echo '<div style="margin:10px 0; min-height:150px; width:100%;">No tienes respuestas pendientes.</div>';
		}else{
			echo '<div style="margin:10px 0; min-height:150px; width:100%;">Tienes '.$id_respuesta.' cotizaciones.</div>';
		}
	}else{
		echo '<div style="margin:10px 0; min-height:150px; width:100%;"> <i class="fa fa-check-square-o" aria-hidden="true"></i> No preguntas ni respuestas pendientes!</div>'; 
	}	
?>
        </div> 
        
        <div class="col-sm-4 col-xs-12">
        	<div style="text-align:center; color:#fff; font-weight:700; background:#7C4793; padding:25px 0; margin:5px 0;">
            	<a href="publicaciones-favoritas.html" style="color:#fff; font-weight:700;"><i class="fa fa-thumbs-up" aria-hidden="true"></i> Mi favoritos</a>
            </div>

<?php
	$sql="SELECT count(id_fav) FROM ic_favoritos WHERE id_usuario ='".$_SESSION['sess_usu_id']."'";			
	$result=$db->Execute($sql);
		 
	list($total)=select_format($result->fields);
	
	echo '<div style="margin:10px 0; min-height:150px; width:100%;">Tienes '.$total.' publicaciones favoritas.</div>';

?>
        </div>     
        <div class="col-sm-4 col-xs-12">
        	<div style="text-align:center; color:#fff; font-weight:700; background:#7C4793; padding:25px 0; margin:5px 0;">
            	<a href="mi-reputacion.html" style="color:#fff; font-weight:700;"><i class="fa fa-check-circle-o" aria-hidden="true"></i>  Mi Reputación</a>
            </div>
            
<?php
		$sql="SELECT count(calificacion_coti) FROM ic_cotizaciones a, ic_directorio b  WHERE b.id_usuario ='".$_SESSION['sess_usu_id']."'";			
	$result=$db->Execute($sql);
		 
	list($total)=select_format($result->fields);
	
	echo '<div style="margin:10px 0; min-height:150px; width:100%;">Tienes '.$total.' calificaciones.</div>';

?>
        </div>  
        
        <div class="col-sm-4 col-xs-12">
        	<div style="text-align:center; color:#fff; font-weight:700; background:#7C4793; padding:25px 0; margin:5px 0;">
            	<a href="planes-worklife.html" style="color:#fff; font-weight:700;"><i class="fa fa-users" aria-hidden="true"></i>  Cambiar mi plan</a>
            </div>
            
<?php
	$sql="SELECT plan_wl FROM ic_usuarios WHERE us_id ='".$_SESSION['sess_usu_id']."'";			
	$result=$db->Execute($sql);		 
	list($plan)=select_format($result->fields);
	
	echo '<div style="margin:10px 0; min-height:150px; width:100%;">Tu plan actual es "'.$plan.'".</div>';

?>
        </div>  
    </div>
</div>
<br />

<?php	
	}
?>   
</div>