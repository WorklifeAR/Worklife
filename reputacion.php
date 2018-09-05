<?php
 
include ("conexion.php");

if(!empty($_SESSION['sess_usu_id'])) {
    $sql = "SELECT a.tipo, a.fecha, a.descripcion, a.calificacion, z.us_nombre, a.id_usuario
            FROM ic_reputacion a, ic_cotizaciones b, ic_usuarios z
            WHERE a.id_cotizacion=b.id_coti AND b.id_usuario ='" . $_SESSION['sess_usu_id'] . "' AND a.id_usuario=z.us_id	
			UNION
			SELECT a.tipo, a.fecha, a.descripcion, a.calificacion, z.us_nombre, a.id_usuario
            FROM ic_reputacion a, ic_cotizaciones b, ic_directorio c, ic_usuarios z
            WHERE a.id_cotizacion=b.id_coti AND b.id_directorio=c.id_directorio AND c.id_usuario ='" . $_SESSION['sess_usu_id'] . "' AND a.id_usuario=z.us_id		
	";

    $result = $db->Execute($sql);
	
	////////////////////////////////////////
	
	$sql = "SELECT AVG(calificacion) FROM (SELECT a.calificacion
            FROM ic_reputacion a, ic_cotizaciones b
            WHERE a.id_cotizacion=b.id_coti AND b.id_usuario ='" . $_SESSION['sess_usu_id'] . "' 
			UNION
			SELECT a.calificacion
            FROM ic_reputacion a, ic_cotizaciones b, ic_directorio c
            WHERE a.id_cotizacion=b.id_coti AND b.id_directorio=c.id_directorio AND c.id_usuario ='" . $_SESSION['sess_usu_id'] . "') cali";
    $media_calificacion = $db->Execute($sql);
	list($promedio) =  select_format($media_calificacion->fields);
	
	$promedio = round($promedio);
	
	$color="";
	
	if($calificacion>=4){
		$color="#008C00";
	}elseif($calificacion<=2){
		$color="#ff0000";
	}else{
		$color="#D96D00";
	}
?>

<style>
    .start{font-size:60px;}
    
    @media screen and (max-width: 600px) {
        .start{font-size:40px;}
    }

</style>

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
        <div style="text-align:center; width:100%; background:#7C4793; padding:3px 0; margin:5px 0;">
            <a href="cotizaciones.html" style="color:#fff; font-size:16px;"><i class="fa fa-list-alt" aria-hidden="true"></i> Cotizaciones</a>
        </div>
    </div>         
    <div class="col-sm-2 col-xs-6">
        <div style="text-align:center; width:100%; background:#7C4793; padding:3px 0; margin:5px 0;">
            <a href="publicaciones-favoritas.html" style="color:#fff; font-size:16px;"><i class="fa fa-heart" aria-hidden="true"></i> Mi favoritos</a>
        </div>
    </div>     
    <div class="col-sm-2 col-xs-6">
        <div style="text-align:center; width:100%; background:#FAA532; padding:3px 0; margin:5px 0;">
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

<h1><i class="fa fa-star" aria-hidden="true"></i> Mi Reputación</h1>
<h2>Calificaciones de los usuarios y de operaciones</h2>

<br /><br />

<div class="row" style="margin:10px 0;">
    <div class="col-sm-12 col-xs-12 text-center start">
		<div style="color:<?=$color?>;">
			<?php if($promedio==1 || $promedio==2 || $promedio==3 || $promedio==4 || $promedio==5){ ?><i class="fa fa-star" aria-hidden="true"></i><?php }else{ ?><i class="fa fa-star-o" aria-hidden="true"></i><?php } ?>
			<?php if($promedio==2 || $promedio==3 || $promedio==4 || $promedio==5){ ?><i class="fa fa-star" aria-hidden="true"></i><?php }else{ ?><i class="fa fa-star-o" aria-hidden="true"></i><?php } ?>
			<?php if($promedio==3 || $promedio==4 || $promedio==5){ ?><i class="fa fa-star" aria-hidden="true"></i><?php }else{ ?><i class="fa fa-star-o" aria-hidden="true"></i><?php } ?>
			<?php if($promedio==4 || $promedio==5){ ?><i class="fa fa-star" aria-hidden="true"></i><?php }else{ ?><i class="fa fa-star-o" aria-hidden="true"></i><?php } ?>
			<?php if($promedio==5){ ?><i class="fa fa-star" aria-hidden="true"></i><?php }else{ ?><i class="fa fa-star-o" aria-hidden="true"></i><?php } ?>
		</div>
		<div style="font-size:20px;margin:10px 0;" id="mensajeValoracion">
			<?php if($promedio==0 || $promedio==""){ echo "-- Sin Valoración --"; } ?>
			<?php if($promedio==1){ echo '<span style="color:#ff0000;">Valoración: Malo</span>'; } ?>
			<?php if($promedio==2){ echo '<span style="color:#ff0000;">Valoración: Regular</span>'; } ?>
			<?php if($promedio==3){ echo '<span style="color:#D96D00;">Valoración: Normal</span>'; } ?>
			<?php if($promedio==4){ echo '<span style="color:#008C00;">Valoración: Muy bueno</span>'; } ?>
			<?php if($promedio==5){ echo '<span style="color:#008C00;">Valoración: Excelente</span>'; } ?>
		</div>
    </div>
</div>
<br><br>
<?php

    if(!$result->EOF) {
        while (!$result->EOF) {
            list($tipo, $fecha, $descripcion, $calificacion, $nombre, $id_usuario) = 
                    select_format($result->fields);
			$color="";
			
			if($calificacion>=4){
				$color="#008C00";
			}elseif($calificacion<=2){
				$color="#ff0000";
			}else{
				$color="#D96D00";
			}
?>

    <div class="row" style="padding:0 0 15px 0; border-bottom:5px solid #ccc; margin:10px 0 0 0;">
        <div class="col-sm-4 col-xs-12 text-center" style="font-size:30px;">
			
			<div style="color:<?=$color?>;">
				<?php if($calificacion==1 || $calificacion==2 || $calificacion==3 || $calificacion==4 || $calificacion==5){ ?><i class="fa fa-star" aria-hidden="true"></i><?php }else{ ?><i class="fa fa-star-o" aria-hidden="true"></i><?php } ?>
				<?php if($calificacion==2 || $calificacion==3 || $calificacion==4 || $calificacion==5){ ?><i class="fa fa-star" aria-hidden="true"></i><?php }else{ ?><i class="fa fa-star-o" aria-hidden="true"></i><?php } ?>
				<?php if($calificacion==3 || $calificacion==4 || $calificacion==5){ ?><i class="fa fa-star" aria-hidden="true"></i><?php }else{ ?><i class="fa fa-star-o" aria-hidden="true"></i><?php } ?>
				<?php if($calificacion==4 || $calificacion==5){ ?><i class="fa fa-star" aria-hidden="true"></i><?php }else{ ?><i class="fa fa-star-o" aria-hidden="true"></i><?php } ?>
				<?php if($calificacion==5){ ?><i class="fa fa-star" aria-hidden="true"></i><?php }else{ ?><i class="fa fa-star-o" aria-hidden="true"></i><?php } ?>
			</div>
			
			<div style="font-size:20px;margin:10px 0;" id="mensajeValoracion">
				<?php if($calificacion==0 || $calificacion==""){ echo "-- Sin Valoración --"; } ?>
				<?php if($calificacion==1){ echo '<span style="color:#ff0000;">Valoración: Malo</span>'; } ?>
				<?php if($calificacion==2){ echo '<span style="color:#ff0000;">Valoración: Regular</span>'; } ?>
				<?php if($calificacion==3){ echo '<span style="color:#D96D00;">Valoración: Normal</span>'; } ?>
				<?php if($calificacion==4){ echo '<span style="color:#008C00;">Valoración: Muy bueno</span>'; } ?>
				<?php if($calificacion==5){ echo '<span style="color:#008C00;">Valoración: Excelente</span>'; } ?>
			</div>
        </div>        
        <div class="col-sm-8 col-xs-12 text-left">
            <h5><?= (($id_usuario==$_SESSION['sess_usu_id'])?"Yo:":$nombre) ?></h5>
            <div><?= $descripcion ?></div>
        </div>
    </div>

<?php
            $result->MoveNext();
        }
    } else {
        echo 
        '<div class="col-sm-3 col-xs-12 text-center"></div>
        <div class="col-sm-4 col-xs-12 text-center">
            <h4>No tenés calificaciones</h4>
        </div>
        <div class="col-sm-3 col-xs-12 text-center"></div>';
    }
}
?>