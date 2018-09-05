<?php
$variables_ver = variables_metodo("publicacion,cotizacion");
$publicacion= 				$variables_ver[0];
$cotizacion= 				$variables_ver[1];
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


<script language="javascript">
 
function enviar(campos){

	var camposObligatorios = campos.split(",");

	for(i=0;i<camposObligatorios.length;i++)
	{
		if(document.getElementById(camposObligatorios[i]).value==""){
			alert("Field "+ document.getElementById(camposObligatorios[i]).title +" <?=_OBLIGATORIO?>");
			return;
		}
	}
	document.denuncias.submit();
}
</script>


<div class="row">	    
    <div class="col-sm-12 col-xs-12 text-center" style="text-transform:uppercase; font-weight:700;">
    	<h1>DENUNCIAR PUBLICACIÓN</h1>
        <h4>Tuviste algun problema con un servicio, tenes dudas o sospechas sobre alguna publucación.<br />Escribinos, nos pondremos en contato.</h4>
        <br />
        <a href="contacto@worklife.com.ar">contacto@worklife.com.ar</a>
		<br /><br />
    </div> 
    <div class="col-sm-12 col-xs-12 text-center">
    </div> 
    <div class="col-sm-12 col-xs-12">
        <form action="enviar_denuncia_calificacion.php?id=<?=session_id()?>" method="post" name="denuncias" id="denuncias">
        <div style="margin:10px 0; width:49%; float:left;">
            <input name="nombre_completo" type="text" class="form-control" id="nombre_completo" value="<?php if(isset($_SESSION['sess_nombre'])){ echo $_SESSION['sess_nombre']." ".$_SESSION['sess_last_nombre']; } ?>" title="Nombre completo" placeholder="Nombre completo *"/>
        </div>    
        <div style="margin:10px 0; width:49%; float:right;">
        <input name="phone" type="text" class="form-control" id="phone"  title="Phone" placeholder="Teléfono"/>
        </div>             
        <div style="margin:10px 0; width:100%; float:right;">
            <input name="email" type="text" class="form-control" id="e_mail" title="Email" placeholder="Email *" value="<?php if(isset($_SESSION['sess_usu_email'])){ echo $_SESSION['sess_usu_email']; } ?>"/>
        </div>       
        <div style="margin:10px 0; width:49%; float:left;">
        <input name="publicacion" type="text" class="form-control" id="publicacion" title="Publicación" readonly="readonly" value="<?=$publicacion?>" placeholder="Publicación *"/>
        </div> 
        <div style="margin:10px 0; width:49%; float:right;">
        <input name="cotizacion" type="text" class="form-control" id="cotizacion" title="Cotiación" readonly="readonly" value="<?=$cotizacion?>" placeholder="Cotiación *"/>
        </div>                              
        <div style="margin:10px 0; width:100%;">
        <textarea name="comentarios"  rows="2" id="comentarios" class="form-control" title="Cual fue el problema?"  placeholder="Cual fue el problema? *"></textarea></div>               
        <div style="margin:10px 0; width:100%; text-align:right;">
        <?php generateRandString(4); capchat(); ?>
        </div>
        <div style="margin:11px 0; width:100%; text-align:right;">
        <input type="button" onClick="enviar('nombre_completo,e_mail,publicacion,comentarios');" name="Submit" value="Enviar" />
        </div>
        </form>
    </div>
</div>

