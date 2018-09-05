<?php
if($mod!=""){
?>
    <center>
    <h3 class="mas_titulo">Panel de Administración</h3><br />
	<b>| <a href="admin.php" >Home</a> | 
	     <a href="admin.php?mod=4" >Configuración</a> | 
		 <a href="admin.php?salir=1" >Cerrar Sesión</a> |
	</b>
    </center>
<?php
}elseif($mod==""){
?>
<div style="float:left; width:100%;">
<center>
<h3 class="mas_titulo">Panel de Administración</h3><br />
</center>
<br /><br />

<div>Acceso Rápido</div>
<div style="margin:20px 0; width:100%; border-top:1px solid #494949;"></div>

<div class="row">
<?php
	$sql="SELECT m_id,m_descripcion,m_imagen,m_categoria,m_id_categoria,m_texto FROM ic_modulos 
	      WHERE m_activo=1 AND(m_imagen IS NOT NULL AND m_imagen!='') AND m_id in (117,521,5,9) ORDER BY m_id_categoria ASC";
	$result=$db->Execute($sql);
	
	$categoria_modulo = "-";
	
	while(!$result->EOF){
		list($m_id,$m_descripcion,$m_imagen,$m_categoria,$m_id_categoria,$m_texto)=select_format($result->fields);
?>		
		<div class="col-md-3 col-xs-6">  
        	<table width="100%" border="0" cellspacing="0" cellpadding="4">
				  <tr>
					<td width="1" valign="top">
						<a href="admin.php?mod=<?=$m_id?>">
							<img src="images/admin/<?=$m_imagen?>" width="38" height="38" border="0"/>
						</a>
					</td>
					<td valign="top"><a href="admin.php?mod=<?=$m_id?>"><?=$m_descripcion?></a><div style="font-size:11px;"><?=$m_texto?></div></td>
				  </tr>
				</table>	
        </div>
<?php
		$result->MoveNext();
	}
?>
</div>

<br /><br />
<div>Logs y Registros</div>
<div style="margin:20px 0; width:100%; border-top:1px solid #494949;"></div>

<div class="row">
	<div class="col-md-4 col-xs-6"> 
    <h3 style="font-size:20px;">Últimos contactos</h3>
<?php
	$sql="SELECT id,rc_fecha,rc_usuario,rc_modulo,rc_id_item,rc_nombre_item,rc_actividad 
	      FROM ic_registro_cambios 
		  WHERE rc_actividad IN ('COMPRA','CONTACTO')
		  ORDER BY rc_fecha,rc_modulo ASC LIMIT 0,10";
	$result=$db->Execute($sql);
	
	
	while(!$result->EOF){
		list($id,$rc_fecha,$rc_usuario,$rc_modulo,$rc_id_item,$rc_nombre_item,$rc_actividad)=select_format($result->fields);
		$texto_descripcion = "";		
		
		//if($rc_actividad=="COMPRA"){
			//$texto_descripcion = "Compra Tienda";
		//}else{
			$texto_descripcion = substr(strip_tags($rc_nombre_item),0,150);
		///}
		
		echo '<div style="width:100%; margin:15px 0; padding:10px 0; border-bottom:1px solid #000; font-size:12px; color:#000;">
			  <div>'.$rc_fecha.'</div>
			  <div>'.$rc_usuario.'</div>
			  <div style="color:#888;">'.strip_tags($rc_nombre_item).'</div>
			</div>';
		
		$result->MoveNext();
	}
?>	
    </div>
    
    <div class="col-md-4 col-xs-6"> 
    <h3 style="font-size:20px;">Últimos Registros de Usuarios</h3>
<?php
	$sql="SELECT id,rc_fecha,rc_usuario,rc_modulo,rc_id_item,rc_nombre_item,rc_actividad 
	      FROM ic_registro_cambios 
		  WHERE rc_actividad IN ('REGISTRO')
		  ORDER BY rc_fecha,rc_modulo ASC LIMIT 0,10";
	$result=$db->Execute($sql);
	
	
	while(!$result->EOF){
		list($id,$rc_fecha,$rc_usuario,$rc_modulo,$rc_id_item,$rc_nombre_item,$rc_actividad)=select_format($result->fields);
		$texto_descripcion = "";		
		
		//if($rc_actividad=="COMPRA"){
			//$texto_descripcion = "Compra Tienda";
		//}else{
			$texto_descripcion = substr(strip_tags($rc_nombre_item),0,150);
		///}
		
		echo '<div style="width:100%; margin:15px 0; padding:10px 0; border-bottom:1px solid #000; font-size:12px; color:#000;">
			  <div>'.$rc_fecha.'</div>
			  <div>Nuevo Usuario</div>
			  <div style="color:#888;">'.$rc_usuario.'</div>
			</div>';
		
		$result->MoveNext();
	}
?>	
    </div>
    
    <div class="col-md-4 col-xs-6"> 
    <h3 style="font-size:20px;">Últimos Cambios</h3>
<?php
	$sql="SELECT id,rc_fecha,rc_usuario,rc_modulo,rc_id_item,rc_nombre_item,rc_actividad 
	      FROM ic_registro_cambios 
		  WHERE rc_actividad IN ('MODIFICAR','GUARDAR','ELIMINAR')
		  ORDER BY rc_fecha,rc_modulo ASC LIMIT 0,10";
	$result=$db->Execute($sql);
	
	
	while(!$result->EOF){
		list($id,$rc_fecha,$rc_usuario,$rc_modulo,$rc_id_item,$rc_nombre_item,$rc_actividad)=select_format($result->fields);
		$texto_descripcion = "";		
		
		$sql="SELECT m_descripcion FROM ic_modulos WHERE m_id='".$rc_modulo."'";
		$result_m=$db->Execute($sql);
		list($rc_modulo)=select_format($result_m->fields);
		
		echo '<div style="width:100%; margin:15px 0; padding:10px 0; border-bottom:1px solid #000; font-size:12px; color:#000;">
			  <div>'.$rc_fecha.'</div>
			  <div>'.$rc_actividad.' // '.$rc_modulo.' // '.$rc_nombre_item.'</div>
			  <div style="color:#888;">'.$rc_usuario.'</div>
			</div>';
		
		$result->MoveNext();
	}
?>
    </div>
</div>
</div>
<?php
}
?>