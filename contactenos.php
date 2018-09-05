
<script language="javascript">
 
function enviar(campos){

	var camposObligatorios = campos.split(",");

	for(i=0;i<camposObligatorios.length;i++)
	{
		if(document.getElementById(camposObligatorios[i]).value==""){
			alert("Field "+ document.getElementById(camposObligatorios[i]).title +" <?=_OBLIGATORIO?>");
			return;
		}
	}
	document.contactenos.submit();
}
</script>


<div class="row">	    
    <div class="col-sm-12 col-xs-12 text-center" style="text-transform:uppercase; font-weight:700;">
    	<h1>CONTÁCTENOS</h1>
        <h4>Tenés dudas, comentarios, ¡escribinos!</h4>
        <br />
        <a href="contacto@worklife.com.ar">contacto@worklife.com.ar</a>
		<br /><br />
    </div> 
    <div class="col-sm-12 col-xs-12 text-center">
    </div> 
    <div class="col-sm-12 col-xs-12">
        <form action="enviar_formulario.php?id=<?=session_id()?>" method="post" name="contactenos" id="contactenos">
        <div style="margin:10px 0; width:49%; float:left;">
            <input name="nombre_completo" type="text" class="form-control" id="nombre_completo" title="Nombre completo" placeholder="Nombre completo *"/>
        </div>               
        <div style="margin:10px 0; width:49%; float:right;">
            <input name="email" type="text" class="form-control" id="e_mail" title="Email" placeholder="Email *"/>
        </div>               
        <div style="margin:10px 0; width:49%; float:left;">
        <input name="phone" type="text" class="form-control" id="phone"  title="Phone" placeholder="Teléfono"/>
        </div>               
        <div style="margin:10px 0; width:49%; float:right;">
        <input name="ciudad" type="text" class="form-control" id="ciudad" title="City" placeholder="Ciudad *"/>
        </div>                            
        <div style="margin:10px 0; width:100%;">
        <textarea name="comentarios"  rows="2" id="comentarios" class="form-control" title="Mensaje" placeholder="Mensaje *"></textarea></div>               
        <div style="margin:10px 0; width:100%; text-align:right;">
        <?php generateRandString(4); capchat(); ?>
        </div>
        <div style="margin:11px 0; width:100%; text-align:right;">
        <input type="button" onClick="enviar('nombre_completo,e_mail,ciudad,comentarios');" name="Submit" value="Enviar" />
        </div>
        </form>
    </div>
</div>

