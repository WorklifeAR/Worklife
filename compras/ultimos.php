<div style="font-family: 'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:20px; font-weight:bold">Last Added</div>
<div style="width:480px; height: 400px; overflow:auto; margin: 0px 0px 0px 0px;">

<table width="100%" border="0" cellspacing="0" cellpadding="5">

<?php

include ("conexion.php");

$var=0;
$columnas = 3;

//Consulta para todas las categorias
$sql="SELECT  cat_id, cat_titulo, cat_conten,img_categoria FROM ic_categoria WHERE orden='S' AND id_tipo='6' AND cat_sub_categoria = '0' ORDER BY cat_id DESC LIMIT 0,6";
$result=$db->Execute($sql);

while(!$result->EOF){
	list($cat_id, $cat_titulo, $cat_conten, $img_categoria)=$result->fields;

	$modulo= my_bcmod($var, $columnas);
	
	if($modulo==0){
		echo '<tr>';
	}
?>

<td valign="top">
  <table border="0" cellspacing="0" cellpadding="2" style="height: 170px;">             
	  <tr>
		<td align="center" style="height:145px;"><img src="<?=$img_categoria?>" border="0" alt="<?=$cat_titulo?>" hspace="10" vspace="0" /></td>
	  </tr>
	   <tr>
		<td align="center" bgcolor="#666666"><a href="ver.php/mod/catalogo/categoria/<?=$cat_id?>" style="text-decoration:none; color:#FFFFFF"><h1 style="text-decoration:none; color:#FFFFFF; font-size:13px; line-height: 15px"><?=$cat_titulo?></h1></a></td>
	</tr>             
  </table>	
</td>

<?php
		$var++;
		$modulo= my_bcmod($var, $columnas);
		
		if($modulo==0){
			echo '</tr>';
		}
	
		$result->MoveNext();
	}
?>

</table>
</div>