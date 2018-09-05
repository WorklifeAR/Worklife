<?php
//Cabeceras del documento
include("cabecera.php");

$_SESSION['url_visto'] = "";
?>


<?php area("header"); ?>

<style>
    
    .logo{
        width: 100%;
    }
    .accountPremium{
        background:url(images/fondo.jpg) 0 24% repeat-x; 
        padding:0px 0; 
        width:100%;
    }
    
    @media screen and (max-width: 600px) {
        .cajaBusquedas { margin: 20px auto 0 auto }
    }
    @media screen and (max-width: 992px) {
        .logo{
            width: auto;
        }
        .tar {
            text-align: center;
        }
        .cajaBusquedas { max-width: 90% }
        .contentFull{ margin: 100px auto 0 auto }
        .accountPremium{
            background: url(images/fondo.jpg) 0 32% repeat-x
        }
    }

</style>
    
<div class="banner">
    <div id="bannerscollection_zoominout_opportune">
        <div class="myloader"></div>
        <!-- CONTENT -->
        <ul class="bannerscollection_zoominout_list">
            <?php banner("BANNER-HOME", $db); ?>            	
        </ul>           
    </div>
</div>



<div class="sombra cajaBusquedas">
    <div class="row">
        <div class="col-md-12 col-xs-12" style="text-align:center;">            
            <form action="listado-servicios.html" method="get" id="serach_list" name="serach_list">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="¿Qué Servicio Buscás?" id="search_services" name="search_services" style="border-radius:5px 0 0 5px;border-right:none; padding:2px 10px; height:33px; border:1px solid #ccc;" />
                    <span class="input-group-btn">
                        <button class="btn btn-secondary buscarBtn" type="button" onclick="enviarBusqueda();" style="background:#FAA532;border-radius:0 5px 5px 0;">
                            <i class="fa fa-search" aria-hidden="true"></i>
                        </button>
                    </span>
                </div>
                <input type="hidden" name="palabra" id="palabra_search" />
                <input type="hidden" name="categoria" id="categoria_search" />
            </form>
        </div>
    </div>

    <div class="row" style="margin-top:20px;">
        <div class="col-md-6 col-xs-12">
            <input type="button" class="buscarBtn2" onclick="javascript:window.location.href = 'listado-servicios.html';" style="width:100%; background:#7C4793;" value="Buscar yo mismo" /> 
        </div>
        <div class="col-md-6 col-xs-12">
            <input type="button" class="buscarBtn" onclick="javascript:window.location.href = 'contenido/cuentas-premium.html';" style="width:100%; background:#FAA532;" value="Cuenta Premium" /> 
        </div>
    </div>
</div>

<div class="contentFull">

    <div style="margin:100px 0 70px 0; font-size:37px; text-align:center; font-weight:bold; color:#545454">
        Encuentra lo que Buscas ahora con Mas de 600 Categorias disponibles
    </div>

    <div class="row">
        <?php
        $sql = "SELECT cat_id, cat_titulo, img_categoria, cat_conten FROM `ic_categoria` WHERE id_tipo=7 AND cat_home=1  ORDER BY orden DESC LIMIT 0,6";
        $result = $db->Execute($sql);

        $datos = array();

        while (!$result->EOF) {
            list($cat_id, $cat_titulo, $img_categoria, $cat_content) = select_format($result->fields);
            ?>
            <div class="col-md-4 col-xs-12 imgCentralDiv">
                <a href="<?= "directorio/" . organizar_nombre($cat_titulo) ?>"><img src="<?= $img_categoria ?>" class="imgCentral" title="<?= $cat_titulo ?>" /></a>
                <div style="background:#333333; padding:7px 0; width:100%; text-align:center; margin:0 0 20px 0;">
                    <a class="imgCentralText" href="<?= "directorio/" . organizar_nombre($cat_titulo) ?>"><?= $cat_content ?></a>
                </div>
            </div>
            <?php
            $result->MoveNext();
        }
        ?>
    </div>
</div>

<div class="contentFull">
    <div style="width:300px; margin:0 auto; height:5px; background:#FAA532; "></div>
    <div style="width:100%; text-align:center; margin:20px 0 80px 0; font-size:37px; color:#F9A532; font-weight:bold;">Descubrí WORKLife</div>
    <div style="margin:0 auto; text-align:center;"><iframe src="https://www.youtube.com/embed/Hfwb9b1t1r8?rel=0&loop=1" class="videoHome" frameborder="0" allowfullscreen></iframe></div>
</div>

<div class="accountPremium">
    <div class="contentFull">
        <div style="width:300px; margin:0 auto; height:5px; background:#FAA532; "></div>
        <div style="width:100%; text-align:center; margin:20px 0 80px 0; font-size:30px; color:#F9A532; font-weight:bold;">Cuenta Premium WORKLife</div>
        <div class="row">
            <div class="col-md-1 col-xs-12"></div>

            <div class="col-md-3 col-xs-12">    
                <img src="images/about.png" style="width:100%;" />
            </div>

            <div class="col-md-1 col-xs-12"></div>

            <div class="col-md-6 col-xs-12" style="font-size:22px;">  
<?php contenidoEstatico("contenido", "1", $db); ?>
            </div>

            <div class="col-md-1 col-xs-12"></div>
        </div>
    </div>
</div>

<div id="profesional" style="margin:40px 0;">
    <div class="container">
        <div class="row">
            <div class="col-md-9 col-xs-12 text-trabajo">
                ¡GANA DINERO! ofreciendo lo que mejor sabes Hacer!
            </div>
            <div class="col-md-3 col-xs-12 boton-trabajo">
                <input onclick="javascript:window.location.href = 'usuarios.html';" class="buscarBtn" type="button" style="width:100%; background:#FAA532; padding:15px 0;" value="Registrate ahora" /> 
            </div>
        </div>
    </div>
</div>

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
                        <div class="carouselItem">
                            <a href="<?= "servicio/" . organizar_nombre($nombre) ?>">
                                <div class="carouselImg" style="background: url('<?= $logo ?>');" /></div>
                                <div class="carouselText">
                                    <a href="<?= "servicio/" . organizar_nombre($nombre) ?>"><h4><?= ucfirst(strtolower($nombre)) ?></h4></a>
                                    <h5><?= $ciudad ?></h5>
                                </div>
                            </a>
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


<?php 
area("footer"); 
//script del sitio
include("footer.php");
?>
</body>
</html>