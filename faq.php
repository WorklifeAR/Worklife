<script type="text/javascript">

$( function() {
	$( "#accordion" ).accordion({
		collapsible: true,
		heightStyle: "content"
	});
} );

</script>


<h1>Servicio de Ayuda</h1>
<h4>Preguntas y respuestas frecuentes</h4>
<br><br>
<div id="accordion">
	
<?php
	$path = "adodb/adodb.inc.php";
	include ("admin/var.php");
	include ("conexion.php");
	
	$sql="SELECT faq_id,faq_titulo,faq_conten FROM ic_faq WHERE 1=1 ";
	$result=$db->Execute($sql);
	$result_titulos=$db->Execute($sql);

	//------------------------------------------------------------------------
	
	while(!$result->EOF){
		list($faq_id,$faq_titulo,$faq_conten)=select_format($result->fields);
?>

    <h3><?=$faq_titulo?></h3>
	<div><?=$faq_conten?></div>
<?php
		$result->MoveNext();
	}
?>
</div>
<style>
	h3 { background:#f4f4f4 !important; font-weight:bold !important; color:#000 !important; font-size:20px !important; border:none !important; padding:7px 15px !important; margin: 10px 0 0 0 !important; }
	.ui-accordion-content{  padding:7px 15px !important; border:none !important; }
</style>