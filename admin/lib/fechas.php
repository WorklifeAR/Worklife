<?php
/******************************************************************************************************************
FUNCIONES DEL MANEJO DE FECHAS
1. mostrar_hora($segundos) - Muestra hora enviada en segundos.
2. formato_dias($dia) - Muestra el dia en texto enviando el numero
3. formato_mes($mes) - Muestra el mes en texto enviando el numero
4. dias_entre_fechas($fch1, $fch2) - Muestra la cantidad de dias entre dos fechas
5. getTiempo() - Captura el inicio de una ejecucion
6. formato_fecha($fecha, $separador, $formato_origen, $formato_final)- Devuelve el formato de fecha solicitado
******************************************************************************************************************/

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function mostrar_hora($segundos)
{
	if(bcmod($segundos, 3600)==0)
	{
		$hora=$segundos/3600;
		$minutos="00";
		
	}
	elseif (bcmod($segundos, 3600)!=0)
	{	
		$minutes = bcmod($segundos, 3600);
		$hour=$segundos-$minutes;
		$hora=$hour/3600;
		$minutos = $minutes/60;
		
	}
	return $hora.":".$minutos;
	 
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function formato_dias($dia){
	$diasSemana = array("Mon"=>"Lunes","Tue"=>"Martes","Wed"=>"Miercoles","Thu"=>"Jueves","Fri"=>"Viernes","Sat"=>"Sabado","Sun"=>"Domingo");
	
	return $diasSemana[$dia];
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function formato_mes($mes){
	
	$mes_ano = array("01"=>"Ene",
					 "02"=>"Feb",
					 "03"=>"Mar",
					 "04"=>"Abr",
					 "05"=>"May",
					 "06"=>"Jun",
					 "07"=>"Jul",
					 "08"=>"Ago",
					 "09"=>"Sep",
					 "10"=>"Oct",
					 "11"=>"Nov",
					 "12"=>"Dic");
	
	return $mes_ano[$mes];
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function dias_entre_fechas($fch1, $fch2){
	
	
	$fch1 = explode("-", $fch1);
	$fch2 = explode("-", $fch2);
	
	//defino fecha 1
	$ano1 = $fch1[0];
	$mes1 = round($fch1[1]);
	$dia1 = round($fch1[2]);
	
	//defino fecha 2
	$ano2 = $fch2[0];
	$mes2 = round($fch2[1]);
	$dia2 = round($fch2[2]);
	
	//calculo timestam de las dos fechas
	$timestamp1 = mktime(0,0,0,$mes1,$dia1,$ano1);
	$timestamp2 = mktime(4,12,0,$mes2,$dia2,$ano2);
	
	//resto a una fecha la otra
	$segundos_diferencia = $timestamp1 - $timestamp2;
	//echo $segundos_diferencia;
	
	//convierto segundos en días
	$dias_diferencia = $segundos_diferencia / (60 * 60 * 24);
	
	//obtengo el valor absoulto de los días (quito el posible signo negativo)
	$dias_diferencia = abs($dias_diferencia);
	
	//quito los decimales a los días de diferencia
	return $dias_diferencia = floor($dias_diferencia);
}


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function getTiempo() { 
	list($usec, $sec) = explode(" ",microtime()); 
	return ((float)$usec + (float)$sec); 
} 

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function formato_fecha($fecha, $separador, $formato_origen, $formato_final){
	$new_fecha = "";
	
	if($formato_origen=="LATIN" && $formato_final=="USA"){
		$fecha = explode("-", $fecha);
		$new_fecha = $fecha[1] . $separador . $fecha[2] . $separador . $fecha[0];
	}elseif($formato_origen=="USA" && $formato_final=="LATIN"){
		$fecha = explode("-", $fecha);
		$new_fecha = $fecha[2] . $separador . $fecha[0] . $separador . $fecha[1];
	}elseif($formato_origen=="DEFAULT" && $formato_final=="LATIN"){
		$fecha = explode("-", $fecha);
		$new_fecha = $fecha[2] . $separador . $fecha[1] . $separador . $fecha[0];
	}elseif($formato_origen=="DEFAULT" && $formato_final=="USA"){
		$fecha = explode("-", $fecha);
		$new_fecha = $fecha[1] . $separador . $fecha[2] . $separador . $fecha[0];
	}else{
		$new_fecha = $fecha;	
	}
	
	return $new_fecha;
}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

?>