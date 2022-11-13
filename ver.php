<?php
require_once "db.php";
session_start();

// verificar que se envió parametro
if ( ! isset($_GET['id_alumno']) ) {
  $_SESSION['error'] = "Alumno no especificado";
  header('Location: index.php');
  return;
}

$stmt = $pdo->prepare("SELECT * FROM alumno where id_alumno = :idx");
$stmt->execute(array(":idx" => $_GET['id_alumno']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ( $row === false ) {
    $_SESSION['error'] = 'Alumno no encontrado';
    header( 'Location: index.php' ) ;
    return;
}

// Flash pattern
if ( isset($_SESSION['error']) ) {
    echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
    unset($_SESSION['error']);
}

$fn = htmlentities($row['nombres']);
$ln = htmlentities($row['apellidos']);
$em = htmlentities($row['correo']);
$af = htmlentities($row['aficiones']);
$id = $row['id_alumno'];


?>
<html>
<head>
<title>Consulta de Alumno</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">

</head><body>
<div class="container mt-4">
<div class="row justify-content-center">
<div class="clo-sm-4">
<div class="card p-4">

<h1>Consulta de Alumno</h1>

<form >
	<div class="col-4">
	<p class="form-control">Nombres: <?php echo($fn);?></p>
	</div>

<div class="col-4">
<p class="form-control">Apellidos: <?php echo($ln);?></p>
</div>

<div class="col-4">
<p class="form-control">Correo:<?php echo($em);?></p>
</div>
<div class="col-4">
<p class="form-control">Aficiones: <?php echo($af);?></p>
</div>
<div class="col-4">
<p class="form-control">Cursos y certificaciones</p>
</div>
<ul>
<?php
	$stmt = $pdo->prepare("SELECT ac.anio, c.nombre FROM alumno_cursos ac inner join cursos c 
							on c.id_curso = ac.id_curso
							where ac.id_alumno = :idx");
	$stmt->execute(array(":idx" => $_GET['id_alumno']));
	$result = $stmt -> fetchAll();

	foreach( $result as $row ) {
		echo "<li>".$row['anio'] ;
		echo ": ";
		echo $row['nombre'];
		echo "</li>";
	}
?>
</ul>


<input type="hidden" id="id_alumno" name="id_alumno" value="<?php echo($id);?>">
 

<a href="index.php" class=" btn btn-primary">Regresar</a></p>
</form>

</div>
</div>
</div>
</div>
