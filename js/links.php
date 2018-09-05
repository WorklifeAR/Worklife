<?php

	$path = "../adodb/adodb.inc.php";
	include ("../admin/var.php");
	include ("../admin/lib/general.php");
	include ("../admin/lib/url.php");
	include ("../conexion.php");
	
	$sql="SELECT pg_id, pg_titulo FROM ic_contenido ";
	$result=$db->Execute($sql);	
	
	$url_pagina = "";
	while(!$result->EOF){
		list($id,$titulo)=select_format($result->fields);
		$url_pagina .= "".organizar_nombre_link("contenido", $titulo)."!".$titulo.";";
		$result->MoveNext();
	}
	
	echo $url_pagina = substr($url_pagina, 0, strlen($url_pagina)-1);
?>