<?php
if ($_SESSION['usuario']!="admin"){		
	//echo '<META HTTP-EQUIV="Refresh" CONTENT="0;URL=admin.php">';
	die("-1");
}
if ($_SESSION['permisos']!="all"){		
	//echo 'No tiene los suficientes permisos para acceder a este m&oacute;dulo.';
	die("-2");
}

$variables_metodo = variables_metodo("funcion,num_noti,correo,fecha,cabeceras,titulo,posicion,login,new_pass,grupo,idioma_activo,usuario_activo,c_base_location,c_tag_descripcion,c_scripts");

$funcion= 			$variables_metodo[0];
$num_noti= 			$variables_metodo[1];
$correo= 			$variables_metodo[2];
$fecha= 			$variables_metodo[3];
$cabeceras= 		$variables_metodo[4];
$titulo= 			$variables_metodo[5];
$posicion= 			$variables_metodo[6];
$login= 			$variables_metodo[7];
$new_pass= 			$variables_metodo[8];
$grupo= 			$variables_metodo[9];
$idioma_activo= 	$variables_metodo[10];
$usuario_activo= 	$variables_metodo[11];
$c_base_location= 	$variables_metodo[12];
$c_tag_descripcion= 	$variables_metodo[13];
$c_scripts= 	$variables_metodo[14];

if ($funcion == "guardar"){
	guardar($num_noti,$correo,$fecha,$cabeceras,$titulo,$posicion,$login,$new_pass,$grupo,$idioma_activo,$usuario_activo,$c_base_location,$c_tag_descripcion,$c_scripts);
}

/****************************************************************************************************************************/
function guardar($num_noti,$correo,$fecha,$cabeceras,$titulo,$posicion,$login,$new_pass,$grupo,$idioma_activo,$usuario_activo,$c_base_location,$c_tag_descripcion,$c_scripts)
{
	$path = "adodb/adodb.inc.php";
	include "var.php";
	include "conexion.php";
	
	if(($num_noti!="")||($correo!="")||($fecha!="")||($cabeceras!="")||($titulo!="")||($posicion!="")||($login!="")){
	
		$new_pass2="";
		if($new_pass!=""){
			$new_pass2="c_pass";
		}
		
		$result=update_bd_format(array("c_noti","c_correo","c_ingreso","c_cabeceras","c_titulo","c_posiciones","c_usu","c_grupo_defecto","c_lang","c_activo_new_usu","c_base_location","c_tag_descripcion","c_scripts",$new_pass2), "ic_config", array($num_noti,$correo,$fecha,$cabeceras,$titulo,$posicion,$login,$grupo,$idioma_activo,$usuario_activo,$c_base_location,$c_tag_descripcion,$c_scripts,$new_pass), "", $db);
		
		if ($result != false) $mensaje = "1";
		else $mensaje  = "0";
		
		echo"<META HTTP-EQUIV='Refresh' CONTENT='0;URL=admin.php?mod=4&mensaje=".$mensaje."'>";
		die();	
	}else{
		echo"<META HTTP-EQUIV='Refresh' CONTENT='0;URL=admin.php?mod=4&mensaje=2'>";
		die();	
	}

}
/****************************************************************************************************************************/

	$sql="SELECT 
			c_noti,c_correo,c_ingreso,c_cabeceras,c_titulo,
			c_posiciones,c_usu,c_pass,c_grupo_defecto,c_lang,
			c_activo_new_usu,c_base_location,c_tag_descripcion,c_scripts
		FROM ic_config ";
	$result=$db->Execute($sql);
	
	list($noti,$correo,$ingreso,$cabeceras,$titulo,$posiciones,$usu,$pass,$grupo,$idioma_activo,$usuario_activo,$c_base_location,$c_tag_descripcion,$c_scripts)=select_format($result->fields);
?>


<form action="" method="post" name="enviar">
<input type="hidden" name="funcion" value="guardar">
<input type="hidden" name="grupo" id="grupo" value="2" />
<input type="hidden" name="usuario_activo" id="usuario_activo" value="S" />
<table border="0" align="center" cellpadding="0" cellspacing="9">
  <tr>
    <td colspan="2" valign="top" class="titulo"><div align="center"><strong>Modificar Configuraci&oacute;n B&aacute;sica del Sitio </strong></div></td>
    </tr>
  <tr>
    <td valign="top" colspan="2" >
	
<?php
if($mensaje==1){ 
	$mensaje = "<center><span id='confirmacion'>Informaci&oacute;n Actualizada Correctamente</span></center>";
}elseif(($mensaje==2)&&($mensaje!="")){
	$mensaje  = "<center><span id='error'>Existen Campos obligatorios vacios y no se ha actualizado la informaci&oacute;n</span></center>"; 
}elseif(($mensaje==0)&&($mensaje!="")){
	$mensaje  = "<center><span id='error'>Ha ocurrido un error y no se ha actualizado la informaci&oacute;n.</span></center>"; 
}else {
	$mensaje  = "";
}
echo $mensaje;
?>	</td>
  </tr>
  <tr>
    <td valign="top"><div align="right"><strong>&Uacute;ltima fecha de Modificaci&oacute;n</strong></div></td>
    <td><?=$ingreso?>
      <input name="fecha" type="hidden" id="fecha" value="<? echo date('Y-m-d'); ?>" /></td>
  </tr>
  <tr>
    <td valign="top"><div align="right"><strong>URL Base</strong></div></td>
    <td><input name="c_base_location" type="text" id="c_base_location" value="<?=$c_base_location?>" /></td>
  </tr>
  <tr>
    <td valign="top"><div align="right"><strong>T&iacute;tulo de la p&aacute;gina </strong></div></td>
    <td><input name="titulo" type="text" id="titulo" value="<?=$titulo?>"></td>
  </tr>
  <tr>
    <td valign="top"><div align="right"><strong>Cabeceras</strong></div></td>
    <td><textarea name="cabeceras" cols="60" rows="4" id="cabeceras"><?=$cabeceras?></textarea></td>
  </tr>
  <tr>
    <td valign="top"><div align="right"><strong>Descripci&oacute;n</strong></div></td>
    <td><textarea name="c_tag_descripcion" cols="60" rows="4" id="c_tag_descripcion"><?=$c_tag_descripcion?></textarea></td>
  </tr>
  <tr>
    <td valign="top"><div align="right"><strong>Correo por defecto </strong></div></td>
    <td><input name="correo" type="text" id="correo" value="<?=$correo?>"></td>
  </tr>
  <tr>
    <td valign="top"><div align="right"><strong>No. de noticias a mostrar el el home </strong></div></td>
    <td><input name="num_noti" type="text" id="num_noti" value="<?=$noti?>"></td>
  </tr>
  <tr>
    <td valign="top"><div align="right"><strong>Multi-Idioma</strong></div></td>
    <td>
	
	<select name="idioma_activo" id="idioma_activo">
		<?php cargar_lista_estatica("1,0","Activo,Inactivo",0,$idioma_activo); ?>
	</select>
	</td>
  </tr>
  <tr>
    <td valign="top"><div align="right"><strong>Scripts de Cabecera de la p&aacute;gina</strong></div></td>
    <td><textarea name="c_scripts" cols="60" rows="5" id="c_scripts"><?=$c_scripts?></textarea></td>
  </tr>
  <tr>
    <td align="right" valign="top"><strong>Bloques de posici&oacute;n<br>
      </strong><i><small>Separados por comas</small></i></td>
    <td><textarea name="posicion" cols="60" rows="3" id="posicion"><?=$posiciones?></textarea></td>
  </tr>
  <tr>
    <td valign="top"><div align="right"><strong>Usuario de administraci&oacute;n </strong></div></td>
    <td><input name="login" type="text" id="login" value="<?=$usu?>"></td>
  </tr>
  <tr>
    <td valign="top"><div align="right"><strong>Cambio de Contrase&ntilde;a </strong></div></td>
    <td>    <table border="0" cellspacing="7" cellpadding="0">
      <tr>
        <td>Contrase&ntilde;a Actual</td>
        <td><input name="actual_pass" type="password" id="actual_pass" size="8"></td>
      </tr>
      <tr>
        <td>Contrase&ntilde;a Nueva</td>
        <td><input name="new_pass" type="password" id="new_pass" size="8"></td>
      </tr>
      <tr>
        <td>Confirmar Contrase&ntilde;a </td>
        <td><input name="new_pass_c" type="password" id="new_pass_c" size="8"></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" align="center"><input type="button" onClick="revision('<?=$pass?>');" name="Submit" value="Guardar Cambios"></td>
  </tr>
</table>
</form>
<script language="javascript" type="text/javascript">
function revision(pass)
{
if ((document.enviar.actual_pass.value != "")&&(document.enviar.new_pass.value != "")&&(document.enviar.new_pass_c.value != ""))
{
	if (document.enviar.actual_pass.value != pass)
	{
		alert("Password actual incorrecto!");
		return;
	}
	if(document.enviar.new_pass.value != document.enviar.new_pass_c.value)
	{
		alert("Por favor confirme correctamente el password!");
		return;
	}
}
document.enviar.submit();
}
</script>
