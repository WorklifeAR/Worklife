<?php

	$directorio = dir("../images/");
	$nombre_archivos = "";
	
	while($archivo = $directorio->read())
	{
		if($archivo!="." && $archivo!=".."){
			if(!is_dir("../images/".$archivo)){
				$nombre_archivos .= $archivo.",";
			}		
		}
	}
	$directorio->close(); 
	
	echo $nombre_archivos = substr($nombre_archivos, 0, strlen($nombre_archivos)-1);
	
?>