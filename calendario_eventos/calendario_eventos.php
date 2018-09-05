<?php
$path = "adodb/adodb.inc.php";
include ("admin/var.php");
include ("conexion.php");
	
$variables_ver = variables_metodo("dia,mes,ano");
$dia= 				$variables_ver[0];
$mes= 				$variables_ver[1];
$ano= 				$variables_ver[2];
	
$tipo_semana = 1;
$tipo_mes = 0;

$MESCOMPLETO[1] = 'Enero';
$MESCOMPLETO[2] = 'Febrero';
$MESCOMPLETO[3] = 'Marzo';
$MESCOMPLETO[4] = 'Abril';
$MESCOMPLETO[5] = 'Mayo';
$MESCOMPLETO[6] = 'Junio';
$MESCOMPLETO[7] = 'Julio';
$MESCOMPLETO[8] = 'Agosto';
$MESCOMPLETO[9] = 'Septiembre';
$MESCOMPLETO[10] = 'Octubre';
$MESCOMPLETO[11] = 'Noviembre';
$MESCOMPLETO[12] = 'Diciembre';

$MESABREVIADO[1] = 'Ene';
$MESABREVIADO[2] = 'Feb';
$MESABREVIADO[3] = 'Mar';
$MESABREVIADO[4] = 'Abr';
$MESABREVIADO[5] = 'May';
$MESABREVIADO[6] = 'Jun';
$MESABREVIADO[7] = 'Jul';
$MESABREVIADO[8] = 'Ago';
$MESABREVIADO[9] = 'Sep';
$MESABREVIADO[10] = 'Oct';
$MESABREVIADO[11] = 'Nov';
$MESABREVIADO[12] = 'Dic';

$SEMANACOMPLETA[0] = 'Domingo';
$SEMANACOMPLETA[1] = 'Lunes';
$SEMANACOMPLETA[2] = 'Martes';
$SEMANACOMPLETA[3] = 'Miercoles';
$SEMANACOMPLETA[4] = 'Jueves';
$SEMANACOMPLETA[5] = 'Viernes';
$SEMANACOMPLETA[6] = 'Sabado';


$SEMANAABREVIADA[0] = 'Lun';
$SEMANAABREVIADA[1] = 'Mar';
$SEMANAABREVIADA[2] = 'Mie';
$SEMANAABREVIADA[3] = 'Jue';
$SEMANAABREVIADA[4] = 'Vie';
$SEMANAABREVIADA[5] = 'Sab';
$SEMANAABREVIADA[6] = 'Dom';

////////////////////////////////////
if($tipo_semana == 0){
	$ARRDIASSEMANA = $SEMANACOMPLETA;
}elseif($tipo_semana == 1){
	$ARRDIASSEMANA = $SEMANAABREVIADA;
}
if($tipo_mes == 0){
	$ARRMES = $MESCOMPLETO;
}elseif($tipo_mes == 1){
	$ARRMES = $MESABREVIADO;
}

if(!$dia) $dia = date('d');
if(!$mes) $mes = date('n');
if(!$ano) $ano = date('Y');

$TotalDiasMes = date('t',mktime(0,0,0,$mes,$dia,$ano));
$DiaSemanaEmpiezaMes = date('w',mktime(0,0,0,$mes,1,$ano));
$DiaSemanaTerminaMes = date('w',mktime(0,0,0,$mes,$TotalDiasMes,$ano));
$EmpiezaMesCalOffset = $DiaSemanaEmpiezaMes;
$TerminaMesCalOffset = 6 - $DiaSemanaTerminaMes;
$TotalDeCeldas = $TotalDiasMes + $DiaSemanaEmpiezaMes + $TerminaMesCalOffset;


if($mes == 1){
	$MesAnterior = 12;
	$MesSiguiente = $mes + 1;
	$AnoAnterior = $ano - 1;
	$AnoSiguiente = $ano;
}elseif($mes == 12){
	$MesAnterior = $mes - 1;
	$MesSiguiente = 1;
	$AnoAnterior = $ano;
	$AnoSiguiente = $ano + 1;
}else{
	$MesAnterior = $mes - 1;
	$MesSiguiente = $mes + 1;
	$AnoAnterior = $ano;
	$AnoSiguiente = $ano;
	$AnoAnteriorAno = $ano - 1;
	$AnoSiguienteAno = $ano + 1;
}

echo '<h1 class="mas_titulo">'._EVENTOS.'</h1><br><table align=center border=0 cellpadding=5 cellspacing=7 width="100%">
<tr>
<td colspan=10 align=center>
<div align="center">
<table border=0 align=center >
  <tr>
	<td width="40" align=center><a href="ver.php?mod=eventos&mes='.$mes.'&ano='.$AnoAnteriorAno.'">&laquo;&laquo;</a></td>
	<td width="40" align=center><a href="ver.php?mod=eventos&mes='.$MesAnterior.'&ano='.$AnoAnterior.'">&laquo;</a></td>
	<td width="100" align="center" nowrap><b>'.$ARRMES[$mes].' - '.$ano.'</b></td>
	<td width="40" align=center><a href="ver.php?mod=eventos&mes='.$MesSiguiente.'&ano='.$AnoSiguiente.'">&raquo;</a></td>
	<td width="40" align=center><a href="ver.php?mod=eventos&mes='.$mes.'&ano='.$AnoSiguienteAno.'">&raquo;&raquo;</a></td>
  </tr>
</table>
</div>
</td>
</tr>
<tr>';

foreach($ARRDIASSEMANA as $key){
	echo '<td bgcolor=#ccccff align=center><b>'.$key.'</b></td>';
}
echo '</tr>';

$b = "";
$c = "";

for($a=2;$a <= $TotalDeCeldas;$a++){
	if(!$b) $b = 0;
	if($b == 7) $b = 0;
	if($b == 0) echo '<tr>';
	if(!$c) $c = 1;
	if($a > $EmpiezaMesCalOffset && $c <= $TotalDiasMes){
		
		$sql="SELECT id_evento, nombre_evento, fecha_evento FROM ic_eventos WHERE fecha_evento = '".$ano."-".$mes."-".$c."' ";
		$result=$db->Execute($sql);
		
		$dia = $c;
		
		if($c == date('d') && $mes == date('m') && $ano == date('Y')){
			if(!$result->EOF){
				echo '<td bgcolor="#FFC40D" align=center>';
				
				list($id_evento, $nombre_evento, $fecha_evento)=select_format($result->fields);			
				echo "<a href='".organizar_nombre_link("eventos", $nombre_evento)."' title='".$nombre_evento."'>".$c."</a>";
				
				echo '</td>';
			}else{
				echo '<td bgcolor="#7A6AAF" align=center><font color="#ffffff">'.$dia.'</font></td>';
			}
		}elseif($b == 5 || $b == 6){
			if(!$result->EOF){
				echo '<td bgcolor="#FFC40D" align=center>';
				
				list($id_evento, $nombre_evento, $fecha_evento)=select_format($result->fields);			
				echo "<a href='".organizar_nombre_link("eventos", $nombre_evento)."' title='".$nombre_evento."'>".$c."</a>";
				
				echo '</td>';
			}else{
				echo '<td bgcolor="#dfdfdf" align=center>'.$dia.'</td>';
			}
		}else{
			if(!$result->EOF){
				echo '<td bgcolor="#FFC40D" align=center>';
				
				list($id_evento, $nombre_evento, $fecha_evento)=select_format($result->fields);			
				echo "<a href='".organizar_nombre_link("eventos", $nombre_evento)."' title='".$nombre_evento."'>".$c."</a>";
				
				echo '</td>';
			}else{
				echo '<td bgcolor="#cfcfcf" align=center>'.$dia.'</td>';
			}
		}
		$c++;
	}else{
		echo '<td> </td>';
	}
	if($b == 6) echo '</tr>';
	$b++;
}
echo '</table>';

//////////////////////////////////////////////////////

$sql = "SELECT 
		id_evento,nombre_evento,fecha_evento,hora_evento,lugar_evento,previa_evento,ciudad_evento,principal_evento
	FROM 
		ic_eventos
	WHERE l_lang='".$_SESSION['idioma']."' AND fecha_evento>'".date("Y-m-d")."' ORDER BY fecha_evento DESC";
$result=$db->Execute($sql);
?> 
<br /><br />

<h2>Upcoming Events</h2>
<br />
<hr />
<br /><br />
<!-- ************ Listado ************ -->

<table width="100%" border="0" cellpadding="5" cellspacing="5">
<?php

$cont = 0;

while(!$result->EOF){
	list($id_evento,$nombre_evento,$fecha_evento,$hora_evento,$lugar_evento,$previa_evento,$ciudad_evento,$principal_evento)=select_format($result->fields);
	
	$color = "#ffffff";
	
	if(bcmod($cont, 2)==0){
		$color = "#F4F4F4";
	}
?>
    <tr>
      <td valign="top" bgcolor="<?=$color?>">
      <div class="titulo_evento"><a href="<?=organizar_nombre_link("eventos", $nombre_evento)?>"><?=$nombre_evento?></a></div>
      <div class="info_evento"><?=formato_fecha($fecha_evento, "/", "LATIN", "USA")?> - <?=$ciudad_evento?> ( <?=$lugar_evento?> )( <?=_HORA?>: <?=$hora_evento." ".$principal_evento?> )</div>
      <div><?=$previa_evento?></div>
      </td>
    </tr>
<?php
	$cont++;
	$result->MoveNext();
}
?>
</table>