<?php
	//Cabeceras del documento
	include("cabecera.php");
?>

<?php area("header"); ?>

<div id="profesional" style="margin:0 0 20px 0;">
    <div style="max-width:1150px; margin:0 auto; border-radius:10px; background:#fff; padding:30px 30px 25px 30px; box-sizing:border-box;">
    	<div class="row">
			<div class="col-md-9 col-xs-12" style="text-align:center;">            
				<form action="listado-servicios.html" method="get" id="serach_list" name="serach_list">
            <table width="100%" border="0" style="margin:0 0 0 0; padding:0;">
			<tr>
			<td><input type="text" placeholder="¿Qué Servicio Buscas?" style="width:100% !important; border-right:none; border-radius:5px 0 0 5px;  border:1px solid #ccc; padding:2px; margin:-6px 0 0 0; height:35px;" id="search_services" name="search_services" /></td><td width="30" nowrap valign="top" style="padding:16px 0 0 0;"><a href="#" class="buscarBtn" style="background:#FAA532; padding:9px 8px 10px 8px; margin:0 0 0 -13px; font-size:14px; color:#FFF; border-radius:0 5px 5px 0;"><i class="fa fa-search" aria-hidden="true"></i>
</a></td>
			</tr>
			</table>
			<input type="hidden" name="palabra" id="palabra_search" /><input type="hidden" name="categoria" id="categoria_search" />
			</form>
			</div>
		
			<div class="col-md-3 col-xs-12">            
				<input type="button" onclick="javascript:window.location.href='listado-servicios.html';" class="buscarBtn2" style="width:100%; background:#7C4793;" value="Buscar yo mismo" /> 
			</div> 
		</div>
    </div>
</div>



<div style="padding:0 0 100px 0; box-sizing:border-box; margin:0 auto; max-width:1150px;">
<?php 
	//Metodo para desplegar la informacion del contenido del portal
	desplegarModulos($mod, $identificador, $buscar); 
	
	if($mod=="contenido" && $identificador=="5"){
		echo '<div style="max-width:300px; margin:0 auto; height:5px; background:#FAA532; "></div>
        <div style="width:100%; text-align:center; margin:20px 0 80px 0; font-size:30px; color:#F9A532; font-weight:bold;">Contactanos!, hacete Premium.</div>
        ';
		
		include("contacto-premium.php");
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
    $sql="SELECT id_directorio, nombre, categoria, logo,ciudad,estado FROM `ic_directorio` WHERE activo=1 AND logo!='' ORDER BY RAND() LIMIT 0,10";
    $result=$db->Execute($sql);
    
    $datos = array();
    
    while(!$result->EOF){
        list($id_directorio, $nombre,$categoria,$logo,$ciudad,$estado)=select_format($result->fields);
        
    ?>
                        <li>
                            <div style="box-sizing:border-box; padding:10px;">
							<div class="itemBox">
								<a href="<?="servicio/".organizar_nombre($nombre)?>"><img src="<?=$logo?>" style="max-width:100%; max-height:100%;" /></a>
							</div>
							<div style="min-height:150px; margin:7px 0 0 0;">
								<a href="<?="servicio/".organizar_nombre($nombre)?>"><strong><?=$nombre?></strong></a>
								<br>
								<?=$ciudad?>
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