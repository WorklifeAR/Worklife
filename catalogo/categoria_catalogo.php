<?php
$path = "adodb/adodb.inc.php";
include ("admin/var.php");
include ("conexion.php");


//Consulta para todas las categorias
$sql="SELECT  cat_id, cat_titulo, cat_conten,img_categoria FROM ic_categoria WHERE id_tipo='7' AND cat_sub_categoria = '0' ORDER BY orden ASC";
$result=$db->Execute($sql);

	
while(!$result->EOF){
	list($cat_id, $cat_titulo, $cat_conten, $img_categoria)=select_format($result->fields);
	
	$sql="SELECT COUNT(b.id_item) FROM ic_catalogo b WHERE b.categoria_item LIKE '%*".$cat_id."*%'";
	$result_count=$db->Execute($sql);
	list($count)=select_format($result_count->fields);
	
	if($img_categoria==""){
		$img_categoria = 'images/img_temp.png';
	}
?>    

<div style="width:262px; display:inline-block; margin:10px 5px; vertical-align:top;">
    <a href="<?=organizar_nombre_link("productos", $cat_titulo)?>">
      <img src="<?=$img_categoria?>" alt="<?=$cat_titulo?>" style="width:262px; margin:0; height:300px; position:relative; z-index:10;" border="0"/>
    </a>
    <div style="padding:7px 0; margin:0; text-align:center; background:#000; width:100%;">
    	<a href="<?=organizar_nombre_link("productos", $cat_titulo)?>" style="color:#E6C349; font-size: 17px;"><?=_TODOS_PRODUCTOS?></a>
    </div>
    <div style="margin: 0 0 0 0; width:100%; text-align: center; padding:7px 0;">
      <?=$cat_titulo?>
    </div>				
</div>

<?php
		
	$result->MoveNext();
}
?>
</table>