<?php
session_permiso("502");

$variables_metodo = variables_metodo("funcion,id_cupon,fecha_creacion,referencia,tipo_cupon,valor,fecha_inicio,fecha_final,productos_relacionados,usuario,fecha_uso,estado,pedido,cantidad,mult_usos,categoria_aplica");

$funcion= 			$variables_metodo[0];
$id_cupon= 			$variables_metodo[1];
$fecha_creacion= 			$variables_metodo[2];
$referencia= 			$variables_metodo[3];
$tipo_cupon= 			$variables_metodo[4];
$valor= 			$variables_metodo[5];
$fecha_inicio= 			$variables_metodo[6];
$fecha_final= 			$variables_metodo[7];
$productos_relacionados= 			$variables_metodo[8];
$usuario= 			$variables_metodo[9];
$fecha_uso= 			$variables_metodo[10];
$estado= 			$variables_metodo[11];
$pedido= 			$variables_metodo[12];
$cantidad = 			$variables_metodo[13];
$mult_usos = 			$variables_metodo[14];
$categoria_aplica = 	$variables_metodo[15];

if ($funcion == "guardar"){
	if($id_cupon!=""){
		/*Funcion para modificar los datos del formulario*/	
		modificar($id_cupon,$fecha_creacion,$referencia,$tipo_cupon,$valor,$fecha_inicio,$fecha_final,$productos_relacionados,$usuario,$fecha_uso,$estado,$pedido,$mult_usos,$categoria_aplica);
	}		
}elseif(($funcion == "borrar")&&($id_cupon!="")){
	borrar($id_cupon);
}elseif($funcion == "generar"){
	generar($fecha_creacion,$referencia,$tipo_cupon,$valor,$fecha_inicio,$fecha_final,$productos_relacionados,$usuario,$fecha_uso,$estado,$pedido,$cantidad,$mult_usos,$categoria_aplica);
}

/****************************************************************************************************************************/

function borrar($id)
{
	/*$path = "adodb/adodb.inc.php";
	include "var.php";
	include "conexion.php";
	
	$sql="SELECT b_titulo FROM ic_bloque WHERE b_id='".$id."' ";
	$result=$db->Execute($sql);
	
	list($titulo)=select_format($result->fields);
	
	//funcion para registrar la modificacion hecha por el usuario
	registrar_cambios($db, date('Y-m-d'), $_SESSION['sess_nombre'], '3', $id, $titulo,'ELIMINAR');
	
	$result=$db->Execute("delete from ic_bloque where b_id = '".$id."' ");
	
	if ($result != false) $mensaje = "3";
	else $mensaje  = "0";
		
	echo"<META HTTP-EQUIV='Refresh' CONTENT='0;URL=admin.php?mod=3&mensaje=".$mensaje."'>";
	die();*/
}

/****************************************************************************************************************************/

function generar($fecha_creacion,$referencia,$tipo_cupon,$valor,$fecha_inicio,$fecha_final,$productos_relacionados,$usuario,$fecha_uso,$estado,$pedido,$cantidad,$mult_usos,$categoria_aplica){
	$path = "adodb/adodb.inc.php";
	include "var.php";
	include "conexion.php";
	
	$sql="SELECT MAX(id_cupon) FROM ic_cupones";
	$result=$db->Execute($sql);
	
	list($max)=$result->fields;
	
	if($productos_relacionados!=""){
		$productos_relacionados = "".$productos_relacionados."";
	}
		
	$cadena = array("a","1","b","2","c","3","d","4","e","5","f","6","g","7","h","8","i","9","j","0","k","1","l","2","m","3","n","4","o","p","5","q","6","r","7","s","8","t","9","u","0","v","1","w","2","x","3","y","4","z");
	
	for($i=0; $i<$cantidad; $i++){
		$nro_cupon = $referencia;
		
		if($cantidad>1){
			$nro_rand_cupon="";
			
			for($y=0; $y<6; $y++){
				$nro_rand_cupon .=  $cadena[mt_rand(0, (count($cadena)-1))];
				
				if(($y+1)<6){
					$nro_rand_cupon .=  "-";
				}
			}	
			
			$cadena_des = explode("-", $nro_rand_cupon);
			$posicion = mt_rand(0, (count($cadena_des)-1));
			
			for($t=0; $t<count($cadena_des); $t++){
				$nro_cupon .= $cadena_des[$t];
				
				if($t == $posicion){
					$nro_cupon .= $max++;
				}
			}
		}
		
		$result=insert_bd_format("fecha_creacion,referencia,tipo_cupon,valor,fecha_inicio,fecha_final,productos_relacionados,usuario,estado,mult_usos,categoria_relacionada", 
								 "ic_cupones", 
								 array(date('Y-m-d'),strtoupper($nro_cupon),$tipo_cupon,$valor,$fecha_inicio,$fecha_final,$productos_relacionados,$usuario,$estado,$mult_usos,$categoria_aplica), 
								 $db);
		
		if ($result != false) $mensaje = "1";
		else $mensaje  = "0";
		
	}

	//funcion para registrar la modificacion hecha por el usuario
	registrar_cambios($db, date('Y-m-d'), $_SESSION['sess_nombre'], '502', '0', $referencia, 'GUARDAR');
	
	echo"<META HTTP-EQUIV='Refresh' CONTENT='0;URL=admin.php?mod=501&mensaje=".$mensaje."'>";
	die();	
}
/****************************************************************************************************************************/

function modificar($id_cupon,$fecha_creacion,$referencia,$tipo_cupon,$valor,$fecha_inicio,$fecha_final,$productos_relacionados,$usuario,$fecha_uso,$estado,$pedido,$mult_usos,$categoria_aplica){
	$path = "adodb/adodb.inc.php";
	include "var.php";
	include "conexion.php";
	
	if($productos_relacionados!=""){
		$productos_relacionados = "".$productos_relacionados."";
	}
	
	$result=update_bd_format(array("referencia","tipo_cupon","valor","fecha_inicio","fecha_final","productos_relacionados","estado","mult_usos","categoria_relacionada"), 
							 "ic_cupones", 
	                         array($referencia,$tipo_cupon,$valor,$fecha_inicio,$fecha_final,$productos_relacionados,$estado,$mult_usos,$categoria_aplica), 
							 "WHERE id_cupon='".$id_cupon."'", 
							 $db);
	
	if ($result != false) $mensaje = "2";
	else $mensaje  = "0";
	
	//funcion para registrar la modificacion hecha por el usuario
	registrar_cambios($db, date('Y-m-d'), $_SESSION['sess_nombre'], '502', '0', $titulo, 'MODIFICAR');
	
	echo"<META HTTP-EQUIV='Refresh' CONTENT='0;URL=admin.php?mod=502&mensaje=".$mensaje."&id_cupon=".$id_cupon."'>";
	die();	
}

/****************************************************************************************************************************/

?>

<form action="" name="generar" method="post">
<?php

if($id_cupon!=""){

	$sql = "SELECT id_cupon,fecha_creacion,referencia,tipo_cupon,valor,fecha_inicio,fecha_final,productos_relacionados,usuario,fecha_uso,estado,pedido,mult_usos,categoria_relacionada
			FROM ic_cupones WHERE id_cupon='".$id_cupon."'";
	$result=$db->Execute($sql);
	
	list($id_cupon,$fecha_creacion,$referencia,$tipo_cupon,$valor,$fecha_inicio,$fecha_final,$productos_relacionados,$usuario,$fecha_uso,$estado,$pedido,$mult_usos,$categoria_aplica)=select_format($result->fields);
	
	echo '<input type="hidden" name="funcion" value="guardar">';
	echo '<input type="hidden" name="id_cupon" value="'.$id_cupon.'">';
}else{
	echo '<input type="hidden" name="funcion" value="generar">';
	
	$sql="SELECT MAX(id_cupon) FROM ic_cupones";
	$result=$db->Execute($sql);
	
	list($max)=$result->fields;
	
	$cadena = array("a","1","b","2","c","3","d","4","e","5","f","6","g","7","h","8","i","9","j","0","k","1","l","2","m","3","n","4","o","p","5","q","6","r","7","s","8","t","9","u","0","v","1","w","2","x","3","y","4","z");
	
	$nro_cupon = "";
	$nro_rand_cupon="";
	
	for($y=0; $y<6; $y++){
		$nro_rand_cupon .=  $cadena[mt_rand(0, (count($cadena)-1))];
		
		if(($y+1)<6){
			$nro_rand_cupon .=  "-";
		}
	}	
	
	$cadena_des = explode("-", $nro_rand_cupon);
	$posicion = mt_rand(0, (count($cadena_des)-1));
	
	for($t=0; $t<count($cadena_des); $t++){
		$nro_cupon .= $cadena_des[$t];
		
		if($t == $posicion){
			$nro_cupon .= $max++;
		}
	}
	
	$referencia = strtoupper($nro_cupon);
}

if($cantidad==""){
	$cantidad=1;
}
?>


<table border="0" align="center" cellpadding="5" cellspacing="5">
  <tr align="center">
    <td colspan="6" class="titulo"><strong>Edit Coupons</strong></td>
  </tr>
  <tr>
    <td colspan="6"><div align="center"><a href="admin.php?mod=501">Back to list</a></div></td>
  </tr>
  <tr>
    <td colspan="6">
      <div align="center">
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
        </div></td>
    </tr>
  <tr>
    <td bgcolor="#CCCCCC"><div align="right"><strong>Coupon Code</strong></div></td>
    <td><input type="text" name="referencia" id="referencia" value="<?=$referencia?>"/></td>
    <td bgcolor="#CCCCCC"><div align="right"><strong>Cantity</strong></div></td>
    <td><input name="cantidad" type="text" id="cantidad" value="<?=$cantidad?>" size="3" maxlength="3" style="text-align:right"/></td>
    <td bgcolor="#CCCCCC"><div align="right"><strong><span id="result_box" lang="en" xml:lang="en">Multiple use</span></strong></div></td>
    <td><strong>
      <select name="mult_usos" id="mult_usos">
        <?php cargar_lista_estatica("N,S","No,Si","0",$mult_usos,$db); ?>
      </select>
    </strong></td>
    </tr>
  <tr>
    <td bgcolor="#CCCCCC"><div align="right"><strong>Start Date</strong></div></td>
    <td>
    
<table border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><input name="fecha_inicio" id="fecha_inicio" type="text" size="10" maxlength="10" value="<?=$fecha_inicio?>"/></td>
    <td>&nbsp;</td>
    <td><a href="javascript:;" onclick="showCalendar('',document.getElementById('fecha_inicio'),document.getElementById('fecha_inicio'),'','holder5',-10,5,1)"> <img src="admin/calendario/icono_calendario.jpg" width="18" height="17" border="0" align="bottom" /> </a></td>
  </tr>
</table>

</td>
    <td bgcolor="#CCCCCC"><div align="right"><strong>Expiration Date</strong></div></td>
    <td>      
    
<table border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><input name="fecha_final" type="text" id="fecha_final" value="<?=$fecha_final?>" size="10" maxlength="10"/></td>
    <td>&nbsp;</td>
    <td><a href="javascript:;" onclick="showCalendar('',document.getElementById('fecha_final'),document.getElementById('fecha_final'),'','holder5',-10,5,1)"> <img src="admin/calendario/icono_calendario.jpg" width="18" height="17" border="0" align="bottom" /> </a></td>
  </tr>
</table>

    </td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td bgcolor="#CCCCCC"><div align="right"><strong>Type of Coupon</strong></div></td>
    <td>
	<select name="tipo_cupon" id="tipo_cupon">
        <?php cargar_lista_estatica("P,V","Porcentaje,Valor","1",$tipo_cupon,$db); ?>
     </select></td>
    <td bgcolor="#CCCCCC"><div align="right"><strong>Value</strong></div></td>
    <td><input name="valor" type="text" id="valor" value="<?=$valor?>" size="10" maxlength="10"/></td>
    <td bgcolor="#CCCCCC"><div align="right"><strong>Status</strong></div></td>
    <td><strong>
      <select name="estado" id="estado">
        <?php cargar_lista_estatica("A,I","Activo,Inactivo","0",$estado,$db); ?>
      </select>
    </strong></td>
  </tr>
  <tr>
    <td bgcolor="#CCCCCC"><div align="right"><strong>Products</strong></div></td>
    <td colspan="5"><select name="productos_relacionados" id="productos_relacionados">
      <?php cargar_lista("ic_catalogo","id_item,nombre_item","nombre_item","1",$productos_relacionados,"",$db); ?>
    </select></td>
    </tr>
  <tr>
    <td bgcolor="#CCCCCC"><div align="right"><strong>Categories</strong></div></td>
    <td colspan="5"><select name="categoria_aplica" id="categoria_aplica">
      <?php cargar_lista("ic_categoria","cat_id,cat_titulo","cat_titulo","1",$categoria_aplica," WHERE cat_sub_categoria=0 AND id_tipo=7 ",$db); ?>
    </select></td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><div align="right">
      <input type="submit" name="Submit" value="Save Coupons" />
    </div></td>
    </tr>
  <tr>
    <td colspan="6">&nbsp;</td>
  </tr>
</table>
</form>

<?php

/****************************************************************************************************************************/


?>
