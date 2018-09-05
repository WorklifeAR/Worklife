<?php
session_permiso("8");

$variables_metodo = variables_metodo("id,link,nombre,desc,funcion,n_nombre,n_link,n_desc,id_max,res_act,target,n_target,n_lang,nn_lang");

$id= 			$variables_metodo[0];
$link= 			$variables_metodo[1];
$nombre= 		$variables_metodo[2];
$desc= 			$variables_metodo[3];
$funcion= 		$variables_metodo[4];
$n_nombre= 		$variables_metodo[5];
$n_link= 		$variables_metodo[6];
$n_desc= 		$variables_metodo[7];
$id_max= 		$variables_metodo[8];
$res_act= 		$variables_metodo[9];
$target= 		$variables_metodo[10];
$n_target= 		$variables_metodo[11];
$n_lang= 		$variables_metodo[12];
$nn_lang= 		$variables_metodo[13];

if ($funcion == "guardar"){
	/*Funcion para guardar los datos del formulario*/	
	guardar($id,$link,$nombre,$desc,$n_nombre,$n_lang,$n_link,$n_desc,$id_max,$res_act,$target,$n_target,$nn_lang);
}elseif(($funcion == "borrar")&&($id!="")){
	borrar($id);
}

/****************************************************************************************************************************/
function borrar($id)
{
	$path = "adodb/adodb.inc.php";
	include "var.php";
	include "conexion.php";
	
	$sql="SELECT m_nombre FROM ic_menu WHERE m_id='".$id."' ";
	$result=$db->Execute($sql);
	
	list($titulo)=select_format($result->fields);

	//funcion para registrar la modificacion hecha por el usuario
	registrar_cambios($db, date('Y-m-d'), $_SESSION['sess_nombre'], '8', $id, $titulo, 'ELIMINAR');	
	
	$result=$db->Execute("DELETE FROM ic_menu WHERE m_id='".$id."' ");
	
	if ($result != false) echo "<center><span id='confirmacion'>Menu Eliminado Correctamente</span></center>";
	else echo '<em id="error">Ha ocurrido un error y no se ha eliminado la informaci&oacute;n.</em>';
}
/****************************************************************************************************************************/
function guardar($id,$link,$nombre,$desc,$n_nombre,$n_lang,$n_link,$n_desc,$id_max,$res_act,$target,$n_target,$nn_lang)
{
	$path = "adodb/adodb.inc.php";
	include "var.php";
	include "conexion.php";

	if (($n_link!="")&&($n_nombre!="")){
	
		$result_insertar=insert_bd_format("m_nombre,m_link,m_desc,m_target,l_lang", "ic_menu", array($n_nombre,$n_link,$n_desc,$n_target,$nn_lang), $db);
	
		if ($result_insertar != false) echo "<center><span id='confirmacion'>Menu Guardado</span></center>";
		else echo '<em id="error">Ha ocurrido un error y no se ha guardado la informaci&oacute;n</em>';	
		
		//funcion para registrar la modificacion hecha por el usuario
	    registrar_cambios($db, date('Y-m-d'), $_SESSION['sess_nombre'], '8', '0', $n_nombre, 'GUARDAR');
	}
	
	if(($id != "")&&($link != "")&&($nombre != ""))
	{	
		for ($i=1;$i<=$id_max;$i++)
		{	
			if(isset ($id[$i]) ){
			
				if(!isset($res_act[$i])){
					$res_act[$i]=0;
				}

				$result_modificar=update_bd_format(array("m_nombre","m_link","m_desc","activo","m_target","l_lang"), "ic_menu", array($nombre[$i],$link[$i],$desc[$i],$res_act[$i],$target[$i],$n_lang[$i]), "WHERE m_id='".$id[$i]."'", $db);		
			 
			 	//funcion para registrar la modificacion hecha por el usuario
	            registrar_cambios($db, date('Y-m-d'), $_SESSION['sess_nombre'], '8', $id[$i], $nombre[$i], 'MODIFICAR');
	
				if ($result_modificar == false){
					echo '<em id="error">Ha ocurrido un error y no se ha actualizado la informaci&oacute;n</em>'; 
					die();
				}
			}
		}
		echo "<center><span id='confirmacion'>Menu Actualizado</span></center>";	
	}
}

/****************************************************************************************************************************/
?>

<table  border="0" align="center" cellpadding="3" cellspacing="1">
<form action="" name="guardar" method="post">
<input type="hidden" name="funcion" value="guardar">
<?php

$sql="SELECT m_id,m_nombre,m_link,m_desc,activo,m_target,l_lang FROM ic_menu ORDER BY l_lang, m_id ";
$result=$db->Execute($sql);
		
$sql_max="SELECT MAX(m_id) FROM ic_menu";
$result_max=$db->Execute($sql_max);

list($id_max)=select_format($result_max->fields);
?>
  <tr>
    <td colspan="7" valign="top"><div align="right">
      <div align="center" class="titulo">Administrar Menu</div>
    </div></td>
    </tr>
  <tr>
    <td colspan="7" align="center" valign="top"><div align="right"><a href="admin.php?mod=8">Cancelar / Nuevo </a></div></td>
    </tr>
  <tr>
    <td align="center"><div align="center"><strong>Idioma</strong></div></td>
	<td align="center"><div align="center"><strong>Nombre</strong></div></td>
    <td align="center"><strong>Link/URL</strong></td>
    <td align="center"><strong>Descripci&oacute;n</strong></td>
    <td align="center"><strong>Target</strong></td>
    <td align="center"><strong>Sesi&oacute;n</strong></td>
    <td align="center">&nbsp;</td>
  </tr>
<?php
while (!$result->EOF)
{
	list($id,$nombre,$link,$desc,$act,$target,$n_lang)=select_format($result->fields);
	
	$check="";
	if($act==1){
		$check='checked="checked"';
	}
?>
  <tr>
  	<td valign="top">
	<select name="n_lang[<?=$id?>]" <?=$activo_campo_idioma?> >
		<?php cargar_lista("ic_language","lang_prefijo,lang_descripcion","lang_id","0",$n_lang,"",$db); ?>
	</select>
	</td>
    <td valign="top">
	<input type="hidden" name="id[<?=$id?>]" value="<?=$id?>">
	<input type="text" name="nombre[<?=$id?>]" size="15" value="<?=$nombre?>" /></td>
    <td><input name="link[<?=$id?>]" type="text" value="<?=$link?>" size="15" /></td>
    <td><input name="desc[<?=$id?>]" type="text" value="<?=$desc?>" size="15" /></td>
    <td align="center">
	<select name="target[<?=$id?>]" style="width: 58px;font-size:9px"  >
	  <?php cargar_lista_estatica("_self,_parent,_top,_blank","_self,_parent,_top,_blank","0",$target); ?>
    </select>	</td>
    <td align="center"><input type="checkbox" name="res_act[<?=$id?>]" value="1" <?=$check?> /></td>
    <td align="center"><a href="admin.php?mod=8&funcion=borrar&id=<?=$id?>" title="Eliminar"><img src="images/admin/eliminar.png" border="0" /></a></td>
  </tr>
<?php
	$result->MoveNext();
}
?>
  <tr>
    <td colspan="7" valign="top"><hr color="#666666" size="1" width="98%" align="center" /></td>
    </tr>
  <tr>
    <td bgcolor="#CCCCCC">
	<select name="nn_lang" <?=$activo_campo_idioma?> >
		<?php cargar_lista("ic_language","lang_prefijo,lang_descripcion","lang_id","0","","",$db); ?>
	</select>
	</td>
	<td bgcolor="#CCCCCC"><input name="n_nombre" type="text" id="n_nombre" value="" size="15"/></td>
    <td bgcolor="#CCCCCC"><input name="n_link" type="text" id="n_link" value="" size="15" /></td>
    <td bgcolor="#CCCCCC"><input name="n_desc" type="text" id="n_desc" value="" size="15" /></td>
    <td bgcolor="#CCCCCC">
	<select name="n_target" style="width: 58px;font-size:9px"  >
	  <?php cargar_lista_estatica("_self,_parent,_top,_blank","_self,_parent,_top,_blank","0",$target); ?>
    </select>	</td>
    <td bgcolor="#CCCCCC"><font style="font-size:9px"><strong>Nueva<br />Fila</strong></font></td>
    <td>&nbsp;</td>
  </tr>
  
  
  <tr>
    <td colspan="6" valign="top">
	<div align="right">
     <input type="hidden" name="id_max" value="<?=$id_max?>" />
	 <input type="submit" name="Enviar" value="Guardar Menu" />  
    </div>	</td>
  </tr></form>
</table>
