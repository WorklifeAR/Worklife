<?php
	session_start();
					
	unset($_SESSION['tipo_cupon']);
	unset($_SESSION['valor_cupon']);
	unset($_SESSION['ref_cupon']);
	unset($_SESSION['producto_cupon']);
	unset($_SESSION['total_cupon']);
	unset($_SESSION['categoria_cupon']);
	
	echo "OK";
?>