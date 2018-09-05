<?php

session_permiso("5");

$variables_metodo = variables_metodo("id,fecha,titulo,full,funcion,disabled,cat_id,tipo,sub_categoria,imagen,descuento,orden,cat_content_int,envio_gratis,tipo_fil,palabra_fil,cat_estatus,estado_fill,cat_home");

$id= 				$variables_metodo[0];
$fecha= 			$variables_metodo[1];
$titulo= 			$variables_metodo[2];
$full= 				$variables_metodo[3];
$funcion= 			$variables_metodo[4];
$disabled= 			$variables_metodo[5];
$cat_id= 			$variables_metodo[6];
$tipo= 				$variables_metodo[7];
$sub_categoria= 	$variables_metodo[8];
$imagen= 			$variables_metodo[9];
$descuento= 		$variables_metodo[10];
$orden= 			$variables_metodo[11];
$cat_content_int= 	$variables_metodo[12];
$envio_gratis= 		$variables_metodo[13];
$tipo_fil= 			$variables_metodo[14];
$palabra_fil= 		$variables_metodo[15];
$cat_estatus= 		$variables_metodo[16];
$estado_fill= 		$variables_metodo[17];
$cat_home= 		$variables_metodo[18];


define('_TAMX1',206);
define('_TAMY1',300);

if ($funcion == "guardar"){
	if($id==""){
	/*Funcion para guardar los datos del formulario*/	
		guardar($fecha,$titulo,$full,$tipo,$sub_categoria,$imagen,$descuento,$orden,$cat_content_int,$envio_gratis,$cat_estatus,$cat_home);
	}elseif($id!=""){
	/*Funcion para modificar los datos del formulario*/	
		modificar($id,$fecha,$titulo,$full,$tipo,$sub_categoria,$imagen,$descuento,$orden,$cat_content_int,$envio_gratis,$cat_estatus,$cat_home);
	}	
}elseif(($funcion == "borrar")&&($id!="")){
	borrar($id);
}

/****************************************************************************************************************************/

function borrar($id)
{
	$path = "adodb/adodb.inc.php";
	include "var.php";
	include "conexion.php";
	
	$sql="SELECT cat_titulo,img_categoria FROM ic_categoria WHERE cat_id='".$id."' ";
	$result=$db->Execute($sql);
	
	list($titulo,$img_categoria)=select_format($result->fields);

	//funcion para registrar la modificacion hecha por el usuario
	registrar_cambios($db, date('Y-m-d'), $_SESSION['sess_nombre'], '5', $id, $titulo, 'ELIMINAR');
	
	if($img_categoria!=""){
		if(file_exists($img_categoria)){
			unlink($img_categoria);
		}
	}
	
	$result=$db->Execute("DELETE FROM ic_categoria WHERE cat_id = '".$id."' ");
	$result=$db->Execute("DELETE FROM ic_directorio_categoria WHERE id_categoria = '".$id."' ");
	
	if ($result != false) $mensaje = "3";
	else $mensaje  = "0";
		
	echo"<META HTTP-EQUIV='Refresh' CONTENT='0;URL=admin.php?mod=5&mensaje=".$mensaje."'>";
	die();
}

/****************************************************************************************************************************/

function guardar($fecha,$titulo,$full,$tipo,$sub_categoria,$imagen,$descuento,$orden,$cat_content_int,$envio_gratis,$cat_estatus,$cat_home)
{
	$path = "adodb/adodb.inc.php";
	include "var.php";
	include "conexion.php";
	
	$dir = "images/categoria/";
	
	$url = $_FILES['imagen']['tmp_name'];
	$imagen_name = $_FILES['imagen']['name'];
	 
	if(!empty($_FILES['imagen']['name'])){
		if(subir_imagen($imagen_name,$url,$dir)){
			//$tamanox = _TAMX1;
			//$tamanoy = _TAMY1;
		
			//tamano($dir,$imagen_name,"cat_",$tamanox,$tamanoy);
			
			$imagen = $dir . $imagen_name;
		}else{
			echo"<META HTTP-EQUIV='Refresh' CONTENT='1;URL=admin.php?mod=5&mensaje=0'>";
			die("Error Cargando la Imagen");
		}
	}
	
	if($sub_categoria==""){
		$sub_categoria=0;
	}
	
	if($orden=="" || $orden=="0"){
		$orden="999999";
	}
	
	$result=insert_bd_format("cat_titulo,cat_conten,cat_creacion,id_tipo,cat_sub_categoria,img_categoria,descuento,orden,cat_content_int,envio_gratis,cat_estatus,cat_home", 
							 "ic_categoria",
							 array($titulo,$full,$fecha,$tipo,$sub_categoria,$imagen,$descuento,$orden,$cat_content_int,$envio_gratis,$cat_estatus,$cat_home),
							 $db);

	if ($result != false) $mensaje = "1";
	else $mensaje  = "0";
	
	//funcion para registrar la modificacion hecha por el usuario
	registrar_cambios($db, date('Y-m-d'), $_SESSION['sess_nombre'], '5', '0', $titulo, 'GUARDAR');
	
	implantar_urls($db);
	
	echo"<META HTTP-EQUIV='Refresh' CONTENT='0;URL=admin.php?mod=5&mensaje=".$mensaje."'>";
	die();	
}
/****************************************************************************************************************************/
function modificar($id,$fecha,$titulo,$full,$tipo,$sub_categoria,$imagen,$descuento,$orden,$cat_content_int,$envio_gratis,$cat_estatus,$cat_home)
{
	$path = "adodb/adodb.inc.php";
	include "var.php";
	include "conexion.php";
	
	$dir = "images/categoria/";
	$img2="";
	$subcat2="";
	
	$url = $_FILES['imagen']['tmp_name'];
	$imagen_name = $_FILES['imagen']['name']; 
	
	if(!empty($_FILES['imagen']['name'])){
		
		if(subir_imagen($imagen_name,$url,$dir)){			
			
			//$tamanox = _TAMX1;
			//$tamanoy = _TAMY1;
			//tamano($dir,$imagen_name,"cat_",$tamanox,$tamanoy);

			$imagen = $dir . $imagen_name;
			$img2 = "img_categoria";
		}else{
			echo"<META HTTP-EQUIV='Refresh' CONTENT='1;URL=admin.php?mod=5&mensaje=0'>";
			die("Error Cargando la Imagen");
		}
	}
	
	if($sub_categoria!=""){
		$sub_categoria = "".$sub_categoria."";
		$subcat2="cat_sub_categoria";
	}
	
	if($orden=="" || $orden=="0"){
		$orden="999999";
	}
	
	$result=update_bd_format(array("cat_titulo","cat_creacion","cat_conten","id_tipo",$subcat2,$img2,"descuento","orden","cat_content_int","envio_gratis","cat_estatus","cat_home"), 
							 "ic_categoria", 
							 array($titulo,$fecha,$full,$tipo,$sub_categoria,$imagen,$descuento,$orden,$cat_content_int,$envio_gratis,$cat_estatus,$cat_home), 
							 "WHERE cat_id='".$id."'", 
							 $db);
	
	if ($result != false) $mensaje = "2";
	else $mensaje  = "0";
	
	//funcion para registrar la modificacion hecha por el usuario
	registrar_cambios($db, date('Y-m-d'), $_SESSION['sess_nombre'], '5', $id, $titulo, 'MODIFICAR');	
	
	implantar_urls($db);
	
	echo"<META HTTP-EQUIV='Refresh' CONTENT='0;URL=admin.php?mod=5&mensaje=".$mensaje."&cat_id=".$id."'>";
	die();
}
/****************************************************************************************************************************/


if($cat_id=="" && $funcion!="nuevo"){
	
	$where = "";
	if($palabra_fil!=""){
		$where .= " AND (cat_titulo LIKE '%".$palabra_fil."%')";
	}
	
	if($tipo_fil!=""){
		$where .= " AND id_tipo ='".$tipo_fil."' ";
	}
	
	if($estado_fill!=""){
		$where .= " AND cat_estatus ='".$estado_fill."' ";
	}
	
	$sql="SELECT cat_id,cat_titulo,cat_conten,cat_creacion,id_tipo,cat_sub_categoria,img_categoria,descuento,orden,cat_content_int,envio_gratis,cat_estatus
        FROM ic_categoria WHERE 1=1 ".$where." ORDER BY cat_home DESC, cat_titulo ASC LIMIT 0,200";
	$result=$db->Execute($sql);
?>

<table border="0"  width="1000" align="center">
<form action="" method="post">
  <tr align="center">
    <td colspan="12">
    <img src="images/admin/categorias.png" border="0"/>  Categorías Servicios
    </td>
  </tr>
  <tr>
  	<td colspan="12">&nbsp;</td>
  </tr>
	  <tr align="center">
	    <td><strong>Palabra Clave</strong></td>
	    <td><input name="palabra_fil" type="text" class="form-control" id="palabra_fil" value="<?=$palabra_fil?>" /></td>
	    <td>&nbsp;</td>	   
	    <td><strong>Tipo Categoría</strong></td>
	    <td>
        <select name="tipo_fil" id="tipo_fil" class="form-control" >
			<?php cargar_lista("ic_tipo_categoria","id_tipo,nombre_tipo","id_tipo DESC","1",$tipo_fil,"",$db); ?>
		</select>
        </td>
		<td><strong>Estado</strong></td>
	    <td>
        <select name="estado_fill" id="estado_fill" class="form-control" >
			<?php cargar_lista_estatica("A,I","Activa,Inactiva",0,$estado_fill); ?>
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
    <td colspan="10" align="right" ><a href="admin.php?mod=5&funcion=nuevo">[ Nueva Categoría ]</a></td>
  </tr>
  <tr>
    <th>Nombre</th>
    <th>Descripcion Home</th>
    <th>Tipo</th>    
    <th>Estado</th>    
    <th>Opción</th>
  </tr>
<?php
	while(!$result->EOF){
		list($id,$titulo,$full,$fecha,$tipo,$sub_categoria,$imagen,$descuento,$orden,$cat_content_int,$envio_gratis,$cat_estatus)=select_format($result->fields);
		
		$sql="SELECT nombre_tipo FROM ic_tipo_categoria WHERE id_tipo='".$tipo."' ";
		$result_tipo=$db->Execute($sql);
		
		list($tipo)=$result_tipo->fields;
		
		$color="";
		$estad="";
		if($cat_estatus=="I"){ $color="#FFBFBF"; $estad="Inactivo"; }else{ $color="#fff"; $estad="Activo"; }
?>

  <tr>
    <td valign="top" bgcolor="<?=$color?>" ><?=$titulo?></td>
    <td valign="top" bgcolor="<?=$color?>" ><?=$full?></td>
    <td valign="top" bgcolor="<?=$color?>" ><?=$tipo?></td>
    <td valign="top" bgcolor="<?=$color?>" ><?=$estad?></td>
    <td align="center" valign="top" bgcolor="<?=$color?>" ><a href="admin.php?mod=5&cat_id=<?=$id?>">[ Editar ]</a></td>
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
	incluir_editor("cat_content_int");
</script>

<form action="" name="modificar" method="post">
<table border="0"  width="600" align="center">
  <tr align="center">
    <td colspan="12">
    <img src="images/admin/categorias.png" border="0"/>  Categorías Servicios
    </td>
  </tr>
  <tr>
  	<td colspan="12">&nbsp;</td>
  </tr>
   <tr>
     <td>
       <select name="cat_id" class="form-control">
         <option>-- Seleccionar --</option>
         <optgroup label="News">
           <?php cargar_lista("ic_categoria","cat_id,cat_titulo","cat_titulo","0",""," WHERE id_tipo='1'",$db); ?>
          </optgroup>
         <optgroup label="-- --- -- --- --"></optgroup>
         <optgroup label="Images">
           <?php cargar_lista("ic_categoria","cat_id,cat_titulo","cat_titulo","0",""," WHERE id_tipo='5'",$db); ?>
          </optgroup>
         <optgroup label="-- --- -- --- --"></optgroup>
         <optgroup label="FAQ's">
           <?php cargar_lista("ic_categoria","cat_id,cat_titulo","cat_titulo","0",""," WHERE id_tipo='9'",$db); ?>
          </optgroup>
          <optgroup label="-- --- -- --- --"></optgroup>
         <optgroup label="Videos">
           <?php cargar_lista("ic_categoria","cat_id,cat_titulo","cat_titulo","0",""," WHERE id_tipo='4'",$db); ?>
          </optgroup>
          <optgroup label="-- --- -- --- --"></optgroup>
         <optgroup label="Servicios">
           <?php cargar_lista("ic_categoria","cat_id,cat_titulo","cat_titulo","0",""," WHERE id_tipo='7'",$db); ?>
          </optgroup>
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
}
else {
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

<table  border="0" align="center" cellpadding="3" cellspacing="3">
<form action="" name="guardar" method="post" enctype="multipart/form-data">
<input type="hidden" name="funcion" value="guardar">
<input type="hidden" name="descuento" value="0">
<input type="hidden" name="envio_gratis" value="N">
<input type="hidden" name="fecha" value="<? echo date('Y-m-d'); ?>">
<?php
if ($cat_id != ""){

  $sql="SELECT cat_id,cat_titulo,cat_conten,cat_creacion,id_tipo,cat_sub_categoria,img_categoria,descuento,orden,cat_content_int,envio_gratis,cat_estatus,cat_home
        FROM ic_categoria WHERE cat_id='".$cat_id."' ";
	$result=$db->Execute($sql);
	
	list($id,$titulo,$full,$fecha,$tipo,$sub_categoria,$imagen,$descuento,$orden,$cat_content_int,$envio_gratis,$cat_estatus,$cat_home)=select_format($result->fields);
	
	$full = reemplazar_escape($full);
	
	echo '<input type="hidden" name="id" value="'.$id.'">';
}
?>
<tr>
  	<td colspan="5"><div style="margin:20px 0; width:100%; border-top:1px solid #494949;"></div></td>
  </tr>
 <tr>
   <td valign="top" width="100"><div align="left"><a href="admin.php?mod=5">Volver</a></div></td>
   <td><div align="right"><a href="admin.php?mod=5&amp;funcion=nuevo">Cancelar/Nuevo</a></div></td>
 </tr>
 <tr>
   <td valign="top">&nbsp;</td>
   <td><div align="right"><font color="#FF0000" size="1">(*)Campos requeridos</font></div></td>
 </tr>
 <tr>
    <td colspan="2" valign="top"><div align="right"><strong>Fecha
      (<? if ($fecha==""){ echo date('Y-m-d'); } else { echo $fecha; }?>)
    </strong></div>      </td>
    </tr>
  <tr>
    <td valign="top"><div align="right">Titulo(*) </div></td>
    <td><input name="titulo" type="text" id="titulo" value="<?=$titulo; ?>"  class="form-control">
    <input type="hidden" name="descuento" value="0"></td>
  </tr>
  <tr>
    <td valign="top"><div align="right">Orden</div></td>
    <td><input name="orden" type="text" id="orden" value="<?=$orden; ?>"  class="form-control" /></td>
  </tr>
  <tr>
    <td valign="top"><div align="right">Estado(*) </div></td>
    <td>
	  <select name="cat_estatus" id="cat_estatus" class="form-control" >
		<?php cargar_lista_estatica("A,I","Activa,Inactiva",0,$cat_estatus); ?>
      </select>	</td>
  </tr>
  <tr>
    <td valign="top"><div align="right">Mostrar en Home </div></td>
    <td>
	  <select name="cat_home" id="cat_home" class="form-control" >
		<?php cargar_lista_estatica("0,1","No mostrar,Si, mostrar",0,$cat_home); ?>
      </select>	</td>
  </tr>
  <tr>
    <td valign="top"><div align="right">Tipo Categoría(*) </div></td>
    <td>
	  <select name="tipo" id="tipo" class="form-control" >
        <?php cargar_lista("ic_tipo_categoria","id_tipo,nombre_tipo","id_tipo DESC","0",$tipo,"",$db); ?>
      </select>	</td>
  </tr>
  <tr>
    <td valign="top" bgcolor="#666666"></td>
    <td valign="top" bgcolor="#666666"><strong style="color:#FFF">Solo para directorio de servicios</strong></td>
  </tr>
  <tr>
    <td valign="top" bgcolor="#CCCCCC"><div align="right">Imagen</div></td>
    <td bgcolor="#CCCCCC"><input type="file" name="imagen" id="imagen" class="form-control" /> 
      <em>206px X 300px Aprox</em><br /><?=$imagen?>
	  <br /><img src="<?=$imagen?>" width="50" />
	  </td>
  </tr>
  <tr>
    <td valign="top" bgcolor="#CCCCCC"><div align="right">Subcategoría</div>
      </td>
      <td bgcolor="#CCCCCC">
    	<select name="sub_categoria" id="sub_categoria" class="form-control">
        	<option></option>
            <?php select_sub_categorias("0", 0, $sub_categoria, $db); ?>
      	</select>
    </td>
  </tr>
  <tr>
    <td valign="top"><div align="right">Descripción Previa</div></td>
    <td><textarea name="full"  class="form-control" id="texto_completo"><?= $full?></textarea></td>
  </tr>
  <tr>
    <td valign="top"><div align="right">Descripción Completo</div></td>
    <td><textarea name="cat_content_int"  class="form-control" id="cat_content_int"><?= $cat_content_int?></textarea></td>
  </tr>
  <tr>
    <td valign="top">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td valign="top">&nbsp;</td>
    <td align="right">
	<input type="button" name="Enviar" value="Guardar" onClick="enviar('titulo');">
  </form>
	<p>
    <form action="" method="post" name="eliminar">
	<input type="submit" name="Delete"  id="borrar" value="Eliminar"><input type="hidden" name="id" value="<?=$id?>">
	<input type="hidden" name="funcion" value="borrar">
	</form>
	</p>
    </td>
  </tr>
</table>

<?php	
}
?>