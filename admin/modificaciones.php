<?php

session_permiso("12");

$variables_metodo = variables_metodo("fecha_inicio,fecha_final,accion,moduloAplic,palabra_clave");

$fecha_inicio= 	$variables_metodo[0];
$fecha_final= 	$variables_metodo[1];
$accion= 		$variables_metodo[2];
$moduloAplic= 		$variables_metodo[3];
$palabra_clave= $variables_metodo[4];

if($fecha_inicio=="" || $fecha_final=="" ){
	$fecha_inicio=date('Y-m')."-01";
	$fecha_final=date('Y-m').date('-t');
}

?>
<script>
	$(function () {
		$( "#fecha_inicio" ).datepicker({ 
			changeMonth: true,
			changeYear: true, 
			dateFormat: "yy-mm-dd", 
			onSelect: function(dateText, inst) { 
			}
		});
		
		$( "#fecha_final" ).datepicker({ 
			changeMonth: true,
			changeYear: true, 
			dateFormat: "yy-mm-dd", 
			onSelect: function(dateText, inst) { 
			}
		});
	});
</script>
<form action="" name="buscar" method="post">
<table border="0" cellspacing="5" cellpadding="0" align="center">
  <tr align="center">
    <td colspan="11">
    <img src="images/admin/modificaciones.png" border="0"/>  Logs y Registros
    </td>
  </tr>
  <tr>
  	<td colspan="11">&nbsp;</td>
  </tr>
   <tr>
     <td><table width="100%" border="0" cellspacing="0" cellpadding="3">
       <tr>
         <td><strong>Fechas</strong></td>
         <td><input name="fecha_inicio" id="fecha_inicio" type="text" class="form-control" value="<?=$fecha_inicio?>"/></td>
         <td><input name="fecha_final" id="fecha_final" type="text" class="form-control" value="<?=$fecha_final?>"/></td>
         <td><strong>Usuarios</strong></td>
         <td><input name="palabra_clave" type="text" class="form-control" value="<?=$palabra_clave?>"/></td>
         </tr>
       <tr>
         <td><strong>Modulos</strong></td>
         <td colspan="2">
           <select name="moduloAplic" class="form-control">
             <?php cargar_lista("ic_modulos","m_id,m_modulo","m_modulo","1",$moduloAplic,"",$db); ?>
             </select>
         </td>
         <td><strong>Acción</strong></td>
         <td><select name="accion" class="form-control">
           <?php cargar_lista_estatica("GUARDAR,MODIFICAR,ELIMINAR","GUARDAR,MODIFICAR,ELIMINAR",1,$accion); ?>
         </select></td>
         </tr>
       </table></td>
   </tr>
  <tr><td><div align="right">
    <input type="submit" name="Submit" value="Buscar" />
  </div></td></tr>
</table>
</form>

<div style="margin:20px 0; width:100%; border-top:1px solid #494949;"></div>

<div style="overflow:auto; height:320px;" >
<table width="100%" border="1" style="border-collapse:collapse" bordercolor="#CCCCCC" align="center" cellpadding="2" cellspacing="0">  
  <tr>
    <th><div align="center">Fecha</div></th>
    <th><div align="center">Usuarios</div></th>
    <th><div align="center">Modulos</div></th>
    <th><div align="center">Item Id </div></th>
    <th><div align="center">Titulo</div></th>
    <th><div align="center">Acción</div></th>
  </tr>
<?php

	if($accion!=""){
		$accion = " AND rc_actividad='".$accion."' ";
	}
	
	if($palabra_clave!=""){
		$palabra_clave = " AND rc_usuario LIKE '%".$palabra_clave."%' ";
	}
	
	if($moduloAplic!=""){
		$moduloAplic = " AND rc_modulo='".$moduloAplic."' ";
	}
	
	if($fecha_inicio!=""){
		$fecha_inicio = " AND rc_fecha>='".$fecha_inicio."' ";
	}
	
	if($fecha_final!=""){
		$fecha_final = " AND rc_fecha<='".$fecha_final."' ";
	}

	$sql="SELECT rc_fecha,rc_usuario,rc_modulo,rc_id_item,rc_nombre_item,rc_actividad 
	      FROM ic_registro_cambios 
		  WHERE 1=1 ".$accion.$moduloAplic.$palabra_clave."
		  ORDER BY rc_fecha,rc_modulo ASC";
	$result=$db->Execute($sql);
	
	while(!$result->EOF){
		list($rc_fecha,$rc_usuario,$rc_modulo,$rc_id_item,$rc_nombre_item,$rc_actividad)=select_format($result->fields);
		
		if($rc_modulo!=""){
			$sql="SELECT m_modulo FROM ic_modulos WHERE m_id=".$rc_modulo." ";
			$modulo=$db->Execute($sql);
			list($rc_modulo)=select_format($modulo->fields);
		}
?>  
  <tr>
    <td><div align="center">
      <font size="1">
      <?=$rc_fecha?>
      </font></div></td>
    <td><font size="1">
      <?=$rc_usuario?>
    </font></td>
    <td><font size="1">
      <?=$rc_modulo?>
    </font></td>
    <td><font size="1">
      <?=$rc_id_item?>
    </font></td>
    <td><font size="1">
      <?=$rc_nombre_item?>
    </font></td>
    <td><font size="1">
      <?=$rc_actividad?>
    </font></td>
  </tr>
<?php
	$result->MoveNext();
	}
?>
</table>
</div>

