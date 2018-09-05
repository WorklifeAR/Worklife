<?php

if(!isset($_POST['votando']) && !isset($_POST['votacion'])){
	
	include ("conexion.php");
	
	$sql="SELECT MAX(id_encuesta) FROM ic_encuestas WHERE l_lang='".$_SESSION['idioma']."'";
	$result_encuesta=$db->Execute($sql);
	list($id_max)=select_format($result_encuesta->fields);
	
	
	$sql="SELECT  nombre_encuesta, pregunta1_encuesta, pregunta2_encuesta,
	              pregunta3_encuesta, pregunta4_encuesta, pregunta5_encuesta, 
				  pregunta6_encuesta, comentario_encuesta
	      FROM ic_encuestas WHERE id_encuesta='".$id_max."'";
	$result_encuesta=$db->Execute($sql);
	
	list($nombre_encuesta, $pregunta1_encuesta, $pregunta2_encuesta, $pregunta3_encuesta, $pregunta4_encuesta, $pregunta5_encuesta, $pregunta6_encuesta, $comentario_encuesta)=select_format($result_encuesta->fields);
?>

<table width="100%" border="0"  cellspacing="0" cellpadding="5">
  <tr>
    <td bgcolor="#999999" class="redondeado"><strong><font color="#FFFFFF"><?=_ENCUESTA?></font></strong></td>
  </tr>
  <tr>
    <td>
	
	<table width="100%" border="0" cellspacing="0" cellpadding="3">
	<form name="votaciones" action="encuesta/detalle_encuesta.php" method="post">
      <tr>
        <td><span class="titulo"><?=$nombre_encuesta?></span>
          <br /><small><?=$comentario_encuesta?></small></td>
        </tr>
<?php 
	if(trim($pregunta1_encuesta)!=""){
?>
      <tr>
        <td valign="top"><table border="0" cellpadding="0" cellspacing="0">
          <?php 
	if(trim($pregunta1_encuesta)!=""){
?>
          <tr>
            <td valign="top">&nbsp;</td>
            <td valign="top"><input name="votacion" type="radio" value="1" /></td>
            <td valign="top">&nbsp;</td>
            <td valign="top"><?=$pregunta1_encuesta?></td>
            </tr>
          <?php 
	}
	if(trim($pregunta2_encuesta)!=""){
?>
          <tr>
            <td valign="top">&nbsp;</td>
            <td valign="top"><input name="votacion" type="radio" value="2" /></td>
            <td valign="top">&nbsp;</td>
            <td valign="top"><?=$pregunta2_encuesta?></td>
            </tr>
          <?php 
	}
	if(trim($pregunta3_encuesta)!=""){
?>
          <tr>
            <td valign="top">&nbsp;</td>
            <td valign="top"><input name="votacion" type="radio" value="3" /></td>
            <td valign="top">&nbsp;</td>
            <td valign="top"><?=$pregunta3_encuesta?></td>
            </tr>
          <?php 
	}
	if(trim($pregunta4_encuesta)!=""){
?>
          <tr>
            <td valign="top">&nbsp;</td>
            <td valign="top"><input name="votacion" type="radio" value="4" /></td>
            <td valign="top">&nbsp;</td>
            <td valign="top"><?=$pregunta4_encuesta?></td>
            </tr>
          <?php 
	}
	if(trim($pregunta5_encuesta)!=""){
?>
          <tr>
            <td valign="top">&nbsp;</td>
            <td valign="top"><input name="votacion" type="radio" value="5" /></td>
            <td valign="top">&nbsp;</td>
            <td valign="top"><?=$pregunta5_encuesta?></td>
            </tr>
          <?php 
	}
	if(trim($pregunta6_encuesta)!=""){
?>
          <tr>
            <td valign="top">&nbsp;</td>
            <td valign="top"><input name="votacion" type="radio" value="6" /></td>
            <td valign="top">&nbsp;</td>
            <td valign="top"><?=$pregunta6_encuesta?></td>
            </tr>
          <?php 
	}
?>
        </table></td>
        </tr>
<?php 
	}
?>
      <tr>
        <td><div align="right">
          <input type="hidden" name="votando" value="<?=$id_max?>" />
          <input type="submit" name="Submit" value="<?=_VOTAR_ENCUESTA?>" />
        </div></td>
        </tr>
      <tr>
        <td>
		<center><a href='lista-encuestas.html'><?=_VER_ENCUESTAS?></a> <a href='<?=organizar_nombre_link("encuesta", $nombre_encuesta)?>'><?=_RESULTADOS_ENCUESTA?></a>
		</center>		</td>
        </tr>
	</form>
    </table>
    </td>
  </tr>
</table>

  <?php

}else{
	session_start();
	
	include("../cabecera.php");
	
	$mensaje="";
	
	if(isset($_POST['votando']) && isset($_POST['votacion'])){

		$sql="UPDATE ic_encuestas SET valor".$_POST['votacion']."_encuesta=valor".$_POST['votacion']."_encuesta+1 WHERE id_encuesta='".$_POST['votando']."'";
		$result_encuesta=$db->Execute($sql);
		
		if($result_encuesta != false){
			$mensaje=_VOTAR_OK;
		}else{
			$mensaje=_VOTAR_ERROR;
		}
		
		$sql="SELECT nombre_encuesta FROM ic_encuestas WHERE id_encuesta='".$_POST['votando']."'";
		$result=$db->Execute($sql);
	
		list($nombre_encuesta)=select_format($result->fields);
	}
?>

<link href="../estilo.css" rel="stylesheet" type="text/css">
<br /><br />
<div align="center" class="mas_titulo">
<?=$mensaje?>
</div>

<script language="javascript" type="text/javascript">
	window.location.href="<?=organizar_nombre_link("encuesta", $nombre_encuesta)?>";
</script>

<?php

}

?>