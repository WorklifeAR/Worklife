<?php
session_start();

if(!isset($_SESSION['usuario']) || $_SESSION['usuario']!="admin"){
	die("acceso no autorizado");
}
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Cargar Archivo</title>

<script language="javascript" type="text/javascript" src="tiny_mce.js"></script>
<script language="javascript" type="text/javascript" src="tiny_mce_popup.js"></script>
<script language="javascript" type="text/javascript">
	
	var FileBrowserDialogue = {
    init : function () {
        // Here goes your code for setting your custom things onLoad.
    },
    putUrl : function (url_field) {
        var URL = url_field;
        var win = tinyMCEPopup.getWindowArg("window");

        // insert information now
        win.document.getElementById(tinyMCEPopup.getWindowArg("input")).value = URL;

        // are we an image browser
        if (typeof(win.ImageDialog) != "undefined") {
            // we are, so update image dimensions...
            if (win.ImageDialog.getImageData)
                win.ImageDialog.getImageData();

            // ... and preview if necessary
            if (win.ImageDialog.showPreviewImage)
                win.ImageDialog.showPreviewImage(URL);
        }

        // close popup window
        tinyMCEPopup.close();
    }
}

tinyMCEPopup.onInit.add(FileBrowserDialogue.init, FileBrowserDialogue);

</script>
</head>

<style type="text/css">
.panel_wrapper {
	border: 1px solid #919B9C;
	border-top: 0px;
	padding: 10px;
	padding-top: 5px;
	clear: both;
	background-color: white;
}

.tabs {
	float: left;
	width: 100%;
	line-height: normal;
	background-image: url("themes/advanced/images/xp/tabs_bg.gif");
}

.tabs ul {
	margin: 0;
	padding: 0 0 0;
	list-style: none;
}

.tabs li {
	float: left;
	background: url("themes/advanced/images/xp/tab_bg.gif") no-repeat left top;
	margin: 0;
	margin-left: 0;
	margin-right: 2px;
	padding: 0 0 0 10px;
	line-height: 18px;
}

.tabs li.current {
	background: url("themes/advanced/images/xp/tab_sel_bg.gif") no-repeat left top;
	margin-right: 2px;
}

.tabs span {
	float: left;
	display: block;
	background: url("themes/advanced/images/xp/tab_end.gif") no-repeat right top;
	padding: 0px 10px 0 0;
}

.tabs .current span {
	background: url("themes/advanced/images/xp/tab_sel_end.gif") no-repeat right top;
}

.tabs a {
	text-decoration: underline;
	font-family: Verdana, Arial;
	font-size: 7pt;
}

.tabs a:link, .tabs a:visited, .tabs a:hover {
	color: black;
}

fieldset {
	border: 1px solid #919B9C;
	font-family: Verdana, Arial;
	font-size: 10px;
	padding: 0;
	margin: 0;
	padding: 4px;
}
</style>

<body bgcolor="#F0F0EE" style="margin: 9px;">

<?php

$mensaje = "";
$color = "";
$ventana = "";
$campo = "";

if(isset($_GET['win'])){ $ventana = $_GET['win']; }
if(isset($_GET['field_name'])){ $campo = $_GET['field_name']; }

if(isset($_FILES['upload_file'])){

	if($_FILES['upload_file']['name']!=""){
	
		$url = $_FILES['upload_file']['tmp_name'];
		$imagen = $_FILES['upload_file']['name']; 
		
		//Determino si el fichero si es una imagen
		$tipo_archivo = explode(".", $_FILES['upload_file']['name']);
		$tamano = count($tipo_archivo);
		
		$tamano = ($tamano>0)?($tamano-1):$tamano;
		$extension_file = $tipo_archivo[$tamano];
				
		if($extension_file!="" && $ventana=="image"){
		
			$extension_file = strtoupper($extension_file);
			
			if($extension_file=="PNG" || $extension_file=="JPG" || $extension_file=="JPEG" || $extension_file=="GIF" || $extension_file=="BMP" || $extension_file=="SWF" || $extension_file=="FLV"){
				
				$dir = "../../images/";
				
				//Si es una imagen la subo al servidor
				$cargo = false;
		
				if(copy($url, $dir.$imagen)){ $cargo = true; }
				
				if($cargo){
					$mensaje = "Imagen Cargada satisfactoriamente. Redireccionando...";
					$color = "#66CC33";
					
					//Asigno el nombre al campo
					echo '<script type="text/javascript">
				
					setTimeout("colocarNombre();",1000);
					
					function colocarNombre(){					
						FileBrowserDialogue.putUrl("images/'.$_FILES['upload_file']['name'].'");
					}				
				  </script>';
				}else{
					$mensaje = "Se ha produccido un error y no se ha podido cargar el archivo. 
								Por favor intentelo de nuevo mas adelante.";
					$color = "#ff0000";
				}
			}else{
				$mensaje = "El fichero a subir no es un tipo de imagen reconocido.";
					$color = "#ff0000";
			}
		}else{
			$dir = "../../documentos/";
			
			//Si es una imagen la subo al servidor
			$cargo = false;
	
			if(copy($url, $dir.$imagen)){	$cargo = true;	}
			
			if($cargo){
				$mensaje = "Archivo Cargado satisfactoriamente. Redireccionando...";
				$color = "#66CC33";
				$directorio = "";
				
				//Asigno el nombre al campo
				echo '<script type="text/javascript">
									
					setTimeout("colocarNombre();",1000);
					
					function colocarNombre(){					
						FileBrowserDialogue.putUrl("documentos/'.$_FILES['upload_file']['name'].'");
					}			
			  	</script>';
			}else{
				$mensaje = "Se ha produccido un error y no se ha podido cargar el archivo. 
							Por favor intentelo de nuevo mas adelante.";
				$color = "#ff0000";
			}
		}		
	}
}
?>
<div class="tabs">
	<ul>
		<li id="general_tab" class="current"><span><a href="upload.php?win=<?=$ventana?>&field_name=<?=$campo?>">Cargar Archivo</a></span></li>
		<li id="appearance_tab"><span><a href="explorer.php?win=<?=$ventana?>&field_name=<?=$campo?>">Buscar Archivo</a></span></li>
	</ul>
</div>

<div class="panel_wrapper" >
			<div id="general_panel">
			
<fieldset>
	<table class="properties" width="80%" border="0" cellspacing="0" cellpadding="5">
	<form action="upload.php?win=<?=$ventana?>&field_name=<?=$campo?>" method="post" name="uploadFile" id="uploadFile" enctype="multipart/form-data">
	  <tr>
		<td><div align="center"><font color="<?=$color?>" size="1" face="Arial, Helvetica, sans-serif">
		  <?=$mensaje?>
		</font></div></td>
	  </tr>
	  <tr>
		<td><font size="2" face="Arial, Helvetica, sans-serif">Archivo a cargar:</font></td>
	  </tr>
	  <tr>
		<td><input type="file" size="35" name="upload_file" id="upload_file"/></td>
	  </tr>
	  <tr>
		<td><div align="right">
		  <input type="button" name="button" onClick="javascript:document.getElementById('uploadFile').submit();" value="Cargar Archivo" />
		</div></td>
	  </tr>
	  <tr>
		<td>
			<div align="center">
				<em>
					<font size="1" face="Arial, Helvetica, sans-serif">
					Desde esta opci&oacute;n solo se pueden cargar imagenes en formato PNG, JPG, JPEG, GIF, BMP. 
					</font>
				</em>
			</div>
		</td>
	  </tr>
	</form>
	</table>
</fieldset>

</div></div>
</body>
</html>