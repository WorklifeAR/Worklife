<?php

session_start();

if(isset($_SESSION["imageText"])){
	if($_SESSION["imageText"] != $_POST["captcha"]){
		echo "Caracteres de validacion de registro incorrectos, por favor confirmelos.";	
	}else{
		echo "OK";	
	}
}else{
	echo "No se puede continuar el registro, no existe dato de validacion.";	
}

?>