<?php
$path = "adodb/adodb.inc.php";
include ("admin/var.php");
include ("conexion.php");

	//Variables para la carga de un modulo especifico
	$variables_ver = variables_metodo("id_item");
	$id_item= 				$variables_ver[0];
	
if($id_item==""){	

	$variable_metodo = variables_metodo('fecha_desde,fecha_hasta,palabra');
	$fecha_desde		=	$variable_metodo[0];
	$fecha_hasta		=	$variable_metodo[1];
	$palabra			=	$variable_metodo[2];
	
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	$fec0 = "";
	$fec1 = "";
	$fec2 = "";
	if($fecha_desde!=""){
		$fec1 = " AND fecha_evento >= '".$fecha_desde."' ";
	}elseif($fecha_hasta!=""){
		$fec2 = " AND fecha_evento <= '".$fecha_hasta."' ";
	}else{	
		$fec0 = " AND fecha_evento >= '".date('Y-m-d')."' ";
	}

	$pal = "";
	if($palabra!=""){
		$pal = " AND (nombre_evento LIKE '%".$palabra."%' OR previa_evento LIKE '%".$palabra."%' OR descripcion_evento LIKE '%".$palabra."%' )";
	}

	$sql = "SELECT 
				id_evento,nombre_evento,fecha_evento,hora_evento,lugar_evento,previa_evento,ciudad_evento,principal_evento
			FROM 
				ic_eventos
			WHERE l_lang='".$_SESSION['idioma']."'  ".$fec1." ".$fec2." ".$fec0." ".$pal." ORDER BY fecha_evento DESC";
	$result=$db->Execute($sql);
?>


<div class="redondeado" style="margin-bottom:30px; padding:10px 15px;">
<table border="0" cellpadding="3" cellspacing="2">
  <form action="" name="verEventos" id="verEventos" method="post">
	<tr>
      <td ><strong><?=_FECHA_DESDE?></strong> </td>
      <td bgcolor="#FFFFFF" ><input name="fecha_desde" style="border:none;" type="text" size="9" id="fecha_desde" value="<?=$fecha_desde?>" />
          <a href="javascript:;" onclick="showCalendar('',document.getElementById('fecha_desde'),document.getElementById('fecha_desde'),'','holder10',-10,5,1)" style="display: inline-block; vertical-align: middle"> 
	<img src="admin/calendario/icono_calendario.jpg"  width="18" height="17" border="0" hspace="2" /></a></td>
      <td ><strong>
        To
      </strong></td>
      <td bgcolor="#FFFFFF"><input name="fecha_hasta" type="text" style="border:none;" size="9" id="fecha_hasta" value="<?=$fecha_hasta?>" />
          <a href="javascript:;" onclick="showCalendar('',document.getElementById('fecha_hasta'),document.getElementById('fecha_hasta'),'','holder10',-10,5,1)" style="display: inline-block; vertical-align: middle"> 
	<img src="admin/calendario/icono_calendario.jpg"  width="18" height="17" border="0" hspace="2" /></a></td>
      </tr>
    <tr>
      <td colspan="5">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="5"><input name="Submit" type="submit" value="<?=_BUSCAR_EVENTOS?>"/></td>
    </tr>  </form>
</table>
</div>

<!-- ************ Listado ************ -->

<?php

$cont = 0;

while(!$result->EOF){
	list($id_evento,$nombre_evento,$fecha_evento,$hora_evento,$lugar_evento,$previa_evento,$ciudad_evento,$principal_evento)=select_format($result->fields);
	
?>

    <div style="padding:10px 15px;">
      <div class="fecha_evento"><?=formato_fecha($fecha_evento, "/", "DEFAULT", "LATIN")?></div>
      <div class="titulo_evento"><a href="<?=organizar_nombre_link("eventos", $nombre_evento)?>"><?=$nombre_evento?></a></div>
      <div class="info_evento"><?=$ciudad_evento?> ( <?=$lugar_evento?> )( <?=_HORA?>: <?=$hora_evento." ".$principal_evento?> )</div>
      <div><?=$previa_evento?></div>
    </div>
    <div style="margin:5px 0 20px 0;"><img src="images/linea.png" style="width:100%; min-height:auto;"/></div>

<?php
	$cont++;
	$result->MoveNext();
}

}else{
	$variable_metodo = variables_metodo('id_item');
	$id_evento		=	$variable_metodo[0];

	$sql = "SELECT 
				id_evento,nombre_evento,fecha_evento,hora_evento,descripcion_evento,lugar_evento,ciudad_evento,principal_evento
			FROM 
				ic_eventos
			WHERE id_evento='".$id_evento."' ";
	$result=$db->Execute($sql);

	list($id_evento,$nombre_evento,$fecha_evento,$hora_evento,$descripcion_evento,$lugar_evento,$ciudad_evento,$principal_evento)=select_format($result->fields);
?>
<table width="100%" border="0" cellspacing="0" cellpadding="3">     
      <tr>
        <td><span class="mas_titulo">
          <?=$nombre_evento?>
        </span></td>
      </tr>
       <tr>
        <td><div align="right"><a href="eventos.html">
          <?=_VOLVER_LISTADO?>
        </a></div></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td><table width="100%" border="0" align="center" cellpadding="4" cellspacing="3">
            <tr>
              <td bgcolor="#F4f4f4"><b>
                <?=_FECHA?>
                :</b>
                <?=$fecha_evento?></td>
              <td bgcolor="#F4f4f4"><b>
                <?=_HORA?>
                :</b>
                <?=$hora_evento?></td>
            </tr>
            <tr>
              <td colspan="2" bgcolor="#F4f4f4"><b>
                <?=_CIUDAD?>
                :</b>
                <?=$ciudad_evento?></td>
            </tr>
            <tr>
              <td colspan="2" bgcolor="#F4f4f4"><b>
                <?=_LUGAR_EVENTO?>
                :</b>
                <?=$lugar_evento?></td>
            </tr>
        </table></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td><?=$descripcion_evento?></td>
      </tr>
    </table>
<?php
//include("comentario.php");

}
?>