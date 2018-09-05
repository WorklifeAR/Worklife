<?php
	ini_set('display_errors', 1);
	
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
	include ("language/language".$_SESSION['idioma'].".php");
	//include ("compras/funciones_carrito.php");
	//include ("catalogo/funciones_catalogo.php");
	
	$sql="SELECT c_cabeceras,c_titulo,c_grupo_defecto,c_lang,c_activo_new_usu,c_base_location,c_tag_descripcion,c_scripts FROM ic_config";
	$result=$db->Execute($sql);

	list($c_cabeceras,$c_titulo,$grupo_defecto,$c_lang,$c_activo_new_usu,$c_base_location,$c_tag_descripcion,$c_scripts)=select_format($result->fields);

	//Variables para la carga de un modulo especifico
	$variables_ver = variables_metodo("mod,identificador,grupo_noti,buscar,salir,fullview,item");
	$mod= 				$variables_ver[0];
	$identificador= 	$variables_ver[1];
	$grupo_noti= 		$variables_ver[2];
	$buscar= 			$variables_ver[3];
	$salir= 			$variables_ver[4];
	$fullview= 			$variables_ver[5];
	$item= 				$variables_ver[6];
	
	if($salir==1){
		cerrar_sesion("index.php");
	}
	
	if($fullview=="true"){
		$_SESSION['fullview'] = "true";
	}else{
		$_SESSION['fullview'] = "false";
	}
	
	//Cambiar Tag de pagina si el contenido trea informacion
	$contenidos_tags = cargar_tags($mod, $identificador, $c_cabeceras, $c_titulo, $c_tag_descripcion, $db);
	$c_titulo = $contenidos_tags[0];
	$c_cabeceras = $contenidos_tags[1];
	$c_tag_descripcion = $contenidos_tags[2];
	
	$contenido_facebook="";
	
	if(($mod=="producto" && $item!="") || ($mod=="noticias" && $identificador!="")){
		if($mod=="producto"){
			$identificador=$item;
		}
		$contenido_facebook=cargar_tags_facebook($mod, $identificador, $db);
	}
	
	$variablesPaginas = variables_metodo('funcion,us_id,us_codigo_ref,us_nombre,us_pais,us_telefono,us_email,us_direccion,us_nacimiento,us_last_name,us_sexo,us_descripcion,us_login,us_pass,us_postal,us_ciudad,us_estado_us,us_appartment,op_usu,correo_recordar,destino');
	
	$funcion= 			$variablesPaginas[0];
	$us_id= 			$variablesPaginas[1]; 
	$us_codigo_ref= 			$variablesPaginas[2];
	$us_nombre= 			$variablesPaginas[3];
	$us_pais= 			$variablesPaginas[4];
	$us_telefono= 			$variablesPaginas[5];
	$us_email= 			$variablesPaginas[6];
	$us_direccion= 			$variablesPaginas[7];
	$us_nacimiento= 			$variablesPaginas[8];
	$us_last_name= 			$variablesPaginas[9];
	$us_sexo= 			$variablesPaginas[10];
	$us_descripcion= 			$variablesPaginas[11];
	$us_login= 			$variablesPaginas[12];
	$us_pass= 			$variablesPaginas[13];
	$us_postal= 			$variablesPaginas[14];
	$us_ciudad= 			$variablesPaginas[15];
	$us_estado_us= 			$variablesPaginas[16];
	$us_appartment= 			$variablesPaginas[17];
	$op_usu= 			$variablesPaginas[18];
	$correo_recordar=	$variablesPaginas[19];
	$destino=	$variablesPaginas[20];

	if($funcion == "login_usuario"){
		iniciar_session($us_login,$us_pass,$destino,$db);
	}

	if($funcion == "modificar_usuario"){
		if($us_id!=""){
			modificar_usuario($us_id, $us_codigo_ref, $us_nombre, $us_pais, $us_telefono, $us_email, $us_direccion, $us_nacimiento,
			 				$us_last_name, $us_sexo, $us_descripcion, $us_login, $us_pass, $us_postal, $us_ciudad, $us_estado_us, $us_appartment);
		}elseif($us_id==""){
			crear_usuario($us_id, $us_codigo_ref, $us_nombre, $us_pais, $us_telefono, $us_email, $us_direccion, $us_nacimiento,
			 $us_last_name, $us_sexo, $us_descripcion, $us_login, $us_pass, $us_postal, $us_ciudad, $us_estado_us, $us_appartment,$grupo_defecto);
		}
	}

	if ( ($funcion == "recordar_cuenta") && ($correo_recordar !="") ){
		enviar_cuenta($correo_recordar, $db);
	}

	$_SESSION['c_base_location'] = $c_base_location;
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title><?=$c_titulo?></title>

<meta name="description" content="<?=$c_tag_descripcion?>">
<meta name="keywords" content="<?=$c_cabeceras?>">
<meta name="robots" content="INDEX,FOLLOW"/>
<meta name="copyright" content="2015 by WA"/>

<base href="<?=$c_base_location?>" target="_top" />
<link rel="SHORTCUT ICON" href="favicon.ico"/>

<?=$c_scripts?>

<link rel="stylesheet" href="asset/css/bootstrap.min.css" type="text/css" media="screen">
<link rel="stylesheet" type="text/css" href="asset/css/animate.css" media="screen">

<script type="text/javascript" src="js/jquery-2.1.4.min.js"></script>
<script type="text/javascript" src="js/jquery.migrate.js"></script>
<script type="text/javascript" src="js/modernizrr.js"></script>

<script type="text/javascript" src="asset/js/bootstrap.min.js"></script>

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
<link href="js/jquery-ui.css" rel="stylesheet" type="text/css">

</head>
<body >

<?=$contenido_facebook?>
