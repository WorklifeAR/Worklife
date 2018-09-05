<?php
/******************************************************************************************************************
FUNCIONES GENERALES DEL APP
1. idioma_session($db) - Carga en session parametro de idioma defecto
2. select_format($array_fields) - formatea el restultado de una consulta
3. insert_bd_format($campos, $tabla, $inserts, $db) - formatea e inserta un registro en la bd
4. update_bd_format($campos, $tabla, $updates, $where, $db) - formatea y actualiza un registro en la bd
5. cargar_tags($mod, $identificador, $c_cabeceras, $c_titulo, $c_tag_descripcion, $db) - devuelve las cabeceras teniendo en cuenta que pagian carga
6. variables_metodo($parametros) - Captura parametros de los metodos de envio
7. cargar_lista($tabla,$campos,$orden,$defecto,$selected,$where,$db) - carga un resultado de una consulta en options
8. cargar_lista_estatica($valores,$label,$defecto,$seleccionado) - Carga un listado texto en options
9. cargar_lista_multiple($tabla,$campos,$orden,$defecto,$selected,$where,$db) - carga un resultado de una consulta en options para un select multiple
10. subir_imagen($imagen,$url,$dir) - carga un archivo a los directorios especificados
11. tamano($dir,$image,$alt,$tamanox,$tamanoy) - redimensiona una imagen teniendo en cuenta los parametros enviados
12. agregar_iva($valor,$iva) - Adiciona a un valor el valor iva enviado
13. reemplazar_escape($contenido) - elimina caracteres espace de una cadena
14. my_bcmod( $x, $y ) - bcmod
15. registrar_cambios($db, $fecha, $usuario_cambio, $modulo, $id_item, $titulo, $actividad) - guarda log de actividades
16. limpiar_contenido_oracle($contenido) - limpia caracteres & de cadenas para bd oracle
17. mostrarContenidosEmbebidos($contenido, $db) - reemplaza script de insercion con contenido de modulos de galerias
18. generateRandString($numChars) - genera cadena aleatoria
******************************************************************************************************************/

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function idioma_session($db){ 

	if(isset($_SESSION['idioma'])){
		if($_SESSION['idioma']==""){
				$_SESSION['idioma']="_en";		
		}
	}else{
		$_SESSION['idioma']="_en";		
	}
	
	$result=$db->Execute("SELECT c_lang FROM ic_config");
	list($idioma_activo)=select_format($result->fields);
	
	if($idioma_activo=="1"){
		$variables_ver = variables_metodo("lang");
		$lang= 				$variables_ver[0];
	
		if($lang!=""){
			if(file_exists("language/language".$lang.".php")){
				$_SESSION['idioma']=$lang;
			}					
		}
	}
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function select_format($array_fields){
	$array=array();
	
	for($i=0;$i<count($array_fields);$i++){
		if(isset($array_fields[$i])){
			array_push($array, reemplazar_escape($array_fields[$i]));
		}else{
			array_push($array, "");
		}
	}
	
	return $array;
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function insert_bd_format($campos, $tabla, $inserts, $db){
	
	$insert="";
	
	for($i=0; $i<count($inserts); $i++){
		$insert .= "'".utf8_decode(addslashes($inserts[$i]))."'";
		
		if(($i+1)!=count($inserts)){
			$insert .= ",";
		}
	}
	
	$sql="INSERT INTO ".$tabla." (".$campos.") VALUES (".$insert.")";
	$result=$db->Execute($sql);
	
	return $result;
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function update_bd_format($campos, $tabla, $updates, $where, $db){
	
	$update="";
	
	if(count($campos)!=count($updates)){
		return false;
	}
	
	for($i=0; $i<count($campos); $i++){
			
		if(trim($campos[$i])!=""){
			$update .= "".$campos[$i]."='".utf8_decode(addslashes($updates[$i]))."'";
		}
		
		if(($i+1)<count($campos) && trim($campos[$i+1])!=""){
			$update .= ",";
		}
	}
	
	$sql="UPDATE ".$tabla." SET ".$update." ".$where;
	$result=$db->Execute($sql);
	
	return $result;
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function cargar_tags($mod, $identificador, $c_cabeceras, $c_titulo, $c_tag_descripcion, $db){
	$sql="";
	$array=array();
	
	if($mod!="" && $identificador!=""){
		
		if($mod=="contenido"){
			$sql="SELECT pg_tag_titulo,pg_tag_desc,pg_tag_key FROM ic_contenido WHERE pg_id='".$identificador."'";
		}elseif($mod=="noticias"){
			$sql="SELECT n_tag_titulo,n_tag_desc,n_tag_key FROM ic_noticias WHERE n_id='".$identificador."'";
		}
		
		$result=$db->Execute($sql);		
		list($pg_tag_titulo,$pg_tag_desc,$pg_tag_key)=$result->fields;
				
		if($pg_tag_titulo!=""){
			array_push($array, $c_titulo." - ".$pg_tag_titulo);
		}else{
			array_push($array, $c_titulo);
		}
		
		if($pg_tag_key!=""){
			array_push($array, $pg_tag_key);
		}else{
			array_push($array, $c_cabeceras);
		}
		
		if($pg_tag_desc!=""){
			array_push($array, $pg_tag_desc);
		}else{
			array_push($array, $c_tag_descripcion);
		}
	}else{
		array_push($array, $c_titulo);
		array_push($array, $c_cabeceras);
		array_push($array, $c_tag_descripcion);
	}
	return $array;
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function cargar_tags_facebook($mod, $identificador, $db){
	$tags="";
	
	if($mod!="" && $identificador!=""){
		
		if($mod=="producto"){
			$sql="SELECT nombre_item, full_item, img_previa FROM ic_catalogo WHERE id_item='".$identificador."'";
		}elseif($mod=="noticias"){
			$sql="SELECT n_tag_titulo,n_tag_desc,n_imagen FROM ic_noticias WHERE n_id='".$identificador."'";
		}
		
		$result=$db->Execute($sql);		
		list($pg_tag_titulo,$pg_tag_desc,$pg_tag_img)=$result->fields;
			
		
			
		if($pg_tag_titulo!=""){
			$tags.='<meta property="og:title" content="'.strtoupper($pg_tag_titulo).'" />
';
		}
		$tags.='<meta property="og:type" content="website" />
<meta property="og:url" content="" />';

		if($pg_tag_desc!=""){
			$tags.='<meta property="og:description" content="'.strip_tags($pg_tag_desc).'" />
';
		}

		if($pg_tag_img!=""){
			$tags.='<meta property="og:image" content="'.$_SESSION['c_base_location'].''.$pg_tag_img.'" />';
		}
	}
	
	return $tags;
}


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function variables_metodo($parametros){	
	$url_get = "";
	
	if(isset($_SERVER['PATH_INFO'])){
		$url_get = explode("/", $_SERVER['PATH_INFO']);
	}
	
	$variable_metodo="";
	$parametros=explode(",",$parametros);
	
	for($i=0; $i<count($parametros);$i++)
	{
		if(isset($_POST[$parametros[$i]]))
		{
			$valor_gp = $_POST[$parametros[$i]];
			if(!strstr($_SERVER['PHP_SELF'], "admin.php") && !strstr($_SERVER['PHP_SELF'], "user.php") && !strstr($_SERVER['PHP_SELF'], "listado-servicios.php")){
				$valor_gp = strip_tags($valor_gp);
			}
			$variable_metodo[$i]=$valor_gp;
		}
		elseif ($url_get!="" && array_search($parametros[$i], $url_get))
		{
			$id_matriz = array_search($parametros[$i], $url_get);
			
			if(array_key_exists(($id_matriz+1), $url_get) && my_bcmod(($id_matriz+1), 2)==0 ){
				$variable_metodo[$i]=strip_tags($url_get[($id_matriz+1)]);
			}else{
				$variable_metodo[$i]="";
			}
		}
		elseif(isset($_GET[$parametros[$i]]))
		{
			if(!is_array($_GET[$parametros[$i]])){
				$variable_metodo[$i]=strip_tags($_GET[$parametros[$i]]);
			}else{
				$variable_metodo[$i]=$_GET[$parametros[$i]];
			}
		}else
		{
			$variable_metodo[$i]="";
		}
	}

	return $variable_metodo;
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function cargar_lista($tabla,$campos,$orden,$defecto,$selected,$where,$db){

$sql="SELECT ".str_replace(";",",",$campos)." FROM ".$tabla." ";

if($where!=""){	$sql .= $where; }

if($orden!=""){ $sql .= " ORDER BY ".$orden." "; }

	$result=$db->Execute($sql);
	
	if ($defecto == "1") { echo "<option value='' >"._SELECCIONAR."...</option>"; }

    while(! $result->EOF)
	{
		$datos=$result->fields;
		
		if ($selected==$datos[0])
		{ 
			$select="selected='selected'"; 
		}
		else
		{
			$select=""; 
		}
		
		echo "<option value='".$datos[0]."' ".$select." >";
		
		$largo = substr_count ( $campos, ",");
		
		if($tabla!="ic_language" && $tabla!="ic_referencias_items"){
			echo "ID".utf8_encode($datos[0])." | ";
		}
		
		for ($i=1; $i<=$largo; $i++)
		{
			if($tabla=="ic_language" || $tabla=="ic_referencias_items"){
				echo utf8_encode($datos[$i])." ";
			}else{
				echo utf8_encode($datos[$i])." | ";
			}
		}
		echo "</option>";
	
	$result->MoveNext();
	}
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function cargar_lista_estatica($valores,$label,$defecto,$seleccionado){
	
	$valores = explode(",",$valores);
	$label = explode(",",$label);
	
	$selected= "";
	
	if ($defecto == "1") { 
		echo "<option value='' >"._SELECCIONAR."...</option>"; 
	}
	
	for($i=0;$i<count($label); $i++){
		
		if($valores[$i]==$seleccionado){
			$selected= "selected='selected'";
		}else{
			$selected= "";
		}
		
		echo"<option value='".$valores[$i]."' ".$selected." >".$label[$i]."</option>";
	}	
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function cargar_lista_multiple($tabla,$campos,$orden,$defecto,$selected,$where,$db){

$sql="SELECT ".$campos." FROM ".$tabla." ";

if($where!=""){	$sql .= $where; }

if($orden!=""){ $sql .= " ORDER BY ".$orden." "; }

	$result=$db->Execute($sql);
	
	if ($defecto == "1") { echo "<option value='' >"._SELECCIONAR."...</option>"; }

    while(! $result->EOF)
	{
		$datos=$result->fields;
		
		if ($selected!=""){ 
			$valores = explode("**",$selected);
			
			for($i=0; $i<count($valores);$i++){
				$val = str_replace("*","",$valores[$i]);
				$select="";
				
				if($val==$datos[0]){
					$select="selected='selected'"; 
					break;
				}
			}
		}else{
			$select=""; 
		}
		
		echo "<option value='".$datos[0]."' ".$select." >";
		
		$largo = substr_count ( $campos, ",");
		
		for ($i=1; $i<=$largo; $i++)
		{
			echo utf8_encode($datos[$i])." ";
		}
		echo "</option>";
	
	$result->MoveNext();
	}
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function subir_imagen($imagen,$url,$dir){

	if(copy($url, $dir.$imagen)){	return true;	}
	else{	return false;	}
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function tamano($dir,$image,$alt,$tamanox,$tamanoy){
	$nuevo = $image;
	$image = $dir.$image;
	$xSize=$tamanox;
	$ySize=$tamanoy;
	$needed_user_factor="";
	$new_image_path = $dir.$alt.$nuevo;
	
	if($xSize == 0 || $ySize == 0)
	return array("At the leat one pair of size is 0",0,0);
	
	$size = getImageSize($image);
	$width = $size[0];
	$height = $size[1];
	$x_ratio = $xSize / $size[0];
	$y_ratio = $ySize / $size[1];
	
	if($x_ratio < $y_ratio)    $res_ratio = $y_ratio;
	else $res_ratio = $x_ratio;
	
	if(abs($res_ratio - 1) > $needed_user_factor){
		$width = ceil($width * $res_ratio);
		$height = ceil($height * $res_ratio);
	}
	
	$fileinfo = pathinfo($image);
	$file_type = strtolower($fileinfo["extension"]);
	
	if($file_type == "jpg" || $file_type == "jpeg")
		$src = ImageCreateFromJpeg($image);
	else if($file_type == "gif")
		$src = ImageCreateFromGif($image);
	else if($file_type == "png")
		$src = ImageCreateFromPNG($image);
	else if($file_type == "bmp")   
		$src = ImageCreateFromBmp($image);    //Thanks Previous Coder
	else
		return array("Unknown image format: ".$file_type, 0, 0); 
	
	$output_path = ($new_image_path == "")?$image:$new_image_path;
	$dst = ImageCreateTrueColor($width, $height);
	ImageCopyResized($dst, $src, 0,0,0,0,$width, $height, $size[0], $size[1]);
	
	if($file_type == "jpg" || $file_type == "jpeg")
		ImageJpeg($dst, $output_path, 100);
	else if($file_type == "gif" || $file_type == "bmp")
	 	ImageGif($dst, $output_path);
	else if($file_type == "png")
		ImagePNG($dst, $output_path);
	else
		return false;
	
	ImageDestroy($src);
	ImageDestroy($dst);
	
	unlink ($image); 
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function agregar_iva($valor,$iva){
	
	$resultado = ($valor*$iva)/100;
	
	$resultado = $valor + $resultado;
	
	return $resultado;
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function reemplazar_escape($contenido)
{
	$cadena = " \ ";

	$contenido = str_replace(trim($cadena), "", $contenido);
	$contenido = html_entity_decode($contenido);
	
	$encoding = mb_detect_encoding($contenido,"UTF-8, ISO-8859-1, GBK");

	if($encoding!="UTF-8"){	
		$contenido=utf8_encode($contenido);		
	}	

	

	return $contenido;
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function my_bcmod( $x, $y ){
   // how many numbers to take at once? carefull not to exceed (int)
   $take = 5;   
   $mod = '';

   do
   {
       $a = (int)$mod.substr( $x, 0, $take );
       $x = substr( $x, $take );
       $mod = $a % $y;   
   }
   while ( strlen($x) );

   return (int)$mod;
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function registrar_cambios($db, $fecha, $usuario_cambio, $modulo, $id_item, $titulo, $actividad){	
	$sql="INSERT INTO ic_registro_cambios 
	      VALUES ('".$fecha."','".$usuario_cambio."','".$modulo."','".$id_item."','".$titulo."','".$actividad."')";
	$result=$db->Execute($sql);
}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function limpiar_contenido_oracle($contenido){
	
	$contenido=str_replace("&","'||'&'||'",$contenido);
	
	return $contenido;
}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function mostrarContenidosEmbebidos($contenido, $db){
	$contenido = html_entity_decode ($contenido);
	
	//[!--{XXXX-NNNNNN}--] // XXXX -> Numero id a buscar // NNNNNN -> Nombre del modulo
	$contenido_formato = explode("[!--{", $contenido);
	
	$nuevo_contenido="";
	
	for($i=0;$i<count($contenido_formato);$i++){
		if($i==0){
			$nuevo_contenido .= $contenido_formato[$i];
		}else{
			$numero_idenficador = substr($contenido_formato[$i],0,4);
			$modulo_idenficador = substr($contenido_formato[$i],5,6);
						
			if($modulo_idenficador=="videos"){
				$sql="SELECT url_video,modo FROM ic_videos WHERE id_video='".number_format($numero_idenficador, 0)."' "; 	
				$result=$db->Execute($sql);
				list($video,$modo)=select_format($result->fields);
				
				if($modo!="codigo"){
					$video = '<object width="300" height="300" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0">
					 <param name="salign" value="lt" />
					 <param name="quality" value="high" /> 
					 <param name="scale" value="noscale" /> 
					 <param name="movie" value="flashplayerflv.swf" />
					 <param name="FlashVars" value="&image=images/spacer.gif&file='.$video.'&autoStart=false" />
					 <embed src="flashplayerflv.swf?image=images/spacer.gif&file='.$video.'&autoStart=false" width="300" height="300" quality="high" scale="noscale" salign="lt" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer">
					 </embed>
					 </object>';
				}
				
				$nuevo_contenido .= $video;
			}
			
			if($modulo_idenficador=="imagen"){
				$sql="SELECT img_full FROM ic_galeria_imagenes WHERE id_imagen='".number_format($numero_idenficador, 0)."' "; 	
				$result=$db->Execute($sql);
				list($img_full)=select_format($result->fields);
				
				$nuevo_contenido .= "<img src='".$img_full."' alt='' border='0' />";
			}
			
			$nuevo_contenido .= substr($contenido_formato[$i],15,strlen($contenido));
		}
	}
	
	return $nuevo_contenido;
}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function generateRandString($numChars){

	$chars = "0123456789";
	$charsCount = strlen($chars) - 1;
	$randString = "";
	
	for ($i=0; $i < $numChars; $i++){
		$num = rand(0, $charsCount);
		$randString .= $chars[$num];
	}
	
	$_SESSION["imageText"] = $randString;
	
	return $_SESSION["imageText"];
}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


function calcular_distancia($lat1, $lon1, $lat2, $lon2, $unit='K') 
{ 
  $theta = $lon1 - $lon2; 
  $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta)); 
  $dist = acos($dist); 
  $dist = rad2deg($dist); 
  $miles = $dist * 60 * 1.1515;
  $unit = strtoupper($unit);

  if ($unit == "K") {
    return ($miles * 1.609344); 
  } else if ($unit == "N") {
      return ($miles * 0.8684);
    } else {
        return $miles;
      }
}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


function enviar_notificacion($destino, $nombre, $tipo, $version, $adicional_formato) 
{ 
	if($destino!=""){
		$contenido = plantilla_email($version);
		$subject="";
		$email_salida="contacto@worklife.com.ar";
		
		$cabeceras = "MIME-Version: 1.0\r\n"; 
		$cabeceras .= "Content-type: text/html; charset=utf-8\r\n"; 
		
		//////////////////////////
		
		if($tipo=="REGISTRO"){
			$cabeceras .= "From:  Registro de usuario WORKLife <".$email_salida.">\r\n";
			$texto = '<p>Hola '.$nombre.',</p>	
					<p>Muchas gracias por registrarte en <a href="http://www.worklife.com.ar/">Worklife</a>, de ahora en más sos parte de la nueva comunidad virtual donde podrás encontrar el servicio que buscas!   </p>
					<p>Por ser parte de nuestra comunidad vas a contar con los siguientes beneficios:  </p>
					<p><strong>* Ubicación de los servicios GPS * <br>
					  * Compartir publicaciones con tus amigos de Facebook *
						<br>
					  * Interactuar con otros usuarios</strong> *</p>
					<p><strong>¡¡¡Y muchos màs!!!  </strong></p>					
					<p>Tus datos de acceso son:<br>'.$adicional_formato.'</p>
					<p>Te recordamos que leas nuestros <a href="http://www.worklife.com.ar/contenido/terminos-y-condiciones.html">términos y condiciones</a> para que no tengas ningún inconveniente en el uso de tu usuario en Worklife.  </p>
					<p>Bienvenido a tu lugar en la red, bienvenido a Worklife. </p>';
			$subject="Ya sos parte de Worklife!";
			
			$contenido = str_replace("[{CONTENIDO}]",$texto,$contenido);
		}
		
		//////////////////////////
		
		if($tipo=="NEW_PREMIUM"){
			$cabeceras .= "From:  WORKLife Premium<".$email_salida.">\r\n";
			$texto = '<p>Hola '.$nombre.',</p>	
			          <p>Gracias por completar la información de tu cuenta premium, nos pondremosen contacto en lo siguientes días para validar tu información y actiivar tu cuenta PREMIUM</p>';
			$subject="Solicitud de cuenta Premium WORKLife";
			
			$contenido = str_replace("[{CONTENIDO}]",$texto,$contenido);
		}
		
		//////////////////////////
		
		if($tipo=="NEW_PREMIUM_ADMIN"){
			$cabeceras .= "From:  WORKLife Premium<".$email_salida.">\r\n";
			$texto = '<p>Se ha solicitado una nueva cuenta PREMIUM a nombre de '.$nombre.'.</p>	
			          <p>Información profesional:<br>'.$adicional_formato.'</p>';
			$subject="Solicitud de cuenta Premium WORKLife";
			
			$contenido = str_replace("[{CONTENIDO}]",$texto,$contenido);
		}
		
		//////////////////////////
		
		if($tipo=="NOTIFI_CAMBIO_PASS"){
			$cabeceras .= "From:  Actualización de datos WORKLife<".$email_salida.">\r\n";
			$texto = '<p>Hola '.$nombre.',</p>	
			          <p>Cambiaste tu contraseña de <a href="http://www.worklife.com.ar/">Worklife</a>, en caso de no haber sido vos quien realizó el cambio te pedimos nos notifiques respondiendo este email.</p>';
			$subject="Cambio de contraseña Worklife";
			
			$contenido = str_replace("[{CONTENIDO}]",$texto,$contenido);
		}
		
		//////////////////////////
		
		if($tipo=="RECORDAR_PASS"){
			$cabeceras .= "From:  Recordar datos WORKLife<".$email_salida.">\r\n";
			$texto = '<p>Hola '.$nombre.',</p>
			          <p>Estos son tus datos de acceso a <a href="http://www.worklife.com.ar/">Worklife</a>.<br><br><b>'.$adicional_formato.'</b></p>';
			$subject="Recordar contraseña Worklife";
			
			$contenido = str_replace("[{CONTENIDO}]",$texto,$contenido);
		}
		
		//////////////////////////
		
		if($tipo=="NUEVA_PUBLICACION"){
			$cabeceras .= "From:  Publicaciones WORKLife<".$email_salida.">\r\n";
			$texto = '<p>Hola '.$nombre.',</p>	
			          <p>Publicaste un aviso en <a href="http://www.worklife.com.ar/">Worklife</a>, empeza a disfrutar de los beneficios y compartilo con tus amigos en FB por aca.</p>
					  <p><b>'.$adicional_formato.'</b></p>	
					  <p>Publica este link en tu FB para que todos te encuentren en Worklife! </p>';
			$subject="Publicación Activa Worklife";
			
			$contenido = str_replace("[{CONTENIDO}]",$texto,$contenido);
		}
		
		//////////////////////////
		
		if($tipo=="PUBLICACION_VENCIDA"){
			$cabeceras .= "From:  Publicaciones WORKLife<".$email_salida.">\r\n";
			$texto = '<p>Hola '.$nombre.',</p>	
			          <p>Tu publicación está vencida, ingresa a <a href="http://www.worklife.com.ar/">Worklife</a> y renovala para seguir consiguiendo clientes.</p>
					  <p><b>'.$adicional_formato.'</b></p>';
			$subject="Publicación Vencida Worklife";
			
			$contenido = str_replace("[{CONTENIDO}]",$texto,$contenido);
		}
		
		//////////////////////////
		
		if($tipo=="NUEVA_COTIZACION"){
			$cabeceras .= "From:  Cotizaciones WORKLife<".$email_salida.">\r\n";
			$texto = '<p>Hola '.$nombre.',</p>	
			          <p><b>Recibiste una cotización!!</b>, ingresa a <a href="http://www.worklife.com.ar/">Worklife</a> y responde!, un cliente está a la espera para resolver su necesidad.</p>
					  <p><b>'.$adicional_formato.'</b></p>	';
			$subject="Nueva cotización Worklife";
			
			$contenido = str_replace("[{CONTENIDO}]",$texto,$contenido);
		}
		
		//////////////////////////
		
		if($tipo=="NUEVO_MENSAJE"){
			$cabeceras .= "From:  Mensajes WORKLife<".$email_salida.">\r\n";
			$texto = '<p>Hola '.$nombre.',</p>	
			          <p><b>Recibiste un nuevo mensaje!!</b>, ingresa a <a href="http://www.worklife.com.ar/">Worklife</a> y responde!.</p>
					  <p><b>'.$adicional_formato.'</b></p>';
			$subject="Nuevo mensaje Worklife";
			
			$contenido = str_replace("[{CONTENIDO}]",$texto,$contenido);
		}
		
		//////////////////////////
		
		if($tipo=="CALIFICACION_PENDIENTE"){
			$cabeceras .= "From:  Calificación WORKLife<".$email_salida.">\r\n";
			$texto = '<p>Hola '.$nombre.',</p>	
			          <p>Tenés pendiente la calificación de un servicio, ingresa a este link y contanos como te fue.</p>
					  <p><b>'.$adicional_formato.'</b></p>';
			$subject="Calificá el servicio Worklife";
			
			$contenido = str_replace("[{CONTENIDO}]",$texto,$contenido);
		}
		
		//////////////////////////
		
		if($tipo=="EXTRANAMOS"){
			$cabeceras .= "From:  Acceso WORKLife<".$email_salida.">\r\n";
			$texto = '<p>Hola '.$nombre.',</p>	
			          <p>Te extraños, hace tiempo que no te vemos, ingresa a <a href="http://www.worklife.com.ar/">Worklife</a> y publicá tus servicios o mirá todos los servicios que tenemos para ofrecerte.</p>';
			$subject="Te extrañamos…";
			
			$contenido = str_replace("[{CONTENIDO}]",$texto,$contenido);
		}
		
		//////////////////////////
		
		if($subject!=""){
			mail($destino, $subject, $contenido, $cabeceras);
		}
	}
}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


function plantilla_email($version) 
{ 
	$formato_email = "";
	
	if($version=="1"){
		$formato_email = '<html>
						<head>
						<meta charset="utf-8">
						<title>emailing</title>
						</head>
						<style>
							body{ width:100%; height:100%; background:#704990; font-size:14px; font-family:Arial, Helvetica, sans-serif;}
						</style>
						<body>
						<div style="width:100%; padding:50px 0;  height:100%;  background:#704990;">
								<div style="margin:0 auto; max-width:800px; background:#FFF; padding:40px 50px; text-align:center;"> <a href="http://www.worklife.com.ar/" target="_blank"><img src="http://www.worklife.com.ar/images/logo.jpg" /></a>
									<br><br>  
									[{CONTENIDO}]
									<br><br><br><br>            
									Seguinos            
									<br><br>
									<a href="https://www.facebook.com/WorkLifeAr/" target="_blank"><img src="http://www.worklife.com.ar/images/redes1.jpg" width="35" style="margin:5px;" /></a>
									<a href="https://www.youtube.com/channel/UCy9_PmDyt1XvO3kvJ2OM2SA" target="_blank"><img src="http://www.worklife.com.ar/images/redes2.jpg" width="35" style="margin:5px;" /></a>
									<a href="https://www.instagram.com/WORKLifeAr/" target="_blank"><img src="http://www.worklife.com.ar/images/redes3.jpg" width="35" style="margin:5px;" /></a>            
									<br><br>
									Copyright © 2017 WORKLife

						  </div>
							</div>
						</body>
						</html>';
	}
	
	if($version=="2"){
		$formato_email = '<html>
						<head>
						<meta charset="utf-8">
						<title>emailing</title>
						</head>
						<style>
							body{ width:100%; height:100%; background:#704990; font-size:14px; font-family:Arial, Helvetica, sans-serif;}
						</style>
						<body>
						<div style="width:100%; padding:50px 0;  height:100%;  background:#704990;">
								<div style="margin:0 auto; max-width:700px; border:20px solid #95619C; background:#FFF; padding:40px 50px; text-align:center;"> <a href="http://www.worklife.com.ar/" target="_blank"><img src="http://www.worklife.com.ar/images/logo.jpg" /></a>
									<br><br>  
									[{CONTENIDO}]
									<br><br><br><br>            
									Seguinos            
									<br><br>
									<a href="https://www.facebook.com/WorkLifeAr/" target="_blank"><img src="http://www.worklife.com.ar/images/redes1.jpg" width="35" style="margin:5px;" /></a>
									<a href="https://www.youtube.com/channel/UCy9_PmDyt1XvO3kvJ2OM2SA" target="_blank"><img src="http://www.worklife.com.ar/images/redes2.jpg" width="35" style="margin:5px;" /></a>
									<a href="https://www.instagram.com/WORKLifeAr/" target="_blank"><img src="http://www.worklife.com.ar/images/redes3.jpg" width="35" style="margin:5px;" /></a>            
									<br><br>
									Copyright © 2017 WORKLife

						  </div>
							</div>
						</body>
						</html>';
	}
	
	if($version=="3"){
		$formato_email = '<html>
			<head>
			<meta charset="utf-8">
			<title>emailing</title>
			</head>
			<style>
				body{ width:100%; height:100%; background:#fff; font-size:14px; color:#FFF !important; font-family:Arial, Helvetica, sans-serif;}
			</style>
			<body>
			<div style="width:100%; padding:50px 0;  height:100%;  background:#fff;">
					<div style="margin:0 auto; max-width:700px; border:20px solid #95619C; background:#704990; padding:40px 50px; text-align:center;">
					
						<div style="width:100%; padding:15px 0; background:#FFF;">
						<a href="http://www.worklife.com.ar/" target="_blank"><img src="http://www.worklife.com.ar/images/logo.jpg" /></a>
						</div>
						
						<br><br>  
						[{CONTENIDO}]
						<br><br><br><br>            
					   
						<div style="width:100%; color:#000; margin:0 auto; padding:15px 0; background:#FFF;">
						
						 Seguinos            
						<br><br>
						
						<a href="https://www.facebook.com/WorkLifeAr/" target="_blank"><img src="http://www.worklife.com.ar/images/redes1.jpg" width="35" style="margin:5px;" /></a>
						<a href="https://www.youtube.com/channel/UCy9_PmDyt1XvO3kvJ2OM2SA" target="_blank"><img src="http://www.worklife.com.ar/images/redes2.jpg" width="35" style="margin:5px;" /></a>
						<a href="https://www.instagram.com/WORKLifeAr/" target="_blank"><img src="http://www.worklife.com.ar/images/redes3.jpg" width="35" style="margin:5px;" /></a> 
						
						<br><br>
						Copyright © 2017 WORKLife 
						</div>          
						

			  </div>
				</div>
			</body>
			</html>';
	}
	
	return $formato_email;
}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

?>

