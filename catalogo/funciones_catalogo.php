
<?php

/**********************************************************************************************************************/

function convertir_categorias_where($categorias){
	$where = " (";
	$cat = explode(",", $categorias);
	
	for($i=0;$i<count($cat);$i++){
		$where .= " categoria_item LIKE '%*".$cat[$i]."*%' ";
		
		if(($i+1)<count($cat)){
			$where .= " OR ";
		}
	}
	
	$where .= " )";
				
	return $where;
}

/**********************************************************************************************************************/

//Funcion para visualizar un listado de todas las subcategorias, subsubcategorias, etc de una categoria principal
function sub_categorias($categoria, $db, $profundidad){
	
	if($categoria==""){
		$categoria="0";
	}
	
	$sql="SELECT cat_id, cat_titulo, cat_sub_categoria
	      FROM ic_categoria 
		  WHERE id_tipo='7' AND cat_sub_categoria='".$categoria."' ORDER BY orden ASC";
	$result=$db->Execute($sql);

	while(!$result->EOF){
		list($cat_id, $cat_titulo, $cat_sub_categoria)=select_format($result->fields);
		
		$cat="";
		if($categoria!=""){
			$cat=" AND categoria_item LIKE '%*".$cat_id."*%' ";
			
			$contenido = id_sub_categorias($cat_id, $db, "1", "");
			
			if($contenido!=""){
				$cat = " AND ".convertir_categorias_where($contenido.$cat_id)." ";
			}
		}
		
		if($cat_sub_categoria=="0"){	
			echo "<div class=''>";
		}
		
		echo '<table width="100%" border="0" cellspacing="1" cellpadding="0" style="margin-top: 5px;">
		<tr>';
		
		$espacios="";
		for($i=0; $i<$profundidad; $i++){
			$espacios .= " <td width='5'>&nbsp;</td><td width='5'>&nbsp;</td>";
		}
		
		$imagen = "";
		
		if($cat_sub_categoria=="0"){		
			$catalogo_titulo = "<div><a href='".organizar_nombre_link("productos", $cat_titulo)."' style='color: #000; font-size:22px;'><b>".$cat_titulo."</b></a></div>";
		}else{
			$catalogo_titulo = "<a href='".organizar_nombre_link("productos", $cat_titulo)."' style='color: #000; font-size:22px;'>".$cat_titulo."</a>";
		}
		
		echo "".$espacios."".$imagen."<td valign='top'>".$catalogo_titulo."</td>";
		
		echo '</tr>
		</table>
		';
		
		if($cat_sub_categoria!="" && $profundidad!=-1){
			$profundidad++;
			
			if($profundidad=="1"){
				echo "<div class=''>";
			}
			
			//recursividad para visualizar las categorias de las subcategorias
			sub_categorias($cat_id, $db, $profundidad);
			
			if($profundidad=="1"){
				echo "</div>";
			}
			
			$profundidad--;
		}
		
		if($cat_sub_categoria=="0"){	
			echo "</div>";
		}
		
		$result->MoveNext();
	}	
}

/****************************************************************************************************************************/

function select_sub_categorias($categoria, $profundidad, $seleccionada, $db){
		
	if($categoria==""){
		$categoria="0";
	}
	
	$sql="SELECT cat_id, cat_titulo, cat_sub_categoria
	      FROM ic_categoria 
		  WHERE id_tipo='7' AND cat_sub_categoria='".$categoria."' ORDER BY orden ASC";
	$result=$db->Execute($sql);
			
	while(!$result->EOF){
		list($cat_id, $cat_titulo, $cat_sub_categoria)=select_format($result->fields);
		
		$espacios="";
		for($i=0; $i<$profundidad; $i++){
			$espacios .= "&nbsp;&nbsp;&nbsp;";
		}
		
		$select = "";
		if($seleccionada == $cat_id){
			$select = " selected='selected' ";
		}
		
		if($cat_sub_categoria=="0"){
			echo "<option value='".$cat_id."' ".$select." style='font-weight:bold'>".$espacios."&bull; ".$cat_titulo."</option>";
		}else{
			echo "<option value='".$cat_id."' ".$select.">".$espacios."".$cat_titulo."</option>";
		}

		if($cat_sub_categoria!=""){
			$profundidad++;
			//recursividad para visualizar las categorias de las subcategorias
			select_sub_categorias($cat_id, $profundidad, $seleccionada, $db);
			$profundidad--;
		}
		
		$result->MoveNext();
	}
}

/****************************************************************************************************************************/


function select_sub_categorias_multiple($categoria, $profundidad, $seleccionada, $db){
		
	if($categoria==""){
		$categoria="0";
	}
	
	$datos = $seleccionada;
	
	$sql="SELECT cat_id, cat_titulo, cat_sub_categoria
	      FROM ic_categoria 
		  WHERE id_tipo='7' AND cat_sub_categoria='".$categoria."' ORDER BY orden ASC";
	$result=$db->Execute($sql);
			
	while(!$result->EOF){
		list($cat_id, $cat_titulo, $cat_sub_categoria)=select_format($result->fields);
		
		$espacios="";
		for($i=0; $i<$profundidad; $i++){
			$espacios .= "&nbsp;&nbsp;&nbsp;";
		}
		
		$select=""; 
		
		if ($datos!=""){ 
			$valores = explode("**",$datos);
			
			for($i=0; $i<count($valores);$i++){
				$val = str_replace("*","",$valores[$i]);
				
				if($val==$cat_id){
					echo $select="selected='selected'"; 
					break;
				}
			}
		}
			
		if($cat_sub_categoria=="0"){
			echo "<option value='".$cat_id."' ".$select." style='font-weight:bold'>".$espacios."&bull; ".$cat_titulo."</option>";
		}else{
			echo "<option value='".$cat_id."' ".$select.">".$espacios."".$cat_titulo."</option>";
		}

		if($cat_sub_categoria!=""){
			$profundidad++;
			//recursividad para visualizar las categorias de las subcategorias
			select_sub_categorias_multiple($cat_id, $profundidad, $seleccionada, $db);
			$profundidad--;
		}
		
		$result->MoveNext();
	}
}

/****************************************************************************************************************************/

//Funcion para visualizar la cantidad de articulos contenidos en la categoria
function cantidad_articulos($categoria, $todos, $db){
	
	$todas_categorias = "";
	
	if($todos!="1"){
	
		$sql="SELECT  COUNT(*) FROM ic_catalogo WHERE categoria_item LIKE '%*".$categoria."*%'";
		$result=$db->Execute($sql);
		
	}else{
	
		//--------------------------------------------------

		$todas_categorias = $categoria;
		
		$sql="SELECT cat_id FROM ic_categoria WHERE cat_sub_categoria='".$categoria."'";
		$result=$db->Execute($sql);
		
		// -- Profundidad 2
		while(!$result->EOF){
			list($cat)=$result->fields;
			
			$todas_categorias .= ",".$cat;
			
			$sql_3="SELECT cat_id FROM ic_categoria WHERE cat_sub_categoria='".$cat."'";
			$result_3=$db->Execute($sql_3);
			
			// -- Profundidad 3
			while(!$result_3->EOF){
				list($cat)=$result_3->fields;
			
				$todas_categorias .= ",".$cat;
							
				$sql_4="SELECT cat_id FROM ic_categoria WHERE cat_sub_categoria='".$cat."'";
				$result_4=$db->Execute($sql_4);
				
				// -- Profundidad 4
				while(!$result_4->EOF){
					list($cat)=$result_4->fields;
					
					$todas_categorias .= ",".$cat;
					
					$sql_5="SELECT cat_id FROM ic_categoria WHERE cat_sub_categoria='".$cat."'";
					$result_5=$db->Execute($sql_5);
					
					// -- Profundidad 5
					while(!$result_5->EOF){
						list($cat)=$result_5->fields;
						
						$todas_categorias .= ",".$cat;
						
						$result_5->MoveNext();				
					}
						
					$result_4->MoveNext();				
				}
				
				$result_3->MoveNext();
			}
			
			$result->MoveNext();
		}
		
		//--------------------------------------------------
		
		$sql="SELECT COUNT(*) FROM ic_catalogo WHERE 1=1 AND ".convertir_categorias_where($todas_categorias)."";
		$result=$db->Execute($sql);
	}
	
	list($count)=$result->fields;
		
	echo "(".$count.")";
}

/**********************************************************************************************************************/

//Cargar Lista sin los ID de los items
function cargar_lista_catalogo($tabla,$campos,$orden,$defecto,$selected,$where,$db)
{

$sql="SELECT ".$campos." FROM ".$tabla." ";

if($where!=""){	$sql .= $where; }

if($orden!=""){ $sql .= " ORDER BY ".$orden." "; }

	$result=$db->Execute($sql);

	if ($defecto == "1") { echo "<option value='' >"._SELECCIONAR."...</option>"; }

    while(! $result->EOF){
		$datos=$result->fields;

		if ($selected==$datos[0]){
			$select="selected='selected'";
		}else{
			$select="";
		}

		echo "<option value='".$datos[0]."' ".$select." >";

		$largo = substr_count ( $campos, ",");

		for ($i=1; $i<=$largo; $i++){
			echo $datos[$i];
		}
		
		echo "</option>";

		$result->MoveNext();
	}
}

/**********************************************************************************************************************/

function categorias_item($item, $categoria, $db){
	
	$categoria_item="";
	
	if($item!="" && $categoria==""){
	
		$sql="SELECT categoria_item FROM ic_catalogo WHERE id_item='".$item."'";
		$result=$db->Execute($sql);
		
		list($categoria_item)=$result->fields;
		
	}elseif($categoria!="" && $item==""){
		$categoria_item = $categoria;
	}	
	
	$sql="SELECT cat_id, cat_titulo, cat_sub_categoria FROM ic_categoria WHERE cat_id='".$categoria_item."'";
	$result=$db->Execute($sql);
	
	$categorias_item = "";
	
	if(!$result->EOF){
		list($cat_id, $cat_titulo, $cat_sub_categoria)=select_format($result->fields);
		
		if($cat_sub_categoria!="0"){
		
			while($cat_sub_categoria!=""){
				$sql="SELECT cat_id, cat_titulo, cat_sub_categoria FROM ic_categoria WHERE cat_id='".$cat_sub_categoria."'";
				$result_cat=$db->Execute($sql);
				
				if(!$result_cat->EOF){
					list($c_id, $cat_tit, $cat_sub_categoria)=select_format($result_cat->fields);
					
					if($c_id!=""){
						$categorias_item = "<a href='".organizar_nombre_link("productos", $cat_tit)."' class='miga'>".$cat_tit." &raquo; </a>" . $categorias_item;
					}
				}else{
					$cat_sub_categoria="";
				}
				
			}
			
			$categorias_item .= "<a href='".organizar_nombre_link("productos", $cat_titulo)."' class='miga'>".$cat_titulo." &raquo; </a>";
		}else{
			$categorias_item .= "<a href='".organizar_nombre_link("productos", $cat_titulo)."' class='miga'>".$cat_titulo." &raquo; </a>";
		}
	}
	
	if($item!=""){
	
		$sql="SELECT nombre_item FROM ic_catalogo WHERE id_item='".$item."'";
		$result=$db->Execute($sql);
		
		list($nombre_item)=select_format($result->fields);
		
		$categorias_item .= "<a href='javascript:;' class='miga'>".$nombre_item."</a>";
	}
	
	echo $categorias_item;
}

/**********************************************************************************************************************/

/*function categoriaImagenSuperior($id_producto, $categoria_item, $db){

	if($id_producto!=""){
		$sql="SELECT categoria_item FROM ic_catalogo WHERE id_item='".$id_producto."'";
		$result=$db->Execute($sql);
		
		list($categoria_item)=$result->fields;
	}
	
	$sql="SELECT cat_titulo, cat_sub_categoria, img_categoria FROM ic_categoria WHERE l_lang='".$_SESSION['idioma']."' AND cat_id='".$categoria_item."'";
	$result=$db->Execute($sql);
	
	list($cat_titulo, $cat_sub_categoria, $img_categoria)=$result->fields;
	
	if($img_categoria!=""){
		echo '<table width="100%" style="height: 30px;" border="0" cellspacing="0" cellpadding="0" bgcolor="#cccccc">
				  <tr>
					<td background="'.$img_categoria.'"><div style="margin-left: 15px; font-size: 15px; color: #FFFFFF; font-weight:bold;">'.$cat_titulo.'</div></td>
				  </tr>
				</table>';
	}else{
		categoriaImagenSuperior("", $cat_sub_categoria, $db);
	}
}*/

/**********************************************************************************************************************/

function previasCatalogo($cantidad, $columnas, $orden, $db){

	$sql="SELECT id_item, nombre_item, previa_item, img_previa FROM ic_catalogo WHERE 1=1 ORDER BY id_item ".$orden." LIMIT 0,".$cantidad."";
	$result=$db->Execute($sql);
	
	echo '<br /><table width="100%" border="0" cellspacing="0" cellpadding="5">';

	$var=0;
	
	while(!$result->EOF){
		list($id_item, $nombre_item, $previa_item, $img_previa)=select_format($result->fields);
		
		$modulo= my_bcmod($var, $columnas);
		
		if($modulo==0){
			echo '<tr>';
		}
    	
		echo "<td><a href='".organizar_nombre_link("item", $nombre_item)."' class='nombreArticulo'>&bull; ".$nombre_item."</a></b></td>";
		/*$puntos="";
		if(strlen($previa_item)>80){
			$puntos = "...";
		}
		
		$puntos_titulo="";
		if(strlen($nombre_item)>40){
			$puntos_titulo = "...";
		}
		
		$previa_item = html_entity_decode($previa_item);
		$previa_item = substr($previa_item, 0, 80);
		
		$nombre_item = substr($nombre_item, 0, 40);
		
		echo '<td valign="top" bgcolor="#E9E9E9" width="50%">
			<table width="100%" border="0" cellspacing="0" cellpadding="5">
			  <tr>
				<td width="60" valign="top"><img src="'.$img_previa.'" border="0" alt="" vspace="0" width="60" height="60" /></td>
				<td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
				  <tr>
					<td><a href="ver.php?mod=producto&item='.$id_item.'" style="text-decoration:none;"><h1>'.$nombre_item.$puntos_titulo.'</h1></td>
				  </tr>
				  <tr>
					<td>'.$previa_item.$puntos.'</td>
				  </tr>
				</table></td>
			  </tr>
			</table>			
		</td>';*/
		
		$var++;
		$modulo= my_bcmod($var, $columnas);
		
		if($modulo==0){
  			echo '</tr>';
		}
		
		$result->MoveNext();	
	}
	
	echo '</table>';
}

/**********************************************************************************************************************/

function id_sub_categorias($categoria, $db, $profundidad, $maximo){

	$categorias = "";
	$where = "";

	if($categoria!=""){
		$where .= "cat_sub_categoria='".$categoria."'";
	}else{
		$where .= " (cat_sub_categoria IS NULL OR cat_sub_categoria='0') ";
	}

	$sql="SELECT cat_id, cat_titulo, cat_sub_categoria
	      FROM ic_categoria
		  WHERE 1=1 AND ".$where." ORDER BY cat_id ASC";
	$result=$db->Execute($sql);

	while(!$result->EOF){
		list($cat_id, $cat_titulo, $cat_sub_categoria)=select_format($result->fields);

		$categorias .= $cat_id;

		if($maximo == ""){
			$profundidad++;
			$categorias .= ",";
			//recursividad para visualizar las categorias de las subcategorias
			$categorias .= id_sub_categorias($cat_id, $db, $profundidad, $maximo);
			$profundidad--;
		}else{
			if($profundidad<$maximo){
				$profundidad++;
				$categorias .= ",";
				//recursividad para visualizar las categorias de las subcategorias
				$categorias .= id_sub_categorias($cat_id, $db, $profundidad, $maximo);
				$profundidad--;
			}
		}

		$result->MoveNext();
	}

	return $categorias;
}

/**********************************************************************************************************************/

function articulos_home($cantidad, $db){
	$sql="SELECT nombre_item, id_item, img_previa, precio, previa_item, categoria_item 	
	      FROM ic_catalogo
		  WHERE home='S' AND estado='Available' ORDER BY RAND() DESC LIMIT 0,".$cantidad." ";
	$result=$db->Execute($sql);
		
	while(!$result->EOF){
		list($nombre_item, $id_item, $img_previa,$precio,$previa_item, $categoria_item )=select_format($result->fields);
		
		$img_previa = (($img_previa=="")?"images/spacer.gif":$img_previa);
		$info_precio = precioFinalCopia($id_item, "", $db);
				
		echo '<li>
			<div style="width:262px; margin:0 auto;">
				<div style="overflow:hidden; height:275px; width:262px; padding:0 0 0 0;">
					<a href="'.organizar_nombre_link("item", $nombre_item).'">
					  <img src="'.$img_previa.'" alt="'.$nombre_item.'" style="width:262px; height:auto; position:relative; z-index:10;" border="0"/>
					</a>
				</div>
	
				
				<div style="background:#000; padding:12px 0; text-align:center; order:1px solid #000;">
					<a href="'.organizar_nombre_link("item", $nombre_item).'" style="color:#E6C34B;">'.$nombre_item.'<br />
					'.$info_precio.'</a>
				</div>
				
				<div style="width:100%; padding:10px 0; text-align:center; border:1px solid #000; margin:0 0 10px 0;">
					<a href="'.organizar_nombre_link("item", $nombre_item).'" style="color:#000;">
					<img src="images/add-cart3.png"  style="margin:0 0 -5px 0;"/>
					'._ADICIONAR.'
					</a>
				</div>	
							
			</div>
   		</li>
';
		
		$result->MoveNext();
	}
}

function articulos_home2($cantidad, $db){
	$sql="SELECT nombre_item, id_item, img_previa, precio, previa_item, categoria_item 	
	      FROM ic_catalogo
		  WHERE estado='Available' ORDER BY RAND() DESC LIMIT 0,".$cantidad." ";
	$result=$db->Execute($sql);
		
	while(!$result->EOF){
		list($nombre_item, $id_item, $img_previa,$precio,$previa_item, $categoria_item )=select_format($result->fields);
		
		$img_previa = (($img_previa=="")?"images/spacer.gif":$img_previa);
		$info_precio = precioFinalCopia($id_item, "", $db);
				
		echo '<li>
			<div style="width:262px; margin:0 auto;">
				<div style="overflow:hidden; height:275px; width:262px; padding:0 0 0 0;">
					<a href="'.organizar_nombre_link("item", $nombre_item).'">
					  <img src="'.$img_previa.'" alt="'.$nombre_item.'" style="width:262px; height:auto; position:relative; z-index:10;" border="0"/>
					</a>
				</div>
	
				
				<div style="background:#000; padding:12px 0; text-align:center; order:1px solid #000;">
					<a href="'.organizar_nombre_link("item", $nombre_item).'" style="color:#E6C34B;">'.$nombre_item.'<br />
					'.$info_precio.'</a>
				</div>
				
				<div style="width:100%; padding:10px 0; text-align:center; border:1px solid #000; margin:0 0 10px 0;">
					<a href="'.organizar_nombre_link("item", $nombre_item).'" style="color:#000;">
					<img src="images/add-cart3.png"  style="margin:0 0 -5px 0;"/>
					'._ADICIONAR.'
					</a>
				</div>	
							
			</div>
   		</li>
';
		
		$result->MoveNext();
	}
}

function articulos_home_genero($genero, $db){
	/*$sql="SELECT nombre_item, id_item, img_previa, precio, previa_item, categoria_item 	
	      FROM ic_catalogo
		  WHERE home='S' AND estado='Available' AND genero='".$cantidad."' ORDER BY RAND() DESC LIMIT 0,5 ";*/
	$sql="SELECT nombre_item, id_item, img_previa, precio, previa_item, categoria_item 	
	      FROM ic_catalogo
		  WHERE home='S' AND estado='Available' ORDER BY RAND() DESC LIMIT 0,5 ";
	$result=$db->Execute($sql);
		
	while(!$result->EOF){
		list($nombre_item, $id_item, $img_previa,$precio,$previa_item, $categoria_item )=select_format($result->fields);
		
		$img_previa = (($img_previa=="")?"images/spacer.gif":$img_previa);
		$info_precio = precioFinalCopia($id_item, "", $db);
				
		echo '<li>
			<div style="width:100%; margin:0 auto; position:relative; z-index:10; box-sizing:border-box; padding:0 1px;">
				<div style="overflow:hidden; width:100%; padding:0 0 0 0;">
					<a href="'.organizar_nombre_link("item", $nombre_item).'">
					  <img src="'.$img_previa.'" alt="'.$nombre_item.'" style="width:100%; height:auto; position:relative; z-index:10;" border="0"/>
					</a>
				</div>
	
				
				<div style="width:100%; background:#000; padding:12px 0; text-align:center; border:1px solid #000;">
					<a href="'.organizar_nombre_link("item", $nombre_item).'" style="color:#E6C34B;">'.$nombre_item.'<br />
					'.$info_precio.'</a>
				</div>
				
				<div style="width:100%; padding:10px 0; text-align:center; border:1px solid #000; margin:0 0 10px 0;">
					<a href="'.organizar_nombre_link("item", $nombre_item).'" style="color:#000;">
					<img src="images/add-cart3.png"  style="margin:0 0 -5px 0;"/>
					'._ADICIONAR.'
					</a>
				</div>	
							
			</div>
   		</li>
';
		
		$result->MoveNext();
	}
}

/**********************************************************************************************************************/

function capturarPadre($hijo, $db){
	
	$padre = "";
	
	$sql="SELECT cat_id, cat_sub_categoria FROM ic_categoria WHERE id_tipo=2 AND cat_id='".$hijo."'";
	$result=$db->Execute($sql);
	
	if(!$result->EOF){
		list($cat_id, $cat_sub_categoria)=$result->fields;
		
		if($cat_sub_categoria!="" && $cat_sub_categoria!="0"){
			$padre = capturarPadre($cat_sub_categoria, $db);
		}else{
			$padre = $hijo;
		}
	}else{
		$padre = $hijo;
	}
	
	return $padre;
}

/**********************************************************************************************************************/

//Adaptaicion de la funcion anterior para mas detalle
function descuentoProducto($id_articulo, $categoria, $db){
	$valor=0;
	
	$sql="SELECT categoria_item,precio,descuento,sostiene_descuento FROM ic_catalogo WHERE id_item=".$id_articulo."";
	$result=$db->Execute($sql);

	if(!$result->EOF){
		list($categoria_item, $precio, $descuento, $sostiene_descuento)=$result->fields;
		
		$descuento_tienda = obtenerDescripcion("DESCUENTO_TIENDA", "", $db);
		
		if($descuento_tienda->fields[0]=="" || $descuento_tienda->fields[0]=="0"){
			if($categoria!=""){
				$sql="SELECT descuento FROM ic_categoria WHERE cat_id='".$categoria."'";
			}else{
				$cw = str_replace("**",",",$categoria_item);
				$cw = str_replace("*","",$cw);
				
				$sql="SELECT descuento FROM ic_categoria WHERE cat_id IN (".$cw.")";
			}
			
			$result=$db->Execute($sql);
			list($descuento_categoria)=$result->fields;
					
			if($descuento_categoria != "" && $descuento_categoria != "0") {
				$descat = $descuento_categoria;
			}
		}else{
			if($descuento_tienda->fields[0] != "" && $descuento_tienda->fields[0] != "0") {
				$descat = $descuento_tienda->fields[0];
			}
		}
		
		$despro = "";
		
		if($descuento != "" && $descuento != "0") {
			$despro = $descuento;
		}
		
		if($despro != "" || $descat != ""){
			
			if($sostiene_descuento!="Y" && $descat != ""){
				$despro = "";
			}
			
			$valor = $despro+$descat;			
		}
	}
	
	return $valor;
}

/**********************************************************************************************************************/

//Adaptaicion de la funcion anterior para mas detalle
function precioFinalCopia($id_articulo, $categoria, $db){
	$valor="";
	$valor2="";
	
	$despro="";
	$descat="";
	
	$sql="SELECT categoria_item,precio,descuento,sostiene_descuento FROM ic_catalogo WHERE id_item=".$id_articulo."";
	$result=$db->Execute($sql);
	
	$sql="SELECT MIN(valor) FROM ic_referencias_items WHERE id_articulo='".$id_articulo."' AND stock='S' ";
	$result_ref=$db->Execute($sql);
	list($min_val)=$result_ref->fields;
	
	if(!$result->EOF){
		list($categoria_item, $precio, $descuento, $sostiene_descuento)=$result->fields;
		
		if($min_val!=0){			
			$precio = $min_val;
			$precio_art = $min_val;
		}else{
			$precio_art = $precio;
		}
		
		$descuento_tienda = obtenerDescripcion("DESCUENTO_TIENDA", "", $db);
		
		if($descuento_tienda->fields[0]=="" || $descuento_tienda->fields[0]=="0"){
			if($categoria!=""){
				$sql="SELECT descuento FROM ic_categoria WHERE cat_id='".$categoria."'";
			}else{
				$cw = str_replace("**",",",$categoria_item);
				$cw = str_replace("*","",$cw);
				
				$sql="SELECT descuento FROM ic_categoria WHERE cat_id IN (".$cw.")";
			}
			
			$result=$db->Execute($sql);
			list($descuento_categoria)=$result->fields;
					
			if($descuento_categoria != "" && $descuento_categoria != "0") {
				$descat = $descuento_categoria;
			}
		}else{
			if($descuento_tienda->fields[0] != "" && $descuento_tienda->fields[0] != "0") {
				$descat = $descuento_tienda->fields[0];
			}
		}
		
		if($descuento != "" && $descuento != "0") {
			$despro = $descuento;
		}
		
		if($despro != "" || $descat != ""){
			
			if($sostiene_descuento!="Y" && $descat != ""){
				$despro = "";
			}
			
			$valor2 .= '<span class="descuento"> '.$despro.''.(($despro != "" && $descat != "")?'% off + ':'').''.$descat.'% off </span>';
			$precio_final = $precio_art - ($precio_art * (($despro+$descat)/100));
			//$valor2 .= '<div class="descuento">'._PRECIO_CARRITO.': $'.formateo_numero($precio_final).'</div>';
			$valor2 .= '<span class="descuento">$'.formateo_numero($precio_final).'</span>';
			
			$precio = "<strike>$".formateo_numero($precio)."</strike>";
		}else{
			$precio = "$".formateo_numero($precio)."";
		}
		
		//$valor .= '<div class="precio_producto">'._PRECIO_CARRITO.': $'.$precio.'</div>'.$valor2 ;
		if($min_val!=0){
			$valor .= '<span class="precio_producto">From '.$precio.'</span>'.$valor2 ;
		}else{
			$valor .= '<span class="precio_producto">'.$precio.'</span>'.$valor2 ;
		}		

	}
	
	return $valor;
}

/**********************************************************************************************************************/


//Funcion para determinar el precio final del articulo
function precioFinalCarrito($id_articulo, $categoria, $db){
	
	$valor="";
	$valor2="";
	
	$despro="";
	$descat="";
	
	$sql="SELECT categoria_item,precio,descuento,sostiene_descuento FROM ic_catalogo WHERE id_item=".$id_articulo."";
	$result=$db->Execute($sql);
	
	$sql="SELECT MIN(valor) FROM ic_referencias_items WHERE id_articulo='".$id_articulo."' AND stock='S' ";
	$result_ref=$db->Execute($sql);
	list($min_val)=$result_ref->fields;
	
	if(!$result->EOF){
		list($categoria_item, $precio, $descuento, $sostiene_descuento)=$result->fields;
		
		if($min_val!=0){			
			$precio = $min_val;
			$precio_art = $min_val;
		}else{
			$precio_art = $precio;
		}
		
		$descuento_tienda = obtenerDescripcion("DESCUENTO_TIENDA", "", $db);
		
		if($descuento_tienda->fields[0]=="" || $descuento_tienda->fields[0]=="0"){
			if($categoria!=""){
				$sql="SELECT descuento FROM ic_categoria WHERE cat_id='".$categoria."'";
			}else{
				$cw = str_replace("**",",",$categoria_item);
				$cw = str_replace("*","",$cw);
				
				$sql="SELECT descuento FROM ic_categoria WHERE cat_id IN (".$cw.")";
			}
			
			$result=$db->Execute($sql);
			list($descuento_categoria)=$result->fields;
					
			if($descuento_categoria != "" && $descuento_categoria != "0") {
				$descat = $descuento_categoria;
			}
		}else{
			if($descuento_tienda->fields[0] != "" && $descuento_tienda->fields[0] != "0") {
				$descat = $descuento_tienda->fields[0];
			}
		}
		
		if($descuento != "" && $descuento != "0") {
			$despro = $descuento;
		}
		
		if($despro != "" || $descat != ""){
			
			if($sostiene_descuento!="Y" && $descat != ""){
				$despro = "";
			}
			
			$valor = $precio_art - ($precio_art * (($despro+$descat)/100));
			
		}else{
			$valor = $precio;
		}
	}
	
	return $valor;
}

/**********************************************************************************************************************/
//Funcion para visualizar un listado de todas las subcategorias, subsubcategorias, etc de una categoria principal
function sub_categorias2($categoria, $db, $profundidad){
	
	if($categoria==""){
		$categoria="0";
	}
	
	$sql="SELECT cat_id, cat_titulo, cat_sub_categoria
	      FROM ic_categoria 
		  WHERE id_tipo='6' AND cat_sub_categoria='".$categoria."' ORDER BY orden ASC";
	$result=$db->Execute($sql);

	while(!$result->EOF){
		list($cat_id, $cat_titulo, $cat_sub_categoria)=select_format($result->fields);
		
		$cat="";
		if($categoria!=""){
			$cat=" AND categoria_item LIKE '%*".$cat_id."*%' ";
			
			$contenido = id_sub_categorias($cat_id, $db, "1", "");
			
			if($contenido!=""){
				$cat = " AND ".convertir_categorias_where($contenido.$cat_id)." ";
			}
		}

		$sql_count="SELECT  COUNT(*) FROM ic_catalogo WHERE 1=1 ".$cat."";
		$result_count=$db->Execute($sql_count);		
		list($count)=$result_count->fields;
		
		echo '<table width="100%" border="0" cellspacing="2" cellpadding="2">';
		echo "<tr>";
		
		$espacios="";
		for($i=0; $i<$profundidad; $i++){
			$espacios .= " <td width='5'>&nbsp;</td>";
		}
		
		$imagen = "";
				
		echo "".$espacios."<td>
		<a href='".organizar_nombre_link("productos", $cat_titulo)."' style='color: #666; font-size: 16px;'>&bull; ".$cat_titulo."</a>
		</td>";
		
		echo "</tr>";
		echo '</table>';
		
		if($cat_sub_categoria!="" && $profundidad!=-1){
			$profundidad++;
			//recursividad para visualizar las categorias de las subcategorias
			sub_categorias2($cat_id, $db, $profundidad);
			$profundidad--;
		}
		
		$result->MoveNext();
	}	
}

/**********************************************************************************************************************/
?>