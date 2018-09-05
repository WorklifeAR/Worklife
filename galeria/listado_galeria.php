<?php
	$path = "adodb/adodb.inc.php";
	include ("admin/var.php");
	include ("conexion.php");
?>
<script type="text/javascript">
jQuery(document).ready(function($) {
  $(".slider").slideshow({
	width      : 600,
	height     : 400,
	pauseOnClick : true,
	navigation: true,
    selector: false,
    timer: true,
    control: true,		
	transition : 'random'
  });
});
</script>

<div class="sombra redondeado" style="padding: 80px 5px 5px 5px; margin: 0 auto; width: 640px;">
<img src="images/galeria_titulo.fw.png" width="371" height="73" style="margin-top: -70px; margin-left: -21px;" />

<?php
	$sql = "SELECT id_imagen, nombre_imagen, img_full, img_original, img_previa, visitas_imagen, id_categoria
		    FROM ic_galeria_imagenes WHERE 1=1 ORDER BY id_imagen DESC LIMIT 0,10 ";
	$result = $db->Execute($sql);
	$result_full = $db->Execute($sql);
	
	$var=0;
	$columnas = 4;
	echo '<div class="sombra" style="padding: 5px 5px 5px 5px; margin: 0 auto; width: 600px;">
	<table border="0" cellspacing="5" cellpadding="5">';
	
	while(!$result->EOF){
		list($id_imagen, $nombre_imagen, $img_full, $img_original, $img_previa, $visita, $id_categoria)=select_format($result->fields);
		list($ancho, $altura, $tipo, $atr) = getimagesize($img_original);
		
		$modulo= my_bcmod($var, $columnas);
	
		if($modulo==0){
			echo '<tr>';
		}
		$sql="SELECT cat_titulo FROM ic_categoria WHERE cat_id='".$id_categoria."' ";
		$result_categoria=$db->Execute($sql);	
		
		list($cat_titulo)=select_format($result_categoria->fields);
		
		echo '<td bgcolor="#DDDDDD" valign="middle">
		      <a href="'.$img_full.'" rel="shadowbox[galeria];options={counterType:\'skip\'}"><img src="'.$img_previa.'" border="0" alt="'.$nombre_imagen.'" title="'.$nombre_imagen.'" width="100"/></a>
			  </td>';
		
		$var++;
		$modulo= my_bcmod($var, $columnas);
		
		if($modulo==0){
			echo '</tr>';
		}
		
		$result->MoveNext();
	}
	echo '</table></div>';
?>

<br />
<div class="sombra" style="padding: 5px 5px 5px 5px; margin: 0 auto; width: 600px;">
	<div class="slider">
<?php
	while(!$result_full->EOF){
		list($id_imagen, $nombre_imagen, $img_full, $img_original, $img_previa, $visita, $id_categoria)=select_format($result_full->fields);
		
		echo '<div><img src="'.$img_full.'" border="0" alt="'.$nombre_imagen.'" title="'.$nombre_imagen.'" /></div>';
		
		$result_full->MoveNext();
	}
?>
	</div>
</div>

<br />
<hr size="1" width="100%" color="#CCCCCC" />
<br />

<?php	
	//------------------------------------------------------------------------------------------------------------------------
	
	$sql="	SELECT COUNT( a.id_imagen ) , a.id_categoria, cat_titulo, cat_conten, b.fecha_creacion, cat_creacion
			FROM ic_galeria_imagenes a, ic_galeria_imagenes b, ic_categoria
			WHERE a.id_categoria = cat_id  AND b.id_categoria=a.id_categoria AND a.id_imagen=b.id_imagen
			GROUP BY a.id_categoria
			ORDER BY b.fecha_creacion DESC ";
	$result=$db->Execute($sql);
	
	$var=0;
	$columnas = 3;
	
	echo '<div style="margin: 0 auto; width: 640px;"><table cellspacing="10" cellpadding="4" border="0">';
	
	while(!$result->EOF){
	
		list($cant_img, $id_categoria, $categoria_titulo, $categoria_desc, $fecha_modificacion, $fecha_creacion)=select_format($result->fields);
		
		$sql="SELECT img_previa FROM ic_galeria_imagenes WHERE id_categoria='".$id_categoria."' AND img_principal='S' ";
		$result_categoria=$db->Execute($sql);	
		
		list($img_principal)=select_format($result_categoria->fields);
		
		$sql="SELECT SUM(visitas_imagen) FROM ic_galeria_imagenes WHERE id_categoria='".$id_categoria."' ";
		$result_categoria=$db->Execute($sql);	
		
		list($visitas)=select_format($result_categoria->fields);
		
		if($img_principal==""){
			$sql="SELECT img_previa FROM ic_galeria_imagenes WHERE id_categoria='".$id_categoria."' ORDER BY id_imagen DESC ";
			$result_categoria=$db->Execute($sql);	
			
			list($img_principal)=select_format($result_categoria->fields);
		}
		
		$modulo= my_bcmod($var, $columnas);
		
		if($modulo==0){
			echo '<tr>';
		}
?>
    <td valign="top">

<table border="0" align="right" cellpadding="0" cellspacing="0">
  
  <tr>
    <td align="center"><a href="<?=organizar_nombre_link("galeria", $categoria_titulo)?>"><img src="<?=$img_principal?>" border="0" alt="<?=$nombre_imagen?>" title="<?=$nombre_imagen?>" vspace="3"/></a></td>
  </tr>
  <tr>
    <td><a href="<?=organizar_nombre_link("galeria", $categoria_titulo)?>" style="text-decoration:none;"><b><?=$categoria_titulo?></b></a></td>
  </tr>
  <tr>
    <td><?=$categoria_desc?></td>
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
	
	echo '</table></div>';
?>
</div>