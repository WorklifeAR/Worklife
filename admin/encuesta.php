<?php
session_permiso("112");

$variables_metodo = variables_metodo("id,nombre,comentario,item1,item2,item3,item4,item5,item6,funcion,lang,activa");

$id= 			$variables_metodo[0];
$nombre= 		$variables_metodo[1];
$comentario= 	$variables_metodo[2];
$item1= 		$variables_metodo[3];
$item2= 		$variables_metodo[4];
$item3= 		$variables_metodo[5];
$item4= 		$variables_metodo[6];
$item5= 		$variables_metodo[7];
$item6= 		$variables_metodo[8];
$funcion= 		$variables_metodo[9];
$lang= 			$variables_metodo[10];
$activa= 		$variables_metodo[11];

if ($funcion == "guardar"){
	if($id==""){
		/*Funcion para guardar los datos del formulario*/	
		guardar($nombre,$comentario,$item1,$item2,$item3,$item4,$item5,$item6,$lang,$activa);
	}elseif($id!=""){
		/*Funcion para modificar los datos del formulario*/	
		modificar($id,$nombre,$comentario,$item1,$item2,$item3,$item4,$item5,$item6,$lang,$activa);
	}		
}elseif(($funcion == "borrar")&&($id!="")){
	borrar($id);
}elseif(($funcion == "reiniciar")&&($id!="")){
	reiniciar($id);
}


/****************************************************************************************************************************/

function borrar($id)
{
	$path = "adodb/adodb.inc.php";
	include "var.php";
	include "conexion.php";
	
	$sql="SELECT nombre_encuesta FROM ic_encuestas WHERE id_encuesta='".$id."' ";
	$result=$db->Execute($sql);
	
	list($titulo)=select_format($result->fields);

	//funcion para registrar la modificacion hecha por el usuario
	registrar_cambios($db, date('Y-m-d'), $_SESSION['sess_nombre'], '112', $id, $titulo, 'ELIMINAR');
	
	$result=$db->Execute("DELETE FROM ic_encuestas WHERE id_encuesta = '".$id."' ");
	
	if ($result != false) $mensaje = "3";
	else $mensaje  = "0";
	
	echo"<META HTTP-EQUIV='Refresh' CONTENT='0;URL=admin.php?mod=112&mensaje=".$mensaje."'>";
	die();
}
/****************************************************************************************************************************/

function reiniciar($id)
{
	$path = "adodb/adodb.inc.php";
	include "var.php";
	include "conexion.php";
	
	$sql="SELECT nombre_encuesta FROM ic_encuestas WHERE id_encuesta='".$id."' ";
	$result=$db->Execute($sql);
	
	list($titulo)=select_format($result->fields);
	
	//funcion para registrar la modificacion hecha por el usuario
	registrar_cambios($db, date('Y-m-d'), $_SESSION['sess_nombre'], '112', $id, $titulo, 'REINICIAR ENCUESTA');
	
	$result=update_bd_format(array("valor1_encuesta","valor2_encuesta","valor3_encuesta","valor4_encuesta","valor5_encuesta","valor6_encuesta"), "ic_encuestas", array("0","0","0","0","0","0"), "id_encuesta='".$id."'", $db);
	
	if ($result != false) $mensaje = "4";
	else $mensaje  = "0";
	
	echo"<META HTTP-EQUIV='Refresh' CONTENT='0;URL=admin.php?mod=112&mensaje=".$mensaje."'>";
	die();
}

/****************************************************************************************************************************/

function guardar($nombre,$comentario,$item1,$item2,$item3,$item4,$item5,$item6,$lang,$activa)
{
	$path = "adodb/adodb.inc.php";
	include "var.php";
	include "conexion.php";
	
	if($lang==""){
		$lang=$_SESSION['idioma'];
	}
	
	$result=insert_bd_format("nombre_encuesta,fecha_encuesta,comentario_encuesta,pregunta1_encuesta,pregunta2_encuesta,pregunta3_encuesta,pregunta4_encuesta,pregunta5_encuesta,pregunta6_encuesta,l_lang,activa_encuesta", "ic_encuestas", array($nombre,date('Y-m-d'),$comentario,$item1,$item2,$item3,$item4,$item5,$item6,$lang,$activa), $db);
						  
	if ($result != false) $mensaje = "1";
	else $mensaje  = "0";
	
	//funcion para registrar la modificacion hecha por el usuario
	registrar_cambios($db, date('Y-m-d'), $_SESSION['sess_nombre'], '112', '0', $nombre, 'GUARDAR');
	
	implantar_urls($db);
	
	echo"<META HTTP-EQUIV='Refresh' CONTENT='0;URL=admin.php?mod=112&mensaje=".$mensaje."'>";
	die();	
}

/****************************************************************************************************************************/

function modificar($id,$nombre,$comentario,$item1,$item2,$item3,$item4,$item5,$item6,$lang,$activa)
{
	$path = "adodb/adodb.inc.php";
	include "var.php";
	include "conexion.php";

	if($lang==""){
		$lang=$_SESSION['idioma'];
	}
	
	$result=update_bd_format(array("nombre_encuesta","comentario_encuesta","pregunta1_encuesta","pregunta2_encuesta","pregunta3_encuesta","pregunta4_encuesta","pregunta5_encuesta","pregunta6_encuesta","l_lang","activa_encuesta"), "ic_encuestas", array($nombre,$comentario,$item1,$item2,$item3,$item4,$item5,$item6,$lang,$activa), "WHERE id_encuesta='".$id."'", $db);
	
	if ($result != false) $mensaje = "2";
	else $mensaje  = "0";
	
	//funcion para registrar la modificacion hecha por el usuario
	registrar_cambios($db, date('Y-m-d'), $_SESSION['sess_nombre'], '112', $id, $nombre, 'MODIFICAR');
	
	implantar_urls($db);
	
	echo"<META HTTP-EQUIV='Refresh' CONTENT='0;URL=admin.php?mod=112&mensaje=".$mensaje."'>";
	die();
}
/****************************************************************************************************************************/
?>
<form action="" name="modificar" method="post">
	<table border="0" cellspacing="5" cellpadding="0" align="center">
	<tr align="center">
	<td colspan="3" class="titulo"><strong>Crear/Modificar Encuestas </strong></td>
	</tr>
	<tr>
	<td><strong>Modificar Encuesta </strong></td>
	<td>
	<strong>
	<select name="id">
	<?php cargar_lista("ic_encuestas","id_encuesta,l_lang,nombre_encuesta,fecha_encuesta","fecha_encuesta","1","","",$db); ?>
	</select>
	</strong></td>
	<td><strong>
	<input type="submit" name="Submit" value="Modificar">
	</strong></td>
	</tr>
	<tr><td colspan="3">&nbsp;</td></tr>
	</table>
</form>

<?php
if($mensaje==1){ 
	$mensaje = "<center><span id='confirmacion'>Informaci&oacute;n Guardada Correctamente</span></center>";
}elseif(($mensaje==2)&&($mensaje!="")){
	$mensaje  = "<center><span id='confirmacion'>Informaci&oacute;n Actualizada Correctamente</span></center>"; 
}elseif(($mensaje==3)&&($mensaje!="")){
	$mensaje  = "<center><span id='confirmacion'>Informaci&oacute;n Eliminada Correctamente</span></center>"; 
}elseif(($mensaje==4)&&($mensaje!="")){
	$mensaje  = "<center><span id='confirmacion'>Encuesta Reiniciada</span></center>"; 
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

<table  border="0" align="center" cellpadding="0" cellspacing="5">
<form action="" name="guardar" method="post">
<input type="hidden" name="funcion" value="guardar">
<?php

$link_resultados = "";

if ($id != "")
{
	$sql="SELECT id_encuesta,l_lang,nombre_encuesta,fecha_encuesta,comentario_encuesta,pregunta1_encuesta,
	             pregunta2_encuesta,pregunta3_encuesta,pregunta4_encuesta,pregunta5_encuesta,pregunta6_encuesta, activa_encuesta
		  FROM ic_encuestas WHERE id_encuesta='".$id."' ";
	$result=$db->Execute($sql);
	
	list($id_encuesta,$lang,$nombre,$fecha_encuesta,$comentario,$item1,$item2,$item3,$item4,$item5,$item6,$activa)=select_format($result->fields);
	
	echo '<input type="hidden" name="id" value="'.$id_encuesta.'">';
	
	$link_resultados = '<a href="ver.php?mod=encuesta&id_item='.$id_encuesta.'" target="_blank">Resultados de la Encuesta</a>';
}else{
	$fecha_encuesta="";
}
?>
<tr>
<td valign="top">&nbsp;</td>
<td><div align="right"><a href="admin.php?mod=112">Cancelar / Nuevo </a></div></td>
</tr>
<tr>
<td valign="top"><div align="right"></div></td>
<td><div align="right"><font color="#FF0000" size="1">(*)Campos Obligatorios</font></div></td>
</tr>
<tr>
<td valign="top"><div align="right"><strong>Fecha  Creaci&oacute;n</strong></div></td>
<td><? if ($fecha_encuesta==""){ echo date('Y-m-d'); } else { echo $fecha_encuesta; }?></td>
</tr>
<tr>
<td valign="top"><div align="right"><strong>Idioma</strong></div></td>
<td><strong>
<select name="lang" id="lang" <?=$activo_campo_idioma?> >
	<?php cargar_lista("ic_language","lang_prefijo,lang_descripcion","","0",$lang,"",$db); ?>
</select>
</strong></td>
</tr>
<tr>
  <td valign="top"><div align="right"><strong>Encuesta Activa </strong></div></td>
  <td><select name="activa" id="activa" >
		<?php cargar_lista_estatica("ACTIVA,INACTIVA","Activa,Inactiva",1,$activa); ?>
	</select> </td>
</tr>
<tr>
<td valign="top"><div align="right"><strong>Nombre </strong>(*)</div></td>
<td>
<input  name="nombre" type="text" id="nombre" value="<?=$nombre?>" size="50"></td>
</tr>
<tr>
  <td valign="top">&nbsp;</td>
  <td>
<?=$link_resultados?>
  </td>
</tr>
<tr>
  <td valign="top"><div align="right"><strong>Item 1</strong>(*)</div></td>
  <td><input  name="item1" type="text" id="item1" value="<?=$item1?>" size="60" /></td>
</tr>
<tr>
  <td valign="top"><div align="right"><strong>Item 2 </strong>(*)</div></td>
  <td><input  name="item2" type="text" id="item2" value="<?=$item2?>" size="60" /></td>
</tr>
<tr>
  <td valign="top"><div align="right"><strong>Item 3 </strong></div></td>
  <td><input  name="item3" type="text" id="item3" value="<?=$item3?>" size="60" /></td>
</tr>
<tr>
  <td valign="top"><div align="right"><strong>Item 4 </strong></div></td>
  <td><input  name="item4" type="text" id="item4" value="<?=$item4?>" size="60" /></td>
</tr>
<tr>
  <td valign="top"><div align="right"><strong>Item 5 </strong></div></td>
  <td><input  name="item5" type="text" id="item5" value="<?=$item5?>" size="60" /></td>
</tr>
<tr>
  <td valign="top"><div align="right"><strong>Item 6 </strong></div></td>
  <td><input  name="item6" type="text" id="item6" value="<?=$item6?>" size="60" /></td>
</tr>
<tr>
  <td valign="top">&nbsp;</td>
  <td>&nbsp;</td>
</tr>
<tr>
  <td valign="top"><div align="right"><strong>Comentario</strong></div></td>
  <td><textarea name="comentario" cols="60" rows="5"><?=$comentario?></textarea></td>
</tr>
<tr>
  <td valign="top">&nbsp;</td>
  <td>
<tr>
<td valign="top">&nbsp;</td>
<td>
<input type="button" onClick="enviar('activa,nombre,item1,item2');" name="Enviar" value="Guardar Encuesta">
</form><br /><br />
<table border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><form action="" method="post" name="eliminar">
<input type="submit" name="Borrar" value="Borrar Encuesta">
<input type="hidden" name="id" value="<?=$id?>">
<input type="hidden" name="funcion" value="borrar">
</form></td>
	<td><img src="images/spacer.gif" width="20" height="1" /></td>
    <td><form action="" method="post" name="reiniciar">
<input type="submit" name="Borrar" value="Reiniciar Encuesta">
<input type="hidden" name="id" value="<?=$id?>">
<input type="hidden" name="funcion" value="reiniciar">
</form></td>
  </tr>
</table>

</td>
</tr>
</table>
