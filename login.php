<?php
require_once "db.php";
session_start();


if (
    isset($_POST['correo'])
    && isset($_POST['pass'])
) {


    //validar
    if (strlen($_POST['correo']) < 1 || strlen($_POST['pass']) < 1) {
        $_SESSION['error'] = 'datos incompletos';
        header("Location: login.php");
        return;
    }

    if (strpos($_POST['correo'], '@') === false) {
        $_SESSION['error'] = 'Correo no válido';
        header("Location: login.php");
        return;
    }
    //$salt="XyZzy12*_";

    //$check = hash('md5', $salt.$_POST['pass']);
    $check = $_POST['pass'];
    $stmt = $pdo->prepare('select id_usuario, nombre from usuario WHERE correo = :em AND password = :pw');

    $stmt->execute(array(':em' => $_POST['correo'], ':pw' => $check));

    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row !== false) {
        $_SESSION['nombre'] = $row['nombre'];
        $_SESSION['id_usuario'] = $row['id_usuario'];
        $_SESSION['success'] = "Bienvenido " . $row['nombre'];;
        // Redirect the browser to index.php
        header("Location: index.php");
        return;
    } else {
        $_SESSION['error'] = 'usuario no encontrado, revisar usuario y/o contraseña';
    }
}

// Flash pattern
if (isset($_SESSION['error'])) {
    echo '<p style="color:red">' . $_SESSION['error'] . "</p>\n";
    unset($_SESSION['error']);
}
?>
<html>

<head>
    <title>Ingreso al sistema</title>
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">

</head>

<body>
    <br>
    <br>
    <div class="container">
        <form method="post" class="row">

            <h1 class="btn btn-dark">Ingrese sus credenciales</h1>


            <div class="col-4">
                <p class="text-primary">Email:
                    <input require type="email" class="form-control" name="correo" id="f_correo">
                </p>
            </div>
            <div class="col-6">
                <p class="text-primary">Password:
                    <input type="password" class="form-control" name="pass" id="f_pass">
                </p>
            </div>
            <div class="col-9">
                <p><input class="btn btn-primary" type="submit" onclick="return doValidate();" value="Log In" />
                    <a href="index.php" type="button" class="btn btn-danger">Cancelar</i></a>
                </p>
            </div>
        </form>

        <script>
            function doValidate() {
                console.log('Validating...');
                try {
                    pw = document.getElementById('f_pass').value;
                    em = document.getElementById('f_correo').value;
                    console.log("Validating pw=" + pw);
                    console.log("Validating em=" + em);
                    if (pw == null || pw == "") {
                        alert("Debe especificar ambos campos");
                        return false;
                    }
                    return true;
                } catch (e) {
                    return false;
                }
                return false;
            }
        </script>

    </div>
</body>