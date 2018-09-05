<?php
$path = "adodb/adodb.inc.php";
include ("admin/var.php");
include ("conexion.php");

	$variables_ver = variables_metodo("item,color,categoria");
	$item= 		$variables_ver[0];
	$color= 	$variables_ver[1];
	$categoria= $variables_ver[2];
	
	//Consulta para la informacion del producto
	$sql="SELECT nombre_item, full_item, img_full, categoria_item, precio, estado, previa_item, convinado, cantidad_articulos, img_previa, free_shipping, img_ori
	      FROM ic_catalogo
		  WHERE id_item='".$item."'";
	$result=$db->Execute($sql);

	list($nombre_item,$full_item,$img_full,$categoria_item,$precio,$estado_articulo,$previa_item,$convinado,$cantidad_articulos,$img_previa,$free_shipping,$img_original)=select_format($result->fields);
	
	$info_precio = precioFinalCopia($item, "", $db);
	
	$categoria_item = explode("**", $categoria_item);
	$categoria_item = $categoria_item[0];
	
	$cantidad_solicitada="1";
	
	if(isset($_SESSION['carrito'])){
		$carrito_compras=$_SESSION['carrito'];
		
		if(isset($carrito_compras[$item])){
			$cantidad_solicitada=$carrito_compras[$item];
		}
	}
?>
<script>var sudoSlider5 = "";</script>

<meta property="og:title" content="<?=$nombre_item?>" />
<meta property="og:description" content="<?=str_replace('"','',preg_replace("/\r\n+|\r+|\n+|\t+/i", "", strip_tags($full_item)))?>" />
<meta property="og:image" content="<?=$img_previa?>" />

<div style="float:left; width:100%; margin:0 0 20px 0;">
<a href="javascript:history.go(-1);">BACK</a> | <?php categorias_item("", str_replace("*","",$categoria_item), $db); ?>
</div>

<div class="pimg">
    <div id="slider5" >
        <ul>
            <li><div style="padding:0; text-align:center;"><a href="<?=$img_original?>" rel="shadowbox[producto<?=$item?>];">
            <img src="<?=$img_original?>" alt="<?=$nombre_item?>" style="width:100%; height:auto;"/>
            </a></div></li>
<?php
$sql="SELECT id_imagen,nombre_imagen,img_full,img_original FROM ic_galeria_productos WHERE id_categoria  LIKE '*".$item."*'";
$result=$db->Execute($sql);

while(!$result->EOF){
	list($id_imagen,$nombre_imagen,$img_full_galeria,$img_original)=select_format($result->fields);
	
	echo '<li><div style="padding:0; text-align:center;"><a href="'.$img_original.'" rel="shadowbox[producto'.$item.'];"><img src="'.$img_original.'" alt="'.$nombre_imagen.'" style="width:100%; height:auto;"/></a></div></li>';
	
	$result->MoveNext();
}
?>        
        </ul>
    </div>	
    
    
	<img src="<?=$img_previa?>" alt="<?=$nombre_item?>" style="width:80px !important; display:inline-block; margin:0 5px 10px 0; height:auto; cursor:pointer; vertical-align:top;" onclick="sudoSlider3.goToSlide(1, 0);"/>
<?php
$sql="SELECT nombre_imagen,img_full,img_original FROM ic_galeria_productos WHERE id_categoria  LIKE '*".$item."*'";
$result=$db->Execute($sql);
$itemcount=2;

while(!$result->EOF){
	list($nombre_imagen,$img_full_galeria,$img_original)=select_format($result->fields);
	
	echo '<img src="'.$img_full_galeria.'" alt="'.$nombre_imagen.'" style="width:80px !important; display:inline-block; margin:0 5px 10px 0; cursor:pointer; vertical-align:top; height:auto" onclick="sudoSlider3.goToSlide('.$itemcount.', 0);"/>';
	
	$itemcount++;
	$result->MoveNext();
}
?>	
</div>

<div class="pvalor" style="padding:20px; box-sizing:border-box;">
	<h1><?=$nombre_item?></h1>
    <div style="width:100%; margin: 20px 0; position:relative; font-size:30px !important; z-index:100; background:#000; text-align: center; box-sizing:border-box; padding:3px 0 3px 20px;">
        <?=$info_precio?>
    </div>
    <div style="width:100%; margin:10px 0;">
    
<?php
	$sql="SELECT id FROM ic_referencias_items WHERE stock!='0' AND id_articulo='".$item."'";
	$result_ref=$db->Execute($sql);
	
	if(!$result_ref->EOF){
?>  
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="left" width="60%"><strong><?=_SIZE_REFE?></strong></td>
    <td width="15"></td>	
    <td align="left"><strong><?=_CANTIDAD_CART?></strong></td>	
  </tr>
  <tr>
    <td align="left">
	<select name="referencias_<?=$item?>" id="referencias_<?=$item?>" style="width:100%; background:#000; border:0px; border-radius:0px; color:#FFF; font-size:18px;">
	<option value=""></option>
<?php 
	$defecto = "";
	if(isset($_SESSION['adicional']) && isset($_SESSION['adicional'][$item])){	  
		$defecto = $_SESSION['adicional'][$item];
	}
	 
	$sql="SELECT id,descripcion,valor,stock FROM ic_referencias_items WHERE id_articulo='".$item."' ORDER BY id_campo ASC";
	$result_ref=$db->Execute($sql); 
		
	while(!$result_ref->EOF){
		list($id,$descripcion,$valor,$stock)=$result_ref->fields;
		
		$select = '';
		
		if($valor=="0" || $valor==""){
			$valor = $precio;
		}
		
		$precio_final = "";
		$preciof = "";
		
		$descu = descuentoProducto($item, "", $db);
		if($descu!="" && $descu!="0"){
			$precio_final = $valor - ($valor * (($descu)/100));
			$preciof = $precio_final;
			$precio_final = "(".formateo_numero($valor).") ".$descu."% off $".formateo_numero($precio_final);			
		}else{
			$precio_final = formateo_numero($valor);
			$preciof = $valor;
		}
		
		$id_elemento=$id.'-'.$descripcion.'-'.$preciof;
		
		if($defecto==$id_elemento){
			$select = 'selected="selected"';
		}
		
		$agotado = '';
		if($stock=="0"){
			$agotado = ' disabled ';
			$descripcion = $descripcion." (agotado)";
		}
		
		echo '<option value="'.$id_elemento.'" '.$select.$agotado.'>'.$descripcion.'</option>';
		
		$result_ref->MoveNext();
	}
?>
    </select>
    <style>
	#referencias option{ font-size:19px; font-weight:bold; }
	</style>
    </td>
    <td></td>
    <td>
<?php
	$cant="";
	for($i=1; $i<10; $i++){
		$cant.="".$i;
		
		if(($i+1)<10){
			$cant.=",";
		}
	}
	echo '<select name="carrito_cantidad_'.$item.'" id="carrito_cantidad_'.$item.'"style="width:100%; background:#000; border:0px; border-radius:0px; color:#FFF; font-size:18px;">';
	cargar_lista_estatica($cant,$cant,0,$cantidad_solicitada);
	echo '</select>';
?>
    </td>
  </tr>
</table>
<?php
	}else{
		if($precio!="0"){
?>
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	  <tr>
		<td align="left"><strong><?=_CANTIDAD_CART?></strong></td>	
	  </tr>
	  <tr>
		<td>
<?php
	$cant="";
	for($i=1; $i<10; $i++){
		$cant.="".$i;
		
		if(($i+1)<10){
			$cant.=",";
		}
	}
	echo '<select name="carrito_cantidad_'.$item.'" id="carrito_cantidad_'.$item.'"style="width:100%; background:#000; border:0px; border-radius:0px; color:#FFF; font-size:18px;">';
	cargar_lista_estatica($cant,$cant,0,$cantidad_solicitada);
	echo '</select>';
?>
    </td>
  </tr>
</table>
<?php
		}else{
			echo "Item out of stock";
		}
	}
?>
    </div>
    <div style="padding:3px 20px; background:#000; width:100%; overflow:hidden; box-sizing:border-box;">
        <?php include("compras/adicionarProducto.php"); ?>
    </div>
    <div style="margin:25px 0;">
        <!-- AddThis Button BEGIN -->
        <div class="addthis_toolbox addthis_default_style addthis_32x32_style">
            <a class="addthis_button_preferred_1"></a>
            <a class="addthis_button_preferred_2"></a>
            <a class="addthis_button_preferred_3"></a>
            <a class="addthis_button_preferred_4"></a>
            <a class="addthis_button_preferred_5"></a>
            <a class="addthis_button_preferred_6"></a>
            <a class="addthis_button_preferred_7"></a>
            <a class="addthis_button_preferred_8"></a>
            <a class="addthis_button_compact"></a>
            <a class="addthis_counter addthis_bubble_style"></a>
        </div>
        <script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=xa-4f39c4ef24b7309b"></script>
        <!-- AddThis Button END -->
        
        <div style="display:inline-block; width:100%; margin:30px 0 0 0; box-sizing:border-box; vertical-align:top;">
			<?php include("contactenos-carrito.php"); ?>
        </div>
    </div>
</div>

<div style="width:100%; float:left; margin:5px 0; overflow:hidden;">
	<div style="border:1px solid #000;">
    	<div style="width:100%; background:#000; margin:0 0 10px 0; font-size:18px; color:#FFF; padding:6px 20px; box-sizing:border-box;"><?=_PRODDETAIL?></div>
    
        <div style="padding:3px 20px;">
            <h1 style="color:#000;"><?=$nombre_item?></h1>
            <?=$full_item?>
        </div>
    </div>
    
    
    
    <div style="width:100%; background:#000; margin:50px 0 10px 0; font-size:22px; color:#FFF; padding:6px 20px; box-sizing:border-box;"><?=_OTROSPROD?></div>
    
    <div class="slide_home">
        <div id="slider1">
            <ul>
                <?php articulos_home2("10", $db); ?>
            </ul>
        </div>
    </div>
</div>