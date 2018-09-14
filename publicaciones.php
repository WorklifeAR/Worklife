<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
//Definicion de parametros de metodos
$variables_metodo = variables_metodo("id_directorio,funcion,tabnumber,text-auto-view");

$id_directorio = $variables_metodo[0];
$funcion = $variables_metodo[1];
$tabnumber = $variables_metodo[2];
$text_auto_view = $variables_metodo[3];

$variables_metodo = variables_metodo("nombre,categoria,descripcion,direccion,ciudad,estado,zip,telefono,web,info_adicional,email,sponsor,logo,latitud,longitud,orden,id_usuario,activo,destacado,img1,img2,img3,video,fecha,visualizaciones,horario_man,horario_tar,horario_noc,dia_lun,dia_mar,dia_mie,dia_jue,dia_vie,dia_sab,dia_dom,urgencia,realiza_domi,realiza_esta,realiza_ofic,realiza_onli,certificado_estudio,matricula,garantia,seguro,horario_24");

$nombre = $variables_metodo[0];
$categoria = $variables_metodo[1];
$descripcion = $variables_metodo[2];
$direccion = $variables_metodo[3];
$ciudad = $variables_metodo[4];
$estado = $variables_metodo[5];
$zip = $variables_metodo[6];
$telefono = $variables_metodo[7];
$web = $variables_metodo[8];
$info_adicional = $variables_metodo[9];
$email = $variables_metodo[10];
$sponsor = $variables_metodo[11];
$logo = $variables_metodo[12];
$latitud = $variables_metodo[13];
$longitud = $variables_metodo[14];
$orden = $variables_metodo[15];
$id_usuario = $variables_metodo[16];
$activo = $variables_metodo[17];
$destacado = $variables_metodo[18];
$img1 = $variables_metodo[19];
$img2 = $variables_metodo[20];
$img3 = $variables_metodo[21];
$video = $variables_metodo[22];
$fecha = $variables_metodo[23];
$visualizaciones = $variables_metodo[24];
$horario_man = $variables_metodo[25];
$horario_tar = $variables_metodo[26];
$horario_noc = $variables_metodo[27];
$dia_lun = $variables_metodo[28];
$dia_mar = $variables_metodo[29];
$dia_mie = $variables_metodo[30];
$dia_jue = $variables_metodo[31];
$dia_vie = $variables_metodo[32];
$dia_sab = $variables_metodo[33];
$dia_dom = $variables_metodo[34];
$urgencia = $variables_metodo[35];
$realiza_domi = $variables_metodo[36];
$realiza_esta = $variables_metodo[37];
$realiza_ofic = $variables_metodo[38];
$realiza_onli = $variables_metodo[39];
$certificado_estudio = $variables_metodo[40];
$matricula = $variables_metodo[41];
$garantia = $variables_metodo[42];
$seguro = $variables_metodo[43];
$horario_24 = $variables_metodo[44];
$borrarimg = "";


/////////////////
if ($funcion == "guardar") {
    if ($id_directorio == "") {
        /* Funcion para guardar los datos del formulario */
        $id_directorio = guardar($nombre, $categoria, $descripcion, $direccion, $ciudad, $estado, $zip, $telefono, $web, $info_adicional, $email, $sponsor, $logo, $latitud, $longitud, $orden, $id_usuario, $activo, $destacado, $img1, $img2, $img3, $video, $fecha, $visualizaciones, $horario_man, $horario_tar, $horario_noc, $dia_lun, $dia_mar, $dia_mie, $dia_jue, $dia_vie, $dia_sab, $dia_dom, $urgencia, $realiza_domi, $realiza_esta, $realiza_ofic, $realiza_onli, $certificado_estudio, $matricula, $garantia, $seguro, $horario_24, $text_auto_view, $db);

        $funcion = "editar";
    } elseif ($id_directorio != "") {
        /* Funcion para modificar los datos del formulario */
        modificar($id_directorio, $nombre, $categoria, $descripcion, $direccion, $ciudad, $estado, $zip, $telefono, $web, $info_adicional, $email, $sponsor, $logo, $latitud, $longitud, $orden, $id_usuario, $activo, $destacado, $img1, $img2, $img3, $video, $fecha, $visualizaciones, $horario_man, $horario_tar, $horario_noc, $dia_lun, $dia_mar, $dia_mie, $dia_jue, $dia_vie, $dia_sab, $dia_dom, $urgencia, $realiza_domi, $realiza_esta, $realiza_ofic, $realiza_onli, $certificado_estudio, $matricula, $garantia, $seguro, $borrarimg, $horario_24, $tabnumber, $text_auto_view, $db);

        if ($tabnumber != "4") {
            $funcion = "editar";
        } else {
            $funcion = "";
        }
    }
} elseif ($funcion == "eliminar") {
    if ($id_directorio != "") {
        eliminar($id_directorio, $db);
    }
    $funcion = "";
} elseif ($funcion == "estado") {
    if ($id_directorio != "") {
        estado($id_directorio, $db);
    }
    $funcion = "";
}

/* * ************************************************************************************************************************* */

function guardar($nombre, $categoria, $descripcion, $direccion, $ciudad, $estado, $zip, $telefono, $web, $info_adicional, $email, $sponsor, $logo, $latitud, $longitud, $orden, $id_usuario, $activo, $destacado, $img1, $img2, $img3, $video, $fecha, $visualizaciones, $horario_man, $horario_tar, $horario_noc, $dia_lun, $dia_mar, $dia_mie, $dia_jue, $dia_vie, $dia_sab, $dia_dom, $urgencia, $realiza_domi, $realiza_esta, $realiza_ofic, $realiza_onli, $certificado_estudio, $matricula, $garantia, $seguro, $horario_24, $text_auto_view, $db) {
    $dir = "images/directorio/";

    $url_full = $_FILES['logo']['tmp_name'];
    $imagen_full = $_FILES['logo']['name'];

    if ($url_full != "" && $imagen_full != "") {
        $alt = mt_rand();

        if (subir_imagen($alt . $imagen_full, $url_full, $dir)) {
            $logo = $dir . $alt . $imagen_full;
        }
    }

    ////////////////

    $dir = "images/galeria/";

    ////////////////

    $url_full = $_FILES['img1']['tmp_name'];
    $imagen_full = $_FILES['img1']['name'];

    if ($url_full != "" && $imagen_full != "") {
        $alt = mt_rand();

        if (subir_imagen($alt . $imagen_full, $url_full, $dir)) {
            $img1 = $dir . $alt . $imagen_full;
        }
    }

    ////////////////

    $url_full = $_FILES['img2']['tmp_name'];
    $imagen_full = $_FILES['img2']['name'];

    if ($url_full != "" && $imagen_full != "") {
        $alt = mt_rand();

        if (subir_imagen($alt . $imagen_full, $url_full, $dir)) {
            $img2 = $dir . $alt . $imagen_full;
        }
    }

    ////////////////

    $url_full = $_FILES['img3']['tmp_name'];
    $imagen_full = $_FILES['img3']['name'];

    if ($url_full != "" && $imagen_full != "") {
        $alt = mt_rand();

        if (subir_imagen($alt . $imagen_full, $url_full, $dir)) {
            $img3 = $dir . $alt . $imagen_full;
        }
    }

    $result = utf8_encode(insert_bd_format("nombre,categoria,descripcion,direccion,ciudad,estado,zip,telefono,web,info_adicional,email,sponsor,logo,latitud,longitud,orden,id_usuario,activo,destacado," .
            "img1,img2,img3,video,fecha,visualizaciones,horario_man,horario_tar,horario_noc,dia_lun,dia_mar,dia_mie,dia_jue,dia_vie,dia_sab,dia_dom,urgencia,realiza_domi," .
            "realiza_esta,realiza_ofic,realiza_onli,certificado_estudio,matricula,garantia,seguro,horario_24", "ic_directorio", array($nombre, "", $descripcion, $direccion, $ciudad, $estado, $zip, $telefono, $web, $info_adicional, $email, $sponsor, $logo, $latitud, $longitud,
        $orden, $_SESSION['sess_usu_id'], "0", $destacado, $img1, $img2, $img3, $video, date("Y-m-d"), $visualizaciones, $horario_man, $horario_tar, $horario_noc, $dia_lun,
        $dia_mar, $dia_mie, $dia_jue, $dia_vie, $dia_sab, $dia_dom, $urgencia, $realiza_domi, $realiza_esta, $realiza_ofic, $realiza_onli, $certificado_estudio,
        $matricula, $garantia, $seguro, $horario_24), $db));

    //////////////////////////////

    $sql = "SELECT MAX(id_directorio) FROM ic_directorio WHERE id_usuario='" . $_SESSION['sess_usu_id'] . "' ";
    $result = $db->Execute($sql);
    list($id_directorio) = select_format($result->fields);

    if ($text_auto_view != "") {
        $text_auto_view = explode(",", $text_auto_view);

        for ($i = 0; $i < count($text_auto_view); $i++) {
            $sql = "INSERT INTO ic_categoria (id_tipo ,cat_creacion, cat_titulo, cat_sub_categoria, cat_estatus ) VALUES ('7','" . date("Y-m-d") . "','" . $text_auto_view[$i] . "','0','I');";
            $result = $db->Execute($sql);

            $sql = "SELECT MAX(cat_id) FROM ic_categoria WHERE cat_estatus='I';";
            $result = $db->Execute($sql);
            list($max_cat) = select_format($result->fields);

            $sql = "INSERT INTO ic_directorio_categoria (id_directorio,id_categoria) VALUES ('" . $id_directorio . "','" . $max_cat . "');";
            $result = $db->Execute($sql);
        }
    }

    $categoria = explode(",", $categoria);

    for ($i = 0; $i < count($categoria); $i++) {
        $sql = "INSERT INTO ic_directorio_categoria (id_directorio,id_categoria) VALUES ('" . $id_directorio . "','" . $categoria[$i] . "');";
        $result = $db->Execute($sql);
    }

    /////////////////////

    if (isset($_SESSION["us_codigo_ref"]) && $_SESSION["us_network"] == "facebook") {
        $sql = "INSERT INTO ic_amigo_directorio (id_amigo, id_directorio, id_usuario, tipo) 
				VALUES ('" . $_SESSION["us_codigo_ref"] . "',
						'" . $id_directorio . "',
						'" . $_SESSION['sess_usu_id'] . "',
						'PUB');";
        $result = $db->Execute($sql);
    }

    /////////////////////

    if ($result != false)
        $mensaje = "1";
    else
        $mensaje = "0";

    //funcion para registrar la modificacion hecha por el usuario
    registrar_cambios($db, date('Y-m-d'), $_SESSION['sess_nombre'], '521', "0", $nombre, 'GUARDAR');

    implantar_urls($db);

	
	////////////////////////
		
	$adicional_formato="http://www.worklife.com.ar/servicio/" . organizar_nombre($nombre)."";
	enviar_notificacion($_SESSION['sess_usu_email'], $_SESSION['sess_nombre'], "NUEVA_PUBLICACION", "1", $adicional_formato) ;
	
	////////////////////////
	
    echo '<script>
		$(document).ready(function () {
			$("#msg").html("Información guardada..."); 
			$("#msg").show(); 
				
			setTimeout(function(){ 
				$("#msg").fadeOut(); 
			}, 5000);
		});
	</script>';

    return $id_directorio;
}

/* * ************************************************************************************************************************* */

function modificar($id_directorio, $nombre, $categoria, $descripcion, $direccion, $ciudad, $estado, $zip, $telefono, $web, $info_adicional, $email, $sponsor, $logo, $latitud, $longitud, $orden, $id_usuario, $activo, $destacado, $img1, $img2, $img3, $video, $fecha, $visualizaciones, $horario_man, $horario_tar, $horario_noc, $dia_lun, $dia_mar, $dia_mie, $dia_jue, $dia_vie, $dia_sab, $dia_dom, $urgencia, $realiza_domi, $realiza_esta, $realiza_ofic, $realiza_onli, $certificado_estudio, $matricula, $garantia, $seguro, $borrarimg, $horario_24, $tabnumber, $text_auto_view, $db) {

    $dir = "images/directorio/";

    $url_full = $_FILES['logo']['tmp_name'];
    $imagen_full = $_FILES['logo']['name'];
    $n_imagen = "";
    $n_imagen1 = "";

    if ($url_full != "" && $imagen_full != "") {
        $alt = mt_rand();

        if (subir_imagen($alt . $imagen_full, $url_full, $dir)) {
            $n_imagen1 = "logo";
            $n_imagen = $dir . $alt . $imagen_full;
        }
    }

    ///////////////////////////////

    $dir = "images/galeria/";

    $url_full = $_FILES['img1']['tmp_name'];
    $imagen_full = $_FILES['img1']['name'];
    $img1 = "";
    $img11 = "";

    if ($url_full != "" && $imagen_full != "") {
        $alt = mt_rand();

        if (subir_imagen($alt . $imagen_full, $url_full, $dir)) {
            $img11 = "img1";
            $img1 = $dir . $alt . $imagen_full;
        }
    }

    /////////////

    $url_full = $_FILES['img2']['tmp_name'];
    $imagen_full = $_FILES['img2']['name'];
    $img2 = "";
    $img22 = "";

    if ($url_full != "" && $imagen_full != "") {
        $alt = mt_rand();

        if (subir_imagen($alt . $imagen_full, $url_full, $dir)) {
            $img22 = "img2";
            $img2 = $dir . $alt . $imagen_full;
        }
    }

    /////////////

    $url_full = $_FILES['img3']['tmp_name'];
    $imagen_full = $_FILES['img3']['name'];
    $img3 = "";
    $img33 = "";

    if ($url_full != "" && $imagen_full != "") {
        $alt = mt_rand();

        if (subir_imagen($alt . $imagen_full, $url_full, $dir)) {
            $img33 = "img3";
            $img3 = $dir . $alt . $imagen_full;
        }
    }

    //////////////////////////////

    if ($borrarimg == "S") {
        $n_imagen = "";
        $n_imagen1 = "logo";
    }

    //////////////////////////////

    $categoria = explode(",", $categoria);
    $sql = "DELETE FROM `ic_directorio_categoria` WHERE id_directorio='" . $id_directorio . "'";
    $result = $db->Execute($sql);

    if ($text_auto_view != "") {
        $text_auto_view = explode(",", $text_auto_view);

        for ($i = 0; $i < count($text_auto_view); $i++) {
            $sql = "INSERT INTO ic_categoria (id_tipo ,cat_creacion, cat_titulo, cat_sub_categoria, cat_estatus ) VALUES ('7','" . date("Y-m-d") . "','" . $text_auto_view[$i] . "','0','I');";
            $result = $db->Execute($sql);

            $sql = "SELECT MAX(cat_id) FROM ic_categoria WHERE cat_estatus='I';";
            $result = $db->Execute($sql);
            list($max_cat) = select_format($result->fields);

            $sql = "INSERT INTO ic_directorio_categoria (id_directorio,id_categoria) VALUES ('" . $id_directorio . "','" . $max_cat . "');";
            $result = $db->Execute($sql);
        }
    }

    for ($i = 0; $i < count($categoria); $i++) {
        $sql = "INSERT INTO ic_directorio_categoria (id_directorio,id_categoria) VALUES ('" . $id_directorio . "','" . $categoria[$i] . "');";
        $result = $db->Execute($sql);
    }

    /////////////////////

		$act = 1;
	if($estado=="" || $ciudad=="" || $zip=="" || $direccion=="" || $telefono=="" || $email==""){ $act = 0; }
		
    $result = update_bd_format(array("nombre", "categoria", "descripcion", "direccion", "ciudad", "estado", "zip", "telefono", "web", "info_adicional", "email", "sponsor", "latitud",
        "longitud", "id_usuario", "destacado", "video", "horario_man", "horario_tar",
        "horario_noc", "dia_lun", "dia_mar", "dia_mie", "dia_jue", "dia_vie", "dia_sab", "dia_dom", "urgencia", "realiza_domi", "realiza_esta", "realiza_ofic",
        "realiza_onli", "certificado_estudio", "matricula", "garantia", "seguro", "horario_24", $n_imagen1, $img11, $img22, $img33,"activo"), 
		"ic_directorio", 
		array($nombre, "", $descripcion, $direccion, $ciudad, $estado, $zip, $telefono, $web, $info_adicional, $email, $sponsor, $latitud, $longitud,
        $_SESSION['sess_usu_id'], $destacado, $video, $horario_man, $horario_tar, $horario_noc, $dia_lun,
        $dia_mar, $dia_mie, $dia_jue, $dia_vie, $dia_sab, $dia_dom, $urgencia, $realiza_domi, $realiza_esta, $realiza_ofic, $realiza_onli, $certificado_estudio,
        $matricula, $garantia, $seguro, $horario_24, $n_imagen, $img1, $img2, $img3,$act), 
		"WHERE id_directorio='" . $id_directorio . "'", 
		$db);

    if ($result != false)
        $mensaje = "2";
    else
        $mensaje = "0";

    //funcion para registrar la modificacion hecha por el usuario
    registrar_cambios($db, date('Y-m-d'), $_SESSION['sess_nombre'], '521', $id_directorio, $nombre, 'MODIFICAR');

    implantar_urls($db);

    echo '<script>
		$(document).ready(function () {
			$("#msg").html("Información actualizada..."); 
			$("#msg").show(); 
				
			setTimeout(function(){ 
				$("#msg").fadeOut(); 
			}, 5000);
		});
	</script>';
}

/* * ************************************************************************************************************************* */

function eliminar($id_directorio, $db) {
    $sql_dir = "SELECT nombre FROM ic_directorio "
            . "WHERE id_directorio = '$id_directorio' LIMIT 1;";
    $result_dir = $db->Execute($sql_dir);

    list($nombre) = select_format($result_dir->fields);

    $sql_directorio = "DELETE FROM ic_directorio WHERE id_directorio = '$id_directorio';";
    $sql_directorio_categoria = "DELETE FROM ic_directorio_categoria WHERE id_directorio = '$id_directorio';";
    $result_directorio = $db->Execute($sql_directorio);
    $result_directorio_categoria = $db->Execute($sql_directorio_categoria);

    //funcion para registrar la modificacion hecha por el usuario
    registrar_cambios($db, date('Y-m-d'), $_SESSION['sess_nombre'], '521', $id_directorio, $nombre, 'ELIMINAR');

    implantar_urls($db);

    echo '<script>
		$(document).ready(function () {
			$("#msg").html("Información eliminada..."); 
			$("#msg").show(); 
				
			setTimeout(function(){ 
				$("#msg").fadeOut(); 
			}, 5000);
		});
	</script>';
}

/* * ************************************************************************************************************************* */

function estado($id_directorio, $db) {
    $sql_activo = "SELECT activo FROM ic_directorio "
            . "WHERE id_directorio = '$id_directorio' LIMIT 1;";
    $result_activo = $db->Execute($sql_activo);

    list($stage) = select_format($result_activo->fields);

    if (empty($stage)) {
        $stage = 1;
        $msg = "pausada";
    } else {
        $stage = 0;
        $msg = "activa";
    }

    $sql_update = "UPDATE ic_directorio SET activo = '$stage' "
            . "WHERE id_directorio = '$id_directorio';";

    $result_update = $db->Execute($sql_update);

    implantar_urls($db);

    echo '<script>
		$(document).ready(function () {
			$("#msg").html("Publicación ' . $msg . '..."); 
			$("#msg").show(); 
				
			setTimeout(function(){ 
				$("#msg").fadeOut(); 
			}, 5000);
		});
	</script>';
}

/* * ************************************************************************************************************************* */
?>
<link rel="stylesheet" href="dist/app.css">
<link rel="stylesheet" href="dist/bootstrap-tagsinput.css">

<script src="js/typeahead.bundle.min.js"></script>
<script src="dist/bootstrap-tagsinput.min.js"></script>

<div class="row">
    <div class="col-sm-2 col-xs-6">
        <div style="text-align:center; width:100%; background:#7C4793; padding:3px 0; margin:5px 0;">
            <a href="info-usuario.html" style="color:#fff; font-size:16px;"><i class="fa fa-user" aria-hidden="true"></i> Mi perfil</a>
        </div>            
    </div>        
    <div class="col-sm-2 col-xs-6">
        <div style="text-align:center; width:100%; background:#FAA532; padding:3px 0; margin:5px 0;">
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

<h1><i class="fa fa-list" aria-hidden="true"></i> Mis Publicaciones</h1>

<?php
if ($funcion == "") {
	
	$sql="SELECT 
			 us_id, us_codigo_ref, us_nombre, us_pais, us_telefono, us_email, us_direccion, us_nacimiento,
			 us_last_name, us_sexo, us_descripcion, us_login, us_pass, us_postal, us_ciudad, us_estado_us,us_appartment, us_descripcion 
		  FROM ic_usuarios
		  WHERE us_id ='".$_SESSION['sess_usu_id']."' ";
			
	$result=$db->Execute($sql);

	list($us_id, $us_codigo_ref, $us_nombre, $us_pais, $us_telefono, $us_email, $us_direccion, $us_nacimiento,
		 $us_last_name, $us_sexo, $us_descripcion, $us_login, $us_pass, $us_postal, $us_ciudad, $us_estado_us, $us_appartment,$us_network)=select_format($result->fields);
	
	$link = 'Ofrece tus Servicios<br>¡RECUERDA!<br>Que podras Cargar la Cantidad de Servicios que desees, esto mejorara el Resultado para que tus clientes te contacten
	<a href="javascript:;" onclick="enviarAccion(\'\', \'nueva\');" class="label label-warning" style="font-size:16px; padding:5px 15px; color:#fff;">Carga Nueva Publicación</a>';	
	
	if($us_nombre=="" || $us_last_name=="" || $us_direccion=="" || $us_ciudad==""){
		$link = 'Debes completar tus datos de usuario para poder publicar. <a href="info-usuario.html">Ir al perfil</a>';
	}
		
    ?>
    <div class='label label-success' id='msg' style='float:right; position:relative; padding:15px 40px; display:none;'></div>

    <h2>Listado Publicaciones vigentes en la cuenta</h2>
    <br />    
    <br />
    <p><?=$link?></p>
    <br />


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
    $sql = "SELECT `id_directorio`, `nombre`, `categoria`, `descripcion`, `direccion`, `ciudad`, `estado`, `zip`, `telefono`, `web`, `info_adicional`, `email`, `sponsor`, `logo`, `latitud`, `longitud`, `orden`, `id_usuario`, `activo`, `destacado`, `img1`, `img2`, `img3`, `video`, `fecha`, `visualizaciones` FROM `ic_directorio` WHERE id_usuario='" . $_SESSION['sess_usu_id'] . "'";
    $result = $db->Execute($sql);

    if ($result->EOF) {
        ?>
        <div class="row" style="padding:0 0 15px 0; margin:0 0 10px 0;">
            <div class="col-sm-12 col-xs-12 text-center">
                No tienes publicaciones vigentes.
            </div>
        </div>
        <?php
    } else {
        while (!$result->EOF) {
            list($id_directorio, $nombre, $categoria, $descripcion, $direccion, $ciudad, $estado, $zip, $telefono, $web, $info_adicional, $email, $sponsor, $logo, $latitud, $longitud, $orden,
                    $id_usuario, $activo, $destacado, $img1, $img2, $img3, $video, $fecha, $visualizaciones) = select_format($result->fields);
            $fecha = explode(" ", $fecha);
            $fecha = explode("-", $fecha[0]);
			
            if ($logo == "") {
                $logo = "images/publication.jpg";
            }

            if ($destacado == "" || $destacado == "0") {
                $destacado = "No Destacado";
            } else {
                $destacado = "Destacado";
            }

            $color = "#ccc";
            $estado = "Activo";
            $cambiar_estado = '<i class="fa fa-pause" aria-hidden="true"></i> Pausar';
			
			if (empty($activo)) {
                $color = "#F66";
                $estado = "Inactivo";
                $cambiar_estado = '<i class="fa fa-play" aria-hidden="true"></i> Activar';
            }
			
			$mensajeact = "";
			if($estado=="" || $ciudad=="" || $zip=="" || $direccion=="" || $telefono=="" || $email==""){ $mensajeact = "<div>Debes completar todos los datos de la publicacion para activarla.</div>"; $cambiar_estado=""; }

			
            
            ?>
            <div class="row" style="padding:0 0 15px 0; border-bottom:10px solid <?= $color ?>; margin:0 0 10px 0;">
                <div class="col-sm-2 col-xs-12 text-center">
                    <a href="javascript:;" onclick="enviarAccion('<?= $id_directorio ?>', 'editar');" title="Editar"><img src="<?= $logo ?>" style="width:100%" /></a>					
                </div>        
                <div class="col-sm-7 col-xs-12 text-left">
                    <a href="javascript:;" onclick="enviarAccion('<?= $id_directorio ?>', 'editar');" style="color:#000;"><h4><?= $nombre ?></h4></a>
                    <?php
                        echo "<div style='color:#bbb; margin:10px 0 15px 0;'>Publicado desde $fecha[2] / $fecha[1] del $fecha[0]</div>"
                    ?>
                    <div><?= $direccion ?>, <?= $ciudad ?> | <?= $destacado ?> | <span style="color:<?= $color ?>;"><?= $estado ?></span> | <span style="color:#ccc;">#<?= $id_directorio ?></span></div>
					<?=$mensajeact?>
                </div>
                <div class="col-sm-3 col-xs-12 text-right">
                    <div style="margin:10px 0;">
                        <a href="<?= "servicio/" . organizar_nombre($nombre) ?>" style="color:#093"><i class="fa fa-eye" aria-hidden="true"></i> Ver publicación</a>
                    </div>
                    <div style="margin:10px 0;">
                        <a href="javascript:;" onclick="enviarAccion('<?= $id_directorio ?>', 'editar');"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Editar</a> - 
						<a href="javascript:;" onclick="enviarAccion('<?= $id_directorio ?>', 'estado');"><?= $cambiar_estado ?></a>
                    </div>
                    <div style="margin:10px 0;">
                        <a href="javascript:;" onclick="enviarAccion('<?= $id_directorio ?>', 'eliminar');" style="color:#900"><i class="fa fa-trash-o" aria-hidden="true"></i> Eliminar</a> 
                    </div>
                </div>
            </div>
            <?php
            $result->MoveNext();
        }
    }

} elseif ($funcion == "nueva" || (($funcion == "editar" || $funcion == "guardar") && $id_directorio != "")) {
	
	$sql="SELECT 
			 us_id, us_codigo_ref, us_nombre, us_pais, us_telefono, us_email, us_direccion, us_nacimiento,
			 us_last_name, us_sexo, us_descripcion, us_login, us_pass, us_postal, us_ciudad, us_estado_us,us_appartment, us_descripcion 
		  FROM ic_usuarios
		  WHERE us_id ='".$_SESSION['sess_usu_id']."' ";
			
	$result=$db->Execute($sql);

	list($us_id, $us_codigo_ref, $us_nombre, $us_pais, $us_telefono, $us_email, $us_direccion, $us_nacimiento,
		 $us_last_name, $us_sexo, $us_descripcion, $us_login, $us_pass, $us_postal, $us_ciudad, $us_estado_us, $us_appartment,$us_network)=select_format($result->fields);
	
	
	if($us_nombre=="" || $us_last_name=="" || $us_direccion=="" || $us_ciudad==""){
		die("<META HTTP-EQUIV='Refresh' CONTENT='0;URL=mis-publicaciones.html'>");
	}

    if ($id_directorio != "") {
        $sql = "SELECT id_directorio, nombre, categoria, descripcion, direccion, ciudad, estado, zip, telefono, web, info_adicional, email, sponsor, logo,
		               latitud, longitud, orden, id_usuario, activo, destacado, img1, img2, img3, video, fecha, visualizaciones, horario_man, horario_tar, 
					   horario_noc, dia_lun, dia_mar, dia_mie, dia_jue, dia_vie, dia_sab, dia_dom, urgencia, realiza_domi, realiza_esta, realiza_ofic, realiza_onli, 
					   certificado_estudio, matricula, garantia, seguro, horario_24
		        FROM ic_directorio 
				WHERE id_directorio='" . $id_directorio . "'";
        $result = $db->Execute($sql);

        list($id_directorio, $nombre, $categoria, $descripcion, $direccion, $ciudad, $estado, $zip, $telefono, $web, $info_adicional, $email, $sponsor, $logo, $latitud, $longitud, $orden, $id_usuario, $activo, $destacado, $img1, $img2, $img3, $video, $fecha, $visualizaciones, $horario_man, $horario_tar, $horario_noc, $dia_lun, $dia_mar, $dia_mie, $dia_jue, $dia_vie, $dia_sab, $dia_dom, $urgencia, $realiza_domi, $realiza_esta, $realiza_ofic, $realiza_onli, $certificado_estudio, $matricula, $garantia, $seguro, $horario_24) = select_format($result->fields);
		
		if($direccion==""){
			$direccion=$_SESSION['sess_usu_direccion'];
		}
		if($zip==""){
			$zip=$_SESSION['sess_usu_postal'];
		}
		if($ciudad==""){
			$ciudad=$_SESSION['sess_usu_ciudad'];
		}
		
    }
    ?>
    <h2><?= ucfirst($funcion) ?> publicacion</h2>
    <a href="mis-publicaciones.html" style="color:#7C4793; float:right; font-weight:700;">&laquo; Volver al listado de publicaciones</a>
    <br />
    <br />
    <div class='label label-success' id='msg' style='float:right; position:relative; padding:15px 40px; display:none;'></div>

    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" <?= (($tabnumber == "") ? "class='active'" : "") ?>><a href="#basic" aria-controls="basic" role="tab" data-toggle="tab"><b>Paso 1 <i class="fa fa-arrow-right" aria-hidden="true"></i> Describe </b></a></li>
        <li role="presentation" <?= (($tabnumber == "2") ? "class='active'" : "") ?>><a href="#address" onclick="inicializacion();" aria-controls="address" role="tab" data-toggle="tab"><b>Paso 2 <i class="fa fa-arrow-right" aria-hidden="true"></i> Datos</b></a></li>
        <li role="presentation" <?= (($tabnumber == "3") ? "class='active'" : "") ?>><a href="#gallery" aria-controls="gallery" role="tab" data-toggle="tab"><b>Paso 3 <i class="fa fa-arrow-right" aria-hidden="true"></i> Publicá</b></a></li>
    </ul>

    <form action="" name="guardar" id="guardar" method="post" enctype="multipart/form-data"> 
        <input type="hidden" name="funcion" value="guardar">
        <input type="hidden" name="id_directorio" value="<?= $id_directorio ?>">

        <div class="tab-content">
            <div role="tabpanel" class="tab-pane <?= (($tabnumber == "" || $tabnumber == "1") ? "active" : "") ?>" id="basic">

                <div class="row" style="padding:20px;">
                    <div class="col-sm-12 col-xs-12 text-left" style="margin:10px 0 !important;">
                        <strong>Titulo/Nombre servicio</strong>
                        <input name="nombre" type="text" id="nombre" title="Titulo/Nombre servicio" maxlength="60" placeholder="Ej.: Servicio de Peluqueria a Domicilio;" class="form-control" value="<?= $nombre ?>" />
                    </div>
                    <div class="col-sm-12 col-xs-12 text-left" style="margin:10px 0 !important;">
						<style>
							.twitter-typeahead{ width:100%;}
						</style>
                        <strong>Categorías</strong>
                        <input name="categoria" type="text" id="categoria" title="Categoria" placeholder="Escribe la categoría de tu servicio ej,: Instalador de Aire Acondicionado, Pomero, Abogado, Profesor de Ingles etc..." class="form-control" />
                        <em>_ Si Agregas nuevas Categorías, Recordá separarlas por coma ( , ). Podras agregar las que Quieras!, esto Mejora los resultados y te pueden encontrar más fácilmente.</em>
                        <script>
                            var categories = new Bloodhound({
                                datumTokenizer: Bloodhound.tokenizers.obj.whitespace('text'),
                                queryTokenizer: Bloodhound.tokenizers.whitespace,
                                prefetch: 'data/categories.php'
                            });
                            categories.initialize();

                            var elt = $('#categoria');
                            elt.tagsinput({
                                itemValue: 'value',
                                itemText: 'text',
                                typeaheadjs: {
                                    name: 'categories',
                                    displayKey: 'text',
                                    source: categories.ttAdapter()
                                },
                                tagClass: 'label label-warning'
                            });
    <?php
    $sql = "SELECT cat_id,cat_titulo FROM ic_categoria a, ic_directorio_categoria b WHERE id_directorio='" . $id_directorio . "' AND cat_id=id_categoria ORDER BY cat_titulo ASC";
    $result = $db->Execute($sql);

    $data = array();
    while (!$result->EOF) {
        list($id, $titulo) = select_format($result->fields);

        echo 'elt.tagsinput(\'add\', { "value": ' . $id . ' , "text": "' . $titulo . '"});
';

        $result->MoveNext();
    }
    ?>
                        </script>
                    </div>
                    <div class="col-sm-12 col-xs-12 text-left" style="margin:10px 0 !important;">
                        <strong>Descipción del servicio</strong>
                        <textarea name="descripcion" title="Descripcion del servicio" placeholder="Escribe una descripcion" rows="5" class="form-control" id="descripcion"><?= $descripcion ?></textarea>
						<em>_ ¡ATENCIÓN! No se podran introducir datos personales, numeros de telefonos, correos, etc. </em>
                    </div>
                    <div class="col-sm-4 col-xs-12 text-left" style="margin:10px 0 !important;">
                        <strong>Horario de atención</strong><br/>
                        <input type="checkbox" name="horario_man" id="horario_man" <?= (($horario_man == "1") ? "checked" : "") ?> value="1" /> Mañana
                        <br/>
                        <input type="checkbox" name="horario_tar" id="horario_tar" <?= (($horario_tar == "1") ? "checked" : "") ?> value="1" /> Tarde
                        <br/>
                        <input type="checkbox" name="horario_noc" id="horario_noc" <?= (($horario_noc == "1") ? "checked" : "") ?> value="1" /> Noche
                        <br/>
                        <input type="checkbox" name="horario_24" id="horario_24" <?= (($horario_24 == "1") ? "checked" : "") ?> value="1" /> Servicio 24hs
                    </div>
                    <div class="col-sm-4 col-xs-12 text-left" style="margin:10px 0 !important;">
                        <strong>Dias de la semana</strong><br/>
                        <table>
                            <tr>
                                <td valign="top">
                                    <input type="checkbox" name="dia_lun" id="dia_lun" <?= (($dia_lun == "1") ? "checked" : "") ?> value="1" /> Lunes
                                    <br/>
                                    <input type="checkbox" name="dia_mar" id="dia_mar" <?= (($dia_mar == "1") ? "checked" : "") ?> value="1" /> Martes
                                    <br/>
                                    <input type="checkbox" name="dia_mie" id="dia_mie" <?= (($dia_mie == "1") ? "checked" : "") ?> value="1" /> Miércoles
                                    <br/>
                                    <input type="checkbox" name="dia_jue" id="dia_jue" <?= (($dia_jue == "1") ? "checked" : "") ?> value="1" /> Jueves
                                    <br/>
                                    <input type="checkbox" name="dia_vie" id="dia_vie" <?= (($dia_vie == "1") ? "checked" : "") ?> value="1" /> Viernes
                                </td>                
                                <td valign="top">
                                    <input type="checkbox" name="dia_sab" id="dia_sab" <?= (($dia_sab == "1") ? "checked" : "") ?> value="1" /> Sabado
                                    <br/>
                                    <input type="checkbox" name="dia_dom" id="dia_dom" <?= (($dia_dom == "1") ? "checked" : "") ?> value="1" /> Domingo
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div class="col-sm-4 col-xs-12 text-left" style="margin:10px 0 !important;">
                        <strong>Donde realizará el servicio</strong><br/>
                        <input type="checkbox" name="realiza_domi" id="realiza_domi" <?= (($realiza_domi == "1") ? "checked" : "") ?> value="1" /> a Domicilio 
                        <br/>
                        <input type="checkbox" name="realiza_esta" id="realiza_esta" <?= (($realiza_esta == "1") ? "checked" : "") ?> value="1" /> en Establecimiento/Instituto
                        <br/>
                        <input type="checkbox" name="realiza_ofic" id="realiza_ofic" <?= (($realiza_ofic == "1") ? "checked" : "") ?> value="1" /> en Oficina/ Local
                        <br/>
                        <input type="checkbox" name="realiza_onli" id="realiza_onli" <?= (($realiza_onli == "1") ? "checked" : "") ?> value="1" /> On-Line/Freelance
                    </div>

                    <div class="col-sm-6 col-xs-12 text-left" style="margin:10px 0 !important;">
                        <strong>Certificados/Estudios</strong>
                        <input name="certificado_estudio" type="text" maxlength="60" id="certificado_estudio" class="form-control" value="<?= $certificado_estudio ?>" />
                        <em>_ Si tenés certificados o estúdios profesionales, adicioná la información, esto genera más confianza en tu público</em>
                    </div>
                    <div class="col-sm-6 col-xs-12 text-left" style="margin:10px 0 !important;">
                        <strong>N°. Matrícula</strong> 
                        <input name="matricula" type="text" maxlength="60" id="matricula" class="form-control" value="<?= $matricula ?>" />
                        <em>_ Si tenés mátricula comercial o profesional, adicioná el numero, esto genera mas confianza en tu público</em>
                    </div>

                    <div class="col-sm-6 col-xs-12 text-left" style="margin:10px 0 !important;">
                        <strong>Seguro</strong> 
                        <input name="seguro" type="text" maxlength="60" id="seguro" class="form-control letras" value="<?= $seguro ?>" />
                        <em>_ En caso de Brindar un Servicio que requiera seguro ( ART, etc ) posee alguna Cobertura </em>
                    </div>    
                    <div class="col-sm-6 col-xs-12 text-left" style="margin:10px 0 !important;">
                        <strong>Garantía</strong>
                        <select name="garantia" id="garantia" class="form-control" >
                            <?php cargar_lista_estatica("0,1,2,3,4,5,6,7,8,9,10,11,12,18,24", "Sin garantía,1 mes,2 meses,3 meses,4 meses,5 meses,6 meses,7 meses,8 meses,9 meses,10 meses,11 meses,1 año,18 meses,2 años", 0, $garantia); ?>
                        </select>
                    </div>

                    <div class="col-sm-12 col-xs-12 text-left" style="margin:10px 0 !important;">
                        <strong>Información adicional</strong>
                        <textarea name="info_adicional" rows="5" class="form-control" id="info_adicional"><?= $info_adicional ?></textarea>
						<em>_ Aquí podrás detallar formas de cobro dependiendo de tu Servicio ,como por ej. : si es por hora, por día , por mes, por consulta, por trabajo completo , por M2. y también  si incluyes o no adicionales al mismo</em>
                    </div>

                </div>

                <div class="row">
                    <div class="col-sm-12 col-xs-12 text-right">
                        <input type="button" value="Guardar información y continuar &raquo;" onclick="enviar('nombre,descripcion', '2');" />
                        <script></script>
                    </div>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane <?= (($tabnumber == "2") ? "active" : "") ?>" id="address">
                <div class="row" style="padding:20px;">
                    <div class="col-sm-6 col-xs-12 text-left" style="margin:10px 0 !important;">
                        Provincia *
                        <select name="estado" id="estado" title="Provincia" class="form-control">
                            <?= listado_estados_us($estado, "1") ?>
                        </select>
                    </div>
                    <div class="col-sm-6 col-xs-12 text-left" style="margin:10px 0 !important;">
                        Ciudad *
                        <input type="text" name="ciudad" id="ciudad" title="Ciudad" value="<?= $ciudad ?>"  class="form-control" onblur="captura();"/>
                    </div>
                    <div class="col-sm-6 col-xs-12 text-left" style="margin:10px 0 !important;">
                        Código postal *
                        <input type="text" name="zip" id="zip"  title="Codigo Postal" value="<?= $zip ?>"  class="form-control"/>
                    </div>
                    <div class="col-sm-6 col-xs-12 text-left" style="margin:10px 0 !important;">
                        Teléfono *
                        <input type="text" name="telefono" title="Telefono" id="telefono" value="<?= $telefono ?>"  class="form-control"/>
                    </div>

                    <div class="col-sm-12 col-xs-12 text-left" style="margin:10px 0 !important;">
                        Dirección *
                        <input type="text" name="direccion" id="direccion"  title="Direccion" value="<?= $direccion ?>" class="form-control" onblur="captura();"> 
                        <a href="javascript:;" onclick="captura();">[Obtener Coordenadas]</a>
                        <input type="hidden" name="longitud" id="longitud" value="<?= $longitud ?>">
                        <input type="hidden" name="latitud" id="latitud" value="<?= $latitud ?>">

                        <script type="text/javascript" src="http://maps.google.com/maps/api/js?language=es&key=AIzaSyA9p9bG0RcWHM6jy2f6U3ea4MvSCPC3Zhc"></script>
                        <br />
                        Arrastras marcador en la posición<br />
                        <div id="mapa" style="width:100%; height:300px;"></div>

                        <script type="text/javascript">
                            function captura() {

                                var address = "";

                                if (document.getElementById("direccion").value != "" && document.getElementById("ciudad").value != "") {
                                    address = document.getElementById("direccion").value + ' ' + document.getElementById("ciudad").value + ' ' + document.getElementById("estado").value + ' ' + document.getElementById("zip").value;
                                }

                                var options = {
                                    zoom: 13,
                                    mapTypeId: google.maps.MapTypeId.ROADMAP,
                                    center: {lat: -34.6156625, lng: -58.5033383}
                                };

                                map = new google.maps.Map(document.getElementById('mapa'), options);

                                if (address.trim() != '') {

                                    var geoCoder = new google.maps.Geocoder(address)
                                    var request = {address: address};
                                    latlng = geoCoder.geocode(request, function (result, status) {
                                        latlng = new google.maps.LatLng(result[0].geometry.location.lat(), result[0].geometry.location.lng());

                                        map.setOptions({
                                            zoom: 13,
                                            center: latlng,
                                            mapTypeId: google.maps.MapTypeId.ROADMAP
                                        });

                                        var marcador = new google.maps.Marker({
                                            position: latlng,
                                            map: map,
                                            animation: google.maps.Animation.DROP,
                                            draggable: true,
                                            title: "A marker that can be moved"
                                        });


                                        document.getElementById("latitud").value = result[0].geometry.location.lat();
                                        document.getElementById("longitud").value = result[0].geometry.location.lng();

                                        //Creo un evento asociado a "marcador" para cuando se termina de mover ("dragend") el mismo.
                                        google.maps.event.addListener(marcador, "dragend", function (evento) {
                                            //Obtengo las coordenadas separadas
                                            var latitud = evento.latLng.lat();
                                            var longitud = evento.latLng.lng();

                                            document.getElementById("latitud").value = latitud;
                                            document.getElementById("longitud").value = longitud;
                                        });
                                    });
                                }
                            }

                            //////////////////////////////////////////////

                            function inicializacion() {
                                var lat = '<?= $latitud ?>';
                                var lon = '<?= $longitud ?>';

                                var options = {
                                    zoom: 13,
                                    mapTypeId: google.maps.MapTypeId.ROADMAP,
                                    center: {lat: -34.6156625, lng: -58.5033383}
                                };

                                map = new google.maps.Map(document.getElementById('mapa'), options);

                                if (lat != "" && lon != "") {
                                    //Creo un nuevo mapa situado en Buenos Aires, Argentina, con 13 de Zoom y del tipo ROADMAP
                                    map.setCenter(new google.maps.LatLng(lat, lon));
                                    //var mapa = new google.maps.Map(document.getElementById("mapa"), {center: , zoom: 13, mapTypeId: google.maps.MapTypeId.ROADMAP});


                                    //Creo un marcador cualquiera situado en una coordenada cualquiera, con la propiedad "draggable" como "true".
                                    var marcador = new google.maps.Marker({position: new google.maps.LatLng(lat, lon), map: map, animation: google.maps.Animation.DROP, draggable: true, title: "A marker that can be moved"});

                                    //Creo un evento asociado a "marcador" para cuando se termina de mover ("dragend") el mismo.
                                    google.maps.event.addListener(marcador, "dragend", function (evento) {
                                        //Obtengo las coordenadas separadas
                                        var latitud = evento.latLng.lat();
                                        var longitud = evento.latLng.lng();

                                        document.getElementById("latitud").value = latitud;
                                        document.getElementById("longitud").value = longitud;
                                    });
                                }

                            }

                            //////////////////////////////////////////////


                        </script>
                    </div>

                    <div class="col-sm-6 col-xs-12 text-left" style="margin:10px 0 !important;">
                        Email de contacto *
                        <input type="text" name="email" id="email" title="Email" value="<?= $email ?>"  class="form-control">
                    </div>
                    <div class="col-sm-6 col-xs-12 text-left" style="margin:10px 0 !important;">
                        Página Web
                        <input type="text" name="web" id="web" value="<?= $web ?>"  class="form-control"/>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12 col-xs-12 text-right">
                        <input type="button" value="Guardar dirección y continuar &raquo;" onclick="enviar('estado,ciudad,zip,direccion,telefono,email', '3');" />
                    </div>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane <?= (($tabnumber == "3") ? "active" : "") ?>" id="gallery">
                <div class="row">
                    <div class="col-sm-12 col-xs-12 text-left">
                        <strong>* Recuerda:</strong> luego de seleccionar las imágenes, debe dar click en el boton guardar.
                    </div>
                </div>
                <div class="row" style="padding:20px;">
                    <div class="col-sm-6 col-xs-12 text-left" style="margin:10px 0 !important;">
                        ¿Tienes un Logo? Agregalo ahora
                        <input type="file" name="logo" id="logo"  class="form-control"/>
                        <br />
                        <img src="<?= ($logo == "") ? "images/publication.jpg" : $logo ?>" height="100" alt="" border="0" />
                    </div>
					<div class="col-sm-6 col-xs-12 text-left" style="margin:10px 0 !important;">
					</div>
				</div>
				<div class="row" style="padding:20px;">
					<div class="col-sm-12 col-xs-12 text-left" style="margin:10px 0 !important;">
						Ingresa fotos de tu Servicio.  Muéstralo en detalle, con fondo blanco y bien iluminado. No incluyas logos, banners ni textos promocionales.
					</div>
                    <div class="col-sm-4 col-xs-12 text-left" style="margin:10px 0 !important;">
                        Imagen 1
                        <input type="file" name="img1" id="img1"  class="form-control"/>
                        <br />
                        <img src="<?= ($img1 == "") ? "images/publication.gif" : $img1 ?>" height="100" alt="" border="0" />
                    </div>

                    <div class="col-sm-4 col-xs-12 text-left" style="margin:10px 0 !important;">
                        Imagen 2
                        <input type="file" name="img2" id="img2"  class="form-control"/>
                        <br />
                        <img src="<?= ($img2 == "") ? "images/publication.gif" : $img2 ?>" height="100" alt="" border="0" />
                    </div>
                    <div class="col-sm-4 col-xs-12 text-left" style="margin:10px 0 !important;">
                        Imagen 3
                        <input type="file" name="img3" id="img3"  class="form-control"/>
                        <br />
                        <img src="<?= ($img3 == "") ? "images/publication.gif" : $img3 ?>" height="100" alt="" border="0" />
                    </div> 
				</div>
				<div class="row" style="padding:20px;">
                    <div class="col-sm-12 col-xs-12 text-left" style="margin:10px 0 !important;">
                        Video
                        <input type="text" name="video" id="video" value="<?= $video ?>"  class="form-control"/>
                        <em>_ Adicioná el link de YOUTUBE de tu video principal, para que se muestre en el detalle de tu publicación. Recordá que debes adicionar el link o el vinculo de compartir.</em>
                    </div>           
                </div>

                <div class="row">
                    <div class="col-sm-12 col-xs-12 text-right">
                        <input type="button" value="Guardar Imagenes y Listo!" onclick="enviar('nombre,descripcion', '4');" />
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" name="tabnumber" id="tabnumber" value="" />
    </form>

    <script language="javascript">

		$(".letras").keypress(function (key) {
            window.console.log(key.charCode)
            if ((key.charCode < 97 || key.charCode > 122)//letras mayusculas
                && (key.charCode < 65 || key.charCode > 90) //letras minusculas
                && (key.charCode != 45) //retroceso
                && (key.charCode != 241) //ñ
                 && (key.charCode != 209) //Ñ
                 && (key.charCode != 32) //espacio
                 && (key.charCode != 225) //á
                 && (key.charCode != 233) //é
                 && (key.charCode != 237) //í
                 && (key.charCode != 243) //ó
                 && (key.charCode != 250) //ú
                 && (key.charCode != 193) //Á
                 && (key.charCode != 201) //É
                 && (key.charCode != 205) //Í
                 && (key.charCode != 211) //Ó
                 && (key.charCode != 218) //Ú
 
                )
                return false;
        });
	   
        function enviar(campos, tabpanel) {

            var camposObligatorios = campos.split(",");
            for (i = 0; i < camposObligatorios.length; i++)
            {
                if (document.getElementById(camposObligatorios[i]).value == "") {
                    alert("Campo " + document.getElementById(camposObligatorios[i]).title + " es obligatorio");
                    $("#" + camposObligatorios[i] + "").css("border", "1px solid #ff0000");
                    $("#" + camposObligatorios[i] + "").focus();
                    return;
                } else {
                    $("#" + camposObligatorios[i] + "").css("border", "1px solid #eee");
                }
            }

            if (document.getElementById("categoria").value == "" && $("input[name='text_auto_view']").val() == "") {
                alert("Debe agregar al menos una categoria a la publicacion!");
                $("#categoria").css("border", "1px solid #ff0000");
                $("#categoria").focus();
            } else {
                $("#categoria").css("border", "1px solid #eee");
            }

            document.getElementById("tabnumber").value = tabpanel;
            document.guardar.submit();
        }

    </script>
    <?php
}
?>

