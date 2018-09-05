<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

$path = "../adodb/adodb.inc.php";
include ("../admin/var.php");
include ("../conexion.php");

include ("../admin/lib/general.php");
include ("../admin/lib/usuarios.php");

$id = filter_input(INPUT_POST, 'id');
$friends = $_POST['friends'];


if(!empty($id) || is_array($friends)) {
    
    $sql="SELECT us_id FROM ic_usuarios WHERE us_codigo_ref = $id";
    $result1 = $db->Execute($sql);
    list($us_id) = $result1->fields;
    
    $sql="DELETE FROM ic_amigos_fb WHERE id_usuario = $us_id";
    $db->Execute($sql);
    
    foreach ($friends['data'] as $key => $friend) {
        $sql="INSERT INTO ic_amigos_fb (id_amigo, id_usuario) VALUES({$friend['id']},  $us_id)";
        $result2=$db->Execute($sql);
    }
}