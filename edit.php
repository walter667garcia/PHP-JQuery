<?php
require_once "db.php";
session_start();

// verificar que se envió parametro
if (!isset($_GET['id_alumno'])) {
    $_SESSION['error'] = "Alumno no especificado";
    header('Location: index.php');
    return;
}

$stmt = $pdo->prepare("SELECT * FROM alumno where id_alumno = :idl");
$stmt->execute(array(":idl" => $_GET['id_alumno']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ($row === false) {
    $_SESSION['error'] = 'Alumno no encontrado';
    header('Location: index.php');
    return;
}

// Flash pattern
if (isset($_SESSION['error'])) {
    echo '<p style="color:red">' . $_SESSION['error'] . "</p>\n";
    unset($_SESSION['error']);
}


$nom = htmlentities($row['nombres']);
$ape = htmlentities($row['apellidos']);
$cor = htmlentities($row['correo']);
$afi = htmlentities($row['aficiones']);
$id_usuario = $row['id_usuario'];
$id = $row['id_alumno'];


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edicion de Alumno</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">


</head>

<body>

    <h1 class="alert alert-success">Editando Alumno</h1>
    <?php
    // require_once "db.php";
    // $id = $_GET['id_alumno'];
    // echo "<h1> Alumno: $id </h1>";

   
    ?>
    <form action="actualizar.php" method="POST" class="container">
        <div class="col-4">
            <h3 class="alert alert-danger">Nombres</h3>
            <input name="nombres" class="form-control " value="<?php echo ($nom); ?>" type="text">

        </div>
        <div class="col-4">
            <h3 class="alert alert-danger">Apellidos</h3>

            <input name="apellidos" class="form-control" value="<?php echo ($ape); ?>" type="text">
        </div>
        <div class="col-4">
            <h3 class="alert alert-danger">Correo</h3>
            <input name="correo" class="form-control " value="<?php echo ($cor); ?>" type="text">
        </div>
        <div class="col-6">
            <h3 class="alert alert-danger">Aficiones</h3>
            <input name="aficiones" class="form-control" value="<?php echo ($afi); ?>" type="text">
        </div>

        <div class="col-6">
            <input name="id_usuario" class="form-control" value="<?php echo ($id_usuario); ?>" type="hidden">
            <input name="id" class="form-control" value="<?php echo ($id); ?>" type="hidden">
        </div>
        <br>
        <br>

        <button class="btn btn-danger">Cancelar</button>
        <button name="actualizar" type="submit" class="btn btn-warning">Aztualizar</button>
    </form>
</body>

</html>