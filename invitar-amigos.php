
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
	document.invitacion.submit();
}
</script>


<div class="row">	    
    <div class="col-sm-12 col-xs-12 text-center" style="text-transform:uppercase; font-weight:700;">
    	<h1>INVITA A TUS AMIGOS</h1>
        <h4><?=((isset($_SESSION['sess_nombre']))?$_SESSION['sess_nombre']."! ":"")?>Muestrale a tus amigos las ventajas de ser parte de la comunidad de <strong>WORKLIFE</strong>, enviales una invitación.</h4>
		<br /><br />
    </div> 
    <div class="col-sm-12 col-xs-12 text-center">
    </div> 
    <div class="col-sm-12 col-xs-12">
        <form action="enviar_invitacion.php?id=<?=session_id()?>" method="post" name="invitacion" id="invitacion">
        <div style="margin:10px 0; width:49%; float:left;">
            <input name="emisor" type="text" class="form-control" id="emisor" title="Quien lo envía" placeholder="Quien lo envía *" readonly value="<?=$_SESSION['sess_nombre']." ".$_SESSION['sess_last_nombre']?>"/>
        </div>    
		<div style="margin:10px 0; width:49%; float:right;">
            <input name="asunto" type="text" class="form-control" id="asunto" title="Asunto" placeholder="Asunto *" readonly value="<?=$_SESSION['sess_nombre']?> te invita a Conocer WORKLife.com.ar "/>
        </div>  
        <div style="margin:10px 0; width:49%; float:left;">
            <input name="nombre_completo" type="text" class="form-control" id="nombre_completo" title="Nombre completo" placeholder="Nombre de tu amigo *"/>
        </div>               
        <div style="margin:10px 0; width:49%; float:right;">
            <input name="email" type="text" class="form-control" id="e_mail" title="Email" placeholder="Email de tu amigo *"/>
        </div>                 
        <div style="margin:10px 0; width:100%;">
        <textarea name="comentarios"  rows="4" id="comentarios" class="form-control" title="Mensaje" placeholder="Mensaje *">Tu Amigo <?=$_SESSION['sess_nombre']?> te ha invitado a unirte a WORKLife. ¡Registrate y Publica! Lo que mejor Sabes Hacer. </textarea></div>               
        <div style="margin:10px 0; width:100%; text-align:right;">
        <?php generateRandString(4); capchat(); ?>
        </div>
        <div style="margin:11px 0; width:100%; text-align:right;">
        <input type="button" onClick="enviar('nombre_completo,e_mail,comentarios');" name="Submit" value="Enviar" />
        </div>
        </form>
    </div>
</div>

