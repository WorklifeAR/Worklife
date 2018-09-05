<?php
session_permiso("521");

$variables_metodo = variables_metodo("funcion,id_directorio,borrarimg,palabra_fil,dir_fil,estado_fil,depar_fil");

$funcion= 			$variables_metodo[0];
$id_directorio= 		$variables_metodo[1];
$borrarimg= 		$variables_metodo[2];
$palabra_fil= 		$variables_metodo[3];
$dir_fil= 		$variables_metodo[4];
$estado_fil= 		$variables_metodo[5];
$depar_fil= 		$variables_metodo[6];

$variables_metodo = variables_metodo("nombre,categoria,descripcion,direccion,ciudad,estado,zip,telefono,web,info_adicional,email,sponsor,logo,latitud,longitud,orden,id_usuario,activo,destacado,img1,img2,img3,video,fecha,visualizaciones,horario_man,horario_tar,horario_noc,dia_lun,dia_mar,dia_mie,dia_jue,dia_vie,dia_sab,dia_dom,urgencia,realiza_domi,realiza_esta,realiza_ofic,realiza_onli,certificado_estudio,matricula,garantia,seguro,horario_24");

$nombre= 			$variables_metodo[0];
$categoria= 		$variables_metodo[1];
$descripcion= 		$variables_metodo[2];
$direccion= 			$variables_metodo[3];
$ciudad= 		$variables_metodo[4];
$estado= 		$variables_metodo[5];
$zip= 			$variables_metodo[6];
$telefono= 		$variables_metodo[7];
$web= 		$variables_metodo[8];
$info_adicional= 		$variables_metodo[9];
$email= 		$variables_metodo[10];
$sponsor= 		$variables_metodo[11];
$logo= 		$variables_metodo[12];
$latitud= 		$variables_metodo[13];
$longitud= 		$variables_metodo[14];
$orden= 		$variables_metodo[15];
$id_usuario= 		$variables_metodo[16];
$activo= 		$variables_metodo[17];
$destacado= 		$variables_metodo[18];
$img1= 		$variables_metodo[19];
$img2= 		$variables_metodo[20];
$img3= 		$variables_metodo[21];
$video= 		$variables_metodo[22];
$fecha= 		$variables_metodo[23];
$visualizaciones= 		$variables_metodo[24];
$horario_man= 		$variables_metodo[25];
$horario_tar= 		$variables_metodo[26];
$horario_noc= 		$variables_metodo[27];
$dia_lun= 		$variables_metodo[28];
$dia_mar= 		$variables_metodo[29];
$dia_mie= 		$variables_metodo[30];
$dia_jue= 		$variables_metodo[31];
$dia_vie= 		$variables_metodo[32];
$dia_sab= 		$variables_metodo[33];
$dia_dom= 		$variables_metodo[34];
$urgencia= 		$variables_metodo[35];
$realiza_domi= 		$variables_metodo[36];
$realiza_esta= 		$variables_metodo[37];
$realiza_ofic= 		$variables_metodo[38];
$realiza_onli= 		$variables_metodo[39];
$certificado_estudio= 		$variables_metodo[40];
$matricula= 		$variables_metodo[41];
$garantia= 		$variables_metodo[42];
$seguro= 		$variables_metodo[43];
$horario_24= 		$variables_metodo[44];

if ($funcion == "guardar"){
	if($id_directorio==""){
	/*Funcion para guardar los datos del formulario*/	
		guardar($nombre,$categoria,$descripcion,$direccion,$ciudad,$estado,$zip,$telefono,$web,$info_adicional,$email,$sponsor,$logo,$latitud,$longitud,
		        $orden,$id_usuario,$activo,$destacado,$img1,$img2,$img3,$video,$fecha,$visualizaciones,$horario_man,$horario_tar,$horario_noc,$dia_lun,
				$dia_mar,$dia_mie,$dia_jue,$dia_vie,$dia_sab,$dia_dom,$urgencia,$realiza_domi,$realiza_esta,$realiza_ofic,$realiza_onli,$certificado_estudio,
				$matricula,$garantia,$seguro,$horario_24);
	}elseif($id_directorio!=""){
	/*Funcion para modificar los datos del formulario*/	
		modificar($id_directorio,$nombre,$categoria,$descripcion,$direccion,$ciudad,$estado,$zip,$telefono,$web,$info_adicional,$email,$sponsor,$logo,$latitud,
			  	  $longitud,$orden,$id_usuario,$activo,$destacado,$img1,$img2,$img3,$video,$fecha,$visualizaciones,$horario_man,$horario_tar,$horario_noc,$dia_lun,$dia_mar,
				  $dia_mie,$dia_jue,$dia_vie,$dia_sab,$dia_dom,$urgencia,$realiza_domi,$realiza_esta,$realiza_ofic,$realiza_onli,$certificado_estudio,$matricula,$garantia,
				  $seguro,$borrarimg,$horario_24);
	}	
}elseif(($funcion == "borrar")&&($id_directorio!="")){
	borrar($id_directorio);
}

/****************************************************************************************************************************/

function borrar($id_directorio)
{
	$path = "adodb/adodb.inc.php";
	include "var.php";
	include "conexion.php";
	
	$sql="SELECT nombre FROM ic_directorio WHERE id_directorio='".$id_directorio."' ";
	$result=$db->Execute($sql);
	
	list($titulo)=select_format($result->fields);

	//funcion para registrar la modificacion hecha por el usuario
	registrar_cambios($db, date('Y-m-d'), $_SESSION['sess_nombre'], '521', $id_directorio, $titulo, 'ELIMINAR');
	
	$result=$db->Execute("DELETE FROM ic_directorio WHERE id_directorio = '".$id_directorio."' ");
	
	if ($result != false) $mensaje = "3";
	else $mensaje  = "0";
	
	echo"<META HTTP-EQUIV='Refresh' CONTENT='0;URL=admin.php?mod=521&mensaje=".$mensaje."'>";
	die();
}

/****************************************************************************************************************************/

function guardar($nombre,$categoria,$descripcion,$direccion,$ciudad,$estado,$zip,$telefono,$web,$info_adicional,$email,$sponsor,$logo,$latitud,$longitud,
		        $orden,$id_usuario,$activo,$destacado,$img1,$img2,$img3,$video,$fecha,$visualizaciones,$horario_man,$horario_tar,$horario_noc,$dia_lun,
				$dia_mar,$dia_mie,$dia_jue,$dia_vie,$dia_sab,$dia_dom,$urgencia,$realiza_domi,$realiza_esta,$realiza_ofic,$realiza_onli,$certificado_estudio,
				$matricula,$garantia,$seguro,$horario_24)
{
	$path = "adodb/adodb.inc.php";
	include "var.php";
	include "conexion.php";

	
	$dir = "images/directorio/";
	
	$url_full = $_FILES['logo']['tmp_name'];
	$imagen_full = $_FILES['logo']['name']; 
	
	if($url_full!="" && $imagen_full!=""){
		$alt = mt_rand();
				
		if(subir_imagen($alt.$imagen_full, $url_full, $dir)){
			$logo = $dir.$alt.$imagen_full;
		}
	}
	
	////////////////
	
	$dir = "images/galeria/";
	
	////////////////
	
	$url_full = $_FILES['img1']['tmp_name'];
	$imagen_full = $_FILES['img1']['name']; 
	
	if($url_full!="" && $imagen_full!=""){
		$alt = mt_rand();
				
		if(subir_imagen($alt.$imagen_full, $url_full, $dir)){
			$img1 = $dir.$alt.$imagen_full;
		}
	}
	
	////////////////
	
	$url_full = $_FILES['img2']['tmp_name'];
	$imagen_full = $_FILES['img2']['name']; 
	
	if($url_full!="" && $imagen_full!=""){
		$alt = mt_rand();
				
		if(subir_imagen($alt.$imagen_full, $url_full, $dir)){
			$img2 = $dir.$alt.$imagen_full;
		}
	}
	
	////////////////
	
	$url_full = $_FILES['img3']['tmp_name'];
	$imagen_full = $_FILES['img3']['name']; 
	
	if($url_full!="" && $imagen_full!=""){
		$alt = mt_rand();
				
		if(subir_imagen($alt.$imagen_full, $url_full, $dir)){
			$img3 = $dir.$alt.$imagen_full;
		}
	}
	
	////////////////
	
	
	$result=insert_bd_format("nombre,categoria,descripcion,direccion,ciudad,estado,zip,telefono,web,info_adicional,email,sponsor,logo,latitud,longitud,orden,id_usuario,activo,destacado,".
	                         "img1,img2,img3,video,fecha,visualizaciones,horario_man,horario_tar,horario_noc,dia_lun,dia_mar,dia_mie,dia_jue,dia_vie,dia_sab,dia_dom,urgencia,realiza_domi,".
							 "realiza_esta,realiza_ofic,realiza_onli,certificado_estudio,matricula,garantia,seguro,horario_24", 
							 "ic_directorio", 
							 array($nombre,"",$descripcion,$direccion,$ciudad,$estado,$zip,$telefono,$web,$info_adicional,$email,$sponsor,$logo,$latitud,$longitud,
								   $orden,$id_usuario,$activo,$destacado,$img1,$img2,$img3,$video,$fecha,$visualizaciones,$horario_man,$horario_tar,$horario_noc,$dia_lun,
								   $dia_mar,$dia_mie,$dia_jue,$dia_vie,$dia_sab,$dia_dom,$urgencia,$realiza_domi,$realiza_esta,$realiza_ofic,$realiza_onli,$certificado_estudio,
								   $matricula,$garantia,$seguro,$horario_24), 
							 $db);
	
	////////////////////////
	
	$categoria = explode(",", $categoria);

    for ($i = 0; $i < count($categoria); $i++) {
        $sql = "INSERT INTO ic_directorio_categoria (id_directorio,id_categoria) VALUES ('" . $id_directorio . "','" . $categoria[$i] . "');";
        $result = $db->Execute($sql);
    }
	
	if ($result != false) $mensaje = "1";
	else $mensaje  = "0";
	
	//funcion para registrar la modificacion hecha por el usuario
	registrar_cambios($db, date('Y-m-d'), $_SESSION['sess_nombre'], '521', '0', $nombre, 'GUARDAR');
	
	implantar_urls($db);
	
	echo"<META HTTP-EQUIV='Refresh' CONTENT='0;URL=admin.php?mod=521&mensaje=".$mensaje."'>";
	die();	
}

/****************************************************************************************************************************/

function modificar($id_directorio,$nombre,$categoria,$descripcion,$direccion,$ciudad,$estado,$zip,$telefono,$web,$info_adicional,$email,$sponsor,$logo,$latitud,
			  	  $longitud,$orden,$id_usuario,$activo,$destacado,$img1,$img2,$img3,$video,$fecha,$visualizaciones,$horario_man,$horario_tar,$horario_noc,$dia_lun,$dia_mar,
				  $dia_mie,$dia_jue,$dia_vie,$dia_sab,$dia_dom,$urgencia,$realiza_domi,$realiza_esta,$realiza_ofic,$realiza_onli,$certificado_estudio,$matricula,$garantia,
				  $seguro,$borrarimg,$horario_24)
{
	$path = "adodb/adodb.inc.php";
	include "var.php";
	include "conexion.php";	
			
	$dir = "images/directorio/";
	$url_full = $_FILES['logo']['tmp_name'];
	$imagen_full = $_FILES['logo']['name']; 
	$n_imagen = "";
	$n_imagen1 = "";
		
	if($url_full!="" && $imagen_full!=""){
		$alt = mt_rand();
				
		if(subir_imagen($alt.$imagen_full, $url_full, $dir)){
			$n_imagen1 = "logo";
			$n_imagen = $dir.$alt.$imagen_full;
		}
	}
	
	///////////////////////////////
	
	$dir = "images/galeria/";
	
	$url_full = $_FILES['img1']['tmp_name'];
	$imagen_full = $_FILES['img1']['name']; 
	$img1 = "";
	$img11 = "";
		
	if($url_full!="" && $imagen_full!=""){
		$alt = mt_rand();
				
		if(subir_imagen($alt.$imagen_full, $url_full, $dir)){
			$img11 = "img1";
			$img1 = $dir.$alt.$imagen_full;
		}
	}
	
	/////////////
	
	$url_full = $_FILES['img2']['tmp_name'];
	$imagen_full = $_FILES['img2']['name']; 
	$img2 = "";
	$img22 = "";
		
	if($url_full!="" && $imagen_full!=""){
		$alt = mt_rand();
				
		if(subir_imagen($alt.$imagen_full, $url_full, $dir)){
			$img22 = "img2";
			$img2 = $dir.$alt.$imagen_full;
		}
	}
	
	/////////////
	
	$url_full = $_FILES['img3']['tmp_name'];
	$imagen_full = $_FILES['img3']['name']; 
	$img3 = "";
	$img33 = "";
		
	if($url_full!="" && $imagen_full!=""){
		$alt = mt_rand();
				
		if(subir_imagen($alt.$imagen_full, $url_full, $dir)){
			$img33 = "img3";
			$img3 = $dir.$alt.$imagen_full;
		}
	}
	
	//////////////////////////////
	
	if($borrarimg=="S"){
		$n_imagen = "";
		$n_imagen1 = "logo";
	}
	
	$categoria_item1="";
	
	for($i=0; $i<count($categoria); $i++){
		if(isset($categoria[$i]) && $categoria[$i]!=""){
			$categoria_item1 .= "*".$categoria[$i]."*";
		}
	}
	
	////////////////////
	
	$sql="SELECT activo FROM ic_directorio WHERE id_directorio='".$id_directorio."'";
	$result=$db->Execute($sql);
	
	list($activo_old)=$result->fields;
	
	/////////////////////
	
	$result=update_bd_format(array("nombre","descripcion","direccion","ciudad","estado","zip","telefono","web","info_adicional","email","sponsor","latitud",
	                               "longitud","orden","id_usuario","activo","destacado","video","fecha","visualizaciones","horario_man","horario_tar",
								   "horario_noc","dia_lun","dia_mar","dia_mie","dia_jue","dia_vie","dia_sab","dia_dom","urgencia","realiza_domi","realiza_esta","realiza_ofic",
								   "realiza_onli","certificado_estudio","matricula","garantia","seguro","horario_24",$n_imagen1,$img11,$img22,$img33), 
						 	 "ic_directorio",
							  array($nombre,$descripcion,$direccion,$ciudad,$estado,$zip,$telefono,$web,$info_adicional,$email,$sponsor,$latitud,$longitud,
							        $orden,$id_usuario,$activo,$destacado,$video,$fecha,$visualizaciones,$horario_man,$horario_tar,$horario_noc,$dia_lun,
									$dia_mar,$dia_mie,$dia_jue,$dia_vie,$dia_sab,$dia_dom,$urgencia,$realiza_domi,$realiza_esta,$realiza_ofic,$realiza_onli,$certificado_estudio,
									$matricula,$garantia,$seguro,$horario_24,$n_imagen,$img1,$img2,$img3), 
							  "WHERE id_directorio='".$id_directorio."'", 
							  $db);
	
	////////////////////////
	
	$sql = "DELETE FROM ic_directorio_categoria WHERE id_directorio='" . $id_directorio . "';";
    $result = $db->Execute($sql);
	
	$categoria = explode(",", $categoria);

    for ($i = 0; $i < count($categoria); $i++) {
        $sql = "INSERT INTO ic_directorio_categoria (id_directorio,id_categoria) VALUES ('" . $id_directorio . "','" . $categoria[$i] . "');";
        $result = $db->Execute($sql);
    }
	
	/*if($activo_old=="0" && $activo=="1" && $id_usuario!=""){
		$sql="SELECT us_email FROM ic_usuarios WHERE us_id='".$id_usuario."'";
		$result_user=$db->Execute($sql);
		
		list($MailTo)=$result->fields;
				
		//variables para envio de notificacion de email
		$asunto = "Your publication in Super Orange Book";

		$contenido = "<img src='http://superorangebook.com/images/logo.png' /><br><br>";
		$contenido .= "The status of your publication has changed (".$nombre.")<br><br>Now everyone can see and share their publication.<br><br>";
		$cabeceras = "MIME-Version: 1.0\r\n";
		$cabeceras .= "Content-type: text/html; charset=utf-8\r\n";
		$cabeceras .= "From: Your publication in Super Orange Book <info@superorangebook.com>\r\n";		
		
		mail($MailTo, "Your publication in Super Orange Book", $contenido, $cabeceras);
	}*/
	
	if ($result != false) $mensaje = "2";
	else $mensaje  = "0";
	
	//funcion para registrar la modificacion hecha por el usuario
	registrar_cambios($db, date('Y-m-d'), $_SESSION['sess_nombre'], '521', $id_directorio, $nombre, 'MODIFICAR');
	
	implantar_urls($db);
	
	echo"<META HTTP-EQUIV='Refresh' CONTENT='0;URL=admin.php?mod=521&mensaje=".$mensaje."&id_directorio=".$id_directorio."'>";
	die();
}
/****************************************************************************************************************************/

if($id_directorio=="" && $funcion!="nuevo"){
	$where = "";
	if($palabra_fil!=""){
		$where .= " AND (nombre LIKE '%".$palabra_fil."%' OR descripcion LIKE '%".$palabra_fil."%' OR web LIKE '%".$palabra_fil."%')";
	}
	
	if($dir_fil!=""){
		$where .= " AND (direccion LIKE '%".$dir_fil."%' OR ciudad LIKE '%".$dir_fil."%' OR estado LIKE '%".$dir_fil."%' OR zip LIKE '%".$dir_fil."%')";
	}
	
	if($estado_fil!=""){
		$where .= " AND (activo='".$estado_fil."')";
	}
	
	if($depar_fil!=""){
		$where .= " AND (estado='".$depar_fil."')";
	}
	
	
	$sql="SELECT id_directorio,nombre,categoria,descripcion,direccion,ciudad,estado,zip,telefono,web,info_adicional,email,sponsor,logo,latitud,longitud,orden,id_usuario,activo,destacado FROM ic_directorio 
	      WHERE 1=1 ".$where." ORDER BY id_directorio DESC LIMIT 0,200";
	$result=$db->Execute($sql);
	
?>
<table border="0"  width="900" align="center">
<form action="" method="post">
  <tr align="center">
    <td colspan="12">
    <img src="images/admin/catalogo.png" border="0"/>  Directorio de Servicios
    </td>
  </tr>
  <tr>
  	<td colspan="12">&nbsp;</td>
  </tr>
	  <tr align="center">
	    <td><strong>Palabra Clave</strong></td>
	    <td><input name="palabra_fil" type="text" class="form-control" id="palabra_fil" value="<?=$palabra_fil?>" /></td>
	    <td>&nbsp;</td>
	    <td><strong>Dirección</strong></td>
	    <td>
        <input name="dir_fil" type="text" class="form-control" id="dir_fil" value="<?=$dir_fil?>" />
        </td>
		<td>&nbsp;</td>
	    <td><strong>Provincia</strong></td>
	    <td>
        <select name="depar_fil" id="depar_fil" class="form-control" >
			<?= listado_estados_us($depar_fil,"1") ?>
		</select>
        </td><td>&nbsp;</td>
	    <td><strong>Estado</strong></td>
	    <td>
        <select name="estado_fil" id="activo" class="form-control" >
	      <?php cargar_lista_estatica("0,1","Inactivo,Activo",1,$estado_fil); ?>
	      </select>
        </td>
        <td>
        	<input type="submit" name="Submit2" value="Buscar" />
        </td>
  </tr>
 </form>
</table>
<table width="1100" border="0" align="center">
  <tr>
  	<td colspan="10"><div style="margin:20px 0; width:100%; border-top:1px solid #494949;"></div></td>
  </tr>
  <tr>
    <td colspan="10" align="right" ><a href="admin.php?mod=521&funcion=nuevo">[ Nuevo Servicio ]</a></td>
  </tr>
  <tr>
    <th>Nombre</th>
    <th>Direccion</th>
    <th>Provincia</th>
    <th>Web</th>
    <th>Email</th>
    <th>Estado</th>
    <th>Opción</th>
  </tr>
<?php
	while(!$result->EOF){
		list($id_directorio,$nombre,$categoria,$descripcion,$direccion,$ciudad,$estado,$zip,$telefono,$web,$info_adicional,$email,$sponsor,$logo,$latitud,$longitud,$orden,$id_usuario,$activo,$destacado)=select_format($result->fields);
		$bgcolor = "#FFFFFF";
		if($activo=="0"){
			$bgcolor = "#FF6666";
		}
?>

  <tr>
    <td valign="top" bgcolor="#FFFFFF" ><?=$nombre?></td>
    <td valign="top" bgcolor="#FFFFFF" ><?=$direccion?><br /><?=$ciudad?><br /><?=$zip?><br /><?=$telefono?></td>
    <td valign="top" bgcolor="#FFFFFF" ><?=$estado?></td>
    <td valign="top" bgcolor="#FFFFFF" ><?=$web?></td>
    <td valign="top" bgcolor="#FFFFFF"><?=$email?></td>
    <td align="center" valign="top" bgcolor="<?=$bgcolor?>" ><?=(($activo=="0")?"Inactivo":"Activo")?></td>
    <td align="center" valign="top" bgcolor="#FFFFFF" ><a href="admin.php?mod=521&id_directorio=<?=$id_directorio?>">[ Editar ]</a></td>
  </tr>

<?php	
		$result->MoveNext();
	}
?>
</table>

<?php
}else{
?>
	<script language="javascript" type="text/javascript">
		//incluir_editor("descripcion,info_adicional");
	</script>

<form action="" name="modificar" method="post">
<table border="0" width="600" align="center">
  <tr align="center">
    <td colspan="12">
    <img src="images/admin/catalogo.png" border="0"/>  Directorio de Servicios
    </td>
  </tr>
  <tr>
  	<td colspan="12">&nbsp;</td>
  </tr>
	   <tr>
		<td>
		  <select name="id_directorio" class="form-control">
		  <?php cargar_lista("ic_directorio","id_directorio,nombre","nombre","1","","",$db); ?>
		  </select>
		  </td>
		<td>
		  <input type="submit" name="Submit" value="Editar">
		</td>
	   </tr>
	</table>
	</form>
	
<?php
if($mensaje==1){ 
	$mensaje = "<center><span id='confirmacion'>Informaci&oacute;n Guardada Correctamente</span></center>";
}elseif(($mensaje==2)&&($mensaje!="")){
	$mensaje  = "<center><span id='confirmacion'>Informaci&oacute;n Actualizada Correctamente</span></center>"; 
}elseif(($mensaje==3)&&($mensaje!="")){
	$mensaje  = "<center><span id='confirmacion'>Informaci&oacute;n Eliminada Correctamente</span></center>"; 
}elseif(($mensaje==0)&&($mensaje!="")){
	$mensaje  = "<center><span id='error'>Ha ocurrido un error y no se ha guardado la informaci&oacute;n.</span></center>"; 
}else {
	$mensaje  = "";
}
echo $mensaje;
?>

<script language="javascript">	

function enviar(campos){

	var camposObligatorios = campos.split(",");
	
	for(i=0;i<camposObligatorios.length;i++)
	{	
		if(document.getElementById(camposObligatorios[i]).value==""){
			alert("Campo "+ camposObligatorios[i] +" obligatorio");
			return;
		}
	}	
	document.guardar.submit();
}

</script>

<?php
if ($id_directorio != "")
{
	$sql = "SELECT id_directorio, nombre, categoria, descripcion, direccion, ciudad, estado, zip, telefono, web, info_adicional, email, sponsor, logo,
		               latitud, longitud, orden, id_usuario, activo, destacado, img1, img2, img3, video, fecha, visualizaciones, horario_man, horario_tar, 
					   horario_noc, dia_lun, dia_mar, dia_mie, dia_jue, dia_vie, dia_sab, dia_dom, urgencia, realiza_domi, realiza_esta, realiza_ofic, realiza_onli, 
					   certificado_estudio, matricula, garantia, seguro, horario_24
		        FROM ic_directorio 
				WHERE id_directorio='".$id_directorio."'";
		$result=$db->Execute($sql); 

		list($id_directorio,$nombre,$categoria,$descripcion,$direccion,$ciudad,$estado,$zip,$telefono,$web,$info_adicional,$email,$sponsor,$logo,$latitud,$longitud,
		     $orden,$id_usuario,$activo,$destacado,$img1,$img2,$img3,$video,$fecha,$visualizaciones,$horario_man,$horario_tar,$horario_noc,$dia_lun,$dia_mar,$dia_mie,
			 $dia_jue,$dia_vie,$dia_sab,$dia_dom,$urgencia,$realiza_domi,$realiza_esta,$realiza_ofic,$realiza_onli,$certificado_estudio,$matricula,$garantia,$seguro,$horario_24)=select_format($result->fields);
}
?>
<link rel="stylesheet" href="dist/app.css">
<link rel="stylesheet" href="dist/bootstrap-tagsinput.css">

<script src="js/typeahead.bundle.min.js"></script>
<script src="dist/bootstrap-tagsinput.min.js"></script>
<table width="1100" border="0" align="center">
<form action="" name="guardar" method="post" enctype="multipart/form-data">
<input type="hidden" name="funcion" value="guardar">
<input type="hidden" name="id_directorio" value="<?=$id_directorio?>">

  <tr>
  	<td colspan="10"><div style="margin:20px 0; width:100%; border-top:1px solid #494949;"></div></td>
  </tr>
  <tr>
	   <td valign="top" width="100"><a href="admin.php?mod=521">Volver al listado</a></td>
	   <td><div align="right"><a href="admin.php?mod=521&amp;funcion=nuevo">Cancelar/Nuevo</a></div></td>
	 </tr>
	 <tr>
	   <td valign="top">&nbsp;</td>
	   <td><div align="right"><font color="#FF0000" size="1">(*)Campos requeridos</font></div></td>
      </tr>
	 <tr>
		<td valign="top"><div align="right"><strong>Nombre</strong></div></td>
		<td valign="top"><input name="nombre" type="text" id="nombre" class="form-control" value="<?=$nombre ?>" /></td>
	  </tr>
	  <tr>
	    <td valign="top"><div align="right"><strong>Estado</strong></div></td>
	    <td><select name="activo" id="activo" class="form-control" >
	      <?php cargar_lista_estatica("0,1","Inactive,Active",0,$activo); ?>
	      </select></td>
      </tr>
	  <tr>
	    <td valign="top"><div align="right"><strong>Usuario</strong></div></td>
	    <td><select name="id_usuario" id="id_usuario" class="form-control" >
	      <?php cargar_lista("ic_usuarios","us_id,CONCAT(us_nombre;' ';us_last_name)","(CONCAT(us_nombre,' ',us_last_name))","1",$id_usuario," WHERE us_estado='1'",$db); ?>
	      </select></td>
      </tr>
	  <tr>
	    <td valign="top"><div align="right"><strong>Destacado</strong></div></td>
	    <td><select name="destacado" id="destacado" class="form-control" >
	      <?php cargar_lista_estatica("1,2,3","View in Home,Featured,View in Home and Featured",1,$destacado); ?>
	      </select></td>
      </tr>
	  <tr>
		<td valign="top"><div align="right"><strong>Categoría</strong></div></td>
		<td>
		<input name="categoria" type="text" id="categoria" title="Categoria" placeholder="Escribe la categoría de tu servicio ej,: Instalador de Aire Acondicionado, Pomero, Abogado, Profesor de Ingles etc..." class="form-control" />
                        <em>_ Si Agregas nuevas Categorías, Recordá separarlas por coma ( , ). Podras agregar las que Quieras!, esto Mejora los resultados y te pueden encontrar más fácilmente.</em>
                        <script>
                            var categories = new Bloodhound({
                                datumTokenizer: Bloodhound.tokenizers.obj.whitespace('text'),
                                queryTokenizer: Bloodhound.tokenizers.whitespace,
                                prefetch: 'data/categories.php'
                            });
                            categories.initialize();

                            var elt = $('#categoria');
                            elt.tagsinput({
                                itemValue: 'value',
                                itemText: 'text',
                                typeaheadjs: {
                                    name: 'categories',
                                    displayKey: 'text',
                                    source: categories.ttAdapter()
                                },
                                tagClass: 'label label-warning'
                            });
    <?php
    $sql = "SELECT cat_id,cat_titulo FROM ic_categoria a, ic_directorio_categoria b WHERE id_directorio='" . $id_directorio . "' AND cat_id=id_categoria ORDER BY cat_titulo ASC";
    $result = $db->Execute($sql);

    $data = array();
    while (!$result->EOF) {
        list($id, $titulo) = select_format($result->fields);

        echo 'elt.tagsinput(\'add\', { "value": ' . $id . ' , "text": "' . $titulo . '"});
';

        $result->MoveNext();
    }
    ?>
                        </script>
						
        
        </td>
  	  </tr>	  
	  <tr>
		<td valign="top" colspan="10"><div style="width:100%; height:5px; background:#ccc; margin:15px 0;"></div></td>
	  </tr>
	  <tr>
		<td valign="top"><div align="right"><strong>Ciudad</strong></div></td>
		<td><input type="text" name="ciudad" id="ciudad" value="<?=$ciudad ?>"  class="form-control"/></td>
	  </tr>
      <tr>
		<td valign="top"><div align="right"><strong>Provincia</strong></div></td>
		<td>
            <select name="estado" id="estado"  class="form-control">
				<?= listado_estados_us($estado,"1") ?>
		    </select>
		</td>
	  </tr>
      <tr>
		<td valign="top"><div align="right"><strong>Código Postal</strong></div></td>
		<td><input type="text" name="zip" id="zip" value="<?=$zip ?>"  class="form-control"/></td>
	  </tr>	  
	  <tr>
		<td valign="top"><div align="right"><strong>Dirección</strong></div></td>
		<td><input type="text" name="direccion" id="direccion" value="<?=$direccion ?>" class="form-control"> 
		  <a href="javascript:;" onclick="inicializacion();">[Obtener Coordenadas]</a></td>
	  </tr>
	  <tr>
	    <td valign="top"><div align="right">
	      <p><strong>Ubicación</strong></p>
        </div></td>
	    <td>
        <input type="hidden" name="longitud" id="longitud" value="<?=$longitud?>">
        <input type="hidden" name="latitud" id="latitud" value="<?=$latitud?>">

		<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		<script type="text/javascript" src="http://maps.google.com/maps/api/js?language=es&key=AIzaSyCMQOYdkjJyu0hkaxtC-h8t9r-KZE_YFOQ"></script>

Arrastras marcador en la posición<br />
<a href="javascript:;" onclick="inicializacion();">[Obtener Coordenadas]</a><br />
<div id="mapa" style="width:100%; height:200px;"></div>

<script type="text/javascript">
function inicializacion() {
		
	var address = document.getElementById("direccion").value + ' ' + document.getElementById("ciudad").value + ' ' + document.getElementById("estado").value + ' ' + document.getElementById("zip").value;
	
	if(address.trim()!=''){
		
		var options = {
			zoom: 13,
			mapTypeId: google.maps.MapTypeId.ROADMAP
		};
	
		map = new google.maps.Map(document.getElementById('mapa'), options);
	
		var geoCoder = new google.maps.Geocoder(address)
		var request = {address:address};
		latlng = geoCoder.geocode(request, function(result, status){
				latlng = new google.maps.LatLng(result[0].geometry.location.lat(), result[0].geometry.location.lng());

				map.setOptions({
						 zoom: 13
						, center: latlng
						, mapTypeId: google.maps.MapTypeId.ROADMAP						
				});
				
				var marcador = new google.maps.Marker({position: latlng,
				                                       map: map, 
													   animation: google.maps.Animation.DROP, 
													   draggable: true, 
													   title:"A marker that can be moved"});
				
				
				document.getElementById("latitud").value=result[0].geometry.location.lat();
				document.getElementById("longitud").value=result[0].geometry.location.lng();
					
				//Creo un evento asociado a "marcador" para cuando se termina de mover ("dragend") el mismo.
				google.maps.event.addListener(marcador, "dragend", function(evento) {
					//Obtengo las coordenadas separadas
					var latitud = evento.latLng.lat();
					var longitud = evento.latLng.lng();
					
					document.getElementById("latitud").value=latitud;
					document.getElementById("longitud").value=longitud;
				});	
		});
	}else{	
		var lat = '<?=$latitud?>';
		var lon = '<?=$longitud?>';
		
		if(lat=="" && lon==""){
			var lat = '41.7509724';
			var lon = '-88.3067189';
		}
	
		//Creo un nuevo mapa situado en Buenos Aires, Argentina, con 13 de Zoom y del tipo ROADMAP
		var mapa = new google.maps.Map(document.getElementById("mapa"),{center: new google.maps.LatLng(lat,lon),zoom: 13,mapTypeId: google.maps.MapTypeId.ROADMAP});
		
		//Creo un marcador cualquiera situado en una coordenada cualquiera, con la propiedad "draggable" como "true".
		var marcador = new google.maps.Marker({position: new google.maps.LatLng(lat,lon),map: mapa, animation: google.maps.Animation.DROP, draggable: true, title:"A marker that can be moved"});
			
		//Creo un evento asociado a "marcador" para cuando se termina de mover ("dragend") el mismo.
		google.maps.event.addListener(marcador, "dragend", function(evento) {
			//Obtengo las coordenadas separadas
			var latitud = evento.latLng.lat();
			var longitud = evento.latLng.lng();
			
			document.getElementById("latitud").value=latitud;
			document.getElementById("longitud").value=longitud;
		});	
	}
	
}

inicializacion();
</script>
        </td>
      </tr>
      <tr>
		<td valign="top"><div align="right"><strong>Teléfono</strong></div></td>
		<td><input type="text" name="telefono" id="telefono" value="<?=$telefono ?>"  class="form-control"/></td>
	  </tr>
      <tr>
		<td valign="top"><div align="right"><strong>Web</strong></div></td>
		<td><input type="text" name="web" id="web" value="<?=$web ?>"  class="form-control"/></td>
	  </tr>
      <tr>
		<td valign="top"><div align="right"><strong>Email</strong></div></td>
		<td><input type="text" name="email" id="email" value="<?=$email ?>"  class="form-control"></td>
	  </tr>
	  <tr>
		<td valign="top"><div align="right"><strong>Horario de atención</strong></div></td>
		<td>
			<input type="checkbox" name="horario_man" id="horario_man" <?=(($horario_man=="1")?"checked":"")?> value="1" /> Mañana
			<br/>
			<input type="checkbox" name="horario_tar" id="horario_tar" <?=(($horario_tar=="1")?"checked":"")?> value="1" /> Tarde
			<br/>
			<input type="checkbox" name="horario_noc" id="horario_noc" <?=(($horario_noc=="1")?"checked":"")?> value="1" /> Noche
			<br/>
			<input type="checkbox" name="horario_24" id="horario_24" <?=(($horario_24=="1")?"checked":"")?> value="1" /> Servicio 24h/Urgencia
		</td>
	  </tr>
	  <tr>
		<td valign="top"><div align="right"><strong>Días de la semana</strong></div></td>
		<td align="left">
			<table align="left">
				<tr>
					<td valign="top">
						<input type="checkbox" name="dia_lun" id="dia_lun" <?=(($dia_lun=="1")?"checked":"")?> value="1" /> Lunes
						<br/>
						<input type="checkbox" name="dia_mar" id="dia_mar" <?=(($dia_mar=="1")?"checked":"")?> value="1" /> Martes
						<br/>
						<input type="checkbox" name="dia_mie" id="dia_mie" <?=(($dia_mie=="1")?"checked":"")?> value="1" /> Miércoles
						<br/>
						<input type="checkbox" name="dia_jue" id="dia_jue" <?=(($dia_jue=="1")?"checked":"")?> value="1" /> Jueves
						<br/>
						<input type="checkbox" name="dia_vie" id="dia_vie" <?=(($dia_vie=="1")?"checked":"")?> value="1" /> Viernes
					</td>                
					<td valign="top">
						<input type="checkbox" name="dia_sab" id="dia_sab" <?=(($dia_sab=="1")?"checked":"")?> value="1" /> Sabado
						<br/>
						<input type="checkbox" name="dia_dom" id="dia_dom" <?=(($dia_dom=="1")?"checked":"")?> value="1" /> Domingo
					</td>
				</tr>
			</table>
		</td>
	  </tr>
	  <tr>
		<td valign="top"><div align="right"><strong>Donde se realizará el servicio</strong></div></td>
		<td>
			<input type="checkbox" name="realiza_domi" id="realiza_domi" <?=(($realiza_domi=="1")?"checked":"")?> value="1" /> a Domicilio 
			<br/>
			<input type="checkbox" name="realiza_esta" id="realiza_esta" <?=(($realiza_esta=="1")?"checked":"")?> value="1" /> en Establecimiento/Instituto
			<br/>
			<input type="checkbox" name="realiza_ofic" id="realiza_ofic" <?=(($realiza_ofic=="1")?"checked":"")?> value="1" /> en Oficina/ Local
			<br/>
			<input type="checkbox" name="realiza_onli" id="realiza_onli" <?=(($realiza_onli=="1")?"checked":"")?> value="1" /> On-Line/Freelance
		</td>
	  </tr>
	  <tr>
		<td valign="top" colspan="10"><div style="width:100%; height:5px; background:#ccc; margin:15px 0;"></div></td>
	  </tr>      
	  <tr>
	    <td valign="top"><div align="right"><strong>Logo</strong></div></td>
	    <td><table border="0" cellspacing="0" cellpadding="0" align="left">
	      <tr>
	        <td><input type="file" name="logo" id="logo"  class="form-control"/></td>
	        <td>&nbsp;</td>
	        <td><input type="checkbox" name="borrarimg" id="borrarimg" value="S"  class="form-control"/></td>
	        <td><em>Borrar Imagen</em></td>
          </tr>
	      </table>
	      <img src="<?=($n_imagen=="")?"images/spacer.gif":$n_imagen?>" width="50" height="50" alt="" border="0" /></td>
      </tr>
      <tr>
		<td valign="top"><div align="right"><strong>Imagen 1</strong></div></td>
		<td>
        <input type="file" name="img1" id="img1"  class="form-control"/>
        <br />
        <img src="<?=($img1=="")?"images/spacer.gif":$img1?>" width="50" alt="" border="0" />
        </td>
	  </tr>
      <tr>
		<td valign="top"><div align="right"><strong>Imagen 2</strong></div></td>
		<td>
        <input type="file" name="img2" id="img2"  class="form-control"/>
        <br />
        <img src="<?=($img2=="")?"images/spacer.gif":$img2?>" width="50" alt="" border="0" />
        </td>
	  </tr>
      <tr>
		<td valign="top"><div align="right"><strong>Imagen 3</strong></div></td>
		<td>
        <input type="file" name="img3" id="img3"  class="form-control"/>
        <br />
        <img src="<?=($img3=="")?"images/spacer.gif":$img3?>" width="50" alt="" border="0" />
        </td>
	  </tr>
	  
	  <tr>
		<td valign="top" colspan="10"><div style="width:100%; height:5px; background:#ccc; margin:15px 0;"></div></td>
	  </tr>
	  <tr>
		<td valign="top"><div align="right">
		  <p><strong>Descripción</strong></p>
		</div></td>
		<td><textarea name="descripcion"  class="form-control" id="descripcion" ><?= $descripcion?></textarea></td>
	  </tr>
	  <tr>
		<td valign="top"><div align="right">
		  <p><strong>Información Adicional</strong></p>
		</div></td>
		<td><textarea name="info_adicional"  class="form-control" id="info_adicional"><?=$info_adicional?></textarea></td>
	  </tr>
      <tr>
		<td valign="top"><div align="right">
		  <p><strong>Video</strong></p>
		</div></td>
		<td><textarea name="video" class="form-control" id="video"><?=$video?></textarea></td>
	  </tr>	  
	  <tr>
		<td valign="top"><div align="right">
		  <p><strong>Certificados/Estudios</strong></p>
		</div></td>
		<td><input name="certificado_estudio" type="text" id="certificado_estudio" class="form-control" value="<?=$certificado_estudio ?>" /></td>
	  </tr>	 
	  <tr>
		<td valign="top"><div align="right">
		  <p><strong>Matricula</strong></p>
		</div></td>
		<td><input name="matricula" type="text" id="matricula" class="form-control" value="<?=$matricula ?>" /></td>
	  </tr>	
	  <tr>
		<td valign="top"><div align="right">
		  <p><strong>Seguro</strong></p>
		</div></td>
		<td><input name="seguro" type="text" id="seguro" class="form-control" value="<?=$seguro ?>" /></td>
	  </tr>	
	  <tr>
		<td valign="top"><div align="right">
		  <p><strong>Garantía</strong></p>
		</div></td>
		<td>
			<select name="garantia" id="garantia" class="form-control" >
				<?php cargar_lista_estatica("0,1,2,3,4,5,6,7,8,9,10,11,12,18,24","Sin garantía,1 mes,2 meses,3 meses,4 meses,5 meses,6 meses,7 meses,8 meses,9 meses,10 meses,11 meses,1 año,18 meses,2 años",0,$garantia); ?>
			</select>
		</td>
	  </tr>
	  <tr>
		<td valign="top" colspan="10"><div style="width:100%; height:5px; background:#ccc; margin:15px 0;"></div></td>
	  </tr>
	  <tr>
		<td valign="top"><div align="right"><strong>Orden</strong></div></td>
		<td><input type="text" name="orden" id="orden" value="<?=$orden ?>"  class="form-control"/></td>
	  </tr>
	  <tr>
		<td valign="top"><div align="right"><strong>Visualizaciones</strong></div></td>
		<td><input type="text" name="visualizaciones" id="visualizaciones" value="<?=$visualizaciones ?>"  class="form-control"/></td>
	  </tr>
	  <tr>
		<td valign="top">&nbsp;</td>
		<td>&nbsp;</td>
	  </tr>
	  <tr>
		<td valign="top">&nbsp;</td>
		<td align="right">
		<input type="button" name="Enviar" value="Guardar" onClick="enviar('nombre,categoria');" >
	  </form>
		<p>
		<form action="" method="post" name="eliminar">
		<input type="submit" name="Borrar" id="borrar" value="Borrar">
		<input type="hidden" name="id_directorio" value="<?=$id_directorio?>">
		<input type="hidden" name="funcion" value="borrar">
		</form>
		</p>
		</td>
	  </tr>
	</table>
<?php

}

?>