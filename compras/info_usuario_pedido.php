<?php
	session_start();
	
	if(isset($_POST['nombre']) && isset($_POST['email']) && isset($_POST['direccion']) && isset($_POST['telefono']) && isset($_POST['pais']) && isset($_POST['ciudad'])){
		
		$_SESSION['sess_nombre']=$_POST['nombre'];
		$_SESSION['sess_last_nombre']=$_POST['last_nombre'];
		$_SESSION['sess_usu_email']=$_POST['email'];
		
		$_SESSION['sess_usu_direccion']=$_POST['direccion'];
		$_SESSION['sess_usu_telefono']=$_POST['telefono'];
		$_SESSION['sess_usu_ciudad']=$_POST['ciudad'];
		$_SESSION['sess_usu_postal']=$_POST['cp'];
		$_SESSION['sess_usu_pais']=$_POST['pais'];
		$_SESSION['sess_usu_estado_us']=$_POST['estado_us'];
		$_SESSION['sess_usu_appartment']=$_POST['appartment'];
		$_SESSION['sess_usu_comm']=$_POST['comments'];
		
		echo "info registrada";
	}
?>