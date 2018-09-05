<?php
//Cabeceras del documento
include("cabecera.php");

$variables_ver = variables_metodo("id,id_directorio,funcion,estado_fav");
$id = $variables_ver[0];
$id_directorio = $variables_ver[1];
$funcion = $variables_ver[2];
$estado_fav = $variables_ver[3];

$variables_ver = variables_metodo("nombre_cotiza,tele_cotiza,email_cotiza,descripcion_cotiza,file_cotiza");
$nombre_cotiza = $variables_ver[0];
$tele_cotiza = $variables_ver[1];
$email_cotiza = $variables_ver[2];
$descripcion_cotiza = $variables_ver[3];
$file_cotiza = $variables_ver[4];

$cotizando = "";

/////////////////

if ($id_directorio != "" && $funcion == "favorito") {
    /* Funcion para guardar los datos del formulario */
    favorito($id_directorio, $estado_fav, $db);
}

if ($funcion == "cotizar") {
    /* Funcion para guardar los datos del formulario */
    $cotizando = cotizar($id_directorio, $nombre_cotiza, $tele_cotiza, $email_cotiza, $descripcion_cotiza, $file_cotiza, $db);
}

/* * ************************************************************************************************************************ */

function favorito($id_directorio, $estado_fav, $db) {
    echo $sql_dir = "SELECT nombre FROM ic_directorio "
    . "WHERE id_directorio = '$id_directorio' LIMIT 1;";
    $result_dir = $db->Execute($sql_dir);

    list($nombre) = select_format($result_dir->fields);

    echo $sql_directorio = "DELETE FROM ic_favoritos WHERE id_directorio = '" . $id_directorio . "' AND id_usuario = '" . $_SESSION['sess_usu_id'] . "';";
    $result_directorio = $db->Execute($sql_directorio);

    if ($estado_fav == "S") {
        $sql_directorio_categoria = "INSERT INTO ic_favoritos (id_directorio,id_usuario,fecha) VALUES ('" . $id_directorio . "','" . $_SESSION['sess_usu_id'] . "','" . date("Y-m-d") . "');";
        $result_directorio_categoria = $db->Execute($sql_directorio_categoria);
    }

    //funcion para registrar la modificacion hecha por el usuario
    registrar_cambios($db, date('Y-m-d'), $_SESSION['sess_nombre'], '0', $id_directorio, $nombre, 'FAVORITO');
}

/* * ************************************************************************************************************************ */

function cotizar($id_directorio, $nombre_cotiza, $tele_cotiza, $email_cotiza, $descripcion_cotiza, $file_cotiza, $db) {
    $dir = "documentos/";

    $url_full = $_FILES['file_cotiza']['tmp_name'];
    $imagen_full = $_FILES['file_cotiza']['name'];

    if ($url_full != "" && $imagen_full != "") {
        $alt = mt_rand();

        if (subir_imagen($alt . $imagen_full, $url_full, $dir)) {
            $file_cotiza = $dir . $alt . $imagen_full;
        }
    }

    ///////////

    $sql_dir = "SELECT nombre,id_usuario FROM ic_directorio WHERE id_directorio = '" . $id_directorio . "' LIMIT 1;";
    $result_dir = $db->Execute($sql_dir);
    list($nombre, $id_usuario) = select_format($result_dir->fields);

    ////////////////

    $sql = "INSERT INTO ic_cotizaciones (nombre_coti,email_coti,tele_coti,mensaje_coti,fecha_coti,id_directorio,estado_coti,calificacion_coti,id_usuario,tipo_reg_coti,link_coti) 
		        VALUES ('" . $nombre_cotiza . "',
				        '" . $email_cotiza . "',
						'" . $tele_cotiza . "',
						'" . $descripcion_cotiza . "',
						'" . date("Y-m-d") . "',
						'" . $id_directorio . "',
						'A',
						'0',
						'" . $_SESSION['sess_usu_id'] . "',
						'SOL',
						'" . $file_cotiza . "');";
    $result = $db->Execute($sql);

    ///////////

    $sql_coti = "SELECT MAX(id_coti) FROM ic_cotizaciones WHERE id_usuario = '" . $_SESSION['sess_usu_id'] . "' AND id_directorio='" . $id_directorio . "' LIMIT 1;";
    $result_coti = $db->Execute($sql_coti);
    list($id_coti) = select_format($result_coti->fields);

    $sql = "INSERT INTO ic_respuesta_cotizacion (id_coti, id_usuario_envia, id_usuario_destino, fecha, respuesta, estado) 
		        VALUES ('" . $id_coti . "',
				        '" . $_SESSION['sess_usu_id'] . "',
						'" . $id_usuario . "',
						'" . date("Y-m-d H:i:s") . "',
						'" . $descripcion_cotiza . "',
						'UNREAD');";
    $result = $db->Execute($sql);

    ///////////

    if (isset($_SESSION["us_codigo_ref"]) && $_SESSION["us_network"] == "facebook") {
        $sql = "INSERT INTO ic_amigo_directorio (id_amigo, id_directorio, id_usuario, tipo) 
					VALUES ('" . $_SESSION["us_codigo_ref"] . "',
							'" . $id_directorio . "',
							'" . $_SESSION['sess_usu_id'] . "',
							'COT');";
        $result = $db->Execute($sql);
    }


    ////////////////////////

    $sql = "SELECT us_nombre,us_email FROM ic_usuarios WHERE us_id='" . $id_usuario . "'";
    $result_usu = $db->Execute($sql);
    list($us_nombre, $us_email) = select_format($result_usu->fields);

    $adicional_formato = "<a href='http://www.worklife.com.ar/cotizaciones.html'>" . strtoupper($nombre) . "</a>";
    enviar_notificacion($us_email, $us_nombre, "NUEVA_COTIZACION", "1", $adicional_formato);

    ////////////////////////
    //funcion para registrar la modificacion hecha por el usuario
    registrar_cambios($db, date('Y-m-d'), $_SESSION['sess_nombre'], '0', $id_directorio, $nombre, 'FAVORITO');

    return "servicio/" . organizar_nombre($nombre);
}

/* * ************************************************************************************************************************ */

$sql = "SELECT id_directorio, nombre, categoria, descripcion, direccion, ciudad, estado, zip, telefono, web, info_adicional, email, sponsor, logo,
				   latitud, longitud, orden, id_usuario, activo, destacado, img1, img2, img3, video, fecha, visualizaciones, horario_man, horario_tar, 
				   horario_noc, dia_lun, dia_mar, dia_mie, dia_jue, dia_vie, dia_sab, dia_dom, urgencia, realiza_domi, realiza_esta, realiza_ofic, realiza_onli, 
				   certificado_estudio, matricula, garantia, seguro, horario_24 FROM (
				SELECT id_directorio, nombre, categoria, descripcion, direccion, ciudad, estado, zip, telefono, web, info_adicional, email, sponsor, logo,
					   latitud, longitud, orden, id_usuario, activo, destacado, img1, img2, img3, video, fecha, visualizaciones, horario_man, horario_tar, 
					   horario_noc, dia_lun, dia_mar, dia_mie, dia_jue, dia_vie, dia_sab, dia_dom, urgencia, realiza_domi, realiza_esta, realiza_ofic, realiza_onli, 
					   certificado_estudio, matricula, garantia, seguro, horario_24
				FROM ic_directorio 
				WHERE 1=1 ) tabla
			WHERE activo=1 AND id_directorio=" . $id . " ";
$result = $db->Execute($sql);


list($id_directorio, $nombre, $categoria, $descripcion, $direccion, $ciudad, $estado, $zip, $telefono, $web, $info_adicional, $email, $sponsor, $logo, $latitud,
        $longitud, $orden, $id_usuario_pub, $activo, $destacado, $img1, $img2, $img3, $video, $fecha, $visualizaciones, $horario_man, $horario_tar, $horario_noc,
        $dia_lun, $dia_mar, $dia_mie, $dia_jue, $dia_vie, $dia_sab, $dia_dom, $urgencia, $realiza_domi, $realiza_esta, $realiza_ofic, $realiza_onli, $certificado_estudio,
        $matricula, $garantia, $seguro, $horario_24) = select_format($result->fields);

$fecha = explode(" ", $fecha);
$fecha = explode("-", $fecha[0]);
$fecha = $fecha[2] . " de " . $fecha[1] . ", " . $fecha[0];

$_SESSION['url_visto'] = "servicio/" . organizar_nombre($nombre);

///////////

$sql = "SELECT count(*) FROM ic_cotizaciones WHERE id_directorio='" . $id_directorio . "'";
$result_sol = $db->Execute($sql);
list($total_sol) = select_format($result_sol->fields);

///////////

$sql = "SELECT count(*) FROM ic_favoritos WHERE id_directorio='" . $id_directorio . "'";
$result_fav = $db->Execute($sql);
list($total_fav) = select_format($result_fav->fields);

///////////


$sql = "SELECT count(*) FROM ic_favoritos WHERE id_directorio='" . $id_directorio . "' AND id_usuario='" . ((isset($_SESSION['sess_usu_id'])) ? $_SESSION['sess_usu_id'] : "") . "'";
$result_fav = $db->Execute($sql);
list($mi_fav) = select_format($result_fav->fields);

///////////

$sql = "SELECT CONCAT(us_nombre,' ',us_last_name) FROM ic_usuarios WHERE us_id='" . $id_usuario_pub . "'";
$result_usu = $db->Execute($sql);
list($usuario) = select_format($result_usu->fields);

///////////

$sql = "SELECT AVG(calificacion) FROM ic_reputacion a, ic_cotizaciones b WHERE id_coti=id_cotizacion AND b.id_directorio='" . $id_directorio . "'";
$result_cal = $db->Execute($sql);
list($cal) = select_format($result_cal->fields);
$cal = ceil($cal);
?>
<link rel="stylesheet" href="dist/app.css">
<link rel="stylesheet" href="dist/bootstrap-tagsinput.css">

<script src="https://cdnjs.cloudflare.com/ajax/libs/typeahead.js/0.11.1/typeahead.bundle.min.js"></script>
<script src="dist/bootstrap-tagsinput.min.js"></script>


<div style="width:100%; box-sizing:border-box; padding:5px 0;">
    <div class="container"  style="box-sizing:border-box;">
        <div class="row"  style="width:100%; box-sizing:border-box; margin-bottom:10px;">
            <div class="col-md-3 col-xs-12">
                <a href="index.html"><img src="images/logo.jpg" style="width:100%"/></a>
            </div>  
            <div class="col-md-4 col-xs-12">
                <form action="listado-servicios.html" method="get" id="serach_list" name="serach_list">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="¿Qué Servicio Buscás?" id="search_services" name="search_services" style="border-radius:5px 0 0 5px;border-right:none;  padding:2px 10px; height:33px; border:1px solid #ccc;" />
                        <span class="input-group-btn">
                            <button class="btn btn-secondary buscarBtn" type="button" onclick="enviarBusqueda();" style="background:#FAA532;border-radius:0 5px 5px 0;">
								<i class="fa fa-search" aria-hidden="true"></i>
							</button>
                        </span>
                    </div>
					
					<input type="hidden" name="palabra" id="palabra_search" />
					<input type="hidden" name="categoria" id="categoria_search" />
				
                    <!--
                    <table width="100%" border="0" style="margin:0 0 0 0; padding:0;"><tr>
                            <td><input type="text" placeholder="¿Qué Servicio Buscas?" style="width:100%; border-radius:5px 0 0 5px; background:#fff;  border:1px solid #ccc; padding:5px 10px;  position:relative; z-index:50;  margin:0 !importan; font-size:16px; height:35px;" id="search_services" name="search_services" /></td>
                            <td width="30" nowrap valign="top" style="padding:16px 0 0 0;"><a href="#" class="buscarBtn" style="background:#FAA532; padding:9px 8px 10px 8px; position:relative; z-index:100; margin:0 0 0 -12px; font-size:14px; color:#FFF; border-radius:0 5px 5px 0;"><i class="fa fa-search" aria-hidden="true"></i>
                                </a></td></tr></table>
                    <input type="hidden" name="palabra" id="palabra_search" /><input type="hidden" name="categoria" id="categoria_search" />
                    -->
                </form>
            </div>
            <div class="col-md-1 col-xs-12"></div> 
            <div class="col-md-4 col-xs-12" style="text-align:center; padding:13px 0 0 0;">
                <div>
<?php
if (!isset($_SESSION['usuario'])) {
    echo '<a href="usuarios.html"><i class="fa fa-user-plus" aria-hidden="true"></i> Registrate</a> | <a href="usuarios.html">Ingresá</a>';
} else {
    $sql = "SELECT count(*) FROM ic_respuesta_cotizacion WHERE id_usuario_destino ='" . $_SESSION['sess_usu_id'] . "' AND estado='UNREAD'";
    $result = $db->Execute($sql);
    list($can_msg) = select_format($result->fields);

    $msg = "";
    if ($can_msg > 0) {
        $msg = '<span class="label label-warning">' . $can_msg . '</span>';
    }

    echo '<a href="usuarios.html"><i class="fa fa-user-circle" aria-hidden="true"></i> Mi cuenta</a> <span style="margin:0 10px;">|</span> <a href="cotizaciones.html"><i class="fa fa-envelope-o" aria-hidden="true"></i> Mensajes ' . $msg . '</a> <span style="margin:0 10px;">|</span> <a href="index.php?salir=1"> <i class="fa fa-window-close-o" aria-hidden="true"></i> Salir</a>';
}
?>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="profesional" style="margin:0 0 30px 0;">

</div>


<div class="container" style="padding:30px 10px box-sizing:border-box;;">
    <div class="row" style="width:100%; box-sizing:border-box;">
        <div class="col-md-8 col-xs-12">
            <div style="margin:10px 0 20px 0;">
                <a href="javascript:history.back();">Volver al listado &raquo;</a>
            </div>

            <h1><?= $nombre ?></h1>

            <div class="row">
                <div class="col-md-12 col-xs-12"> 
                    <div style="margin:15px 0;">
                        <i class="fa fa-calendar" aria-hidden="true"></i> Publicado desde <?= $fecha ?> | <i class="fa fa-envelope-open-o" aria-hidden="true"></i> <?= $total_sol ?> Solicitudes | <i class="fa fa-thumbs-up" aria-hidden="true"></i> <?= $total_fav ?> Seguidores
                    
						<br><br>
						<?php if ($mi_fav <= 0) { ?>
                            <a href="javascript:;" onclick="enviarAccion('<?= $id_directorio ?>', 'favorito', 'S');" style="background:#7C4793; color:#fff; padding:5px 15px; border-radius:5px;;"><i class="fa fa-heart" aria-hidden="true"></i> Agregar a Favoritos</a>
						<?php } else { ?>
                            <a href="javascript:;" onclick="enviarAccion('<?= $id_directorio ?>', 'favorito', 'N');" style="border:1px solid #7C4793; color:#7C4793; padding:5px 15px; border-radius:5px;"><i class="fa fa-heart" aria-hidden="true"></i> Dejar de seguir</a>       
                        <?php } ?>
						
						</div>
                </div>
            </div>

            <div style="margin:10px 0; font-size:30px; color:#FC0; cursor:pointer;" id="verreputacion">
<?php if ($cal == 1 || $cal == 2 || $cal == 3 || $cal == 4 || $cal == 5) { ?><i class="fa fa-star" aria-hidden="true"></i><?php } else { ?><i class="fa fa-star-o" aria-hidden="true"></i><?php } ?>
<?php if ($cal == 2 || $cal == 3 || $cal == 4 || $cal == 5) { ?><i class="fa fa-star" aria-hidden="true"></i><?php } else { ?><i class="fa fa-star-o" aria-hidden="true"></i><?php } ?>
<?php if ($cal == 3 || $cal == 4 || $cal == 5) { ?><i class="fa fa-star" aria-hidden="true"></i><?php } else { ?><i class="fa fa-star-o" aria-hidden="true"></i><?php } ?>
                <?php if ($cal == 4 || $cal == 5) { ?><i class="fa fa-star" aria-hidden="true"></i><?php } else { ?><i class="fa fa-star-o" aria-hidden="true"></i><?php } ?>
                <?php if ($cal == 5) { ?><i class="fa fa-star" aria-hidden="true"></i><?php } else { ?><i class="fa fa-star-o" aria-hidden="true"></i><?php } ?>

                <?php
                if ($cal == "1") {
                    echo '<span style="color:#ff0000; margin:0 10px; font-size:17px;">Valoración: Malo</span>';
                }
                if ($cal == "2") {
                    echo '<span style="color:#ff0000; margin:0 10px; font-size:17px;">Valoración: Regular</span>';
                }
                if ($cal == "3") {
                    echo '<span style="color:#D96D00; margin:0 10px; font-size:17px;">Valoración: Normal</span>';
                }
                if ($cal == "4") {
                    echo '<span style="color:#008C00; margin:0 10px; font-size:17px;">Valoración: Muy bueno</span>';
                }
                if ($cal == "5") {
                    echo '<span style="color:#008C00; margin:0 10px; font-size:17px;">Valoración: Excelente</span>';
                }
                ?>
            </div>

            <div class="row">
                <div class="col-md-12 col-xs-12">
<?php
if (isset($_SESSION["longitud"]) && isset($_SESSION["latitud"]) && $_SESSION["longitud"] != "" && $_SESSION["latitud"] != "") {
    if ($latitud != "" && $longitud != "") {
        echo '<div style="margin:15px 0;"><i class="fa fa-map-marker" aria-hidden="true"></i> ' . round(calcular_distancia($_SESSION["latitud"], $_SESSION["longitud"], $latitud, $longitud, "K"), 1) . 'km de tí</div>';
    }
}
///////////////
if (isset($_SESSION["us_codigo_ref"]) && $_SESSION["us_network"] == "facebook") {

    $sql = "SELECT b.id_amigo,b.tipo,b.id_usuario FROM ic_amigos_fb a, ic_amigo_directorio b 
								WHERE a.id_amigo=b.id_amigo AND a.id_usuario='" . $_SESSION['sess_usu_id'] . "' AND b.id_directorio='" . $id_directorio . "'";
    $result_amigos = $db->Execute($sql);

    $img_amigos = "";

    while (!$result_amigos->EOF) {
        list($id_amigo, $tipo, $id_usuario) = select_format($result_amigos->fields);

        $sql = "SELECT us_foto,us_nombre,us_last_name  FROM ic_usuarios WHERE us_id='" . $id_usuario . "'";
        $result_profile = $db->Execute($sql);
        list($img_ami, $us_nombre_amigo, $us_last_name_amigo) = select_format($result_profile->fields);

        /* if($img_ami==""){
          $img_ami="images/profile.jpg";
          } */

        //API FACEBOOK PARA CONSULT DE IMAGEN POR USUARIO
        $img_amigos .= " <a href='https://www.facebook.com/app_scoped_user_id/" . $id_amigo . "' target='_blank' title='" . trim($us_nombre_amigo) . " " . trim($us_last_name_amigo) . "'><img src='" . $img_ami . "' width='30' style='border-radius:100px;' /></a> ";

        $result_amigos->MoveNext();
    }
    ///////////////

    $sql = "SELECT COUNT(b.id_amdir) FROM ic_amigos_fb a, ic_amigo_directorio b 
								WHERE a.id_amigo=b.id_amigo AND a.id_usuario='" . $_SESSION['sess_usu_id'] . "' AND b.id_directorio='" . $id_directorio . "'";
    $result_amigos = $db->Execute($sql);
    list($total_amigos) = select_format($result_amigos->fields);

    echo '<span style="font-weight:bold; color:#0036D9;"><i class="fa fa-facebook" aria-hidden="true"></i> Tienes ' . $total_amigos . ' amigos en comun con el Servicio ' . $img_amigos . '</span><br>';
} else {
    echo ' <div style="margin:10px 0;"><span title="Requiere login con Facebook"><i class="fa fa-facebook" aria-hidden="true"></i> Tienes 0 amigos en comun con el Servicio</span></div>';
}
?>
                    <div style="margin:10px 0;">
                        <i class="fa fa-user" aria-hidden="true"></i> Publicado por: <?= $usuario ?>
                    </div>
                </div>

            </div>
        </div>
        <div class="col-md-4 col-xs-12 text-center">
            <img src="<?= (($logo == "") ? "images/publication.jpg" : $logo) ?>" style=" width:60%; border-radius:100px;" />
        </div>
    </div>
	
	<!-- ////////////////////////////////////////////////// -->
	
	
<?php
//Mensaje despues de realizar la cotizavion
if ($cotizando != "") {
    ?>
        <div class="row">
            <div class="col-md-12 col-xs-12 text-center" style="min-height:500px;">
                <br /><br />
                <h1>Gracias por tu solicitud, estamos contactando al profesional del servicio.<br />¡Este se pondrá en contacto con vos!</h1>
                <br />
                <h4>Recordá que podés revisar tu solicitud y enviar mensajes al proveedor del servicio desde el panel de "Tu Cuenta", sección "Cotizaciones"</h4>
                <br />
                <br />
            </div>
        </div>

        <script>
            setTimeout(function () {
                window.location.href = "<?= $cotizando ?>";
            }, 3000);
        </script>

    <?php
} else {
    ?>

        <div class="row">
            <div class="col-md-8 col-xs-12">

                <div style="width:100%; background:#ccc; margin:25px 0; height:3px;"></div>

                <div class="row">
                    <div class="col-sm-4 col-xs-12 text-left" style="margin:10px 0 !important; line-height:27px;">
                        <strong>Horario de atención</strong><br/>
    <?php if ($horario_man != "1" && $horario_tar != "1" && $horario_noc != "1" && $horario_24 != "1") { ?>- No aclarado -<?php } ?>

    <?php if ($horario_man == "1") { ?><i class="fa fa-check-square-o" aria-hidden="true"></i> Mañana <br/><?php } ?>

                        <?php if ($horario_tar == "1") { ?><i class="fa fa-check-square-o" aria-hidden="true"></i> Tarde <br/><?php } ?>

                        <?php if ($horario_noc == "1") { ?><i class="fa fa-check-square-o" aria-hidden="true"></i> Noche<br/><?php } ?>

                        <?php if ($horario_24 == "1") { ?><i class="fa fa-check-square-o" aria-hidden="true"></i>  <span style="color:#C00;font-weight: bold">Servicio 24hs</span><br/><?php } ?>
                    </div>
                    <div class="col-sm-4 col-xs-12 text-left" style="margin:10px 0 !important;">
                        <strong>Dias de la semana</strong><br/>
                        <table>
                            <tr>
                                <td valign="top">
    <?php if ($dia_lun != "1" && $dia_mar != "1" && $dia_mie != "1" && $dia_jue != "1" && $dia_vie != "1" && $dia_sab != "1" && $dia_dom != "1") { ?>- No aclarado -<?php } ?>


                                    <?php if ($dia_lun == "1") { ?><i class="fa fa-check-square-o" aria-hidden="true"></i> Lunes <br/><?php } ?>

    <?php if ($dia_mar == "1") { ?><i class="fa fa-check-square-o" aria-hidden="true"></i> Martes <br/><?php } ?>

                                    <?php if ($dia_mie == "1") { ?><i class="fa fa-check-square-o" aria-hidden="true"></i> Miércoles <br/><?php } ?>

                                    <?php if ($dia_jue == "1") { ?><i class="fa fa-check-square-o" aria-hidden="true"></i> Jueves <br/><?php } ?>

                                    <?php if ($dia_vie == "1") { ?><i class="fa fa-check-square-o" aria-hidden="true"></i> Viernes <br/><?php } ?>                               
                                </td>                
                                <td valign="top">
                                    <?php if ($dia_sab == "1") { ?><i class="fa fa-check-square-o" aria-hidden="true"></i> Sabado <br/><?php } ?>

    <?php if ($dia_dom == "1") { ?><i class="fa fa-check-square-o" aria-hidden="true"></i> Domingo <br/><?php } ?>                                
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div class="col-sm-4 col-xs-12 text-left" style="margin:10px 0 !important;">
                        
						<?php if ($realiza_domi != "1" && $realiza_esta != "1" && $realiza_ofic != "1" && $realiza_onli != "1") { ?>
							
							<strong>El servicio se realiza</strong><br/>
							
							<?php if ($realiza_domi == "1") { ?><i class="fa fa-check-square-o" aria-hidden="true"></i> a Domicilio  <br/><?php } ?>   

							<?php if ($realiza_esta == "1") { ?><i class="fa fa-check-square-o" aria-hidden="true"></i> en Establecimiento/Instituto <br/><?php } ?>   

							<?php if ($realiza_ofic == "1") { ?><i class="fa fa-check-square-o" aria-hidden="true"></i> en Oficina/ Local <br/><?php } ?>   

							<?php if ($realiza_onli == "1") { ?><i class="fa fa-check-square-o" aria-hidden="true"></i> On-Line/Freelance <br/><?php } ?> 
							
						<?php } ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6 col-xs-12 text-left" style="margin:10px 0 !important;  line-height:27px;">
                        <?= (($certificado_estudio == "") ? "" : "<strong>Certificado/Estudio: </strong> ".$certificado_estudio."<br />") ?>
                        <?= (($matricula == "") ? "" : "<strong>Matrícula: </strong> ".$matricula."<br />") ?>
                    </div>

                    <div class="col-sm-6 col-xs-12 text-left" style="margin:10px 0 !important;">
                        <?= (($garantia == "" || $garantia == "0") ? "" : "<strong>Garantía: </strong> ".$garantia." ".(($garantia<=1)?"mes":"meses")."<br />") ?>
                        <?= (($seguro == "") ? "" : "<strong>Seguro (ART): </strong>  ".$seguro."<br />") ?>
                    </div>
                </div>

                <div style="width:100%; background:#ccc; margin:25px 0; height:3px;"></div>

    <?= $descripcion ?>

                <div style="width:100%; background:#ccc; margin:25px 0; height:3px;"></div>

                <?php
                if (trim($info_adicional) != "") {
                    echo '<b>Información Adicional</b><br><br>';
                    echo $info_adicional;
                    echo '<div style="width:100%; background:#ccc; margin:25px 0; height:3px;"></div>';
                }
                ?>

                <div style="margin:15px 0; line-height:27px;">
                    <div class="row">
                        <div class="col-md-6 col-xs-12">
    <?php
    if (trim($direccion) != "" || trim($zip) != "") {
        ?>
                                <i class="fa fa-map-marker" aria-hidden="true" style="color:#C00"></i> <?= $direccion ?>. <?= $zip ?>
                                <br />
                                <?php
                            }

                            if (trim($ciudad) != "" || trim($estado) != "") {
                                ?>
                                <i class="fa fa-map-marker" aria-hidden="true" style="color:#C00"></i> <?= $ciudad ?>, <?= $estado ?>
                                <br />
                                <?php
                            }

                            if (trim($telefono) != "") {
                                ?>
                                <i class="fa fa-phone-square" aria-hidden="true"></i> <?= $telefono ?> 
                                <?php
                            }
                            ?>
                        </div>
                        <div class="col-md-6 col-xs-12">
                            <?php
                            if (trim($web) != "") {
                                ?>
                                <i class="fa fa-globe" aria-hidden="true"></i> <?= $web ?>
                                <br />
                                <?php
                            }

                            if (trim($email) != "") {
                                ?>
                                <i class="fa fa-envelope" aria-hidden="true"></i> <?= $email ?>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>

                <div style="width:100%; background:#ccc; margin:25px 0; height:3px;"></div>


    <?php
    if ($img1 != "" || $img2 != "" || $img3 != "") {

        if ($img1 != "") {
            echo '<div style="margin:10px; width:30%; display:inline-block;"><a href="' . $img1 . '" rel="shadowbox[galeria];"><img src="' . $img1 . '" style="width:100%;" /></a></div>';
        }

        if ($img2 != "") {
            echo '<div style="margin:10px; width:30%; display:inline-block;"><a href="' . $img2 . '" rel="shadowbox[galeria];"><img src="' . $img2 . '" style="width:100%;" /></a></div>';
        }

        if ($img3 != "") {
            echo '<div style="margin:10px; width:30%; display:inline-block;"><a href="' . $img3 . '" rel="shadowbox[galeria];"><img src="' . $img3 . '" style="width:100%;" /></a></div>';
        }

        echo '<div style="width:100%; background:#ccc; margin:25px 0; height:3px;"></div>';
    }
    ?>

                <?php
                if ($video != "") {

                    if (strstr($video, "iframe")) {
                        $video = str_replace('width="560"', 'style="width:100%; height:450px;"', $video);
                        $video = str_replace('height="315"', '', $video);
                        $video = str_replace('width="420"', 'style="width:100%; height:450px;"', $video);
                        $video = str_replace('height="315"', '"', $video);

                        echo $video;
                    }
                    echo '<div style="width:100%; background:#ccc; margin:25px 0; height:3px;"></div>';
                }
                ?>

                <?php
                if (!$result->EOF && $db->affected_rows() > 1) {
                    
                ?>
                    <div style="background:#fff; padding:50px 0;">
                        <div style="max-width:1150px; margin:0 auto;">
                            <div style="width:300px; margin:0 auto; height:5px; background:#FAA532; "></div>
                            <div style="width:100%; text-align:center; margin:20px 0 50px 0; font-size:30px; color:#F9A532; font-weight:bold;">Otras publicaciones del usuario</div>
                            <div style="width:100%; margin:50px 0;">

        <?php
        $result->MoveNext();
        
        while (!$result->EOF) {
            list($id_directorio, $nombre, $categoria, $logo, $ciudad, $estado) = select_format($result->fields);
            
            ?>
                                    <div style="width:28%; margin:10px; display:inline-block; vertical-align:top;">
                                        <div style="box-sizing:border-box; padding:10px;">							
                                            <div class="itemBox2">
                                                <a href="<?= "servicio/" . organizar_nombre($nombre) ?>"><img src="<?= $logo ?>" style="max-width:100%; max-height:100%;" /></a>
                                            </div>
                                            <div style="min-height:150px; margin:7px 0 0 0;">
                                                <a href="<?= "servicio/" . organizar_nombre($nombre) ?>"><strong><?= $nombre ?></strong></a>
                                                <br>
            <?= $ciudad ?>
                                            </div>
                                        </div>
                                    </div>
            <?php
            $result->MoveNext();
        }
        ?>

                            </div>        
                        </div>
                    </div>
                                <?php
                            }
                            ?>    
            </div>
            <div class="col-md-4 col-xs-12 text-left">
                <div style="margin:20px auto; text-align:center; width:300px;">
                    <!-- AddThis Button BEGIN -->
                    <div class="addthis_toolbox addthis_default_style addthis_32x32_style">
                        <a class="addthis_button_preferred_1"></a>
                        <a class="addthis_button_preferred_2"></a>
                        <a class="addthis_button_preferred_3"></a>
                        <a class="addthis_button_preferred_4"></a>
                        <a class="addthis_button_preferred_5"></a>
                        <a class="addthis_button_preferred_6"></a>
                        <a class="addthis_button_preferred_7"></a>
                        <a class="addthis_button_compact"></a>
                    </div>
                    <script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=xa-4f39c4ef24b7309b"></script>
                    <!-- AddThis Button END -->
                </div>


    <?php
    if (isset($_SESSION['sess_nombre'])) {

        $sql = "SELECT count(*) FROM ic_cotizaciones WHERE id_directorio='" . $id_directorio . "' AND estado_coti='A' AND id_usuario='" . $_SESSION['sess_usu_id'] . "'";
        $result_sol = $db->Execute($sql);
        list($total_sol) = select_format($result_sol->fields);

        if ($total_sol > 0) {
            ?>
                        <div style="padding:20px 20px; border-radius:5px; background:#7C4793; border:1px solid #7C4793; box-sizing:border-box; color:#fff;">
                            <h2 style="color:#fff">Ya tienes una solicitud de contacto activa para esta publicación.</h2>
                            <br />
                            <span style="color:#fff;">Si deseas enviar un mensaje, puedes hacerlo desde la sección "Mi Cuenta" opción "Mis Cotizaciones".
                                <br /><br />
                                <a href="cotizaciones.html" style="color:#fff;">[ Ver solicitud de contacto ]</a></span>
                        </div>
                        <?php
                    } else {
                        ?>

                        <div style="padding:20px 20px; border-radius:5px; background:#7C4793; border:1px solid #7C4793; box-sizing:border-box; color:#fff;">
                            <h2 style="color:#fff">¡Solicita Cotización de Servicio!</h2>
                            <br />
                            ¡Recuerda que mejor detallas tu pedido, recibiras una Cotizacion mas Rapido!
                            <br /><br />
                            <form method="post" enctype="multipart/form-data" name="cotizar" id="cotizar">
                                Nombre
                                <input name="nombre_cotiza" type="text" id="nombre_cotiza" class="form-control" value="<?= ((isset($_SESSION['sess_nombre'])) ? $_SESSION['sess_nombre'] . " " . $_SESSION['sess_last_nombre'] : "") ?>" />
                                Teléfono
                                <input name="tele_cotiza" type="text" id="tele_cotiza" class="form-control" value="<?= ((isset($_SESSION['sess_usu_telefono'])) ? $_SESSION['sess_usu_telefono'] : "") ?>" />
                                Email
                                <input name="email_cotiza" type="text" id="email_cotiza" class="form-control" value="<?= ((isset($_SESSION['sess_usu_email'])) ? $_SESSION['sess_usu_email'] : "") ?>" />                
                                Detalle de la solicitud
                                <textarea name="descripcion_cotiza"  rows="5" class="form-control" id="descripcion_cotiza"></textarea>
                                Imagen/Foto del problema
                                <input type="file" name="file_cotiza" id="file_cotiza"  class="form-control"/>
                                <br />
                                <input type="hidden" name="funcion" id="funcion" value="cotizar" />
                                <input type="hidden" name="id_directorio" id="id_directorio" value="<?= $id_directorio ?>" />
                                <input type="button" value="¡Quiero cotizar!" onclick="enviarCotizar();" style="width:100%; background:#F9A532; color:#fff;" />
                            </form>                
                        </div>
            <?php
        }
    } else {
        ?>
                    <div style="margin:15px 0; text-align:center;">
                        <h3 style="line-height:30px; font-size:27px;">Debes ser un usuario WorkLife para poder enviar una solicitud</h3>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 col-xs-12">
                            <div style="text-align:center; color:#fff; font-weight:700; background:#06C; padding:15px 0;cursor:pointer" onclick="fblogin()">
                                <i class="fa fa-facebook" aria-hidden="true"></i> Iniciar sesion con Facebook
                            </div>
                        </div>

                        <div class="col-sm-6 col-xs-12">
                            <div class="google" style="text-align:center; color:#fff; font-weight:700; background:#C30; padding:15px 0;cursor:pointer" onclick="startApp()">
                                <i class="fa fa-google-plus" aria-hidden="true"></i> Iniciar sesion con Google +
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
                                <label>
                                    <input type="checkbox" id="terminos" checked value="S" /> 
                                    Acepto los <a href="contenido/terminos-y-condiciones.html">Terminos y condiciones</a> y las <a href="contenido/politicas-de-privacidad.html">Políticas de privacidad</a>
                            </div>		
                            <div class="col-sm-12 col-xs-12">
                                <input type="hidden" name="funcion" value="login_usuario" />
                                <input type="button" name="Submit" class="buscarBtn2" value="ACCEDER" id="acceso" style="width:100%; margin:20px auto;"/>
                            </div>
                        </form>				
                    </div>

                    <script>

                        $(document).ready(function () {
                            $("#acceso").click(function () {
                                var email = $("#us_login").val();
                                var passw = $("#us_pass").val();
                                var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;

                                if (email != "" && passw != "")
                                {
                                    if (!emailReg.test(email))
                                    {
                                        alert("Debe ingresar un email válido!");
                                    } else {
                                        document.login.submit();
                                    }
                                } else {
                                    alert("Debe completar los campos para continuar!");
                                }
                            });
                        });


                    </script>
        <?php
    }
    ?>


                <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false&amp;language=es"></script>

                <div style="width:100%; height:350px; margin:25px 0;" id="mapa"></div>
				<?php
					if($latitud!="" && $longitud!=""){
				?>
                <script type="text/javascript">
                    function inicializacion() {

                        var lat = '<?= $latitud ?>';
                        var lon = '<?= $longitud ?>';

                        if (lat != "" && lon != "") {
                            //Creo un nuevo mapa situado en Buenos Aires, Argentina, con 13 de Zoom y del tipo ROADMAP
                            var mapa = new google.maps.Map(document.getElementById("mapa"), {center: new google.maps.LatLng(lat, lon), zoom: 14, mapTypeId: google.maps.MapTypeId.ROADMAP});

                            //Creo un marcador cualquiera situado en una coordenada cualquiera, con la propiedad "draggable" como "true".
                            var marcador = new google.maps.Marker({position: new google.maps.LatLng(lat, lon), map: mapa, title: "<?= $nombre ?>"});

                            var infoWindow = new google.maps.InfoWindow();

                            google.maps.event.addListener(marcador, 'click', (function (marcador, lon, lat) {
                                return function () {
                                    infoWindow.setContent('<div style="width: 250px;"><h3><?= $nombre ?></h3><div><?= $direccion . ", " . $ciudad . ", " . $estado . ", " . $zip ?></div><div align="left" style="margin:10px 0 0 0;"><a href="https://www.google.com/maps/place/<?= $direccion . ", " . $ciudad . ", " . $estado . ", " . $zip ?>" target="_blank"  style="font-weight:bold">[ View in GoogleMaps ]</a></div></div>');
                                    infoWindow.open(mapa, marcador);
                                }
                            })(marcador, lon, lat));


                        }
                    }

                    inicializacion();
                </script>
				<?php
					}
				?>
    <?php if (isset($_SESSION['sess_nombre'])) { ?>
                    <div style="width:100%; margin:20px 0; text-align:right">
                        <form action="denuncias.html" name="denuncias" method="post" >
                            <input type="hidden" name="publicacion" id="publicacion" value="<?= $nombre ?>" />
                            <a href="javascript:;" onclick="document.denuncias.submit();" style="color:#900">Denunciar Publicación <i class="fa fa-ban" aria-hidden="true"></i></a>
                        </form>
                    </div>
    <?php } ?>
            </div>
        </div>
                <?php
            }
            ?>
</div>

<form action="" name="publicaciones" method="post" >
    <input type="hidden" name="id_directorio" id="id_directorio_accion" value="" />
    <input type="hidden" name="funcion" id="funcion_accion" value="" />
    <input type="hidden" name="estado_fav" id="estado_fav" value="" />
</form>

<div id="reputacion" title="Reputación de la publicación">
    <p style="text-align:center;color:#FC0; ">
<?php if ($cal == 1 || $cal == 2 || $cal == 3 || $cal == 4 || $cal == 5) { ?><i class="fa fa-star" aria-hidden="true"></i><?php } else { ?><i class="fa fa-star-o" aria-hidden="true"></i><?php } ?>
<?php if ($cal == 2 || $cal == 3 || $cal == 4 || $cal == 5) { ?><i class="fa fa-star" aria-hidden="true"></i><?php } else { ?><i class="fa fa-star-o" aria-hidden="true"></i><?php } ?>
<?php if ($cal == 3 || $cal == 4 || $cal == 5) { ?><i class="fa fa-star" aria-hidden="true"></i><?php } else { ?><i class="fa fa-star-o" aria-hidden="true"></i><?php } ?>
<?php if ($cal == 4 || $cal == 5) { ?><i class="fa fa-star" aria-hidden="true"></i><?php } else { ?><i class="fa fa-star-o" aria-hidden="true"></i><?php } ?>
<?php if ($cal == 5) { ?><i class="fa fa-star" aria-hidden="true"></i><?php } else { ?><i class="fa fa-star-o" aria-hidden="true"></i><?php } ?>
        <br>		
<?php
if ($cal == "1") {
    echo '<span style="color:#ff0000; margin:0 10px; font-size:14px;">Valoración: Malo</span>';
}
if ($cal == "2") {
    echo '<span style="color:#ff0000; margin:0 10px; font-size:14px;">Valoración: Regular</span>';
}
if ($cal == "3") {
    echo '<span style="color:#D96D00; margin:0 10px; font-size:14px;">Valoración: Normal</span>';
}
if ($cal == "4") {
    echo '<span style="color:#008C00; margin:0 10px; font-size:14px;">Valoración: Muy bueno</span>';
}
if ($cal == "5") {
    echo '<span style="color:#008C00; margin:0 10px; font-size:14px;">Valoración: Excelente</span>';
}
?>  
    </p>

    <div style="width:100%; background:#ccc; margin:25px 0; height:3px;"></div>

    <p style="text-align:left;">
        <?php
        $sql = "SELECT id_repu, id_cotizacion, tipo, fecha, a.id_usuario, descripcion, calificacion FROM ic_reputacion a, ic_cotizaciones b  WHERE id_cotizacion=id_coti AND id_directorio='" . $id_directorio . "' LIMIT 0,5";
        $result = $db->Execute($sql);

        while (!$result->EOF) {
            list($id_repu, $id_cotizacion, $tipo, $fecha, $id_usuario, $descripcion, $calificacion) = select_format($result->fields);

            echo '<div style="padding:10px; margin:5px 0; background:#f4f4f4;">
			<div class="row">
				<div class="col-md-3 col-xs-4">
				' . $fecha . '
				</div>
				<div class="col-md-6 col-xs-4">
				' . $descripcion . '
				</div>
				<div class="col-md-3 col-xs-4">';

            if ($calificacion == 1 || $calificacion == 2 || $calificacion == 3 || $calificacion == 4 || $calificacion == 5) {
                echo '<i class="fa fa-star" aria-hidden="true"></i>';
            } else {
                echo '<i class="fa fa-star-o" aria-hidden="true"></i>';
            }
            if ($calificacion == 2 || $calificacion == 3 || $calificacion == 4 || $calificacion == 5) {
                echo '<i class="fa fa-star" aria-hidden="true"></i>';
            } else {
                echo '<i class="fa fa-star-o" aria-hidden="true"></i>';
            }
            if ($calificacion == 3 || $calificacion == 4 || $calificacion == 5) {
                echo '<i class="fa fa-star" aria-hidden="true"></i>';
            } else {
                echo '<i class="fa fa-star-o" aria-hidden="true"></i>';
            }
            if ($calificacion == 4 || $calificacion == 5) {
                echo '<i class="fa fa-star" aria-hidden="true"></i>';
            } else {
                echo '<i class="fa fa-star-o" aria-hidden="true"></i>';
            }
            if ($calificacion == 5) {
                echo '<i class="fa fa-star" aria-hidden="true"></i>';
            } else {
                echo '<i class="fa fa-star-o" aria-hidden="true"></i>';
            }

            echo '</div>
			</div>
		</div>';

            $result->MoveNext();
        }
        ?>
    </p>



</div>
	
<script>
    function enviarAccion(id, accion, estado_fav) {
        if (accion == "favorito") {
            var usuario = "<?= ((isset($_SESSION['sess_usu_id'])) ? $_SESSION['sess_usu_id'] : "") ?>";
            if (usuario == "") {
                var prom = ezBSAlert({
                    messageText: "Debe ser un usuario para marcar como favorita la publicación",
                    alertType: "danger"
                });
                return;
            }
        }

        $("#id_directorio_accion").val(id);
        $("#funcion_accion").val(accion);
        $("#estado_fav").val(estado_fav);

        document.publicaciones.submit();
    }

    function enviarCotizar() {
        if ($("#nombre_cotiza").val() == "") {
            var prom = ezBSAlert({
                messageText: "Debe completar el nombre para cotizar!",
                alertType: "danger"
            });
            return;
        }

        if ($("#email_cotiza").val() == "") {
            var prom = ezBSAlert({
                messageText: "Debe completar el email para cotizar!",
                alertType: "danger"
            });
            return;
        }

        if ($("#descripcion_cotiza").val() == "") {
            var prom = ezBSAlert({
                messageText: "Debe completar la descripción de la solicitud para cotizar!",
                alertType: "danger"
            });
            return;
        }

        document.cotizar.submit();
    }

    $(function () {
        $("#reputacion").dialog({
            autoOpen: false,
            width: 500,
            show: {
                effect: "fade",
                duration: 100
            },
            hide: {
                effect: "fade",
                duration: 100
            }
        });

        $("#verreputacion").on("click", function () {
            $("#reputacion").dialog("open");
        });
    });

</script>

<?php area("footer"); ?>
<?php include("footer.php"); ?>

<script>


</script>

</body>
</html>