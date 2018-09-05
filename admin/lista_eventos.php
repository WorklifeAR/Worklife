<?php
	session_permiso("115");
	
	
	$variable_metodo = variables_metodo('palabra,fecha,fecha_1');

	$palabra		=	$variable_metodo[0];
	$fecha			=	$variable_metodo[1];
	$fecha_1		=	$variable_metodo[2];
	
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$pal = "";
	if($palabra!=""){
		$pal = " AND nombre_evento LIKE '%".$palabra."%' OR descripcion LIKE '%".$palabra."%'";
	}
	
	$fec = "";
	if($fecha!=""){
		$fec = " AND fecha_evento > '".$fecha."' AND fecha_evento < '".$fecha_1."' ";
	}
			
	$sql = "SELECT id_evento, fecha_evento, hora_evento, nombre_evento, ciudad_evento, lugar_evento
			FROM ic_eventos
			WHERE 1=1 ".$pal." ".$fec.
			"ORDER BY fecha_evento, hora_evento, id_evento";
	$result=$db->Execute($sql);
	
?>

<table border="0" align="center" cellpadding="5" cellspacing="0">
  <form action="" name="eventoB" id="eventoB" method="post">
    <tr>
      <td colspan="6" class="titulo"><div align="center"><strong>
        Events List
      </strong></div></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td><strong>Date</strong> </td>
      <td>From</td>
      <td><input name="fecha" type="text" size="9" id="fecha" value="<?=$fecha?>" />
	  <a href="javascript:;" onclick="showCalendar('',document.getElementById('fecha'),document.getElementById('fecha'),'','holder10',-10,5,1)" style="display: inline-block; vertical-align: middle"> 
	<img src="admin/calendario/icono_calendario.jpg"  width="18" height="17" border="0" hspace="2" /></a>
	  </td>
      <td>&nbsp;</td>
      <td><strong>Description</strong></td>
      <td>
          <input name="bono" type="text" size="15" id="bono" value="<?=$palabra?>" />      </td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>To</td>
      <td><input name="fecha_1" type="text" size="9" id="fecha_1" value="<?=$fecha_1?>" />
	   <a href="javascript:;" onclick="showCalendar('',document.getElementById('fecha_1'),document.getElementById('fecha_1'),'','holder10',-10,5,1)" style="display: inline-block; vertical-align: middle"> 
	<img src="admin/calendario/icono_calendario.jpg"  width="18" height="17" border="0" hspace="2" /></a>
	  </td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td colspan="6" align="right"><input name="Submit" type="submit" value="Buscar" /></td>
    </tr>
  </form>
</table>

<p>&nbsp;</p>

<!-- ************ Listado ************ -->

<table border="1" align="center" cellpadding="5" cellspacing="0" width="90%" bordercolor="#cccccc" style="border-collapse: collapse;" id="tabla_bonos">
    <tr>
      <th colspan="5" class="titulo" ><div align="right"><a href="admin.php?mod=116">Create New Event</a></div></th>
    </tr>
    <tr>
      <th bgcolor="#f4f4f4">Date</th>
      <th bgcolor="#f4f4f4">Title</th>
      <th bgcolor="#f4f4f4">Hour</th>
      <th bgcolor="#f4f4f4">Place</th>
      <th bgcolor="#f4f4f4">City</th>
    </tr>
<?php
while(!$result->EOF){
	list($id_eve, $fecha_eve , $hora_eve , $nombre_eve, $ciudad_eve, $lugar_eve)=select_format($result->fields);

?>
    <tr>
      <td valign="top"><div align="center"><a href="admin.php?mod=116&id_eve=<?=$id_eve?>"><?=$fecha_eve?></a></div></td>
      <td valign="top"><div align="left"><a href="admin.php?mod=116&id_eve=<?=$id_eve?>"><?=$nombre_eve?></a></div></td>
      <td valign="top"><div align="center"><?=$hora_eve?></div></td>
      <td valign="top"><?=$lugar_eve?></td>
      <td valign="top"><?=$ciudad_eve?></td>      
    </tr>
<?php
$result->MoveNext();
}
?>
</table>
