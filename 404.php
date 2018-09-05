<?php
	//Cabeceras del documento
	include("cabecera.php");
?>

<?php area("header"); ?>

<div id="profesional" style="margin:0 0 20px 0;">
    <div style="max-width:1150px; margin:0 auto; border-radius:10px; background:#fff; padding:30px 30px 25px 30px; box-sizing:border-box;">
    	<div class="row">
			<div class="col-md-6 col-xs-12" style="text-align:center;">            
				<form action="listado-servicios.html" method="post" id="serach_list" name="serach_list">
                <input type="text" placeholder="Buscá un profesional o un servicio..." style="width:100%; border-radius:5px 0 0 5px; padding:4px; margin:0 0 0 0 !important; height:40px;" id="search_services" name="search_services" /></td><!--<td width="30" nowrap valign="top"><a href="#" style="background:#FAA532; padding:9px 8px; margin:0 0 0 -10px; font-size:14px; color:#FFF; border-radius:0 5px 5px 0;"><i class="fa fa-search" aria-hidden="true"></i>
    </a></td>-->
                <input type="hidden" name="palabra" id="palabra_search" /><input type="hidden" name="categoria" id="categoria_search" />
                </form>
			</div>
		
			<div class="col-md-3 col-xs-12">            
				<input type="button" style="width:100%; background:#7C4793;" value="Buscar yo mismo" /> 
			</div>
			<div class="col-md-3 col-xs-12">            
				<input type="button" style="width:100%; background:#FAA532;" value="Busqueda Worklife" /> 
			</div>
		</div>
    </div>
</div>



<div class="container" style="padding:250px 0; background:url(images/404.jpg) center center;  text-align:center">
	<h1 style="color:#fff;text-shadow: 5px 5px 5px #000;">404 - Página no encontrada</h1>
	<br>
    <h1 style="color:#fff;text-shadow: 5px 5px 5px #000;">El contenido que esta buscando no esta registrado en el sistema.</h1>
</div>

<div style="background:#EEEEEE; padding:50px 0;">
    <div style="max-width:1150px; margin:0 auto;">
        <div style="width:300px; margin:0 auto; height:5px; background:#FAA532; "></div>
        <div style="width:100%; text-align:center; margin:20px 0 80px 0; font-size:30px; color:#F9A532; font-weight:bold;">Profesionales Worklife</div>
        
        <div style="width:100%; margin:50px 0;">
            <div class="slide_home">
                <div id="slider1">
                    <ul>
    <?php
    $sql="SELECT id_directorio, nombre, categoria, logo,ciudad,estado FROM `ic_directorio` WHERE activo=1 AND logo!='' ORDER BY RAND() LIMIT 0,10";
    $result=$db->Execute($sql);
    
    $datos = array();
    
    while(!$result->EOF){
        list($id_directorio, $nombre,$categoria,$logo,$ciudad,$estado)=select_format($result->fields);
        
    ?>
                        <li>
                            <div style="box-sizing:border-box; padding:10px;">
                            <div style="width:246px; height:246px; text-align:center; background:#fff;"><img src="<?=$logo?>" style="max-width:100%; max-height:100%;" /></div>
                            <br><br>
                            <?=$nombre?>
                            <br>
                            <?=$ciudad?> - <?=$estado?>
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