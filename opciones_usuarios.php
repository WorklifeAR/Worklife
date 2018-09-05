<div align="center" id="Contenido_archivo">
    <?php
    if ((!isset($_SESSION['usuario'])) && ($op_usu == "")) {
		
		if($seguimiento==""){ 
			echo "<br /><h1>Registraté o Inicia sesión</h1> <h2>Empezá ya en el mundo de profesionales WORKLife</h2>";
		}elseif($seguimiento=="publicaciones"){
			echo "<br /><h1>Registrate para Publicar tu Servicio</h1>";
		}elseif($seguimiento=="calificar"){
			echo "<br /><h1>Registrate para calificar el Servicio</h1>";
		}
		
        ?>
        
        <br />
        <div style="margin:0 auto; max-width:600px;">
            <div class="row">
                <div class="col-sm-6 col-xs-12">
                    <div class="buscarBtn3" style="text-align:center; color:#fff; font-weight:700; background:#06C; padding:15px 0;cursor:pointer" onclick="fblogin()">
                        <i class="fa fa-facebook" aria-hidden="true"></i> Iniciar sesion con Facebook
                    </div>
                </div>

                <div class="col-sm-6 col-xs-12">
                    <div class="buscarBtn3" class="google" style="text-align:center; color:#fff; font-weight:700; background:#C30; padding:15px 0;cursor:pointer" onclick="startApp()">
                        <i class="fa fa-google-plus" aria-hidden="true"></i> Iniciar sesion con Google +
                    </div>
                </div>
                <div class="col-sm-12 col-xs-12">
                    <br />
                </div>
                <form action="" method="post" name="login" id="login" >
                    <div class="col-sm-12 col-xs-12">
                        <input type="text" name="us_login" id="us_login" class="form-control" placeholder="Email" />
                    </div>
                    <div class="col-sm-12 col-xs-12">
                        <input type="password" name="us_pass" id="us_pass" class="form-control" placeholder="Password" />
                    </div>
                    <div class="col-sm-12 col-xs-12">
                        <label>
                            <input type="checkbox" id="terminos" checked value="S" /> 
                            Acepto <a href="contenido/terminos-y-condiciones.html">Terminos y condiciones</a> y las <a href="contenido/politicas-de-privacidad.html">Políticas de privacidad</a>
                    </div>		
                    <div class="col-sm-12 col-xs-12">
                        <input type="hidden" name="funcion" value="login_usuario" />
                        <input type="button" name="Submit" class="buscarBtn2" value="ACCEDER" id="acceso" style="width:100%; margin:20px auto;"/>
                    </div>
                </form>

                <div class="col-sm-12 col-xs-12 text-center">
                    <a href="recordar-clave.html">[ Recordar mi password ]</a>
                </div>
            </div>
        </div>
        <br />
        <script>

            $(document).ready(function () {
                $("#acceso").click(function () {
                    var email = $("#us_login").val();
                    var passw = $("#us_pass").val();
                    var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;

                    if (email != "" && passw != "")
                    {
                        if (!emailReg.test(email))
                        {
                            alert("Debe ingresar un email válido!");
                        } else {
                            if ($('#terminos').is(':checked')) {
                                document.login.submit();
                            } else {
                                alert("Debe estar de acuerdo con los terminos!");
                            }
                        }
                    } else {
                        alert("Debe completar los campos para continuar!");
                    }
                });
            });


        </script>
    <?php
} elseif ($op_usu == "premium") {
	include ("conexion.php");

        $sql = "SELECT us_id, us_codigo_ref, us_nombre, us_pais, us_telefono, us_email, us_direccion, us_nacimiento, us_last_name, us_sexo, us_creacion, us_ciudad, us_postal, 
		               us_descripcion, us_estado, us_login, us_pass, us_estado_us, us_appartment, plan_wl, us_foto, us_desc_prof, us_desc_pers, us_matricula, us_referencia,
					   us_dni,us_cuit,us_constancia,us_tipo_serv
		      FROM ic_usuarios
			  WHERE us_id ='" . $_SESSION['sess_usu_id'] . "' ";

        $result = $db->Execute($sql);

        list($us_id, $us_codigo_ref, $us_nombre, $us_pais, $us_telefono, $us_email, $us_direccion, $us_nacimiento, $us_last_name, $us_sexo, $us_creacion, $us_ciudad, $us_postal, 
		     $us_descripcion, $us_estado, $us_login, $us_pass, $us_estado_us, $us_appartment, $plan_wl, $us_foto, $us_desc_prof,$us_desc_pers, $us_matricula, $us_referencia,
			 $us_dni,$us_cuit,$us_constancia,$us_tipo_serv) = select_format($result->fields);
		
		$subtitulo="Información para solicitud de cuenta premium";
		if($plan_wl=="Premium"){
			$subtitulo="Información profesional";
		}
		
		if ($us_foto == "") {
			$us_foto = "images/profile.jpg";
		}
?>
<script type="text/javascript">

	function enviar(campos, campos1)
	{
		var camposObligatorios = campos.split(",");

		for (i = 0; i < camposObligatorios.length; i++)
		{
			if (document.getElementById(camposObligatorios[i]).value == "") {
				alert("Campo " + document.getElementById(camposObligatorios[i]).title + " es obligatoria.");
				return;
			}
		}

		document.cambio_datos.submit();
	}

</script>

<div class="row">
	<div class="col-sm-2 col-xs-6">
		<div style="text-align:center; width:100%; background:#7C4793; padding:3px 0; margin:5px 0;">
			<a href="info-usuario.html" style="color:#fff; font-size:16px;"><i class="fa fa-user" aria-hidden="true"></i> Mi perfil</a>
		</div>            
	</div>        
	<div class="col-sm-2 col-xs-6">
		<div style="text-align:center; width:100%; background:#7C4793; padding:3px 0; margin:5px 0;">
			<a href="mis-publicaciones.html" style="color:#fff; font-size:16px;"><i class="fa fa-list" aria-hidden="true"></i> Publicaciones</a>
		</div>
	</div>  
	<div class="col-sm-2 col-xs-6">
		<div style="text-align:center; width:100%; background:#7C4793; padding:3px 0; margin:5px 0;">
			<a href="cotizaciones.html" style="color:#fff; font-size:16px;"><i class="fa fa-list-alt" aria-hidden="true"></i> Cotizaciones</a>
		</div>
	</div>         
	<div class="col-sm-2 col-xs-6">
		<div style="text-align:center; width:100%; background:#7C4793; padding:3px 0; margin:5px 0;">
			<a href="publicaciones-favoritas.html" style="color:#fff; font-size:16px;"><i class="fa fa-heart" aria-hidden="true"></i> Mi favoritos</a>
		</div>
	</div>     
	<div class="col-sm-2 col-xs-6">
		<div style="text-align:center; width:100%; background:#7C4793; padding:3px 0; margin:5px 0;">
			<a href="mi-reputacion.html" style="color:#fff; font-size:16px;"><i class="fa fa-star" aria-hidden="true"></i>  Mi Reputación</a>
		</div>
	</div>  

	<div class="col-sm-2 col-xs-6">
		<div style="text-align:center; width:100%; background:#7C4793; padding:3px 0; margin:5px 0;">
			<a href="contenido/planes-worklife.html" style="color:#fff; font-size:16px;"><i class="fa fa-briefcase" aria-hidden="true"></i>  Cambiar mi plan</a>
		</div>
	</div>  
</div>

<br /><br />

<div class="text-left">
	<h1><i class="fa fa-user" aria-hidden="true"></i> Perfil Premium</h1>
	<h2><?=$subtitulo?></h2>
	<br /><br />
</div>

<form action="" method="post" name="cambio_datos" id="cambio_datos" enctype="multipart/form-data">
	<div style="margin:0 auto;">
		<div class="row">
			<div class="col-sm-2 col-xs-12 text-center">
				<img src="<?= $us_foto ?>" height="100" style="border-radius:100px;" />
			</div>        
			<div class="col-sm-4 col-xs-12 text-left">
				Foto de perfil
				<input type="file" name="us_foto" id="us_foto" class="form-control"  />
			</div>
			<div class="col-sm-6 col-xs-12 text-right">
				<br>
				Plan Actual: <b><?=$plan_wl?></b>
				<br>
				Registrado desde: <?=$us_creacion?>
			</div>
			<div class="col-sm-12 col-xs-12 text-left">
				<div style="width:100%; margin:20px 0; border-top:3px solid #eee;"></div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-6 col-xs-12 text-left">
				Nombre(s) *
				<input name="us_nombre" type="text" id="us_nombre" class="form-control" readonly title="Nombre" placeholder="Nombre" value="<?= $us_nombre ?>" />
			</div>

			<div class="col-sm-6 col-xs-12 text-left">
				Apellido(s) *
				<input name="us_last_name" type="text" id="us_last_name" class="form-control" readonly title="Apellido" placeholder="Apellido" value="<?= $us_last_name ?>" />
			</div>
			
			<div class="col-sm-6 col-xs-12 text-left">
				DNI *
				<input name="us_dni" type="number" id="us_dni" class="form-control" title="DNI" placeholder="DNI" value="<?= $us_dni ?>" />
			</div>

			<div class="col-sm-6 col-xs-12 text-left">
				CUIT *
				<input name="us_cuit" type="number" id="us_cuit" class="form-control" title="CUIT" placeholder="CUIT" value="<?= $us_cuit ?>" />
			</div>
			
			<div class="col-sm-6 col-xs-12 text-left">
				Tipo de Prestador *
				<select name="us_tipo_serv" id="us_tipo_serv" class="form-control" title="Tipo de Prestador" placeholder="Tipo de Prestador" value="<?= $us_tipo_serv ?>">
				<?= cargar_lista_estatica("Monotributista,Autonomo,SAAS,SA,SRL,No Incrito AFIP", "Monotributista,Autonomo,Empresa SAAS,Empresa SA,Empresa SRL,No Incrito AFIP", "1", $us_tipo_serv) ?>
                </select>
			</div>

			<div class="col-sm-6 col-xs-12 text-left">
				Constancia AFIP
				<input type="file" name="us_constancia" id="us_constancia" class="form-control"  />
				<?=(($us_constancia!="")?"<a href='".$us_constancia."'>Ver documento</a>":"")?>
			</div>
			
			<div class="col-sm-12 col-xs-12 text-left">
				<div style="width:100%; margin:20px 0; border-top:3px solid #eee;"></div>
			</div>
			
			<div class="col-sm-6 col-xs-12 text-left">
				Descripción profesional *
				<textarea name="us_desc_prof" id="us_desc_prof" rows="4" class="form-control" title="Descripción profesional" placeholder="Describí tus virtudes profesionales, áreas de experiancia, años de trabajo e información sobre tu profesión."><?= $us_desc_prof ?></textarea>
			</div>

			<div class="col-sm-6 col-xs-12 text-left">
				Descripción personal *
				<textarea name="us_desc_pers" id="us_desc_pers" rows="4" class="form-control" title="Descripción personal" placeholder="Describí tus valores y características como persona, que te hacen un gran profesional."><?= $us_desc_pers ?></textarea>
			</div>
			
			<div class="col-sm-6 col-xs-12 text-left">
				Matrícula profesional *
				<textarea name="us_matricula" id="us_matricula" rows="4" class="form-control" title="Matrícula profesional" placeholder="Información sobre tu No. de Matrícula profesional o identificación profesional."><?= $us_matricula ?></textarea>
			</div>

			<div class="col-sm-6 col-xs-12 text-left">
				Referencia personal *
				<textarea name="us_referencia" id="us_referencia" rows="4" class="form-control" title="Referencia personal" placeholder="Información o contacto que pueda darnos una referencia profesional y personal sobre vos."><?= $us_referencia ?></textarea>
			</div>

			<div class="col-sm-12 col-xs-12 text-left">
				<div style="width:100%; margin:20px 0; border-top:3px solid #eee;"></div>
			</div>

			<div class="col-sm-6 col-xs-12 text-left">
				* Campos obligatorios
			</div>

			<div class="col-sm-6 col-xs-12 text-right">
				<input type="hidden" name="us_id" value="<?= $us_id ?>">
				<input type="hidden" name="funcion" value="modificar_usuario_premium">
				<input type="button" onClick="enviar('us_nombre,us_last_name,us_desc_prof,us_desc_pers,us_matricula,us_referencia');" name="guardar" value="Guardar Informacón Profesional" class="boton" />
			</div>
		</div>
	</div>
</form>

<?php
} elseif ($op_usu == "datos_usuario") {


     if (isset($_SESSION['sess_usu_id'])) {

        include ("conexion.php");

        $sql = "SELECT 
				 us_id, us_codigo_ref, us_nombre, us_pais, us_telefono, us_email, us_direccion, us_nacimiento,
				 us_last_name, us_sexo, us_descripcion, us_login, us_pass, us_postal, us_ciudad, us_estado_us,us_appartment,us_foto
			  FROM ic_usuarios
			  WHERE us_id ='" . $_SESSION['sess_usu_id'] . "' ";

        $result = $db->Execute($sql);

        list($us_id, $us_codigo_ref, $us_nombre, $us_pais, $us_telefono, $us_email, $us_direccion, $us_nacimiento,
                $us_last_name, $us_sexo, $us_descripcion, $us_login, $us_pass, $us_postal, $us_ciudad, $us_estado_us, $us_appartment, $us_foto) = select_format($result->fields);
    }

    if ($us_foto == "") {
        $us_foto = "images/profile.jpg";
    }
    ?>
        <script type="text/javascript">

            function enviar(campos, campos1)
            {
                var camposObligatorios = campos.split(",");

                for (i = 0; i < camposObligatorios.length; i++)
                {
                    if (document.getElementById(camposObligatorios[i]).value == "") {
                        alert("Campo " + document.getElementById(camposObligatorios[i]).title + " es obligatoria.");
                        return;
                    }
                }

                document.cambio_datos.submit();
            }

        </script>

        <div class="row">
            <div class="col-sm-2 col-xs-6">
                <div style="text-align:center; width:100%; background:#FAA532; padding:3px 0; margin:5px 0;">
                    <a href="info-usuario.html" style="color:#fff; font-size:16px;"><i class="fa fa-user" aria-hidden="true"></i> Mi perfil</a>
                </div>            
            </div>        
            <div class="col-sm-2 col-xs-6">
                <div style="text-align:center; width:100%; background:#7C4793; padding:3px 0; margin:5px 0;">
                    <a href="mis-publicaciones.html" style="color:#fff; font-size:16px;"><i class="fa fa-list" aria-hidden="true"></i> Publicaciones</a>
                </div>
            </div>  
            <div class="col-sm-2 col-xs-6">
                <div style="text-align:center; width:100%; background:#7C4793; padding:3px 0; margin:5px 0;">
                    <a href="cotizaciones.html" style="color:#fff; font-size:16px;"><i class="fa fa-list-alt" aria-hidden="true"></i> Cotizaciones</a>
                </div>
            </div>         
            <div class="col-sm-2 col-xs-6">
                <div style="text-align:center; width:100%; background:#7C4793; padding:3px 0; margin:5px 0;">
                    <a href="publicaciones-favoritas.html" style="color:#fff; font-size:16px;"><i class="fa fa-heart" aria-hidden="true"></i> Mi favoritos</a>
                </div>
            </div>     
            <div class="col-sm-2 col-xs-6">
                <div style="text-align:center; width:100%; background:#7C4793; padding:3px 0; margin:5px 0;">
                    <a href="mi-reputacion.html" style="color:#fff; font-size:16px;"><i class="fa fa-star" aria-hidden="true"></i>  Mi Reputación</a>
                </div>
            </div>  

            <div class="col-sm-2 col-xs-6">
                <div style="text-align:center; width:100%; background:#7C4793; padding:3px 0; margin:5px 0;">
                    <a href="contenido/planes-worklife.html" style="color:#fff; font-size:16px;"><i class="fa fa-briefcase" aria-hidden="true"></i>  Cambiar mi plan</a>
                </div>
            </div>  
        </div>

        <br /><br />

        <div class="text-left">
            <h1><i class="fa fa-user" aria-hidden="true"></i> Administrar mi Perfil</h1>
            <h2>Información personal y datos de la cuenta Worklife</h2>
            <br /><br />
        </div>

        <form action="" method="post" name="cambio_datos" id="cambio_datos" enctype="multipart/form-data">
            <div style="margin:0 auto;">
                <div class="row">
                    <div class="col-sm-2 col-xs-12 text-center">
                        <img src="<?= $us_foto ?>" height="100" style="border-radius:100px;" />
                    </div>        
                    <div class="col-sm-4 col-xs-12 text-left">
                        Foto de perfil
                        <input type="file" name="us_foto" id="us_foto" class="form-control"  />
                    </div>
                    <div class="col-sm-6 col-xs-12 text-left">
                    </div>
                    <div class="col-sm-12 col-xs-12 text-left">
                        <div style="width:100%; margin:20px 0; border-top:3px solid #eee;"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6 col-xs-12 text-left">
                        Nombre(s) *
                        <input name="us_nombre" type="text" id="us_nombre" class="form-control" title="Nombre" placeholder="Nombre" value="<?= $us_nombre ?>" />
                    </div>

                    <div class="col-sm-6 col-xs-12 text-left">
                        Apellido(s) *
                        <input name="us_last_name" type="text" id="us_last_name" class="form-control" title="Apellido" placeholder="Apellido" value="<?= $us_last_name ?>" />
                    </div>

                    <div class="col-sm-6 col-xs-12 text-left">
                        Dirección *
                        <input name="us_direccion" type="text" id="us_direccion" class="form-control" title="Dirección" placeholder="Dirección" value="<?= $us_direccion ?>" />
                    </div>

                    <div class="col-sm-6 col-xs-12 text-left">
                        Ciudad *
                        <input name="us_ciudad" type="text" id="us_ciudad" class="form-control" title="Ciudad" placeholder="Ciudad" value="<?= $us_ciudad ?>" />
                    </div>

                    <div class="col-sm-6 col-xs-12 text-left">
                        Teléfono
                        <input name="us_telefono" type="text" id="us_telefono" class="form-control" title="Teléfono" placeholder="Teléfono" value="<?= $us_telefono ?>" />
                    </div>

                    <div class="col-sm-6 col-xs-12 text-left">
                        Código Postal
                        <input name="us_postal" type="text" id="us_postal" class="form-control" title="Código Postal" placeholder="Código Postal" value="<?= $us_postal ?>" />
                    </div>

                    <div class="col-sm-6 col-xs-12 text-left">
                        Fecha Nacimiento
                        <input name="us_nacimiento" type="text" id="us_nacimiento" class="form-control" title="Fecha Nacimiento" placeholder="Fecha Nacimiento" value="<?= $us_nacimiento ?>" />
                    </div>

                    <div class="col-sm-6 col-xs-12 text-left">
                        Genero
                        <select name="us_sexo" id="us_sexo" class="form-control" title="Genero" placeholder="Genero" value="<?= $us_sexo ?>">
    <?= cargar_lista_estatica("H,M", "Masculino,Femenino", "1", $us_sexo) ?>
                        </select>
                    </div>

                    <div class="col-sm-12 col-xs-12 text-left">
                        <div style="width:100%; margin:20px 0; border-top:3px solid #eee;"></div>
                    </div>

                    <div class="col-sm-6 col-xs-12 text-left">
                        Email *
                        <input name="us_email" type="text" id="us_email" class="form-control" title="Email" placeholder="Email" value="<?= $us_email ?>" />
                    </div>

                    <div class="col-sm-6 col-xs-12 text-left">
                        Password *
                        <input name="us_pass" type="password" id="us_pass" class="form-control" title="Password" placeholder="Password" value="<?= $us_pass ?>" />
                    </div>

                    <div class="col-sm-12 col-xs-12 text-left">
                        <div style="width:100%; margin:20px 0; border-top:3px solid #eee;"></div>
                    </div>

                    <div class="col-sm-6 col-xs-12 text-left">
                        * Campos obligatorios
                    </div>

                    <div class="col-sm-6 col-xs-12 text-right">
                        <input type="hidden" name="us_id" value="<?= $us_id ?>">
                        <input type="hidden" name="funcion" value="modificar_usuario">
                        <input type="button" onClick="enviar('us_nombre,us_last_name,us_direccion,us_ciudad,us_email,us_pass');" name="guardar" value="Guardar Informacón Personal" class="boton" />
                    </div>
                </div>
            </div>
        </form>

        <script>
            $(window).load(function () {
                $.datepicker.setDefaults($.datepicker.regional[ "es" ]);

                $('#us_nacimiento').datepicker({
                    dateFormat: 'yy-mm-dd',
                    changeYear: true,
                    changeMonth: true,
                    minDate: new Date(1950, 1 - 1, 1),
                    yearRange: "1950:2015"
                });

            });
        </script>
    <?php
} elseif ($op_usu == "recordar_password") {
    ?>

        <table width="70%" border="0" align="center" cellpadding="5" cellspacing="0">
            <form action="" method="post" name="enviar_cuenta" id="enviar_cuenta" >
                <tr>
                    <td align="center"><h1>Recordar Contraseña</h1></td>
                </tr>
                <tr>
                    <td>Para recordar su contraseña, por favor complete el email con el que se regitro en la plataforma. Se enviará un email con los datos suministrados anteriormente. Recuerde que si se registro con redes sociales, no necesita realizar este paso.</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td align="center">
                        <input name="correo_recordar" type="text" id="correo_recordar" placeholder="Email" class="form-control" />
                        <input type="hidden" name="funcion" value="recordar_cuenta">
                        <input name="Enviar" type="submit" class="boton" value="Envíar contraseña" />
                    </td>
                </tr>
            </form>
        </table>
    <?php
} elseif (isset($_SESSION['usuario'])) {
    ?>
        <br />
        <h1>Bienvenido a tu cuenta!</h1>
        <h2>Hola <?= $_SESSION['sess_nombre'] . " " . $_SESSION['sess_last_nombre'] ?>, estas son tus opciones</h2>
        <br />
        <div style="margin:0 auto;">
            <div class="row">
                <div class="col-sm-4 col-xs-12">
                    <div style="text-align:center; background:#7C4793; padding:25px 0; margin:5px 0;">
                        <a href="info-usuario.html" style="color:#fff; font-weight:700;"><i class="fa fa-user" aria-hidden="true"></i> Mi perfil</a>
                    </div>
    <?php
    $sql = "SELECT 
			 us_id, us_codigo_ref, us_nombre, us_pais, us_telefono, us_email, us_direccion, us_nacimiento,
			 us_last_name, us_sexo, us_descripcion, us_login, us_pass, us_postal, us_ciudad, us_estado_us,us_appartment
		  FROM ic_usuarios
		  WHERE us_id ='" . $_SESSION['sess_usu_id'] . "' ";

    $result = $db->Execute($sql);

    list($us_id, $us_codigo_ref, $us_nombre, $us_pais, $us_telefono, $us_email, $us_direccion, $us_nacimiento,
            $us_last_name, $us_sexo, $us_descripcion, $us_login, $us_pass, $us_postal, $us_ciudad, $us_estado_us, $us_appartment) = select_format($result->fields);

    if ($us_nombre == "" || $us_last_name == "" || $us_ciudad == "" || $us_sexo == "" || $us_nacimiento == "") {
        echo '<div style="margin:10px 0; min-height:80px; width:100%;">Debes completar tus datos de pefil, ésto genera mayor confianza en tus clientes!</div>';
    } else {
        echo '<div style="margin:10px 0; min-height:80px; width:100%;"> Felicitaciones, <i class="fa fa-thumbs-o-up" aria-hidden="true"></i> Tus datos estan completos!</div>';
    }
    ?>

                </div>        
                <div class="col-sm-4 col-xs-12">
                    <div style="text-align:center; color:#fff; font-weight:700; background:#7C4793; padding:25px 0; margin:5px 0;">
                        <a href="mis-publicaciones.html" style="color:#fff; font-weight:700;"><i class="fa fa-list" aria-hidden="true"></i> Mis Publicaciones</a>
                    </div>

                    <?php
                    $sql = "SELECT nombre,visualizaciones,fecha FROM ic_directorio WHERE id_usuario ='" . $_SESSION['sess_usu_id'] . "' ORDER BY id_directorio DESC LIMIT 0,1";
                    $result = $db->Execute($sql);

                    if (!$result->EOF) {
                        list($nombre, $visualizaciones, $fecha) = select_format($result->fields);

                        echo '<div style="margin:10px 0; min-height:80px; width:100%;">Tu última publicación: ' . $nombre . ', ' . $visualizaciones . ' veces vista.</div>';
                    } else {
                        echo '<div style="margin:10px 0; min-height:80px; width:100%;"> <i class="fa fa-hand-o-right" aria-hidden="true"></i> No tienes publicaciones, empezá ya!</div>';
                    }
                    ?>
                </div>  
                <div class="col-sm-4 col-xs-12">
                    <div style="text-align:center; color:#fff; font-weight:700; background:#7C4793; padding:25px 0; margin:5px 0;">
                        <a href="cotizaciones.html" style="color:#fff; font-weight:700;"><i class="fa fa-list-alt" aria-hidden="true"></i> Cotizaciones</a>
                    </div>

                    <?php
                    $sql = "SELECT count(a.id_coti) FROM ic_cotizaciones a, ic_directorio b
	      WHERE a.id_usuario ='" . $_SESSION['sess_usu_id'] . "' AND a.id_directorio=b.id_directorio ";
                    $result = $db->Execute($sql);
                    list($can_soli) = select_format($result->fields);

                    $sql = "SELECT count(a.id_coti) FROM ic_cotizaciones a, ic_directorio b
	      WHERE a.id_usuario ='" . $_SESSION['sess_usu_id'] . "' AND a.id_directorio=b.id_directorio AND estado_coti='A'";
                    $result = $db->Execute($sql);
                    list($can_soli_activa) = select_format($result->fields);

                    /////////////////////////

                    $sql = "SELECT count(a.id_coti) FROM ic_cotizaciones a, ic_directorio b
	      WHERE b.id_usuario ='" . $_SESSION['sess_usu_id'] . "' AND a.id_directorio=b.id_directorio ";
                    $result = $db->Execute($sql);
                    list($can_coti) = select_format($result->fields);

                    $sql = "SELECT count(a.id_coti) FROM ic_cotizaciones a, ic_directorio b
	      WHERE b.id_usuario ='" . $_SESSION['sess_usu_id'] . "' AND a.id_directorio=b.id_directorio AND estado_coti='A'";
                    $result = $db->Execute($sql);
                    list($can_coti_activa) = select_format($result->fields);

                    /////////////////////////

                    $sql = "SELECT count(*) FROM ic_respuesta_cotizacion WHERE id_usuario_destino ='" . $_SESSION['sess_usu_id'] . "' AND estado='UNREAD'";
                    $result = $db->Execute($sql);
                    list($can_msg) = select_format($result->fields);

                    /////////////////////////

                    echo '<div style="margin:10px 0; min-height:80px; width:100%;">Tienes ' . $can_soli_activa . ' solicitudes activas (de ' . $can_soli . ' solicitudes). Cotizaciones activas ' . $can_coti_activa . ' de ' . $can_coti . '. Tienes ' . $can_msg . ' mensajes sin leer.</div>';
                    /* if(!$result->EOF){

                      if($respuestas==0){
                      echo '<div style="margin:10px 0; min-height:80px; width:100%;"><i class="fa fa-check-square-o" aria-hidden="true"></i> No cotizaciones, ni mensajes pendientes!</div>';
                      }else{
                      echo '<div style="margin:10px 0; min-height:80px; width:100%;">Tienes '.$id_respuesta.' cotizaciones.</div>';
                      }
                      }else{
                      echo '<div style="margin:10px 0; min-height:80px; width:100%;"> <i class="fa fa-check-square-o" aria-hidden="true"></i> No cotizaciones, ni mensajes pendientes!</div>';
                      } */
                    ?>
                </div> 

                <div class="col-sm-4 col-xs-12">
                    <div style="text-align:center; color:#fff; font-weight:700; background:#7C4793; padding:25px 0; margin:5px 0;">
                        <a href="publicaciones-favoritas.html" style="color:#fff; font-weight:700;"><i class="fa fa-heart" aria-hidden="true"></i> Mi favoritos</a>
                    </div>

                    <?php
                    $sql = "SELECT count(id_fav) FROM ic_favoritos WHERE id_usuario ='" . $_SESSION['sess_usu_id'] . "'";
                    $result = $db->Execute($sql);

                    list($total) = select_format($result->fields);

                    echo '<div style="margin:10px 0; min-height:80px; width:100%;">Tienes ' . $total . ' publicaciones favoritas.</div>';
                    ?>
                </div>     
                <div class="col-sm-4 col-xs-12">
                    <div style="text-align:center; color:#fff; font-weight:700; background:#7C4793; padding:25px 0; margin:5px 0;">
                        <a href="mi-reputacion.html" style="color:#fff; font-weight:700;"><i class="fa fa-star" aria-hidden="true"></i>  Mi Reputación</a>
                    </div>

                    <?php
                    $sql = "SELECT count(calificacion_coti) "
                            . "FROM ic_cotizaciones a, ic_directorio b  "
                            . "WHERE b.id_usuario ='" . $_SESSION['sess_usu_id'] . "'";
                    $result = $db->Execute($sql);

                    list($total) = select_format($result->fields);

                    echo '<div style="margin:10px 0; min-height:80px; width:100%;">Tienes ' . $total . ' calificaciones.</div>';
                    ?>
                </div>  

				<?php
					$sql = "SELECT plan_wl FROM ic_usuarios WHERE us_id ='" . $_SESSION['sess_usu_id'] . "'";
					$result = $db->Execute($sql);
					list($plan) = select_format($result->fields);
					
					$msg_plan="Cambiar a Premium";
					if($plan!="Basic"){
						$msg_plan="Información Premium";
					}
					
					if ($us_nombre == "" || $us_last_name == "" || $us_ciudad == "" || $us_sexo == "" || $us_nacimiento == "") {
				?>
				<div class="col-sm-4 col-xs-12">
                    <div style="text-align:center; color:#fff; font-weight:700; background:#333; padding:25px 0; margin:5px 0;">
                        <a href="javascript:;" style="color:#fff; font-weight:700;"><i class="fa fa-briefcase" aria-hidden="true"></i> Cuenta Premium</a>
                    </div>
					<div style="margin:10px 0; min-height:80px; width:100%;">Debes completar tu perfil. Informate más sobre nuestros planes <a href="contenido/cuentas-premium.html">aquí</a>.</div>
                </div>  
				<?php
					} else {
				?>
                <div class="col-sm-4 col-xs-12">
                    <div style="text-align:center; color:#fff; font-weight:700; background:#F0AD4E; padding:25px 0; margin:5px 0;">
                        <a href="info-usuario-premium.html" style="color:#fff; font-weight:700;"><i class="fa fa-briefcase" aria-hidden="true"></i> <?=$msg_plan?></a>
                    </div>

                    <?php
                   

                    echo '<div style="margin:10px 0; min-height:80px; width:100%;">Tu plan actual es "' . $plan . '". Informate más sobre nuestros planes <a href="contenido/cuentas-premium.html">aquí</a>.</div>';
                    ?>
                </div>  
				<?php
					}
				?>
            </div>
        </div>
        <br />

              <?php
                }
                ?>   
</div>