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
<input type="hidden" name="num_noti" id="num_noti" value="2" />
<input type="hidden" name="usuario_activo" id="usuario_activo" value="S" />
<table border="0" align="center" width="800">
  <tr align="center">
    <td colspan="12">
    <img src="images/admin/bloques.png" border="0"/>  Secciones HTML
    </td>
  </tr>
  <tr>
  	<td colspan="12">&nbsp;</td>
  </tr>
  <tr>
  <td colspan="5"><div style="margin:20px 0; width:100%; border-top:1px solid #494949;"></div></td>
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
    <td valign="top" width="100"><div align="right"><strong>Fecha</strong></div></td>
    <td><?=$ingreso?>
      <input name="fecha" type="hidden" id="fecha" value="<? echo date('Y-m-d'); ?>"  class="form-control"/></td>
  </tr>
  <tr>
    <td valign="top"><div align="right"><strong>URL Base</strong></div></td>
    <td><input name="c_base_location" type="text" id="c_base_location" value="<?=$c_base_location?>"  class="form-control"/></td>
  </tr>
  <tr>
    <td valign="top"><div align="right"><strong>Título</strong></div></td>
    <td><input name="titulo" type="text" id="titulo" value="<?=$titulo?>" class="form-control"></td>
  </tr>
  <tr>
    <td valign="top"><div align="right"><strong>Headers</strong></div></td>
    <td><textarea name="cabeceras" id="cabeceras" class="form-control"><?=$cabeceras?></textarea></td>
  </tr>
  <tr>
    <td valign="top"><div align="right"><strong>Descripción</strong></div></td>
    <td><textarea name="c_tag_descripcion" id="c_tag_descripcion" class="form-control"><?=$c_tag_descripcion?></textarea></td>
  </tr>
  <tr>
    <td valign="top"><div align="right"><strong>Email</strong></div></td>
    <td><input name="correo" type="text" id="correo" value="<?=$correo?>" class="form-control"></td>
  </tr>
  <tr>
    <td valign="top"><div align="right"><strong>Multi-language</strong></div></td>
    <td>
	
	<select name="idioma_activo" id="idioma_activo" class="form-control">
		<?php cargar_lista_estatica("1,0","Activo,Inactivo",0,$idioma_activo); ?>
	</select>
	</td>
  </tr>
  <tr>
    <td valign="top"><div align="right"><strong>Scripts</strong></div></td>
    <td><textarea name="c_scripts" id="c_scripts" class="form-control"><?=$c_scripts?></textarea></td>
  </tr>
  <tr>
    <td align="right" valign="top"><strong>Bloques HTML</strong><i><small></small></i></td>
    <td><textarea name="posicion"  id="posicion" class="form-control"><?=$posiciones?></textarea></td>
  </tr>
  <tr>
    <td valign="top"><div align="right"><strong>Admin User</strong></div></td>
    <td><input name="login" type="text" id="login" value="<?=$usu?>" class="form-control"></td>
  </tr>
  <tr>
    <td valign="top"><div align="right"><strong>Admin User Password</strong></div></td>
    <td>    <table border="0" cellspacing="7" cellpadding="0" align="left">
      <tr>
        <td width="200">Actual Password</td>
        <td><input name="actual_pass" type="password" id="actual_pass" class="form-control"></td>
      </tr>
      <tr>
        <td>Nuevo Password</td>
        <td><input name="new_pass" type="password" id="new_pass" class="form-control"></td>
      </tr>
      <tr>
        <td>Confirmar Password</td>
        <td><input name="new_pass_c" type="password" id="new_pass_c"  class="form-control"></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" align="center"><input type="button" onClick="revision('<?=$pass?>');" name="Submit" value="Save"></td>
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
