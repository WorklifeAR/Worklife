<?php
//Cabeceras del documento
include("cabecera.php");

$_SESSION['url_visto'] = "";

$variables_ver = variables_metodo("orden,pag,categorias_total,categorias_eliminar");
$orden = $variables_ver[0];
$pag = $variables_ver[1];
$categorias_total = $variables_ver[2];
$categorias_eliminar = $variables_ver[3];

$variables_ver = variables_metodo("palabra,categoria,categoriastop,horario_man,horario_tar,horario_noc,dia_lun,dia_mar,dia_mie,dia_jue,dia_vie,dia_sab,dia_dom,realiza_domi,realiza_esta,realiza_ofic,realiza_onli,facebook_amigos,matricula,garantia,seguro,horario_24,distancia");
$palabra = $variables_ver[0];
$categoria = $variables_ver[1];
$categoriastop = $variables_ver[2];
$horario_man = $variables_ver[3];
$horario_tar = $variables_ver[4];
$horario_noc = $variables_ver[5];
$dia_lun = $variables_ver[6];
$dia_mar = $variables_ver[7];
$dia_mie = $variables_ver[8];
$dia_jue = $variables_ver[9];
$dia_vie = $variables_ver[10];
$dia_sab = $variables_ver[11];
$dia_dom = $variables_ver[12];
$realiza_domi = $variables_ver[13];
$realiza_esta = $variables_ver[14];
$realiza_ofic = $variables_ver[15];
$realiza_onli = $variables_ver[16];
$facebook_amigos = $variables_ver[17];
$matricula = $variables_ver[18];
$garantia = $variables_ver[19];
$seguro = $variables_ver[20];
$horario_24 = $variables_ver[21];
$distancia = $variables_ver[22];

////////////////////////////////////////////////////

$categorias_total = ""; //str_replace("|",",",$categorias_total);

if ($categoria != "") {
    $categorias_total .= "*" . $categoria . "*";
}

if ($categoriastop != "" && count($categorias_total) > 0) {
    foreach ($categoriastop as $cattop) {
        $categorias_total .= "*" . $cattop . "*";
    }
}

if ($categorias_eliminar != "") {
    $categorias_total = str_replace("*" . $categorias_eliminar . "*", "", $categorias_total);
    $categorias_eliminar == "";
}

if ($categorias_total != "") {
    $categorias_total = str_replace("**", ",", $categorias_total);
    $categorias_total = str_replace("*", "", $categorias_total);
}

////////////////////////////////////////////////////

$art = 15;
$basekm = 50;

if ($distancia == "") {
    $distancia = $basekm;
}

//Variables de la paginacion
$count_1 = $art;
$limit = 1;
$count_2 = 0;

if ($pag != "") {
    $limit = $pag;
    $count_2 = ($count_1 * $limit) - $count_1;
} else {
    $pag = 1;
}

///////
$orden_sql = "";

if ($orden == "" || $orden == "rel") {
    $orden = "rel";
} elseif ($orden == "fec") {
    $orden_sql = ", fecha DESC";
}

///////

$where = "";

///////

if ($distancia < $basekm) {
    $count_2 = 0;
    $art = 100;
}

///////	

if ($palabra != "") {
    $where .= " AND (nombre LIKE '%" . $palabra . "%' OR ciudad LIKE '%" . $palabra . "%' OR descripcion LIKE '%" . $palabra . "%' OR web LIKE '%" . $palabra . "%')";
}

//////////

if ($horario_man != "") {
    $where .= " AND horario_man=1";
}
if ($horario_tar != "") {
    $where .= " AND horario_tar=1";
}
if ($horario_noc != "") {
    $where .= " AND horario_noc=1";
}
if ($horario_24 != "") {
    $where .= " AND horario_24=1";
}

//////////

if ($realiza_domi != "") {
    $where .= " AND realiza_domi=1";
}
if ($realiza_ofic != "") {
    $where .= " AND realiza_ofic=1";
}
if ($realiza_onli != "") {
    $where .= " AND realiza_onli=1";
}
if ($realiza_esta != "") {
    $where .= " AND realiza_esta=1";
}

//////////

if ($matricula != "") {
    $where .= " AND matricula!=''";
}
if ($garantia != "") {
    $where .= " AND garantia!=''";
}
if ($seguro != "") {
    $where .= " AND seguro!=''";
}

//////////

if ($dia_lun != "") {
    $where .= " AND dia_lun=1";
}
if ($dia_mar != "") {
    $where .= " AND dia_mar=1";
}
if ($dia_mie != "") {
    $where .= " AND dia_mie=1";
}
if ($dia_jue != "") {
    $where .= " AND dia_jue=1";
}
if ($dia_vie != "") {
    $where .= " AND dia_vie=1";
}
if ($dia_sab != "") {
    $where .= " AND dia_sab=1";
}
if ($dia_dom != "") {
    $where .= " AND dia_dom=1";
}

//////////

if ($categorias_total != "") {
    $where .= " AND id_directorio IN (SELECT id_directorio FROM ic_directorio_categoria WHERE id_categoria IN (" . $categorias_total . "))";
}

//////////
?>

<link rel="stylesheet" href="dist/app.css">
<link rel="stylesheet" href="dist/bootstrap-tagsinput.css">
<style>
    
    .logo{
        width: 100%;
    }

    @media screen and (max-width: 992px) {
        .logo{
            width: auto;
        }
        .tar {
            text-align: center;
        }
    }

</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/typeahead.js/0.11.1/typeahead.bundle.min.js"></script>
<script src="dist/bootstrap-tagsinput.min.js"></script>

<div style="width:100%; padding:5px 0; box-sizing:border-box;">
    <div style="width:100%; box-sizing:border-box;">
        <div class="row" style="width:100%; box-sizing:border-box; margin-bottom:10px;">
            <div class="col-md-2 col-xs-12" style="text-align:center;">
                <a href="index.html"><img class="logo" src="images/logo.jpg"/></a>
            </div>  
            <div class="col-md-4 col-xs-12">
                <form action="listado-servicios.html" method="get" id="serach_list" name="serach_list">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="¿Qué Servicio Buscás?" id="search_services" name="search_services" style="border-radius:5px 0 0 5px;border-right:none;  padding:2px 10px; height:33px; border:1px solid #ccc;" />
                        <span class="input-group-btn">
                            <button class="btn btn-secondary buscarBtn" type="button" onclick="enviarBusqueda();" style="background:#FAA532;border-radius:0 5px 5px 0;">
								<i class="fa fa-search" aria-hidden="true"></i>
							</button>
                        </span>
                    </div>
					
					<input type="hidden" name="palabra" id="palabra_search" />
					<input type="hidden" name="categoria" id="categoria_search" />
				
                    <!--
                    <table width="100%" border="0" style="margin:0 0 0 0; padding:0;"><tr>
                            <td><input type="text" placeholder="¿Qué Servicio Buscas?" style="width:100%; border-radius:5px 0 0 5px; background:#fff;  border:1px solid #ccc; padding:5px 10px;  position:relative; z-index:50;  margin:0 !importan; font-size:16px; height:35px;" id="search_services" name="search_services" /></td>
                            <td width="30" nowrap valign="top" style="padding:16px 0 0 0;"><a href="#" class="buscarBtn" style="background:#FAA532; padding:9px 8px 10px 8px; position:relative; z-index:100; margin:0 0 0 -12px; font-size:14px; color:#FFF; border-radius:0 5px 5px 0;"><i class="fa fa-search" aria-hidden="true"></i>
                                </a></td></tr></table>
                    <input type="hidden" name="palabra" id="palabra_search" /><input type="hidden" name="categoria" id="categoria_search" />
                    -->
                </form>
            </div>
            <div class="col-md-1 col-xs-12"></div> 
            <div class="col-md-5 col-xs-12" style="text-align:center; padding:13px 0 0 0;">
                <div style="display:inline-block; vertical-align:top;">
					<a href="javascript:;" onclick="enviarAccion('', 'nueva');" class="label label-warning" style="font-size:16px; padding:5px 15px; color:#fff;">Quiero publicar!</a>
				</div>
                <div style="padding:0 0 0 20px; display:inline-block; vertical-align:top;">
                    <?php
                    if (!isset($_SESSION['usuario'])) {
                        echo '<a href="usuarios.html"><i class="fa fa-user-plus" aria-hidden="true"></i> Registrate</a> | <a href="usuarios.html">Ingresá</a>';
                    } else {
                        $sql = "SELECT count(*) FROM ic_respuesta_cotizacion WHERE id_usuario_destino ='" . $_SESSION['sess_usu_id'] . "' AND estado='UNREAD'";
                        $result = $db->Execute($sql);
                        list($can_msg) = select_format($result->fields);

                        $msg = "";
                        if ($can_msg > 0) {
                            $msg = '<span class="label label-warning">' . $can_msg . '</span>';
                        }

                        echo '<a href="usuarios.html"><i class="fa fa-user-circle" aria-hidden="true"></i> Mi cuenta</a> <span style="margin:0 10px;">|</span> <a href="cotizaciones.html"><i class="fa fa-envelope-o" aria-hidden="true"></i> Mensajes ' . $msg . '</a> <span style="margin:0 10px;">|</span> <a href="index.php?salir=1"> <i class="fa fa-window-close-o" aria-hidden="true"></i> Salir</a>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="profesional" style="margin:0 0;">

</div>
<script>
$(document).ready(function() {
		
	$(".itemMenu").click(function() {
		$(".menuListado").show();
	});
	
	$(".cerrarListado").click(function() {
		$(".menuListado").fadeOut();
	});
});
</script>
<div class="itemMenu">
	<i class="fa fa-bars" aria-hidden="true"></i> 
</div>

<div style="width:100%; margin:0 auto; overflow:hidden;">
    <div class="row">
        <div class="col-md-2 col-xs-12 menuListado left-side" style="background:#EEEEEE; height:100% !important; min-height:100% !important;">
			<div class="cerrarListado">
			[X]
			</div>
            <div style="box-sizing:border-box; padding:10px 30px 50px 30px;">
                <h1>Filtros</h1>
                <form name="filtros" id="filtros" method="get" enctype="multipart/form-data" class="filtros">
                    <input type="hidden" name="categorias_total" id="categorias_total" value="<?= str_replace(",", "|", $categorias_total) ?>" />
                    <input type="hidden" name="categorias_eliminar" id="categorias_eliminar"  />

                    <strong style="color:#7C4793">Palabra clave</strong><br/>
                    <input name="palabra" type="text" id="palabra" value="<?= $palabra ?>" class="form-control" style="background:#fff; border:1px solid #ccc; height:40px !important; border-radius:3px;" />
                    <br/>

                    <!---<strong style="color:#7C4793">Categorías</strong><br/>
                    <input name="categoria" type="text" id="categoria" class="form-control" />
                    <script>
                        var categories = new Bloodhound({
                            datumTokenizer: Bloodhound.tokenizers.obj.whitespace('text'),
                            queryTokenizer: Bloodhound.tokenizers.whitespace,
                            prefetch: 'data/categories.php'
                        });
                        categories.initialize();

                        var elt = $('#categoria');
                        elt.tagsinput({
                            itemValue: 'value',
                            itemText: 'text',
                            typeaheadjs: {
                                name: 'categories',
                                displayKey: 'text',
                                source: categories.ttAdapter()
                            },
                            tagClass: 'label label-warning'
                        });

                        $('#categoria').on('itemAdded', function (event) {
                            setTimeout(function () {
                                document.filtros.submit();
                            }, 700);
                        });
                    </script>
                    <br/><br/>-->


                    <strong style="color:#7C4793">Distancia:</strong> <span id="km"><?= $distancia ?>km</span><br/>
                    <input type="range" min="1" max="<?= $basekm ?>" name="distancia" id="distancia" value="<?= $distancia ?>" />
                    <br/><br/>

                    <strong class="categorias">Categorías</strong>
                    <?php
                    if ($where == "") {
                        $sql = "SELECT cat_id,cat_titulo,count(*) AS total FROM ic_categoria a, ic_directorio_categoria b WHERE cat_id=id_categoria GROUP BY cat_id,cat_titulo ORDER BY total DESC LIMIT 0,5";
                    } else {
                        $sql = "SELECT id_categoria, cat_titulo,count(*) AS total 
			  FROM ic_categoria a, ic_directorio_categoria b 
			  WHERE cat_id=id_categoria AND 
					id_directorio IN (SELECT id_directorio FROM ic_directorio WHERE activo=1 " . $where . ")
			  GROUP BY id_categoria,cat_titulo
			  ORDER BY total DESC LIMIT 0,10";
                    }
                    $result = $db->Execute($sql);

                    $data = array();
                    while (!$result->EOF) {
                        list($id, $titulo, $total) = select_format($result->fields);

                        $selected = "";
                        $negrita = "normal";

                        if ($categoriastop != "") {
                            foreach ($categoriastop as $cattop) {
                                if ($cattop == $id) {
                                    $selected = "checked";
									$negrita = "bold";
                                }
                            }
                        }

                        echo '	<br/>
                <label><input type="checkbox" name="categoriastop[]" id="categoriastop' . $id . '" ' . $selected . ' value="' . $id . '" /><span style="font-weight:'.$negrita.';"> ' . $titulo . '</span></label>
';

                        $result->MoveNext();
                    }
                    ?>               
                    <br/><br/>


                    <strong style="color:#7C4793">Servicios</strong><br/>
                    <label><input type="checkbox" name="realiza_domi" id="realiza_domi" <?= (($realiza_domi == "1") ? "checked" : "") ?> value="1" /> A Domicilio </label>
                    <br/>
                    <label><input type="checkbox" name="realiza_esta" id="realiza_esta" <?= (($realiza_esta == "1") ? "checked" : "") ?> value="1" /> Establecimiento</label>
                    <br/>
                    <label><input type="checkbox" name="realiza_ofic" id="realiza_ofic" <?= (($realiza_ofic == "1") ? "checked" : "") ?> value="1" /> Oficina/ Local</label>
                    <br/>
                    <label><input type="checkbox" name="realiza_onli" id="realiza_onli" <?= (($realiza_onli == "1") ? "checked" : "") ?> value="1" /> Freelance</label>
                    <br/>
                    <label><input type="checkbox" name="horario_24" id="horario_24" <?= (($horario_24 == "1") ? "checked" : "") ?> value="1" /> Urgencia 24hs</label>
                    <br/><br/>

                    <strong style="color:#7C4793">Seguridad</strong><br/>
                    <label><input type="checkbox" name="facebook_amigos" id="facebook_amigos" <?= (($facebook_amigos == "1") ? "checked" : "") ?> value="1" /> Amigos de amigos</label>
                    <br/>
                    <label><input type="checkbox" name="matricula" id="matricula" <?= (($matricula == "1") ? "checked" : "") ?> value="1" /> Matriculados</label>
                    <br/>
                    <label><input type="checkbox" name="seguro" id="seguro" <?= (($seguro == "1") ? "checked" : "") ?> value="1" /> Asegurados (ART)</label>
                    <br/>
                    <label><input type="checkbox" name="garantia" id="garantia" <?= (($garantia == "1") ? "checked" : "") ?> value="1" /> Garantía</label>
                    <br/><br/>

                    <strong style="color:#7C4793">Horario de atención</strong><br/>
                    <label><input type="checkbox" name="horario_man" id="horario_man" <?= (($horario_man == "1") ? "checked" : "") ?> value="1" /> Mañana</label>
                    <br/>
                    <label><input type="checkbox" name="horario_tar" id="horario_tar" <?= (($horario_tar == "1") ? "checked" : "") ?> value="1" /> Tarde</label>
                    <br/>
                    <label><input type="checkbox" name="horario_noc" id="horario_noc" <?= (($horario_noc == "1") ? "checked" : "") ?> value="1" /> Noche</label>
                    <br/><br/>

                    <strong style="color:#7C4793">Disponibilidad</strong><br/>
                    <label><input type="checkbox" name="dia_lun" id="dia_lun" <?= (($dia_lun == "1") ? "checked" : "") ?> value="1" /> Lunes</label>
                    <br/>
                    <label><input type="checkbox" name="dia_mar" id="dia_mar" <?= (($dia_mar == "1") ? "checked" : "") ?> value="1" /> Martes</label>
                    <br/>
                    <label><input type="checkbox" name="dia_mie" id="dia_mie" <?= (($dia_mie == "1") ? "checked" : "") ?> value="1" /> Miércoles</label>
                    <br/>
                    <label><input type="checkbox" name="dia_jue" id="dia_jue" <?= (($dia_jue == "1") ? "checked" : "") ?> value="1" /> Jueves</label>
                    <br/>
                    <label><input type="checkbox" name="dia_vie" id="dia_vie" <?= (($dia_vie == "1") ? "checked" : "") ?> value="1" /> Viernes
                        <br/>
                        <label><input type="checkbox" name="dia_sab" id="dia_sab" <?= (($dia_sab == "1") ? "checked" : "") ?> value="1" /> Sabado</label>
                        <br/>
                        <label><input type="checkbox" name="dia_dom" id="dia_dom" <?= (($dia_dom == "1") ? "checked" : "") ?> value="1" /> Domingo</label>
                        <br/><br/>

                        <input type="hidden" name="pag" id="pag" val="<?= $pag ?>" />
                        <input type="hidden" name="orden" id="orden" val="<?= $orden ?>" />
                </form>
            </div>
        </div>
        <div class="col-md-10 col-xs-12 right-side">
            <?php
            $sql = "SELECT id_directorio, nombre, categoria, descripcion, direccion, ciudad, estado, zip, telefono, web, info_adicional, email, sponsor, logo,
				   latitud, longitud, orden, id_usuario, activo, destacado, img1, img2, img3, video, fecha, visualizaciones, horario_man, horario_tar, 
				   horario_noc, dia_lun, dia_mar, dia_mie, dia_jue, dia_vie, dia_sab, dia_dom, urgencia, realiza_domi, realiza_esta, realiza_ofic, realiza_onli, 
				   certificado_estudio, matricula, garantia, seguro, horario_24 FROM (
				SELECT id_directorio, nombre, categoria, descripcion, direccion, ciudad, estado, zip, telefono, web, info_adicional, email, sponsor, logo,
					   latitud, longitud, orden, id_usuario, activo, destacado, img1, img2, img3, video, fecha, visualizaciones, horario_man, horario_tar, 
					   horario_noc, dia_lun, dia_mar, dia_mie, dia_jue, dia_vie, dia_sab, dia_dom, urgencia, realiza_domi, realiza_esta, realiza_ofic, realiza_onli, 
					   certificado_estudio, matricula, garantia, seguro, horario_24
				FROM ic_directorio 
				WHERE 1=1 ) tabla
			WHERE activo=1 " . $where . "
			ORDER BY destacado DESC " . $orden_sql . " LIMIT " . $count_2 . "," . $art . "";
            $result = $db->Execute(utf8_decode($sql));

            //Consulta para contar la cantidad de productos a mostrar
            $sql_count = "SELECT COUNT(id_directorio) FROM ic_directorio WHERE 1=1 " . $where . "";
            $result_count = $db->Execute($sql_count);
            list($count) = $result_count->fields;
            ?>

            <div style="box-sizing:border-box; padding:10px 1% 50px;">
                <!--<div style="width:300px; height:5px; background:#FAA532; "></div>-->
                <div style="width:100%; margin:10px 0 10px 0; font-size:25px; color:#F9A532; font-weight:bold;"><?php if ($distancia < $basekm) { ?> Resultados cercanos<?php } else { ?> Resultados: <?= $count ?> publicaciones <?php } ?></div>
                <div style="width:100%; margin:0 0 10px 0;">
                    Filtros:
                    <?php
                    if ($palabra != "") {
                        echo '<span class="label label-warning" onclick="eliminarFiltro(\'palabra\',\'input\');" style="color:#fff; cursor:pointer;">' . $palabra . ' <i class="fa fa-times" aria-hidden="true"></i></span> ';
                    }
                    if ($distancia != "") {
                        echo '<span class="label label-warning" onclick="eliminarFiltro(\'distancia\',\'range\');" style="color:#fff; cursor:pointer;">' . $distancia . 'km  <i class="fa fa-times" aria-hidden="true"></i></span> ';
                    }

                    ////////////////

                    if ($realiza_domi != "") {
                        echo '<span class="label label-warning" onclick="eliminarFiltro(\'realiza_domi\',\'checkbox\');" style="color:#fff; cursor:pointer;">a Domicilio  <i class="fa fa-times" aria-hidden="true"></i></span> ';
                    }
                    if ($realiza_esta != "") {
                        echo '<span class="label label-warning" onclick="eliminarFiltro(\'realiza_esta\',\'checkbox\');" style="color:#fff; cursor:pointer;">Establecimiento <i class="fa fa-times" aria-hidden="true"></i></span> ';
                    }
                    if ($realiza_ofic != "") {
                        echo '<span class="label label-warning" onclick="eliminarFiltro(\'realiza_domi\',\'checkbox\');" style="color:#fff; cursor:pointer;">Oficina/ Local  <i class="fa fa-times" aria-hidden="true"></i></span> ';
                    }
                    if ($realiza_onli != "") {
                        echo '<span class="label label-warning" onclick="eliminarFiltro(\'realiza_domi\',\'checkbox\');" style="color:#fff; cursor:pointer;">On-Line/Freelance  <i class="fa fa-times" aria-hidden="true"></i></span> ';
                    }

                    ////////////////

                    if ($facebook_amigos != "") {
                        echo '<span class="label label-warning" onclick="eliminarFiltro(\'facebook_amigos\',\'checkbox\');" style="color:#fff; cursor:pointer;">Amigos en común <i class="fa fa-times" aria-hidden="true"></i></span> ';
                    }
                    if ($matricula != "") {
                        echo '<span class="label label-warning" onclick="eliminarFiltro(\'matricula\',\'checkbox\');" style="color:#fff; cursor:pointer;">Matriculados  <i class="fa fa-times" aria-hidden="true"></i></span> ';
                    }
                    if ($seguro != "") {
                        echo '<span class="label label-warning" onclick="eliminarFiltro(\'seguro\',\'checkbox\');" style="color:#fff; cursor:pointer;">Asegurados (ART)  <i class="fa fa-times" aria-hidden="true"></i></span> ';
                    }
                    if ($garantia != "") {
                        echo '<span class="label label-warning" onclick="eliminarFiltro(\'garantia\',\'checkbox\');" style="color:#fff; cursor:pointer;">Garantia <i class="fa fa-times" aria-hidden="true"></i></span> ';
                    }

                    ////////////////

                    if ($horario_man != "") {
                        echo '<span class="label label-warning" onclick="eliminarFiltro(\'horario_man\',\'checkbox\');" style="color:#fff; cursor:pointer;">Mañana <i class="fa fa-times" aria-hidden="true"></i></span> ';
                    }
                    if ($horario_tar != "") {
                        echo '<span class="label label-warning" onclick="eliminarFiltro(\'horario_tar\',\'checkbox\');" style="color:#fff; cursor:pointer;">Tarde  <i class="fa fa-times" aria-hidden="true"></i></span> ';
                    }
                    if ($horario_noc != "") {
                        echo '<span class="label label-warning" onclick="eliminarFiltro(\'horario_noc\',\'checkbox\');" style="color:#fff; cursor:pointer;">Noche  <i class="fa fa-times" aria-hidden="true"></i></span> ';
                    }
                    if ($horario_24 != "") {
                        echo '<span class="label label-warning" onclick="eliminarFiltro(\'horario_24\',\'checkbox\');" style="color:#fff; cursor:pointer;">Urgencia 24hs <i class="fa fa-times" aria-hidden="true"></i></span> ';
                    }

                    ////////////////

                    if ($dia_lun != "") {
                        echo '<span class="label label-warning" onclick="eliminarFiltro(\'dia_lun\',\'checkbox\');" style="color:#fff; cursor:pointer;">Lunes <i class="fa fa-times" aria-hidden="true"></i></span> ';
                    }
                    if ($dia_mar != "") {
                        echo '<span class="label label-warning" onclick="eliminarFiltro(\'dia_mar\',\'checkbox\');" style="color:#fff; cursor:pointer;">Martes <i class="fa fa-times" aria-hidden="true"></i></span> ';
                    }
                    if ($dia_mie != "") {
                        echo '<span class="label label-warning" onclick="eliminarFiltro(\'dia_mie\',\'checkbox\');" style="color:#fff; cursor:pointer;">Miércoles <i class="fa fa-times" aria-hidden="true"></i></span> ';
                    }
                    if ($dia_jue != "") {
                        echo '<span class="label label-warning" onclick="eliminarFiltro(\'dia_jue\',\'checkbox\');" style="color:#fff; cursor:pointer;">Jueves <i class="fa fa-times" aria-hidden="true"></i></span> ';
                    }
                    if ($dia_vie != "") {
                        echo '<span class="label label-warning" onclick="eliminarFiltro(\'dia_vie\',\'checkbox\');" style="color:#fff; cursor:pointer;">Viernes  <i class="fa fa-times" aria-hidden="true"></i></span> ';
                    }
                    if ($dia_sab != "") {
                        echo '<span class="label label-warning" onclick="eliminarFiltro(\'dia_sab\',\'checkbox\');" style="color:#fff; cursor:pointer;">Sabado <i class="fa fa-times" aria-hidden="true"></i></span> ';
                    }
                    if ($dia_dom != "") {
                        echo '<span class="label label-warning" onclick="eliminarFiltro(\'dia_dom\',\'checkbox\');" style="color:#fff; cursor:pointer;">Domingo <i class="fa fa-times" aria-hidden="true"></i></span> ';
                    }
                    ?>


                </div>
                <div style="width:100%; color:#666;">
                    <span>Categorías:</span>
                    <?php
                    if ($categorias_total == "") {
                        echo '<span class="label label-default" style="color:#fff; cursor:pointer;">Todas</span> ';
                    } else {
                        $categorias_total = str_replace("**", ",", $categorias_total);
                        $categorias_total = str_replace("*", "", $categorias_total);

                        $sql = "SELECT cat_id,cat_titulo FROM ic_categoria WHERE cat_id IN (" . $categorias_total . "); ";
                        $result_cat = $db->Execute($sql);

                        $data = array();
                        while (!$result_cat->EOF) {
                            list($id, $titulo) = $result_cat->fields;

                            echo '<span class="label label-default" onclick="eliminarCategoria(\'' . $id . '\');" style="color:#fff; cursor:pointer;">' . $titulo . ' <i class="fa fa-times" aria-hidden="true"></i></span> ';

                            $result_cat->MoveNext();
                        }
                    }
                    ?>
                </div>
                <div style="width:100%; margin:0 0 10px 0;">
                    <div class="row">

                        <div class="row" style="padding:0 0 0 0;">
                            <div class="col-sm-8 col-xs-12 text-right">
                            </div>
                            <div class="col-sm-2 col-xs-12 text-right">       	 	
                                <select id="orden_list" name="orden_list" style="border-radius:0px;" class="form-control" onchange="enviarOrden(this.value);">
                                    <option value='rel' <?= (($orden == "rel") ? "selected" : "") ?>>Ordenar por Relevancia</option>
                                    <option value='fec' <?= (($orden == "fec") ? "selected" : "") ?>>Ordenar por Fecha</option>
                                </select>
                            </div>
                            <div class="col-sm-2 col-xs-12 text-right">
                                <?php 
                                $htmlPag = '<select id="pag_lis" name="pag_lis" style="border-radius:0px;" class="form-control" onchange="enviarPag(this.value);">'
                                            . '<option value="">Página</value>';
                                 
                                for ($i = 1; $i <= ceil($count / $art); $i++) {
                                    $pagina = "";

                                    if ($pag != "") {
                                        if ($pag == $i) {
                                            $pagina = "selected='selected'";
                                        }
                                    }
                                    //Paginas
                                    $htmlPag .= "<option value='" . $i . "' " . $pagina . ">Pag. " . $i . " </option>";
                                }
                                $htmlPag .= '</select>';
                                echo $htmlPag;
                                ?>
                            </div>
                        </div>
                        <?php
                        if ($result->EOF) {
                            ?>
                            <div class="row" style="padding:0 0 15px 0;">
                                <div class="col-sm-12 col-xs-12 text-center">
                                    <br><br><br>
                                    <h1><i class="fa fa-smile-o" aria-hidden="true"></i> Uuupppsss!!!...</h1>
                                    <br>
                                    <h4>
                                        No hay resultados de servicios publicados. 
                                        <br><br>
                                        Te recomendamos ajustar los filtros de busqueda o verificá que la categoría este correcta.
                                    </h4>
                                </div>
                            </div>
                            <?php
                        } else {
                            while (!$result->EOF) {
                                list($id_directorio, $nombre, $categoria, $descripcion, $direccion, $ciudad, $estado, $zip, $telefono, $web, $info_adicional, $email, $sponsor, $logo, $latitud,
                                        $longitud, $orden, $id_usuario, $activo, $destacado, $img1, $img2, $img3, $video, $fecha, $visualizaciones, $horario_man, $horario_tar, $horario_noc,
                                        $dia_lun, $dia_mar, $dia_mie, $dia_jue, $dia_vie, $dia_sab, $dia_dom, $urgencia, $realiza_domi, $realiza_esta, $realiza_ofic, $realiza_onli, $certificado_estudio,
                                        $matricula, $garantia, $seguro, $horario_24) = select_format($result->fields);
                                        echo "<script>console.log('$fecha');</script>";

                                $fecha = explode(" ", $fecha);
                                $fecha = explode("-", $fecha[0]);
                                if ($fecha[0] != 0000 ){
                                    $fecha = formato_mes($fecha[1]) . ", " . $fecha[0];
                                } else {
                                    $fecha = NULL;
                                }

                                $distanciakm = "";
                                $distanciakmfinal = "";

                                ///////////

                                if (isset($_SESSION["longitud"]) && isset($_SESSION["latitud"]) && $_SESSION["longitud"] != "" && $_SESSION["latitud"] != "") {
                                    if ($latitud != "" && $longitud != "") {
                                        $distanciakmfinal = round(calcular_distancia($_SESSION["latitud"], $_SESSION["longitud"], $latitud, $longitud, "K"), 1);
                                        $distanciakm = $distanciakmfinal . ' km de tí <i class="fa fa-map-marker fa-fw" aria-hidden="true"></i> <br />';
                                    }
                                }


                                if ($distancia < $basekm) {
                                    if ($distanciakmfinal > $distancia) {
                                        break;
                                    }
                                }

                                ///////////

                                $style_des = "";
                                if ($destacado != "" && $destacado != "0") {
                                    if ($destacado == "1") {
                                        $style_des = "background:#EEEEEE; border-bottom:5px solid #7C4793;";
                                    }
                                    if ($destacado == "2") {
                                        $style_des = "background:#EEEEEE; border-bottom:5px solid #7C4793;";
                                    }
                                    if ($destacado == "3") {
                                        $style_des = "background:#EEEEEE; border-bottom:5px solid #7C4793;";
                                    }
                                } else {
                                    $style_des = "background:#fff; border:1px solid #ccc;";
                                }

                                ///////////

                                $sql = "SELECT count(*) FROM ic_cotizaciones WHERE id_directorio='" . $id_directorio . "'";
                                $result_sol = $db->Execute($sql);
                                list($total_sol) = select_format($result_sol->fields);

                                ///////////

                                $sql = "SELECT count(*) FROM ic_favoritos WHERE id_directorio='" . $id_directorio . "'";
                                $result_fav = $db->Execute($sql);
                                list($total_fav) = select_format($result_fav->fields);

                                ///////////

                                $sql = "SELECT CONCAT(us_nombre,' ',us_last_name) FROM ic_usuarios WHERE us_id='" . $id_usuario . "'";
                                $result_usu = $db->Execute($sql);
                                list($usuario) = select_format($result_usu->fields);

                                ///////////

                                $sql = "SELECT AVG(calificacion) FROM ic_reputacion a, ic_cotizaciones b WHERE id_coti=id_cotizacion AND b.id_directorio='" . $id_directorio . "'";
                                $result_cal = $db->Execute($sql);
                                list($cal) = select_format($result_cal->fields);
                                $cal = ceil($cal);


                                ///////////////

                                $amigos = '<span title="Requiere login con Facebook">Amigos en común <i class="fa fa-facebook" aria-hidden="true"></i></span><br>';
                                $colortexto1 = "#7C4793";
                                $colortexto2 = "#333";

                                if (isset($_SESSION["us_codigo_ref"])) {

                                    $sql = "SELECT COUNT(b.id_amdir) FROM ic_amigos_fb a, ic_amigo_directorio b 
				        WHERE a.id_amigo=b.id_amigo AND a.id_usuario='" . $_SESSION['sess_usu_id'] . "' AND b.id_directorio='" . $id_directorio . "'";
                                    $result_amigos = $db->Execute($sql);
                                    list($total_amigos) = select_format($result_amigos->fields);

                                    if ($total_amigos > 0) {
                                        $colortexto1 = "#0036D9";
                                        $colortexto2 = "#0036D9";
                                    }
                                    $amigos = '<span style="color:' . $colortexto2 . '">' . $total_amigos . ' Amigos en común <i class="fa fa-facebook fa-fw" aria-hidden="true"></i></span><br>';
                                }
                                ?>

                                <div class="col-md-4 col-xs-12 publicacionDiv" title="<?= $nombre ?>">
                                    <div style="<?= $style_des ?>" class="elementList publicacion" onclick="javascript:window.location.href = '<?= "servicio/" . organizar_nombre($nombre) ?>';">
                                        <h3 class="publicacionTitulo" style="color:<?= $colortexto1 ?>;">
                                            <?= $nombre; ?>
                                        </h3>
                                        <p class="publicacionCiudad">
                                            <?= $ciudad ?>
                                        </p>
                                        <p>
                                            <?= $direccion . " - " . $zip ?>
                                        </p>
                                        <p class="publicacionHorario">
                                            <?= (($horario_24 == "1") ? "Servicio 24h" : "") ?> (Horarios <?= (($horario_man != "1" && $horario_tar != "1" && $horario_noc != "1") ? " - No Definidos -" : "") ?> <?= (($horario_man == "1") ? "Mañana" : "") ?> <?= (($horario_tar == "1") ? "Tarde" : "") ?> <?= (($horario_noc == "1") ? "Noche" : "") ?>)
                                        </p>
                                        <p>
                                            Servicio <?= (($realiza_domi != "1" && $realiza_esta != "1" && $realiza_ofic != "1" && $realiza_onli != "1") ? " - No Definido -" : "") ?> <?= (($realiza_domi == "1") ? "a Domicilio " : "") ?> <?= (($realiza_esta == "1") ? "en Establecimiento/Instituto" : "") ?> <?= (($realiza_ofic == "1") ? "en Oficina/ Local" : "") ?> <?= (($realiza_onli == "1") ? "On-Line/Freelance" : "") ?>
                                        </p>
                                        <div class="publicacionFooter">
                                            <div class="publicacionImg" style="background-image: url('<?= (($logo == "") ? "images/publication.jpg" : $logo) ?>');"></div>
                                            <div class="text-right publicacionFacebook">
                                                <!--<?= $total_sol ?> Solicitudes <i class="fa fa-envelope-open-o fa-fw" aria-hidden="true"></i>
                                                <br />
                                                <?= $total_fav ?> <i class="fa fa-heart fa-fw" aria-hidden="true"></i>
                                                <br />-->
                                                <?php
                                                ///////////////

                                                if ($distanciakm != "") {
                                                    echo $distanciakm;
                                                }

                                                echo $amigos;

                                                ///////////////
                                                ?>                                     
                                                <?php if ($cal == 1 || $cal == 2 || $cal == 3 || $cal == 4 || $cal == 5) { ?><i class="fa fa-star" aria-hidden="true"></i><?php } else { ?><i class="fa fa-star-o" aria-hidden="true"></i><?php } ?>
                                                <?php if ($cal == 2 || $cal == 3 || $cal == 4 || $cal == 5) { ?><i class="fa fa-star" aria-hidden="true"></i><?php } else { ?><i class="fa fa-star-o" aria-hidden="true"></i><?php } ?>
                                                <?php if ($cal == 3 || $cal == 4 || $cal == 5) { ?><i class="fa fa-star" aria-hidden="true"></i><?php } else { ?><i class="fa fa-star-o" aria-hidden="true"></i><?php } ?>
                                                <?php if ($cal == 4 || $cal == 5) { ?><i class="fa fa-star" aria-hidden="true"></i><?php } else { ?><i class="fa fa-star-o" aria-hidden="true"></i><?php } ?>
                                                <?php if ($cal == 5) { ?><i class="fa fa-star" aria-hidden="true"></i><?php } else { ?><i class="fa fa-star-o" aria-hidden="true"></i><?php } ?>
                                                <br /><br />
                                                <i><?= $usuario ?></i>
                                                <br />
                                                <?php
                                                    if (!is_null($fecha)){
                                                        echo "Publicado desde $fecha";
                                                    }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                $result->MoveNext();
                            }
                        }
                        ?>
                            <div class="col-sm-10 col-xs-12"></div>
                            <div class="col-sm-2 col-xs-12 text-right">
                                <?php echo $htmlPag;?>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php area("footer"); ?>
<?php include("footer.php"); ?>

<script>
    function enviarOrden(valor) {
        $("#orden").val(valor);

        document.filtros.submit();
    }

    ////////////////////

    function enviarPag(valor) {
        $("#pag").val(valor);

        document.filtros.submit();
    }

    ////////////////////

    function eliminarFiltro(valor, tipo) {
        if (tipo == "input") {
            $("#" + valor + "").val('');
        }
        if (tipo == "range") {
            $("#" + valor + "").val('50');
        }
        if (tipo == "checkbox") {
            $("#" + valor + "").prop('checked', false);
        }

        document.filtros.submit();
    }

    ////////////////////

    function eliminarCategoria(valor) {
        $("#categorias_eliminar").val(valor);

        if ($("#categoriastop" + valor).length) {
            $("#categoriastop" + valor + "").prop('checked', false);
        }

        document.filtros.submit();
    }

    ////////////////////

    $(document).ready(function () {
        $(".elementList").hover(function () {
            $(this).css('background-color', '#EDE2F1');
        }, function () {
            $(this).css('background-color', '#fff');
        });

        $('#distancia').change(function () {
            $("#km").html($(this).val() + "km");

            setTimeout(function () {
                document.filtros.submit();
            }, 1000);
        });

        $('input[type=checkbox]').change(function () {
            if (!$(this).is(':checked')) {
                if ($("#categoriastop" + $(this).val()).length) {
                    $("#categorias_eliminar").val($(this).val());
                }
            }
            setTimeout(function () {
                document.filtros.submit();
            }, 500);
        });

        $('#palabra').keypress(function (e) {
            if (e.which == 13) {
                setTimeout(function () {
                    document.filtros.submit();
                }, 1000);
            }
        });
    });
</script>
<style>
    .label{margin:4px 1px !important;}
</style>

</body>
</html>