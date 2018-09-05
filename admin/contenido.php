<?php
session_permiso("2");

$variables_metodo = variables_metodo("id,fecha,titulo,full,funcion,pg_id,pg_lang,pg_imprimir,pg_tag_titulo,pg_tag_desc,pg_tag_key");

$id= 			$variables_metodo[0];
$fecha= 		$variables_metodo[1];
$titulo= 		$variables_metodo[2];
$full= 			$variables_metodo[3];
$funcion= 		$variables_metodo[4];
$pg_id= 		$variables_metodo[5];
$pg_lang= 		$variables_metodo[6];
$pg_imprimir= 		$variables_metodo[7];
$pg_tag_titulo= 		$variables_metodo[8];
$pg_tag_desc= 		$variables_metodo[9];
$pg_tag_key= 		$variables_metodo[10];

if ($funcion == "guardar"){
	if($id==""){
	/*Funcion para guardar los datos del formulario*/	
		guardar($fecha,$titulo,$full,$pg_lang,$pg_imprimir,$pg_tag_titulo,$pg_tag_desc,$pg_tag_key);
	}elseif($id!=""){
	/*Funcion para modificar los datos del formulario*/	
		modificar($id,$fecha,$titulo,$full,$pg_lang,$pg_imprimir,$pg_tag_titulo,$pg_tag_desc,$pg_tag_key);
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
	
	$sql="SELECT pg_titulo FROM ic_contenido WHERE pg_id='".$id."' ";
	$result=$db->Execute($sql);
	
	list($titulo)=select_format($result->fields);
	
	//funcion para registrar la modificacion hecha por el usuario
	registrar_cambios($db, date('Y-m-d'), $_SESSION['sess_nombre'], '2', $id, $titulo, 'ELIMINAR');
	
	$result=$db->Execute("DELETE FROM ic_contenido WHERE pg_id = '".$id."' ");
	
	if ($result != false) $mensaje = "3";
	else $mensaje  = "0";
	
	echo"<META HTTP-EQUIV='Refresh' CONTENT='0;URL=admin.php?mod=2&mensaje=".$mensaje."'>";
	die();
}
/****************************************************************************************************************************/
function guardar($fecha,$titulo,$full,$pg_lang,$pg_imprimir,$pg_tag_titulo,$pg_tag_desc,$pg_tag_key)
{
	$path = "adodb/adodb.inc.php";
	include "var.php";
	include "conexion.php";
	
	if($pg_lang==""){
		$pg_lang=$_SESSION['idioma'];
	}
		
	if($pg_imprimir==""){
		$pg_imprimir="0";
	}
	
	$result=insert_bd_format("pg_titulo,pg_conten,pg_creacion,l_lang,pg_imprimir,pg_tag_titulo,pg_tag_desc,pg_tag_key", "ic_contenido", array($titulo,$full,$fecha,$pg_lang,$pg_imprimir,$pg_tag_titulo,$pg_tag_desc,$pg_tag_key), $db);

	if ($result != false) $mensaje = "1";
	else $mensaje  = "0";
	
	//funcion para registrar la modificacion hecha por el usuario
	registrar_cambios($db, date('Y-m-d'), $_SESSION['sess_nombre'], '2', '0', $titulo, 'GUARDAR');
	
	implantar_urls($db);
	
	echo"<META HTTP-EQUIV='Refresh' CONTENT='0;URL=admin.php?mod=2&mensaje=".$mensaje."'>";
	die();	
}
/****************************************************************************************************************************/
function modificar($id,$fecha,$titulo,$full,$pg_lang,$pg_imprimir,$pg_tag_titulo,$pg_tag_desc,$pg_tag_key)
{
	$path = "adodb/adodb.inc.php";
	include "var.php";
	include "conexion.php";
	
	if($pg_lang==""){
		$pg_lang=$_SESSION['idioma'];
	}
		
	if($pg_imprimir==""){
		$pg_imprimir="0";
	}
	
	$result=update_bd_format(array("pg_titulo","pg_creacion","pg_conten","l_lang","pg_imprimir","pg_tag_titulo","pg_tag_desc","pg_tag_key"), "ic_contenido", array($titulo,$fecha,$full,$pg_lang,$pg_imprimir,$pg_tag_titulo,$pg_tag_desc,$pg_tag_key), "WHERE pg_id='".$id."'", $db);
	
	if ($result != false) $mensaje = "2";
	else $mensaje  = "0";
	
	//funcion para registrar la modificacion hecha por el usuario
	registrar_cambios($db, date('Y-m-d'), $_SESSION['sess_nombre'], '2', $id, $titulo, 'MODIFICAR');
	
	implantar_urls($db);
	
	echo"<META HTTP-EQUIV='Refresh' CONTENT='0;URL=admin.php?mod=2&mensaje=".$mensaje."&pg_id=".$id."'>";
	die();
}
/****************************************************************************************************************************/
?>
<script language="javascript" type="text/javascript">
	incluir_editor("texto_completo");
</script>
<form action="" name="modificar" method="post">
<table border="0" width="600" align="center">
  <tr align="center">
    <td colspan="12">
    <img src="images/admin/estaticas.png" border="0"/>  Páginas Estáticas
    </td>
  </tr>
  <tr>
  	<td colspan="12">&nbsp;</td>
  </tr>
   <tr>
    <td>
	  <select name="pg_id" class="form-control">		
		<?php cargar_lista("ic_contenido","pg_id,l_lang,pg_titulo","pg_titulo","1","","",$db); ?>
      </select>
	  </td>
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
}else {
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

function mostrarAdicional(){
	if(document.getElementById("tabla_adicional")){
		if(document.getElementById("tabla_adicional").style.display=="none"){
			document.getElementById("tabla_adicional").style.display="inline";
		}else{
			document.getElementById("tabla_adicional").style.display="none";
		}
	}
}
</script>

<table width="1100" border="0" align="center">
<form action="" name="guardar" method="post">
<input type="hidden" name="funcion" value="guardar">
<input type="hidden" name="fecha" value="<? echo date('Y-m-d'); ?>">
<?php
if ($pg_id != "")
{
$sql="SELECT pg_id,pg_titulo,pg_conten,pg_creacion,l_lang,pg_imprimir,pg_tag_titulo,pg_tag_desc,pg_tag_key FROM ic_contenido WHERE pg_id='".$pg_id."' ";
		$result=$db->Execute($sql);
		
		list($id,$titulo,$full,$fecha,$pg_lang,$pg_imprimir,$pg_tag_titulo,$pg_tag_desc,$pg_tag_key)=select_format($result->fields);
				
		echo '<input type="hidden" name="id" value="'.$id.'">';
}
?>

  <tr>
  	<td colspan="5"><div style="margin:20px 0; width:100%; border-top:1px solid #494949;"></div></td>
  </tr>
 <tr>
   <td valign="top" width="100">&nbsp;</td>
   <td><div align="right"><a href="admin.php?mod=2">Cancelar/Nuevo</a></div></td>
 </tr>
 <tr>
   <td valign="top"><div align="right"></div></td>
   <td><div align="right"><font color="#FF0000" size="1">(*)Campos requeridos</font></div></td>
 </tr>
 <tr>
    <td colspan="2" valign="top"><div align="right"><strong>Creation Date
      <?php if ($fecha==""){ echo date('Y-m-d'); } else { echo $fecha; }?>
    </strong></div></td>
    </tr>
  <tr>
    <td><div align="left"><strong>Idioma</strong></div></td>
    <td><strong>
      <select name="pg_lang" id="pg_lang" <?=$activo_campo_idioma?>  class="form-control">
        <?php cargar_lista("ic_language","lang_prefijo,lang_descripcion","lang_id","0",$pg_lang,"",$db); ?>
      </select>
    </strong></td>
  </tr>
  <tr>
    <td><div align="left"><strong>Titulo</strong>(*)</div></td>
    <td><input type="text" size="30" name="titulo" id="titulo" class="form-control" value="<?= $titulo; ?>" /></td>
  </tr>
  <tr>
    <td valign="top" nowrap="nowrap"><div align="left"><strong>Imprimir</strong></div></td>
    <td>
<?php
$checked="";
if($pg_imprimir=="1"){
	$checked = "checked";
}

?>
      <input type="checkbox" name="pg_imprimir" id="pg_imprimir" value="1"  <?=$checked?> />
    </td>
  </tr>
  <tr>
    <td valign="top"><b>URL de Acceso</b></td>
    <td>
<?php
	if ($id != ""){
?>
       <a href="<?=organizar_nombre_link("contenido", $titulo)?>" target="_blank"><?=organizar_nombre_link("contenido", $titulo)?></a>
      <?php
	}else{
		echo "Sin URL.";
	}
?>
    </td>
  </tr>
   <tr>
    <td align="right" valign="top"><div align="left">
      <strong>Texto</strong>(*)
    </div></td>

    <td valign="top"><textarea name="full"  class="form-control" id="texto_completo"><?= $full?></textarea></td>
    </tr>
  <tr>
    <td colspan="2" align="right" valign="top"><div align="left"> <strong><a href="javascript:;" onclick="mostrarAdicional();">Información Adicional [+]</a></strong></div></td>
  </tr>
  <tr>
    <td colspan="2" valign="top">
    <table  border="0" cellpadding="2" cellspacing="2" id="tabla_adicional" style="display:none; width:100% !important;">
    <tr>
      <td colspan="2" valign="top"><b>T&iacute;tulo de la p&aacute;gina</b></td>
    </tr>
    <tr>
      <td colspan="2" valign="top"><textarea name="pg_tag_titulo"  class="form-control" id="pg_tag_titulo"><?= $pg_tag_titulo?></textarea></td>
    </tr>
    <tr>
      <td colspan="2" align="right" valign="top"><div align="left"> <strong>Descripci&oacute;n de la P&aacute;gina</strong></div></td>
    </tr>
    <tr>
      <td colspan="2" valign="top"><textarea name="pg_tag_desc"  class="form-control" id="pg_tag_desc"><?= $pg_tag_desc?></textarea></td>
    </tr>
    <tr>
      <td colspan="2" align="right" valign="top"><div align="left"> <strong>KeyWords</strong>(*) </div></td>
    </tr>
    <tr>
      <td colspan="2" valign="top"><textarea name="pg_tag_key"  class="form-control" id="pg_tag_key"><?= $pg_tag_key?></textarea></td>
    </tr>
</table>

    </td>
    </tr>
  <tr>
    <td valign="top">&nbsp;</td>
    <td align="right">
	<input type="button" onClick="enviar('titulo');" name="Enviar" value="Guardar">
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
