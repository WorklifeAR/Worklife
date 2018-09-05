function nuevoAjax()
{ 
	/* Crea el objeto AJAX. Esta funcion es generica para cualquier utilidad de este tipo */
	var xmlhttp=false; 
	try 
	{ 
		// Creacion del objeto AJAX para navegadores no IE
		xmlhttp=new ActiveXObject("Msxml2.XMLHTTP"); 
	}
	catch(e)
	{ 
		try
		{ 
			// Creacion del objet AJAX para IE 
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP"); 
		} 
		catch(E) { xmlhttp=false; }
	}
	if (!xmlhttp && typeof XMLHttpRequest!="undefined") { xmlhttp=new XMLHttpRequest(); } 

	return xmlhttp; 
}

//--------------------------------------------------------------------

	var ajax=nuevoAjax();
	ajax.open("POST", "../../../../js/ficheros.php?", false);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("opcion=archivos");
	
	var nombreArchivo = "";
	//ajax.onreadystatechange=function()
	//{	
	if (ajax.readyState==4)
	{
		if(ajax.responseText)
		{
			nombreArchivo = ajax.responseText.split(",");
		}else{
			alert("No existen archivos.");
		}
	}
	//};
	
//--------------------------------------------------------------------

var formatoImg = new Array("PNG","GIF","JPG","JPEG","BMP","ICO","TIF");
var count = 0;
var tinyMCEImageList = new Array();

for(i=0;i<nombreArchivo.length;i++){	
	var soloNombre = nombreArchivo[i].split(".");
	
	for(j=0;j<formatoImg.length;j++){	
		
		if(soloNombre[soloNombre.length-1].toUpperCase()==formatoImg[j]){
			
			tinyMCEImageList[count] = new Array(soloNombre[0],"images/"+nombreArchivo[i]);			
			count++;
		}
	}
}

var formatoMedia = new Array("SWF","MPEG","AVI","WMP","MVO");
var count = 0;
var tinyMCEMediaList = new Array();

for(i=0;i<nombreArchivo.length;i++){	
	var soloNombre = nombreArchivo[i].split(".");
	
	for(j=0;j<formatoMedia.length;j++){	
		
		if(soloNombre[soloNombre.length-1].toUpperCase()==formatoMedia[j]){
			
			tinyMCEMediaList[count] = new Array(soloNombre[0],"images/"+nombreArchivo[i]);			
			count++;
		}
	}
}

var formatoFlash = new Array("SWF");
var count = 0;
var tinyMCEFlashList = new Array();

for(i=0;i<nombreArchivo.length;i++){	
	var soloNombre = nombreArchivo[i].split(".");
	
	for(j=0;j<formatoFlash.length;j++){	
		
		if(soloNombre[soloNombre.length-1].toUpperCase()==formatoFlash[j]){
			
			tinyMCEFlashList[count] = new Array(soloNombre[0],"images/"+nombreArchivo[i]);			
			count++;
		}
	}
}

//------------------------------------------

	var ajax=nuevoAjax();
	ajax.open("POST", "../../../../js/links.php?", false);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("opcion=paginas");
	
	var nombrePagina = "";

	if (ajax.readyState==4)
	{
		if(ajax.responseText)
		{
			nombrePagina = ajax.responseText.split(";");
		}else{
			alert("No existen link a relacionar.");
		}
	}else{
		alert("Ocurrio un error al momento de generar la consulta.");
	}
	

var count = 0;
var tinyMCELinkList = new Array();

for(i=0;i<nombrePagina.length;i++){
	var nombreUrl = nombrePagina[i].split("!");
	
	tinyMCELinkList[count] = new Array(""+nombreUrl[1],""+nombreUrl[0]);
	count++;
}
