<?php
session_permiso("11");

$variables_metodo = variables_metodo("id,fecha,titulo,full,funcion,faq_id,faq_lang,faq_categoria");

$id= 			$variables_metodo[0];
$fecha= 		$variables_metodo[1];
$titulo= 		$variables_metodo[2];
$full= 			$variables_metodo[3];
$funcion= 		$variables_metodo[4];
$faq_id= 		$variables_metodo[5];
$faq_lang= 		$variables_metodo[6];
$faq_categoria= $variables_metodo[7];

if ($funcion == "guardar"){
	if($id==""){
	/*Funcion para guardar los datos del formulario*/	
		guardar($fecha,$titulo,$full,$faq_lang,$faq_categoria);
	}elseif($id!=""){
	/*Funcion para modificar los datos del formulario*/	
		modificar($id,$fecha,$titulo,$full,$faq_lang,$faq_categoria);
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
	
	$sql="SELECT faq_titulo FROM ic_faq WHERE faq_id='".$id."' ";
	$result=$db->Execute($sql);
	
	list($titulo)=select_format($result->fields);

	//funcion para registrar la modificacion hecha por el usuario
	registrar_cambios($db, date('Y-m-d'), $_SESSION['sess_nombre'], '11', $id, $titulo, 'ELIMINAR');
	
	$result=$db->Execute("DELETE FROM ic_faq WHERE faq_id = '".$id."' ");
	
	if ($result != false) $mensaje = "3";
	else $mensaje  = "0";
	
	echo"<META HTTP-EQUIV='Refresh' CONTENT='0;URL=admin.php?mod=11&mensaje=".$mensaje."'>";
	die();
}
/****************************************************************************************************************************/
function guardar($fecha,$titulo,$full,$faq_langm,$faq_categoria)
{
	$path = "adodb/adodb.inc.php";
	include "var.php";
	include "conexion.php";
	
		
	$result=insert_bd_format("faq_titulo,faq_conten,faq_creacion,faq_categoria", "ic_faq", array($titulo,$full,$fecha,$faq_categoria), $db);

	if ($result != false) $mensaje = "1";
	else $mensaje  = "0";
	
	//funcion para registrar la modificacion hecha por el usuario
	registrar_cambios($db, date('Y-m-d'), $_SESSION['sess_nombre'], '11', '0', $titulo, 'GUARDAR');
	
	echo"<META HTTP-EQUIV='Refresh' CONTENT='0;URL=admin.php?mod=11&mensaje=".$mensaje."'>";
	die();	
}
/****************************************************************************************************************************/
function modificar($id,$fecha,$titulo,$full,$faq_lang,$faq_categoria)
{
	$path = "adodb/adodb.inc.php";
	include "var.php";
	include "conexion.php";
	
	$result=update_bd_format(array("faq_titulo","faq_creacion","faq_conten","faq_categoria"), "ic_faq", array($titulo,$fecha,$full,$faq_categoria), "WHERE faq_id='".$id."'", $db);
	
	if ($result != false) $mensaje = "2";
	else $mensaje  = "0";
	
	//funcion para registrar la modificacion hecha por el usuario
	registrar_cambios($db, date('Y-m-d'), $_SESSION['sess_nombre'], '11', $id, $titulo, 'MODIFICAR');
	
	echo"<META HTTP-EQUIV='Refresh' CONTENT='0;URL=admin.php?mod=11&mensaje=".$mensaje."'>";
	die();
}
/****************************************************************************************************************************/
?>
<script language="javascript" type="text/javascript">
	incluir_editor("texto_completo");
</script>


<form action="" name="modificar" method="post">
<table border="0"  width="600" align="center">
  <tr align="center">
    <td colspan="2">
    <img src="images/admin/faq.png" border="0"/>  Preguntas Frecuentes
    </td>
  </tr>
    <tr>
  	<td>&nbsp;</td>
  </tr>
   <tr>
    <td>
	  <select name="faq_id" class="form-control">
		<?php cargar_lista("ic_faq","faq_id,faq_titulo","faq_titulo","1","","",$db); ?>
      </select>
	  </td>
    <td width="150">
      <input type="submit" name="Submit" value="Edit">
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
</script>

<table  border="0" align="center" width="800">
<form action="" name="guardar" method="post">
<input type="hidden" name="funcion" value="guardar">
<input type="hidden" name="fecha" value="<? echo date('Y-m-d'); ?>">
<?php
if ($faq_id != "")
{
$sql="SELECT faq_id,faq_titulo,faq_conten,faq_creacion,l_lang,faq_categoria FROM ic_faq WHERE faq_id='".$faq_id."' ";
		$result=$db->Execute($sql);
		
		list($id,$titulo,$full,$fecha,$faq_lang,$faq_categoria)=select_format($result->fields);
				
		echo '<input type="hidden" name="id" value="'.$id.'">';
		echo '<input type="hidden" name="fecha" value="'.date("Y-m-d").'">';
}
?>
<tr>
  	<td colspan="2"><div style="margin:20px 0; width:100%; border-top:1px solid #494949;"></div></td>
  </tr>
 <tr>
   <td valign="top">&nbsp;</td>
   <td><div align="right"><a href="admin.php?mod=11">Cacelar/Nuevo</a></div></td>
 </tr>
 <tr>
   <td valign="top"><div align="right"></div></td>
   <td><div align="right"><font color="#FF0000" size="1">(*)Campos requeridos</font></div></td>
 </tr>
 <tr>
    <td valign="top"><div align="right"><strong>Fecha</strong></div></td>
    <td><? if ($fecha==""){ echo date('Y-m-d'); } else { echo $fecha; }?></td>
  </tr>
  <tr>
    <td valign="top"><div align="right"><strong>Idioma</strong></div></td>
    <td><strong>
      <select name="faq_lang" id="faq_lang" class="form-control" <?=$activo_campo_idioma?> >
        <?php cargar_lista("ic_language","lang_prefijo,lang_descripcion","lang_id","0",$faq_lang,"",$db); ?>
      </select>
    </strong></td>
  </tr>
  <tr>
    <td valign="top"><div align="right"><strong>Categoría</strong></div></td>
    <td><strong>
      <select name="faq_categoria" class="form-control" id="faq_categoria">
        <?php cargar_lista("ic_categoria","cat_id,cat_titulo","cat_titulo","1",$faq_categoria," WHERE id_tipo='9'",$db); ?>
      </select>
    </strong></td>
  </tr>
  <tr>
    <td valign="top"><div align="right"><strong>Pregunta</strong>(*)</div></td>
    <td>
		<input  name="titulo" type="text" id="titulo" value="<? echo $titulo; ?>" class="form-control">	</td>
  </tr>
  <tr>
    <td valign="top"><div align="right">
      <p><strong>Respuesta</strong>(*)    </p>
    </div></td>
    <td><textarea name="full" class="form-control" id="texto_completo"><?= $full?></textarea></td>
  </tr>
  <tr>
    <td valign="top">&nbsp;</td>
    <td>&nbsp;</td> 
  </tr>
  <tr>
    <td valign="top">&nbsp;</td>
    <td align="right">
	<input type="button" onClick="enviar('titulo');" name="Enviar" value="Guardar Faq">
	</form>
	<p>
    <form action="" method="post" name="eliminar">
	<input type="submit" name="Borrar" id="borrar" value="Borrar Faq">
	<input type="hidden" name="id" value="<?=$id?>">
	<input type="hidden" name="funcion" value="borrar">
	</form>
	</p>
</td>
  </tr>
</table>
