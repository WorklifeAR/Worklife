<?php
	session_permiso("501");
	
	$variable_metodo = variables_metodo('fecha,cupon,estado');
	$fecha		=	$variable_metodo[0];
	$cupon		=	$variable_metodo[1];
	$estado		=	$variable_metodo[2];
	
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	$fec = "";
	if($fecha!=""){
		$fec = " AND fecha_creacion = '".$fecha."' ";
	}
	$cup = "";
	if($cupon!=""){
		$cup = " AND referencia LIKE '%".$cupon."%' ";
	}
	$est = "";
	if($estado!=""){
		$est = " AND estado = '".$estado."' ";
	}

			
	$sql = "SELECT id_cupon,fecha_creacion,referencia,tipo_cupon,valor,fecha_inicio,fecha_final,productos_relacionados,usuario,fecha_uso,estado,pedido
			FROM ic_cupones WHERE 1=1 ".$fec.$cup.$est." ORDER BY id_cupon DESC";
	$result=$db->Execute($sql);
	
?>
<table border="0" align="center" cellpadding="8" cellspacing="0">
  <form action="" name="registro" id="registro" method="post">
    <tr>
      <td colspan="9" class="titulo"><div align="center"><strong>List of Coupons</strong></div></td>
    </tr>
    <tr>
      <td><b>Code</b></td>
      <td><input type="text" name="cupon" id="cupo" value="<?=$cupon?>"/></td>
      <td><b>Status</b></td>
      <td><select name="estado" id="estado"  >
        <?php cargar_lista_estatica("A,I","Active,Inactive","1",$estado,$db); ?>
      </select></td>
      <td><strong>Creation Date</strong></td>
      <td colspan="4"><input name="fecha" type="text" size="9" id="fecha" value="<?=$fecha?>" />
        <font size="1">(yyy-mm-dd)</font></td>
    </tr>
    <tr>
      <td colspan="9" align="right"><input name="Submit" type="submit" value="Search" /></td>
    </tr>
  </form>
</table>

<?php

if($mensaje==1){ 
	$mensaje = "<center><span id='confirmacion'>Properly Stored Information</span></center>";
}elseif(($mensaje==2)&&($mensaje!="")){
	$mensaje  = "<center><span id='confirmacion'>Properly Updated Information</span></center>"; 
}elseif(($mensaje==3)&&($mensaje!="")){
	$mensaje  = "<center><span id='confirmacion'>Properly Removed Information</span></center>"; 
}elseif(($mensaje==0)&&($mensaje!="")){
	$mensaje  = "<center><span id='error'>An error occurred and has not kept the information.</span></center>"; 
}
else {
	$mensaje  = "";
}
echo $mensaje;

?>
<p align="center"><a href="admin.php?mod=502">[ Add New ]</a></p><br />
  
  <!-- ************ Listado ************ -->

<table border="1" align="center" cellpadding="3" cellspacing="0" bordercolor="#cccccc" style="border-collapse: collapse;">
    <tr>
      <td colspan="7" align="right" bgcolor="#f4f4f4" class="titulo" ><table border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td><img src="images/excel-icon.jpg" width="20" height="20" hspace="5" vspace="5" /></td>
          <td><a href="admin/excel_cupones.php" style="color:#393">Create  Excel</a></td>
          <td>&nbsp;</td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <th bgcolor="#f4f4f4" class="titulo" >Code</th>
      <th bgcolor="#f4f4f4" class="titulo" >Date</th>
      <th bgcolor="#f4f4f4" class="titulo" >Expiration Date</th>
      <th bgcolor="#f4f4f4" class="titulo" >Status</th>
      <th bgcolor="#f4f4f4" class="titulo" >Used by</th>
    </tr>
<?php
while(!$result->EOF){
	list($id_cupon,$fecha_creacion,$referencia,$tipo_cupon,$valor,$fecha_inicio,$fecha_final,$productos_relacionados,$usado_por,$fecha_uso,$estado,$pedido)=select_format($result->fields);
	
	if($estado=="A"){
		$estado="Activo";	
	}
	if($estado=="I"){
		$estado="Inactive";	
	}
?>
    <tr>
      <td valign="top" nowrap="nowrap" style="font-size:10px"><a href="admin.php?mod=502&id_cupon=<?=$id_cupon?>"><?=$referencia?></a></td>
      <td valign="top" nowrap="nowrap" style="font-size:10px"><div align="center"><?=$fecha_creacion?></div></td>
      <td valign="top" nowrap="nowrap" style="font-size:10px"><div align="center"><?=$fecha_final?></div></td>
      <td valign="top" nowrap="nowrap" style="font-size:10px"><div align="center"><?=$estado?></div></td>
      <td valign="top" nowrap="nowrap" style="font-size:10px"><?php if($usado_por!=""){ echo $usado_por." (Order".$pedido.")"; }?></td>
    </tr>
<?php
$result->MoveNext();
}
?>
</table>