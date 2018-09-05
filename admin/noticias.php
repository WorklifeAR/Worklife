<?php
session_permiso("1");

$variables_metodo = variables_metodo("id,fecha,titulo,full,previa,funcion,categoria,n_id,n_lang,n_tag_titulo,n_tag_desc,n_tag_key,n_imagen,borrarimg,palabra_fil,fecha_fil");

$id= 			$variables_metodo[0];
$fecha= 		$variables_metodo[1];
$titulo= 		$variables_metodo[2];
$full= 			$variables_metodo[3];
$previa= 		$variables_metodo[4];
$funcion= 		$variables_metodo[5];
$categoria= 	$variables_metodo[6];
$n_id= 			$variables_metodo[7];
$n_lang= 		$variables_metodo[8];
$n_tag_titulo= 		$variables_metodo[9];
$n_tag_desc= 		$variables_metodo[10];
$n_tag_key= 		$variables_metodo[11];
$n_imagen= 		$variables_metodo[12];
$borrarimg= 		$variables_metodo[13];
$palabra_fil= 		$variables_metodo[14];
$fecha_fil= 		$variables_metodo[15];

if ($funcion == "guardar"){
	if($id==""){
	/*Funcion para guardar los datos del formulario*/	
		guardar($fecha,$titulo,$full,$previa,$categoria,$n_lang,$n_tag_titulo,$n_tag_desc,$n_tag_key,$n_imagen);
	}elseif($id!=""){
	/*Funcion para modificar los datos del formulario*/	
		modificar($id,$fecha,$titulo,$full,$previa,$categoria,$n_lang,$n_tag_titulo,$n_tag_desc,$n_tag_key,$n_imagen,$borrarimg);
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
	
	$sql="SELECT n_titulo FROM ic_noticias WHERE n_id='".$id."' ";
	$result=$db->Execute($sql);
	
	list($titulo)=select_format($result->fields);

	//funcion para registrar la modificacion hecha por el usuario
	registrar_cambios($db, date('Y-m-d'), $_SESSION['sess_nombre'], '1', $id, $titulo, 'ELIMINAR');
	
	$result=$db->Execute("DELETE FROM ic_noticias WHERE n_id = '".$id."' ");
	
	if ($result != false) $mensaje = "3";
	else $mensaje  = "0";
	
	echo"<META HTTP-EQUIV='Refresh' CONTENT='0;URL=admin.php?mod=1&mensaje=".$mensaje."'>";
	die();
}

/****************************************************************************************************************************/

function guardar($fecha,$titulo,$full,$previa,$categoria,$n_lang,$n_tag_titulo,$n_tag_desc,$n_tag_key,$n_imagen)
{
	$path = "adodb/adodb.inc.php";
	include "var.php";
	include "conexion.php";

	if($n_lang==""){
		$n_lang=$_SESSION['idioma'];
	}
	
	$dir = "images/";
	$url_full = $_FILES['n_imagen']['tmp_name'];
	$imagen_full = $_FILES['n_imagen']['name']; 
	
	if($url_full!="" && $imagen_full!=""){
		$alt = mt_rand();
				
		if(subir_imagen($alt.$imagen_full, $url_full, $dir)){
			$n_imagen = $dir.$alt.$imagen_full;
		}
	}
	
	$result=insert_bd_format("n_titulo,n_des,n_text,n_fecha,n_categoria,l_lang,n_tag_titulo,n_tag_desc,n_tag_key,n_imagen", "ic_noticias", array($titulo,$previa,$full,$fecha,$categoria,$n_lang,$n_tag_titulo,$n_tag_desc,$n_tag_key,$n_imagen), $db);

	if ($result != false) $mensaje = "1";
	else $mensaje  = "0";
	
	//funcion para registrar la modificacion hecha por el usuario
	registrar_cambios($db, date('Y-m-d'), $_SESSION['sess_nombre'], '1', '0', $titulo, 'GUARDAR');
	
	implantar_urls($db);
	
	echo"<META HTTP-EQUIV='Refresh' CONTENT='0;URL=admin.php?mod=1&mensaje=".$mensaje."'>";
	die();	
}

/****************************************************************************************************************************/

function modificar($id,$fecha,$titulo,$full,$previa,$categoria,$n_lang,$n_tag_titulo,$n_tag_desc,$n_tag_key,$n_imagen,$borrarimg)
{
	$path = "adodb/adodb.inc.php";
	include "var.php";
	include "conexion.php";	
	
	if($n_lang==""){
		$n_lang=$_SESSION['idioma'];
	}
		
	$dir = "images/";
	$url_full = $_FILES['n_imagen']['tmp_name'];
	$imagen_full = $_FILES['n_imagen']['name']; 
	
	if($url_full!="" && $imagen_full!=""){
		$alt = mt_rand();
				
		if(subir_imagen($alt.$imagen_full, $url_full, $dir)){
			$n_imagen1 = "n_imagen";
			$n_imagen = $dir.$alt.$imagen_full;
		}
	}
	
	if($borrarimg=="S"){
		$n_imagen = "";
		$n_imagen1 = "n_imagen";
	}
	
	$result=update_bd_format(array("n_fecha","n_titulo","n_des","n_text","n_categoria","l_lang","n_tag_titulo","n_tag_desc","n_tag_key",$n_imagen1), 
						 	 "ic_noticias",
							  array($fecha,$titulo,$previa,$full,$categoria,$n_lang,$n_tag_titulo,$n_tag_desc,$n_tag_key,$n_imagen), 
							  "WHERE n_id='".$id."'", 
							  $db);
	
	if ($result != false) $mensaje = "2";
	else $mensaje  = "0";
	
	//funcion para registrar la modificacion hecha por el usuario
	registrar_cambios($db, date('Y-m-d'), $_SESSION['sess_nombre'], '1', $id, $titulo, 'MODIFICAR');
	
	implantar_urls($db);
	
	echo"<META HTTP-EQUIV='Refresh' CONTENT='0;URL=admin.php?mod=1&mensaje=".$mensaje."&n_id=".$id."'>";
	die();
}
/****************************************************************************************************************************/

if($n_id=="" && $funcion!="nuevo"){
	$where = "";
	if($palabra_fil!=""){
		$where .= " AND (n_titulo LIKE '%".$palabra_fil."%' OR n_des LIKE '%".$palabra_fil."%')";
	}
	
	if($fecha_fil!=""){
		$where .= " AND (n_fecha = '".$fecha_fil."')";
	}
	
	$sql="SELECT n_id,n_titulo,n_des,n_text,n_fecha,n_categoria,l_lang,n_tag_titulo,n_tag_desc,n_tag_key,n_imagen FROM ic_noticias 
	      WHERE 1=1 ".$where." ";
	$result=$db->Execute($sql);
	
?>
<script>
	$(function () {
		$( "#fecha_fil" ).datepicker({ 
			changeMonth: true,
			changeYear: true, 
			dateFormat: "yy-mm-dd", 
			onSelect: function(dateText, inst) { 
			}
		});
	});
</script>
<table border="0"  width="800" align="center">
<form action="" method="post">
  <tr align="center">
    <td colspan="6">
    <img src="images/admin/noticias.png" border="0"/>  Novedades y Publicaciones
    </td>
  </tr>
  <tr>
  	<td colspan="6">&nbsp;</td>
  </tr>
  <tr align="center">
    <td><strong>Palabra clave</strong></td>
    <td><input name="palabra_fil" type="text" class="form-control" id="palabra_fil" value="<?=$palabra_fil?>" /></td>
    <td>&nbsp;</td>
    <td><strong>Fecha</strong></td>
    <td>
    <input name="fecha_fil" type="text" class="form-control" id="fecha_fil" value="<?=$fecha_fil?>" size="10" /> 	    
    </td>
    <td><input type="submit" name="Submit2" value="Buscar" /></td>
  </tr>
 </form>
</table>

<table width="1100" border="0" align="center">
  <tr>
  	<td colspan="5"><div style="margin:20px 0; width:100%; border-top:1px solid #494949;"></div></td>
  </tr>
  <tr>
    <td colspan="5" align="right"><a href="admin.php?mod=1&funcion=nuevo">[ Crear Novedad ]</a></td>
  </tr>
  <tr>
    <th>Fecha</th>
    <th>Titulo</th>
    <th>Previa</th>
    <th>Imagen</th>
    <th>Opción</th>
  </tr>
<?php
	while(!$result->EOF){
		list($id,$titulo,$previa,$full,$fecha,$categoria,$n_lang,$n_tag_titulo,$n_tag_desc,$n_tag_key,$n_imagen)=select_format($result->fields);
?>

  <tr>
    <td align="center" valign="top" nowrap="nowrap" bgcolor="#FFFFFF"><?=$fecha?></td>
    <td valign="top" width="200" bgcolor="#FFFFFF"><?=$titulo?></td>
    <td valign="top" bgcolor="#FFFFFF"><?=$previa?></td>
    <td valign="top" bgcolor="#FFFFFF"><img src="<?=$n_imagen?>" width="100" alt="" border="0" /></td>
    <td align="center" valign="top" bgcolor="#FFFFFF"><a href="admin.php?mod=1&n_id=<?=$id?>">[ Editar ]</a></td>
  </tr>

<?php	
		$result->MoveNext();
	}
?>
</table>

<?php
}else{
?>
	<script language="javascript" type="text/javascript">
		incluir_editor("descripcion_previa,full");
	</script>
<form action="" method="post">
<table border="0"  width="600" align="center">
  <tr align="center">
    <td colspan="6">
    <img src="images/admin/noticias.png" border="0"/>  Novedades y Publicaciones
    </td>
  </tr>
  <tr>
  	<td colspan="6">&nbsp;</td>
  </tr>
   <tr>
    <td>
      <select name="n_id" class="form-control">
      <?php cargar_lista("ic_noticias","n_id,l_lang,n_titulo","n_titulo","1","","",$db); ?>
      </select>
     </td>
    <td><strong>
      <input type="submit" name="Submit" value="Edit">
    </strong></td>
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

<?php
if ($n_id != "")
{
	$sql="SELECT n_id,n_titulo,n_des,n_text,n_fecha,n_categoria,l_lang,n_tag_titulo,n_tag_desc,n_tag_key,n_imagen FROM ic_noticias WHERE n_id='".$n_id."' ";
	$result=$db->Execute($sql);
		
	list($id,$titulo,$previa,$full,$fecha,$categoria,$n_lang,$n_tag_titulo,$n_tag_desc,$n_tag_key,$n_imagen)=select_format($result->fields);
}
?>
<script>
	$(function () {
		$( "#fecha" ).datepicker({ 
			changeMonth: true,
			changeYear: true, 
			dateFormat: "yy-mm-dd", 
			onSelect: function(dateText, inst) { 
			}
		});
	});
</script>

	<table  border="0" width="800" align="center">
	<form action="" name="guardar" method="post" enctype="multipart/form-data">
	<input type="hidden" name="funcion" value="guardar">
	<input type="hidden" name="id" value="<?=$id?>">
	<tr>
  		<td colspan="5"><div style="margin:20px 0; width:100%; border-top:1px solid #494949;"></div></td>
  	</tr>
  	<tr>
	   <td valign="top"><a href="admin.php?mod=1">Volver al listado</a></td>
	   <td><div align="right"><a href="admin.php?mod=1&amp;funcion=nuevo">Cancelar/Nuevo</a></div></td>
	 </tr>
	 <tr>
	   <td valign="top">&nbsp;</td>
	   <td><div align="right"><font color="#FF0000" size="1">(*)Campos requeridos</font></div></td>
      </tr>
	 <tr>
		<td valign="top"><div align="right"><strong>Creation Date</strong></div></td>
		<td valign="top">
          <input type="text" class="form-control" name="fecha" id="fecha" value="<? if ($fecha==""){ echo date('Y-m-d'); } else { echo $fecha; }?>" /></td>
	  </tr>
	  <tr>
		<td valign="top"><div align="right"><strong>language</strong></div></td>
		<td>
		<strong>
		  <select name="n_lang" class="form-control" id="n_lang" <?=$activo_campo_idioma?> >
			<?php cargar_lista("ic_language","lang_prefijo,lang_descripcion","lang_id","0",$n_lang,"",$db); ?>
		  </select>
		</strong>		</td>
  	  </tr>
	  <tr>
		<td valign="top"><div align="right"><strong>Title</strong>(*)</div></td>
		<td><input type="text" class="form-control" name="titulo" id="titulo" value="<?=$titulo ?>"></td>
	  </tr>
	  <tr>
		<td valign="top"><div align="right"><strong>Categorie</strong>(*)</div></td>
		<td>
		<select name="categoria" class="form-control" id="categoria">
			<?php cargar_lista("ic_categoria","cat_id,cat_titulo","cat_titulo","0",$categoria," WHERE id_tipo='1'",$db); ?>
		</select>	  </td>
	  </tr>
	  <tr>
	    <td valign="top"><div align="right"><strong>Image</strong></div></td>
	    <td><table border="0" cellspacing="0" cellpadding="0" align="left">
	      <tr>
	        <td><input type="file" class="form-control" name="n_imagen" id="n_imagen" /></td>
	        <td>&nbsp;</td>
	        <td><input type="checkbox" class="form-control" name="borrarimg" id="borrarimg" value="S" /></td>
	        <td><em>Delete Image</em></td>
          </tr>
	      </table>
	      <img src="<?=($n_imagen=="")?"images/spacer.gif":$n_imagen?>" width="50" height="50" alt="" border="0" /></td>
      </tr>
	  <tr>
		<td valign="top"><div align="right">
		  <p><strong>Preview description</strong></p>
		</div></td>
		<td><textarea name="previa" class="form-control" style="width: 458px; height: 100px;" id="descripcion_previa" ><?= $previa?></textarea></td>
	  </tr>
	  <tr>
		<td valign="top"><div align="right">
		  <p><strong>Full Text</strong></p>
		</div></td>
		<td><textarea name="full" class="form-control" style="width: 458px; height: 100px;" id="full"><?=$full?></textarea></td>
	  </tr>
	  <tr>
    <td colspan="2" align="right" valign="top"><div align="left"> <strong><a href="javascript:;" onclick="mostrarAdicional();">Adition Information [+]</a></strong></div></td>
  </tr>
	  <tr>
	    <td colspan="2" valign="top">
        
        <table  border="0" cellpadding="2" cellspacing="2" id="tabla_adicional" style="display:none">
    <tr>
      <td colspan="2" valign="top"><b>T&iacute;tulo de la p&aacute;gina</b></td>
    </tr>
    <tr>
      <td colspan="2" valign="top"><textarea name="n_tag_titulo"  class="form-control" id="n_tag_titulo"><?= $n_tag_titulo?></textarea></td>
    </tr>
    <tr>
      <td colspan="2" align="right" valign="top"><div align="left"> <strong>Descripci&oacute;n de la P&aacute;gina</strong></div></td>
    </tr>
    <tr>
      <td colspan="2" valign="top"><textarea name="n_tag_desc"  class="form-control" id="n_tag_desc"><?= $n_tag_desc?></textarea></td>
    </tr>
    <tr>
      <td colspan="2" align="right" valign="top"><div align="left"> <strong>KeyWords</strong>(*) </div></td>
    </tr>
    <tr>
      <td colspan="2" valign="top"><textarea name="n_tag_key" class="form-control" id="n_tag_key"><?= $n_tag_key?></textarea></td>
    </tr>
</table>
        
        </td>
      </tr>
	  <tr>
		<td valign="top">&nbsp;</td>
		<td>&nbsp;</td>
	  </tr>
	  <tr>
		<td valign="top">&nbsp;</td>
		<td align="right">
		<input type="button" name="Enviar" value="Save News" onClick="enviar('titulo,categoria');" >
	  </form>
		<p>
		<form action="" method="post" name="eliminar">
		<input type="submit" name="Borrar" id="borrar" value="Borrar Noticia">
		<input type="hidden" name="id" value="<?=$id?>">
		<input type="hidden" name="funcion" value="borrar">
		</form>
		</p>
		</td>
	  </tr>
	</table>
<?php

}

?>