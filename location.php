<?php
	session_start();
	
	$path = "adodb/adodb.inc.php";
	include ("admin/var.php");
	include ("conexion.php");
	include ("admin/lib/general.php");
	
	$variables_ver = variables_metodo("longitud,latitud");
	$longitud= 			$variables_ver[0];
	$latitud= 			$variables_ver[1];
	
	if($longitud!="" && $latitud!=""){
		$_SESSION["longitud"]=$longitud;
		$_SESSION["latitud"]=$latitud;
	}else{
		$_SESSION["longitud"]="-58.381653";
		$_SESSION["latitud"]="-34.603075";
	}
	
	echo "OK - LON:".$_SESSION["longitud"]."/ LAT:".$_SESSION["latitud"];
?>