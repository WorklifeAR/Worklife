<?php
	session_start();
?>
<html>
<head>
<title>Mensaje Enviado Correctamente</title>
<meta charset="utf-8">
</head>
<body>

<?php

	if($_GET['id'] != session_id()){
		die("<META HTTP-EQUIV='Refresh' CONTENT='0;URL=ver.php?mod=reservas'>");
	}

	$path = "../adodb/adodb.inc.php";
	include ("../admin/var.php");
	include ("../conexion.php");
	include ("../admin/funciones.php");
	idioma_session($db);
	include ("../language/language".$_SESSION['idioma'].".php");
	include ("../compras/funciones_carrito.php");
	
	$variablesPaginas = variables_metodo('nombre_completo,email,direccion,telefono,articulo,comentarios');
	$nombre_completo=		$variablesPaginas[0];
	$email	=				$variablesPaginas[1];
	$direccion	=			$variablesPaginas[2];
	$telefono	=			$variablesPaginas[3];
	$articulo	=				$variablesPaginas[4];
	$comentarios	=		$variablesPaginas[5];

	$MailTo=obtenerDescripcion("CORREO_RESERVA", "", $db);

	$fecha=date("m.d.Y");
	$hora=date("H:i:s");
	$contenido=_MSG_CONTACTO_1 . " " .$fecha." "._MSG_CONTACTO_2." ".$hora.":\n\n
		----------------------------------------------------------------------------\n
			"._RESERVA_SUBJET."
		----------------------------------------------------------------------------\n\n
			"._NOMBRE_COMPLETO.":\n ".$nombre_completo."\n\n
			"._EMAIL.":\n ".$email."\n\n
			"._DIRECCION.":\n ".$direccion."\n\n
			"._TELEFONO.":\n ".$telefono."\n\n
			"._RESERVA_ARTICULO.":\n ".$libro."\n\n
			"._MSG_CONTACTO_8.":\n ".$comentarios."\n\n
		----------------------------------------------------------------------------\n\n";

	 $db->close();

	$subject = _RESERVA_SUBJET;
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
  <font color="#999999" size="4" face="Arial, Helvetica, sans-serif"><img src="../images/admin/cargando_admin.gif" border="0" alt="Cargando" /></font>
</center></div>

<?
	if(mail($MailTo->fields[0], $subject, $contenido, $subject." ".$MailTo->fields[0]."")){

?>
		<script type="text/javascript">
			setTimeout("cargar()",2000);
		</script>
		<div id="mensaje" class="titulo" style="display:none"><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>
		<div align="center"><font color="#999999" size="4" face="Arial, Helvetica, sans-serif"><?=_MSG_CORREO_ENVIADO?></font></div>
		<META HTTP-EQUIV='Refresh' CONTENT='5;URL=../ver.php?mod=reservas'>
		</div>
<?
	}else{
?>
		<script type="text/javascript">
			setTimeout("cargar()",2000);
		</script>
		<div id="mensaje" class="titulo" style="display:none"><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>
		<div align="center"><font color="#ff0000" size="4" face="Arial, Helvetica, sans-serif"><?=_MSG_CORREO_NOENVIADO?></font></div>
		<META HTTP-EQUIV='Refresh' CONTENT='5;URL=../ver.php?mod=reservas'>
		</div>
<?
}
?>
</td>
 </tr>
</table>

</body>
</html>
