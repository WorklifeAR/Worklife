<?php
session_permiso("111");

$variables_metodo = variables_metodo("id_item,l_lang,fecha_creacion,nombre_item,previa_item,full_item,categoria_item,img_previa,img_full,peso,precio,descuento,sostiene_descuento,convinado,producto,cantidad_articulos,estado,peso_lb,alto,ancho,largo,funcion,borrar_imagen,previa_oculta,peso_consolidado,free_shipping,nombre_ref,valor_ref,stock_ref,id_ref_max,id_ref,id_campo_ref,colores,home,valor_envio,marca,genero,keywords,palabra_fil,keyword_fil,categoria_fil,id_campo");

$id_item= 	$variables_metodo[0];
$l_lang= 	$variables_metodo[1];
$fecha_creacion= 	$variables_metodo[2];
$nombre_item= 	$variables_metodo[3];
$previa_item= 	$variables_metodo[4];
$full_item= 	$variables_metodo[5];
$categoria_item= 	$variables_metodo[6];
$img_previa= 	$variables_metodo[7];
$img_full= 	$variables_metodo[8];
$peso= 	$variables_metodo[9];
$precio= 	$variables_metodo[10];
$descuento= 	$variables_metodo[11];
$sostiene_descuento= 	$variables_metodo[12];
$convinado= 	$variables_metodo[13];
$producto= 	$variables_metodo[14];
$cantidad_articulos= 	$variables_metodo[15];
$estado= 	$variables_metodo[16];
$peso_lb= 	$variables_metodo[17];
$alto= 	$variables_metodo[18];
$ancho= 	$variables_metodo[19];
$largo= 	$variables_metodo[20];
$funcion= 	$variables_metodo[21];
$borrar_imagen= 	$variables_metodo[22];
$previa_oculta= 	$variables_metodo[23];
$peso_consolidado= 	$variables_metodo[24];
$free_shipping= 	$variables_metodo[25];
$nombre_ref= 	$variables_metodo[26];
$valor_ref= 	$variables_metodo[27];
$stock_ref= 	$variables_metodo[28];
$id_ref_max= 	$variables_metodo[29];
$id_ref= 	$variables_metodo[30];
$id_campo_ref= 	$variables_metodo[31];
$colores= 	$variables_metodo[32];
$home= 	$variables_metodo[33];
$valor_envio= 	$variables_metodo[34];
$marca= 	$variables_metodo[35];
$genero= 	$variables_metodo[36];
$keywords= 	$variables_metodo[37];
$palabra_fil= 	$variables_metodo[38];
$keyword_fil= 	$variables_metodo[39];
$categoria_fil= 	$variables_metodo[40];
$id_campo= 	$variables_metodo[41];

define('_TAMX1',1200);
define('_TAMY1',900);

define('_TAMX2',800);
define('_TAMY2',600);

if ($funcion == "guardar"){
	if($id_item==""){
	/*Funcion para guardar los datos del formulario*/
		guardar($l_lang,$fecha_creacion,$nombre_item,$previa_item,$full_item,$categoria_item,$img_previa,
				$img_full,$peso,$precio,$descuento,$sostiene_descuento,$convinado,$producto,$cantidad_articulos,
				$estado,$peso_lb,$alto,$ancho,$largo,$peso_consolidado,$free_shipping,$nombre_ref,$valor_ref,$stock_ref,
				$id_ref_max,$id_ref,$id_campo_ref,$home,$valor_envio,$marca,$genero,$keywords);
	}elseif($id_item!=""){
	/*Funcion para modificar los datos del formulario*/
		modificar($id_item,$l_lang,$fecha_creacion,$nombre_item,$previa_item,$full_item,$categoria_item,
				  $img_previa,$img_full,$peso,$precio,$descuento,$sostiene_descuento,$convinado,$producto,
				  $cantidad_articulos,$estado,$peso_lb,$alto,$ancho,$largo,$borrar_imagen,$previa_oculta,$peso_consolidado,$free_shipping,
				  $nombre_ref,$valor_ref,$stock_ref,$id_ref_max,$id_ref,$id_campo_ref,$home,$valor_envio,$marca,$genero,$keywords,$id_campo);
	}
}elseif(($funcion == "borrar")&&($id_item!="")){
	borrar($id_item);
}elseif(($funcion == "borrar_ref")&&($id_item!="")){
	borrar_ref($id_item, $id_ref);
}


/****************************************************************************************************************************/

function borrar($id)
{
	$path = "adodb/adodb.inc.php";
	include "var.php";
	include "conexion.php";

	//funcion para registrar la modificacion hecha por el usuario
	registrar_cambios($db, date('Y-m-d'), $_SESSION['sess_nombre'], '111', $id, $titulo,'ELIMINAR');

	$sql="SELECT img_previa,img_full FROM ic_catalogo WHERE id_item='".$id."' ";
	$result=$db->Execute($sql);

	list($img_previa,$img_full)=$result->fields;

	if($img_previa!=""){
		if(file_exists($img_previa)){
			unlink($img_previa);
		}
	}

	if($img_full!=""){
		if(file_exists($img_full)){
			unlink($img_full);
		}
	}

	$result=$db->Execute("DELETE FROM ic_catalogo WHERE id_item = '".$id."' ");

	if ($result != false) $mensaje = "3";
	else $mensaje  = "0";

	echo"<META HTTP-EQUIV='Refresh' CONTENT='0;URL=admin.php?mod=121&mensaje=".$mensaje."'>";
	die();
}

/****************************************************************************************************************************/

function borrar_ref($id_item, $id_ref)
{
	$path = "adodb/adodb.inc.php";
	include "var.php";
	include "conexion.php";

	$result=$db->Execute("DELETE FROM ic_referencias_items WHERE id_articulo = '".$id_item."' AND id='".$id_ref."' ");

	echo"<META HTTP-EQUIV='Refresh' CONTENT='0;URL=admin.php?mod=121&id_item=".$id_item."'>";
	die();
}

/****************************************************************************************************************************/

function guardar($l_lang,$fecha_creacion,$nombre_item,$previa_item,$full_item,$categoria_item,
				 $img_previa,$img_full,$peso,$precio,$descuento,$sostiene_descuento,$convinado,$producto,
				 $cantidad_articulos,$estado,$peso_lb,$alto,$ancho,$largo,$peso_consolidado,$free_shipping,
				 $nombre_ref,$valor_ref,$stock_ref,$id_ref_max,$id_ref,$id_campo_ref,$home,$valor_envio,$marca,$genero,$keywords)
{
	$path = "adodb/adodb.inc.php";
	include "var.php";
	include "conexion.php";

	if($l_lang==""){
		$l_lang=$_SESSION['idioma'];
	}

	//----------------------------------------

	$dir = "images/catalogo/";
	$dir_final = "images/catalogo/";

	$url_previa = $_FILES['img_previa']['tmp_name'];
	$imagen_previa = $_FILES['img_previa']['name'];
	$img_previa = "";
	$img_full = "";
	$img_ori = "";

	//----------------------------------------

	if($url_previa!="" && $imagen_previa!=""){
		$alt = mt_rand();
		$tamanox = _TAMX1;
		$tamanoy = _TAMY1;

		$alt = mt_rand();
				
		if(subir_imagen($imagen_previa, $url_previa, $dir))
		{
			//Genero una copia original
			copy($dir.$imagen_previa, $dir.$alt.$imagen_previa);
			$img_ori = "".$dir.$imagen_previa."";
						
			tamano($dir, $alt.$imagen_previa, "full_", $tamanox, $tamanoy);
			
			//Genero la imagen previa
			copy($dir.$imagen_previa, $dir.$alt.$imagen_previa);
			tamano($dir, $alt.$imagen_previa, "prev_", $tamanox, $tamanoy);
			
			$img_previa = "".$dir."prev_".$alt.$imagen_previa."";
			$img_full = "".$dir."full_".$alt.$imagen_previa."";
						
		}else{
			echo"<META HTTP-EQUIV='Refresh' CONTENT='1;URL=admin.php?mod=121&mensaje=0'>";
			die("Error Loading Image Preview");
		}
	}

	//----------------------------------------

	if($peso=="" || $peso=="0"){
		$peso="999999";
	}
	
	if($cantidad_articulos==""){
		$cantidad_articulos="0";
	}
		
	$categoria_item1="";
	
	for($i=0; $i<count($categoria_item); $i++){
		if(isset($categoria_item[$i]) && $categoria_item[$i]!=""){
			$categoria_item1 .= "*".$categoria_item[$i]."*";
		}
	}
	
	$result=insert_bd_format("l_lang,fecha_creacion,nombre_item,previa_item,full_item,categoria_item,img_previa,img_full,img_ori,peso,precio,".
	                         "descuento,sostiene_descuento,convinado,producto,cantidad_articulos,estado,peso_lb,alto,ancho,largo,peso_consolidado,".
							 "free_shipping,home,valor_envio,marca,genero,keywords", 
							 "ic_catalogo", 
							 array($l_lang,$fecha_creacion,$nombre_item,$previa_item,$full_item,$categoria_item1,
								   $img_previa,$img_full,$img_ori,$peso,$precio,$descuento,$sostiene_descuento,$convinado,$producto,
				 				   $cantidad_articulos,$estado,$peso_lb,$alto,$ancho,$largo,$peso_consolidado,$free_shipping,
								   $home,$valor_envio,$marca,$genero,$keywords), 
							 $db);


	if ($result != false) $mensaje = "1";
	else $mensaje  = "0";

	//funcion para registrar la modificacion hecha por el usuario
	registrar_cambios($db, date('Y-m-d'), $_SESSION['sess_nombre'], '111', '0', $nombre_item, 'GUARDAR');
	
	
	//capturo el registro recien insertado
	$sql="SELECT MAX(id_item) FROM ic_catalogo ";
	$result=$db->Execute($sql);
	list($id)=$result->fields;	
	
	$sql="INSERT INTO ic_referencias_items (id_articulo,descripcion,valor,stock,id_campo) VALUES ('".$id."','Talla XS','0','0','1') ";
	$result=$db->Execute($sql);
	$sql="INSERT INTO ic_referencias_items (id_articulo,descripcion,valor,stock,id_campo) VALUES ('".$id."','Talla S','0','0','2') ";
	$result=$db->Execute($sql);
	$sql="INSERT INTO ic_referencias_items (id_articulo,descripcion,valor,stock,id_campo) VALUES ('".$id."','Talla M','0','0','3') ";
	$result=$db->Execute($sql);
	$sql="INSERT INTO ic_referencias_items (id_articulo,descripcion,valor,stock,id_campo) VALUES ('".$id."','Talla L','0','0','4') ";
	$result=$db->Execute($sql);
	$sql="INSERT INTO ic_referencias_items (id_articulo,descripcion,valor,stock,id_campo) VALUES ('".$id."','Talla XL','0','0','5') ";
	$result=$db->Execute($sql);
	$sql="INSERT INTO ic_referencias_items (id_articulo,descripcion,valor,stock,id_campo) VALUES ('".$id."','Talla XLL','0','0','6') ";
	$result=$db->Execute($sql);
	$sql="INSERT INTO ic_referencias_items (id_articulo,descripcion,valor,stock,id_campo) VALUES ('".$id."','Talla XLLL','0','0','7') ";
	$result=$db->Execute($sql);
	/*$sql="INSERT INTO ic_referencias_items (id_articulo,descripcion,valor,stock,id_campo) VALUES ('".$id."','Size 11.5','0','0','8') ";
	$result=$db->Execute($sql);
	$sql="INSERT INTO ic_referencias_items (id_articulo,descripcion,valor,stock,id_campo) VALUES ('".$id."','Ref #9','0','99','9') ";
	$result=$db->Execute($sql);
	$sql="INSERT INTO ic_referencias_items (id_articulo,descripcion,valor,stock,id_campo) VALUES ('".$id."','S','0','99','1') ";
	$result=$db->Execute($sql);*/
	
	implantar_urls($db);
	
	echo"<META HTTP-EQUIV='Refresh' CONTENT='0;URL=admin.php?mod=121&mensaje=".$mensaje."'>";
	die();
}

/****************************************************************************************************************************/

function modificar($id_item,$l_lang,$fecha_creacion,$nombre_item,$previa_item,$full_item,$categoria_item,
				  $img_previa,$img_full,$peso,$precio,$descuento,$sostiene_descuento,$convinado,$producto,
				  $cantidad_articulos,$estado,$peso_lb,$alto,$ancho,$largo,$borrar_imagen,$previa_oculta,$peso_consolidado,$free_shipping,
				  $nombre_ref,$valor_ref,$stock_ref,$id_ref_max,$id_ref,$id_campo_ref,$home,$valor_envio,$marca,$genero,$keywords,$id_campo)
{
	$path = "adodb/adodb.inc.php";
	include "var.php";
	include "conexion.php";

	if($l_lang==""){
		$l_lang=$_SESSION['idioma'];
	}

	//----------------------------------------

	$dir = "images/catalogo/";
	$dir_final = "images/catalogo/";

	$url_previa = $_FILES['img_previa']['tmp_name'];
	$imagen_previa = $_FILES['img_previa']['name'];
	$img_previa = "";
	$img_previa2 = "";
	
	$img_full = "";
	$img_full2 = "";
	
	$img_ori = "";
	$img_ori2 = "";
	
	//----------------------------------------
	
	if($url_previa!="" && $imagen_previa!=""){
		$alt = mt_rand();
		$tamanox = _TAMX2;
		$tamanoy = _TAMY2;

		if(subir_imagen($imagen_previa, $url_previa, $dir))
		{
			//Genero una copia original
			copy($dir.$imagen_previa, $dir.$alt.$imagen_previa);
			$img_ori = "".$dir.$imagen_previa."";
						
			tamano($dir, $alt.$imagen_previa, "full_", $tamanox, $tamanoy);
			
			//Genero la imagen previa
			copy($dir.$imagen_previa, $dir.$alt.$imagen_previa);
			tamano($dir, $alt.$imagen_previa, "prev_", $tamanox, $tamanoy);
			
			$img_previa = "".$dir."prev_".$alt.$imagen_previa."";
			$img_full = "".$dir."full_".$alt.$imagen_previa."";
			
			$img_previa2 = "img_previa";
			$img_full2 = "img_full";
			$img_ori2 = "img_ori";
			
		}else{
			echo"<META HTTP-EQUIV='Refresh' CONTENT='1;URL=admin.php?mod=imagen_previa&mensaje=0'>";
			die("Error Cargando la Imagen Completa");
		}
	}

	//----------------------------------------

	if($peso=="" || $peso=="0"){
		$peso="999999";
	}

	if($borrar_imagen=="borrar"){
		$img_previa = "";
		$img_previa2 = "img_previa";
		$img_full = "";
		$img_full2 = "img_full";
	}
	
	if($cantidad_articulos==""){
		$cantidad_articulos="0";
	}
	
	$categoria_item1="";
	
	for($i=0; $i<count($categoria_item); $i++){
		if(isset($categoria_item[$i]) && $categoria_item[$i]!=""){
			$categoria_item1 .= "*".$categoria_item[$i]."*";
		}
	}
	
	$result=update_bd_format(array("l_lang","fecha_creacion","nombre_item","previa_item","full_item","categoria_item",$img_previa2,
								   $img_full2,$img_ori2,"peso","precio","descuento","sostiene_descuento","convinado","producto","cantidad_articulos",
								   "estado","peso_lb","alto","ancho","largo","peso_consolidado","free_shipping","home","valor_envio","marca","genero","keywords"), 
							 "ic_catalogo", 
							 array($l_lang,$fecha_creacion,$nombre_item,$previa_item,$full_item,$categoria_item1,
								   $img_previa,$img_full,$img_ori,$peso,$precio,$descuento,$sostiene_descuento,$convinado,$producto,
								   $cantidad_articulos,$estado,$peso_lb,$alto,$ancho,$largo,$peso_consolidado,$free_shipping,
								   $home,$valor_envio,$marca,$genero,$keywords), 
							 "WHERE id_item='".$id_item."'", 
							 $db);

	if ($result != false) $mensaje = "2";
	else $mensaje  = "0";

	//funcion para registrar la modificacion hecha por el usuario
	registrar_cambios($db, date('Y-m-d'), $_SESSION['sess_nombre'], '111', $id_item, $nombre_item, 'MODIFICAR');

	//-----------------------------------------------------------------------------------
	
	$result=$db->Execute("DELETE FROM ic_referencias_items WHERE id_articulo = '".$id_item."' ");
	
	for ($i=0;$i<=$id_ref_max;$i++){
		
		if(isset($nombre_ref[$i])){
			if($nombre_ref[$i]!=""){
				$sql="INSERT INTO ic_referencias_items (id_articulo,descripcion,valor,stock,id_campo) 
				      VALUES ('".$id_item."','".addslashes($nombre_ref[$i])."',
							  '".$valor_ref[$i]."',
							  '".$stock_ref[$i]."',
							  '".$id_campo[$i]."') ";
				$result=$db->Execute($sql);
			}
		}
	}
	
	//-----------------------------------------------------------------------------------
	
	implantar_urls($db);
	
	echo"<META HTTP-EQUIV='Refresh' CONTENT='0;URL=admin.php?mod=121&mensaje=".$mensaje."&id_item=".$id_item."'>";
	die();
}
/****************************************************************************************************************************/
if($id_item!="" || $funcion=="nuevo"){
?>
	<script language="javascript" type="text/javascript">
		incluir_editor("descripcion_previa,full");
	</script>

	<form action="" name="modificar" method="post">
	<table border="0" cellspacing="5" cellpadding="0" align="center">
	  <tr align="center">
		<td class="titulo"><strong>Create/Edit Items</strong></td>
	  </tr>
	   <tr>
	     <td align="center"><strong>Edit Items</strong></td>
      </tr>
	   <tr>
		<td align="center">
		  <strong>
		  <select name="id_item">
          <option value=""></option>
<?php 

$sql="SELECT cat_id,cat_titulo FROM ic_categoria WHERE id_tipo='7' ORDER BY cat_titulo";
$result=$db->Execute($sql);

while(!$result->EOF){
	list($cat_id,$cat_titulo)=$result->fields;	
	
	echo "<optgroup label='".$cat_titulo."'>";
	cargar_lista("ic_catalogo","id_item,nombre_item,peso","nombre_item","0",""," WHERE categoria_item LIKE '%*".$cat_id."*%'",$db); 
	echo "</optgroup>";
	
	$result->MoveNext();
}
?>
		  </select>
	     </strong></td>
	   </tr>
	   <tr>
	     <td><div align="right"><strong>
	       <input type="submit" name="Submit" value="Edit" />
         </strong></div></td>
      </tr>
	  <tr><td>&nbsp;</td></tr>
	  <tr><td>[ <a href="admin/excel_detalle_catalogo.php">Download Products</a> / <a href="admin.php?mod=122" style="color:#ff0000">Load File</a> ] - <a href="admin/excel_detalle_catalogo_items.php">[ Download Products Details ]</a> </td></tr>
	  
	</table>
	</form> 

<?php
if($mensaje==1){ 
	$mensaje = "<center><span id='confirmacion'>Product successfully created</span></center>";
}elseif(($mensaje==2)&&($mensaje!="")){
	$mensaje  = "<center><span id='confirmacion'>Product successfully updated</span></center>"; 
}elseif(($mensaje==3)&&($mensaje!="")){
	$mensaje  = "<center><span id='confirmacion'>Product successfully deleted</span></center>"; 
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
if ($id_item != "")
{
	$sql="SELECT id_item,l_lang,fecha_creacion,nombre_item,previa_item,full_item,categoria_item,img_previa,
				 img_full,peso,precio,descuento,sostiene_descuento,convinado,producto,cantidad_articulos,
				 estado,peso_lb,alto,ancho,largo,peso_consolidado,free_shipping,home,valor_envio,marca,genero,keywords
	      FROM ic_catalogo
		  WHERE id_item='".$id_item."' ";
	$result=$db->Execute($sql);

	list($id_item,$l_lang,$fecha_creacion,$nombre_item,$previa_item,$full_item,$categoria_item,
		 $img_previa,$img_full,$peso,$precio,$descuento,$sostiene_descuento,$convinado,$producto,
		 $cantidad_articulos,$estado,$peso_lb,$alto,$ancho,$largo,$peso_consolidado,$free_shipping,
		 $home,$valor_envio,$marca,$genero,$keywords)=select_format($result->fields);
}
?>

	<table  border="0" width="100%" align="center" cellpadding="2" cellspacing="5">
	<form action="" method="post" enctype="multipart/form-data" name="guardar">
	<input type="hidden" name="funcion" value="guardar">
	<input type="hidden" name="id_item" value="<?=$id_item?>">
    <input type="hidden" name="descuento" value="0">
    <input type="hidden" name="peso_consolidado" value="0">
	<input type="hidden" name="fecha_creacion" value="<? if ($fecha_creacion==""){ echo date('Y-m-d'); } else { echo $fecha_creacion; }?>">
	 <tr>
	   <td valign="top"><a href="admin.php?mod=121">back to list</a></td>
	   <td colspan="3"><div align="right"><a href="admin.php?mod=121&funcion=nuevo">[ Cancel/New ]</a></div></td>
	 </tr>
	 <tr>
	   <td valign="top" nowrap="nowrap"><div align="right"><strong>Last Change</strong></div></td>
	   <td valign="top"><? if ($fecha_creacion==""){ echo date('Y-m-d'); } else { echo date("m-d-Y",strtotime($fecha_creacion)); }?></td>
	   <td valign="top"><img src="images/spacer.gif" width="200" height="1" /></td>
	   <td valign="top"><div align="right"><font color="#FF0000" size="1">(*)Required Fields</font></div></td>
      </tr>
	  <tr>
		<td valign="top" nowrap="nowrap" bgcolor="#CCCCCC"><div align="right"><strong>Language</strong></div></td>
		<td valign="top"><strong>
		  <select name="l_lang" id="l_lang" <?=$activo_campo_idioma?> >
		    <?php cargar_lista("ic_language","lang_prefijo,lang_descripcion","lang_id","0",$l_lang,"",$db); ?>
	    </select>
		</strong></td>
		<td width="300" rowspan="5" valign="top" bgcolor="#CCCCCC"><div align="right"><strong>Categories</strong>(*)</div></td>
		<td rowspan="5" valign="top">
        <select name="categoria_item[]" size="9" multiple="multiple" id="categoria_item" ultiple="multiple" >
        	<option></option>
            <?php select_sub_categorias_multiple("0", 0, $categoria_item, $db); ?>
		</select>
        </td>
      </tr>
	  <tr>
	    <td valign="top" nowrap="nowrap" bgcolor="#CCCCCC"><div align="right"><strong>Product Name</strong>(*)</div></td>
	    <td valign="top"><input name="nombre_item" type="text" id="nombre_item" value="<?=$nombre_item ?>" size="30" /></td>
      </tr>
	  <tr>
	    <td valign="top" nowrap="nowrap" bgcolor="#CCCCCC"><div align="right"><strong>Status</strong></div></td>
	    <td valign="top"><select name="estado" id="estado" >
	      <?php cargar_lista_estatica("Available,Sold Out,By Request,Reservations only,Inactive","Available,Sold Out,By Request,Reservations only,Inactive",0,$estado); ?>
        </select></td>
      </tr>
	  <tr>
	    <td valign="top" nowrap="nowrap" bgcolor="#CCCCCC"><div align="right"><strong>Featured Home<br /></strong></div></td>
	    <td valign="top"><select name="home" id="home" >
	      <?php cargar_lista_estatica("S,N","Yes,No",1,$home); ?>
        </select></td>
      </tr>
	  <tr>
	    <td valign="top" nowrap="nowrap" bgcolor="#CCCCCC"><div align="right"><strong>Order </strong></div></td>
	    <td valign="top"><input name="peso" type="text" id="peso" value="<?=$peso ?>" size="5" />
        <i><font size="1">(items del home) <i>&gt;=1</i></font></i></td>
      </tr>
	  <tr>
	    <td valign="top" nowrap="nowrap" bgcolor="#CCCCCC"><div align="right">
	      <div align="right"><strong>Default Price</strong>(*)</div>
	      </div></td>
	    <td valign="top"><input name="precio" type="text" id="precio" value="<?=$precio ?>" size="10" /></td>
	    <td valign="top" nowrap="nowrap" bgcolor="#CCCCCC"><div align="right"><strong>Brand</strong>(*)</div></td>
	    <td valign="top"><input name="marca" type="text" id="marca" value="<?=$marca ?>" size="30" /></td>
      </tr>
	  <tr>
	    <td valign="top" nowrap="nowrap" bgcolor="#CCCCCC"><div align="right"><strong>Shipping Cost</strong></div></td>
	    <td valign="top"><input name="valor_envio" type="text" id="valor_envio" value="<?=(($valor_envio!="")?$valor_envio:"0")?>" size="10" /></td>
	    <td valign="top" nowrap="nowrap" bgcolor="#CCCCCC"><div align="right"><strong>Free shipping</strong>(*)</div></td>
	    <td valign="top"><select name="free_shipping" id="free_shipping" >
	      <?php cargar_lista_estatica("N,Y","No,Yes",0,$free_shipping); ?>
	      </select></td>
      </tr>
	  <tr>
	    <td valign="top" nowrap="nowrap" bgcolor="#CCCCCC"><div align="right">
	      <div align="right"><strong>Discount</strong>(*)</div>
	      </div></td>
	    <td valign="top"><input name="descuento" type="text" id="descuento" value="<?=(($descuento!="")?$descuento:"0")?>" size="10" /></td>
	    <td valign="top" nowrap="nowrap" bgcolor="#CCCCCC"><div align="right"><strong>Accumulates discount?<br />
        </strong><em style="font-size:10px">(In case Category has a discount)</em></span></div></td>
	    <td valign="top"><select name="sostiene_descuento" id="sostiene_descuento" >
	      <?php cargar_lista_estatica("Y,N","Yes,No",1,$sostiene_descuento); ?>
	      </select></td>
      </tr>
	  <tr>
	    <td valign="top" bgcolor="#CCCCCC"><div align="right"><strong>Points for Order</strong>(*)</div></td>
	    <td valign="top"><input name="peso_consolidado" type="text" id="peso_consolidado" value="<?=(($peso_consolidado!="")?$peso_consolidado:"1") ?>" size="10" /></td>
	    <td valign="top" bgcolor="#CCCCCC"><div align="right"><strong>Section</strong>(*)</div></td>
	    <td valign="top">
        	<select name="genero" id="genero" >
	      	<?php cargar_lista_estatica("H,M,K","Hombre,Mujer,Kids",1,$genero); ?>
	      	</select>
        </td>
      </tr>
	  <tr>
	    <td valign="top" bgcolor="#CCCCCC"><div align="right">Dimensions and Weight(*)</div></td>
	    <td colspan="3" bgcolor="#CCCCCC"><table width="100%" border="0" cellspacing="0" cellpadding="3">
	      <tr>
	        <td align="right">Lenght</td>
	        <td><input type="text" name="largo" id="largo" value="<?=$largo ?>" size="5"/></td>
	        <td align="right">Width</td>
	        <td><input type="text" name="ancho" id="ancho" value="<?=$ancho ?>" size="5"/></td>
	        <td align="right">Height</td>
	        <td><input type="text" name="alto" id="alto" value="<?=$alto ?>" size="5"/></td>
	        <td align="right">Weight</td>
	        <td><input type="text" name="peso_lb" id="peso_lb" value="<?=$peso_lb ?>" size="5"/></td>
          </tr>
        </table></td>
      </tr>
	  <tr>
	    <td valign="top" bgcolor="#CCCCCC"><div align="right"><strong>Keywords<br />
        </strong>Separate by comes(,) </div></td>
	    <td colspan="3"><input name="keywords" type="text" id="keywords" value="<?=$keywords ?>" size="90" /></td>
      </tr>
	  <tr>
	    <td valign="top" bgcolor="#CCCCCC"><div align="right"><strong>Image</strong></div></td>
	    <td colspan="3"><table border="0" cellspacing="0" cellpadding="0" align="left">
          <tr>
            <td valign="top"><font size="1">Previous</font>
            <input type="file" name="img_previa" id="img_previa" /></td>
          </tr>
          <tr>
            <td valign="top"><div align="left"><a href="<?=$img_previa?>" target="_blank"><img src="<?=($img_previa=="")?"images/spacer.gif":$img_previa?>" width="80" border="1" style="border: 1px solid #333"/></a> Minimo 1024x768. Siempre formato horizontal </div>
              <input type="hidden" name="previa_oculta" value="<?=$img_previa?>" /></td>
          </tr>
          <tr>
            <td valign="top"><label>
              <input type="checkbox" name="borrar_imagen" value="borrar" />
            </label>
            Delete image </td>
          </tr>
        </table></td>
      </tr>
	 <tr>
	    <td valign="top" bgcolor="#CCCCCC"><div align="right"><b><span id="result_box10" lang="en" xml:lang="en"><span title="">References<br />
	    </span></span></b></div></td>
	    <td colspan="3">
<!-- ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->

<?php
	if ($id_item != ""){
?>
<table  border="0" cellpadding="3" cellspacing="1" align="left">

<?php

$sql="SELECT id,descripcion,valor,stock,id_campo FROM ic_referencias_items WHERE id_articulo='".$id_item."' ORDER BY id_campo ASC";
$result_ref=$db->Execute($sql);
		
$sql_max="SELECT MAX(id) FROM ic_referencias_items WHERE id_articulo='".$id_item."'";
$result_max=$db->Execute($sql_max);

list($id_ref_max)=$result_max->fields;
?>
    <tr>
      <td align="center"><div align="center"><strong>Name</strong></div></td>
      <td align="center">Order</td>
      <td align="center"><strong>In Stock</strong></td>
      <td align="center">&nbsp;</td>
    </tr>
<?php
$count=1;

while (!$result_ref->EOF)
{
	list($id_ref,$descripcion,$valor,$stock,$id_campo)=$result_ref->fields;
?>
    <tr>
      <td valign="top">
		  <input type="hidden" name="id_campo_ref[<?=$id_ref?>]" value="<?=$count?>" />
		  <input type="text" name="nombre_ref[<?=$id_ref?>]" size="25" value="<?=$descripcion?>" />
	  </td>
      <td>
      	  <input type="text" name="id_campo[<?=$id_ref?>]" value="<?=$id_campo?>" style="width:50px; text-align:center;"/>
	  </td>
      <td>
		<input type="text" name="stock_ref[<?=$id_ref?>]" value="<?=$stock?>" style="width:50px; text-align:center;"/>
	  </td>
      <td align="center"><a href="admin.php?mod=121&funcion=borrar_ref&id_ref=<?=$id_ref?>&id_item=<?=$id_item?>" title="Delete"><img src="images/admin/eliminar.png" border="0" /></a></td>
    </tr>
<?php
	$count++;
	$result_ref->MoveNext();
}
?>
    <tr>
      <td colspan="3" valign="top"><hr color="#666666" size="1" width="98%" align="center" /></td>
    </tr>
    <tr>
      <td bgcolor="#CCCCCC">
      <input name="nombre_ref[0]" type="text" id="nombre_ref" value="" size="25"/></td>
      <td bgcolor="#CCCCCC">
      <input name="id_campo_ref[0]" type="text" id="id_campo_ref"  value="<?=$count?>" style="width:50px; text-align:center;"/>
      <td bgcolor="#CCCCCC">
	  <input name="stock_ref[0]" type="text" id="stock_ref"  style="width:50px; text-align:center;"/>
	  </td>
      <td bgcolor="#CCCCCC"><input type="button" name="Save" value="Save New Row" onClick="enviar('nombre_item,categoria_item,precio');" ></td>
    </tr>
    <tr>
      <td colspan="3" valign="top"><div align="right">
        <input type="hidden" name="id_ref_max" value="<?=$id_ref_max?>" />
      </div></td>
    </tr>
</table>
<?php
	}else{
		echo "You must select an item to view available items.";
	}
?>
<!-- ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->

       </td>
      </tr>
	  <tr>
		<td valign="top" bgcolor="#CCCCCC"><div align="right">
		  <p><strong>Previous Description</strong>(*)</p>
		</div></td>
		<td colspan="3"><textarea name="previa_item" style="width: 458px; height: 100px;" id="descripcion_previa" ><?= $previa_item?></textarea></td>
	  </tr>
	  <tr>
		<td valign="top" bgcolor="#CCCCCC"><div align="right">
		  <p><strong>Full Description</strong>(*)</p>
		</div></td>
		<td colspan="3"><textarea name="full_item" style="width: 458px; height: 100px;" id="full"><?=$full_item?></textarea></td>
	  </tr>
	  <tr>
		<td valign="top">&nbsp;</td>
		<td colspan="3">&nbsp;</td>
	  </tr>
	  <tr>
		<td valign="top">&nbsp;</td>
		<td colspan="3">
		<input type="button" name="Enviar" value="Save Item" onClick="enviar('nombre_item,categoria_item,precio,peso_consolidado');" >
	  </form>
		<p>
		<form action="" method="post" name="eliminar">
		<input type="submit" name="Borrar" value="Delete Item">
		<input type="hidden" name="id_item" value="<?=$id_item?>">
		<input type="hidden" name="funcion" value="borrar">
		</form>
		</p>
		</td>
	  </tr>
	</table>
<?php
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//Listado con registros creados
}else{
?>
<table border="0"  width="800" align="center">
<form action="" method="post">
  <tr align="center">
    <td colspan="10">
    <img src="images/admin/catalogo.png" border="0"/>  Directorio de Servicios
    </td>
  </tr>
  <tr>
  	<td colspan="10">&nbsp;</td>
  </tr>
  <tr>
    <td>Name</td>
    <td><input name="palabra_fil" type="text" id="palabra_fil" class="form-control" value="<?=$palabra_fil?>" /></td>
    <td>&nbsp;</td>
	<td>Keywords</td>
    <td><input name="keyword_fil" type="text" id="keyword_fil" class="form-control" value="<?=$keyword_fil?>" /></td>
    <td>&nbsp;</td>
    <td>Category</td>
    <td>
    <select name="categoria_fil" id="categoria_fil" class="form-control" >
      <?php cargar_lista("ic_categoria","cat_id,cat_titulo","cat_titulo","1",$categoria_fil," WHERE id_tipo='7' ",$db); ?>
    </select>
    </td>
    <td>
      <input type="submit" name="Submit2" value="Search" />
   </td>
  </tr> 
  </form>
</table>
<br />

<table width="100%" border="0" cellspacing="3" cellpadding="3">
  <tr>
    <th colspan="7" align="right" bgcolor="#FFFFFF"><a href="admin.php?mod=121&amp;funcion=nuevo">[ New Item]</a></th>
  </tr>
  <tr>
    <th bgcolor="#DDDDDD">Photo</th>
	<th bgcolor="#DDDDDD">Date</th>
	<th bgcolor="#DDDDDD">Name</th>
    <th bgcolor="#DDDDDD">Category</th>
    <th bgcolor="#DDDDDD">Price</th>
	<th bgcolor="#DDDDDD">Keywords</th>
    <th bgcolor="#DDDDDD">&nbsp;</th>
  </tr>
<?php

$where = "";

if($palabra_fil!=""){
	$where .= " AND (nombre_item LIKE '%".$palabra_fil."%')";
}
if($keyword_fil!=""){
	$where .= " AND (nombre_item LIKE '%".$keyword_fil."%')";
}
if($categoria_fil!=""){
	$where .= " AND (keywords = '".$categoria_fil."')";
}
	
$sql="SELECT id_item,l_lang,fecha_creacion,nombre_item,previa_item,full_item,categoria_item,img_previa,
			 img_full,peso,precio,descuento,sostiene_descuento,convinado,producto,cantidad_articulos,
			 estado,peso_lb,alto,ancho,largo,peso_consolidado,free_shipping,home,valor_envio,marca,genero,keywords
	  FROM ic_catalogo
	  WHERE 1=1 ".$where." ORDER BY id_item DESC LIMIT 0,100";
$result=$db->Execute($sql);


	
while(!$result->EOF){
	list($id_item,$l_lang,$fecha_creacion,$nombre_item,$previa_item,$full_item,$categoria_item,
	 $img_previa,$img_full,$peso,$precio,$descuento,$sostiene_descuento,$convinado,$producto,
	 $cantidad_articulos,$estado,$peso_lb,$alto,$ancho,$largo,$peso_consolidado,$free_shipping,
	 $home,$valor_envio,$marca,$genero,$keywords)=select_format($result->fields);

	$sql="SELECT cat_titulo FROM ic_categoria WHERE cat_id='".$categoria_item."'";
	$result_cat=$db->Execute($sql);
	
	list($cat_titulo)=select_format($result_cat->fields);
?> 
  <tr>
    <td bgcolor="#f4f4f4"><img src="<?=$img_previa?>" width="60" border="0"/></td>
    <td align="center" bgcolor="#f4f4f4"><?=$fecha_creacion?></td>
    <td bgcolor="#f4f4f4"><?=$nombre_item?></td>
    <td align="center" bgcolor="#f4f4f4"><?=$cat_titulo?></td>
    <td align="center" bgcolor="#f4f4f4"><?=$precio?></td>
	<td align="center" bgcolor="#f4f4f4"><?=$keywords?></td>
    <td align="center" nowrap="nowrap" bgcolor="#f4f4f4"><a href="admin.php?mod=121&id_item=<?=$id_item?>" style="#ff0000">[ Edit ]</a></td>
  </tr>
<?php
	$result->MoveNext();
}
?>
</table>

<?php	
}
?>