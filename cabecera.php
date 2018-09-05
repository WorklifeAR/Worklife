<?php

//error_reporting(E_ALL);
//ini_set('display_errors', '1');

date_default_timezone_set("America/Argentina/Buenos_Aires");

session_start();
$_SESSION['act'] = 1;
$_SESSION['poss'] = 0;

$path = "adodb/adodb.inc.php";
include ("admin/var.php");
include ("conexion.php");
include ("admin/lib/url.php");
include ("admin/lib/general.php");
include ("admin/lib/usuarios.php");
include ("admin/lib/fechas.php");
include ("bloque.php");
include ("contenido.php");
idioma_session($db);
include ("language/language" . $_SESSION['idioma'] . ".php");
include ("compras/funciones_carrito.php");
include ("catalogo/funciones_catalogo.php");

$sql = "SELECT c_cabeceras,c_titulo,c_grupo_defecto,c_lang,c_activo_new_usu,c_base_location,c_tag_descripcion,c_scripts FROM ic_config";
$result = $db->Execute($sql);

list($c_cabeceras, $c_titulo, $grupo_defecto, $c_lang, $c_activo_new_usu, $c_base_location, $c_tag_descripcion, $c_scripts) = select_format($result->fields);

//Variables para la carga de un modulo especifico
$variables_ver = variables_metodo("mod,identificador,grupo_noti,buscar,salir,fullview,item");
$mod = $variables_ver[0];
$identificador = $variables_ver[1];
$grupo_noti = $variables_ver[2];
$buscar = $variables_ver[3];
$salir = $variables_ver[4];
$fullview = $variables_ver[5];
$item = $variables_ver[6];

if ($salir == 1) {
    cerrar_sesion("index.php");
}

if ($fullview == "true") {
    $_SESSION['fullview'] = "true";
} else {
    $_SESSION['fullview'] = "false";
}

if(strstr($_SERVER['PHP_SELF'], "detalle-servicio.php")){
	$variables_ver = variables_metodo("id");
	$id_directorio = $variables_ver[0];
	
	$sql = "SELECT id_directorio, nombre, categoria, descripcion, direccion, ciudad, estado, zip, telefono, web, info_adicional, email, sponsor, logo,
				   latitud, longitud, orden, id_usuario, activo, destacado, img1, img2, img3, video, fecha, visualizaciones, horario_man, horario_tar, 
				   horario_noc, dia_lun, dia_mar, dia_mie, dia_jue, dia_vie, dia_sab, dia_dom, urgencia, realiza_domi, realiza_esta, realiza_ofic, realiza_onli, 
				   certificado_estudio, matricula, garantia, seguro, horario_24
			FROM ic_directorio WHERE id_directorio=".$id_directorio."";
	$result=$db->Execute($sql);
	
	list($id_directorio,$nombre,$categoria,$descripcion,$direccion,$ciudad,$estado,$zip,$telefono,$web,$info_adicional,$email,$sponsor,$logo,$latitud,
		 $longitud,$orden,$id_usuario,$activo,$destacado,$img1,$img2,$img3,$video,$fecha,$visualizaciones,$horario_man,$horario_tar,$horario_noc,
		 $dia_lun,$dia_mar,$dia_mie,$dia_jue,$dia_vie,$dia_sab,$dia_dom,$urgencia,$realiza_domi,$realiza_esta,$realiza_ofic,$realiza_onli,$certificado_estudio,
		 $matricula,$garantia,$seguro,$horario_24)=select_format($result->fields);
		 
	$c_titulo = $nombre . " - " .$c_titulo;
	$c_tag_descripcion = strip_tags($descripcion);
}else{
	//Cambiar Tag de pagina si el contenido trea informacion
	$contenidos_tags = cargar_tags($mod, $identificador, $c_cabeceras, $c_titulo, $c_tag_descripcion, $db);
	$c_titulo = $contenidos_tags[0];
	$c_cabeceras = $contenidos_tags[1];
	$c_tag_descripcion = $contenidos_tags[2];
}
/*
$contenido_facebook = "";

if (($mod == "producto" && $item != "") || ($mod == "noticias" && $identificador != "")) {
    if ($mod == "producto") {
        $identificador = $item;
    }
    $contenido_facebook = cargar_tags_facebook($mod, $identificador, $db);
}*/

$variablesPaginas = variables_metodo('funcion,us_id,us_codigo_ref,us_nombre,us_pais,us_telefono,us_email,us_direccion,us_nacimiento,us_last_name,us_sexo,us_descripcion,us_login,us_pass,us_postal,us_ciudad,us_estado_us,us_appartment,op_usu,correo_recordar,destino,us_desc_prof,us_desc_pers,us_matricula,us_referencia,us_dni,us_cuit,us_constancia,us_tipo_serv');

$funcion = $variablesPaginas[0];
$us_id = $variablesPaginas[1];
$us_codigo_ref = $variablesPaginas[2];
$us_nombre = $variablesPaginas[3];
$us_pais = $variablesPaginas[4];
$us_telefono = $variablesPaginas[5];
$us_email = $variablesPaginas[6];
$us_direccion = $variablesPaginas[7];
$us_nacimiento = $variablesPaginas[8];
$us_last_name = $variablesPaginas[9];
$us_sexo = $variablesPaginas[10];
$us_descripcion = $variablesPaginas[11];
$us_login = $variablesPaginas[12];
$us_pass = $variablesPaginas[13];
$us_postal = $variablesPaginas[14];
$us_ciudad = $variablesPaginas[15];
$us_estado_us = $variablesPaginas[16];
$us_appartment = $variablesPaginas[17];
$op_usu = $variablesPaginas[18];
$correo_recordar = $variablesPaginas[19];
$destino = $variablesPaginas[20];
$us_desc_prof = $variablesPaginas[21];
$us_desc_pers = $variablesPaginas[22];
$us_matricula = $variablesPaginas[23];
$us_referencia = $variablesPaginas[24];
$us_dni = $variablesPaginas[25];
$us_cuit = $variablesPaginas[26];
$us_constancia = $variablesPaginas[27];
$us_tipo_serv = $variablesPaginas[28];


if ($funcion == "login_usuario") {
    iniciar_session($us_login, $us_pass, $destino, $db);
}

if ($funcion == "modificar_usuario" || $funcion == "modificar_usuario_premium") {
    if ($us_id != "") {
        modificar_usuario($us_id, $us_codigo_ref, $us_nombre, $us_pais, $us_telefono, $us_email, $us_direccion, $us_nacimiento, $us_last_name, $us_sexo, $us_descripcion, $us_login, $us_pass, $us_postal, $us_ciudad, $us_estado_us, $us_appartment, $us_foto,$funcion,$us_desc_prof,$us_desc_pers,$us_matricula,$us_referencia,$us_dni,$us_cuit,$us_constancia,$us_tipo_serv);
    } elseif ($us_id == "") {
        //crear_usuario($us_id, $us_codigo_ref, $us_nombre, $us_pais, $us_telefono, $us_email, $us_direccion, $us_nacimiento, $us_last_name, $us_sexo, $us_descripcion, $us_login, $us_pass, $us_postal, $us_ciudad, $us_estado_us, $us_appartment, $grupo_defecto);
    }
}

if (($funcion == "recordar_cuenta") && ($correo_recordar != "")) {
    enviar_cuenta($correo_recordar, $db);
}

$_SESSION['c_base_location'] = $c_base_location;
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <title><?= $c_titulo ?></title>

        <meta name="description" content="<?= $c_tag_descripcion ?>">
        <meta name="keywords" content="<?= $c_cabeceras ?>">
        <meta name="robots" content="INDEX,FOLLOW"/>
        <meta name="copyright" content="Worklife"/>
        <link rel="SHORTCUT ICON" href="favicon.ico"/>

<?= $c_scripts ?>
		
        <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700" rel="stylesheet"> 
        
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="asset/css/animate.css" media="screen">
        <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
        <script type="text/javascript" src="js/jquery-ui.min.js"></script>
        <script type="text/javascript" src="js/jquery.migrate.js"></script>
        <script type="text/javascript" src="js/modernizrr.js"></script>
        <script type="text/javascript" src="js/jquery.ui.touch-punch.min.js"></script>
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>


        <script type="text/javascript" src="js/jquery.fitvids.js"></script>
        <script type="text/javascript" src="js/owl.carousel.min.js"></script>
        <script type="text/javascript" src="js/jquery.isotope.min.js"></script>
        <script type="text/javascript" src="js/jquery.appear.js"></script>
        <script type="text/javascript" src="js/count-to.js"></script>
        <script type="text/javascript" src="js/jquery.textillate.js"></script>
        <script type="text/javascript" src="js/jquery.lettering.js"></script>
        <script type="text/javascript" src="js/jquery.easypiechart.min.js"></script>
        <script type="text/javascript" src="js/jquery.nicescroll.min.js"></script>
        <script type="text/javascript" src="js/jquery.parallax.js"></script>
        <script type="text/javascript" src="js/mediaelement-and-player.js"></script>
        <script type="text/javascript" src="js/jquery.slicknav.js"></script>
        <script type="text/javascript" src="js/jquery.sudoSlider.min.js"></script>
        <script type="text/javascript" src="js/nivo-lightbox.min.js"></script>

        <link href="estilo.css" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="font-awesome-4.7.0/css/font-awesome.min.css">
        
        
        
        <link rel="stylesheet" type="text/css" href="bannerscollection_zoominout.css">
        <script type="text/javascript" src="slider/slider/jquery.slider.min.js"></script>
        <script type="text/javascript" src="js/add-ddaccordion.js"></script>
        <script type="text/javascript" src="js/tinynav.min.js"></script>
        <script type="text/javascript" src="js/bannerscollection_zoominout.js"></script>
        <script type="text/javascript" src="js/datepicker-es.js"></script>

    </head>
    <body style="display:none;">

