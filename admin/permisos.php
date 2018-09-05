<?php
session_permiso("10");

$variables_metodo = variables_metodo("grupo,permisos_grupo,funcion");

$grupo= 			$variables_metodo[0];
$permisos_grupo= 	$variables_metodo[1];
$funcion= 			$variables_metodo[2];

if ($funcion == "guardar"){
	/*Funcion para guardar los datos del formulario*/	
	guardar($grupo,$permisos_grupo);
}elseif($funcion=="modificar"){
	/*Funcion para modificar los datos del formulario*/	
	modificar($grupo,$permisos_grupo);	
}elseif($funcion == "borrar"){
	borrar($grupo);
}

/****************************************************************************************************************************/
function borrar($grupo)
{
	$path = "adodb/adodb.inc.php";
	include "var.php";
	include "conexion.php";
	
	//funcion para registrar la modificacion hecha por el usuario
	registrar_cambios($db, date('Y-m-d'), $_SESSION['sess_nombre'], '10', $grupo, 'ELIMINAR');
	
	$result=$db->Execute("DELETE FROM ic_privilegios WHERE id_grupo = '".$grupo."' ");
	
	if ($result != false) $mensaje = "3";
	else $mensaje  = "0";
	
	echo"<META HTTP-EQUIV='Refresh' CONTENT='0;URL=admin.php?mod=10&mensaje=".$mensaje."'>";
	die();
}
/****************************************************************************************************************************/
function guardar($grupo,$permisos_grupo)
{
	$path = "adodb/adodb.inc.php";
	include "var.php";
	include "conexion.php";

	$result=insert_bd_format("id_grupo,modulos", "ic_privilegios", array($grupo,$permisos_grupo), $db);
	
	if ($result != false) $mensaje = "1";
	else $mensaje  = "0";
	
	//funcion para registrar la modificacion hecha por el usuario
	registrar_cambios($db, date('Y-m-d'), $_SESSION['sess_nombre'], '10', '0', 'GUARDAR');
	
	echo"<META HTTP-EQUIV='Refresh' CONTENT='0;URL=admin.php?mod=10&mensaje=".$mensaje."'>";
	die();	
	
}
/****************************************************************************************************************************/
function modificar($grupo,$permisos_grupo)
{
	$path = "adodb/adodb.inc.php";
	include "var.php";
	include "conexion.php";

	$result=update_bd_format(array("id_grupo","modulos"), "ic_privilegios", array($grupo,$permisos_grupo), "WHERE id_grupo='".$grupo."'", $db);
	
	if ($result != false) $mensaje = "2";
	else $mensaje  = "0";
	
	//funcion para registrar la modificacion hecha por el usuario
	registrar_cambios($db, date('Y-m-d'), $_SESSION['sess_nombre'], '10', $grupo, 'MODIFICAR');
	
	echo"<META HTTP-EQUIV='Refresh' CONTENT='0;URL=admin.php?mod=10&mensaje=".$mensaje."'>";
	die();
}
/****************************************************************************************************************************/

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
<?php
if ($grupo != ""){
	$sql="SELECT id_grupo,modulos FROM ic_privilegios WHERE id_grupo='".$grupo."' ";
	$result=$db->Execute($sql);
	
	list($grupo,$permisos_grupo)=select_format($result->fields);
	
	echo '<input type="hidden" name="funcion" value="modificar">';
}else{
	echo '<input type="hidden" name="funcion" value="guardar">';
}
?>
<tr align="center">
    <td colspan="12">
    <img src="images/admin/bloques.png" border="0"/>  Secciones HTML
    </td>
  </tr>
  <tr>
  <td colspan="5"><div style="margin:20px 0; width:100%; border-top:1px solid #494949;"></div></td>
</tr>
 <tr>
   <td colspan="4" valign="top">
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
   </td>
   </tr>
 <tr>
   <td valign="top" width="50%">&nbsp;</td>
   <td><div align="right"><a href="admin.php?mod=10">Carcelar/Nuevo</a></div></td>
 </tr>
 <tr>
   <td valign="top">&nbsp;</td>
   <td><div align="right"><font color="#FF0000" size="1">(*)Campos Requeridos</font></div></td>
 </tr>
  <tr>
    <td valign="top"><div align="left"><strong>Grupos</strong>(*) </div></td>
    <td><div align="left"><strong>Permisos</strong>(*) </div></td>
  </tr>
  <tr>
    <td valign="top">
	<select name="grupo" id="grupo" class="form-control">
	<?php 
		if($grupo==""){
		
			$sql="SELECT id_grupo FROM ic_privilegios";
			$result=$db->Execute($sql);
			
			list($id_tmp)=select_format($result->fields);
			if($id_tmp==""){
				cargar_lista("ic_grupo","gru_id,gru_titulo","gru_id","1",$grupo,"",$db); 
			}else{
				cargar_lista("ic_grupo,ic_privilegios","gru_id,gru_titulo","gru_id","1","","WHERE gru_id!=id_grupo",$db); 
			}
			
		}else{
			cargar_lista("ic_grupo","gru_id,gru_titulo","gru_id","1",$grupo,"",$db); 
		}
	?>
    </select>	</td>
    <td><textarea name="permisos_grupo" class="form-control" id="permisos_grupo"><?=$permisos_grupo?></textarea></td>
  </tr>
  <tr>
    <td valign="top">&nbsp;</td>
    <td align="right">
      <input type="button" name="Enviar" onClick="enviar('grupo,permisos_grupo');" value="Guardar">    </td>
  </form>	
</table>

<p>&nbsp;</p>
<table border="1" align="center" cellpadding="5" cellspacing="0" bordercolor="#CCCCCC" width="800" style="border-collapse:collapse">
  <tr>
    <td bgcolor="#F4F4F4"><strong>Group</strong></td>
    <td bgcolor="#F4F4F4"><strong>Modules</strong></td>
    <td bgcolor="#F4F4F4">&nbsp;</td>
  </tr>
<?php
	$sql="SELECT id_grupo,gru_titulo,modulos FROM ic_privilegios,ic_grupo WHERE id_grupo=gru_id";
	$result=$db->Execute($sql);
	
	while(!$result->EOF){
		list($id_grupo,$gru_titulo,$modulos)=select_format($result->fields);
?>
  <tr>
    <td><?=$gru_titulo?></td>
    <td><?=$modulos?></td>
    <td><a href="admin.php?mod=10&grupo=<?=$id_grupo?>">Edit</a> - <a href="admin.php?mod=10&grupo=<?=$id_grupo?>&funcion=borrar">Delete</a> </td>
  </tr>
<?php
		$result->MoveNext();
	}
?>
</table>
<p>&nbsp;</p>
