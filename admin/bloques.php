<?php
session_permiso("3");

$variables_metodo = variables_metodo("id,fecha,titulo,full,posicion,funcion,b_id,b_lang");

$id= 			$variables_metodo[0];
$fecha= 		$variables_metodo[1];
$titulo= 		$variables_metodo[2];
$full= 			$variables_metodo[3];
$posicion= 		$variables_metodo[4];
$funcion= 		$variables_metodo[5];
$b_id= 			$variables_metodo[6];
$b_lang= 		$variables_metodo[7];

if ($funcion == "guardar"){
	if($id==""){
	/*Funcion para guardar los datos del formulario*/	
		guardar($fecha,$titulo,$full,$posicion,$b_lang);
	}elseif($id!=""){
	/*Funcion para modificar los datos del formulario*/	
		modificar($id,$fecha,$titulo,$full,$posicion,$b_lang);
	}		
}elseif(($funcion == "borrar")&&($id!="")){
	borrar($id);
}

/****************************************************************************************************************************/
function borrar($id)
{
	$path = "adodb/adodb.inc.php";
	include "var.php";
	include "conexion.php";
	
	$sql="SELECT b_titulo FROM ic_bloque WHERE b_id='".$id."' ";
	$result=$db->Execute($sql);
	
	list($titulo)=select_format($result->fields);
	
	//funcion para registrar la modificacion hecha por el usuario
	registrar_cambios($db, date('Y-m-d'), $_SESSION['sess_nombre'], '3', $id, $titulo,'ELIMINAR');
	
	$result=$db->Execute("delete from ic_bloque where b_id = '".$id."' ");
	
	if ($result != false) $mensaje = "3";
	else $mensaje  = "0";
		
	echo"<META HTTP-EQUIV='Refresh' CONTENT='0;URL=admin.php?mod=3&mensaje=".$mensaje."'>";
	die();
}
/****************************************************************************************************************************/
function guardar($fecha,$titulo,$full,$posicion,$b_lang)
{
	$path = "adodb/adodb.inc.php";
	include "var.php";
	include "conexion.php";
	
	if($b_lang==""){
		$b_lang=$_SESSION['idioma'];
	}
		
	//Limpieza de contenido para ORACLE
	//$full = limpiar_contenido_oracle($full);
	
	$result=insert_bd_format("b_titulo,b_conten,b_posicion,b_modifi,l_lang", "ic_bloque", array($titulo,$full,$posicion,$fecha,$b_lang), $db);
	
	if ($result != false) $mensaje = "1";
	else $mensaje  = "0";
	
	//funcion para registrar la modificacion hecha por el usuario
	registrar_cambios($db, date('Y-m-d'), $_SESSION['sess_nombre'], '3', '0', $titulo, 'GUARDAR');
	
	echo"<META HTTP-EQUIV='Refresh' CONTENT='0;URL=admin.php?mod=3&mensaje=".$mensaje."'>";
	die();	
	
}
/****************************************************************************************************************************/
function modificar($id,$fecha,$titulo,$full,$posicion,$b_lang)
{
	$path = "adodb/adodb.inc.php";
	include "var.php";
	include "conexion.php";
	
	if($b_lang==""){
		$b_lang=$_SESSION['idioma'];
	}
		
	//Limpieza de contenido para ORACLE
	//$full = limpiar_contenido_oracle($full);
	
	$result=update_bd_format(array("b_titulo","b_modifi","b_conten","b_posicion","l_lang"), "ic_bloque", array($titulo,$fecha,$full,$posicion,$b_lang), "WHERE b_id='".$id."'", $db);
	
	if ($result != false) $mensaje = "2";
	else $mensaje  = "0";
	
	//funcion para registrar la modificacion hecha por el usuario
	registrar_cambios($db, date('Y-m-d'), $_SESSION['sess_nombre'], '3', $id, $titulo, 'MODIFICAR');
	
	echo"<META HTTP-EQUIV='Refresh' CONTENT='0;URL=admin.php?mod=3&mensaje=".$mensaje."'>";
	die();
}
/****************************************************************************************************************************/
?>
<form action="" name="modificar" method="post">
<table border="0"  width="600" align="center">
  <tr align="center">
    <td colspan="12">
    <img src="images/admin/bloques.png" border="0"/>  Secciones HTML
    </td>
  </tr>
  <tr>
  	<td colspan="12">&nbsp;</td>
  </tr>
   <tr>
    <td>
	  <strong>
	  <select name="b_id" class="form-control">
		<?php cargar_lista("ic_bloque","b_id,b_titulo","b_id","1","","",$db); ?>
      </select>
	  </strong></td>
    <td>
      <input type="submit" name="Submit" value="Editar">
    </td>
   </tr>
</table>
</form>

<?php
if($mensaje==1){ 
	$mensaje = "<center><span id='confirmacion'>Informaci&oacute;n Guardada Correctamente</span></center>";
}elseif(($mensaje==2)&&($mensaje!="")){
	$mensaje  = "<center><span id='confirmacion'>Informaci&oacute;n Actualizada Correctamente</span></center>"; 
}elseif(($mensaje==3)&&($mensaje!="")){
	$mensaje  = "<center><span id='confirmacion'>Informaci&oacute;n Eliminada Correctamente</span></center>"; 
}elseif(($mensaje==0)&&($mensaje!="")){
	$mensaje  = "<center><span id='error'>Ha ocurrido un error y no se ha guardado la informaci&oacute;n.</span></center>"; 
}
else {
	$mensaje  = "";
}
echo $mensaje;
?>

<script language="javascript">	

function enviar(campos){

	var camposObligatorios = campos.split(",");
	
	for(i=0;i<camposObligatorios.length;i++)
	{	
		if(document.getElementById(camposObligatorios[i]).value==""){
			alert("Campo "+ camposObligatorios[i] +" obligatorio");
			return;
		}
	}	
	document.guardar.submit();
}
</script>

<table  border="0" align="center" width="800">
<form action="" name="guardar" method="post">
<input type="hidden" name="funcion" value="guardar">
<input type="hidden" name="fecha" value="<? echo date('Y-m-d'); ?>">
<?php
if ($b_id != ""){
	$sql="SELECT b_id,b_titulo,b_conten,b_modifi,b_posicion,l_lang FROM ic_bloque WHERE b_id='".$b_id."' ";
	$result=$db->Execute($sql);
	
	list($id,$titulo,$full,$fecha,$posicion,$b_lang)=select_format($result->fields);
		
	echo '<input type="hidden" name="id" value="'.$id.'">';
}
?>
<tr>
  <td colspan="5"><div style="margin:20px 0; width:100%; border-top:1px solid #494949;"></div></td>
</tr>
 <tr>
   <td valign="top" width="100">&nbsp;</td>
   <td><div align="right"><a href="admin.php?mod=3">Cancelar/Nuevo</a></div></td>
 </tr>
 <tr>
   <td valign="top">&nbsp;</td>
   <td><div align="right"><font color="#FF0000" size="1">(*)Campos Obligatorios</font></div></td>
 </tr>
 <tr>
    <td valign="top"><div align="right"><strong>Fecha</strong></div></td>
    <td><? if ($fecha==""){ echo date('Y-m-d'); } else { echo $fecha; }?></td>
  </tr>
  <tr>
    <td valign="top"><div align="right"><strong>Language</strong></div></td>
    <td><strong>
      <select name="b_lang" id="b_lang" <?=$activo_campo_idioma?>  class="form-control">
        <?php cargar_lista("ic_language","lang_prefijo,lang_descripcion","lang_id","0",$b_lang,"",$db); ?>
      </select>
    </strong></td>
  </tr>
  <tr>
    <td valign="top"><div align="right"><strong>Title</strong>(*)</div></td>
    <td><input type="text" name="titulo" id="titulo" value="<?=$titulo?>" class="form-control"></td>
  </tr>
  <tr>
    <td valign="top"><div align="right"><strong>Position</strong>(*)</div></td>
    <td><select name="posicion" id="posicion"  class="form-control">
	<option value='<?=$posicion?>'><?=$posicion?></option>
<?php
	$sql="select c_posiciones from ic_config ";
	$result=$db->Execute($sql);

	list($posi)=select_format($result->fields);
	
	$posiciones=explode(",",$posi);
	
	foreach($posiciones as $pos)
	{
	if ($pos != $posicion){	echo "<option value='".$pos."'>".$pos."</option>"; }
	}
?>	
	</select></td>
  </tr>
  <tr>
    <td valign="top"><div align="right"><strong>Full Text</strong>(*) </div></td>
    <td><textarea name="full" class="form-control" id="texto_completo"><?=$full?></textarea></td>
  </tr>
  <tr>
    <td valign="top">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td valign="top">&nbsp;</td>
    <td align="right">
	<input type="button" name="Enviar" onClick="enviar('titulo,posicion,texto_completo');" value="Guardar">
	</form>
	<p>
    <form action="" method="post" name="eliminar">
	<input type="submit" name="Borrar" id="borrar" value="Eliminar">
	<input type="hidden" name="id" value="<?=$id?>">
	<input type="hidden" name="funcion" value="borrar">
	</form>
	</p>
	</td>
  </tr>
</table>
</form>
