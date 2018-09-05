<?php
/******************************************************************************************************************
FUNCIONES DE USUARIOS
1. iniciar_session($us_login,$us_pass,$db) - Inicio sesion usuario generales
2. session_permiso($modulo) - verifica permisos de usuario al panel de admin
3. permiso_modulo($modulo) - Verifica si un usuario puede ingresar al admin de un modulo
4. listado_paises($seleccionado,$defecto) - carga select con options de paises
5. inicio_usuario_admin($usu,$pass,$db) - inicio session usuario admin
6. cerrar_sesion($archivo) - cierra sesion y envia al archivo indicado
7. crear_usuario($us_id, $us_codigo_ref, $us_nombre, $us_pais, $us_telefono, $us_email, $us_direccion, 
   $us_nacimiento, $us_estado_civil, $us_sexo, $us_descripcion, $us_login, $us_pass, $grupo_defecto, $db) - Registro de usuario generales
8. modificar_usuario($us_id, $us_codigo_ref, $us_nombre, $us_pais, $us_telefono, $us_email, $us_direccion, 
   $us_nacimiento, $us_estado_civil, $us_sexo, $us_descripcion, $us_login, $us_pass, $db) - actualiza info usuarios generales
9. enviar_cuenta($correo_recordar, $db) - Envia contraseña a usuario
10. listado_estados_us($seleccionado,$defecto) - listado de estados de usa
11. registrar_newsletter($correo_news, $db) - Regsitra info a bd de news
******************************************************************************************************************/

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function iniciar_session($us_login,$us_pass,$destino,$db){	
	if($destino=="network"){
		$sql="	SELECT 
				usu.us_id, usu.us_nombre, usu.us_email, grus.gru_id, gru.gru_titulo
			FROM 
				ic_usuarios usu, ic_usu_gru grus, ic_grupo gru
			WHERE
				usu.us_codigo_ref='".$us_login."' AND
				grus.id=usu.us_id AND
				gru.gru_id=grus.gru_id AND
				usu.us_estado='1' ";
	}else{
		$sql="	SELECT 
				usu.us_id, usu.us_nombre, usu.us_email, grus.gru_id, gru.gru_titulo
			FROM 
				ic_usuarios usu, ic_usu_gru grus, ic_grupo gru
			WHERE
				usu.us_login='".$us_login."' AND
				usu.us_pass='".$us_pass."' AND
				grus.id=usu.us_id AND
				gru.gru_id=grus.gru_id AND
				usu.us_estado='1' ";
	}
	$result=$db->Execute($sql);

	if(!$result->EOF)
	{			
		list($usuario_id,$usuario_nom,$usuario_email,$usuario_gruid,$usuario_grutitulo)=select_format($result->fields);
		
		$sql="SELECT modulos FROM ic_privilegios WHERE id_grupo='".$usuario_gruid."' ";				
		$result=$db->Execute($sql);		
		list($modulos)=select_format($result->fields);
		
		if($modulos!="" || $modulos!=NULL)
		{
			$_SESSION['usuario']="admin";		
			$_SESSION['permisos']=$modulos;
		}else{
			$_SESSION['usuario']="none";		
			$_SESSION['permisos']="";
		}
		
		$sql="SELECT 
				 us_id, us_codigo_ref, us_nombre, us_pais, us_telefono, us_email, us_direccion, us_nacimiento,
				 us_last_name, us_sexo, us_descripcion, us_login, us_pass, us_postal, us_ciudad, us_estado_us,us_appartment, us_descripcion 
			  FROM ic_usuarios
			  WHERE us_id ='".$usuario_id."' ";
				
		$result=$db->Execute($sql);
	
		list($us_id, $us_codigo_ref, $us_nombre, $us_pais, $us_telefono, $us_email, $us_direccion, $us_nacimiento,
			 $us_last_name, $us_sexo, $us_descripcion, $us_login, $us_pass, $us_postal, $us_ciudad, $us_estado_us, $us_appartment,$us_network)=select_format($result->fields);
			 
		$_SESSION['sess_usu_id']=$usuario_id;		
		$_SESSION['sess_usu_grupo']=$usuario_gruid;	
		$_SESSION['sess_usu_gru_nombre']=$usuario_grutitulo;
		
		$_SESSION['us_codigo_ref']=$us_codigo_ref;
		$_SESSION['us_network']=$us_network;
		
		$_SESSION['sess_nombre']=$us_nombre;
		$_SESSION['sess_last_nombre']=$us_last_name;
		$_SESSION['sess_usu_email']=$us_email;
		$_SESSION['sess_usu_pais']=$us_pais;
		$_SESSION['sess_usu_telefono']=$us_telefono;
		$_SESSION['sess_usu_direccion']=$us_direccion;
		$_SESSION['sess_usu_postal']=$us_postal;
		$_SESSION['sess_usu_appartment']=$us_appartment;
		$_SESSION['sess_usu_ciudad']=$us_ciudad;
		$_SESSION['sess_usu_estado_us']=$us_estado_us;
		$_SESSION['sess_usu_postal']=$us_postal;
			
		/////////////////////////////////
		
		registrar_cambios($db,  date('Y-m-d'), $_SESSION['sess_nombre'], '', '', '', 'INICIO_SESSION');
		
		if($destino=="network"){
				
				
				if(isset($_SESSION['url_visto']) && $_SESSION['url_visto']!=""){
					echo ''.$_SESSION['url_visto'].'';
				}else{
					echo "usuarios.html";
				}
		}else{
			if(isset($_SESSION['url_visto']) && $_SESSION['url_visto']!=""){
				echo '<META HTTP-EQUIV="Refresh" CONTENT="0;URL='.$_SESSION['c_base_location'].''.$_SESSION['url_visto'].'">';
			}else{
				echo '<META HTTP-EQUIV="Refresh" CONTENT="0;URL='.$_SESSION['c_base_location'].'usuarios.html">';
			}
		}
		die();
		
	}else{
		crear_usuario($us_login,$us_pass);
	}
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function session_permiso($modulo){
	if(!isset($_SESSION['usuario'])){
		echo '<META HTTP-EQUIV="Refresh" CONTENT="0;URL='.$_SESSION['c_base_location'].'admin.php">';
		die();
	}elseif($_SESSION['usuario']!="admin")
	{		
		echo '<META HTTP-EQUIV="Refresh" CONTENT="0;URL='.$_SESSION['c_base_location'].'admin.php">';
		die();
	}elseif($_SESSION['permisos']=="all" ){
		/*Permisos totales*/
	}else{
		if ($_SESSION['permisos']!="all" && $_SESSION['permisos']!=""){	
			$permisos_usuario = $_SESSION['permisos'];
			
			$permisos_usuario = explode("*",$permisos_usuario);
			
			$esta=false;
			
			for($i=0;$i<count($permisos_usuario);$i++){
				if($permisos_usuario[$i]==$modulo){
					$esta=true;
					break;
				}else{
					$esta=false;
				}
			}
			
			if(!$esta){
				echo '<META HTTP-EQUIV="Refresh" CONTENT="0;URL='.$_SESSION['c_base_location'].'admin.php">';
				die();
			}
			
		}else{
			echo '<META HTTP-EQUIV="Refresh" CONTENT="0;URL='.$_SESSION['c_base_location'].'admin.php">';
			die();
		}
	}
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function permiso_modulo($modulo){

	$permisos_usuario = $_SESSION['permisos'];
	if($permisos_usuario=="all"){
		return true;
	}else{
		$permisos_usuario = explode("*",$permisos_usuario);
		
		$esta=false;
		
		for($i=0;$i<count($permisos_usuario);$i++){
			if($permisos_usuario[$i]==$modulo){
				$esta=true;
				break;
			}
		}	
		return $esta;
	}
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function listado_paises($seleccionado,$defecto){
	//Paises
	$paises = "United States (USA),Afghanistan,Albania,Algeria,Andorra,Angola,Antigua and Barbuda,Argentina,Armenia,Aruba,Australia,Austria,Azerbaijan,Bahamas,Bahrain,Bangladesh,Barbados,Belarus,Belgium,Belize,Benin,Bhutan,Bolivia,Bosnia and Herzegovina,Botswana,Brazil,Brunei,Bulgaria,Burkina Faso,Burma,Burundi,Cambodia,Cameroon,Canada,Cape Verde,Central African Republic,Chad,Chile,China,Colombia,Comoros,Congo - Democratic Republic of the,Congo - Republic of the,Costa Rica,Croatia,Cuba,Curacao,Cyprus,Czech Republic,Denmark,Djibouti,Dominica,Dominican Republic,East Timor (see Timor-Leste),Ecuador,Egypt,El Salvador,Equatorial Guinea,Eritrea,Estonia,Ethiopia,Fiji,Finland,France,Gabon,Gambia, The,Georgia,Germany,Ghana,Greece,Grenada,Guatemala,Guinea,Guinea-Bissau,Guyana,Haiti,Holy See,Honduras,Hong Kong,Hungary,Iceland,India,Indonesia,Iran,Iraq,Ireland,Israel,Italy,Jamaica,Japan,Jordan,Kazakhstan,Kenya,Kiribati,Korea, North,Korea, South,Kosovo,Kuwait,Kyrgyzstan,Laos,Latvia,Lebanon,Lesotho,Liberia,Libya,Liechtenstein,Lithuania,Luxembourg,Macau,Macedonia,Madagascar,Malawi,Malaysia,Maldives,Mali,Malta,Marshall Islands,Mauritania,Mauritius,Mexico,Micronesia,Moldova,Monaco,Mongolia,Montenegro,Morocco,Mozambique,Namibia,Nauru,Nepal,Netherlands,Netherlands Antilles,New Zealand,Nicaragua,Niger,Nigeria,North Korea,Norway,Oman,Pakistan,Palau,Palestinian Territories,Panama,Papua New Guinea,Paraguay,Peru,Philippines,Poland,Portugal,Qatar,Romania,Russia,Rwanda,Saint Kitts and Nevis,Saint Lucia,Saint Vincent and the Grenadines,Samoa,San Marino,Sao Tome and Principe,Saudi Arabia,Senegal,Serbia,Seychelles,Sierra Leone,Singapore,Sint Maarten,Slovakia,Slovenia,Solomon Islands,Somalia,South Africa,South Korea,South Sudan,Spain,Sri Lanka,Sudan,Suriname,Swaziland,Sweden,Switzerland,Syria,Taiwan,Tajikistan,Tanzania,Thailand,Timor-Leste,Togo,Tonga,Trinidad and Tobago,Tunisia,Turkey,Turkmenistan,Tuvalu,Uganda,Ukraine,United Arab Emirates,United Kingdom,Uruguay,Uzbekistan,Vanuatu,Venezuela,Vietnam,Yemen,Zambia,Zimbabwe,";

	$paises = explode(",",$paises);
	$selected= "";	

	if ($defecto == "1") { 
		echo "<option value='' >"._SELECCIONAR."...</option>"; 
	}

	for($i=0;$i<count($paises); $i++){		

		if($paises[$i]==$seleccionado){
			$selected= "selected='selected'";
		}else{
			$selected= "";
		}

		echo"<option value='".trim($paises[$i])."' ".$selected." style='font-size: 10px;'>".trim($paises[$i])."</option>";
	}	
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function inicio_usuario_admin($usu,$pass,$db){
	if($_POST['id']!=session_id()){
		die('<META HTTP-EQUIV="Refresh" CONTENT="0;URL='.$_SESSION['c_base_location'].'index.php">');
	}
	
	$path = "adodb/adodb.inc.php";
	include ("admin/var.php");
	include ("conexion.php");
	
	$sql="SELECT c_usu,c_pass FROM ic_config ";
	$result=$db->Execute($sql);
		
	list($usuar,$passw)=select_format($result->fields);
	
	if(($usu==$usuar)&&($pass==$passw)){	
		$_SESSION['usuario']="admin";		
		$_SESSION['permisos']="all";
		$_SESSION['sess_nombre']=_ADMIN_SISTEMA;
		
		registrar_cambios($db,  date('Y-m-d'), $_SESSION['sess_nombre'], '', '', '', 'INICIO_SESSION');
		
		echo '<META HTTP-EQUIV="Refresh" CONTENT="0;URL='.$_SESSION['c_base_location'].'admin.php">';
		die();
	}	
	$result->close();
	return "true";
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function cerrar_sesion($archivo){
	echo '<META HTTP-EQUIV="Refresh" CONTENT="0;URL='.$_SESSION['c_base_location'].''.$archivo.'">';
	session_unset();
	session_destroy();
	die();
}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function crear_usuario($usuario,$password)
{
	$path = "adodb/adodb.inc.php";
	include ("admin/var.php");
	include ("conexion.php");
		
	$sql1_1="SELECT us_email FROM ic_usuarios WHERE us_email='".$usuario."' "; 	
	$result1_1=$db->Execute($sql1_1);
	list($e_mail_existe)=$result1_1->fields;
	
	if( ( $e_mail_existe == "" || $e_mail_existe == NULL ) ){
			
		$result1=insert_bd_format("us_email,us_creacion,us_login,us_pass,us_estado", 
							 "ic_usuarios", 
								 array($usuario,date("Y-m-d"),$usuario,$password,"1"), 
								 $db);
							 
		$sql1_1="SELECT us_id FROM ic_usuarios WHERE us_email='".$usuario."' "; 	
		$result1_1=$db->Execute($sql1_1);
		list($id_nuevo)=$result1_1->fields;
		
		$sql="INSERT INTO ic_usu_gru (gru_id,id) 
					VALUES('2','".$id_nuevo."')"; 	
		$result2=$db->Execute($sql);
		
		////////////////////////
		
		$adicional_formato="Email: ".$usuario."<br>Contraseña: ".$password;
		enviar_notificacion($usuario, "", "REGISTRO", "1", $adicional_formato) ;
		
		////////////////////////
		
		if (($result1 != false)&&($result2 != false)) 
		{
			iniciar_session($usuario,$password,"",$db);
		}		
	}else{
		echo"<script type='text/javascript'>
				alert('Tus datos de acceso son incorrectos, por favor confirma o recoda tu password!');
			 </script>";
		echo '<META HTTP-EQUIV="Refresh" CONTENT="0;URL=usuarios.html">';
	}
	
	$db->close();
}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function modificar_usuario($us_id, $us_codigo_ref, $us_nombre, $us_pais, $us_telefono, $us_email, $us_direccion, $us_nacimiento,
			 				$us_last_name, $us_sexo, $us_descripcion, $us_login, $us_pass, $us_postal, $us_ciudad, $us_estado_us, 
							$us_appartment, $us_foto, $funcion,$us_desc_prof,$us_desc_pers,$us_matricula,$us_referencia,$us_dni,$us_cuit,
							$us_constancia,$us_tipo_serv)
{
	$path = "adodb/adodb.inc.php";
	include ("admin/var.php");
	include ("conexion.php");
	
	$dir = "images/usuarios/";
	
	//---------------------------------------------------------------------
	
	$url_full = $_FILES['us_foto']['tmp_name'];
	$imagen_full = $_FILES['us_foto']['name']; 
	
	$img_full = "";	
	$img_full1 = "";	
	
	if($url_full!="" && $imagen_full!=""){
		$alt = mt_rand();
				
		if(subir_imagen($alt.$imagen_full, $url_full, $dir))
		{
			
			$img_full = $dir.$alt.$imagen_full;
			$img_full1 = "us_foto";
			
		}else{
			echo"<META HTTP-EQUIV='Refresh' CONTENT='1;URL=info-usuario.html'>";
			die("Error Cargando la Imagen");
		}
	}
	//---------------------------------------------------------------------
		
	$url_full = $_FILES['us_constancia']['tmp_name'];
	$imagen_full = $_FILES['us_constancia']['name']; 
	
	$us_constancia_full = "";	
	$us_constancia1 = "";	
	
	if($url_full!="" && $imagen_full!=""){
		$alt = mt_rand();
				
		if(subir_imagen($alt.$imagen_full, $url_full, $dir))
		{
			
			$us_constancia_full = $dir.$alt.$imagen_full;
			$us_constancia1 = "us_constancia";
			
		}else{
			echo"<META HTTP-EQUIV='Refresh' CONTENT='1;URL=info-usuario-premium.html'>";
			die("Error Cargando la Imagen");
		}
	}
	
	//---------------------------------------------------------------------
	
	$sql1_1="SELECT us_pass, plan_wl, us_desc_prof, us_desc_pers, us_matricula, us_cuit   FROM ic_usuarios WHERE us_email='".$us_email."' "; 	
	$result1_1=$db->Execute($sql1_1);
	list($us_pass_old,$plan_wl,$us_desc_prof_old,$us_desc_pers_old,$us_matricula_old,$us_cuit_old)=$result1_1->fields;
	
	if($us_pass_old!=$us_pass){
		enviar_notificacion($us_email, $us_nombre, "NOTIFI_CAMBIO_PASS", "1", "");
	}
	
	if($us_desc_prof_old=="" && $us_desc_prof!="" && $us_cuit_old=="" && $us_cuit!=""){
		//enviar_notificacion($us_email, $us_nombre, "NEW_PREMIUM", "1", "");
		
		//enviar_notificacion("contacto@worklife.com.ar", $us_nombre, "NEW_PREMIUM_ADMIN", "1", $us_desc_prof);
	}
	
	$us_login=$us_email;
	
	if($funcion == "modificar_usuario"){
		$result=update_bd_format(array("us_nombre","us_pais","us_telefono","us_email","us_direccion","us_last_name",
									   "us_sexo","us_pass","us_login","us_postal","us_ciudad","us_estado_us",
									   "us_appartment","us_nacimiento",$img_full1), 
								 "ic_usuarios", 
								 array($us_nombre,$us_pais,$us_telefono,$us_email,$us_direccion,
									   $us_last_name,$us_sexo,$us_pass,$us_login,$us_postal,
									   $us_ciudad,$us_estado_us,$us_appartment,$us_nacimiento,$img_full), 
								 "WHERE us_id='".$us_id."'", 
								 $db);
								 
		$_SESSION['sess_usu_id']=$us_id;		
		$_SESSION['sess_nombre']=$us_nombre;
		$_SESSION['sess_last_nombre']=$us_last_name;
		$_SESSION['sess_usu_email']=$us_email;
		$_SESSION['sess_usu_pais']=$us_pais;
		$_SESSION['sess_usu_telefono']=$us_telefono;
		$_SESSION['sess_usu_direccion']=$us_direccion;
		$_SESSION['sess_usu_postal']=$us_postal;
		$_SESSION['sess_usu_appartment']=$us_appartment;
		$_SESSION['sess_usu_ciudad']=$us_ciudad;
		$_SESSION['sess_usu_estado_us']=$us_estado_us;
		$_SESSION['sess_usu_postal']=$us_postal;
	}else{
		$result=update_bd_format(array("us_desc_prof","us_desc_pers",
									   "us_matricula","us_referencia","us_dni","us_cuit",$us_constancia1,"us_tipo_serv"), 
								 "ic_usuarios", 
								 array($us_desc_prof,$us_desc_pers,$us_matricula,$us_referencia,$us_dni,$us_cuit,
									   $us_constancia_full,$us_tipo_serv), 
								 "WHERE us_id='".$us_id."'", 
								 $db);
	}
	
	
	if ($result != false) 
	{
		if($funcion == "modificar_usuario"){
			echo '<META HTTP-EQUIV="Refresh" CONTENT="0;URL='.$_SESSION['c_base_location'].'info-usuario.html">';
		}else{
			if($plan_wl!="Basic"){
				echo "<script type='text/javascript'>
					alert('Gracias por completar tu perfil PREMIUM, validaremos la informacion y te contactaremos para realizar la actualizacion de tu cuenta.');
				</script>";
			}
			echo '<META HTTP-EQUIV="Refresh" CONTENT="0;URL='.$_SESSION['c_base_location'].'info-usuario-premium.html">';
		}
		die();
	}
	else
	{
		echo"<script type='text/javascript'>
				alert('"._MSG_NO_ACTUALIZO."');
		 	</script>";
		echo '<META HTTP-EQUIV="Refresh" CONTENT="0;URL='.$_SESSION['c_base_location'].'info-usuario.html">';
		die();
	}
	
	$db->close();
}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function enviar_cuenta($correo_recordar, $db){
	
	$sql1_1="SELECT us_email, us_login, us_pass, us_nombre, us_codigo_ref, us_descripcion
			 FROM ic_usuarios 
			 WHERE us_email='".$correo_recordar."' "; 	
	$result1_1=$db->Execute($sql1_1);
	list($e_mail_existe, $us_login, $us_pass, $us_nombre, $us_codigo_ref,$us_descripcion)=select_format($result1_1->fields);
		
	if($e_mail_existe==""){	
		echo"<script type='text/javascript'>
					alert('Em email no existe en nuestra base de datos.);
				</script>";
		echo '<META HTTP-EQUIV="Refresh" CONTENT="0;URL='.$_SESSION['c_base_location'].'recordar-clave.html">';
		die();
	}
	
	////////////////////////
	
	if($us_codigo_ref!=""){
		$adicional_formato="INGRESO POR REDES SOCIALES // ".strtoupper($us_descripcion)."";
	}else{
		$adicional_formato="Email: ".$e_mail_existe."<br>Contraseña: ".$us_pass;
	}
	enviar_notificacion($e_mail_existe, $us_nombre, "RECORDAR_PASS", "1", $adicional_formato) ;
	
	////////////////////////
	
	
	if(true){	
		echo"<script type='text/javascript'>
					alert('Se ha enviado el email. Por favor verifique sus datos e ingrese a su cuenta.');
				</script>";
		echo '<META HTTP-EQUIV="Refresh" CONTENT="0;URL='.$_SESSION['c_base_location'].'recordar-clave.html">';
		die();
	}else{
		echo"<script type='text/javascript'>
					alert('Ha ocurrido un error, por favor comunicate con contacto@worklife.com.ar');
				</script>";
		echo '<META HTTP-EQUIV="Refresh" CONTENT="0;URL='.$_SESSION['c_base_location'].'recordar-clave.html">';
		die();
	}
	
	$db->close();
}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function listado_estados_us($seleccionado,$defecto){
	//ciudades
	$paises = "CIUDAD AUTONOMA DE BUENOS AIRES,CIUDAD AUTONOMA DE BUENOS AIRES,BUENOS AIRES,BUENOS AIRES,CATAMARCA,CATAMARCA,CHACO,CHACO,CHUBUT,CHUBUT,CORDOBA,CORDOBA,CORRIENTES,CORRIENTES,ENTRE RIOS,ENTRE RIOS,FORMOSA,FORMOSA,JUJUY,JUJUY,LA PAMPA,LA PAMPA,LA RIOJA,LA RIOJA,MENDOZA,MENDOZA,MISIONES,MISIONES,NEUQUEN,NEUQUEN,RIO NEGRO,RIO NEGRO,SALTA,SALTA,SAN JUAN,SAN JUAN,SAN LUIS,SAN LUIS,SANTA CRUZ,SANTA CRUZ,SANTA FE,SANTA FE,SANTIAGO DEL ESTERO,SANTIAGO DEL ESTERO,TIERRA DEL FUEGO,TIERRA DEL FUEGO,TUCUMAN,TUCUMAN";

	$paises = explode(",",$paises);
	$selected= "";

	if ($defecto == "1") {
		echo "<option value='' >"._SELECCIONAR."...</option>";
	}

	for($i=0;$i<count($paises); $i++){

		if($paises[($i+1)]==$seleccionado){
			$selected= "selected='selected'";
		}else{
			$selected= "";
		}

		echo"<option value='".$paises[($i+1)]."' ".$selected." style='font-size: 10px;'>".$paises[$i]."</option>";

		$i++;
	}
}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function registrar_newsletter($correo_news, $db){
	
	$sql1_1="SELECT us_email FROM ic_usuarios WHERE us_email='".$correo_news."' "; 	
	$result1_1=$db->Execute($sql1_1);
	list($e_mail_existe)=select_format($result1_1->fields);
	
	if($e_mail_existe!=""){	
		echo"<script type='text/javascript'>
					alert('This email already registered on our newsletter. Thank you.');
				</script>";
		echo '<META HTTP-EQUIV="Refresh" CONTENT="0;URL='.$_SESSION['c_base_location'].'index.php">';
		die();
	}
	
	$result1=insert_bd_format("us_email", "ic_usuarios", array($correo_news), $db);
	
	if($result1 != false){	
		echo"<script type='text/javascript'>
					alert('Thanks for signing in Mexico Video newsletter here. Soon we will be in touch with you.');
				</script>";
		echo '<META HTTP-EQUIV="Refresh" CONTENT="0;URL='.$_SESSION['c_base_location'].'index.php/registro/ok">';
		die();
	}else{
		echo"<script type='text/javascript'>
					alert('Had a problem and have not registered the inforacion. Please try again later. Thank you.');
				</script>";
		echo '<META HTTP-EQUIV="Refresh" CONTENT="0;URL='.$_SESSION['c_base_location'].'index.php/registro/error">';
		die();
	}
	
	$db->close();
}

?>