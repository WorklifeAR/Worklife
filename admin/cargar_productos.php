<?php

session_permiso("122");

$variables_metodo = variables_metodo("archivo,funcion,mensaje");
$archivo= 			$variables_metodo[0];
$funcion= 			$variables_metodo[1];
$mensaje= 			$variables_metodo[2];

if ($funcion == "guardar"){
	cargar_archivo($archivo);
}

/****************************************************************************************************************************/

function cargar_archivo($archivo)
{
	$path = "adodb/adodb.inc.php";
	include "var.php";
	include "conexion.php";
	
	echo "<p class='titulo'>Cargando Informacion, por favor espere un momento. No cancele el proceso.</p>";

	$dir = "documentos/excel/";

	//---------------------------------------------------------------------

	$archivo = $_FILES['archivo']['tmp_name'];
	$archivo_name = $_FILES['archivo']['name']; 

	if($archivo!="" && $archivo_name!=""){
		
		if(!subir_imagen($archivo_name, $archivo, $dir)){
			echo"<META HTTP-EQUIV='Refresh' CONTENT='3;URL=admin.php?mod=122&mensaje=0'>";
			die("Error load file");
		}
	}
	
	$fp = fopen($dir.$archivo_name, "r");	
	while(!feof($fp)) {		
		$linea = fgets($fp);	
		$linea = explode(";",$linea);
		
		if(count($linea)>0){
			$sql = "UPDATE ic_catalogo
					SET nombre_item='".$linea[1]."', categoria_item='".$linea[2]."', peso='".$linea[3]."', precio='".$linea[4]."', 
					    descuento='".$linea[5]."', sostiene_descuento='".$linea[6]."',
						estado='".$linea[7]."', peso_lb='".$linea[8]."', alto='".$linea[9]."', ancho='".$linea[10]."', largo='".$linea[11]."', 
						peso_consolidado='".$linea[12]."', free_shipping='".$linea[13]."', 
						home='".$linea[14]."', valor_envio='".$linea[15]."', marca ='".$linea[16]."', genero ='".$linea[17]."', keywords ='".$linea[18]."'
					WHERE id_item='".$linea[0]."'";	
			$result = $db->Execute($sql);
		}
	}
	
	fclose($fp);

	implantar_urls($db);
	
	//funcion para registrar la modificacion hecha por el usuario
	registrar_cambios($db, date('Y-m-d'), $_SESSION['sess_nombre'], '122', '0', "Carga catalogo", 'GUARDAR');

	echo"<META HTTP-EQUIV='Refresh' CONTENT='0;URL=admin.php?mod=122&mensaje=1'>";
	die();	
}

/****************************************************************************************************************************/


?>

<table border="0" cellspacing="5" cellpadding="0" align="center">
  <tr align="center">
    <td class="titulo"><strong>Load File</strong></td>
  </tr>
</table>

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
<table width="50%"  border="0" align="center" cellpadding="0" cellspacing="5">
    <tr>
      <td valign="top">&nbsp;</td>
      <td><div align="right"><a href="admin.php?mod=121">Cancel/New</a></div></td>
    </tr>
    <tr>
      <td valign="top">&nbsp;</td>
      <td><div align="right"><font color="#FF0000" size="1">(*)Campos Obligatorios</font></div></td>
    </tr>
</table>
<br />


<table  border="0" align="center" cellpadding="0" cellspacing="5">
  <form action="" name="guardar" method="post" enctype="multipart/form-data">
    <input type="hidden" name="funcion" value="guardar" />
    <tr>
      <td colspan="2" align="center" valign="top"><b class="titulo">Load File (detail products) (.csv)</b></td>
    </tr>
    <tr>
      <td valign="top"><div align="right"><strong>File  (*) </strong></div></td>
      <td><input type="file" name="archivo" id="archivo" /></td>
    </tr>
    <tr>
      <td valign="top">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td valign="top">&nbsp;</td>
      <td><input type="submit" name="Enviar" value="Load File" /></td>
    </tr>
  </form>
</table>