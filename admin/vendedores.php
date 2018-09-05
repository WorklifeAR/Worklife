<?php

session_permiso("90");

$variablesPaginas = variables_metodo('funcion,us_id,us_codigo_ref,us_nombre,us_pais,us_telefono,us_email,us_direccion,us_nacimiento,us_last_name,us_sexo,us_descripcion,us_login,us_pass,us_postal,us_ciudad,us_estado_us,us_appartment,m_us_id,us_grupo,us_estado');
	
$funcion= 			$variablesPaginas[0];
$us_id= 			$variablesPaginas[1]; 
$us_codigo_ref= 			$variablesPaginas[2];
$us_nombre= 			$variablesPaginas[3];
$us_pais= 			$variablesPaginas[4];
$us_telefono= 			$variablesPaginas[5];
$us_email= 			$variablesPaginas[6];
$us_direccion= 			$variablesPaginas[7];
$us_nacimiento= 			$variablesPaginas[8];
$us_last_name= 			$variablesPaginas[9];
$us_sexo= 			$variablesPaginas[10];
$us_descripcion= 			$variablesPaginas[11];
$us_login= 			$variablesPaginas[12];
$us_pass= 			$variablesPaginas[13];
$us_postal= 			$variablesPaginas[14];
$us_ciudad= 			$variablesPaginas[15];
$us_estado_us= 			$variablesPaginas[16];
$us_appartment= 			$variablesPaginas[17];
$m_us_id= 			$variablesPaginas[18];
$us_grupo=	$variablesPaginas[19];
$us_estado=	$variablesPaginas[20];


if ($funcion == "guardar"){
	if($us_id==""){
	/*Funcion para guardar los datos del formulario*/	
		guardar($us_codigo_ref, $us_nombre, $us_pais, $us_telefono, $us_email, $us_direccion, $us_nacimiento,
			 	 $us_last_name, $us_sexo, $us_descripcion, $us_login, $us_pass, $us_postal, $us_ciudad, $us_estado_us, $us_appartment,
				 $us_estado,$us_grupo);
	}elseif($us_id!=""){
	/*Funcion para modificar los datos del formulario*/	
		modificar($us_id, $us_codigo_ref, $us_nombre, $us_pais, $us_telefono, $us_email, $us_direccion, $us_nacimiento,
			 	  $us_last_name, $us_sexo, $us_descripcion, $us_login, $us_pass, $us_postal, $us_ciudad, $us_estado_us, 
				  $us_appartment,$us_estado,$us_grupo);
	}	
}elseif(($funcion == "borrar")&&($us_id!="")){
	eliminar($us_id);
}

/***********************************************************************************************************/

function guardar($us_codigo_ref, $us_nombre, $us_pais, $us_telefono, $us_email, $us_direccion, $us_nacimiento,
			 	 $us_last_name, $us_sexo, $us_descripcion, $us_login, $us_pass, $us_postal, $us_ciudad, $us_estado_us, $us_appartment,
				 $us_estado,$us_grupo)
{
	$path = "adodb/adodb.inc.php";
	include "var.php";
	include "conexion.php";
	
	$sql1_1="SELECT us_email FROM ic_usuarios WHERE us_email='".$us_email."' "; 	
	$result1_1=$db->Execute($sql1_1);
	list($e_mail_existe)=select_format($result1_1->fields);
		
	$sql_3="SELECT us_login FROM ic_usuarios WHERE us_login='".$us_login."' "; 	
	$result1_3=$db->Execute($sql_3);
	list($nick_usuario_existe)=select_format($result1_3->fields);
	
	if( ( $e_mail_existe == "" || $e_mail_existe == NULL ) && ( $nick_usuario_existe == "" || $nick_usuario_existe == NULL ) ){
	
		$result1=insert_bd_format("us_codigo_ref,us_nombre,us_pais,us_telefono,us_email,us_direccion,us_creacion,us_last_name,us_descripcion,us_login,us_pass,us_postal,us_ciudad,us_estado_us,us_appartment,us_estado", 
							 "ic_usuarios", 
								 array('-',$us_nombre,$us_pais,$us_telefono,$us_email,
									   $us_direccion,date('Y-m-d'),$us_last_name,
									   $us_descripcion,$us_login,$us_pass,$us_postal,$us_ciudad,
									   $us_estado_us,$us_appartment,$us_estado), 
								 $db);
		
		//funcion para registrar la modificacion hecha por el usuario
	    registrar_cambios($db, date('Y-m-d'), $_SESSION['sess_nombre'], '90', '0', $us_nombre, 'GUARDAR');

			
		if ($result1 != false) $mensaje = "1";
		else $mensaje  = "0";
		
		if($mensaje=="1"){
			$sql1_1="SELECT us_id FROM ic_usuarios WHERE us_email='".$us_email."' "; 	
			$result1_1=$db->Execute($sql1_1);
			list($id_nuevo)=select_format($result1_1->fields);
	
			$result2=insert_bd_format("gru_id,id", "ic_usu_gru", array($us_grupo,$id_nuevo), $db);
			
			if ($result2 != false) $mensaje = "1";
			else $mensaje  = "4";
			
			implantar_urls($db);
			
			echo"<META HTTP-EQUIV='Refresh' CONTENT='0;URL=admin.php?mod=90&mensaje=".$mensaje."'>";
			die();
		}
		
		echo"<META HTTP-EQUIV='Refresh' CONTENT='0;URL=admin.php?mod=90&mensaje=".$mensaje."'>";
		die();		
	}else{
		echo "<script type='text/javascript'>
				alert('"._MSG_CORREO_EXISTE."');
			 </script>";
	}
}

/***********************************************************************************************************/

function eliminar($us_id)
{
	$path = "adodb/adodb.inc.php";
	include "var.php";
	include "conexion.php";
	
	$sql="SELECT us_nombre  FROM ic_usuarios WHERE us_id='".$id."' ";
	$result=$db->Execute($sql);
	
	list($titulo)=select_format($result->fields);

	//funcion para registrar la modificacion hecha por el usuario
	registrar_cambios($db, date('Y-m-d'), $_SESSION['sess_nombre'], '90', $us_id, $titulo, 'ELIMINAR');
	
	$sql="DELETE FROM ic_usu_gru WHERE id= '".$us_id."' ";
	$result2=$db->Execute($sql);
	
	$sql="DELETE FROM ic_usuarios WHERE us_id= '".$us_id."' ";
	$result2=$db->Execute($sql);
	
	if ($result2 != false) $mensaje = "3";
	else $mensaje  = "0";
	
	implantar_urls($db);
	
	echo"<META HTTP-EQUIV='Refresh' CONTENT='0;URL=admin.php?mod=90&mensaje=".$mensaje."'>";
	die();	
}  

/***********************************************************************************************************/

function modificar($us_id, $us_codigo_ref, $us_nombre, $us_pais, $us_telefono, $us_email, $us_direccion, $us_nacimiento,
			 	  $us_last_name, $us_sexo, $us_descripcion, $us_login, $us_pass, $us_postal, $us_ciudad, $us_estado_us, 
				  $us_appartment,$us_estado,$us_grupo)
{
	$path = "adodb/adodb.inc.php";
	include "var.php";
	include "conexion.php";

	$sql1_1="SELECT us_email FROM ic_usuarios WHERE us_id!='".$us_id."' AND us_email='".$us_email."' "; 	
	$result1_1=$db->Execute($sql1_1);
	list($e_mail_existe)=select_format($result1_1->fields);
		
	$sql_3="SELECT us_login FROM ic_usuarios WHERE us_id!='".$us_id."' AND us_email='".$us_email."' "; 	
	$result1_3=$db->Execute($sql_3);
	list($nick_usuario_existe)=select_format($result1_3->fields);
	
	if( ( $e_mail_existe == "" || $e_mail_existe == NULL ) && ( $nick_usuario_existe == "" || $nick_usuario_existe == NULL ) ){
			
		$result=update_bd_format(array("us_codigo_ref","us_nombre","us_pais","us_telefono","us_email","us_direccion","us_last_name",
									   "us_sexo","us_descripcion","us_pass","us_login","us_postal","us_ciudad","us_estado_us",
									   "us_appartment","us_estado"), 
								 "ic_usuarios", 
								 array($us_codigo_ref,$us_nombre,$us_pais,$us_telefono,$us_email,$us_direccion,
									   $us_last_name,$us_sexo,$us_descripcion,$us_pass,$us_login,$us_postal,
									   $us_ciudad,$us_estado_us,$us_appartment,$us_estado), 
								 "WHERE us_id='".$us_id."'", 
								 $db);
			
		if ($result != false) $mensaje = "2";
		else $mensaje  = "0";
		
		//funcion para registrar la modificacion hecha por el usuario
	    registrar_cambios($db, date('Y-m-d'), $_SESSION['sess_nombre'], '90', $us_id, $us_nombre, 'MODIFICAR');

		$result2=update_bd_format(array("gru_id"), "ic_usu_gru", array($us_grupo), "WHERE id='".$us_id."'", $db);
		
		implantar_urls($db);
		
		echo"<META HTTP-EQUIV='Refresh' CONTENT='0;URL=admin.php?mod=90&mensaje=".$mensaje."'>";
		die();
	}else{
		echo "<script type='text/javascript'>
				alert('"._MSG_CORREO_EXISTE."');
			 </script>";
	}
}

/***********************************************************************************************************/

?>

<form action="" name="modificar" method="post">
<table border="0" cellspacing="5" cellpadding="0" align="center">
  <tr align="center">
    <td colspan="3" class="titulo"><strong>Create/Modify Resellers</strong></td>
  </tr>
   <tr>
    <td><strong>Edit Users</strong></td>
    <td>
	  <strong>
	  <select name="m_us_id">
		<?php cargar_lista("ic_usuarios","us_id,CONCAT(us_nombre;' ';us_last_name),us_email,if(us_estado='1'; 'Active'; 'Inactive')","us_nombre","1",""," WHERE us_id IN (SELECT id FROM ic_usu_gru WHERE gru_id=3)",$db); ?>
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
	$mensaje  = "<center><span id='confirmacion'>Ha ocurrido un error y no se ha guardado la informaci&oacute;n.</span></center>"; 
}elseif(($mensaje==4)&&($mensaje!="")){
	$mensaje  = "<center><span id='error'>Ha ocurrido un error, se guardo el usuario pero no se le asigno grupo. Por favor edite el usuario nuevamente.</span></center>"; 
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
			alert("Campo "+ document.getElementById(camposObligatorios[i]).title +" obligatorio");
			return;
		}
	}	
	document.guardar.submit();
}
</script>

<table  border="0" align="center" cellpadding="3" cellspacing="0">
<form action="" name="guardar" method="post">
<input type="hidden" name="funcion" value="guardar">
<input type="hidden" name="fecha" value="<? echo date('Y-m-d'); ?>">
<?php
if ($m_us_id != ""){

	$sql="SELECT 
				 us_id,us_codigo_ref,us_nombre,us_pais,us_telefono,us_email,us_direccion,us_nacimiento,
				 us_last_name,us_sexo,us_creacion,us_ciudad,us_postal,us_descripcion,us_estado,us_login,
				 us_pass,us_estado_us,us_appartment
			  FROM ic_usuarios
			  WHERE us_id ='".$m_us_id."' ";
				
		$result=$db->Execute($sql);
	
		list($us_id,$us_codigo_ref,$us_nombre,$us_pais,$us_telefono,$us_email,$us_direccion,$us_nacimiento,
			 $us_last_name,$us_sexo,$us_creacion,$us_ciudad,$us_postal,$us_descripcion,$us_estado,$us_login,
			 $us_pass,$us_estado_us,$us_appartment)=select_format($result->fields);
	
	$sql="SELECT gru_id FROM ic_usu_gru WHERE id='".$us_id."' ";
	$result=$db->Execute($sql);
	
	list($us_grupo)=select_format($result->fields);
	
	echo '<input type="hidden" name="us_id" value="'.$us_id.'">';
}
?>
<tr>
  <td colspan="2" align="right" valign="top"><a href="admin.php?mod=90">Cancel /New</a></td>
</tr>
<tr>
  <td colspan="2" valign="top" bgcolor="#CCCCCC"><?=_REGUSER_DATPER?></td>
</tr>
<tr>
  <td width="50%" bgcolor="#FFFFFF"><div align="left">
    <?=_NOMBRE_COMPLETO?>
    (*):</div></td>
  <td width="50%" bgcolor="#FFFFFF"><div align="left">
    Last Name
    :</div></td>
  </tr>
<tr>
  <td><input name="us_nombre" type="text" id="us_nombre"  size="30" title="<?=_NOMBRE_COMPLETO?>"
					 value="<?=$us_nombre?>" /></td>
	<td><input name="us_last_name" type="text" id="us_last_name"  size="25" title="<?=_REGUSER_LASTNAME?>"
					 value="<?=$us_last_name?>" /></td>
</tr>
<tr>
  <td valign="top" bgcolor="#FFFFFF"><div align="left">
    <?=_REGUSER_DIR?> (*)
    : </div></td>
  <td bgcolor="#FFFFFF"><?=_REGUSER_APART?>:</td>
  </tr>
<tr>
	<td valign="top"><input name="us_direccion" type="text" id="us_direccion" title="<?=_DIRECCION?>"
					 value="<?=$us_direccion?>" size="25"/></td>
	<td><input name="us_appartment" type="text" id="us_appartment" title="<?=_REGUSER_APART?>"
					 value="<?=$us_appartment?>" size="30"/></td>
	</tr>
<tr>
  <td bgcolor="#FFFFFF"><div align="left">
    <?=_MSG_CONTACTO_5?> (*): </div></td>
  <td valign="top" bgcolor="#FFFFFF"><div align="left">
    <?=_REGUSER_ZIP?>
    (*)
    : </div></td>
  </tr>
<tr>
  <td><input name="us_ciudad" type="text" id="us_ciudad" title="<?=_MSG_CONTACTO_5?>"
					 value="<?=$us_ciudad?>" size="25"/></td>
  <td valign="top"><input name="us_postal" type="text" id="us_postal" title="Zip Code"
					 value="<?=$us_postal?>" size="10"/></td>
  </tr>
<tr>
  <td valign="top" bgcolor="#FFFFFF"><div align="left">
    <?=_REGUSER_STAT?>
    (*)
    : </div></td>
	<td valign="top" bgcolor="#FFFFFF"><?=_PAIS?>
	  (*):</td>
	</tr>
<tr>
  <td valign="top"><select name="us_estado_us" id="us_estado_us" title="<?=_REGUSER_STAT?>" >
    <?= listado_estados_us($us_estado_us,"1") ?>
  </select></td>
  <td valign="top"><input name="us_pais" type="text" id="us_pais"  size="25" title="<?=_PAIS?>"
					 value="<?=($us_pais=="")?"USA":$us_pais?>" /></td>
  </tr>
<tr>
  <td valign="top" bgcolor="#FFFFFF"><div align="left">
    <?=_TELEFONO?>
    (*)	  : </div></td>
  <td>&nbsp;</td>
  </tr>
<tr>
  <td valign="top"><input name="us_telefono" type="text" id="us_telefono"  title="<?=_TELEFONO?>"
					 value="<?=$us_telefono?>" size="25"/></td>
  <td>&nbsp;</td>
</tr>
<tr>
  <td colspan="2" valign="top" bgcolor="#CCCCCC"><?=_REGUSER_DATREG?></td>
</tr>
<tr>
  <td valign="top" bgcolor="#FFFFFF"><div align="left">
      <?=_EMAIL?>
    (*): </div></td>
  <td bgcolor="#FFFFFF">User Group</td>
</tr>
<tr>
  <td valign="top"><input name="us_email" type="text" id="us_email" title="<?=_EMAIL?>"
					 value="<?=$us_email?>" size="30"/></td>
  <td><select name="us_grupo" id="us_grupo" title="User Group">
    <?php cargar_lista("ic_grupo","gru_id,gru_titulo","gru_titulo","1",$us_grupo," WHERE gru_id='3'",$db); ?>
  </select></td>
</tr>
<tr>
  <td valign="top" bgcolor="#FFFFFF"><div align="left">
      <?=_USUARIO?>
    (*): </div></td>
  <td>User Status</td>
</tr>
<tr>
  <td valign="top"><input name="us_login" type="text" id="us_login" title="<?=_USUARIO?>"
					 value="<?=$us_login?>" size="25"/></td>
  <td><select name="us_estado" id="us_estado" title="User Status">
    <?php cargar_lista_estatica("1,0","Activo,Inactivo",0,$us_estado); ?>
  </select></td>
</tr>
<tr>
  <td valign="top" bgcolor="#FFFFFF"><div align="left">
      Password
    (*): </div></td>
  <td bgcolor="#FFFFFF"><div align="left">Reseller Link:</div></td>
</tr>
<tr>
  <td valign="top"><input name="us_pass" type="password" id="us_pass" title="<?=_CONTRASENA?>"
					 value="<?=$us_pass?>" size="25"/></td>
  <td><a href="<?=organizar_nombre_solo($us_login)?>/products-reseller.html"><?=organizar_nombre_solo($us_login)?>/products-reseller.html</a></td>
</tr>
<tr>
  <td valign="top">&nbsp;</td>
  <td>
  <input type="button" onClick="enviar('us_nombre,us_direccion,us_ciudad,us_postal,us_estado_us,us_email,us_email,us_login,us_pass,us_grupo,us_estado');" name="Enviar" value="Save Reseller">
	</form>
	<p>
    <form action="" method="post" name="eliminar">
	<input type="submit" name="Borrar" value="Borrar Reseller" />
	<input type="hidden" name="us_id" value="<?=$us_id?>" />
	<input type="hidden" name="funcion" value="borrar" />
	</form>
	</p>
  </td>
</tr>
</table>
