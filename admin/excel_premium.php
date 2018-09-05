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
	
	$sql="SELECT us_id, us_codigo_ref, us_nombre, us_pais, us_telefono, us_email, us_direccion, us_nacimiento, us_last_name, us_sexo, us_creacion, us_ciudad, us_postal, 
				   us_descripcion, us_estado, us_login, us_pass, us_estado_us, us_appartment, plan_wl, us_foto, us_desc_prof, us_desc_pers, us_matricula, us_referencia,
				   us_dni,us_cuit,us_constancia,us_tipo_serv
		  FROM ic_usuarios 
		  WHERE us_dni!='' ORDER BY us_id DESC";
			
	$result=$db->Execute($sql);
	
?>
<table border="1" cellspacing="0" cellpadding="0">
    <tr>
		<th>Fecha Registro</th>
		<th>Nombre</th>
		<th>Email</th>
		<th>Ciudad</th>
		<th>Dirección</th>
		<th>Telefono</th>
		<th>Plan</th>
		<th>Descripcion Profesional</th>
		<th>Descripcion Personal</th>
		<th>Matricula</th>
		<th>Referencia</th>
		<th>DNI</th>
		<th>CUIT</th>
		<th>Tipo Prestador</th>
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
      <td valign="top" bgcolor="#FFFFFF" ><?=$us_ciudad?></td>
      <td valign="top" bgcolor="#FFFFFF" ><?=$us_direccion?></td>
      <td valign="top" bgcolor="#FFFFFF" ><?=$us_telefono?></td>
      <td valign="top" bgcolor="#FFFFFF" ><?=$plan_wl?></td>
      <td valign="top" bgcolor="#FFFFFF" ><?=$us_desc_prof?></td>
      <td valign="top" bgcolor="#FFFFFF" ><?=$us_desc_pers?></td>
      <td valign="top" bgcolor="#FFFFFF" ><?=$us_matricula?></td>
      <td valign="top" bgcolor="#FFFFFF" ><?=$us_referencia?></td>
      <td valign="top" bgcolor="#FFFFFF" ><?=$us_dni?></td>
      <td valign="top" bgcolor="#FFFFFF" ><?=$us_cuit?></td>
      <td valign="top" bgcolor="#FFFFFF" ><?=$us_tipo_serv?></td>
    </tr>
<?php
$result->MoveNext();
}
?>
</table>