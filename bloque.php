<?php

/* * ******************************************************************************************************************************** */

function area($posicion) {
    $path = "adodb/adodb.inc.php";
    include ("admin/var.php");
    include ("conexion.php");

    $sql = "SELECT b_conten FROM ic_bloque 
			WHERE b_posicion='" . $posicion . "' 
			AND l_lang='" . $_SESSION['idioma'] . "' ORDER BY b_id ASC ";
    $result = $db->Execute($sql);

    while (!$result->EOF) {

        list ($contenido) = select_format($result->fields);

        if (strstr($contenido, "[{USUARIO-SESSION}]")) {
            if (!isset($_SESSION['usuario'])) {
                $contenido = str_replace("[{USUARIO-SESSION}]", '<a href="usuarios.html"><i class="fa fa-user-plus" aria-hidden="true"></i> Registrate</a> | <a href="usuarios.html">Ingresá</a>', $contenido);
            } else {
                $sql = "SELECT count(*) FROM ic_respuesta_cotizacion WHERE id_usuario_destino ='" . $_SESSION['sess_usu_id'] . "' AND estado='UNREAD'";
                $result = $db->Execute($sql);
                list($can_msg) = select_format($result->fields);

                $msg = "";
                if ($can_msg > 0) {
                    $msg = '<span class="label label-warning">' . $can_msg . '</span>';
                }

                $contenido = str_replace("[{USUARIO-SESSION}]", '<a href="usuarios.html"><i class="fa fa-user-circle" aria-hidden="true"></i> Mi cuenta</a> | <a href="cotizaciones.html"><i class="fa fa-envelope-o" aria-hidden="true"></i> Mensajes ' . $msg . '</a> | <a href="index.php?salir=1"> <i class="fa fa-window-close-o" aria-hidden="true"></i> Salir</a>', $contenido);
            }
        }
        //eval("\$contenido = \"$contenido\";");
        echo $contenido;

        $result->MoveNext();
    }
}

/* * ******************************************************************************************************************************** */

function menu() {

    $path = "adodb/adodb.inc.php";
    include ("admin/var.php");
    include ("conexion.php");

    $sql = "SELECT m_id,l_lang,m_nombre,m_link,m_desc,activo,m_target
	      FROM ic_menu 
		  WHERE l_lang='" . $_SESSION['idioma'] . "' ORDER BY m_id";
    $result = $db->Execute($sql);

    while (!$result->EOF) {
        list ($m_id, $l_lang, $m_nombre, $m_link, $m_desc, $activo, $m_target) = select_format($result->fields);

        echo '<li><a href="' . $m_link . '" target="' . $m_target . '" class="menu_header">' . $m_nombre . '</a></li>';

        $result->MoveNext();

        /* if(!$result->EOF){
          echo '<li><div style="width:1px; height:25px; border-left:1px solid #000;"></div></li>';
          } */
    }
}

/* * ******************************************************************************************************************************** */

function buscar($buscar) {

    $path = "adodb/adodb.inc.php";
    include ("admin/var.php");
    include ("conexion.php");

    $sql = "SELECT id_item, nombre_item FROM ic_catalogo 
			WHERE estado='Available' AND (nombre_item LIKE '%" . $buscar . "%' OR previa_item LIKE '%" . $buscar . "%' OR full_item LIKE '%" . $buscar . "%')  ";
    $result = $db->Execute($sql);

    $sql_2 = "SELECT pg_id, pg_titulo FROM ic_contenido 
			WHERE (pg_titulo LIKE '%" . $buscar . "%' OR pg_conten LIKE '%" . $buscar . "%') 
			AND l_lang='" . $_SESSION['idioma'] . "' ";
    $result_2 = $db->Execute($sql_2);

    $sql_3 = "SELECT faq_id, faq_titulo FROM ic_faq 
			WHERE (faq_titulo LIKE '%" . $buscar . "%' OR faq_conten LIKE '%" . $buscar . "%')
			AND l_lang='" . $_SESSION['idioma'] . "' ";
    $result_3 = $db->Execute($sql_3);

    //-------------------------------------------------------------------------

    echo '<div align="center">
		<table width="100%"  border="0" cellspacing="0" cellpadding="10">
			<tr>
			  <td><h1>' . _RESULTADO_BUSQUEDA . '</h1></td>
			</tr>
			<tr>
			  <td><table width="100%"  border="0" cellspacing="0" cellpadding="5">
				  <tr>
					<td colspan="2"><h1>Products</h1></td>
				  </tr>';

    //-------------------------------------------------------------------------

    $enc = 0;
    while (!$result->EOF) {
        list ($id_item, $nombre_item) = select_format($result->fields);

        if ($id_item != "") {
            echo'<tr><td width="2%" valign=top >&nbsp;<strong>&bull;</strong></td>';
            echo'<td><a href="' . organizar_nombre_link("productos", $nombre_item) . '">' . $nombre_item . '</a></td></tr>';
            $enc = 1;
        }
        $result->MoveNext();
    }

    if ($enc == 0) {
        echo'<tr><td align="center" colspan="2" ><h1>' . _MSG_NO_RSULTADO . '</h1></td></tr>';
    }

    //-------------------------------------------------------------------------

    echo '</table></td>
		</tr>
		<tr>
		  <td><table width="100%"  border="0" cellspacing="0" cellpadding="5">
			  <tr>
				<td colspan="2"><h1>' . _MSG_CONTENIDO_PORTAL . '</h1></td>
			  </tr>';

    //-------------------------------------------------------------------------

    $enc2 = 0;
    while (!$result_2->EOF) {
        list ($pg_id, $pg_titulo) = select_format($result_2->fields);

        if ($pg_id != "") {
            echo'<tr><td width="2%" valign=top >&nbsp;<strong>&bull;</strong></td>';
            echo'<td><a href="' . organizar_nombre_link("contenido", $pg_titulo) . '">' . $pg_titulo . '</a></td></tr>';
            $enc2 = 1;
        }
        $result_2->MoveNext();
    }

    if ($enc2 == 0) {
        echo'<tr><td align="center" colspan="2" ><h1>' . _MSG_NO_RSULTADO . '</h1></td></tr>';
    }

    //-------------------------------------------------------------------------

    echo '</table></td>
		</tr>
		<tr>
		  <td><table width="100%"  border="0" cellspacing="0" cellpadding="5">
			  <tr>
				<td colspan="2"><h1>' . _MSG_FAQ_PORTAL . '</h1></td>
			  </tr>';

    //-------------------------------------------------------------------------

    $enc3 = 0;
    while (!$result_3->EOF) {
        list ($faq_id, $faq_titulo) = select_format($result_3->fields);

        if ($faq_id != "") {
            echo'<tr><td width="2%" valign=top >&nbsp;<strong>&bull;</strong></td>';
            echo'<td><a href=faq.html#' . $faq_id . '">' . $faq_titulo . '</a></td></tr>';
            $enc3 = 1;
        }
        $result_3->MoveNext();
    }

    if ($enc3 == 0) {
        echo'<tr><td align="center" colspan="2" ><h1>' . _MSG_NO_RSULTADO . '</h1></td></tr>';
    }

    //-------------------------------------------------------------------------

    echo '</table></td>
		</tr>
	  </table>  
	</div>';
}

/* * ******************************************************************************************************************************** */

function logueo() {

    if (!isset($_SESSION['usuario'])) {
        echo'<form action="" method="post" name="login" id="login" >
			  <table border="0" cellspacing="0" cellpadding="2">
				<tr>
				  <td><span class="titulo">' . _MSG_INGRESO_SISTEMA . '</span></td>
				</tr>
				<tr>
				  <td><font size="1"><strong>' . _USUARIO . '</strong></font></td>
				</tr>
				<tr>
				  <td><input name="us_login" type="text" id="us_login" style="width:165px"></td>
				</tr>
				<tr>
				  <td><strong><font size="1">' . _CONTRASENA . '</font></strong></td>
				</tr>
				<tr>
				  <td><input name="us_pass" type="password" id="us_pass" style="width:165px"></td>
				</tr>
				<tr>
				  <td align="right">
					  <input type="hidden" name="funcion" value="login_usuario" />
					  <input type="submit" name="Submit" class="boton"" value="Entrar">
				  </td>
				</tr>
				<tr>
				  <td><a href="recordar-clave.html">' . _MSG_OLVIDAR_PASS . '</a></td>
				</tr>
				<tr>
				  <td><a href="info-usuario.html">' . _NUEVO_USUARIO . '</a></td>
				</tr>
			  </table>
			</form>';
    } else {
        echo'<table border="0" cellspacing="0" cellpadding="3">			
			  <tr>
				<td style="font-size:11px;">' . _MSG_BIENVENIDO . '<br><b>' . $_SESSION['sess_nombre'] . '</b></td>
			  </tr>';
        if ($_SESSION['usuario'] == "admin") {
            echo'<tr>
					<td>&bull; <a href="admin.php" >' . _PANEL_ADMIN . '</a></td>
				 </tr>
				';
        }
        echo' <tr>
			    <td>&bull; <a href="index.php/salir/1" >' . _SALIR_CERRAR . '</a></td>
			  </tr>
			  <tr>
				<td>&nbsp;</td>
			  </tr>
			 </table>';
    }
}

/* * ******************************************************************************************************************************** */

function desplegarModulos($mod, $identificador, $buscar) {

    $variables_ver = variables_metodo("id_video,id_galeria");
    $id_video = $variables_ver[0];
    $id_galeria = $variables_ver[1];

    //Condiciones para la carga de modulo en la platilla
    if ($mod == "contenido" || $mod == "noticias") {
        //Modulo de paginas estaticas y noticias
        contenido($mod, $identificador, '');
        //-----------------------------------------------------------------------------
    } elseif ($mod == "buscar") {
        //Modulo de motor de busqueda interno
        buscar($buscar);
        //-----------------------------------------------------------------------------
    } elseif ($mod == "mis_publicaciones") {
        //Modulo de motor de busqueda interno
        include ("mis-publicaciones.php");
        //-----------------------------------------------------------------------------
    } elseif ($mod == "contactenos") {
        //Pantalla de contactenos
        include ("contactenos.php");
        //-----------------------------------------------------------------------------
    } elseif ($mod == "invitar_amigos") {
        //Pantalla de contactenos
        include ("invitar-amigos.php");
        //-----------------------------------------------------------------------------
    } elseif ($mod == "denuncia") {
        //Pantalla de contactenos
        include ("denuncias.php");
        //-----------------------------------------------------------------------------
    } elseif ($mod == "faq") {
        //Modulo de FAQs
        include ("faq.php");
        //-----------------------------------------------------------------------------
    } elseif ($mod == "eventos") {
        //Modulo de Eventos
        include ("calendario_eventos/eventos.php");
        //-----------------------------------------------------------------------------
    } elseif ($mod == "encuesta") {
        //Modulo de encuestas
        include ("encuesta/encuesta.php");
        //-----------------------------------------------------------------------------
    } elseif ($mod == "descargas") {
        //Modulo de encuestas
        include ("descargas/listado_descargas.php");
        //-----------------------------------------------------------------------------
    } elseif ($mod == "videos") {
        if ($id_video != "") {
            //Modulo de Videos
            include ("videos/video.php");
        } else {
            //Modulo de Listado de Videos
            include ("videos/listado_videos.php");
        }
        //-----------------------------------------------------------------------------
    } elseif ($mod == "galeria") {
        if ($id_galeria != "") {
            //Modulo de categorias de galeria de imagenes
            include ("galeria/galeria_categoria.php");
        } else {
            //Modulo de Listado de imagenes por categorias
            include ("galeria/listado_galeria.php");
        }
        //-----------------------------------------------------------------------------
    } elseif ($mod == "cat_catalogo") {
        //Modulo de encuestas
        include ("catalogo/categoria_catalogo.php");
        //-----------------------------------------------------------------------------
    } elseif ($mod == "catalogo") {
        //Modulo de encuestas
        include ("catalogo/catalogo.php");
        //-----------------------------------------------------------------------------
    } elseif ($mod == "producto") {
        //Modulo de encuestas
        include ("catalogo/producto.php");
        //-----------------------------------------------------------------------------
    } elseif ($mod == "carrito") {
        //Modulo de encuestas
        include ("compras/carrito.php");
        //-----------------------------------------------------------------------------
    } elseif ($mod == "ordenes") {
        //Modulo de encuestas
        include ("compras/pedidos.php");
        //-----------------------------------------------------------------------------
    }
}

/* * ******************************************************************************************************************************** */

//Bloque para inclusion del calendario de eventos.
function calendario() {
    $path = "adodb/adodb.inc.php";
    include "admin/var.php";
    include "conexion.php";

    include("calendario_eventos/calendario_eventos.php");
}

/* * ******************************************************************************************************************************** */

//Bloque para inclusion de la encuesta.
function encuesta() {

    $path = "adodb/adodb.inc.php";
    include ("admin/var.php");

    include("encuesta/detalle_encuesta.php");
}

/* * ******************************************************************************************************************************** */

//Modulo para la inlcusion de la visuializacion de banners.
function banner($publicacion, $db) {

    if ($publicacion != "") {
        $sql = "SELECT id_banner FROM ic_banners WHERE publicacion_banner='" . $publicacion . "' AND l_lang='" . $_SESSION["idioma"] . "'";
    } else {
        $sql = "SELECT MAX(id_banner) FROM ic_banners ";
    }

    $result = $db->Execute($sql);
    $banners_array = NULL;

    while (!$result->EOF) {
        $banners = $result->fields;

        $banners_array .= $banners[0];

        $result->MoveNext();

        if (!$result->EOF) {
            $banners_array .= ",";
        }
    }

    $id_banner_mostrar = $banners_array;


    $sql = "SELECT url_banner,nombre_banner,link_banner FROM ic_banners WHERE id_banner IN (" . $id_banner_mostrar . ") ORDER BY id_banner ASC";
    $result = $db->Execute($sql);

    while (!$result->EOF) {
        list($url, $nombre_banner, $link_banner) = select_format($result->fields);

        echo '<li data-initialZoom="1" data-finalZoom="1" data-horizontalPosition="center" data-verticalPosition="top">';

        if ($url == "") {
            echo '<br /><p aling="center">No se encontraron banners relacionados.</p>';
        } else {
            $archivo = basename($url);
            $archivo = explode(".", $archivo);

            $tamano = getimagesize($url);

            if (strtoupper($archivo[(count($archivo) - 1)]) != "SWF") {
                if ($link_banner != "") {
                    echo '<a href="' . $link_banner . '">';
                }

                echo '<img src="' . $url . '" border="0" alt="' . $nombre_banner . '" width="' . $tamano[0] . '" height="' . $tamano[1] . '" />';

                if ($link_banner != "") {
                    echo '</a>';
                }
            } else {
                echo '<div align="center">
						<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" 
								 codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0" 
								 width="671" height="166">
							<param name="movie" value="' . $url . '" />
							<param name="quality" value="high" />
							<embed src="' . $url . '" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" 
								   type="application/x-shockwave-flash" width="' . $tamano[0] . '" height="' . $tamano[1] . '"></embed>
						</object>
					  </div>';
            }
        }

        echo "</li>";
        $result->MoveNext();
    }
}

/* * ******************************************************************************************************************************** */

//Modulo para la inlcusion de la visuializacion de banners.
function bannerSimple($publicacion, $db) {

    if ($publicacion != "") {
        $sql = "SELECT id_banner FROM ic_banners WHERE publicacion_banner='" . $publicacion . "' AND l_lang='" . $_SESSION["idioma"] . "'";
    } else {
        $sql = "SELECT MAX(id_banner) FROM ic_banners ";
    }

    $result = $db->Execute($sql);
    $banners_array = NULL;

    while (!$result->EOF) {
        $banners = $result->fields;

        if (strstr(strtoupper($publicacion), "SLIDE")) {
            $banners_array .= $banners[0];
        } else {
            $banners_array = $banners[0];
        }

        $result->MoveNext();

        if (!$result->EOF && strstr(strtoupper($publicacion), "SLIDE")) {
            $banners_array .= ",";
        }
    }

    if (strstr(strtoupper($publicacion), "SLIDE")) {
        $id_banner_mostrar = $banners_array;
    } else {
        $id_banner_mostrar = mt_rand(0, count($banners_array) - 1);
        $id_banner_mostrar = $banners[$id_banner_mostrar];
    }

    $sql = "SELECT url_banner,nombre_banner,link_banner FROM ic_banners WHERE id_banner IN (" . $id_banner_mostrar . ") ORDER BY id_banner ASC";
    $result = $db->Execute($sql);

    while (!$result->EOF) {
        list($url, $nombre_banner, $link_banner) = select_format($result->fields);

        if ($link_banner != "") {
            echo '<a href="' . $link_banner . '">';
        }

        echo '<img src="' . $url . '" border="0" alt="' . $nombre_banner . '" style="width:100%; height:auto;" />';

        if ($link_banner != "") {
            echo '</a>';
        }

        $result->MoveNext();
    }
}

/* * ******************************************************************************************************************************** */

//Modulo para la inlcusion de la visuializacion de banners.
function bannerSlide($publicacion, $db) {

    $sql = "SELECT url_banner,nombre_banner,link_banner FROM ic_banners WHERE publicacion_banner='" . $publicacion . "' AND l_lang='" . $_SESSION["idioma"] . "' ORDER BY id_banner ASC";
    $result = $db->Execute($sql);

    while (!$result->EOF) {
        list($url, $nombre_banner, $link_banner) = select_format($result->fields);

        echo "<li>";

        if ($link_banner != "") {
            echo '<a href="' . $link_banner . '">';
        }

        echo '<img src="' . $url . '" border="0" alt="' . $nombre_banner . '" style="width:100%; height:auto;" />';

        if ($link_banner != "") {
            echo '</a>';
        }

        echo "</li>";

        $result->MoveNext();
    }
}

/* * ******************************************************************************************************************************** */

function capchat() {

    echo '
<script type="text/javascript">
	function validar_capchat(){
		var contenido = document.getElementById("captcha").value;
	
		var ajax=nuevoAjax();
		ajax.open("POST", "admin/captcha/valida.php?", false);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("captcha="+contenido);
		
		if (ajax.readyState==4)
		{
			if(ajax.responseText)
			{
				var validacion = ajax.responseText;	
				if(validacion!="OK"){
					alert(validacion);
					document.getElementById("captcha").value="";
					return false;
				}
			}else{
				alert("' . _CAP_VALID . '");
				return false;
			}
		}
		
		return true;
	}
</script>';

    echo '<table border="0" cellspacing="0" cellpadding="4">
		<tr>
		  <td><img src= "admin/captcha/getImage.php" /></td>
		  <td><input name="captcha" type="text" id="captcha" size="10" placeholder="' . _CAP_TEXT_VALID . '"/></td>
		</tr>
	  </table>';
}

/* * ******************************************************************************************************************************** */
?>