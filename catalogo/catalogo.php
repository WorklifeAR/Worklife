<?php
$path = "adodb/adodb.inc.php";
include ("admin/var.php");
include ("conexion.php");

	$variables_ver = variables_metodo("palabra,pag,ord");
	$palabra= 		$variables_ver[0];
	$pag = 			$variables_ver[1];
	$ord = 			$variables_ver[2];
		
	$c_catal=12;

$variables_ver = variables_metodo("seccion,keywords,marca,categoria");
	$seccion = 			$variables_ver[0];
	$keywords = 		$variables_ver[1];
	$marca = 			$variables_ver[2];
	$categoria = 		$variables_ver[3];
	
	
//Si existe, script para la condicion en la consulta (palabra clave)
$pal="";
if($palabra!="" && $palabra!="-"){
	$pal=" AND (full_item LIKE '%".$palabra."%' OR previa_item LIKE '%".$palabra."%' OR nombre_item LIKE '%".$palabra."%') ";
}

$key="";
if($keywords!="" && $keywords!="-"){
	$key=" AND (keywords LIKE '%".$keywords."%') ";
}
$mar="";
if($marca!="" && $marca!="-"){
	$mar=" AND (marca LIKE '%".$marca."%') ";
}
$gen="";
if($seccion!="" && $seccion!="-"){	
	$gen=" AND (genero LIKE '%".$seccion."%') ";
}

//Si existe, script para la condicion en la consulta (categorias)
$cat="";
if($categoria!="" && $categoria!="0"){
	$cat=" AND categoria_item LIKE '%*".$categoria."*%' ";
	
	$contenido = id_sub_categorias($categoria, $db, "1", "");
	
	if($contenido!=""){
		$cat = " AND ".convertir_categorias_where($contenido.$categoria)." ";
	}
}

//Variables de la paginacion
$count_1=$c_catal;
$limit=1;
$count_2=0;

if($pag!=""){
	$limit=$pag;
	$count_2=($count_1*$limit)-$count_1;
}else{
	$pag=1;
}

$order = "ORDER BY peso ASC";

if($ord=="lo"){
	$order = "ORDER BY CAST(precio AS DECIMAL(10,2)) ASC";
}elseif($ord=="hi"){
	$order = "ORDER BY CAST(precio AS DECIMAL(10,2)) DESC";
}

	//Consulta de los productos
	$sql="SELECT id_item,fecha_creacion,nombre_item,previa_item,full_item,categoria_item,img_previa,img_full,precio,estado
      	FROM ic_catalogo 
	  	WHERE 1=1 ".$pal.$cat.$key.$mar.$gen." AND estado = 'Available'
	  	".$order." LIMIT ".$count_2.",".$c_catal." ";

	$result=$db->Execute($sql);

	//Consulta para contar la cantidad de productos a mostrar
 	$sql_count="SELECT COUNT(id_item) FROM ic_catalogo WHERE 1=1 ".$pal.$cat.$key.$mar.$gen." AND estado = 'Available' ";
	$result_count=$db->Execute($sql_count);

	list($count)=$result_count->fields;
	
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
?>
<div style="display:inline-block; vertical-align:top;">
Se encontraron <?=$count?> productos | 
</div>
<div style="display:inline-block; vertical-align:top;">
<?=_ORDENARRPOR?> 
<a style="font-size:17px !important; text-decoration:underline;" href="ver.php?mod=catalogo&ord=lo&keywords=<?=$keywords?>&marca=<?=$marca?>&genero=<?=$genero?>&categoria=<?=(($categoria=="")?"0":$categoria)?>&palabra=<?=(($palabra=="")?"-":$palabra)?>&pag=<?=$pag?>"><?=_ORDENARRPOR1?></a> - 
<a style="font-size:17px !important; text-decoration:underline;" href="ver.php?mod=catalogo&ord=hi&keywords=<?=$keywords?>&marca=<?=$marca?>&genero=<?=$genero?>&categoria=<?=(($categoria=="")?"0":$categoria)?>&palabra=<?=(($palabra=="")?"-":$palabra)?>&pag=<?=$pag?>"><?=_ORDENARRPOR2?></a>  
</div>

<table width="100%" border="0" cellspacing="0" cellpadding="2">
  <tr>
    <td class="resumen_catalogo">
		<div align="right" style="font-size:17px !important;">
		
		<a style="font-size:17px !important;" href="<?=$_SESSION['c_base_location']?>ver.php?mod=catalogo&keywords=<?=$keywords?>&marca=<?=$marca?>&genero=<?=$genero?>&categoria=<?=(($categoria=="")?"0":$categoria)?>&palabra=<?=(($palabra=="")?"-":$palabra)?>&pag=<?=(($pag=="1")?ceil($count/$c_catal):($pag-1))?>">&laquo; Back</a> < 
      	<select name="pag" id="pag" onchange="javascript: window.location.href='<?=$_SESSION['c_base_location']?>ver.php?mod=catalogo&keywords=<?=$keywords?>&marca=<?=$marca?>&genero=<?=$genero?>&categoria=<?=(($categoria=="")?"0":$categoria)?>&palabra=<?=(($palabra=="")?"-":$palabra)?>&pag='+this.options[selectedIndex].value;">
          <?php
	//Inicializacion de la cantidad de items
	$count = ($count=="" || $count=="0")?1:$count;
	
	for($i=1;$i<=ceil($count/$c_catal);$i++){
		$pagina="";
		
		if($pag!=""){
			if($pag==$i){
				$pagina="selected='selected'";
			}
		}
		//Paginas
		echo "<option value='".$i."' ".$pagina."> - ".$i." - </option>";
	}
?>
    	      </select>
              
          > <a style="font-size:17px !important;" href="<?=$_SESSION['c_base_location']?>ver.php?mod=catalogo&keywords=<?=$keywords?>&marca=<?=$marca?>&genero=<?=$genero?>&categoria=<?=(($categoria=="")?"0":$categoria)?>&palabra=<?=(($palabra=="")?"-":$palabra)?>&pag=<?=(($pag==ceil($count/$c_catal))?"1":($pag+1))?>">Next &raquo;</a>
        </div>
        </td>
  </tr>
</table>
<br />

<?php
	
//Result del listado de los productos visualizado en dos columnas
while (!$result->EOF){

	//Columna Uno
	list($item,$fecha_creacion,$nombre_item,$previa_item,$full_item,$categoria_item,$img_previa,$img_full,$precio,$estado_articulo)=select_format($result->fields);
		
	$info_precio = precioFinalCopia($item, $categoria, $db);
	
	
	if($img_previa==""){
		$img_previa = 'images/img_temp.png';
	}
?>
<div style="width:262px; display:inline-block; margin:10px 5px;">
    <div style="overflow:hidden; height:275px; width:262px; padding:0 0 0 0;">
        <a href="<?=organizar_nombre_link("item", $nombre_item)?>">
          <img src="<?=$img_previa?>" alt="<?=$nombre_item?>" style="width:262px; height:auto; position:relative; z-index:10;" border="0"/>
        </a>
    </div>

    
    <div style="background:#000; padding:12px 0; text-align:center; order:1px solid #000;">
        <a href="<?=organizar_nombre_link("item", $nombre_item)?>" style="color:#E6C34B;"><?=$nombre_item?><br />
        <?=$info_precio?></a>
    </div>
    
    <div style="width:100%; padding:10px 0; text-align:center; border:1px solid #000; margin:0 0 10px 0;">
        <a href="<?=organizar_nombre_link("item", $nombre_item)?>" style="color:#000;">
        <img src="images/add-cart3.png"  style="margin:0 0 -5px 0;"/>
        <?=_ADICIONAR?>
        </a>
    </div>	
                
</div>
    


<?php	
	//Siguiente Item
	$result->MoveNext();
}
?>

<br><br>
<table width="100%" border="0" cellspacing="0" cellpadding="2">
  <tr>   
    <td class="resumen_catalogo">
		<div align="right">        
		<a href="<?=$_SESSION['c_base_location']?>ver.php?mod=catalogo&keywords=<?=$keywords?>&marca=<?=$marca?>&genero=<?=$genero?>&categoria=<?=(($categoria=="")?"0":$categoria)?>&palabra=<?=(($palabra=="")?"-":$palabra)?>&pag=<?=(($pag=="1")?ceil($count/$c_catal):($pag-1))?>">&laquo; Back</a> < 
      	<select name="pag" id="pag" onchange="javascript: window.location.href='<?=$_SESSION['c_base_location']?>ver.php?mod=catalogo&keywords=<?=$keywords?>&marca=<?=$marca?>&genero=<?=$genero?>&categoria=<?=(($categoria=="")?"0":$categoria)?>&palabra=<?=(($palabra=="")?"-":$palabra)?>&pag='+this.options[selectedIndex].value;">
          <?php
	//Inicializacion de la cantidad de items
	$count = ($count=="" || $count=="0")?1:$count;
	
	for($i=1;$i<=ceil($count/$c_catal);$i++){
		$pagina="";
		
		if($pag!=""){
			if($pag==$i){
				$pagina="selected='selected'";
			}
		}
		//Paginas
		echo "<option value='".$i."' ".$pagina."> - ".$i." - </option>";
	}
?>
    	      </select>
              
          > <a href="<?=$_SESSION['c_base_location']?>ver.php?mod=catalogo&keywords=<?=$keywords?>&marca=<?=$marca?>&genero=<?=$genero?>&categoria=<?=(($categoria=="")?"0":$categoria)?>&palabra=<?=(($palabra=="")?"-":$palabra)?>&pag=<?=(($pag==ceil($count/$c_catal))?"1":($pag+1))?>">Next &raquo;</a>
        </div>
        </td>
  </tr>
</table>