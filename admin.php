<?php
	//Cabeceras del documento
	include("cabecera.php");
	require('admin/image-resize/class.image-resize.php');
	
	$activo_campo_idioma='';
	if(($c_lang=="0")||($c_lang=="")){
		$activo_campo_idioma=' disabled="disabled" ';
	}
	
	$variables_admin = variables_metodo("usu,pass,login_window,mod,salir,mensaje");

	$usu= 			$variables_admin[0];
	$pass= 			$variables_admin[1];
	$login_window= 		$variables_admin[2];
	$mod= 			$variables_admin[3];
	$salir= 		$variables_admin[4];
	$mensaje= 		$variables_admin[5];

	if($login_window=="admin"){
		$mensaje = inicio_usuario_admin($usu,$pass,$db);
	}	
?>
<?php
	//script del sitio
	include("footer.php");
?>
<script language="javascript" type="text/javascript" src="js/editor/tiny_mce.js"></script>
<script language="javascript" type="text/javascript" src="js/incluir_editor.js"></script>
<style>
table{
	margin: 0 auto;	
}
</style>
<body onLoad="">

<?php area("header" ); ?>

<div style="margin:20px 0 0 0; width:100%; border-top:10px solid #494949;"></div>

<div style="margin:0 auto 0 auto; width:100%; min-height:500px; float:left;  background:#FFF;  position:relative;">


<?php
if(isset($_SESSION['usuario'])){
	if($_SESSION['usuario']=="admin"){
		echo '<div style="background:#494949; padding:20px 15px; float:left; width:300px; position:relative; top:0; left:0; z-index:1000;"> <h3 style="font-size:20px">Modulos</h3> <br>';
		
		$sql="SELECT m_id,m_descripcion,m_imagen,m_categoria,m_id_categoria,m_texto FROM ic_modulos 
	      WHERE m_activo=1 AND(m_imagen IS NOT NULL AND m_imagen!='') ORDER BY m_id_categoria ASC";
		$result=$db->Execute($sql);
				
		while(!$result->EOF){
			list($m_id,$m_descripcion,$m_imagen,$m_categoria,$m_id_categoria,$m_texto)=select_format($result->fields);
			
			echo '<div style="padding:10px 15px; background:#fff; margin:10px 0;">
				<img src="images/admin/'.$m_imagen.'" width="20" height="20" border="0"/> <a href="admin.php?mod='.$m_id.'">'.($m_descripcion).'</a>
			</div>';
			
			$result->MoveNext();
		}
		
		echo '<div style="padding:10px 15px; background:#fff; margin:20px 0;">
				<img src="images/admin/salir.png" width="20" height="20" border="0"/> <a href="admin.php/salir/1">Cerrar Sesión</a>
			</div>';
			
		echo '</div>';
	}	
}
	
	//Determina si carga el formulario de logueo o si ya se inicio sesion
	if(isset($_SESSION['usuario'])){
		if($_SESSION['usuario']!="admin"){
			login();
		}else{
			echo '<div style="padding:30px 50px 50px 330px; width:100%; position:relative; top:0; left:0; z-index:500;">';
			include ("admin/inicio.php");
			echo '</div>';
		}
	}else{
		login();
	}
	
	//Carga un modulo de administracion solicitado
	if($mod!="" && $mod!="0"){
		echo '<div style="padding:30px 50px 50px 330px; width:100%; position:relative; top:0; left:0; z-index:500;">';
		include ("conexion.php");
		
		$sql="SELECT m_archivo FROM ic_modulos WHERE m_id='".$mod."'";
		$result=$db->Execute($sql);
		
		list($m_archivo)=select_format($result->fields);
		
		include ("admin/".$m_archivo.".php");
		echo '</div>';
	}	
/********************************************************************************/

function login()
{
?>
        <form action="admin.php" method="post">
          <table border="0" width="500" align="center">
            <tr>
              <td align="center"><h2>Acceso Panel de Adminsitración</h2></td>
            </tr>
            <tr>
              <td><div style="margin:20px 0; border-top:1px solid #7C4793;"></div></td>
            </tr>
            <tr>
              <td><input name="usu" type="text" class="form-control" id="usu" placeholder="Usuario"></td>
            </tr>
            <tr>
              <td><input name="pass" type="password" class="form-control" id="pass" placeholder="Password"></td>
            </tr>
            <input type="hidden" name="login_window" value="admin">
			<input type="hidden" name="id" value="<?=session_id()?>">
            <tr>
              <td align="right"><input type="submit" name="Submit" value="<?=_ENTRAR?>"></td>
            </tr>
          </table>
        </form>
      <?php
}
/************************************************************************************************************/
	if($mensaje=="true"){
		echo '<center><i><font color="#CC0000" size="2" face="Arial, Helvetica, sans-serif">'._MSG_NO_LOGIN.'</font></i></center><br>';
	}
?>
</div>

<div style="width:100%; float:left;">
<?php area("footer"); ?>

</div>
<script>
window.onload = function() {
	$("body").fadeIn();

}
</script>
</body>
</html>
<?php $db->close(); ?>