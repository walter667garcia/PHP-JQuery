<?php
require_once "db.php";
session_start();
if (!isset($_SESSION['id_usuario'])) {
	die("Ingresar al sistema"); //Diferencia entre die y redireccionar a alguna pagina como login.php/index.php
}

if (
	isset($_POST['nombres'])
	&& isset($_POST['apellidos'])
	&& isset($_POST['correo'])
	&& isset($_POST['aficiones'])
) {

	// validación de datos del lado del server/cliente?
	if (strlen($_POST['nombres']) < 1 || strlen($_POST['apellidos']) < 1) {
		$_SESSION['error'] = 'Datos incompletos';
		header("Location: nuevo.php");
		return;
	}

	if (strpos($_POST['correo'], '@') === false) {
		$_SESSION['error'] = 'Correo electrónico no válido';
		header("Location: nuevo.php");
		return;
	}

	/* validar los datos variables */
	for ($i = 1; $i <= 9; $i++) {
		if (!isset($_POST['anio' . $i])) continue;
		if (!isset($_POST['curso' . $i])) continue;

		$anio = htmlentities($_POST['anio' . $i]);
		$curso = htmlentities($_POST['curso' . $i]);

		if (strlen($anio) == 0 || strlen($curso) == 0) {
			$_SESSION['error'] = 'Todos los campos son requeridos';
			header("Location: nuevo.php");
			return;
		}

		if (!is_numeric($anio)) {
			$_SESSION['error'] = 'Año debe ser numérico';
			header("Location: nuevo.php");
			return;
		}
	}

	$stmt = $pdo->prepare('INSERT INTO alumno(id_usuario, nombres, apellidos, correo, aficiones)
		VALUES ( :ide, :nom, :ape, :cor, :afi);');

	$stmt->execute(
		array(
			':ide' => $_SESSION['id_usuario'],
			':nom' => htmlentities($_POST['nombres']),
			':ape' => htmlentities($_POST['apellidos']),
			':cor' => htmlentities($_POST['correo']),
			':afi' => htmlentities($_POST['aficiones'])
		)
	);

	$id_alumno = $pdo->lastInsertId();

	//insertar cursos y curso si es nuevo
	for ($i = 1; $i <= 9; $i++) {
		if (!isset($_POST['cur_anio' . $i])) continue;
		if (!isset($_POST['cur_nombre' . $i])) continue;

		$anio = $_POST['cur_anio' . $i];
		$curso = $_POST['cur_nombre' . $i];

		$id_curso = false;
		$stmt = $pdo->prepare('select id_curso from cursos WHERE nombre = :nom');
		$stmt->execute(array(':nom' => $curso));
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($row !== false) $id_curso = $row['id_curso'];
		if ($id_curso === false) {
			$stmt = $pdo->prepare('INSERT INTO cursos(nombre) VALUES (:nom)');
			$stmt->execute(array(':nom' => $curso));
			$id_curso = $pdo->lastInsertId();
		}
		$stmt = $pdo->prepare('INSERT INTO alumno_cursos(id_alumno, anio, id_curso) VALUES ( :ide, :anio, :idc)');
		$stmt->execute(
			array(
				':ide' => $id_alumno,
				':anio' => $anio,
				':idc' => $id_curso
			)
		);
	}

	$_SESSION['success'] = 'Registro agregado!!!';
	header('Location: index.php');
	return;
}

// Flash pattern
if (isset($_SESSION['error'])) {
	echo '<p style="color:red">' . $_SESSION['error'] . "</p>\n";
	unset($_SESSION['error']);
}
?>
<html>

<head>
	<?php require_once('head.php'); ?>

	<title>Ingreso de nuevo estudiante</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">

</head>

<body style="  color:crimson; font-size:20px;">
	<div class="card" style="width: 48rem; padding:10px;">
		<div class="alert alert-warning" role="alert">
			<h2>Ingreso de nuevo estudiante</h2>
		</div>


		<form method="post" class="container">

			<p class="input-group-text btn-info">Nombres:
				<br>
				<input class="form-control" type="text" aria-label="Username" name="nombres" id="fn">
			</p>

			<p class="input-group-text btn-indigo">Apellidos:
				<br>
				<input class="form-control" type="text" name="apellidos" id="ln">
			</p>

			<p class="input-group-text btn-info">Correo:
				<br>
				<input class="form-control" type="text" name="correo" id="em">
			</p>

			<p>Aficiones:
				<br>
				<textarea class="form-control" name="aficiones" rows="8" cols="80" id="afi"></textarea>
			</p>

			<p>
			<div class="alert alert-success" role="alert">
				Cursos y/o certificaciones:
			</div>

			<input type="submit" id="addCurso" class="btn btn-success" value="+">
			</p>
			<div id="curso_fields">
			</div>

			<p><input type="submit" type="button" class="btn btn-primary" value="Add" onclick="return doValidate();" />
				<a href="index.php" type="button" class="btn btn-danger">Cancel</a>
			</p>
		</form>

		<script>
			function doValidate() {
				console.log('validar campos...');
				try {
					fn = document.getElementById('fn').value;
					ln = document.getElementById('ln').value;
					em = document.getElementById('em').value;
					afi = document.getElementById('afi').value;

					//console.log("validando pw="+pw);
					console.log("validando em=" + em);
					if (fn == null || fn == "" ||
						ln == null || ln == "" ||
						em == null || em == "" ||
						afi == null || afi == ""
					) {
						alert("Todos los campos son requeridos");
						return false;
					}
					return true;
				} catch (e) {
					return false;
				}
				return false;
			}
		</script>
		<script>
			cuentaCur = 0;
			$(document).ready(function() {
				window.console && console.log("Document ready event");

				$('#addCurso').click(function() {
					event.preventDefault();
					if (cuentaCur >= 9) {
						alert("Número máximo de cursos ingresados");
						return;
					}
					cuentaCur++;
					window.console && console.log("Agregando curso| " + cuentaCur);
					$('#curso_fields').append(
						'<div id="curso' + cuentaCur + '"> \
					<p>Year: <input class="form-control"type="text" name="cur_anio' + cuentaCur + '" value="" /> \
					<input class="btn btn-danger" type= "button" value="-" \
					onclick="$(\'#curso' + cuentaCur + '\').remove();return false;"></p> \
					<br><p >Curso: <input  type="text" name="cur_nombre' + cuentaCur + '" class="cursos form-control " value="" /> \
					</div>'
					);
					$('.cursos').autocomplete({
						source: "cursos.php"
					});
				});
				//$('.cursos').autocomplete({
				//    source: "cursos.php"
				//});
			});
		</script>
</body>

</html>