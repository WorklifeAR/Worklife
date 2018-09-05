<?php

session_permiso("9");

$variablesPaginas = variables_metodo('funcion,us_id,us_codigo_ref,us_nombre,us_pais,us_telefono,us_email,us_direccion,us_nacimiento,us_last_name,us_sexo,us_descripcion,us_login,us_pass,us_postal,us_ciudad,us_estado_us,us_appartment,m_us_id,us_grupo,us_estado,palabra_fil,grupo_fil,estado_fil,us_desc_prof,us_desc_pers,us_matricula,us_referencia,us_dni,us_cuit,us_constancia,us_tipo_serv,plan_wl');
	
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
$palabra_fil=	$variablesPaginas[21];
$grupo_fil=	$variablesPaginas[22];
$estado_fil=	$variablesPaginas[23];
$us_desc_prof = $variablesPaginas[24];
$us_desc_pers = $variablesPaginas[25];
$us_matricula = $variablesPaginas[26];
$us_referencia = $variablesPaginas[27];
$us_dni = $variablesPaginas[28];
$us_cuit = $variablesPaginas[29];
$us_constancia = $variablesPaginas[30];
$us_tipo_serv = $variablesPaginas[31];
$plan_wl = $variablesPaginas[32];

if ($funcion == "guardar"){
	if($us_id!=""){
	/*Funcion para modificar los datos del formulario*/	
		modificar($us_id, $us_codigo_ref, $us_nombre, $us_pais, $us_telefono, $us_email, $us_direccion, $us_nacimiento,
			 	  $us_last_name, $us_sexo, $us_descripcion, $us_login, $us_pass, $us_postal, $us_ciudad, $us_estado_us, 
				  $us_appartment,$us_estado,$us_grupo,$us_desc_prof,$us_desc_pers,$us_matricula,$us_referencia,$us_dni,$us_cuit,$us_constancia,$us_tipo_serv,$plan_wl);
	}	
}
/***********************************************************************************************************/

function modificar($us_id, $us_codigo_ref, $us_nombre, $us_pais, $us_telefono, $us_email, $us_direccion, $us_nacimiento,
			 	  $us_last_name, $us_sexo, $us_descripcion, $us_login, $us_pass, $us_postal, $us_ciudad, $us_estado_us, 
				  $us_appartment,$us_estado,$us_grupo,$us_desc_prof,$us_desc_pers,$us_matricula,$us_referencia,$us_dni,$us_cuit,$us_constancia,$us_tipo_serv,$plan_wl)
{
	$path = "adodb/adodb.inc.php";
	include "var.php";
	include "conexion.php";

	//---------------------------------------------------------------------
	$dir = "images/usuarios/";
	$url_full = $_FILES['us_constancia']['tmp_name'];
	$imagen_full = $_FILES['us_constancia']['name']; 
	
	$us_constancia_full = "";	
	$us_constancia1 = "";	
	
	if($url_full!="" && $imagen_full!=""){
		$alt = mt_rand();
				
		if(subir_imagen($alt.$imagen_full, $url_full, $dir))
		{
			
			$us_constancia_full = $dir.$alt.$imagen_full;
			$us_constancia1 = "us_constancia";
			
		}
	}
	
	//---------------------------------------------------------------------
	
	$sql1_1="SELECT us_email FROM ic_usuarios WHERE us_id!='".$us_id."' AND us_email='".$us_email."' "; 	
	$result1_1=$db->Execute($sql1_1);
	list($e_mail_existe)=select_format($result1_1->fields);
		
	$sql_3="SELECT us_login FROM ic_usuarios WHERE us_id!='".$us_id."' AND us_email='".$us_email."' "; 	
	$result1_3=$db->Execute($sql_3);
	list($nick_usuario_existe)=select_format($result1_3->fields);
	
	if( ( $e_mail_existe == "" || $e_mail_existe == NULL ) && ( $nick_usuario_existe == "" || $nick_usuario_existe == NULL ) ){
		$us_login = $us_email;
		$result=update_bd_format(array("us_nombre","us_telefono","us_direccion","us_last_name",
									   "us_ciudad","us_estado"), 
								 "ic_usuarios", 
								 array($us_nombre,$us_telefono,$us_direccion,
									   $us_last_name,$us_ciudad,$us_estado), 
								 "WHERE us_id='".$us_id."'", 
								 $db);
		
		$result=update_bd_format(array("us_desc_prof","us_desc_pers",
									   "us_matricula","us_referencia","us_dni","us_cuit",$us_constancia1,"us_tipo_serv","plan_wl"), 
								 "ic_usuarios", 
								 array($us_desc_prof,$us_desc_pers,$us_matricula,$us_referencia,$us_dni,$us_cuit,
									   $us_constancia_full,$us_tipo_serv,$plan_wl), 
								 "WHERE us_id='".$us_id."'", 
								 $db);
								 
		if ($result != false) $mensaje = "2";
		else $mensaje  = "0";
		
		//funcion para registrar la modificacion hecha por el usuario
	    registrar_cambios($db, date('Y-m-d'), $_SESSION['sess_nombre'], '9', $us_id, $us_nombre, 'MODIFICAR');

		$result2=update_bd_format(array("gru_id"), "ic_usu_gru", array($us_grupo), "WHERE id='".$us_id."'", $db);

		echo"<META HTTP-EQUIV='Refresh' CONTENT='0;URL=admin.php?mod=800&m_us_id=".$us_id."&mensaje=".$mensaje."'>";
		die();
	}else{
		echo "<script type='text/javascript'>
				alert('"._MSG_CORREO_EXISTE."');
			 </script>";
	}
}

/***********************************************************************************************************/
if($m_us_id=="" && $funcion!="nuevo"){
	
	$where = "";
	if($palabra_fil!=""){
		$where .= " AND (us_nombre LIKE '%".$palabra_fil."%' OR us_last_name LIKE '%".$palabra_fil."%' OR us_email LIKE '%".$palabra_fil."%')";
	}
	
	if($grupo_fil!=""){
		$where .= " AND b.gru_id ='".$grupo_fil."' ";
	}
	
	if($estado_fil!=""){
		$where .= " AND us_estado ='".$estado_fil."' ";
	}
	
	
	$sql="SELECT us_id, us_codigo_ref, us_nombre, us_pais, us_telefono, us_email, us_direccion, us_nacimiento, us_last_name, us_sexo, us_creacion, us_ciudad, us_postal, 
				   us_descripcion, us_estado, us_login, us_pass, us_estado_us, us_appartment, plan_wl, us_foto, us_desc_prof, us_desc_pers, us_matricula, us_referencia,
				   us_dni,us_cuit,us_constancia,us_tipo_serv
		  FROM ic_usuarios 
		  WHERE us_dni!='' ".$where." ORDER BY us_id DESC LIMIT 0,200";
			
	$result=$db->Execute($sql);

?>
<table border="0"  width="900" align="center">
<form action="" method="post">
  <tr align="center">
    <td colspan="12">
    <img src="images/admin/vendedores.png" border="0"/>  Usuarios Premium
    </td>
  </tr>
  <tr>
  	<td colspan="12">&nbsp;</td>
  </tr>
	  <tr align="center">
	    <td><strong>Palabra Clave</strong></td>
	    <td><input name="palabra_fil" type="text" class="form-control" id="palabra_fil" value="<?=$palabra_fil?>" /></td>
	    <td>&nbsp;</td>
	    <td><strong>Dirección</strong></td>
	    <td>
        	<select name="grupo_fil" id="grupo_fil" title="User Group" class="form-control">
				<?php cargar_lista("ic_grupo","gru_id,gru_titulo","gru_titulo","1",$grupo_fil," WHERE gru_id NOT IN (3)",$db); ?>
          	</select>
        </td>
		<td>&nbsp;</td>
	    <td><strong>Estado</strong></td>
	    <td>
            <select name="estado_fil" id="estado_fil" title="Estado" class="form-control">
            	<?php cargar_lista_estatica("1,0","Activo,Inactivo",1,$estado_fil); ?>
            </select>
        </td>
        <td>
        	<input type="submit" name="Submit2" value="Buscar" />
        </td>
  </tr>
 </form>
</table>

<table width="1100" border="0" align="center">
  <tr>
  	<td colspan="10"><div style="margin:20px 0; width:100%; border-top:1px solid #494949;"></div></td>
  </tr>
  <tr>
    <td colspan="10" align="right" ><a href="admin/excel_premium.php">[ Descargar Usuarios ]</a></td>
  </tr>
  <tr>
    <th>Fecha Registro</th>
    <th>Nombre</th>
    <th>Email</th>
    <th>DNI/CUIT</th>
    <th>Plan</th>
    <th>Opción</th>
  </tr>
<?php
	while(!$result->EOF){
		list($us_id, $us_codigo_ref, $us_nombre, $us_pais, $us_telefono, $us_email, $us_direccion, $us_nacimiento, $us_last_name, $us_sexo, $us_creacion, $us_ciudad, $us_postal, 
		     $us_descripcion, $us_estado, $us_login, $us_pass, $us_estado_us, $us_appartment, $plan_wl, $us_foto, $us_desc_prof,$us_desc_pers, $us_matricula, $us_referencia,
			 $us_dni,$us_cuit,$us_constancia,$us_tipo_serv) = select_format($result->fields);
?>

  <tr>
    <td valign="top" bgcolor="#FFFFFF" ><?=$us_creacion?></td>
    <td valign="top" bgcolor="#FFFFFF" ><?=$us_nombre?> <?=$us_last_name?></td>
    <td valign="top" bgcolor="#FFFFFF" ><?=$us_email?></td>
    <td valign="top" bgcolor="#FFFFFF"><?=$us_dni."/".$us_cuit?></td>
    <td valign="top" bgcolor="#FFFFFF"><?=$plan_wl?></td>
    <td align="center" valign="top" bgcolor="#FFFFFF" ><a href="admin.php?mod=800&m_us_id=<?=$us_id?>">[ Editar ]</a></td>
  </tr>

<?php	
		$result->MoveNext();
	}
?>
</table>


<?php
}else{
?>
<form action="" name="modificar" method="post">
<table border="0" width="600" align="center">
 <tr align="center">
    <td colspan="12">
    <img src="images/admin/vendedores.png" border="0"/>  Usuarios Premium
    </td>
  </tr>
  <tr>
  	<td colspan="12">&nbsp;</td>
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

<table  border="0" align="center" width="900">
<form action="" name="guardar" method="post" enctype="multipart/form-data">
<input type="hidden" name="funcion" value="guardar">
<input type="hidden" name="fecha" value="<? echo date('Y-m-d'); ?>">
<?php
if ($m_us_id != ""){

	$sql="SELECT us_id, us_codigo_ref, us_nombre, us_pais, us_telefono, us_email, us_direccion, us_nacimiento, us_last_name, us_sexo, us_creacion, us_ciudad, us_postal, 
				   us_descripcion, us_estado, us_login, us_pass, us_estado_us, us_appartment, plan_wl, us_foto, us_desc_prof, us_desc_pers, us_matricula, us_referencia,
				   us_dni,us_cuit,us_constancia,us_tipo_serv
		  FROM ic_usuarios 
			  WHERE us_id ='".$m_us_id."' ";
				
		$result=$db->Execute($sql);
	
		list($us_id, $us_codigo_ref, $us_nombre, $us_pais, $us_telefono, $us_email, $us_direccion, $us_nacimiento, $us_last_name, $us_sexo, $us_creacion, $us_ciudad, $us_postal, 
		     $us_descripcion, $us_estado, $us_login, $us_pass, $us_estado_us, $us_appartment, $plan_wl, $us_foto, $us_desc_prof,$us_desc_pers, $us_matricula, $us_referencia,
			 $us_dni,$us_cuit,$us_constancia,$us_tipo_serv) = select_format($result->fields);
	
	$sql="SELECT gru_id FROM ic_usu_gru WHERE id='".$us_id."' ";
	$result=$db->Execute($sql);
	
	list($us_grupo)=select_format($result->fields);
	
	echo '<input type="hidden" name="us_id" value="'.$us_id.'">';
}
?>
<tr>
  <td colspan="5"><div style="margin:20px 0; width:100%; border-top:1px solid #494949;"></div></td>
</tr>
<tr>
  <td align="left" valign="top"><a href="admin.php?mod=800">Volver</a></td>
  <td align="right" valign="top"></td>
</tr>
<tr>
  <td colspan="2" valign="top" bgcolor="#CCCCCC">Datos Personales</td>
</tr>
<tr>
  <td width="50%" bgcolor="#FFFFFF"><div align="left">
    Nombres
    (*):</div></td>
  <td width="50%" bgcolor="#FFFFFF"><div align="left">
    Apellidos
    :</div></td>
  </tr>
<tr>
  <td><input name="us_nombre" type="text" id="us_nombre"  class="form-control" title="<?=_NOMBRE_COMPLETO?>"
					 value="<?=$us_nombre?>" /></td>
	<td><input name="us_last_name" type="text" id="us_last_name"  class="form-control" title="<?=_REGUSER_LASTNAME?>"
					 value="<?=$us_last_name?>" /></td>
</tr>
<tr>
  <td valign="top" bgcolor="#FFFFFF"><div align="left">
    Dirección (*)
    : </div></td>
  <td valign="top" bgcolor="#FFFFFF"><div align="left"> Teléfono
    (*)	  : </div></td>
  </tr>
<tr>
	<td valign="top"><input name="us_direccion" type="text" id="us_direccion" title="<?=_DIRECCION?>"
					 value="<?=$us_direccion?>" class="form-control"/></td>
	<td valign="top"><input name="us_telefono" type="text" id="us_telefono"  title="Telefono"
					 value="<?=$us_telefono?>"  class="form-control"/></td>
	</tr>
<tr>
  <td bgcolor="#FFFFFF"><div align="left">
    Ciudad (*): </div></td>
  <td>Estado</td>
  </tr>
<tr>
  <td><input name="us_ciudad" type="text" id="us_ciudad" title="Ciudad"
					 value="<?=$us_ciudad?>" class="form-control"/></td>
  <td><select name="us_estado" id="us_estado" title="Grupo" class="form-control">
    <?php cargar_lista_estatica("1,0","Activo,Inactivo",0,$us_estado); ?>
  </select></td>
  </tr>
<tr>
  <td valign="top" bgcolor="#FFFFFF"><div align="left"> DNI (*): </div></td>
  <td><div align="left"> CUIT (*): </div></td>
</tr>
<tr>
  <td valign="top"><input name="us_dni" type="number" id="us_dni" class="form-control" title="DNI" placeholder="DNI" value="<?= $us_dni ?>" /></td>
  <td><input name="us_cuit" type="number" id="us_cuit" class="form-control" title="CUIT" placeholder="CUIT" value="<?= $us_cuit ?>" /></td>
</tr>
<tr>
  <td valign="top" bgcolor="#FFFFFF"><div align="left"> Tipo Prestador(*): </div></td>
  <td><div align="left"> Constancia AFIP(*): </div></td>
</tr>
<tr>
  <td valign="top">
  <select name="us_tipo_serv" id="us_tipo_serv" class="form-control" title="Tipo de Prestador" placeholder="Tipo de Prestador">
				<?= cargar_lista_estatica("Monotributista,Autonomo,SAAS,SA,SRL,No Incrito AFIP", "Monotributista,Autonomo,Empresa SAAS,Empresa SA,Empresa SRL,No Incrito AFIP", "1", $us_tipo_serv) ?>
                </select>
  </td>
  <td><input type="file" name="us_constancia" id="us_constancia" class="form-control"  />
				<?=(($us_constancia!="")?"<a href='".$us_constancia."'>Ver documento</a>":"")?></td>
</tr>
<tr>
  <td valign="top" bgcolor="#FFFFFF"><div align="left"> Plan (*): </div></td>
  <td><div align="left"></div></td>
</tr>
<tr>
  <td valign="top">
	<select name="plan_wl" id="plan_wl" class="form-control" title="Plan" placeholder="Plan">
	<?= cargar_lista_estatica("Basic,Premium", "Basic,Premium", "0", $plan_wl) ?>
	</select>
  </td>
  <td></td>
</tr>
<tr>
  <td valign="top"><div align="left"> Desc. Profesional(*): </div></td>
  <td><div align="left"> Desc. Personal(*): </div></td>
</tr>
<tr>
  <td valign="top"><textarea name="us_desc_prof" id="us_desc_prof" rows="4" class="form-control" title="Descripción profesional" placeholder="Describí tus virtudes profesionales, áreas de experiancia, años de trabajo e información sobre tu profesión."><?= $us_desc_prof ?></textarea></td>
  <td><textarea name="us_desc_pers" id="us_desc_pers" rows="4" class="form-control" title="Descripción personal" placeholder="Describí tus valores y características como persona, que te hacen un gran profesional."><?= $us_desc_pers ?></textarea></td>
</tr>
<tr>
  <td valign="top"><div align="left"> Matricula (*): </div></td>
  <td><div align="left"> Referencia (*): </div></td>
</tr>
<tr>
  <td valign="top"><textarea name="us_matricula" id="us_matricula" rows="4" class="form-control" title="Matrícula profesional" placeholder="Información sobre tu No. de Matrícula profesional o identificación profesional."><?= $us_matricula ?></textarea></td>
  <td><textarea name="us_referencia" id="us_referencia" rows="4" class="form-control" title="Referencia personal" placeholder="Información o contacto que pueda darnos una referencia profesional y personal sobre vos."><?= $us_referencia ?></textarea></td>
</tr>
<tr>
  <td valign="top">&nbsp;</td>
  <td align="right">
  <input type="button" onClick="enviar('us_nombre,us_last_name,us_telefono,us_direccion,us_dni,us_cuit');" name="Enviar" value="Guardar">
	
  </td>
</tr>
</form>
</table>
<?php
}
?>