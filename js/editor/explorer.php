<?php
session_start();

if(!isset($_SESSION['usuario']) || $_SESSION['usuario']!="admin"){
	die("acceso no autorizado");
}
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8">
<title>Cargar Archivo</title>
<script language="javascript" type="text/javascript" src="tiny_mce.js"></script>
<script language="javascript" type="text/javascript" src="tiny_mce_popup.js"></script>
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
<div class="tabs">
	<ul>
		<li id="appearance_tab"><span><a href="upload.php">Cargar Archivo</a></span></li>
		<li id="general_tab" class="current"><span><a href="explorer.php">Buscar Archivo</a></span></li>
	</ul>
</div>

<div class="panel_wrapper">
			<div id="general_panel">
			
<fieldset>
<?php
	$ventana = "";
	$campo = "";
	
	if(isset($_GET['win'])){ $ventana = $_GET['win']; }
	if(isset($_GET['field_name'])){ $campo = $_GET['field_name']; }

	$location="";
	if(isset($_GET['location'])){		
		if(trim($_GET['location'])!=""){
			$location=trim($_GET['location'])."";
		}
	}
	
	$url_dir = "../../";
	$directorio = dir($url_dir.$location);
	$nombre_archivos = "";
?>

<?php

	echo "<table border='0' cellspacing='0' cellpadding='0'>
		   <tr>
			<td><a href='javascript:;' onclick=\"returnDir('".$location."');\" title='Volver a Carpeta Anterior'><img src='themes/advanced/images/folder.gif' border='0' hspace='8'/></a></td>
			<td><a href='javascript:;' onclick=\"returnDir('".$location."');\" title='Volver a Carpeta Anterior'> . . / </a></td>
		   </tr>
		  </table>";
						  
	while($archivo = $directorio->read())
	{
		if($archivo!="." && $archivo!=".."){
			
			if(is_dir($url_dir.$location.$archivo)){
				if($archivo!="admin" && $archivo!="js" && $archivo!="adodb" && $archivo!="language"){
					echo "<table border='0' cellspacing='0' cellpadding='0'>
					       <tr>
						    <td><a href='javascript:;' onclick=\"intoDir('".$location.$archivo."/');\"><img src='themes/advanced/images/folder.gif' border='0' hspace='8'/></a></td>
							<td><a href='javascript:;' onclick=\"putUrl('".$location.$archivo."/');\">".$archivo."</a></td>
						   </tr>
						  </table>";
				}
			}else if(is_file($url_dir.$location.$archivo)){
				if($archivo!="conexion.php"){
					
					$img = getimagesize($url_dir.$location.$archivo);
					
					if($img[0]!=0 && $img[1]!=0){
						echo "<table border='0' cellspacing='0' cellpadding='0'>
							   <tr>
								<td><img src='themes/advanced/images/image.gif' border='0' hspace='8'/></td>
								<td><a href='javascript:;' onclick=\"FileBrowserDialogue.putUrl('".$location.$archivo."');\">".$archivo."</a></td>
							   </tr>
							  </table>";
					}else{
						echo "<table border='0' cellspacing='0' cellpadding='0'>
							   <tr>
								<td><img src='themes/advanced/images/document.gif' border='0' hspace='8'/></td>
								<td><a href='javascript:;' onclick=\"FileBrowserDialogue.putUrl('".$location.$archivo."');\">".$archivo."</a></td>
							   </tr>
							  </table>";
					}
				}
			}	
		}
	}
	$directorio->close(); 

?>

</fieldset>
</div>
</div>

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

//////////////////////////////////////////////////////////////////////////////////////////////////////
		
	function intoDir(url){
		window.location.href="explorer.php?location="+url+"&win=<?=$ventana?>&field_name=<?=$campo?>";
	}
	
	function returnDir(url){	
		url = url.split("/");
		
		var newUrl="";
		
		for(i=0; i<(url.length-2); i++){
			newUrl += url[i]+"/";
		}
		
		window.location.href="explorer.php?location="+newUrl+"&win=<?=$ventana?>&field_name=<?=$campo?>";
	}	
	
			
</script>

</body>
</html>