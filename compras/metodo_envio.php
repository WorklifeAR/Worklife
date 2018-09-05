<?php
	session_start();
	
	if(isset($_POST['metodo_envio']) && $_POST['metodo_envio']!=""){
		
		$_SESSION['envio_seleccionado'] = $_POST['metodo_envio'];
		$_SESSION['envio_seleccionado_detalle'] = $_POST['detalle_metodo_seleccionado']."|".$_POST['valor_metodo_seleccionado'];
		
		echo "OK";
	}
?>