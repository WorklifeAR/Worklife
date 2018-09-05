<?php
$path = "adodb/adodb.inc.php";
include ("admin/var.php");
include ("conexion.php");

$variables_ver = variables_metodo("articulo");
$articulo= $variables_ver[0];

$sql="SELECT id_item,nombre_item FROM ic_catalogo WHERE id_item='".$articulo."'";
$result=$db->Execute($sql);

list($id, $nombre)=$result->fields;
?>


<script language="javascript">

function enviar(campos){

	var camposObligatorios = campos.split(",");

	for(i=0;i<camposObligatorios.length;i++)
	{
		if(document.getElementById(camposObligatorios[i]).value==""){
			alert("<?=_CAMPO?> "+ document.getElementById(camposObligatorios[i]).title +" <?=_OBLIGATORIO?>");
			return;
		}
	}
	document.reservas.submit();
}
</script>

<table  border="0" align="center" cellpadding="6" cellspacing="0" >
  <tr>
    <td valign="top" class="mas_titulo"><?=_RESERVA?></td>
  </tr>
  <tr>
    <td valign="top"><span><?=_RESERVA_MSG?></span></td>
  </tr>
<?php
	if($articulo!=""){
		echo '<tr>
				<td valign="top"><div align="right"><a href="ver.php?mod=producto&item='.$articulo.'">'._RESERVA_VOLVER.'</a></div></td>
			</tr>';
	}
?>
  <tr>
    <td valign="top">
	<table  border="0" cellpadding="4" cellspacing="0">
      <form action="catalogo/enviar_reserva.php?id=<?=session_id()?>" name="reservas" method="post">
        <tr>
          <td valign="top"><strong><?=_NOMBRE_COMPLETO?> (*)</strong></td>
          <td ><input name="nombre_completo" type="text" id="nombre_completo" title="<?=_NOMBRE_COMPLETO?>" size="35" value="<?=$_SESSION['sess_nombre']?>" /></td>
        </tr>
        <tr>
          <td valign="top"><strong><?=_TELEFONO?> (*) </strong></td>
          <td><input name="telefono" type="text" id="telefono" title="<?=_TELEFONO?>" size="35" value="<?=$_SESSION['sess_usu_telefono']?>" /></td>
        </tr>
        <tr>
          <td valign="top"><strong><?=_EMAIL?> (*)</strong></td>
          <td><input name="email" type="text" id="email" title="<?=_EMAIL?>" size="35" value="<?=$_SESSION['sess_usu_email']?>" /></td>
        </tr>
        <tr>
          <td valign="top"><strong><?=_DIRECCION?> (*)</strong></td>
          <td><input name="direccion" type="text" id="direccion" title="<?=_DIRECCION?>" size="35" value="<?=$_SESSION['sess_usu_direccion']?>" />
            <font size="1"><br />
            <i>
            <?=_RESERVA_MSG_DIRECCION?>
            </i></font></td>
        </tr>
        <tr>
          <td valign="top"><b><?=_RESERVA_ARTICULO?> (*)</b></td>
          <td><input name="articulo" type="text" id="articulo" size="40"  readonly="readonly" value="<?=$nombre?>"/></td>
        </tr>
        <tr>
          <td colspan="2" valign="top"><strong><?=_MSG_CONTACTO_8?> (*) </strong></td>
          </tr>
        <tr>
          <td colspan="2" valign="top">
		    <textarea name="comentarios" cols="30" rows="3" id="comentarios" style="width: 350px; " title="<?=_MSG_CONTACTO_8?>"></textarea></td>
        </tr>
        <tr>
          <td align="right"><div align="left"><font size="1">
            <i>
            <?=_MSG_CAMPOS_OBLIGATORIOS?>
            </i></font></div></td>
          <td align="right"><input type="button" onclick="enviar('nombre_completo,telefono,email,direccion,articulo');" name="Submit" value="<?=_RESERVA_SOLICITAR?>" /></td>
        </tr>
      </form>
    </table>	</td>
  </tr>
</table>
