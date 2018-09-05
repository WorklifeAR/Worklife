<?php 
session_start();

header('Content-type: text/javascript');

$path = "adodb/adodb.inc.php";
include ("../admin/var.php");
include ("../conexion.php");
include ("../admin/lib/general.php");

if(!isset($_SESSION['listado_ciudades'])){
		$sql="SELECT id,estado,ciudad FROM ic_ciudades_usa ORDER BY estado,ciudad";
		$result=$db->Execute($sql);
		
		while(!$result->EOF){
			list($id,$estado,$ciudad)=$result->fields;	
			
			$_SESSION['listado_ciudades'] .= '{ value: "'.$ciudad.'", label: "'.$ciudad.", ".$estado.'", category: "'.$estado.'" }';
		
			$result->MoveNext();
			
			if(!$result->EOF){
				$_SESSION['listado_ciudades'] .=  ",
				";	
			}
		}	
	}
?>
$(function() {
var data = [
<?php
	echo $_SESSION['listado_ciudades'];
?> 
];
	$( "#ciudad_text" ).catcomplete({
		delay: 0,
		minLength: 3,
		source: data,
		select: function( event, ui ) {
			$( "#ciudad_text" ).val( ui.item.label );
			$( "#ciudad" ).val( ui.item.value );
			return false;
		}
	});
});