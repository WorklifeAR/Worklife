<?php
session_start();

$path = "../adodb/adodb.inc.php";
include ("../admin/var.php");
include ("../conexion.php");

include ("../admin/lib/general.php");
include ("../admin/lib/usuarios.php");


$variablesPaginas = variables_metodo('funcion,us_id,us_codigo_ref,us_nombre,us_pais,us_telefono,us_email,us_direccion,us_nacimiento,us_last_name,us_sexo,us_descripcion,us_login,us_pass,us_postal,us_ciudad,us_estado_us,us_appartment,us_foto,correo_recordar,destino');

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
$us_foto = $variablesPaginas[18];
$correo_recordar = $variablesPaginas[19];
$destino = $variablesPaginas[20];


$sql1_1="SELECT us_email,us_pass FROM ic_usuarios WHERE us_codigo_ref='".trim($us_codigo_ref)."' "; 	
$result1_1=$db->Execute($sql1_1);
list($e_mail_existe,$password)=$result1_1->fields;

if( ( $e_mail_existe == "" || $e_mail_existe == NULL ) ){
	
	$password = "WL".rand(1000,9999);
	$us_foto = "http://graph.facebook.com/".trim($us_codigo_ref)."/picture?type=large";
	
	$result1=insert_bd_format("us_nombre,us_codigo_ref,us_last_name,us_sexo,us_foto,us_email,us_creacion,us_login,us_pass,us_estado,plan_wl,us_descripcion", 
						 "ic_usuarios", 
							 array(trim($us_nombre),trim($us_codigo_ref),trim($us_last_name),$us_sexo,$us_foto,trim($us_email),date("Y-m-d"),trim($us_email),$password,"1","Basic",$us_descripcion), 
							 $db);
						 
	$sql1_1="SELECT us_id FROM ic_usuarios WHERE us_codigo_ref='".trim($us_codigo_ref)."' "; 	
	$result1_1=$db->Execute($sql1_1);
	list($id_nuevo)=$result1_1->fields;
	
	$sql="INSERT INTO ic_usu_gru (gru_id,id) VALUES('2','".$id_nuevo."')"; 	
	$result2=$db->Execute($sql);
	
	////////////////////////
		
	$adicional_formato="INGRESO POR REDES SOCIALES // ".strtoupper($us_descripcion)."";
	enviar_notificacion(trim($us_email), $us_nombre, "REGISTRO", "1", $adicional_formato) ;
	
	////////////////////////
	
	if (($result1 != false)&&($result2 != false)){
		echo iniciar_session($us_codigo_ref,$password,"network",$db);
	}		
}else{
	$sql="UPDATE ic_usuarios SET us_foto='http://graph.facebook.com/".trim($us_codigo_ref)."/picture?type=large' WHERE us_codigo_ref='".$us_codigo_ref."'"; 	
	$result2=$db->Execute($sql);
	
	echo iniciar_session($us_codigo_ref,$password,"network",$db);
}

?>