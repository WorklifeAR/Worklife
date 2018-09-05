<?php

session_permiso("111");

$variables_metodo = variables_metodo("id_descarga,fecha,nombre_descarga,descripcion_descarga,funcion,url_descarga,des_id,session_descarga,categoria_descarga");

$id_descarga= 			$variables_metodo[0];
$fecha= 				$variables_metodo[1];
$nombre_descarga= 		$variables_metodo[2];
$descripcion_descarga= 	$variables_metodo[3];
$funcion= 				$variables_metodo[4];
$url_descarga= 			$variables_metodo[5];
$des_id= 				$variables_metodo[6];
$session_descarga= 		$variables_metodo[7];
$categoria_descarga= 	$variables_metodo[8];

if ($funcion == "guardar"){
	if($id_descarga==""){
	/*Funcion para guardar los datos del formulario*/	
		guardar($fecha,$nombre_descarga,$descripcion_descarga,$session_descarga,$url_descarga,$categoria_descarga);
	}elseif($id_descarga!=""){
	/*Funcion para modificar los datos del formulario*/	
		modificar($id_descarga,$fecha,$nombre_descarga,$descripcion_descarga,$session_descarga,$url_descarga,$categoria_descarga);
	}	
}elseif(($funcion == "borrar")&&($id_descarga!="")){
	borrar($id_descarga);
}

/****************************************************************************************************************************/

function borrar($id_descarga){

	$path = "adodb/adodb.inc.php";
	include "var.php";
	include "conexion.php";
	
	$sql="SELECT nombre_descarga  FROM ic_descargas WHERE id_descarga='".$id."' ";
	$result=$db->Execute($sql);
	
	list($titulo)=select_format($result->fields);

	//funcion para registrar la modificacion hecha por el usuario
	registrar_cambios($db, date('Y-m-d'), $_SESSION['sess_nombre'], '111', $id_descarga, $titulo, 'ELIMINAR');
	
	$result=$db->Execute("DELETE FROM ic_descargas WHERE id_descarga = '".$id_descarga."' ");
	
	if ($result != false) $mensaje = "3";
	else $mensaje  = "0";
		
	echo"<META HTTP-EQUIV='Refresh' CONTENT='0;URL=admin.php?mod=111&mensaje=".$mensaje."'>";
	die();
}

/****************************************************************************************************************************/

function guardar($fecha,$nombre_descarga,$descripcion_descarga,$session_descarga,$url_descarga,$categoria_descarga){

	$path = "adodb/adodb.inc.php";
	include "var.php";
	include "conexion.php";

	$archivo_cargado="";
	$dir="images/descargas/";
	
	if(!empty($_FILES['url_descarga']['name'])){

		$dir="images/descargas/";
		$url = $_FILES['url_descarga']['tmp_name'];
		$archivo_nombre = $_FILES['url_descarga']['name']; 
		
		if(!subir_imagen($archivo_nombre,$url,$dir)){
			echo"<META HTTP-EQUIV='Refresh' CONTENT='0;URL=admin.php?mod=111&mensaje=0'>";
			die();
		}
		
		$archivo_cargado=$dir.$archivo_nombre;
	}else{
		$archivo_cargado=$url_descarga;
	}
	
	$result=insert_bd_format("categoria_descarga,nombre_descarga,descripcion_descarga,fecha_modificacion,url_descarga,session_descarga", "ic_descargas", array($categoria_descarga,$nombre_descarga,$descripcion_descarga,$fecha,$archivo_cargado,$session_descarga), $db);

	if ($result != false) $mensaje = "1";
	else $mensaje  = "0";
	
	//funcion para registrar la modificacion hecha por el usuario
	registrar_cambios($db, date('Y-m-d'), $_SESSION['sess_nombre'], '111', '0', $nombre_descarga, 'GUARDAR');
	
	echo"<META HTTP-EQUIV='Refresh' CONTENT='0;URL=admin.php?mod=111&mensaje=".$mensaje."'>";
	die();	
}

/****************************************************************************************************************************/

function modificar($id_descarga,$fecha,$nombre_descarga,$descripcion_descarga,$session_descarga,$url_descarga,$categoria_descarga){

	$path = "adodb/adodb.inc.php";
	include "var.php";
	include "conexion.php";
	
	$archivo_cargado="";
	$archivo_cargado2="";
	
	if(!empty($_FILES['url_descarga']['name'])){

		$dir="images/descargas/";
		$url = $_FILES['url_descarga']['tmp_name'];
		$archivo_nombre = $_FILES['url_descarga']['name']; 
		
		if(!subir_imagen($archivo_nombre,$url,$dir)){
			echo"<META HTTP-EQUIV='Refresh' CONTENT='0;URL=admin.php?mod=111&mensaje=0'>";
			die();
		}
		
		$archivo_cargado="url_descarga";
		$archivo_cargado2=$dir.$archivo_nombre;
	}/*else{
		$archivo_cargado=", url_descarga='".$url_descarga."' ";
	}*/
	
	$result=update_bd_format(array("categoria_descarga","nombre_descarga","descripcion_descarga","fecha_modificacion","session_descarga",$archivo_cargado), "ic_descargas", array($categoria_descarga,$nombre_descarga,$descripcion_descarga,$fecha,$session_descarga,$archivo_cargado2), "WHERE id_descarga='".$id_descarga."'", $db);
	
	if ($result != false) $mensaje = "2";
	else $mensaje  = "0";
	
	//funcion para registrar la modificacion hecha por el usuario
	registrar_cambios($db, date('Y-m-d'), $_SESSION['sess_nombre'], '111', $id_descarga, $nombre_descarga, 'MODIFICAR');	
	
	echo"<META HTTP-EQUIV='Refresh' CONTENT='0;URL=admin.php?mod=111&mensaje=".$mensaje."'>";
	die();
}

?>

<script language="javascript" type="text/javascript">
	incluir_editor("texto_completo");
</script>

<form action="" name="modificar" method="post">
<table border="0" cellspacing="5" cellpadding="0" align="center">
  <tr align="center">
    <td colspan="3" class="nombre_descarga"><strong>Crear/Modificar Descargas </strong></td>
  </tr>
   <tr>
    <td><strong>Modificar Descarga </strong></td>
    <td>
	  <strong>
	  <select name="des_id">
		<?php cargar_lista("ic_descargas","id_descarga,nombre_descarga","nombre_descarga","1","","",$db); ?>
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
		document.getElementById("url_descarga_2").style.display="none";
		document.getElementById("url_descarga").style.display="inline";
		
		document.getElementById("url_descarga").name="url_descarga";
		document.getElementById("url_descarga_2").name="url_descarga_3";
	}else{
		document.getElementById("url_descarga").style.display="none";
		document.getElementById("url_descarga_2").style.display="inline";
		
		document.getElementById("url_descarga_2").name="url_descarga";
		document.getElementById("url_descarga").name="url_descarga_3";
	}
}

</script>

<table  border="0" align="center" cellpadding="0" cellspacing="7">
<form action="" name="guardar" method="post" enctype="multipart/form-data">
<input type="hidden" name="funcion" value="guardar">
<input type="hidden" name="fecha" value="<?=date('Y-m-d')?>">
<?php
if ($des_id != ""){

	$sql="SELECT id_descarga,categoria_descarga,nombre_descarga,descripcion_descarga,fecha_modificacion,url_descarga,session_descarga 
	      FROM ic_descargas WHERE id_descarga='".$des_id."' ";
	$result=$db->Execute($sql);
	
	list($id_descarga,$categoria_descarga,$nombre_descarga,$descripcion_descarga,$fecha_modificacion,$url_descarga,$session_descarga)=select_format($result->fields);
	
	echo '<input type="hidden" name="id_descarga" value="'.$id_descarga.'">';
}
?>
 <tr>
   <td valign="top">&nbsp;</td>
   <td><div align="right"><a href="admin.php?mod=111">Cancelar / Nuevo </a></div></td>
 </tr>
 <tr>
   <td valign="top">&nbsp;</td>
   <td><div align="right"><font color="#FF0000" size="1">(*)Campos Obligatorios</font></div></td>
 </tr>
 <tr>
    <td valign="top"><div align="right"><strong>Fecha de Creaci&oacute;n o Modificaci&oacute;n</strong></div></td>
    <td><? if ($fecha==""){ echo date('Y-m-d'); } else { echo $fecha; }?></td>
  </tr>
  <tr>
    <td valign="top"><div align="right"><strong>T&iacute;tulo o Nombre</strong>(*) </div></td>
    <td><input name="nombre_descarga" type="text" id="nombre_descarga" value="<?=$nombre_descarga?>" size="40"></td>
  </tr>
  <tr>
    <td valign="top"><div align="right"><strong>Categor&iacute;a Descarga</strong>(*) </div></td>
    <td>
	  <select name="categoria_descarga" id="categoria_descarga" >
        <?php cargar_lista("ic_categoria","cat_id,cat_titulo","cat_titulo","1",$categoria_descarga,"WHERE id_tipo='2' ",$db); ?>
      </select>	</td>
  </tr>
  <tr>
    <td valign="top"><div align="right"><strong>Archivo Descarga </strong></div>
      <div align="right">
        <table width="0%" border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td><div align="right"><font size="1">
                <label for="modo_1">Cargar Archivo</label>
            </font></div></td>
            <td><div align="right">
                <input name="modo" type="radio" id="modo_1" value="cargar" checked="checked" onclick="mostrarCargar('cargar');" />
            </div></td>
          </tr>
          <tr>
            <td><div align="right"><font size="1">
                <label for="modo_2">Buscar Archivo</label>
            </font></div></td>
            <td><div align="right">
                <input name="modo" type="radio" id="modo_2" value="buscar"  onclick="mostrarCargar('buscar');"/>
            </div></td>
          </tr>
        </table>
      </div></td>
    <td valign="top">
	<input type="file" name="url_descarga" id="url_descarga" />
	<select name="url_descarga_2" id="url_descarga_2" style="display:none">
<?php
	$directorio = dir("images/descargas/");
	
	while($archivo = $directorio->read())
	{
		$selected = "";
		
		if($archivo!="." && $archivo!=".."){
			if(!is_dir("images/descargas/".$archivo)){
			
				if($url_video == "images/descargas/".$archivo){
					$selected = "selected=\"selected\"";
				}
				
				echo "<option value=\"images/descargas/".$archivo."\" ".$selected .">".$archivo."</option>";
			}		
		}
	}
	$directorio->close();
?>
	</select>
<br />
	<a href="<?=$url_descarga?>" target="_blank"><?=$url_descarga?></a>	</td>
  </tr>
  <tr>
    <td valign="top"><div align="right"><strong>Descarga solo para usuarios </strong></div></td>
    <td>
	<select name="session_descarga" id="session_descarga" >
		<?php cargar_lista_estatica("N,S","No,Si",0,$session_descarga); ?>
  	</select> 
	</td>
  </tr>
  <tr>
    <td valign="top"><div align="right"><strong>Descripci&oacute;n</strong></div></td>
    <td><textarea name="descripcion_descarga" cols="30" rows="2" id="texto_completo"><?= $descripcion_descarga?></textarea></td>
  </tr>
  <tr>
    <td valign="top">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td valign="top">&nbsp;</td>
    <td>
	<input type="button" name="Enviar" value="Guardar Descarga" onClick="enviar('nombre_descarga,categoria_descarga');">
  </form>
	<p>
    <form action="" method="post" name="eliminar">
	<input type="submit" name="Borrar" value="Borrar Descarga">
	<input type="hidden" name="id_descarga" value="<?=$id_descarga?>">
	<input type="hidden" name="funcion" value="borrar">
	</form>
	</p>
    </td>
  </tr>
</table>
