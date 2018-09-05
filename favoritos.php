<?php

$favorite_id = filter_input(INPUT_POST, "fav", FILTER_SANITIZE_NUMBER_INT);
 
include ("conexion.php");

if(!empty($favorite_id) && !empty($_SESSION['sess_usu_id'])) {

    $sql = "DELETE FROM ic_favoritos "
            . "WHERE id_usuario ='" . $_SESSION['sess_usu_id'] . "' AND "
            . "id_fav = '$favorite_id'";

    $result = $db->Execute($sql);
}

if(!empty($_SESSION['sess_usu_id'])) {
    $sql = "SELECT id_fav, f.id_usuario, f.id_directorio, 
                nombre, logo, email
                FROM ic_directorio AS d
                INNER JOIN ic_favoritos f ON d.id_directorio = f.id_directorio
                WHERE f.id_usuario ='" . $_SESSION['sess_usu_id'] . "' ";

    $result = $db->Execute($sql);

    $n = $result->RecordCount();
    $msg = "No estás siguiendo publicaciones, ";

    if(!$result->EOF) {
        $msg = "Estás siguiendo $n ". (($n < 2) ? "publicación, " : "publicaciones, ");
    }
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
        <div style="text-align:center; width:100%; background:#7C4793; padding:3px 0; margin:5px 0;">
            <a href="cotizaciones.html" style="color:#fff; font-size:16px;"><i class="fa fa-list-alt" aria-hidden="true"></i> Cotizaciones</a>
        </div>
    </div>         
    <div class="col-sm-2 col-xs-6">
        <div style="text-align:center; width:100%; background:#FAA532; padding:3px 0; margin:5px 0;">
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

<h1><i class="fa fa-heart" aria-hidden="true"></i> Mis Favoritos</h1>
<h2>Listado de publicaciones seguidas</h2>

<br /><br />
<span><?=$msg?> podés ver más <a href="listado-servicios.html">[ aquí ]</a></span>

<br /><br />
<div class="row" style="padding:15px 0 15px 0; border-bottom:10px solid #ddd; background:#eee; margin:0 0 10px 0;">
    <div class="col-sm-2 col-xs-12 text-center">
        <strong>Imagen</strong>
    </div>        
    <div class="col-sm-8 col-xs-12 text-left">
        <strong>Publicación</strong>
    </div>
     <div class="col-sm-2 col-xs-12 text-left">
        <strong>Opciones</strong>
    </div>
</div>

<?php

    if(!$result->EOF) {
        while (!$result->EOF) {
            list($id_fav, $id_usuario, $id_directorio, $nombre, $logo, $email) = 
                    select_format($result->fields);
					
			if ($logo == "") {
                $logo = "images/publication.jpg";
            }
?>

    <div class="row" style="padding:0 0 15px 0; border-bottom:10px solid #ccc; margin:0 0 10px 0;">
        <div class="col-sm-2 col-xs-12 text-center">
            <a href="<?="servicio/".organizar_nombre($nombre)?>">
                <img src="<?= $logo ?>" style="width:100%" />
            </a>
        </div>        
        <div class="col-sm-7 col-xs-12 text-left">
            <h4><?= $nombre ?></h4>
            <div><?= $email ?> | <span style="color:#ccc;">#<?= $id_directorio ?></span></div>
			<br>
			<a href="<?="servicio/".organizar_nombre($nombre)?>" style="color:#093"><i class="fa fa-eye" aria-hidden="true"></i> Ver publicación</a>
        </div>
         <div class="col-sm-3 col-xs-12 text-right">
            <div style="margin:10px 0;">
                <form action="publicaciones-favoritas.html" method="post">
                    <input name="fav" type="hidden" value="<?=$id_fav?>">
                    <button type="submit" style="color:#900;background: none; border: none">
                        <i class="fa fa-thumbs-o-down" aria-hidden="true"></i> Dejar de seguir
                    </button>
                </form>
            </div>
        </div>
    </div>

<?php
            $result->MoveNext();
        }
    } else {
        echo '<div class="col-sm-12">
                <h4>No tenés favoritos marcados</h4>
            </div>';
    }
}
?>