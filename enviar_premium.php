<?php
	session_start(); 
?>
<html>
<head>
<title>Mensaje Enviado Correctamente</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

</head>

<body>

<?php

	if($_GET['id'] != session_id()){
		die("<META HTTP-EQUIV='Refresh' CONTENT='0;URL=index.php'>");
	}

	$path = "adodb/adodb.inc.php";
	include ("admin/var.php");
	include ("conexion.php");
	include ("admin/lib/general.php");
	idioma_session($db);
	include ("language/language".$_SESSION['idioma'].".php");

	$variablesPaginas = variables_metodo('nombre_completo,email,ciudad,phone');
	$nombre_completo=		$variablesPaginas[0];
	$email	=				$variablesPaginas[1];
	$ciudad	=				$variablesPaginas[2];
	$phone	=				$variablesPaginas[3];

	$sql="SELECT c_correo FROM ic_config";
	$result=$db->Execute($sql);
	list($MailTo)=$result->fields;
	
	$cabeceras = "MIME-Version: 1.0\r\n"; 
	$cabeceras .= "Content-type: text/html; charset=iso-8859-1\r\n"; 
	$cabeceras .= "From: Worklife - Contacto Premium <".$MailTo.">\r\n";
	$cabeceras .= "Return-path: ".$MailTo."\r\n"; 

	$fecha=date("m.d.Y");
	$hora=date("H:i:s");
	$contenido="Time sent  ".$fecha." at ".$hora.":<br />
----------------------------------------------------------------------------<br /><br />
Nombre: ".$nombre_completo."<br />
Email: ".$email."<br />
Telefono: ".$phone."<br />
Ciudad: ".$ciudad."<br />
<br />
----------------------------------------------------------------------------<br /><br />";
	//funcion para registrar la modificacion hecha por el usuario
	registrar_cambios($db, date('Y-m-d'), $nombre_completo, '00', "", strip_tags($contenido), 'CONTACTO');
	
	 $db->close();
	 
?>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<table width="500" border="1" cellspacing="0" cellpadding="20" align="center" bordercolor="#CCCCCC">
  <tr>
    <td align="center" valign="middle" bgcolor="#f4f4f4">

<script type="text/javascript">
function cargar()
{
	document.getElementById("cargando").style.display='none';
	document.getElementById("mensaje").style.display='inline';
}
</script>

<div id="cargando"><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><center>
  <font color="#999999" size="4" face="Arial, Helvetica, sans-serif"><img src="images/admin/cargando_admin.gif" border="0" alt="Cargando" /></font>
</center></div>

<?
	if(mail($MailTo, "Worklife - Contacto Premium", $contenido, $cabeceras)){

?>
		<script type="text/javascript">
			setTimeout("cargar()",0);
		</script>
		<div id="mensaje" class="titulo" style="display:none"><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>
		<div align="center"><font color="#999999" size="4" face="Arial, Helvetica, sans-serif">
		Thanks!
		<br>
		Se ha enviado el mensaje. Estaremos en contacto pronto.
		<br>
		Gracias por escribir.
		</font></div>
		<META HTTP-EQUIV='Refresh' CONTENT='5;URL=index.php'>
		</div>
<?
	}else{
?>
		<script type="text/javascript">
			setTimeout("cargar()",0);
		</script>
		<div id="mensaje" class="titulo" style="display:none"><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>
		<div align="center"><font color="#ff0000" size="4" face="Arial, Helvetica, sans-serif"><?=_MSG_CORREO_NOENVIADO?></font></div>
		<META HTTP-EQUIV='Refresh' CONTENT='5;URL=index.php'>
		</div>
<?
}
?>
</td>
 </tr>
</table>

</body>
</html>
