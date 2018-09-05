<script language="javascript" src="compras/ajax.js" type="text/javascript"></script>

<?php

$nombre_carrito = "";
		
if(isset($_SESSION['reseller'])){
	$nombre_carrito = $_SESSION['reseller'];
}

//////////////////////
	
if($estado_articulo!=_ESTADO_RESERVA){
		
?>
<a href="javascript:;" onclick="adicionarProductoCarrito('<?=$item?>');" style="color:#fff; font-size: 26px;"><?=_ADICIONAR?></a>
<a href="javascript:;" onclick="adicionarProductoCarrito('<?=$item?>');"><img src="images/cart-prod.png" style="float:right; margin:0 0 0 0;"/></a>
        
<input type="hidden" name="carrito_id_<?=$item?>" id="carrito_id_<?=$item?>" value="<?=$item?>" />

<?php

}else{
?>
<table border="0" cellspacing="0" cellpadding="5">
  <tr>
    <td width="1"><img src="images/cesta.gif" alt="<?=_ESTADO_RESERVA?>" width="30" height="26" hspace="5" border="0" title="<?=_ESTADO_RESERVA?>" /></td>
    <td><a href="ver.php?mod=reservas&articulo=<?=$item?>"><?=_ESTADO_RESERVA?></a></td>
  </tr>
</table>

<?php
}
?>