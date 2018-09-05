<?php

/*
@author. Carlos García Pérez.
@company. Autentia Real Business Solutions

Este script genera la imagen de validación del formulario
*/

session_start();

$imageText = $_SESSION["imageText"];
// Verificamos que el usuario inicio sesión
if (! isset($imageText)){
// El usuario no inicio sessión header("HTTP/1.0 405"); // Recurso no permitido
return;
}

header("Content-type: image/gif");

// Definir la variable de entorno para GD
putenv('GDFONTPATH=' . realpath('.'));

// Constantes que nos harán el código más legible
define("HEIGHT", 33);
define("SPC", 30);
define("WIDTH", 150);
define("FONTNAME", "arial.ttf");
define("FONTSIZE", 16);
/* { Generamos la imagen. }
* Nota:
* Esta parte podría ser cambiada para generar una imagen más compleja de
* averiguar por programas de detección de imágenes.
*/
$img = @imagecreate(WIDTH, HEIGHT);
@imagecolorallocate($img, 200, 200, 200);
$grey = @imagecolorallocate($img, 192, 192, 192);
$black = @imagecolorallocate($img, 0, 0, 0);
@imagerectangle($img, 0, 0, WIDTH - 1, HEIGHT - 1, $grey);
for ($i=0, $l = strlen($imageText); $i < $l; $i++){
@imagettftext($img, FONTSIZE, 18, $i*SPC + 20, 25, $black, FONTNAME, $imageText[$i]);
}
// Fin { Generamos la imagen }
// Enviamos la imagen al cliente (Navegador, PDA, Móvil, etc).
// Como veis no se guarda nada en el disco duro pues le enviamos la imagen directamente al cliente.
@imagegif ($img);

// Liberamos recursos
@imagedestroy($img);

?>
