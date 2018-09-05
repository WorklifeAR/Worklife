<?php
 
function contenido($mod,$identificador,$grupo_noti)
{
	$path = "adodb/adodb.inc.php";
	include ("admin/var.php");
	include ("conexion.php");
	
	$variables_ver = variables_metodo("limite,mod,imprimir");
	$limite= 				$variables_ver[0];
	$modulo= 				$variables_ver[1];
	$imprimir= 				$variables_ver[2];
	
	$config=$db->Execute("SELECT c_noti FROM ic_config");
	list ($cont)=select_format($config->fields);
	
	$texto="";
	
	$sql = "SELECT ";

	if ($mod == "contenido"){
		$sql .= "pg_id, pg_titulo, pg_conten, pg_imprimir";
	}elseif($mod == "noticias"){
		$sql .= "n_id, n_titulo, n_des, n_text, n_imagen";
	}

	$sql .= " FROM ic_".$mod." ";

	if ($mod == "contenido"){
		
		$sql .= "WHERE pg_id = '".$identificador."' AND l_lang='".$_SESSION['idioma']."' ";
		
	}elseif($mod == "noticias"){	
		
		$sql .= "WHERE l_lang='".$_SESSION['idioma']."' ";
		
		if($identificador == 0 || $identificador == ""){
		
			if ($grupo_noti!=""){
			
				$sql .= "AND n_categoria = '".$grupo_noti."' ";
				
			}else{
			
				/*if ((isset($_SESSION['sess_usu_grupo']))&&(isset($_SESSION['usuario']))){
				
					$result=$db->Execute("SELECT item_id FROM ic_permisos WHERE gru_id = '".$_SESSION['sess_usu_grupo']."' AND mod = 'categoria'");
					list ($grupo)=select_format($result->fields);
						
					$noticias=$db->Execute("SELECT * FROM ic_noticias WHERE n_categoria='".$grupo."' ");
					list ($grupo_noticias)=select_format($noticias->fields);
				}*/
			}
///////////////////////////////////////////////////////
			
			$sql .= "ORDER BY n_id DESC ";
			
			if ($limite==""){
				$sql .="LIMIT 0,50";
			}			
		}else{
			$sql .= "AND n_id = '".$identificador."' ";
		}
	}
	
	//----------------------
	//echo $sql;
	$result=$db->Execute($sql);	
	$var=0;
	$col=1;
	
	//----------------------
	
	if($mod == "noticias"){
			echo "<h1>"._NOTICIAS."</h1>
			<hr size='1' width='100%' color='#ccc' /><br />";
	}
	
	//----------------------
	
	echo '<table width="100%" border="0" cellspacing="0" cellpadding="0">';
	
	//----------------------
	
	while (!$result->EOF){			
	
		$modulo_bcmod= my_bcmod($var,$col);
		
		//----------------------
		
		if ($mod == "contenido"){
			list ($id, $titulo,$texto,$imprimir) = select_format($result->fields);
			
			$texto = mostrarContenidosEmbebidos($texto, $db);
			
			if($imprimir=="1"){
				if(!isset($_SESSION['usuario'])){
					echo"<META HTTP-EQUIV='Refresh' CONTENT='0;URL=users.html'>";
					die();
				}
			}
		}elseif($mod == "noticias"){
			list ($id,$titulo,$contenido,$texto,$imagen) = select_format($result->fields);
			
			$texto = mostrarContenidosEmbebidos($texto, $db);
		}
		
		//----------------------
		
		if($mod == "noticias"){
			if ($modulo_bcmod==0){
				echo "<tr><td width='50%' valign='top' >";
			}else{
				echo "<td width='50%' valign='top' >";
			}
		}else{
			echo "<tr><td valign='top'>";
		}
		
		//----------------------
		
		if($mod == "noticias"){
		
			echo '<table width="100%" border="0" cellspacing="0" cellpadding="0">
			  <tr>';
			 			
			echo '<td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
				  ';
			
			if($identificador == 0 || $identificador == ""){  
				echo '
				  <tr>
					<td>';
				
				if($imagen!=""){
					echo '<a href="'.organizar_nombre_link("noticias", $titulo).'">
					        <img src="'.$imagen.'" alt="'.$titulo.'" border="0" width="350" style="float:left; margin: 0 15px 15px 0;"/>
					      </a>';
				}
			
				echo '<a href="'.organizar_nombre_link("noticias", $titulo).'"><h2 style="color:#000;">'.$titulo.'</h2></a>'.$contenido.'</td>
				  </tr>
				  <tr>
				    <td align="right"><a href="'.organizar_nombre_link("noticias", $titulo).'">'._LEER_MAS.'</a></td>
				  </tr>
				  <tr><td align="center"><hr size="1" width="100%" color="#333" style="margin: 20px 0;"/></td></tr>
				  <tr><td align="center">&nbsp;</td></tr>';
			}else{
				echo '<tr>
				    <td><a href="'.organizar_nombre_link("noticias", $titulo).'"><h2 style="color:#000;">'.$titulo.'</h2></a></td>
				  </tr>';
				  
				echo '<tr>
					<td>&nbsp;</td>
				  </tr>
				  <tr>
					<td>
					<!-- AddThis Button BEGIN -->
<div class="addthis_toolbox addthis_default_style ">
<a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>
<a class="addthis_button_tweet"></a>
<a class="addthis_button_pinterest_pinit"></a>
<a class="addthis_counter addthis_pill_style"></a>
</div>
<script type="text/javascript">var addthis_config = {"data_track_addressbar":true};</script>
<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-50be66b777323d6b"></script>
<!-- AddThis Button END -->
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
				  </tr>
				  <tr>
					<td>';
				if($imagen!=""){
					echo '<div  style="float: left; margin: 25px 0; width:100%;"><a href="'.organizar_nombre_link("noticias", $titulo).'">
					        <img src="'.$imagen.'" alt="'.$titulo.'" border="0"/>
					      </a></div>';
				}
					
				echo ''.$texto.'</td>
				  </tr>';
			}
			 
			echo '</table></td>
			  </tr>
			</table>';
				
		}elseif($mod == "contenido"){			
			echo '<div id="contenidoInterno">'.$texto.'</div>';
		}
		
		//----------------------
		
		if($mod == "noticias"){
			if($modulo_bcmod==0){
				echo "</td>";
			}else{
				echo "</td></tr>";
			}
		}else{
			echo "</td>";
		}
		
		//----------------------
		
		$var=$var+1;
		$result->MoveNext();
	}
	
	echo '</table>';
}

function contenidoEstatico($mod,$identificador,$db)
{	
	$sql = "SELECT ";
	$sql .= "pg_id, pg_titulo, pg_conten, pg_imprimir";
	$sql .= " FROM ic_".$mod." ";
	$sql .= "WHERE pg_id = '".$identificador."' ";
	$result=$db->Execute($sql);	
	
	//----------------------
	
	while (!$result->EOF){			
	
		list ($id, $titulo,$texto,$imprimir) = select_format($result->fields);
		
		$texto = mostrarContenidosEmbebidos($texto, $db);

		echo ''.$texto.'';
		
		$result->MoveNext();
	}
}
?>