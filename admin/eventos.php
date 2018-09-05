<?php
session_permiso("116");

$variables_metodo = variables_metodo("id,fecha,titulo,full,funcion,hora,id_eve,eve_lang,eve_principal,previa,lugar,ciudad,home_evento,aprobada");

$id= 			$variables_metodo[0];
$fecha= 		$variables_metodo[1];
$titulo= 		$variables_metodo[2];
$full= 			$variables_metodo[3];
$funcion= 		$variables_metodo[4];
$hora= 			$variables_metodo[5];
$id_eve= 		$variables_metodo[6];
$eve_lang= 		$variables_metodo[7];
$eve_principal= $variables_metodo[8];
$previa= 		$variables_metodo[9];
$lugar= 		$variables_metodo[10];
$ciudad= 		$variables_metodo[11];
$home_evento= 	$variables_metodo[12];
$aprobada= 	$variables_metodo[13];

if ($funcion == "guardar"){
	if($id==""){
		/*Funcion para guardar los datos del formulario*/	
		guardar($fecha,$titulo,$full,$hora,$eve_lang,$eve_principal,$previa,$lugar,$ciudad,$home_evento,$aprobada);
	}elseif($id!=""){
		/*Funcion para modificar los datos del formulario*/	
		modificar($id,$fecha,$titulo,$full,$hora,$eve_lang,$eve_principal,$previa,$lugar,$ciudad,$home_evento,$aprobada);
	}
}elseif(($funcion == "borrar")&&($id!="")){
	borrar($id);
}

/****************************************************************************************************************************/

function borrar($id){

	$path = "adodb/adodb.inc.php";
	include "var.php";
	include "conexion.php";	

	$sql="SELECT nombre_evento FROM ic_eventos WHERE id_evento='".$id."' ";
	$result=$db->Execute($sql);
	
	list($titulo)=select_format($result->fields);

	//funcion para registrar la modificacion hecha por el usuario
	registrar_cambios($db, date('Y-m-d'), $_SESSION['sess_nombre'], '116', $id, $titulo, 'ELIMINAR');
	
	$result=$db->Execute("DELETE FROM ic_eventos WHERE id_evento = '".$id."' ");

	if ($result != false) $mensaje = "3";
	else $mensaje  = "0";

	echo"<META HTTP-EQUIV='Refresh' CONTENT='0;URL=admin.php?mod=116&mensaje=".$mensaje."'>";
	die();
}

/****************************************************************************************************************************/

function guardar($fecha,$titulo,$full,$hora,$eve_lang,$eve_principal,$previa,$lugar,$ciudad,$home_evento,$aprobada){

	$path = "adodb/adodb.inc.php";
	include "var.php";
	include "conexion.php";	

	if($eve_lang==""){
		$eve_lang=$_SESSION['idioma'];
	}
	
	$result=insert_bd_format("fecha_evento,hora_evento,nombre_evento,descripcion_evento,l_lang,principal_evento,previa_evento,lugar_evento,ciudad_evento,home_evento,aprobada", "ic_eventos", array($fecha,$hora,$titulo,$full,$eve_lang,$eve_principal,$previa,$lugar,$ciudad,$home_evento,$aprobada), $db);

	if ($result != false) $mensaje = "1";
	else $mensaje  = "0";	
	
	//funcion para registrar la modificacion hecha por el usuario
	registrar_cambios($db, date('Y-m-d'), $_SESSION['sess_nombre'], '116', '0', $titulo, 'GUARDAR');
	
	implantar_urls($db);
	
	echo"<META HTTP-EQUIV='Refresh' CONTENT='0;URL=admin.php?mod=116&mensaje=".$mensaje."'>";
	die();	
}

/****************************************************************************************************************************/

function modificar($id,$fecha,$titulo,$full,$hora,$eve_lang,$eve_principal,$previa,$lugar,$ciudad,$home_evento,$aprobada){

	$path = "adodb/adodb.inc.php";
	include "var.php";
	include "conexion.php";

	if($eve_lang==""){
		$eve_lang=$_SESSION['idioma'];
	}

	$result=update_bd_format(array("fecha_evento","hora_evento","l_lang","previa_evento","nombre_evento","descripcion_evento","principal_evento","lugar_evento","ciudad_evento","home_evento","aprobada"), "ic_eventos", array($fecha,$hora,$eve_lang,$previa,$titulo,$full,$eve_principal,$lugar,$ciudad,$home_evento,$aprobada), "WHERE id_evento='".$id."'", $db);

	if ($result != false) $mensaje = "2";
	else $mensaje  = "0";
	
	//funcion para registrar la modificacion hecha por el usuario
	registrar_cambios($db, date('Y-m-d'), $_SESSION['sess_nombre'], '116', $id, $titulo, 'MODIFICAR');
	
	implantar_urls($db);
	
	echo"<META HTTP-EQUIV='Refresh' CONTENT='0;URL=admin.php?mod=116&id_eve=".$id."&mensaje=".$mensaje."'>";
	die();
}

/****************************************************************************************************************************/
?>

<script language="javascript">	

function enviar(campos){

	var camposObligatorios = campos.split(",");	

	for(i=0;i<camposObligatorios.length;i++){	

		if(document.getElementById(camposObligatorios[i]).value==""){
			alert("Campo "+ camposObligatorios[i] +" obligatorio");
			return;
		}
	}	
	document.guardar.submit();
}

//Inclusion del editor para los campos de texto
incluir_editor("previa,full");

</script>

<p align="center" class="titulo">Create/Edit Events</p>
<p align="center" class="titulo">&nbsp;</p>
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

<table  border="0" align="center" cellpadding="0" cellspacing="5">
<form action="" name="guardar" method="post">
<input type="hidden" name="funcion" value="guardar">
<?php

if ($id_eve != ""){

	$sql="SELECT 
			id_evento, nombre_evento, descripcion_evento, fecha_evento, 
			hora_evento, l_lang, principal_evento, previa_evento, 
			lugar_evento, ciudad_evento, home_evento , aprobada
		  FROM 
		  	ic_eventos 
		  WHERE 
		  	id_evento='".$id_eve."'";
	$result=$db->Execute($sql);

	list($id,$titulo,$full,$fecha,$hora,$eve_lang,$eve_principal,$previa,$lugar,$ciudad,$home_evento,$aprobada)=select_format($result->fields);	

	echo '<input type="hidden" name="id" value="'.$id.'">';
}
?>
 <tr>
   <td colspan="2" valign="top"><a href="admin.php?mod=115">Back to LIst</a></td>
   <td colspan="2"><div align="right"><a href="admin.php?mod=116">Cancel/New</a></div></td>
   </tr>
 <tr>
   <td colspan="4" valign="top"><div align="right"></div>     <div align="right"><font color="#FF0000" size="1">(*)Required Fields</font></div></td>
   </tr>
 <tr>
    <td width="100" valign="top"><div align="right"><strong>Events Date</strong></div></td>
    <td colspan="3"><input type="text" size="9" name="fecha" id="fecha" value="<? if ($fecha==""){ echo date('Y-m-d'); } else { echo $fecha; }?>">
      <a href="javascript:;" onclick="showCalendar('',document.getElementById('fecha'),document.getElementById('fecha'),'','holder10',-10,5,1)" style="display: inline-block; vertical-align: middle"> 
	<img src="admin/calendario/icono_calendario.jpg"  width="18" height="17" border="0" hspace="2" /></a></td>
    </tr>
  <tr>
    <td valign="top"><div align="right"><strong>Language</strong></div></td>
    <td valign="top"><select name="eve_lang" id="eve_lang" <?=$activo_campo_idioma?> >
      <?php cargar_lista("ic_language","lang_prefijo,lang_descripcion","","0",$eve_lang,"",$db); ?>
    </select></td>
    <td valign="top"><div align="right"><strong>Status</strong>(*)</div></td>
    <td valign="top"><select name="aprobada" id="aprobada">
      <?php cargar_lista_estatica("S,N","Si,No","1",$aprobada); ?>
    </select></td>
    </tr>
  <tr>
    <td valign="top"><div align="right"><strong>Home </strong></div></td>
    <td valign="top"><input name="home_evento" type="checkbox" id="home_evento" value="S" border="0" <? if($home_evento=="S"){echo "checked='checked'";}?> /></td>
    <td valign="top"><div align="right"><strong>Main Event</strong></div></td>
    <td valign="top"><select name="eve_principal" id="eve_principal">
      <?php cargar_lista_estatica("S,N","Si,No","1",$eve_principal); ?>
    </select></td>
    </tr>
  <tr>
    <td valign="top"><div align="right"><strong>Event</strong>(*)</div></td>
    <td colspan="3">
		<input  name="titulo" type="text" id="Nombre" value="<?=$titulo?>" size="40">	</td>
    </tr>
  <tr>
    <td valign="top"><div align="right"><strong>Hour(*)</strong></div></td>
    <td><input type="text"  name="hora" id="hora"  size="10" value="<?=$hora?>">
      <font size="1"><em>hh:mm</em></font></td>
    <td><div align="right"><strong>City</strong>(*)</div></td>
    <td><input name="ciudad" type="text" id="ciudad" size="30" value="<?=$ciudad?>" /></td>
  </tr>
  <tr>
    <td valign="top"><div align="right"><strong>Place</strong>(*)</div></td>
    <td><input name="lugar" type="text" id="lugar" size="30" value="<?=$lugar?>" /></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    </tr>
  <tr>
    <td valign="top"><div align="right">
      <p><strong>Preview</strong>(*)</p>
      </div></td>
    <td colspan="3"><textarea name="previa" cols="50" rows="4" id="previa"><?= $previa?></textarea></td>
    </tr>
  <tr>
    <td valign="top"><div align="right">
      <p><strong>Full Description</strong>(*)</p>
      </div></td>
    <td colspan="3"><textarea name="full" cols="50" rows="4" id="Descripcion"><?= $full?></textarea></td>
    </tr>
  <tr>
    <td colspan="4" valign="top">&nbsp;</td>
    </tr>
  <tr>
    <td colspan="4">
	<input type="button" onClick="enviar('aprobada,Nombre,lugar,ciudad');" name="Enviar" value="Save">  
  </form><br /><br />
    <form action="" method="post" name="eliminar">
	<input type="submit" name="Borrar" value="Delete">
	<input type="hidden" name="id" value="<?=$id?>">
	<input type="hidden" name="funcion" value="borrar">
	</form>
	</td>
  </tr>
</table>
