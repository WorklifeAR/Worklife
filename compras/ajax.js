var url = 'http://74.124.203.27/~webact6/clients/comprayteenvio/';

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

//------------------------------------------------------------------------------------------------------------

function adicionarProductoCarrito(idProducto){
	
	actualizarInfoEnvio();
	activarPersiana(true);
	
	var ajax=nuevoAjax();
	
	//------------------------------------------------------
	var idProducto = document.getElementById("carrito_id_"+idProducto).value;
	var cantidadProducto = document.getElementById("carrito_cantidad_"+idProducto).value;
	var referencia = "";
	var color = "";
	
	if(document.getElementById("referencias_"+idProducto)){
		if(document.getElementById("referencias_"+idProducto).value!=""){
			referencia = "" + document.getElementById("referencias_"+idProducto).value;
		}else{
			alert("Please select a reference");
			activarPersiana(false);	
			return;
		}
	}
	
	if(document.getElementById("color")){
		if(document.getElementById("color").value!=""){
			color = "Color: " + document.getElementById("color").options[document.getElementById("color").selectedIndex].text;
		}else{
			alert("Please select Color");	
			activarPersiana(false);	
			return;
		}
	}
	
	//------------------------------------------------------
					
	ajax.open("POST", "compras/adicionar.php?", false);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	var envio = "opcion=carrito_compras&cantidad_producto="+cantidadProducto+"&id_producto="+idProducto+"&referencia="+referencia;
	ajax.send(envio);
	
	if (ajax.readyState==4)
	{
		if(ajax.responseText)
		{
			var elements = ajax.responseText.split("**");;

			if (/OK/.test(elements[0])){
				window.location=url+"ver.php?mod=carrito";
			}else{
				alert(elements[1]);
			}
		}else{
			alert("Error"+ajax.responseText);
		}
	}
}

//------------------------------------------------------------------------------------------------------

function removerProductoCarrito(idProducto){
	
	actualizarInfoEnvio();
	
	var ajax=nuevoAjax();
	
	//------------------------------------------------------
	var idProducto = document.getElementById("carrito_id_"+idProducto).value;
	//------------------------------------------------------
					
	ajax.open("POST", "compras/eliminar.php?", false);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("opcion=carrito_compras&id_producto="+idProducto);
	
	if (ajax.readyState==4)
	{
		if(ajax.responseText)
		{
			alert(ajax.responseText);
			window.location.reload();
		}else{
			alert("Error");
		}
	}
}

//------------------------------------------------------------------------------------------------------

function confirmarCompraProducto(metodo){
	
	activarPersiana(true);

	actualizarInfoEnvio();
	
	var ajax=nuevoAjax();
	
	funcionBotones(true, 0);
	
	//------------------------------------------------------
	
	if(document.getElementById("terminos").checked===false){
		alert("You must accept the terms and conditions");
		activarPersiana(false);
		return;
	}
	
	var regalo = "";
	
	if(document.getElementById("regalo").checked==true){
		regalo = "S"
	}
	
	//------------------------------------------------------
		
	//var bloqueo = DetectarBloqueador();
	//DetectarBloqueador();
	//if(!bloqueo){
		
		ajax.open("POST", "compras/confirmar_pedido.php?", false);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("opcion=registrar_compra&forma_pago="+metodo+"&regalo="+regalo);
		
		if (ajax.readyState==4)
		{
			if(ajax.responseText)
			{
				var return_guardar = ajax.responseText;
				
				var return_metodo = return_guardar.split("|");
								
				if(return_metodo[0].replace(/^\s*|\s*$/g,"")=="exito"){
					
					//Si es un sistema de pago de solo registro de pedido
					if(return_metodo[1]=="0"){
						alert(return_metodo[2]);
						window.location=url+"ver.php?mod=carrito";
					}else{
						alert(return_metodo[2]);
						window.location=return_metodo[1];	
						//document.getElementById("enviarPago").action=return_metodo[1];
						//document.getElementById("enviarPago").submit();
						//window.open(return_metodo[1], 'Pay','location=no,resize=yes,toolbar=no');
					}
				}else{
					alert("An error has occurred: "+return_metodo[1]);
					activarPersiana(false);
				}
			}else{
				alert("Error registering the order");
				activarPersiana(false);
			}
		}
	//}
	
	funcionBotones(false, 0);
	
	activarPersiana(false);
}

//------------------------------------------------------------------------------------------------------

function asignarEnvio(campo){
	var dato = campo.value;
	
	dato = dato.split("|");
	
	if(document.getElementById("fields_metodo_seleccionado")){
		document.getElementById("fields_metodo_seleccionado").value = dato[0];
		document.getElementById("detalle_envio").value = dato[1];
		document.getElementById("valor_envio").value = dato[2];
	}
}

//------------------------------------------------------------------------------------------------------

function seleccionarMetodoEnvio(){
	
	var ajax=nuevoAjax();
	
	//------------------------------------------------------
	
	var metodo_seleccionado = document.getElementById("fields_metodo_seleccionado").value;
	var detalle_metodo_seleccionado = document.getElementById("detalle_envio_"+metodo_seleccionado).value;
	var valor_metodo_seleccionado = document.getElementById("valor_envio_"+metodo_seleccionado).value;
	//------------------------------------------------------

	ajax.open("POST", "compras/metodo_envio.php?", false);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("metodo_envio="+metodo_seleccionado+"&detalle_metodo_seleccionado="+detalle_metodo_seleccionado+"&valor_metodo_seleccionado="+valor_metodo_seleccionado);
	
	if (ajax.readyState==4)
	{
		if(ajax.responseText)
		{	
			if(ajax.responseText=="OK"){
				null;
			}else{
				alert("Error seleccionando envio");
			}
			//window.location.reload();
		}else{
			alert("Error");
		}
	}
	
	activarPersiana(false);
}

//------------------------------------------------------------------------------------------------------

function actualizarInfoEnvio(){
	
	//document.body.scrollTop
	
	activarPersiana(true);
	
	var ajax=nuevoAjax();
	
	//------------------------------------------------------
	
	if(document.getElementById("us_nombre") && document.getElementById("us_last_name") && document.getElementById("us_telefono") && document.getElementById("us_email") && document.getElementById("us_direccion")){
		var nombre = document.getElementById("us_nombre").value;
		var last_nombre = document.getElementById("us_last_name").value;
		var telefono = document.getElementById("us_telefono").value;
		var email = document.getElementById("us_email").value;
		var direccion = document.getElementById("us_direccion").value;
		var ciudad = document.getElementById("us_ciudad").value;
		var pais = document.getElementById("us_pais").value;
		//var cp = document.getElementById("us_postal").value;
		var cp = "";
		
		var appartment = document.getElementById("us_appartment").value;
		var estado_us = document.getElementById("us_estado_us").value;
		var comments = document.getElementById("us_comments").value;
		//------------------------------------------------------
		
		ajax.open("POST", "compras/info_usuario_pedido.php?", false);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("nombre="+nombre+"&telefono="+telefono+"&email="+email+"&direccion="+direccion+"&ciudad="+ciudad+"&pais="+pais+"&cp="+cp+"&appartment="+appartment+"&estado_us="+estado_us+"&last_nombre="+last_nombre+"&comments="+comments);
		
		if (ajax.readyState==4)
		{
			if(ajax.responseText)
			{			
				return true;
			}else{
				alert("Error registering user information");
				activarPersiana(false);
				return false;
			}
		}
		
		return false;
	}
	
	activarPersiana(false);
}

//------------------------------------------------------------------------------------------------------

function funcionBotones(estado, posicion){
	if( document.getElementById("boton_compra_"+posicion) ){
		document.getElementById("boton_compra_"+posicion).disabled = estado;
		
		funcionBotones(estado, (posicion+1));
	}
}

//------------------------------------------------------------------------------------------------------

function DetectarBloqueador()
{
    var blnBloqueado = true;
    var popup = window.open('prueba.htm','Testing','width=1,height=1,left=0,top=0,scrollbars=no,location=no,status=no,resizable=no,toolbar=no');
	
	try {
		var nombre = popup.name;
		//blnBloqueado = false;
		popup.close();	
	}catch (e) {
		alert("An error occurred, your browser is active pop up blocker, please disable this feature to continue the process");
		return;
	} 
	
	//return blnBloqueado;
}

//------------------------------------------------------------------------------------------------------

function cargarInformacionMetodosEnvio(recargar){
	var ajax=nuevoAjax();
	ajax.open("POST", "fedex/Rate/Rate.php?", false);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	
	var datosBasicos = true;
	
	if(document.getElementById("us_estado_us")){
		//------------------------------------------------------
		
		var departamento_seleccionado = document.getElementById("us_estado_us").value;
		
		var nombre = document.getElementById("us_nombre").value;
		var last_nombre = document.getElementById("us_last_name").value;
		var telefono = document.getElementById("us_telefono").value;
		var email = document.getElementById("us_email").value;
		var direccion = document.getElementById("us_direccion").value;
		
		var ciudad = document.getElementById("us_ciudad").value;
		var pais = document.getElementById("us_pais").value;
		//var zip = document.getElementById("us_postal").value;
		var zip = "";
		var apparment = document.getElementById("us_appartment").value;
				
		if(zip=="" || departamento_seleccionado==""){
			datosBasicos = false;
		}
		//------------------------------------------------------
		
		var send_var = "departamento_envio="+departamento_seleccionado+"&nombre="+nombre+"&telefono="+telefono+"&email="+email+"&direccion="+direccion+"&ciudad="+ciudad+"&pais="+pais+"&zip="+zip+"&apparment="+apparment+"&last_nombre="+last_nombre;
	}
	
	//alert(send_var);
	ajax.send(send_var);
		
	if (ajax.readyState==4)
	{
		if(ajax.responseText)
		{
			var return_fedex = ajax.responseText;
						
			if(return_fedex=="SI"){
				if(recargar){
					window.location.reload();
				}
			}else{
				if(datosBasicos){
					alert("There was a problem with the shipping information. "+return_fedex);
					
					if(document.getElementById("error_carrito_fedex")){
						document.getElementById("error_carrito_fedex").innerHTML="All required fields must be completed.<br>"+return_fedex;
					}
					if(document.getElementById("error")){
						document.getElementById("error").value="There was a problem with the shipping information." + return_fedex;
					}
				}
				if(recargar){
					window.location.reload();
				}
			}			
		}else{
			//alert("Error");
		}
	}
}

//------------------------------------------------------------------------------------------------------------

function cargarInformacionMetodosEnvio2(recargar){
	var ajax=nuevoAjax();
	ajax.open("POST", "fedex/Rate/Rate.php?", false);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	
	if(document.getElementById("us_estado_us")){
		//------------------------------------------------------
		
		var departamento_seleccionado = document.getElementById("us_estado_us").value;
		
		var nombre = document.getElementById("us_nombre").value;
		var last_nombre = document.getElementById("us_last_name").value;
		var telefono = document.getElementById("us_telefono").value;
		var email = document.getElementById("us_email").value;
		var direccion = document.getElementById("us_direccion").value;
		
		var ciudad = document.getElementById("us_ciudad").value;
		var pais = document.getElementById("us_pais").value;
		//var zip = document.getElementById("us_postal").value;
		var zip = "";
		var apparment = document.getElementById("us_appartment").value;
				
		//------------------------------------------------------
	
		var send_var = "departamento_envio="+departamento_seleccionado+"&nombre="+nombre+"&telefono="+telefono+"&email="+email+"&direccion="+direccion+"&ciudad="+ciudad+"&pais="+pais+"&zip="+zip+"&last_nombre="+last_nombre+"&apparment="+apparment;
	}
	
	ajax.send(send_var);
	
	if (ajax.readyState==4)
	{
		if(ajax.responseText)
		{
			var return_fedex = ajax.responseText;

			if(return_fedex=="SI"){
				if(recargar){
					window.location.reload();
				}
			}else{
				if(recargar){
					window.location.reload();
				}
			}			
		}else{
			//alert("Error");
		}
	}
}

//------------------------------------------------------------------------------------------------------

function activarPersiana(valor){
	
	var infoHeight = "";
	var infoWidth = "";
	
	if (window.innerHeight){
		//navegadores basados en mozilla	
		infoHeight = window.innerHeight	;
	}else{	
		//Navegadores basados en IExplorer, es que no tengo innerheight	
		infoHeight = document.body.clientHeight	;
	}
	
	if (window.innerWidth){
		//navegadores basados en mozilla	
		infoWidth = window.innerWidth	;
	}else{	
		//Navegadores basados en IExplorer, es que no tengo innerheight	
		infoWidth = document.body.clientWidth;	
	}
	
	if(valor){
		if(document.getElementById("uno")){
			
			document.getElementById("dos").style.top=((infoHeight / 2) )+"px";
			document.getElementById("dos").style.left=((infoWidth - 350)/ 2)+"px";
			document.getElementById("uno").style.display="inline";
			document.getElementById("dos").style.display="inline";
		}
	}else{
		if(document.getElementById("uno")){
			document.getElementById("uno").style.display="none";
			document.getElementById("dos").style.display="none";
		}
	}
}

//------------------------------------------------------------------------------------------------------
	
function validar_cantidad_paquete(){
	var ajax=nuevoAjax();
	ajax.open("POST", "compras/validaCantidadEnvio.php?", false);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	
	var send_var = "envio=true";
	
	ajax.send(send_var);
	
	if (ajax.readyState==4)
	{
		if(ajax.responseText)
		{	
			var dato = ajax.responseText;
			
			if(dato!="OK"){
				alert(dato);
				return false;
			}
			//window.location.reload();
		}else{
			alert("Error");
			return false;
		}
	}
	
	return true;
}

//------------------------------------------------------------------------------------------------------

function steps(paso, orientacion, url){
	activarPersiana(true);
	
	if(orientacion=="right"){
		if(paso==2){
			if(document.getElementById("total_pedido").value=="" || document.getElementById("total_pedido").value=="0"){
				alert("Unable to proceed with the request that was not found worthless receivables.");
				
				activarPersiana(false);
				return;
			}else{	
				var mensaje = validar_cantidad_paquete();
				
				if(mensaje){
					window.location.href=url+"ver.php?mod=carrito&step=2";
				}
				//document.getElementById("paso1").style.display="none";
				//document.getElementById("paso2").style.display="none";
				//document.getElementById("paso3").style.display="none";
				//document.getElementById("paso4").style.display="inline";
			}
		}
		if(paso==3){
			
			var nombre = document.getElementById("us_nombre");
			var last_nombre = document.getElementById("us_last_name");
			var telefono = document.getElementById("us_telefono");
			var email = document.getElementById("us_email");
			var direccion = document.getElementById("us_direccion");
			var ciudad = document.getElementById("us_ciudad");
			var pais = document.getElementById("us_pais");
			var us_estado_us = document.getElementById("us_estado_us");
			//var cp = document.getElementById("us_postal");
			var cp = "";

			if(nombre.value==""){
				alert("The field " + nombre.title + " is required");
				
				activarPersiana(false);
				return;
			}
			
			if(last_nombre.value==""){
				alert("The field " + last_nombre.title + " is required");
				
				activarPersiana(false);
				return;
			}
			
			if(telefono.value==""){
				alert("The field " + telefono.title + " is required");
				
				activarPersiana(false);
				return;
			}
			
			if(email.value==""){
				alert("The field " + email.title + " is required");
				
				activarPersiana(false);
				return;
			}
			
			if(direccion.value==""){
				alert("The field " + direccion.title + " is required");

				activarPersiana(false);
				return;
			}
			
			if(ciudad.value==""){
				alert("The field " + ciudad.title + " is required");
				
				activarPersiana(false);
				return;
			}
			
			if(us_estado_us.value==""){
				alert("The field " + us_estado_us.title + " is required");

				activarPersiana(false);
				return;
			}
			
			/*if(cp.value==""){
				alert("The field " + cp.title + " is required");
				
				activarPersiana(false);
				return;
			}else{
				if (!/^([0-9])*$/.test(cp.value)){
					alert("The field " + cp.title + " is not a number");
					activarPersiana(false);
					return;
				}//isNAN
			}*/
	
			if(document.getElementById("error").value!=""){
				alert(document.getElementById("error").value);
				
				activarPersiana(false);
				return;
			}else{
				actualizarInfoEnvio();
				
				window.location.href=url+"ver.php?mod=carrito&step=3";
				//document.getElementById("paso1").style.display="none";
				//document.getElementById("paso2").style.display="inline";
				//document.getElementById("paso3").style.display="none";
				//document.getElementById("paso4").style.display="none";
			}
		}
		if(paso==4){
			if(!document.getElementById("fields_metodo_seleccionado") || document.getElementById("fields_metodo_seleccionado").value=="" || document.getElementById("fields_metodo_seleccionado").value=="0"){
				alert("Must select a shipping method");
		
				activarPersiana(false);
				return;
			}else{		
				seleccionarMetodoEnvio();
				
				window.location.href=url+"ver.php?mod=carrito&step=4";
				//document.getElementById("paso1").style.display="none";
				//document.getElementById("paso2").style.display="none";
				//document.getElementById("paso3").style.display="inline";
				//document.getElementById("paso4").style.display="none";
			}
		}
		
	}else{
		if(paso==1){
			window.location.href=url+"ver.php?mod=carrito&step=1";
			//document.getElementById("paso1").style.display="inline";
			//document.getElementById("paso2").style.display="none";
			//document.getElementById("paso3").style.display="none";
			//document.getElementById("paso4").style.display="none";
		}
		if(paso==2){
			window.location.href=url+"ver.php?mod=carrito&step=2";
			//document.getElementById("paso1").style.display="none";
			//document.getElementById("paso2").style.display="inline";
			//document.getElementById("paso3").style.display="none";
			//document.getElementById("paso4").style.display="none";
		}
		if(paso==3){
			window.location.href=url+"ver.php?mod=carrito&step=3";
			//document.getElementById("paso1").style.display="none";
			//document.getElementById("paso2").style.display="none";
			//document.getElementById("paso3").style.display="inline";
			//document.getElementById("paso4").style.display="none";
		}
	}

	activarPersiana(false);
}

//------------------------------------------------------------------------------------------------------

function validarCupon(referencia){
	if(referencia.value!=""){
		var ajax=nuevoAjax();
		ajax.open("POST", "compras/validate_coupons.php?", false);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		
		var send_var = "referencia_usuario="+referencia.value;

		//alert(send_var);
		ajax.send(send_var);
			
		if (ajax.readyState==4){
			if(ajax.responseText){
				var dato = ajax.responseText;
				var datos = dato.split("|");
				if(datos[0].replace(/^\s*|\s*$/g,"")=="OK"){
					if(datos[1]!=""){
						alert(datos[1]);
					}
					window.location.reload();
				}else{
					alert(datos);
				}
			}
		}
	}
}


function eliminarCupon(){
	var ajax=nuevoAjax();
	ajax.open("POST", "compras/eliminar_coupons.php?", false);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	
	var send_var = "referencia_usuario=delete";
	ajax.send(send_var);
		
	if (ajax.readyState==4){
		if(ajax.responseText){
			var dato = ajax.responseText;
			
			if(dato!="OK"){
				alert(dato);
			}else{
				window.location.reload();
			}
		}
	}
}