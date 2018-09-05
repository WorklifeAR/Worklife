<?php

session_permiso("6");

$variables_metodo = variables_metodo("id,fecha,titulo,full,mod_gru,item,gru_per,funcion,gru_id");

$id= 			$variables_metodo[0];
$fecha= 		$variables_metodo[1];
$titulo= 		$variables_metodo[2];
$full= 			$variables_metodo[3];
$mod_gru= 		$variables_metodo[4];
$item= 			$variables_metodo[5];
$gru_per= 		$variables_metodo[6];
$funcion= 		$variables_metodo[7];
$gru_id= 		$variables_metodo[8];

switch($funcion) 
{
	case "permisos": { 
		permisos($mod_gru,$item,$gru_per);
		break;
	}case "borrar": { 
		borrar($id);
		break;
	}case "guardar": { 
		guardar($id,$fecha,$titulo,$full);
		break;
	}
}

/****************************************************************************************************************************/
function permisos($mod_gru,$item,$gru_per)
{
	$path = "adodb/adodb.inc.php";
	include "var.php";
	include "conexion.php";
	
	if (($mod_gru != "eli")&&($item!="")&&($gru_per!="")){	
	
		$result=insert_bd_format("mod,gru_id,item_id", "ic_permisos", array($mod_gru,$gru_per,$item), $db);
		
		if ($result != false) $mensaje = "1";
		else $mensaje  = "0";
		
		echo"<META HTTP-EQUIV='Refresh' CONTENT='0;URL=admin.php?mod=6&mensaje=".$mensaje."'>";
		die();	
		
	}elseif (($mod_gru == "eli")&&($item!="")&&($gru_per!="")){		
	
		$result=$db->Execute("DELETE FROM ic_permisos WHERE gru_id = '".$gru_per."' AND item_id = '".$item."' ");
		
		if ($result != false) $mensaje = "3";
		else $mensaje  = "0";
		
		echo"<META HTTP-EQUIV='Refresh' CONTENT='0;URL=admin.php?mod=6&mensaje=".$mensaje."'>";
		die();
		
	}else{	
		echo"<META HTTP-EQUIV='Refresh' CONTENT='0;URL=admin.php?mod=6&mensaje=4'>";
		die();
	}
}
/****************************************************************************************************************************/
function borrar($id)
{
	$path = "adodb/adodb.inc.php";
	include "var.php";
	include "conexion.php";
	
	$sql="SELECT gru_titulo FROM ic_grupo WHERE gru_id='".$id."' ";
	$result=$db->Execute($sql);
	
	list($titulo)=select_format($result->fields);

	//funcion para registrar la modificacion hecha por el usuario
	registrar_cambios($db, date('Y-m-d'), $_SESSION['sess_nombre'], '6', $id, $titulo, 'ELIMINAR');
	
	$result=$db->Execute("delete from ic_grupo where gru_id = '".$id."' ");
	
	if ($result != false) $mensaje = "3";
	else $mensaje  = "0";
		
	echo"<META HTTP-EQUIV='Refresh' CONTENT='0;URL=admin.php?mod=6&mensaje=".$mensaje."'>";
	die();
}
/****************************************************************************************************************************/
function guardar($id,$fecha,$titulo,$full)
{
	$path = "adodb/adodb.inc.php";
	include "var.php";
	include "conexion.php";
	
	if (($id == "")&&($fecha!="")&&($titulo!="")){
	
		$result=insert_bd_format("gru_titulo,gru_conten,gru_creacion", "ic_grupo", array($titulo,$full,$fecha), $db);
	
		if ($result != false) $mensaje = "1";
		else $mensaje  = "0";
		
		//funcion para registrar la modificacion hecha por el usuario
	    registrar_cambios($db, date('Y-m-d'), $_SESSION['sess_nombre'], '6',  '0', $titulo, 'GUARDAR');
		
		echo"<META HTTP-EQUIV='Refresh' CONTENT='0;URL=admin.php?mod=6&mensaje=".$mensaje."'>";
		die();	
	
	}elseif(($id != "")&&($fecha!="")&&($titulo!="")){
	
		$result=update_bd_format(array("gru_titulo","gru_creacion","gru_conten"), "ic_grupo", array($titulo,$fecha,$full), "WHERE gru_id='".$id."'", $db);
		
		if ($result != false) $mensaje = "2";
		else $mensaje  = "0";
		
		//funcion para registrar la modificacion hecha por el usuario
	    registrar_cambios($db, date('Y-m-d'), $_SESSION['sess_nombre'], '6',  $id, $titulo, 'MODIFICAR');
		
		echo"<META HTTP-EQUIV='Refresh' CONTENT='0;URL=admin.php?mod=6&mensaje=".$mensaje."'>";
		die();
	
	}else{
		echo"<META HTTP-EQUIV='Refresh' CONTENT='0;URL=admin.php?mod=6&mensaje=4'>";
		die();
	}
}
/****************************************************************************************************************************/
?>

<table border="0" width="600" cellspacing="0" align="center">
 <tr align="center">
    <td colspan="12">
    <img src="images/admin/grupos.png" border="0"/>  Grupos de usuarios
    </td>
  </tr>
  <tr>
  	<td colspan="12">&nbsp;</td>
  </tr>
  <tr>
  <form action="" name="modificar" method="post">
    <td>
        <select name="gru_id" class="form-control">           
        	<?php cargar_lista("ic_grupo","gru_id,gru_titulo","gru_titulo","1","","",$db); ?>
        </select>
    </td>
    <td>
    	<input type="submit" name="Submit" value="Edit" />
    </td>
  </form>
  </tr>
</table>

<?php
if($mensaje==1){ 
	$mensaje = "<center><span id='confirmacion'>Informaci&oacute;n Guardada Correctamente</span></center>";
}elseif(($mensaje==2)&&($mensaje!="")){
	$mensaje  = "<center><span id='confirmacion'>Informaci&oacute;n Actualizada Correctamente</span></center>"; 
}elseif(($mensaje==3)&&($mensaje!="")){
	$mensaje  = "<center><span id='confirmacion'>Informaci&oacute;n Eliminada Correctamente</span></center>"; 
}elseif(($mensaje==4)&&($mensaje!="")){
	$mensaje  = "<center><span id='error'>Existe informaci&oacute;n vacia y no se ha guardado el registro</span></center>"; 
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
	document.guardarGrupo.submit();
}
</script>


<table  border="0" align="center" width="800">
<form action="" name="guardarGrupo" method="post" >
<input type="hidden" name="funcion" value="guardar">
<input type="hidden" name="fecha" value="<? echo date('Y-m-d'); ?>">
<?php
$disable="";

if ($gru_id != ""){
	
	$sql="SELECT gru_id,gru_titulo,gru_conten,gru_creacion FROM ic_grupo WHERE gru_id='".$gru_id."' ";
	$result=$db->Execute($sql);
	
	list($id,$titulo,$full,$fecha)=select_format($result->fields);
	
	echo '<input type="hidden" name="id" value="'.$id.'">';
	
	if(($gru_id=="1")||($gru_id=="2")){
		$disable="disabled='disabled'";
	}
}
?>
<tr>
  <td colspan="5"><div style="margin:20px 0; width:100%; border-top:1px solid #494949;"></div></td>
</tr>
 <tr>
   <td colspan="2" valign="top"><div align="right"><a href="admin.php?mod=6">Cancelar/Nuevo</a></div></td>
   </tr>
 <tr>
   <td valign="top" width="100">&nbsp;</td>
   <td><div align="right"><font color="#FF0000" size="1">(*)Campos Obligatorios</font> </div></td>
 </tr>
 <tr>
    <td valign="top"><div align="right"><strong>Fecha</strong></div></td>
    <td><? if ($fecha==""){ echo date('Y-m-d'); } else { echo $fecha; }?></td>
  </tr>
  <tr>
    <td valign="top"><div align="right"><strong>Tiíulo</strong>(*) </div></td>
    <td><input type="text" name="titulo" id="titulo" class="form-control" value="<?=$titulo?>"></td>
  </tr>
  <tr>
    <td valign="top"><div align="right"><strong>Descripción</strong></div></td>
    <td><textarea name="full" cols="30" rows="2" class="form-control" id="full"><?= $full?></textarea></td>
  </tr>
  <tr>
    <td valign="top">&nbsp;</td>
    <td align="right">
	<input type="button" onClick="enviar('titulo');" name="Enviar" value="Guardar">
	</form>
    <br /><br />
    <form action="" method="post" name="eliminar">
	<input type="submit" name="Borrar" id="borrar" value="Eliminar" >
	<input type="hidden" name="id" value="<?=$id?>">
	<input type="hidden" name="funcion" value="borrar">
	</form>
    </td>
  </tr>
</table>
