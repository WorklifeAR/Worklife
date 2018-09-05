<?php
	session_start();
	
	if(isset($_POST['departamento_envio']) && $_POST['departamento_envio']!=""){
		
		$_SESSION['departamento_envio'] = $_POST['departamento_envio'];
		$_SESSION['sess_usu_departamento'] = $_POST['departamento_envio'];
		
		
		$_SESSION['sess_nombre']=$_POST['nombre'];
		$_SESSION['sess_usu_telefono']=$_POST['telefono'];
		$_SESSION['sess_usu_email']=$_POST['email'];
		$_SESSION['sess_usu_direccion_envio']=$_POST['direccion'];
		
		$_SESSION['sess_usu_pais']=$_POST['pais'];
		$_SESSION['sess_usu_codigo_ref']=$_POST['zip'];
		$_SESSION['sess_usu_direccion']=$_POST['lastName'];
		$_SESSION['sess_usu_sexo']=$_POST['apparment'];
		$_SESSION['sess_usu_ciudad']=$_POST['ciudad'];
		
		echo "departamento envio agregado";
	}
?>