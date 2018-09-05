<?php
session_permiso("118");

$variables_metodo = variables_metodo("id,fecha,titulo,full,funcion,video_id,categoria,url_video,l_lang,img_previa,modo");

$id= 			$variables_metodo[0];
$fecha= 		$variables_metodo[1];
$titulo= 		$variables_metodo[2];
$full= 			$variables_metodo[3];
$funcion= 		$variables_metodo[4];
$video_id= 		$variables_metodo[5];
$categoria= 	$variables_metodo[6];
$url_video= 	$variables_metodo[7];
$l_lang= 		$variables_metodo[8];
$img_previa= 	$variables_metodo[9];
$modo= 			$variables_metodo[10];

if ($funcion == "guardar"){
	if($id==""){
	/*Funcion para guardar los datos del formulario*/	
		guardar($fecha,$titulo,$full,$categoria,$url_video,$l_lang,$img_previa,$modo);
	}elseif($id!=""){
	/*Funcion para modificar los datos del formulario*/	
		modificar($id,$fecha,$titulo,$full,$categoria,$url_video,$l_lang,$img_previa,$modo);
	}		
}elseif(($funcion == "borrar")&&($id!="")){
	borrar($id);
}

/****************************************************************************************************************************/

function ConectarFTP(){
	//Archivo de variables de conexion
	include "var.php";
	
	//Permite conectarse al Servidor FTP
	$id_ftp=@ftp_connect(@gethostbyname($var_server_ftp[1]),$var_port_ftp[2]); //Obtiene un manejador del Servidor FTP
	ftp_set_option($id_ftp, FTP_TIMEOUT_SEC, (int)600);
	
	ftp_login($id_ftp,$var_user_ftp[3],$var_pass_ftp[4]); //Se loguea al Servidor FTP
	ftp_pasv($id_ftp,$var_pasv_ftp[5]); //Establece el modo de conexi&oacute;n
	
	ftp_chdir($id_ftp, "/public/www/images/videos/");
	
	return $id_ftp; //Devuelve el manejador a la funci&oacute;n
}

/****************************************************************************************************************************/

function SubirArchivo($archivo_local,$archivo_remoto){
	//Sube archivo de la maquina Cliente al Servidor (Comando PUT)
	$id_ftp=ConectarFTP(); //Obtiene un manejador y se conecta al Servidor FTP
	
	//echo $archivo_remoto."<br>".$archivo_local."<br>".ftp_pwd($id_ftp);
	
	@ftp_put($id_ftp,$archivo_remoto,$archivo_local,1);
	//Sube un archivo al Servidor FTP en modo Binario
	ftp_quit($id_ftp); //Cierra la conexion FTP
} 

/****************************************************************************************************************************/

function borrar($id)
{
	$path = "adodb/adodb.inc.php";
	include "var.php";
	include "conexion.php";
	
	$sql="SELECT nombre_video FROM ic_videos WHERE id_video='".$id."' ";
	$result=$db->Execute($sql);
	
	list($titulo)=select_format($result->fields);

	//funcion para registrar la modificacion hecha por el usuario
	registrar_cambios($db, date('Y-m-d'), $_SESSION['sess_nombre'], '118', $id, $titulo, 'ELIMINAR');
	
	$result=$db->Execute("DELETE FROM ic_videos WHERE id_video = '".$id."' ");
	
	if ($result != false) $mensaje = "3";
	else $mensaje  = "0";
	
	echo"<META HTTP-EQUIV='Refresh' CONTENT='0;URL=admin.php?mod=118&mensaje=".$mensaje."'>";
	die();
}
/****************************************************************************************************************************/
function guardar($fecha,$titulo,$full,$categoria,$url_video,$l_lang,$img_previa,$modo)
{
	$path = "adodb/adodb.inc.php";
	include "var.php";
	include "conexion.php";
	
	if($l_lang==""){
		$l_lang=$_SESSION['idioma'];
	}
	
	echo "<p class='titulo' align='center'>Por favor espere un momento, el tiempo de carga es relativo al tama&ntilde;o de los archivos que se cargan..<br><br>Cargando Archivos...</p>";
	
	$dir="images/videos/";
	
	//-------------------------------------------------------------
	
	if (!empty($_FILES['img_previa']['name'])){
		$img_previa=$_FILES['img_previa'];
		
		if(subir_imagen($img_previa['name'], $img_previa['tmp_name'], $dir)){
			$img_previa = $dir.$img_previa['name'];	
		}
	}
	
	//-------------------------------------------------------------
	
	if($modo=="codigo"){
		$url_video = $url_video;
	}elseif($modo=="buscar"){
		$url_video = $url_video;
	}elseif($modo=="cargar"){
	
		if($ftp_activo){
			//Archivo del video
			if (!empty($_FILES['url_video']['name'])){
				$url_video=$_FILES['url_video'];
				SubirArchivo($url_video['tmp_name'], $dir.$url_video['name']);
				$url_video = $dir.$url_video['name'];	
			}
		}else{
			//Archivo del video
			if (!empty($_FILES['url_video']['name'])){
				$url_video=$_FILES['url_video'];
				
				if(subir_imagen($url_video['name'], $url_video['tmp_name'], $dir)){
					$url_video = $dir.$url_video['name'];	
				}
			}
		}
	}
	
	$result=insert_bd_format("l_lang,fecha_creacion,nombre_video,desc_video,img_previa_video,url_video,categoria_video,modo", "ic_videos", array($l_lang,$fecha,$titulo,$full,$img_previa,$url_video,$categoria,$modo), $db);

	if ($result != false) $mensaje = "1";
	else $mensaje  = "0";
	
	//funcion para registrar la modificacion hecha por el usuario
	registrar_cambios($db, date('Y-m-d'), $_SESSION['sess_nombre'], '118', '0', $titulo, 'GUARDAR');
	
	echo"<META HTTP-EQUIV='Refresh' CONTENT='0;URL=admin.php?mod=118&mensaje=".$mensaje."'>";
	die();	
}

/****************************************************************************************************************************/

function modificar($id,$fecha,$titulo,$full,$categoria,$url_video,$l_lang,$img_previa,$modo)
{
	$path = "adodb/adodb.inc.php";
	include "var.php";
	include "conexion.php";
	
	if($l_lang==""){
		$l_lang=$_SESSION['idioma'];
	}
	
	echo "<p class='titulo' align='center'>Por favor espere un momento, el tiempo de carga es relativo al tama&ntilde;o de los archivos que se cargan..<br><br>Cargando Archivos...</p>";
	
	$dir="images/videos/";
	
	//-------------------------------------------------------------
	
	$img_previa1="";
	$img_previa2="";
	
	if (!empty($_FILES['img_previa']['name'])){
		$img_previa=$_FILES['img_previa'];
		
		if(subir_imagen($img_previa['name'], $img_previa['tmp_name'], $dir)){
			$img_previa1 = "img_previa_video";	
			$img_previa2 = $dir.$img_previa['name'];	
		}
	}
	
	//-------------------------------------------------------------

	$url_video1="";
	$url_video2="";
	
	if($modo=="codigo"){
		$url_video1 = "url_video";
		$url_video2 = $url_video;		
	}elseif($modo=="buscar"){
		$url_video1 = "url_video";
		$url_video2 = $url_video;
	}elseif($modo=="cargar"){
	
		if($ftp_activo){
			//Archivo del video
			if (!empty($_FILES['url_video']['name'])){
				$url_video=$_FILES['url_video'];
				
				SubirArchivo($url_video['name'], $url_video['tmp_name']);
				$url_video1 = "url_video";	
				$url_video2 = $dir.$url_video['name'];	
			}
		}else{
			//Archivo del video
			if (!empty($_FILES['url_video']['name'])){
				$url_video=$_FILES['url_video'];
				
				if(subir_imagen($url_video['name'], $url_video['tmp_name'], $dir)){
					$url_video1 = "url_video";
					$url_video2 = $dir.$url_video['name'];	
				}
			}
		}
	}
	
	$result=update_bd_format(array("l_lang","fecha_creacion","nombre_video","desc_video","categoria_video","modo",$img_previa1,$url_video1), "ic_videos", array($l_lang,$fecha,$titulo,$full,$categoria,$modo,$img_previa2,$url_video2), "WHERE id_video='".$id."'", $db);
	
	if ($result != false) $mensaje = "2";
	else $mensaje  = "0";
	
	//funcion para registrar la modificacion hecha por el usuario
	registrar_cambios($db, date('Y-m-d'), $_SESSION['sess_nombre'], '118', $id, $titulo, 'MODIFICAR');
	
	echo"<META HTTP-EQUIV='Refresh' CONTENT='0;URL=admin.php?mod=118&mensaje=".$mensaje."'>";
	die();
}
/****************************************************************************************************************************/
?>
<script language="javascript" type="text/javascript">
	incluir_editor("texto_completo");
</script>


<form action="" name="modificar" method="post">
<table border="0" cellspacing="5" cellpadding="0" align="center">
  <tr align="center">
    <td colspan="3" class="titulo"><strong>Create/Modify Videos</strong></td>
  </tr>
   <tr>
    <td><strong>Edit Video </strong></td>
    <td>
	  <strong>
	  <select name="id">
		<?php cargar_lista("ic_videos","id_video,nombre_video","nombre_video","1","","",$db); ?>
      </select>
	  </strong></td>
    <td><strong>
      <input type="submit" name="Submit" value="Edit">
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

function mostrarCargar(modo){
	if(modo=="cargar"){
		document.getElementById("url_video_2").style.display="none";
		document.getElementById("url_video_3").style.display="none";
		document.getElementById("url_video").style.display="inline";
		
		document.getElementById("url_video").name="url_video";
		document.getElementById("url_video_2").name="url_video_21";
		document.getElementById("url_video_3").name="url_video_31";
	}else if(modo=="codigo"){
		document.getElementById("url_video").style.display="none";
		document.getElementById("url_video_2").style.display="none";
		document.getElementById("url_video_3").style.display="inline";
		
		document.getElementById("url_video").name="url_video_1";
		document.getElementById("url_video_2").name="url_video_21";
		document.getElementById("url_video_3").name="url_video";
		
	}else{
		document.getElementById("url_video").style.display="none";
		document.getElementById("url_video_2").style.display="inline";
		document.getElementById("url_video_3").style.display="none";
		
		document.getElementById("url_video").name="url_video_1";
		document.getElementById("url_video_2").name="url_video";
		document.getElementById("url_video_3").name="url_video_31";
	}
}
</script>


<table  border="0" align="center" cellpadding="0" cellspacing="5">
<form action="admin.php?mod=118" name="guardar" method="post" enctype="multipart/form-data">
<input type="hidden" name="funcion" value="guardar">
<input type="hidden" name="fecha" value="<?=date('Y-m-d')?>">
<?php
$modo="";
$id_inclusion="0000";

if ($id != "")
{
	$sql="SELECT id_video,l_lang,fecha_creacion,nombre_video,desc_video,
                 img_previa_video,url_video,categoria_video,modo
          FROM ic_videos WHERE id_video='".$id."' ";
	$result=$db->Execute($sql);
	
	list($id,$l_lang,$fecha,$titulo,$full,$img_previa,$url_video,$categoria,$modo)=select_format($result->fields);
	
	$formato_id=$id;
	echo '<input type="hidden" name="id" value="'.$id.'">';
	
	$id_inclusion="";
	
	for($y=0;$y<(4-strlen($formato_id));$y++){
		$id_inclusion.="0";
	}
	
	$id_inclusion.=$formato_id;
}

$casilla1="";
$casilla2="";
$casilla3="";

$selected1="";
$selected2="";
$selected3="";

$name1="";
$name2="";
$name3="";

if($modo=="codigo"){
	$casilla1="style='display:none'";
	$casilla2="style='display:none'";
	$casilla3="style='display:inline'";
	
	$selected1="";
	$selected2="";
	$selected3="checked='checked'";
	
	$name1="url_video_1";
	$name2="url_video_2";
	$name3="url_video";

}elseif($modo=="buscar"){
	$casilla1="style='display:none'";
	$casilla2="style='display:inline'";
	$casilla3="style='display:none'";
	
	$selected1="";
	$selected2="checked='checked'";
	$selected3="";
	
	$name1="url_video_1";
	$name2="url_video";
	$name3="url_video_3";
	
}elseif($modo=="cargar"){
	$casilla1="style='display:inline'";
	$casilla2="style='display:none'";
	$casilla3="style='display:none'";
	
	$selected1="checked='checked'";
	$selected2="";
	$selected3="";
	
	$name1="url_video";
	$name2="url_video_2";
	$name3="url_video_3";
	
}else{
	$casilla1="style='display:inline'";
	$casilla2="style='display:none'";
	$casilla3="style='display:none'";
	
	$selected1="checked='checked'";
	$selected2="";
	$selected3="";
	
	$name1="url_video";
	$name2="url_video_2";
	$name3="url_video_3";
}
?>
	  <tr>
   <td valign="top">&nbsp;</td>
   <td><div align="right"><a href="admin.php?mod=118">Cancel/New</a></div></td>
 </tr>
 <tr>
   <td valign="top"><div align="right"></div></td>
   <td><div align="right"><font color="#FF0000" size="1">(*)Fields Required</font></div></td>
 </tr>
 <tr>
    <td valign="top"><div align="right"><strong>Creation Date</strong></div></td>
    <td><? if ($fecha==""){ echo date('Y-m-d'); } else { echo $fecha; }?></td>
  </tr>
  <tr>
    <td valign="top"><div align="right"><strong>Languaje</strong></div></td>
    <td><strong>
      <select name="l_lang" id="l_lang" <?=$activo_campo_idioma?> >
        <?php cargar_lista("ic_language","lang_prefijo,lang_descripcion","lang_id","0",$l_lang,"",$db); ?>
      </select>
    </strong></td>
  </tr>
  <tr>
    <td valign="top"><div align="right"><strong>Name</strong>(*)</div></td>
    <td>
		<input  name="titulo" type="text" id="titulo" value="<?=$titulo?>" size="50">	</td>
  </tr>
	  <tr>
		<td valign="top"><div align="right"><strong>Categorie</strong></div></td>
		<td>
		<select name="categoria" id="categoria" >
        <?php cargar_lista("ic_categoria","cat_id,cat_titulo","cat_titulo","1",$categoria," WHERE id_tipo='4' ",$db); ?>
      </select>		</td>
	  </tr>
	  <tr>
		<td valign="top"><div align="right"><b>Description</b></div></td>
		<td><textarea name="full" style="width: 458px; height: 100px;" id="texto_completo"><?= $full?></textarea></td>
	  </tr>
	  <tr>
		<td valign="top"><div align="right"><b>Image</b>(100x100)</div></td>
		<td><input type="file" name="img_previa" />		</td>
	  </tr>
	  <tr>
		<td valign="top">&nbsp;</td>
		<td><img src="<?=$img_previa?>" border="0" width="100" height="100"/></td>
	  </tr>
	  <tr>
	    <td valign="top"><div align="right"><strong>Add to content</strong></div></td>
	    <td valign="top">[!--{<?=$id_inclusion?>-videos}--]<br />
        <i><font size="1">C&oacute;digo para incluir en contenido (noticias, paginas estaticas, eventos)</font></i></td>
    </tr>
	  <tr>
		<td valign="top"><div align="right"><b>URL video</b>(*)</div></td>
		<td rowspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="5">
          <tr>
            <td width="100" bgcolor="#DBDBDB"><div align="right"><font size="1">Upload File</font></div></td>
            <td width="1" bgcolor="#DBDBDB"><div align="right">
                <input name="modo" type="radio" id="modo_1" value="cargar" checked="checked" onclick="mostrarCargar('cargar');" <?=$selected1?>/>
            </div></td>
            <td bgcolor="#DBDBDB"><input name="<?=$name1?>" type="file" id="url_video" size="40" <?=$casilla1?>  /></td>
          </tr>
          <tr>
            <td width="100"><div align="right"><font size="1">Search File</font></div></td>
            <td><div align="right">
                <input name="modo" type="radio" id="modo_2" value="buscar"  onclick="mostrarCargar('buscar');" <?=$selected2?>/>
            </div></td>
            <td><select name="<?=$name2?>" id="url_video_2" <?=$casilla2?>>
<?php
	$directorio = dir("images/videos/");
	
	while($archivo = $directorio->read())
	{
		$selected = "";
		
		if($archivo!="." && $archivo!=".."){
			if(!is_dir("images/videos/".$archivo)){
			
				if($url_video == "images/videos/".$archivo){
					$selected = "selected=\"selected\"";
				}
				
				echo "<option value=\"images/videos/".$archivo."\" ".$selected .">".$archivo."</option>";
			}		
		}
	}
	$directorio->close();
?>
		</select></td>
          </tr>
          <tr>
            <td width="100" bgcolor="#DBDBDB"><div align="right"><font size="1">
                <label for="label">youtube Code</label>
            </font></div></td>
            <td bgcolor="#DBDBDB"><div align="right">
                <input name="modo" type="radio" id="mod_3" value="codigo"  onclick="mostrarCargar('codigo');" <?=$selected3?>/>
            </div></td>
            <td bgcolor="#DBDBDB"><textarea cols="50" rows="2" name="<?=$name3?>" id="url_video_3" <?=$casilla3?>><?=$url_video?></textarea></td>
          </tr>
        </table>
		  
				<font size="1">Videos in format FLV (Flash Video)</font> <a href="<?=$url_video?>" target="_blank" ><?=$url_video?>
		</a></td>
	  </tr>
	  <tr>
		<td valign="top">&nbsp;</td>
	  </tr>
	   <tr>
	     <td valign="top">&nbsp;</td>
	     <td>       
    <tr>
	  <td valign="top">&nbsp;</td>
	  <td>
		<input type="button" onClick="enviar('titulo');" name="Enviar" value="Save Video">
  </form>
		<p>
		<form action="" method="post" name="eliminar">
		<input type="submit" name="Borrar" value="Borrar Video">
		<input type="hidden" name="id" value="<?=$id?>">
		<input type="hidden" name="funcion" value="borrar">
		</form>
		</p>
		</td>
	  </tr>
	  </form>
</table>
