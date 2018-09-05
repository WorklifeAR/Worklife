<?php
	session_permiso("120");

	echo "<center class='titulo'>Generando Archivo...<br /><br /><a href='admin.php?mod=120'>Volver a generar archivo</a></center>";
?>

<script language="javascript" type="text/javascript">
	window.open("admin/export_db.php", 'export', 'toolbar=no,scrollbars=yes,location=0,statusbar=0,menubar=yes,resizable=0,width=500,height=300');
</script>