<?php
	$path = "adodb/adodb.inc.php";
	include ("admin/var.php");
	include ("conexion.php");
	
	$variables_ver = variables_metodo("id_galeria");
	$id_galeria= 				$variables_ver[0];

	$sql = "SELECT id_imagen, nombre_imagen, img_full, img_original, img_previa, visitas_imagen
		    FROM ic_galeria_imagenes WHERE id_categoria='".$id_galeria."'" ;
	$result = $db->Execute($sql);

	$sql_categoria = "SELECT cat_titulo
		    FROM ic_categoria WHERE cat_id='".$id_galeria."' ";
	$result_categoria = $db->Execute($sql_categoria);	
	list($nombre_categoria)=select_format($result_categoria->fields);
	
	echo '<table width="100%" border="0" cellspacing="0" cellpadding="5">
	  <tr>
		<td colspan="3"><h1>'.$nombre_categoria.'</h1></td>
	  </tr>
	  <tr>
		<td align="right">
			<a href="galeria.html">'._VER_MAS_ALBUMS.'</a>
		</td>
	  </tr>
	  </table>';
	
	//-----------------------------------------------------------------------------------------
	
	$var=0;
	$columnas = 3;
	echo '<table border="0" cellspacing="5" cellpadding="10"  class="sombra">';
	
	while(!$result->EOF){
		list($id_imagen, $nombre_imagen, $img_full, $img_original, $img_previa, $visita)=select_format($result->fields);
		list($ancho, $altura, $tipo, $atr) = getimagesize($img_original);
		
		$modulo= my_bcmod($var, $columnas);
	
		if($modulo==0){
			echo '<tr>';
		}
		
		echo '<td bgcolor="#eeeeee" valign="top">
		<a href="'.$img_full.'" rel="shadowbox[galeria];options={counterType:\'skip\'}"><img src="'.$img_previa.'" border="0" alt="'.$nombre_imagen.'" title="'.$nombre_imagen.'"/>
		<br />'.$nombre_imagen.'
		</td>';
		
		$var++;
		$modulo= my_bcmod($var, $columnas);
		
		if($modulo==0){
			echo '</tr>';
		}
		
		$result->MoveNext();
	}
	echo '</table>';
?>