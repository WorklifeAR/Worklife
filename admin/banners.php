<?php
session_permiso("117");

$variables_metodo = variables_metodo("id_banner,url_banner,nombre_banner,funcion,publicacion,link,l_lang,descripcion");

$id_banner= 		$variables_metodo[0];
$url_banner= 		$variables_metodo[1];
$nombre_banner= 	$variables_metodo[2];
$funcion= 			$variables_metodo[3];
$publicacion= 		$variables_metodo[4];
$link= 				$variables_metodo[5];
$l_lang= 			$variables_metodo[6];
$descripcion= 		$variables_metodo[7];

$tamano[0] = 0;
$tamano[1] = 0;
$imagen_previa = "";

if ($funcion == "guardar"){
	if($id_banner==""){
	/*Funcion para guardar los datos del formulario*/	
		guardar($url_banner,$nombre_banner,$publicacion,$link,$l_lang,$descripcion);
	}elseif($id_banner!=""){
	/*Funcion para modificar los datos del formulario*/	
		modificar($id_banner,$url_banner,$nombre_banner,$publicacion,$link,$l_lang,$descripcion);
	}		
}elseif(($funcion == "borrar")&&($id_banner!="")){
	borrar($id_banner);
}


/****************************************************************************************************************************/
function borrar($id_banner)
{
	$path = "adodb/adodb.inc.php";
	include "var.php";
	include "conexion.php";
		
	//Elimino los archivos fisicos
	$result=$db->Execute("SELECT nombre_banner,url_banner FROM ic_banners WHERE id_banner = '".$id_banner."' ");
	list($nombre_banner,$url)=select_format($result->fields);
	
	//funcion para registrar la modificacion hecha por el usuario
	registrar_cambios($db, date('Y-m-d'), $_SESSION['sess_nombre'], '117', $id_banner, $nombre_banner, 'ELIMINAR');
	
	unlink($url);
	
	$result=$db->Execute("DELETE FROM ic_banners WHERE id_banner = '".$id_banner."' ");
	
	if ($result != false) $mensaje = "3";
	else $mensaje  = "0";
	
	echo"<META HTTP-EQUIV='Refresh' CONTENT='0;URL=admin.php?mod=117&mensaje=".$mensaje."'>";
	die();
}
/****************************************************************************************************************************/
function guardar($url_banner,$nombre_banner,$publicacion,$link,$l_lang,$descripcion)
{
	$path = "adodb/adodb.inc.php";
	include "var.php";
	include "conexion.php";
	
	$imagen_cargada="";
	
	if(!empty($_FILES['url_banner']['name'])){

		$dir="images/banners/";
		$url = $_FILES['url_banner']['tmp_name'];
		$archivo_nombre = $_FILES['url_banner']['name']; 
		
		if(!subir_imagen($archivo_nombre,$url,$dir)){
			echo"<META HTTP-EQUIV='Refresh' CONTENT='0;URL=admin.php?mod=117&mensaje=0'>";
			die();
		}
		
		$imagen_cargada=$dir.$archivo_nombre;
	}else{
		$imagen_cargada=$url_banner;
	}
	
	$result=insert_bd_format("url_banner,nombre_banner,publicacion_banner,link_banner,l_lang,descripcion", "ic_banners", array($imagen_cargada,$nombre_banner,$publicacion,$link,$l_lang,$descripcion), $db);

	if ($result != false) $mensaje = "1";
	else $mensaje  = "0";
	
	//funcion para registrar la modificacion hecha por el usuario
	registrar_cambios($db, date('Y-m-d'), $_SESSION['sess_nombre'], '117', '0', $nombre_banner, 'GUARDAR');
	
	echo"<META HTTP-EQUIV='Refresh' CONTENT='0;URL=admin.php?mod=117&mensaje=".$mensaje."'>";
	die();	
}
/****************************************************************************************************************************/
function modificar($id_banner,$url_banner,$nombre_banner,$publicacion,$link,$l_lang,$descripcion)
{
	$path = "adodb/adodb.inc.php";
	include "var.php";
	include "conexion.php";
	
	$imagen_cargada="";
	$imagen_cargada2="";
	
	if(!empty($_FILES['url_banner']['name'])){

		$dir="images/banners/";
		$url = $_FILES['url_banner']['tmp_name'];
		$archivo_nombre = $_FILES['url_banner']['name']; 
		
		if(!subir_imagen($archivo_nombre,$url,$dir)){
			echo"<META HTTP-EQUIV='Refresh' CONTENT='0;URL=admin.php?mod=117&mensaje=0'>";
			die();
		}
		
		$imagen_cargada="url_banner";
		$imagen_cargada2=$dir.$archivo_nombre;
	}else{
		if($url_banner!=""){
			$imagen_cargada="url_banner";
			$imagen_cargada2=$url_banner;
		}
	}
	
	$result=update_bd_format(array("nombre_banner","publicacion_banner","link_banner","l_lang",$imagen_cargada,"descripcion"), "ic_banners", array($nombre_banner,$publicacion,$link,$l_lang,$imagen_cargada2,$descripcion), "WHERE id_banner='".$id_banner."'", $db);
	
	if ($result != false) $mensaje = "2";
	else $mensaje  = "0";
	
	//funcion para registrar la modificacion hecha por el usuario
	registrar_cambios($db, date('Y-m-d'), $_SESSION['sess_nombre'], '117', $id_banner, $nombre_banner, 'MODIFICAR');
	
	echo"<META HTTP-EQUIV='Refresh' CONTENT='0;URL=admin.php?mod=117&mensaje=".$mensaje."'>";
	die();
}
/****************************************************************************************************************************/
?>

<form action="" name="modificar" method="post">
<table border="0" align="center" width="600">
  <tr align="center">
    <td colspan="2">
    <img src="images/admin/banners.png" border="0"/> Banners y publicidad
    </td>
  </tr>
  <tr>
  	<td>&nbsp;</td>
  </tr>
   <tr>
    <td>
	  <select name="id_banner" id="id_banner" class="form-control">
		<?php cargar_lista("ic_banners","id_banner,nombre_banner,publicacion_banner","nombre_banner","1","","",$db); ?>
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

function mostrarCargar(modo){
	if(modo=="cargar"){
		document.getElementById("url_banner_2").style.display="none";
		document.getElementById("url_banner").style.display="inline";
		
		document.getElementById("url_banner").name="url_banner";
		document.getElementById("url_banner_2").name="url_banner_3";
	}else{
		document.getElementById("url_banner").style.display="none";
		document.getElementById("url_banner_2").style.display="inline";
		
		document.getElementById("url_banner_2").name="url_banner";
		document.getElementById("url_banner").name="url_banner_3";
	}
}
</script>

<table  border="0" align="center" width="800">
<form action="" name="guardar" method="post" enctype="multipart/form-data">
<input type="hidden" name="funcion" value="guardar">

<?php
if ($id_banner != ""){
	$sql="SELECT id_banner, url_banner, nombre_banner, publicacion_banner, link_banner, l_lang, descripcion
		  FROM ic_banners
		  WHERE id_banner = '".$id_banner."' ";
	$result=$db->Execute($sql);
		
	list($id_banner,$url_banner,$nombre_banner,$publicacion,$link,$l_lang,$descripcion)=select_format($result->fields);
		
	echo '<input type="hidden" name="id_banner" value="'.$id_banner.'">';
	
	$archivo = basename($url_banner);
	$archivo = explode(".", $archivo);
	
	$tamano = getimagesize($url_banner);
		
	if(strtoupper($archivo[(count($archivo)-1)])!="SWF"){
		$imagen_previa = '<img src="'.$url_banner.'" border="0" alt="'.$nombre_banner.'" width="120" height="120" />';
	}else{
		$imagen_previa = '<div align="center">
				<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0" width="120" height="120">
					<param name="movie" value="'.$url_banner.'" />
					<param name="quality" value="high" />
					<embed src="'.$url_banner.'" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="120" height="120"></embed>
				</object>
			  </div>';
	}
}
?>
<tr>
  	<td colspan="2"><div style="margin:20px 0; width:100%; border-top:1px solid #494949;"></div></td>
  </tr>
 <tr>
   <td valign="top" width="100">&nbsp;</td>
   <td><div align="right"><a href="admin.php?mod=117">Cancelar/Nuevo</a></div></td>
 </tr>
 <tr>
   <td valign="top"><div align="right"></div></td>
   <td><div align="right"><font color="#FF0000" size="1">(*)Campos requeridos</font></div></td>
 </tr>
  <tr>
    <td valign="top"><div align="right"><strong>Name</strong>(*)</div></td>
    <td><input  name="nombre_banner" type="text" id="nombre_banner" value="<?=$nombre_banner?>" class="form-control" />    </td>
  </tr>
  <tr>
    <td valign="top"><div align="right"><strong>Language</strong></div></td>
    <td><strong>
      <select name="l_lang" id="l_lang" <?=$activo_campo_idioma?>  class="form-control">
        <?php cargar_lista("ic_language","lang_prefijo,lang_descripcion","lang_id","0",$l_lang,"",$db); ?>
      </select>
    </strong> </td>
  </tr>
  <tr>
    <td valign="top"><div align="right"><strong>Publication</strong>(*)</div></td>
    <td valign="top">
    <select name="publicacion" id="publicacion"  class="form-control">
	  <?php cargar_lista_estatica("BANNER-HOME,BANNER-INFERIOR,BANNER-USUARIOS","BANNER-HOME,BANNER-INFERIOR,BANNER-USUARIOS",1,$publicacion); ?>
    </select>
  </tr>
  <tr>
    <td valign="top"><div align="right"><strong>Link</strong></div></td>
    <td valign="top"><input  name="link" type="text" id="link" value="<?=$link?>"  class="form-control" /></td>
  </tr>
  <tr>
    <td valign="top"><div align="right"><strong>File Banner</strong></div>
      <div align="right">
        <table width="0%" border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td><div align="right"><font size="1">
                <label for="modo_1">Cargar Archivo</label>
            </font></div></td>
            <td><div align="right">
                <input name="modo" type="radio" id="modo_1" value="cargar" checked="checked" onclick="mostrarCargar('cargar');"  class="form-control"/>
            </div></td>
          </tr>
          <tr>
            <td><div align="right"><font size="1">
                <label for="modo_2">Buscar Archivo</label>
            </font></div></td>
            <td><div align="right">
                <input name="modo" type="radio" id="modo_2" value="buscar"  class="form-control" onclick="mostrarCargar('buscar');"/>
            </div></td>
          </tr>
        </table>
      </div></td>
    <td valign="top"><table border="0" cellspacing="0" cellpadding="0" align="left">
      <tr>
        <td colspan="2" valign="top"><input type="file" name="url_banner" id="url_banner"  class="form-control"/>
		<select name="url_banner_2" id="url_banner_2" style="display:none" class="form-control">
<?php
	$directorio = dir("images/banners/");
	
	while($archivo = $directorio->read())
	{
		$selected = "";
		
		if($archivo!="." && $archivo!=".."){
			if(!is_dir("images/banners/".$archivo)){
			
				if($url_video == "images/banners/".$archivo){
					$selected = "selected=\"selected\"";
				}
				
				echo "<option value=\"images/banners/".$archivo."\" ".$selected .">".$archivo."</option>";
			}		
		}
	}
	$directorio->close();
?>
		</select>
		</td>
        </tr>
      <tr>
        <td><?=$imagen_previa?></td>
        <td valign="top" nowrap="nowrap">
		<font size="1"><em>Imagen previa Reducida</em></font><br />
        <font size="1">Tama&ntilde;o original (<?=$tamano[0]?>x<?=$tamano[1]?>)</font><br />
        <a href="<?=$url_banner?>" target="_blank"><font size="1">View Full Image</font></a>		</td>
        </tr>
    </table>	</td>
  </tr>
  <tr>
    <td valign="top"><div align="right"><strong>Description</strong></div></td>
    <td><textarea name="descripcion" id="descripcion" class="form-control"><?=$descripcion?></textarea>
  <tr>
    <td valign="top">&nbsp;</td>
    <td align="right">
	<input type="button" onClick="enviar('nombre_banner,publicacion');" name="Enviar" value="Save Banner">
  </form><br /><br />

    <form action="" method="post" name="eliminar">
	<input type="submit" name="Borrar" id="borrar" value="Borrar Banner">
	<input type="hidden" name="id_banner" value="<?=$id_banner?>">
	<input type="hidden" name="funcion" value="borrar">
	</form>
	</td>
  </tr>
</table>
