<?php

$id = $_GET['id'] ?? null;

?>

<!DOCTYPE html>
<html lang="es">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Inscripción completada</title>

<link rel="stylesheet" href="css/estilos.css">

</head>


<body>


<div class="contenedor gracias">


<div class="icono-exito">

✔

</div>


<h1>

¡Inscripción registrada!

</h1>


<p>

Tu solicitud de beca fue enviada correctamente.

</p>


<?php if($id): ?>

<div class="codigo">

Número de inscripción:

<strong>
<?= htmlspecialchars($id) ?>
</strong>

</div>

<?php endif; ?>


<a href="inscripcion.php" class="boton">

Nueva inscripción

</a>


</div>


</body>

</html>