<?php

//Idiomas Modulos
define ('_MOD_NOTICIAS_ES', 'novedades');
define ('_MOD_NOTICIAS_EN', 'novedades');
define ('_MOD_CONTENIDO_ES', 'contenido');
define ('_MOD_CONTENIDO_EN', 'contenido');
define ('_MOD_GALERIA_ES', 'galeria');
define ('_MOD_GALERIA_EN', 'galeria');
define ('_MOD_EVENTOS_ES', 'eventos');
define ('_MOD_EVENTOS_EN', 'eventos');
define ('_MOD_ENCUESTA_ES', 'encuestas');
define ('_MOD_ENCUESTA_EN', 'encuestas');
define ('_MOD_CATPRO_ES', 'publicaciones');
define ('_MOD_CATPRO_EN', 'publicaciones');
define ('_MOD_EMP_ES', 'empresa');
define ('_MOD_EMP_EN', 'empresa');
define ('_MOD_DIR_ES', 'directorio');
define ('_MOD_DIR_EN', 'directorio');

/***********************************************************************************************************************************/

/*RewriteRule contactenos.html	ver.php?mod=contactenos
RewriteRule novedades.html	ver.php?mod=noticias&limite=true
RewriteRule faq.html	ver.php?mod=faq
RewriteRule videos.html	ver.php?mod=videos
RewriteRule galeria.html	ver.php?mod=galeria
RewriteRule eventos.html	ver.php?mod=eventos
RewriteRule info-usuario.html	user.php?op_usu=datos_usuario
RewriteRule usuarios.html	user.php
RewriteRule recordar-clave.html	user.php?op_usu=recordar_password
RewriteRule directorio-de-empresas.html	directorio.php

RewriteRule index.html	index.php

RewriteRule contact-us.html	ver.php?mod=contactenos
RewriteRule news.html	ver.php?mod=noticias&limite=true
RewriteRule faq.html	ver.php?mod=faq
RewriteRule videos.html	ver.php?mod=videos
RewriteRule gallery.html	ver.php?mod=galeria
RewriteRule events.html	ver.php?mod=eventos
RewriteRule info-user.html	user.php?op_usu=nuevo_usuario
RewriteRule user.html	user.php
RewriteRule remenber-password.html	user.php?op_usu=recordar_password
RewriteRule business-directory.html	directorio.php

RewriteRule mis-publicaciones.html	ver.php?mod=mis_publicaciones
RewriteRule my-publications.html	ver.php?mod=mis_publicaciones*/


function implantar_urls($db){

	$pos   = strripos($_SESSION['c_base_location'], $_SERVER['HTTP_HOST']);
	$lar = strlen($_SERVER['HTTP_HOST']);
	
	$host_final = substr($_SESSION['c_base_location'], ($pos+$lar), strlen($_SESSION['c_base_location']));
	
	$lineas = "# Activar RewriteEngine - New Generation ".date('d-m-Y')."
Options +SymLinksIfOwnerMatch
RewriteEngine on
RewriteBase ".$host_final."

ErrorDocument 404 /404.php
ErrorDocument 500 /500.php

RewriteRule info-usuario.html	user.php?op_usu=datos_usuario
RewriteRule usuarios.html	user.php
RewriteRule recordar-clave.html	user.php?op_usu=recordar_password
RewriteRule mis-publicaciones.html?(.*)$	user.php?op_usu=publicaciones&$1 [L,QSA]
RewriteRule mis-publicaciones.html	user.php?op_usu=publicaciones
RewriteRule publicaciones-favoritas.html	user.php?op_usu=favoritos
RewriteRule publicar-ahora.html	user.php?op_usu=publicaciones
RewriteRule mi-reputacion.html	user.php?op_usu=reputacion
RewriteRule listado-servicios.html	listado-servicios.php
RewriteRule contactenos.html	ver.php?mod=contactenos
RewriteRule invitar-amigos.html	ver.php?mod=invitar_amigos
RewriteRule denuncias.html	ver.php?mod=denuncia
RewriteRule cotizaciones.html	user.php?op_usu=cotizaciones
RewriteRule servicio-de-ayuda.html	ver.php?mod=faq
RewriteRule info-usuario-premium.html	user.php?op_usu=premium

RewriteRule index.html	index.php


";

	$sql="SELECT lang_prefijo FROM ic_language WHERE lang_prefijo != ''";
	$result_lan=$db->Execute($sql);
		
	while (!$result_lan->EOF){
	
		list ($lang_prefijo) = select_format($result_lan->fields);
		
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
		$sql="SELECT pg_id,pg_titulo FROM ic_contenido WHERE l_lang ='".$lang_prefijo."'";
		$result=$db->Execute($sql);
			
		while (!$result->EOF){
		
			list ($pg_id,$pg_titulo) = select_format($result->fields);
			
            $prefijo=_MOD_CONTENIDO_ES;
			if($lang_prefijo=="_en"){
				$prefijo=_MOD_CONTENIDO_EN;
			}
            
			$lineas .= "RewriteRule ".$prefijo."/".organizar_nombre($pg_titulo)."	ver.php?mod=contenido&identificador=".$pg_id."&lang=".$lang_prefijo."
";
			
			$result->MoveNext();
		}
		
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
		$sql="SELECT n_id,n_titulo FROM ic_noticias WHERE l_lang ='".$lang_prefijo."'";
		$result=$db->Execute($sql);
			
		while (!$result->EOF){
		
			list ($n_id,$n_titulo) = select_format($result->fields);
			
			$prefijo=_MOD_NOTICIAS_ES;
			if($lang_prefijo=="_en"){
				$prefijo=_MOD_NOTICIAS_EN;
			}
			
			$lineas .= "RewriteRule ".$prefijo."/".organizar_nombre($n_titulo)."	ver.php?mod=noticias&identificador=".$n_id."&lang=".$lang_prefijo."
";
			
			$result->MoveNext();
		}
		
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
		$sql="SELECT cat_id,cat_titulo FROM ic_categoria WHERE id_tipo='5' ";
		$result=$db->Execute($sql);
			
		while (!$result->EOF){
		
			list ($cat_id,$cat_titulo) = select_format($result->fields);
			
			$prefijo=_MOD_GALERIA_ES;
			if($lang_prefijo=="_en"){
				$prefijo=_MOD_GALERIA_EN;
			}
			
			$lineas .= "RewriteRule ".$prefijo."/".organizar_nombre($cat_titulo)."	ver.php?mod=galeria&id_galeria=".$cat_id."&lang=".$lang_prefijo."
";
			
			$result->MoveNext();
		}
		
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
		$sql="SELECT cat_id,cat_titulo FROM ic_categoria WHERE id_tipo='7' ";
		$result=$db->Execute($sql);
			
		while (!$result->EOF){
		
			list ($cat_id,$cat_titulo) = select_format($result->fields);
			
			$prefijo=_MOD_DIR_ES;
			if($lang_prefijo=="_en"){
				$prefijo=_MOD_DIR_EN;
			}
			
			$lineas .= "RewriteRule ".$prefijo."/".organizar_nombre($cat_titulo)."	listado-servicios.php?categoria=".$cat_id."
";
			
			$result->MoveNext();
		}
		
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		$sql="SELECT id_directorio,nombre FROM ic_directorio ";
		$result=$db->Execute($sql);
			
		while (!$result->EOF){
		
			list ($id_directorio,$nombre) = select_format($result->fields);
			
			$prefijo="servicio";
			
			$lineas .= "RewriteRule ".$prefijo."/".organizar_nombre($nombre)."	detalle-servicio.php?id=".$id_directorio."
";
			
			$result->MoveNext();
		}
		
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
		$sql="SELECT cat_id,cat_titulo FROM ic_categoria WHERE id_tipo='7' ";
		$result=$db->Execute($sql);
			
		while (!$result->EOF){
		
			list ($cat_id,$cat_titulo) = select_format($result->fields);
			
			$prefijo="directorio";
			
			$lineas .= "RewriteRule ".$prefijo."/".organizar_nombre($cat_titulo)."	listado-servicios.php?categoria=".$cat_id."
";
			
			$result->MoveNext();
		}
		
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		/*$sql="SELECT us_id,us_login FROM ic_usuarios WHERE us_id IN (SELECT id FROM ic_usu_gru WHERE gru_id=3) ";
		$result_reseller=$db->Execute($sql);
			
		while (!$result_reseller->EOF){
			
			list ($us_id,$us_login) = select_format($result_reseller->fields);
			
				$prefijo=_MOD_CATPRO_ES;
				if($lang_prefijo=="_en"){
					$prefijo=_MOD_CATPRO_EN;
				}
				
				$lineas .= "RewriteRule ".organizar_nombre_solo($us_login)."/".$prefijo."-reseller.html	ver.php?mod=cat_catalogo&reseller=".$us_id."&lang=".$lang_prefijo."
";
				$lineas .= "RewriteRule ".organizar_nombre_solo($us_login)."/".$prefijo."-search.html	ver.php?mod=catalogo&reseller=".$us_id."&lang=".$lang_prefijo."
";
				
				
			$result_reseller->MoveNext();
		}*/
		
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		/*$sql="SELECT us_id,us_login FROM ic_usuarios WHERE us_id IN (SELECT id FROM ic_usu_gru WHERE gru_id=3) ";
		$result_reseller=$db->Execute($sql);
			
		while (!$result_reseller->EOF){
			
			list ($us_id,$us_login) = select_format($result_reseller->fields);
			
			$sql="SELECT cat_id,cat_titulo FROM ic_categoria WHERE id_tipo='7' ";
			$result=$db->Execute($sql);
				
			while (!$result->EOF){
			
				list ($cat_id,$cat_titulo) = select_format($result->fields);
				
				$prefijo=_MOD_CATPRO_ES;
				if($lang_prefijo=="_en"){
					$prefijo=_MOD_CATPRO_EN;
				}
				
				$lineas .= "RewriteRule ".organizar_nombre_solo($us_login)."/".$prefijo."/".$us_id."-".organizar_nombre($cat_titulo)."	ver.php?mod=catalogo&reseller=".$us_id."&categoria=".$cat_id."&lang=".$lang_prefijo."
";
				
				$result->MoveNext();
			}
			
			$result_reseller->MoveNext();
		}*/
		
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	/*
		$sql="SELECT id_evento,nombre_evento FROM ic_eventos WHERE l_lang ='".$lang_prefijo."' ";
		$result=$db->Execute($sql);
			
		while (!$result->EOF){
		
			list ($id_evento,$nombre_evento) = select_format($result->fields);
			
			$prefijo=_MOD_EVENTOS_ES;
			if($lang_prefijo=="_en"){
				$prefijo=_MOD_EVENTOS_EN;
			}
			
			$lineas .= "RewriteRule ".$prefijo."/".organizar_nombre($nombre_evento)."	ver.php?mod=eventos&id_item=".$id_evento."&lang=".$lang_prefijo."
";
			
			$result->MoveNext();
		}*/
	
		
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	/*
		$sql="SELECT id_item,nombre_item,categoria_item FROM ic_catalogo ";
		$result=$db->Execute($sql);
			
		while (!$result->EOF){
		
			list ($id_item,$nombre_item,$categoria_item) = select_format($result->fields);

			$prefijo="item";
			
			$lineas .= "RewriteRule ".$prefijo."/".organizar_nombre($nombre_item)."	ver.php?mod=producto&item=".$id_item."&categoria=".$categoria_item."
";

			$result->MoveNext();
		}*/
		
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$sql="SELECT us_id,us_login FROM ic_usuarios WHERE us_id IN (SELECT id FROM ic_usu_gru WHERE gru_id=3) ";
		$result_reseller=$db->Execute($sql);
			
		while (!$result_reseller->EOF){
			
			list ($us_id,$us_login) = select_format($result_reseller->fields);
			
			$sql="SELECT id_item,nombre_item,categoria_item FROM ic_catalogo WHERE l_lang ='".$lang_prefijo."' ";
			$result=$db->Execute($sql);
				
			while (!$result->EOF){
			
				list ($id_item,$nombre_item,$categoria_item) = select_format($result->fields);
	
				$prefijo="item";
				
				$lineas .= "RewriteRule ".organizar_nombre_solo($us_login)."/".$prefijo."/".$us_id."-".organizar_nombre($nombre_item)."	ver.php?mod=producto&item=".$id_item."&categoria=".$categoria_item."&lang=".$lang_prefijo."&reseller=".$us_id."
";
	
				$result->MoveNext();
			}
			
			$result_reseller->MoveNext();
		}
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$result_lan->MoveNext();
	}
		
	$archivo=".htaccess";
	$fp=fopen($archivo,'w+');
	fwrite($fp,$lineas);
	fclose($fp);	
}

/***********************************************************************************************************************************/

function organizar_nombre($cadena){
		
	$cadena = sanear_string($cadena);
    $cadena = str_replace(" ","-",$cadena);

	return strtolower($cadena).".html";
}

/***********************************************************************************************************************************/

function organizar_nombre_solo($cadena){
		
	$cadena = sanear_string($cadena);
    $cadena = str_replace(" ","-",$cadena);

	return strtolower($cadena);
}

/***********************************************************************************************************************************/

function organizar_nombre_link($modulo, $cadena){
		
	$cadena = sanear_string($cadena);
    $cadena = str_replace(" ","-",$cadena);
		
	//------------------------------------------------------
	
	$id_reseller = "";
	$nombre_reseller = "";

	if(isset($_SESSION['reseller'])){
		$id_reseller = $_SESSION['reseller']."-";
		$nombre_reseller = organizar_nombre_solo($_SESSION['reseller_nombre'])."/";
	}
	
	//------------------------------------------------------
	
	if($modulo=="eventos"){
		if($_SESSION['idioma']=="_en"){
			$cadena=_MOD_EVENTOS_EN."/".$cadena;
		}else{
			$cadena=_MOD_EVENTOS_ES."/".$cadena;
		}
	}	
	
	if($modulo=="galeria"){
		if($_SESSION['idioma']=="_en"){
			$cadena=_MOD_GALERIA_EN."/".$cadena;
		}else{
			$cadena=_MOD_GALERIA_ES."/".$cadena;
		}
	}	
	
	if($modulo=="noticias"){
		if($_SESSION['idioma']=="_en"){
			$cadena=_MOD_NOTICIAS_EN."/".$cadena;
		}else{
			$cadena=_MOD_NOTICIAS_ES."/".$cadena;
		}
	}	
	
	if($modulo=="empresa"){
		if($_SESSION['idioma']=="_en"){
			$cadena=_MOD_EMP_EN."/".$cadena;
		}else{
			$cadena=_MOD_EMP_ES."/".$cadena;
		}
	}
	
	if($modulo=="directorio"){
		if($_SESSION['idioma']=="_en"){
			$cadena=_MOD_DIR_EN."/".$cadena;
		}else{
			$cadena=_MOD_DIR_ES."/".$cadena;
		}
	}
	
	if($modulo=="encuesta"){
		if($_SESSION['idioma']=="_en"){
			$cadena=_MOD_ENCUESTA_EN."/".$cadena;
		}else{
			$cadena=_MOD_ENCUESTA_ES."/".$cadena;
		}
	}	
	
	if($modulo=="contenido"){
		if($_SESSION['idioma']=="_en"){
			$cadena=_MOD_CONTENIDO_EN."/".$cadena;
		}else{
			$cadena=_MOD_CONTENIDO_ES."/".$cadena;
		}
	}
	
	if($modulo=="productos"){
		if($_SESSION['idioma']=="_en"){
			$cadena=$nombre_reseller._MOD_CATPRO_EN."/".$id_reseller.$cadena;
		}else{
			$cadena=$nombre_reseller._MOD_CATPRO_ES."/".$id_reseller.$cadena;
		}
	}
	
	if($modulo=="item"){
		if($_SESSION['idioma']=="_en"){
			$cadena=$nombre_reseller."item/".$id_reseller.$cadena;
		}else{
			$cadena=$nombre_reseller."item/".$id_reseller.$cadena;
		}
	}
	if($modulo=="faq"){
		$cadena="faq/".$cadena;
	}
	
	return strtolower($cadena).".html";
}

/***********************************************************************************************************************************/

function sanear_string($string)
{
 	$string = trim($string);
 	
	//$string = utf8_decode($string);
	
    //Esta parte se encarga de eliminar cualquier caracter extraño
    $string = str_replace(
        array("\\", "¨", "º", "-", "~",
             "#", "@", "|", "!", "\"",
             "·", "$", "%", "&", "/",
             "(", ")", "?", "'", "¡",
             "¿", "[", "^", "`", "]",
             "+", "}", "{", "¨", "´",
             ">", "<", ";", ",", ":",
             ".", "¿"),
        '',
        $string
    );
		
    $string = str_replace(
        array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä', 'Ã', 'ã'),
        array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A', 'A', 'a'),
        $string
    );
 
    $string = str_replace(
        array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
        array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
        $string
    );
 
    $string = str_replace(
        array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
        array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
        $string
    );
 
    $string = str_replace(
        array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô', 'Õ'),
        array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O', 'O'),
        $string
    );
 
    $string = str_replace(
        array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
        array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
        $string
    );
 
    $string = str_replace(
        array('ñ', 'Ñ', 'ç', 'Ç'),
        array('n', 'N', 'c', 'C',),
        $string
    );
 	
	return $string;
}

/***********************************************************************************************************************************/

?>