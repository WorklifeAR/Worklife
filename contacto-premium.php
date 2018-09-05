
<script language="javascript">
 
function enviar(campos){

	var camposObligatorios = campos.split(",");

	for(i=0;i<camposObligatorios.length;i++)
	{
		if(document.getElementById(camposObligatorios[i]).value==""){
			alert("Campo "+ document.getElementById(camposObligatorios[i]).title +" es requerido!");
			return;
		}
	}
	document.contactenos.submit();
}
</script>


<div style="width:100%; box-sizing:border-box; padding:0 20px;">	    
        <form action="enviar_premium.php?id=<?=session_id()?>" method="post" name="contactenos" id="contactenos">
        <div style="margin:10px 0; width:49%; float:left;">
            <input name="nombre_completo" type="text" class="form-control" id="nombre_completo" title="Nombre completo" placeholder="Nombre completo *"/>
        </div>               
        <div style="margin:10px 0; width:49%; float:right;">
            <input name="email" type="text" class="form-control" id="e_mail" title="Email" placeholder="Email *"/>
        </div>               
        <div style="margin:10px 0; width:49%; float:left;">
        <input name="phone" type="text" class="form-control" id="phone"  title="Telefono" placeholder="Teléfono"/>
        </div>               
        <div style="margin:10px 0; width:49%; float:right;">
        <input name="ciudad" type="text" class="form-control" id="ciudad" title="Ciudad" placeholder="Ciudad *"/>
        </div>                            
                
        <div style="margin:10px 0; width:100%; text-align:right;">
        <?php generateRandString(4); capchat(); ?>
        </div>
        <div style="margin:11px 0; width:100%; text-align:right;">
        <input type="button" onClick="enviar('nombre_completo,e_mail,ciudad');" name="Submit" value="Enviar" />
        </div>
        </form>
</div>

