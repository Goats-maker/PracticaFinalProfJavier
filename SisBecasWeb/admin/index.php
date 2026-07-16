<?php

session_start();


if(!isset($_SESSION['admin']))
{

header("Location:login.php");

exit;

}


require_once "../config/config.php";


$sql = "

SELECT

a.id_aspirante,

a.dui,

CONCAT(a.nombres,' ',a.apellidos) AS nombre,

a.institucion_estudio,

a.promedio,

a.ingreso_familiar,

t.nombre AS beca,

a.fecha_registro


FROM aspirantes a


INNER JOIN tipos_beca t

ON a.id_tipo = t.id_tipo


ORDER BY a.fecha_registro DESC


";


$stmt = $pdo->query($sql);


$aspirantes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Total aspirantes

$total = $pdo->query(
"SELECT COUNT(*) FROM aspirantes"
)->fetchColumn();



// Promedio general

$promedioGeneral = $pdo->query(
"SELECT AVG(promedio) FROM aspirantes"
)->fetchColumn();



// Ingreso familiar promedio

$ingresoPromedio = $pdo->query(
"SELECT AVG(ingreso_familiar) FROM aspirantes"
)->fetchColumn();



// Beca más solicitada

$sqlBeca = "

SELECT 
t.nombre,
COUNT(*) cantidad

FROM aspirantes a

INNER JOIN tipos_beca t

ON a.id_tipo=t.id_tipo

GROUP BY t.nombre

ORDER BY cantidad DESC

LIMIT 1

";


$beca = $pdo->query($sqlBeca)
->fetch(PDO::FETCH_ASSOC);




?>


<!DOCTYPE html>

<html lang="es">

<head>

<meta charset="UTF-8">

<title>Panel Administrador</title>


<link rel="stylesheet" href="estilos_admin.css">


</head>


<body>


<div class="panel">


<h1>
Panel de Inscripciones
</h1>


<p>
Listado de aspirantes registrados
</p>

<div class="tarjetas">


<div class="tarjeta">

<h3>
Aspirantes
</h3>

<p>
<?=$total?>
</p>

</div>



<div class="tarjeta">

<h3>
Promedio general
</h3>

<p>
<?=number_format($promedioGeneral,2)?>
</p>

</div>



<div class="tarjeta">

<h3>
Ingreso promedio
</h3>

<p>
$
<?=number_format($ingresoPromedio,2)?>
</p>

</div>



<div class="tarjeta">

<h3>
Beca favorita
</h3>

<p>
<?=$beca['nombre'] ?? 'Sin datos'?>
</p>

</div>


</div>

<a class="salir" href="logout.php">
Cerrar sesión
</a>



<table>


<thead>

<tr>

<th>ID</th>

<th>DUI</th>

<th>Nombre</th>

<th>Institución</th>

<th>Promedio</th>

<th>Ingreso</th>

<th>Beca</th>

<th>Fecha</th>


</tr>

</thead>



<tbody>


<?php foreach($aspirantes as $a): ?>


<tr>


<td>
<?=$a['id_aspirante']?>
</td>


<td>
<?=htmlspecialchars($a['dui'])?>
</td>


<td>
<?=htmlspecialchars($a['nombre'])?>
</td>


<td>
<?=htmlspecialchars($a['institucion_estudio'])?>
</td>


<td>
<?=$a['promedio']?>
</td>


<td>
$<?=$a['ingreso_familiar']?>
</td>


<td>
<?=htmlspecialchars($a['beca'])?>
</td>


<td>
<?=$a['fecha_registro']?>
</td>


</tr>


<?php endforeach; ?>


</tbody>


</table>


</div>


</body>

</html>