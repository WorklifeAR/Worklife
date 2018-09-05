<?php
/*
Inclusion de los archivos de:
Funciones= Archivo de funciones principales del sistema
Path=Ruta de libreria de BD
Var=Parametros de conexion
Conexion=archivo de conexion a la BD
*/

session_permiso("110");

	$path = "adodb/adodb.inc.php";
	include ("var.php");
	include ("conexion.php");

 	$variablesPaginas = variables_metodo('nw_e_mail,nw_todos,nw_grupos,nw_asunto,nw_forma_ver,nw_mensaje,funcion');

	$nw_e_mail=			$variablesPaginas[0];
	$nw_todos	=		$variablesPaginas[1];
	$nw_grupos =		$variablesPaginas[2];
	$nw_asunto=			$variablesPaginas[3];
	$nw_forma_ver	=	$variablesPaginas[4];
	$nw_mensaje =		$variablesPaginas[5];
	$funcion =			$variablesPaginas[6];


	if($funcion=="enviar")
	{
		enviar($nw_e_mail,$nw_todos,$nw_grupos,$nw_asunto,$nw_forma_ver,$nw_mensaje,$funcion);
	}else {
		formulario();
	}

/*****************************************************************************************************************/
function enviar($nw_e_mail,$nw_todos,$nw_grupos,$nw_asunto,$nw_forma_ver,$nw_mensaje,$funcion)
{
	$path = "adodb/adodb.inc.php";
	include ("var.php");
	include ("conexion.php");

	$sql="SELECT c_correo FROM ic_config";
	$result=$db->Execute($sql);
	list($correo_sistema)=select_format($result->fields);

	$para="";

	if($nw_todos=="1"){

		$sql="SELECT us_email FROM ic_usuarios WHERE us_email!=''";
		$correos_bd=$db->Execute($sql);

		while(!$correos_bd->EOF){

			list($e_mail)=select_format($correos_bd->fields);

			$para.= $e_mail;

			$correos_bd->MoveNext();

			if(!$correos_bd->EOF){
				$para.=',';
			}
		}

	}elseif($nw_e_mail!=""){
		$correo = "";

		for($i=0;$i<count($nw_e_mail);$i++){
			$correo .= $nw_e_mail[$i];
			if(($i+1)!=count($nw_e_mail)){
				$correo.=",";
			}
		}

		$sql="SELECT us_email FROM ic_usuarios WHERE us_id IN (".$correo.") AND us_email!=''";
		$correos_bd=$db->Execute($sql);

		while(!$correos_bd->EOF){

			list($e_mail)=select_format($correos_bd->fields);
			$para.= ''.$e_mail.'';
			$correos_bd->MoveNext();

			if(!$correos_bd->EOF){
				$para.=',';
			}
		}
	}

	// asunto
	$asunto = $nw_asunto;

//-------------------------------

// mensaje
if($nw_forma_ver=='html'){
	$mensaje = '<html><head><title>Untitled Document</title><meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">';
}

$mensaje .= $nw_mensaje;

if($nw_forma_ver=='html'){
	$mensaje .= '</body></html>';
}
$cabeceras = '';

if($nw_forma_ver=='html'){

// Para enviar correo HTML, la cabecera Content-type debe definirse
$cabeceras = "MIME-Version: 1.0\r\n"; 
$cabeceras .= "Content-type: text/html; charset=iso-8859-1\r\n"; 
$cabeceras .= "From: ".$asunto." <".$correo_sistema.">\r\n";
$cabeceras .= "Bcc: ".$para."\r\n";
$cabeceras .= "Return-path: ".$correo_sistema."\r\n"; 

}else{

$cabeceras = "MIME-Version: 1.0\r\n"; 
$cabeceras .= "Content-type: text/plain; charset=iso-8859-1\r\n"; 
$cabeceras .= "From: ".$asunto." <".$correo_sistema.">\r\n";
$cabeceras .= "Bcc: ".$para."\r\n";
$cabeceras .= "Return-path: ".$correo_sistema."\r\n"; 

}

//-------------------------------

?>
<script type="text/javascript">
function cargar()
{
	document.getElementById("cargando").style.display='none';
	document.getElementById("mensaje").style.display='inline';
}

setTimeout("cargar()",3000);
</script>
<div id="cargando"><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><center><img src="images/admin/cargando_admin.gif" border="0" alt="Cargando" /></center></div>
<?
	if(mail($correo_sistema, $asunto, $mensaje, $cabeceras)){

		//funcion para registrar la modificacion hecha por el usuario
	    registrar_cambios($db, date('Y-m-d'), $_SESSION['sess_nombre'], '110', '0', $asunto, 'ENVIAR');

?>
		<div id="mensaje" class="titulo" style="display:none"><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>Mensajes Enviados.<p>&nbsp;</p><p><a href="admin.php">Volver</a></p></div>
<?
	}else{
?>
		<div id="mensaje" class="titulo" style="display:none"><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>Ha ocurrido un error y no se han enviado los mensajes.<p>&nbsp;</p><p><a href="admin.php">Volver</a></p></div>
<?
	}

}

/***************************************************************************************/

function formulario(){
	$path = "adodb/adodb.inc.php";
	include ("var.php");
	include ("conexion.php");

?>
<script language="javascript">
function setSelectOptions(the_select, do_check)
{
    var selectObject = document.getElementById(the_select);
    var selectCount  = selectObject.length;

    for (var i = 0; i < selectCount; i++) {
        selectObject.options[i].selected = do_check;
    } // end for
   return true;
}

function enviar(campos){

	var camposObligatorios = campos.split(",");

	for(i=0;i<camposObligatorios.length;i++)
	{
		if(document.getElementById(camposObligatorios[i]).value==""){
			alert("Campo "+ camposObligatorios[i] +" obligatorio");
			return;
		}
	}
	document.newsletter.submit();
}
</script>
<script language="javascript" type="text/javascript">
	incluir_editor("nw_mensaje");
</script>

<table border="0" align="center" cellpadding="5" cellspacing="0">
  <tr align="center">
    <td colspan="2" class="titulo"><strong>Envio de Bolet&iacute;n de Noticias o Informaci&oacute;n </strong></td>
  </tr>
  <form action=""  method="post" id="newsletter" name="newsletter">
    <tr>
      <td valign="top">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td valign="top"><div align="right"><b>Usuarios</b></div></td>
      <td><select name="nw_e_mail[]" id="nw_e_mail[]" multiple size="6">
<?php
   		$sql="SELECT us_id,us_email FROM ic_usuarios";
		$result=$db->Execute($sql);

	while(!$result->EOF)
	{
		list($id, $us_email)=select_format($result->fields);

		echo "<option value='".$id."'>".$us_email."</option>";

		$result->MoveNext();
	}
?>
      </select>
        <br />
          <font size="1"><a href="#" onclick="setSelectOptions('nw_e_mail[]',true);"><font color="#006666">Seleccionar Todos</font></a>
		  /
		  <a href="#" onclick="setSelectOptions('nw_e_mail[]',false)"><font color="#006666">Deseleccionar Todo</font></a></font> </td>
	</tr>
    <tr>
      <td valign="top"><div align="right"></div></td>

      <td valign="top"><input type="checkbox" name="nw_todos" value="1" id="nw_todos" />
      Enviar a Todos los Usuarios </td>
    </tr>
    <tr>
      <td valign="top"><div align="right"><b>Asunto</b></div></td>
      <td><input name="nw_asunto" type="text" id="nw_asunto" size="40" /></td>
    </tr>
    <tr>
      <td valign="top"><div align="right"><b>Tipo Mensaje </b></div></td>
      <td><input name="nw_forma_ver" type="radio" id="nw_check_1" value="txt" />
          <label for="check_1">Texto</label>
          <input name="nw_forma_ver" type="radio" id="nw_check_2" value="html" checked="checked"/>
          <label for="check_2">Html</label></td>
    </tr>
    <tr>
      <td valign="top">
	  <div align="right">
      <p><strong>Mensaje</strong>(*)</p>
      <p><strong><em><a href="#"
onClick="window.open('_editor.php?formname=newsletter&inputname=nw_mensaje','editor_popup','width=760,height=570,scrollbars=no,resizable=yes,status=yes');return false"
TARGET="_blank">
Usar Editor
      </a></em></strong></p>
    </div>	  </td>
      <td><textarea name="nw_mensaje" id="nw_mensaje" style="width: 458px; height: 100px;">Mensaje del Bolet&iacute;n</textarea></td>
    </tr>

  <tr>
    <td colspan="2" align="right"><input type="hidden" name="funcion" value="enviar"><input type="button" onClick="enviar('nw_mensaje');" name="Enviar" value="Enviar"></td>
  </tr>
  </form>
</table>

<?php
}
?>
