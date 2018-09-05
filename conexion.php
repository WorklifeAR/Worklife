<?php
	//Inclusion de libreria de conexion
	include ($path);
	//Creacion de objeto de conexion
	$db = NewADOConnection($var_bd[0]);
	//Asignacion de parametros de conexion al obejto 
	$db->Connect($var_host[1], $var_usu[2], $var_pass[3], $var_bdname[4]);
	//Verificacion de conexion
	if ( $db->Connect($var_host[1], $var_usu[2], $var_pass[3], $var_bdname[4]) == false ){
		die ('<div align="center">
				  <p><font color="#FF0000" size="4" face="Arial, Helvetica, sans-serif"><em><strong>No hay conexion con la Base de Datos.</strong></em></font></p>
				  <p><font color="#FF0000" size="4" face="Arial, Helvetica, sans-serif">Por favor reporte al administrador del sitio. </font></p>
				  <p><font color="#333333" size="2" face="Arial, Helvetica, sans-serif"><em>Error de Conexi&oacute;n. Invensoft Full - Invenciones Creativas</em></font></p>
			  </div>');
	}
?>