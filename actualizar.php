<?php
require_once "db.php";
session_start();

if(isset($_POST['actualizar'])){
    $id=trim($_POST['id']);
$nombres=trim($_POST['nombres']);
$apellidos=trim($_POST['apellidos']);
$correo=trim($_POST['correo']);
$aficiones=trim($_POST['aficiones']);
$id_usuario=trim($_SESSION['id_usuario']);

$consulta = "UPDATE alumno
SET `nombres`= :a,
 `apellidos` = :b, 
 `correo` = :c, 
 `aficiones` = :d,
 `id_usuario` = :e
WHERE `id` = :id";

$sql = $pdo->prepare($consulta);
$sql->bindParam(':id',$id,PDO::PARAM_INT);
$sql->bindParam(':a',$nombres,PDO::PARAM_STR);
$sql->bindParam(':b',$apellidos,PDO::PARAM_STR);
$sql->bindParam(':c',$correo,PDO::PARAM_STR);
$sql->bindParam(':d',$aficiones,PDO::PARAM_STR);
$sql->bindParam(':e',$id_usuario,PDO::PARAM_INT);

echo "$nombres";
echo "<br>";
echo "$apellidos";
echo "<br>";
echo "$correo";
echo "<br>";
echo "$aficiones";
echo "<br>";
echo "$id_usuario";
echo "<br>";
echo "$id";
$sql->execute();

header("Location: index.php");
}// Cierra envio
?>
