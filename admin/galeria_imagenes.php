<?php

session_permiso("119");

$variables_metodo = variables_metodo("funcion,id_imagen,fecha_creacion,nombre_imagen,id_categoria,imagen,img_principal,visitas_imagen,palabra_fil,categoria_fil,link");

$funcion= 			$variables_metodo[0];
$id_imagen= 		$variables_metodo[1];
$fecha_creacion= 	$variables_metodo[2];
$nombre_imagen= 	$variables_metodo[3];
$id_categoria= 		$variables_metodo[4];
$imagen= 			$variables_metodo[5];
$img_principal= 	$variables_metodo[6];
$visitas_imagen= 	$variables_metodo[7];
$palabra_fil= 		$variables_metodo[8];
$categoria_fil= 	$variables_metodo[9];
$link= 				$variables_metodo[10];

define('_TAMX1',400);
define('_TAMY1',400);

define('_TAMX2',500);
define('_TAMY2',500);

if ($funcion == "guardar"){
	if($id_imagen==""){
	/*Funcion para guardar los datos del formulario*/	
		guardar($fecha_creacion,$nombre_imagen,$id_categoria,$imagen,$img_principal,$visitas_imagen,$link);
	}elseif($id_imagen!=""){
	/*Funcion para modificar los datos del formulario*/	
		modificar($id_imagen,$fecha_creacion,$nombre_imagen,$id_categoria,$imagen,$img_principal,$link);
	}	
}elseif(($funcion == "borrar")&&($id_imagen!="")){
	borrar($id_imagen);
}

/****************************************************************************************************************************/

function borrar($id)
{
	$path = "adodb/adodb.inc.php";
	include "var.php";
	include "conexion.php";
	
	//Consulto las imagenes creadas para eliminar los archivos fisicos
	$result=$db->Execute("SELECT img_previa,img_original,img_full,nombre_imagen FROM ic_galeria_imagenes WHERE id_imagen = '".$id."' ");
	list($img_previa,$img_original,$img_full,$nombre_imagen)=select_format($result->fields);
	
	unlink ($img_previa);
	unlink ($img_original);
	unlink ($img_full);
	
	//funcion para registrar la modificacion hecha por el usuario
	registrar_cambios($db, date('Y-m-d'), $_SESSION['sess_nombre'], '119', $id, $nombre_imagen, 'ELIMINAR');
	
	$result=$db->Execute("DELETE FROM ic_galeria_imagenes WHERE id_imagen = '".$id."' ");
	
	if ($result != false) $mensaje = "3";
	else $mensaje  = "0";
		
	echo"<META HTTP-EQUIV='Refresh' CONTENT='0;URL=admin.php?mod=119&mensaje=".$mensaje."'>";
	die();
}
/****************************************************************************************************************************/
function guardar($fecha_creacion,$nombre_imagen,$id_categoria,$imagen,$img_principal,$link)
{
	$path = "adodb/adodb.inc.php";
	include "var.php";
	include "conexion.php";
	
	$dir = "images/galeria/";
	$dir_previas = "images/galeria/previas/";
	$dir_originales = "images/galeria/originales/";
	
	//---------------------------------------------------------------------
	
	$url_full = $_FILES['imagen']['tmp_name'];
	$imagen_full = $_FILES['imagen']['name']; 
	$img_full = "";	
	$img_previa = "";	
	$img_original = "";	
	
	if($url_full!="" && $imagen_full!=""){
		$alt = mt_rand();
				
		if(subir_imagen($alt.$imagen_full, $url_full, $dir))
		{
			//Genero una copia original
			copy($dir.$alt.$imagen_full, $dir_originales.$imagen_full);
			$img_original = "".$dir_originales.$imagen_full."";
			
			$tam = getimagesize($dir_originales.$imagen_full);
			$tamanox = _TAMX1;
			$tamanoy = _TAMY1;
			
			tamano($dir, $alt.$imagen_full, "1_", $tamanox, $tamanoy);
			
			//Genero la imagen previa
			copy($dir."1_".$alt.$imagen_full, $dir_previas.$alt."_".$imagen_full);
			tamano($dir_previas, $alt."_".$imagen_full, "2_", _TAMX2, _TAMY2);
			
			$img_previa = "".$dir_previas."2_".$alt."_".$imagen_full."";
			$img_full = "".$dir."1_".$alt.$imagen_full."";
			
		}else{
			echo"<META HTTP-EQUIV='Refresh' CONTENT='1;URL=admin.php?mod=119&mensaje=0'>";
			die("Error Cargando la Imagen");
		}
	}
	//---------------------------------------------------------------------

	if($img_principal=="S"){
		$result=update_bd_format(array("img_principal"), "ic_galeria_imagenes", array("N"), "  WHERE id_categoria='".$id_categoria."'", $db);
	}
	
	$id_categoria1="";
	
	for($i=0; $i<count($id_categoria); $i++){
		$id_categoria1 .= "*".$id_categoria[$i]."*";
	}
	
	$result=insert_bd_format("fecha_creacion,nombre_imagen,id_categoria,img_previa,img_full,img_original,img_principal,link", 
							 "ic_galeria_imagenes", 
							 array($fecha_creacion,$nombre_imagen,$id_categoria1,$img_previa,$img_full,$img_original,$img_principal,$link), 
							 $db);
	
	if ($result != false) $mensaje = "1";
	else $mensaje  = "0";
	
	//funcion para registrar la modificacion hecha por el usuario
	registrar_cambios($db, date('Y-m-d'), $_SESSION['sess_nombre'], '119', '0', $nombre_imagen, 'GUARDAR');
	
	implantar_urls($db);
	
	echo"<META HTTP-EQUIV='Refresh' CONTENT='0;URL=admin.php?mod=119&mensaje=".$mensaje."'>";
	die();	
}

/****************************************************************************************************************************/

function modificar($id_imagen,$fecha_creacion,$nombre_imagen,$id_categoria,$imagen,$img_principal,$link)
{
	$path = "adodb/adodb.inc.php";
	include "var.php";
	include "conexion.php";
	
	$dir = "images/galeria/";
	$dir_previas = "images/galeria/previas/";
	$dir_originales = "images/galeria/originales/";
	
	$dir = "images/galeria/";
	$dir_previas = "images/galeria/previas/";
	$dir_originales = "images/galeria/originales/";
	
	//---------------------------------------------------------------------
	
	$url_full = $_FILES['imagen']['tmp_name'];
	$imagen_full = $_FILES['imagen']['name']; 
	$img_full = "";	
	$img_full2 = "";	
	$img_previa = "";
	$img_previa2 = "";	
	$img_original = "";	
	$img_original2 = "";	
	
	if($url_full!="" && $imagen_full!=""){
		$alt = mt_rand();
				
		if(subir_imagen($alt.$imagen_full, $url_full, $dir))
		{
			//Genero una copia original
			copy($dir.$alt.$imagen_full, $dir_originales.$imagen_full);
			$img_original = "img_original";
			$img_original2 = $dir_originales.$imagen_full;
			
			//---------------------------------------------------------------------------
			
			//Consulto las imagenes creadas para eliminar los archivos fisicos
			$result_del=$db->Execute("SELECT img_previa,img_original,img_full FROM ic_galeria_imagenes WHERE id_imagen = '".$id_imagen."' ");
			list($img_previa_del,$img_original_del,$img_full_del)=select_format($result_del->fields);
			
			unlink ($img_previa_del);
			unlink ($img_original_del);
			unlink ($img_full_del);
			
			//---------------------------------------------------------------------------
			
			$tam = getimagesize($dir_originales.$imagen_full);
			$tamanox = _TAMX1;
			$tamanoy = _TAMY1;
			
			tamano($dir, $alt.$imagen_full, "1_", $tamanox, $tamanoy);
			
			//Genero la imagen previa
			copy($dir."1_".$alt.$imagen_full, $dir_previas.$alt."_".$imagen_full);
			tamano($dir_previas, $alt."_".$imagen_full, "2_", _TAMX2, _TAMY2);
			
			$img_previa = "img_previa";
			$img_previa2=$dir_previas."2_".$alt."_".$imagen_full;
			$img_full = "img_full";
			$img_full2=$dir."1_".$alt.$imagen_full;
			
		}else{
			echo"<META HTTP-EQUIV='Refresh' CONTENT='1;URL=admin.php?mod=119&mensaje=0'>";
			die("Error Cargando la Imagen");
		}
	}
	//---------------------------------------------------------------------
	
	if($img_principal=="S"){
		$result=update_bd_format(array("img_principal"), "ic_galeria_imagenes", array("N"), " WHERE id_categoria='".$id_categoria."'", $db);
	}
	
	$id_categoria1="";
	
	for($i=0; $i<count($id_categoria); $i++){
		$id_categoria1 .= "*".$id_categoria[$i]."*";
	}
	
	$result=update_bd_format(array("nombre_imagen","fecha_creacion","img_principal","id_categoria","link",$img_full,$img_previa,$img_original), 
						     "ic_galeria_imagenes", 
							 array($nombre_imagen,$fecha_creacion,$img_principal,$id_categoria1,$link,$img_full2,$img_previa2,$img_original2), 
							 "WHERE id_imagen='".$id_imagen."'", $db);
	
	if ($result != false) $mensaje = "2";
	else $mensaje  = "0";
	
	//funcion para registrar la modificacion hecha por el usuario
	registrar_cambios($db, date('Y-m-d'), $_SESSION['sess_nombre'], '119', $id_imagen, $nombre_imagen, 'MODIFICAR');	
	
	implantar_urls($db);
	
	echo"<META HTTP-EQUIV='Refresh' CONTENT='0;URL=admin.php?mod=119&mensaje=".$mensaje."&id_imagen=".$id_imagen."'>";
	die();
}

/****************************************************************************************************************************/

if($id_imagen!="" || $funcion=="nuevo"){
?>

<form action="" name="modificar" method="post">
<table border="0" cellspacing="5" cellpadding="0" align="center">
  <tr align="center">
    <td colspan="3" class="titulo"><strong>Images Administration</strong></td>
  </tr>
   <tr>
    <td><strong>Edit Image</strong></td>
    <td>
	  <strong>
	  <select name="id_imagen">
		<?php cargar_lista("ic_galeria_imagenes","id_imagen,nombre_imagen","nombre_imagen","1","","",$db); ?>
      </select>
	  </strong></td>
    <td><strong>
      <input name="Submit" type="submit" value="Edit">
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
<table  border="0" align="center" cellpadding="0" cellspacing="5">
  <form action="" name="guardar" method="post" enctype="multipart/form-data">
    <input type="hidden" name="funcion" value="guardar" />
    <input type="hidden" name="fecha_creacion" value="<?=date('Y-m-d')?>" />
<?php
$img_previa="";
$img_full="";
$img_original="";
$id_inclusion="";

if ($id_imagen != ""){

	$sql="SELECT id_imagen,nombre_imagen,fecha_creacion,id_categoria,img_previa,img_full,img_original,img_principal,link FROM ic_galeria_imagenes 
	      WHERE id_imagen='".$id_imagen."' ";
	$result=$db->Execute($sql);
	
	list($id_imagen,$nombre_imagen,$fecha_creacion,$id_categoria,$img_previa,$img_full,$img_original,$img_principal,$link)=select_format($result->fields);
	
	for($y=0;$y<(4-strlen($id_imagen));$y++){
		$id_inclusion.="0";
	}
	
	$id_inclusion.=$id_imagen;
	
	echo '<input type="hidden" name="id_imagen" value="'.$id_imagen.'">';
}
?>
    <tr>
      <td valign="top"><a href="admin.php?mod=119">back to list</a></td>
      <td><div align="right"><a href="admin.php?mod=119&funcion=nuevo">Cancel/New</a></div></td>
    </tr>
    <tr>
      <td valign="top">&nbsp;</td>
      <td><div align="right"><font color="#FF0000" size="1">(*)Field required</font></div></td>
    </tr>
    <tr>
      <td valign="top"><div align="right"><strong>last modification</strong></div></td>
      <td><? if ($fecha_creacion==""){ echo date('Y-m-d'); } else { echo $fecha_creacion; }?></td>
    </tr>
    <tr>
      <td valign="top"><div align="right"><strong>Name</strong>(*) </div></td>
      <td><input type="text" name="nombre_imagen" id="nombre_imagen" value="<?=$nombre_imagen?>" /></td>
    </tr>
    <tr>
      <td valign="top"><div align="right"><strong>Category</strong>(*)</div></td>
      <td>
      <select name="id_categoria[]" id="id_categoria">
        <?php cargar_lista("ic_categoria","cat_id,cat_titulo","cat_titulo","1",$id_categoria," WHERE id_tipo='5'",$db); ?>
      </select>
      </td>
    </tr>
    <tr>
      <td valign="top"><div align="right"><strong>Main Image</strong></div></td>
      <td><select name="img_principal" id="img_principal" >
		<?php cargar_lista_estatica("N,S","No,Si",0,$img_principal); ?>
	</select> </td>
    </tr>
    <tr>
      <td valign="top"><div align="right"><strong>Image (*) </strong></div></td>
      <td><input type="file" name="imagen" id="imagen" /><br><small>Minimo 1024x768. Siempre formato horizontal</small></td>
    </tr>
    <tr>
      <td valign="top"><div align="right"><strong>Add to content</strong></div></td>
      <td valign="top">[!--{<?=$id_inclusion?>-imagen}--]<br />
      <i><font size="1">Codigo para incluir en contenido(Noticias, Paginas Estaticas)</font></i></td>
    </tr>
    <tr>
      <td valign="top"><div align="right"><strong>Link</strong></div></td>
      <td><input type="text" name="link" id="link" value="<?=$link?>" size="50" /></td>
    </tr>
    <tr>
      <td valign="top"><div align="right"><i>Original Image</i></div></td>
      <td><a href="<?=$img_original?>" target="_blank"><?=$img_original?></a></td>
    </tr>
    <tr>
      <td valign="top"><div align="right"><i>Full Image</i></div></td>
      <td><a href="<?=$img_full?>" target="_blank">
        <?=$img_full?>
      </a></td>
    </tr>
    <tr>
      <td valign="top"><div align="right"><i>Preview Image</i></div></td>
      <td><img src="<?=$img_previa?>" width="50" height="50" border="0" /></td>
    </tr>
    <tr>
      <td valign="top">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td valign="top">&nbsp;</td>
      <td><input name="Enviar" type="button" onclick="enviar('nombre_imagen');" value="Save Image" />
    </form>
	  <p>
	  <form action="" method="post" name="eliminar">
		<input type="submit" name="Borrar" value="Borrar Imagen" />
		<input type="hidden" name="id_imagen" value="<?=$id_imagen?>" />
		<input type="hidden" name="funcion" value="borrar" />
	  </form>
	  </p>
	  </td>
    </tr>
</table>
<?php
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//Listado con registros creados
}else{
?>
<div class="titulo" align="center"><strong>Create/Modify Image</strong></div>
<br />
<table border="0" align="center" cellpadding="3" cellspacing="0">
<form action="" method="post">
  <tr>
    <td>Keyword</td>
    <td><input name="palabra_fil" type="text" id="palabra_fil" value="<?=$palabra_fil?>" /></td>
    <td>&nbsp;</td>
    <td>Albums</td>
    <td>
    <select name="categoria_fil" id="categoria_fil" >
      <?php cargar_lista("ic_categoria","cat_id,cat_titulo","cat_titulo","1",$categoria_fil," WHERE id_tipo='5' ",$db); ?>
    </select>
    </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td align="right"><strong>
      <input type="submit" name="Submit2" value="Search" />
    </strong></td>
  </tr>
  </form>
</table>
<br />

<table width="100%" border="0" cellspacing="3" cellpadding="3">
  <tr>
    <th colspan="7" align="right" bgcolor="#FFFFFF"><a href="admin.php?mod=119&amp;funcion=nuevo">[ New Image]</a></th>
  </tr>
  <tr>
    <th bgcolor="#DDDDDD">Name</th>
    <th bgcolor="#DDDDDD">Date</th>
    <th bgcolor="#DDDDDD">URL Original</th>
    <th bgcolor="#DDDDDD">Code</th>
    <th bgcolor="#DDDDDD">Image</th>
    <th bgcolor="#DDDDDD">&nbsp;</th>
  </tr>
<?php

$where = "";

if($palabra_fil!=""){
	$where .= " AND (aa.nombre_imagen LIKE '%".$palabra_fil."%')";
}

if($categoria_fil!=""){
	$where .= " AND (aa.id_categoria = '".$categoria_fil."')";
}
	
$sql="SELECT aa.id_imagen,
			aa.nombre_imagen,
			aa.fecha_creacion,
			aa.img_previa,
			aa.img_full,
			aa.img_original,
			aa.img_principal
	      FROM ic_galeria_imagenes aa 
		  WHERE 1=1 ".$where." ORDER BY aa.nombre_imagen ASC ";
$result=$db->Execute($sql);
	
while(!$result->EOF){
	list($id,$titulo,$fecha,$img_previa,$img_full,$img_original,$principal)=select_format($result->fields);
	$id_inclusion = "";
	for($y=0;$y<(4-strlen($id));$y++){
		$id_inclusion.="0";
	}
	
	$id_inclusion.=$id;

?>
  <tr>
    <td bgcolor="#f4f4f4"><?=$titulo?></td>
    <td align="center" bgcolor="#f4f4f4"><?=$fecha?></td>
    <td bgcolor="#f4f4f4"><?=$img_original?></td>
    <td align="center" nowrap="nowrap" bgcolor="#f4f4f4">[!--{<?=$id_inclusion?>-imagen}--]</td>
    <td align="center" bgcolor="#f4f4f4"><img src="<?=$img_previa?>" width="60" border="0" hspace="5" /></td>
    <td align="center" nowrap="nowrap" bgcolor="#f4f4f4"><a href="admin.php?mod=119&id_imagen=<?=$id?>">[ Edit ]</a></td>
  </tr>
<?php
	$result->MoveNext();
}
?>
</table>

<?php	
}
?>