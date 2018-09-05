function nuevoAjax()
{ 
	/* Crea el objeto AJAX. Esta funcion es generica para cualquier utilidad de este tipo */
	var xmlhttp=false; 
	try{ 
		// Creacion del objeto AJAX para navegadores no IE
		xmlhttp=new ActiveXObject("Msxml2.XMLHTTP"); 
	}catch(e){ 
		try{ 
			// Creacion del objet AJAX para IE 
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP"); 
		}catch(E) { xmlhttp=false; }
	}
	
	if (!xmlhttp && typeof XMLHttpRequest!="undefined") { xmlhttp=new XMLHttpRequest(); } 

	return xmlhttp; 
}

//-----------------------------------------

function cargarDatosCalendario(mesAvance, anoAvance){
	var Calendar = new Date();
	var desde = "";
	var hasta = "";
	
	var avanceAno="";
	var avanceMes="";
	
	if(mesAvance!="" || anoAvance!=""){
		var anoActal = document.getElementById('ultimoAno').value;
		var mesActal = document.getElementById('ultimoMes').value;
		
		if(mesActal=='11' && mesAvance=='1'){
			mesActal = -1;
			anoActal = Math.round(anoActal)+Math.round(1);
		}
		
		if(mesActal=='0' && mesAvance=='-1'){
        	mesActal = 12;
			anoActal = Math.round(anoActal)+Math.round(-1);
		}
		
		avanceAno=Math.round(Math.round(anoActal)+Math.round(anoAvance));
		avanceMes=Math.round(Math.round(mesActal)+Math.round(mesAvance));
		
		desde = avanceAno+"-"+(Math.round(avanceMes)+1)+"-01";
		hasta = avanceAno+"-"+(Math.round(avanceMes)+1)+"-31";
		
		document.getElementById('ultimoAno').value = avanceAno;
		document.getElementById('ultimoMes').value = avanceMes;
	
	}else{
		desde = Calendar.getFullYear()+"-"+(Calendar.getMonth()+1)+"-01";
		hasta = Calendar.getFullYear()+"-"+(Calendar.getMonth()+1)+"-31";
	}
			
	var ajax=nuevoAjax();
	ajax.open("POST", "calendario_eventos/calendario_eventos.php?", false);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");	
	ajax.send("desde="+desde+"&hasta="+hasta);
	
	var variables = "";

	if (ajax.readyState==4)
	{
		if(ajax.responseText)
		{
			variables = ajax.responseText.split("|");
			if(avanceAno!="" || avanceMes!=""){
				calendarioEventos(avanceAno, avanceMes, Calendar.getDate(), variables[0], variables[1], variables[2], desde, hasta);
			}else{
				calendarioEventos(Calendar.getFullYear(), Calendar.getMonth(), Calendar.getDate(), variables[0], variables[1], variables[2], desde, hasta);
			}
		}else{
			if(avanceAno!="" || avanceMes!=""){
				calendarioEventos(avanceAno, avanceMes, Calendar.getDate(), "", "", "", desde, hasta);
			}else{
				calendarioEventos(Calendar.getFullYear(), Calendar.getMonth(), Calendar.getDate(), "", "", "", desde, hasta);
			}
		}
	}
}

//-----------------------------------------

function calendarioEventos(ano, mes, dia, fechasEventos, comentarioEventos, linkEventos, desde, hasta){

	//  SET ARRAYS
	var day_of_week = new Array('L','M','M','J','V','S','D');
	var month_of_year = new Array('Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre');
	
	//  DECLARE AND INITIALIZE VARIABLES
	var Calendar = new Date();
	
	var year = ano; //Calendar.getYear();	    // Returns year
	var month = mes; //Calendar.getMonth();    // Returns month (0-11)
	var today = dia; //Calendar.getDate();    // Returns day (1-31)
	var weekday = Calendar.getDay();    // Returns day (1-31)
	
	var DAYS_OF_WEEK = 7;    // "constant" for number of days in a week
	var DAYS_OF_MONTH = 31;    // "constant" for number of days in a month
	var cal;    // Used for printing
	
	Calendar.setDate(1);    // Start the calendar day at '1'
	Calendar.setMonth(month);    // Start the calendar month at now
	Calendar.setYear(ano);
        
	var fechasEventos = fechasEventos;
	var comentarioEventos = comentarioEventos;
	var linkEventos = linkEventos;
	
	/* VARIABLES FOR FORMATTING
	NOTE: You can format the 'BORDER', 'BGCOLOR', 'CELLPADDING', 'BORDERCOLOR'
		  tags to customize your caledanr's look. */
	
	var TR_start = '<TR>';
	var TR_end = '</TR>';
	var highlight_start = '<TD><TABLE CELLSPACING=0 BORDER=0 BGCOLOR=DEDEFF BORDERCOLOR=CCCCCC style="border-collapse:collapse"><TR><TD WIDTH=18 class="calendarioDiaActual"><CENTER>';
	var highlight_end   = '</CENTER></TD></TR></TABLE>';
	var TD_start = '<TD WIDTH="20"><CENTER>';
	var TD_end = '</CENTER></TD>';
	
	/* BEGIN CODE FOR CALENDAR
	NOTE: You can format the 'BORDER', 'BGCOLOR', 'CELLPADDING', 'BORDERCOLOR'
	tags to customize your calendar's look.*/
	
	cal =  '<TABLE BORDER=0 width="100%" BORDERCOLOR="#CCCCCC" STYLE="border-collapse:collapse;" CELLSPACING=0 CELLPADDING=0 ><TR><TD>';
	cal += '<TABLE BORDER=1 width="100%" BORDERCOLOR="#CCCCCC" STYLE="border-collapse:collapse;" CELLSPACING=0 CELLPADDING=2>' + TR_start;
	cal += '<TD COLSPAN="' + DAYS_OF_WEEK + '" BGCOLOR="#EFEFEF">';
        cal +='<table width="100%" border="0" cellspacing="2" cellpadding="0"><tr>';
        cal +='<td width="10"><a href="javascript: cargarDatosCalendario(\'0\', \'-1\');"> &laquo; </a></td>';
		cal +='<td width="10"></td>';
        cal +='<td width="10"><a href="javascript: cargarDatosCalendario(\'-1\', \'0\');"> &lsaquo; </a></td>';
        cal +='<td>';
        cal +='<CENTER><B>' + month_of_year[month]  + '   ' + year + '</B></center>';
        cal +='</td>';
        cal +='<td width="10"><a href="javascript: cargarDatosCalendario(\'1\', \'0\');"> &rsaquo; </a></td>';
		cal +='<td width="10"></td>';
        cal +='<td width="10"><a href="javascript: cargarDatosCalendario(\'0\', \'1\');"> &raquo; </a></td>';
        cal +='</tr></table>';
	
	cal += TD_end + TR_end + TR_start;
	
	//   DO NOT EDIT BELOW THIS POINT  //
	
	// LOOPS FOR EACH DAY OF WEEK
	for(index=0; index < DAYS_OF_WEEK; index++)
	{
	
	// BOLD TODAY'S DAY OF WEEK
	if(weekday == index)
	cal += TD_start + '<div style="background-color: #C1C1C1; font-size:14px;"><B>' + day_of_week[index] + '</div></B>' + TD_end;
	
	// PRINTS DAY
	else
	cal += TD_start + '<div style="background-color: #C1C1C1; font-size:14px;"><B>' + day_of_week[index] + '</div></B>' + TD_end;
	}
	
	cal += TD_end + TR_end;
	cal += TR_start;
	
	// FILL IN BLANK GAPS UNTIL TODAY'S DAY
	var d_i_a = Calendar.getDay();
	
	if(d_i_a==0){
		d_i_a = 7;
	}
	
	for(index=1; index < d_i_a; index++){
		cal += TD_start + '  ' + TD_end;
	}
	
	// LOOPS FOR EACH DAY IN CALENDAR
	for(index=0; index < DAYS_OF_MONTH; index++)
	{
	if( Calendar.getDate() > index )
	{
	  // RETURNS THE NEXT DAY TO PRINT
	  week_day =Calendar.getDay();
	
	  // START NEW ROW FOR FIRST DAY OF WEEK
	  if(week_day == 1)
	  cal += TR_start;
	
	  if(week_day != DAYS_OF_WEEK)
	  {
	
	  // SET VARIABLE INSIDE LOOP FOR INCREMENTING PURPOSES
	  var day  = Calendar.getDate();
	
	  	// HIGHLIGHT TODAY'S DATE
		if( today==Calendar.getDate() ){
			
			var strarLinkCalendario = "";
			var endLinkCalendario = "";
			
			var fechEven = fechasEventos.split(",");
			var comEven= comentarioEventos.split(",");
			var idEven= linkEventos.split(",");
			
			for(i=0; i<fechEven.length; i++){
				if(fechEven[i]==day){
					strarLinkCalendario="<a href='ver.php/mod/eventos/id_item/"+idEven[i]+"/"+comEven[i]+"' title='"+comEven[i]+"' class='linkEvento'>";
					endLinkCalendario="</a>";
					break;
				}
			}
			
			cal += highlight_start + '<div class="diaEvento">' + strarLinkCalendario + day + endLinkCalendario + '</div>' + highlight_end + TD_end;	
		}else{
		
			var strarLinkCalendario = "";
			var endLinkCalendario = "";
			
			var fechEven = fechasEventos.split(",");
			var comEven= comentarioEventos.split(",");
			var idEven= linkEventos.split(",");
			
			for(i=0; i<fechEven.length; i++){
				if(fechEven[i]==day){
					strarLinkCalendario="<a href='ver.php/mod/eventos/id_item/"+idEven[i]+"/"+comEven[i]+"' title='"+comEven[i]+"' class='linkEvento'>";
					endLinkCalendario="</span></a>";
					break;
				}
			}
			
			// PRINTS DAY
			//cal += TD_start + strarLinkCalendario + '<div class="diaEvento">' + day + '</div>' + endLinkCalendario + TD_end;
			cal += TD_start + '<div class="diaEvento">' + strarLinkCalendario + day + endLinkCalendario + '</div>' + TD_end;	
		}
	  }
	
	  // END ROW FOR LAST DAY OF WEEK
	  if(week_day == DAYS_OF_WEEK)
	    cal += TR_end;
	  }
	
	  // INCREMENTS UNTIL END OF THE MONTH
	  Calendar.setDate(Calendar.getDate()+1);
	
	}// end for loop
	
	cal += '</TD></TR></TABLE></TABLE><input name="ultimoAno" id="ultimoAno" type="hidden" value="'+year+'"><input name="ultimoMes" id="ultimoMes" type="hidden" value="'+month+'">';
	cal +='<p><br /><a href="eventos.html">Ver eventos</a></p>';
	
	//  PRINT CALENDARcal
	if(document.getElementById("calendario")){
		
		document.getElementById("calendario").innerHTML = cal;
	}
	//  End -->
}