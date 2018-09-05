<?php

$path = "adodb/adodb.inc.php";
include ("admin/var.php");
include ("conexion.php");

//Variables para la carga de un modulo especifico
$variables_ver = variables_metodo("lista,id_item");
$lista= 				$variables_ver[0];
$id_item= 	$variables_ver[1];
	
if($lista!=""){
	$sql="SELECT  id_encuesta,fecha_encuesta,nombre_encuesta
	      FROM ic_encuestas 
	      WHERE l_lang='".$_SESSION['idioma']."' ORDER BY fecha_encuesta DESC";
	$result=$db->Execute($sql);
	
?>

<table width="100%" border="0" align="center" cellpadding="6" cellspacing="0">
  <tr>
    <td colspan="3" align="center" class="mas_titulo">
    <?=_ENCUESTA?></td>
  </tr>
  <tr>
    <td colspan="3">&nbsp;</td>
  </tr>
<?php
while(!$result->EOF){
	list($id_encuesta,$fecha_encuesta,$nombre_encuesta)=select_format($result->fields);
?>
  <tr>
    <td width="10" align="center"><img src="images/estable.gif" border="0" alt="" width="10"/></td>
    <td width="70">(<?=$fecha_encuesta?>)</td>
    <td width="430"><a href='<?=organizar_nombre_link("encuesta", $nombre_encuesta)?>'><?=$nombre_encuesta?></a></td>
  </tr>
  <tr>
    <td colspan="3" align="center"><hr /></td>
  </tr>
<?php
	$result->MoveNext();
}
?>
</table>


<?php
	
}else{
//ELSE----------------------------------------------------------------------------------------------
$where="";
if($id_item!=""){
	$where=" AND id_encuesta='".$id_item."'";
}

$sql="SELECT  id_encuesta,fecha_encuesta,nombre_encuesta,comentario_encuesta,pregunta1_encuesta,
              valor1_encuesta,pregunta2_encuesta,valor2_encuesta,pregunta3_encuesta,
			  valor3_encuesta,pregunta4_encuesta,valor4_encuesta,pregunta5_encuesta,
			  valor5_encuesta,pregunta6_encuesta,valor6_encuesta 
	  FROM ic_encuestas 
	  WHERE l_lang='".$_SESSION['idioma']."' ".$where." ORDER BY fecha_encuesta DESC";
$result=$db->Execute($sql);

while(!$result->EOF){

	list($id_encuesta,$fecha_encuesta,$nombre_encuesta,$comentario_encuesta,$pregunta1_encuesta,$valor1_encuesta,$pregunta2_encuesta,$valor2_encuesta,$pregunta3_encuesta,$valor3_encuesta,$pregunta4_encuesta,$valor4_encuesta,$pregunta5_encuesta,$valor5_encuesta,$pregunta6_encuesta,$valor6_encuesta)=select_format($result->fields);

	$nn1=intval(($pregunta1_encuesta!="")?$valor1_encuesta:"0");
	$nn2=intval(($pregunta2_encuesta!="")?$valor2_encuesta:"0");
	$nn3=intval(($pregunta3_encuesta!="")?$valor3_encuesta:"0");
	$nn4=intval(($pregunta4_encuesta!="")?$valor4_encuesta:"0");
	$nn5=intval(($pregunta5_encuesta!="")?$valor5_encuesta:"0");
	$nn6=intval(($pregunta6_encuesta!="")?$valor6_encuesta:"0");
	
	$ntotal=$nn1+$nn2+$nn3+$nn4+$nn5+$nn6; //contamos el nº total de votos
	$ndtotal = ($ntotal==0)?1:$ntotal;
?>

<table border="0" cellpadding=5 cellspacing="0" align="center" width="100%">
  <tr>
    <td colspan=3><strong><?=_RESULTADOS_ENCUESTA?> (<?=$fecha_encuesta?>)</strong><br /><br /><h1><?=$nombre_encuesta?></h1></td>
  </tr>
<?php
if($pregunta1_encuesta!=""){
?>
  <tr>
    <td colspan="3">&nbsp;</td>
  </tr>
  <tr>
    <td width="1" nowrap="nowrap"><?=$pregunta1_encuesta?></td>
    <td align="left" valign="middle">
	
	<table border="0" cellpadding="0" cellspacing="1" width="100%" class="barras_encuestas">
      <tr>
        <td><table border="0" cellpadding="0" cellspacing="0" width="<?=($nn1*100/$ndtotal)?>%" bgcolor="#FFFFFF" height="25">
          <tr>
            <td></td>
          </tr>
        </table>        </td>
      </tr>
    </table>    </td>
    <td width="1" align="center" valign="middle" nowrap="nowrap"><?=$nn1?></td>
  </tr>
<?php
}

if($pregunta2_encuesta!=""){
?>
  <tr>
    <td nowrap="nowrap"><?=$pregunta2_encuesta?></td>
    <td align="left" valign="middle">
	
	<table border="0" cellpadding="0" cellspacing="1" width="100%" class="barras_encuestas">
      <tr>
        <td width=100%><table border="0" cellpadding="0" cellspacing="0" width="<?=($nn2*100/$ndtotal)?>%" bgcolor="#FFFFFF" height="25">
          <tr>
            <td></td>
          </tr>
        </table>        </td>
      </tr>
    </table>    </td>
    <td align="center" valign="middle" nowrap="nowrap"><?=$nn2?></td>
  </tr>
<?php
}

if($pregunta3_encuesta!=""){
?>
  <tr>
    <td nowrap="nowrap"><?=$pregunta3_encuesta?></td>
    <td align="left" valign="middle">
	
	<table border="0" cellpadding="0" cellspacing="1" width="100%" class="barras_encuestas">
      <tr>
        <td width=100%><table border="0" cellpadding="0" cellspacing="0" width="<?=($nn3*100/$ndtotal)?>%" bgcolor="#FFFFFF" height="25">
          <tr>
            <td></td>
          </tr>
        </table>        </td>
      </tr>
    </table>    </td>
    <td align="center" valign="middle" nowrap="nowrap"><?=$nn3?></td>
  </tr>
<?php
}

if($pregunta4_encuesta!=""){
?>
  <tr>
    <td nowrap="nowrap"><?=$pregunta4_encuesta?></td>
    <td align="left" valign="middle">
	
	<table border="0" cellpadding="0" cellspacing="1" width="100%" class="barras_encuestas">
      <tr>
        <td width=100%><table border="0" cellpadding="0" cellspacing="0" width="<?=($nn4*100/$ndtotal)?>%" bgcolor="#FFFFFF" height="25">
          <tr>
            <td></td>
          </tr>
        </table>        </td>
      </tr>
    </table>    </td>
    <td align="center" valign="middle" nowrap="nowrap"><?=$nn4?></td>
  </tr>
<?php
}

if($pregunta5_encuesta!=""){
?>
  <tr>
    <td nowrap="nowrap"><?=$pregunta5_encuesta?></td>
    <td align="left" valign="middle">
	
	<table border="0" cellpadding="0" cellspacing="1" width="100%" class="barras_encuestas">
      <tr>
        <td width=100%><table border="0" cellpadding="0" cellspacing="0" width="<?=($nn5*100/$ndtotal)?>" bgcolor="#FFFFFF" height="25">
          <tr>
            <td></td>
          </tr>
        </table>        </td>
      </tr>
    </table>    </td>
    <td align="center" valign="middle" nowrap="nowrap"><?=$nn5?></td>
  </tr>
<?php
}

if($pregunta6_encuesta!=""){
?>
  <tr>
    <td nowrap="nowrap"><?=$pregunta6_encuesta?></td>
    <td align="left" valign="middle">
	
	<table border="0" cellpadding="0" cellspacing="1" width="100%" class="barras_encuestas">
      <tr>
        <td width=100%><table border="0" cellpadding="0" cellspacing="0" width="<?=($nn6*100/$ndtotal)?>%" bgcolor="#FFFFFF" height="25">
          <tr>
            <td></td>
          </tr>
        </table>        </td>
      </tr>
    </table>    </td>
    <td align="center" valign="middle" nowrap="nowrap"><?=$nn6?></td>
  </tr>
<?php
}
?>
  <tr>
    <td colspan=3>&nbsp;</td>
  </tr>
  <tr>
    <td colspan=3><?=_TOTAL_VOTOS_ENCUESTA?> <strong><?=$ntotal?></strong></td>
  </tr>
  <tr>
    <td colspan=3><i><?=$comentario_encuesta?></i></td>
  </tr>
</table>
<br /><br />
<?php

	if($id_item!=""){
		//include ("comentario.php");
	}

	$result->MoveNext();
}
echo "<br /><center>| <a href='encuestas.html'>"._VER_ENCUESTAS."</a> | <a href='lista-encuestas.html'>"._BUSCAR_ENCUESTA."</a> | <a href='index.html'>"._INICIO_ENCUESTA."</a> |</center>";



//----------------------------------------------------------------------------------------------
}
?>