<?php
	session_start();
	
	header("Content-Type: application/vnd.ms-excel");
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("content-disposition: attachment;filename=Newsletter_".date('Y_m_d').".xls");

	$path = "../adodb/adodb.inc.php";
	include "var.php";
	include "../conexion.php";
	include "lib/general.php";
	
	$sql="SELECT 
			 us_id,us_codigo_ref,us_nombre,us_pais,us_telefono,us_email,us_direccion,us_nacimiento,
			 us_last_name,us_sexo,us_creacion,us_ciudad,us_postal,us_descripcion,us_estado,us_login,
			 us_pass,us_estado_us,us_appartment,c.gru_titulo
		  FROM ic_usuarios a, ic_usu_gru b, ic_grupo c
		  WHERE a.us_id=b.id AND b.gru_id=c.gru_id ORDER BY us_id DESC";
			
	$result=$db->Execute($sql);
	
?>
<table border="1" cellspacing="0" cellpadding="0">
    <tr>
		<th>Fecha Registro</th>
		<th>Nombre</th>
		<th>Email</th>
		<th>Ciudad</th>
		<th>Dirección</th>
		<th>Nacimiento</th>
		<th>Registro</th>
		<th>Grupo</th>
		<th>Estado</th>
    </tr>
<?php
while(!$result->EOF){
	list($us_id,$us_codigo_ref,$us_nombre,$us_pais,$us_telefono,$us_email,$us_direccion,$us_nacimiento,
		 $us_last_name,$us_sexo,$us_creacion,$us_ciudad,$us_postal,$us_descripcion,$us_estado,$us_login,
		 $us_pass,$us_estado_us,$us_appartment,$gru_titulo)=($result->fields);
	
?>
    <tr>
      <td valign="top" bgcolor="#FFFFFF" ><?=$us_creacion?></td>
      <td valign="top" bgcolor="#FFFFFF" ><?=$us_nombre?> <?=$us_last_name?></td>
      <td valign="top" bgcolor="#FFFFFF" ><?=$us_email?></td>
      <td valign="top" bgcolor="#FFFFFF" ><?=$us_ciudad?></td>
      <td valign="top" bgcolor="#FFFFFF" ><?=$us_direccion?></td>
      <td valign="top" bgcolor="#FFFFFF" ><?=$us_nacimiento?></td>
      <td valign="top" bgcolor="#FFFFFF"><?=(($us_descripcion=="")?"Registro - Email":"Registro - ".$us_descripcion)?></td>
      <td valign="top" bgcolor="#FFFFFF"><?=$gru_titulo?></td>
      <td align="center" valign="top" bgcolor="#FFFFFF"><?=(($us_estado=="0")?"Inactivo":"Activo")?></td>
    </tr>
<?php
$result->MoveNext();
}
?>
</table>