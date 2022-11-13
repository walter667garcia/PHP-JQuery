<?php
require_once "db.php";
session_start();
?>
<html>

<head>
    <title>Lista de estudiantes</title>
    <!-- CSS only -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
</head>

<body>
    <br>
    <br>
    <div class="row">
        <h1 type="button" class="btn btn-dark">Lista de estudiantes</h1>
    </div>


    <?php
    if (isset($_SESSION['id_usuario'])) {
        echo ('<p><a href="logout.php" type="button" class="btn btn-danger">Salir</a></p>');
    } else {
        echo ('<div class="alert alert-danger" role="alert">
                 <a type="button" class="btn btn-info" href="login.php">Ingresar a sistema</a>
               </div>');
    }

    if (isset($_SESSION['error'])) {
        echo '<p style="color:red">' . $_SESSION['error'] . "</p>\n";
        unset($_SESSION['error']);
    }
    if (isset($_SESSION['success'])) {


        echo '<div class="alert alert-info" role="alert">' . $_SESSION['success'] . "</div>\n";
        unset($_SESSION['success']);
    }
    echo ('<table class="table table-striped">' . "\n");
    echo ('<tr>
<th>Nombres</th>
<th>Apellidos</th>
<th>Correo</th>
<th>Aficiones</th>
<th>Acción</th>
<tr>');

    $stmt = $pdo->query("SELECT id_alumno, nombres, apellidos, correo, aficiones FROM alumno");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr><td>";
        echo (($row['nombres']));
        echo "</td><td>";
        echo (($row['apellidos']));
        echo ("</td><td>");
        echo (($row['correo']));
        echo ("</td><td>");
        echo (($row['aficiones']));
        echo ("</td><td>");

        echo ('<a type="button" class="btn btn-info" href="ver.php?id_alumno=' . $row['id_alumno'] . '"><i class="bi bi-person-lines-fill"></i></a> / ');
        if (isset($_SESSION['id_usuario'])) {
            echo ('<a type="button" class="btn btn-success" href="edit.php?id_alumno=' . $row['id_alumno'] . '"><i class="bi bi-pencil-square"></i></a> / ');
            echo ('<a type="button" class="btn btn-danger" href="delete.php?id_alumno=' . $row['id_alumno'] . '"><i class="bi bi-trash3"></i></a>');
        }
        echo ("</td></tr>\n");
    }
    ?>
    </table>
    <?php
    if (isset($_SESSION['id_usuario'])) {
        echo ('<a href="nuevo.php" type="button" class="btn btn-primary"><i class="bi bi-person-plus-fill"></i></a>');
    }
    ?>