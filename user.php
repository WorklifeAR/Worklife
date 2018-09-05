<?php
//Cabeceras del documento
include("cabecera.php");

$_SESSION['url_visto'] = "";
?>

<?php area("header"); ?>

<style>
        .boxSearch{max-width:1150px; margin:0 auto; border-radius:10px; background:#fff; padding:30px 30px 25px 30px; box-sizing:border-box;}
        
        @media screen and (max-width: 992px) {
            .cajaBusquedas { max-width: 90% }
            .boxSearch{max-width: 90%}
            
        }
</style>
<div id="profesional" class="buscarUser" style="margin:0 0 20px 0;">
    <div class="boxSearch">
        <div class="row">
            <div class="col-md-9 col-xs-12" style="text-align:center;">            
                <form action="listado-servicios.html" method="get" id="serach_list" name="serach_list">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="¿Qué Servicio Buscás?" id="search_services" name="search_services" style="border-radius:5px 0 0 5px;border-right:none;  padding:2px 10px; height:33px; border:1px solid #ccc;" />
                        <span class="input-group-btn">
                            <button class="btn btn-secondary buscarBtn" type="button" style="background:#FAA532;border-radius:0 5px 5px 0;">
                                <i class="fa fa-search" aria-hidden="true"></i>
                            </button>
                        </span>
                    </div>                    
                    <input type="hidden" name="palabra" id="palabra_search" />
                    <input type="hidden" name="categoria" id="categoria_search" />
                </form>
            </div>

            <div class="col-md-3 col-xs-12">            
                <input type="button" onclick="javascript:window.location.href = 'listado-servicios.html';" class="buscarBtn2" style="width:100%; background:#7C4793;" value="Buscar yo mismo" /> 
            </div>
        </div>
    </div>
</div>



<div class="container" style="padding:0 15px 100px 15px;">

    <?php
	$seguimiento = "";
	
    if (isset($_SESSION['usuario'])) {
        if ($op_usu == "favoritos") {
            include ("favoritos.php");
        } elseif ($op_usu == "publicaciones") {
            include ("publicaciones.php");
        } elseif ($op_usu == "cotizaciones") {
            include ("cotizaciones.php");
        } elseif ($op_usu == "reputacion") {
            include ("reputacion.php");
        } elseif ($op_usu == "denuncias") {
            include ("denunciar_calificacion.php");
        } elseif ($op_usu == "mensajes") {
            include ("mensajes.php");
        } elseif ($op_usu == "calificar") {
            include ("calificaciones.php");
        } else {
            include ("opciones_usuarios.php");
        }
    } else {
		if ($op_usu == "publicaciones") {
            $seguimiento = "publicaciones";
        }
		if ($op_usu == "calificar") {
            $seguimiento = "calificar";
        }
        if ($op_usu == "favoritos" || $op_usu == "publicaciones" || $op_usu == "mensajes" || $op_usu == "calificar") {
            $op_usu = "";
        }
        include ("opciones_usuarios.php");
    }
    ?>

</div>

<div style="background:#EEEEEE; padding:50px 0;">
    <div style="max-width:1150px; margin:0 auto;">
        <div style="width:300px; margin:0 auto; height:5px; background:#FAA532; "></div>
        <div style="width:100%; text-align:center; margin:20px 0 80px 0; font-size:30px; color:#F9A532; font-weight:bold;">Profesionales WORKLife</div>

        <div style="width:100%; margin:50px 0;">
            <div class="slide_home">
                <div id="slider1">
                    <ul>
                        <?php
                        $sql = "SELECT id_directorio, nombre, categoria, logo,ciudad,estado FROM `ic_directorio` WHERE activo=1 AND logo!='' ORDER BY RAND() LIMIT 0,10";
                        $result = $db->Execute($sql);

                        $datos = array();

                        while (!$result->EOF) {
                            list($id_directorio, $nombre, $categoria, $logo, $ciudad, $estado) = select_format($result->fields);
                            ?>
                            <li>
                                <div style="box-sizing:border-box; padding:10px;">
                                    <div class="itemBox">
                                        <a href="<?= "servicio/" . organizar_nombre($nombre) ?>"><img src="<?= $logo ?>" style="max-width:100%; max-height:100%;" /></a>
                                    </div>
                                    <div style="min-height:150px; margin:7px 0 0 0;">
                                        <a href="<?= "servicio/" . organizar_nombre($nombre) ?>"><strong><?= $nombre ?></strong></a>
                                        <br>
                                        <?= $ciudad ?>
                                    </div>
                                </div>
                            </li>
                            <?php
                            $result->MoveNext();
                        }
                        ?>

                    </ul>
                </div>
            </div>
        </div>        
    </div>
</div>

<?php area("footer"); ?>
<?php include("footer.php"); ?>
</body>
</html>