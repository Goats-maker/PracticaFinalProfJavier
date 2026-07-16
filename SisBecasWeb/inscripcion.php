<?php

require_once "config/config.php";


$errores = [];


$datos = [

    'dui' => '',
    'nombres' => '',
    'apellidos' => '',
    'fecha_nacimiento' => '',
    'sexo' => '',
    'telefono' => '',
    'correo' => '',
    'institucion_estudio' => '',
    'promedio' => '',
    'ingreso_familiar' => '',
    'id_tipo' => ''

];


// Procesar formulario

if($_SERVER["REQUEST_METHOD"] == "POST")
{


    foreach($datos as $campo => $valor)
    {

        $datos[$campo] = trim($_POST[$campo] ?? '');

    }



    // Validar DUI

    if(!preg_match('/^\d{8}-\d$/',$datos['dui']))
    {
        $errores[] = "El DUI debe tener formato 00000000-0.";
    }



    // Nombres

    if($datos['nombres']=="")
    {
        $errores[]="Debe ingresar los nombres.";
    }



    // Apellidos

    if($datos['apellidos']=="")
    {
        $errores[]="Debe ingresar los apellidos.";
    }



    // Fecha nacimiento

    if($datos['fecha_nacimiento']=="")
    {
        $errores[]="Debe seleccionar la fecha de nacimiento.";
    }
    else
    {

        $edad = date("Y") - date("Y", strtotime($datos['fecha_nacimiento']));


        if($edad < 15 || $edad > 30)
        {
            $errores[]="La edad debe estar entre 15 y 30 años.";
        }

    }



    // Sexo

    if($datos['sexo']=="")
    {
        $errores[]="Debe seleccionar el sexo.";
    }



    // Teléfono

    if(!preg_match('/^[67]\d{3}-\d{4}$/',$datos['telefono']))
    {
        $errores[]="El teléfono debe tener formato 7000-0000.";
    }



    // Correo

    if($datos['correo']!="")
    {

        if(!filter_var($datos['correo'],FILTER_VALIDATE_EMAIL))
        {
            $errores[]="El correo electrónico no es válido.";
        }

    }



    // Institución

    if(strlen($datos['institucion_estudio']) < 5)
    {
        $errores[]="La institución debe tener mínimo 5 caracteres.";
    }



    // Promedio

    if(!is_numeric($datos['promedio']))
    {
        $errores[]="El promedio debe ser numérico.";
    }
    else
    {

        if($datos['promedio'] < 6 || $datos['promedio'] > 10)
        {
            $errores[]="El promedio debe estar entre 6 y 10.";
        }

    }




    // Ingreso

    if(!is_numeric($datos['ingreso_familiar']))
    {
        $errores[]="El ingreso familiar debe ser numérico.";
    }
    else
    {

        if($datos['ingreso_familiar'] < 0)
        {
            $errores[]="El ingreso no puede ser negativo.";
        }

    }



    // Tipo de beca

    if($datos['id_tipo']=="")
    {
        $errores[]="Debe seleccionar un tipo de beca.";
    }




    // Insertar

    if(empty($errores))
    {


        try
        {


            $sql = "

            INSERT INTO aspirantes

            (

            dui,
            nombres,
            apellidos,
            fecha_nacimiento,
            sexo,
            telefono,
            correo,
            institucion_estudio,
            promedio,
            ingreso_familiar,
            id_tipo

            )

            VALUES

            (?,?,?,?,?,?,?,?,?,?,?)

            ";



            $stmt = $pdo->prepare($sql);



            $stmt->execute([


                $datos['dui'],

                $datos['nombres'],

                $datos['apellidos'],

                $datos['fecha_nacimiento'],

                $datos['sexo'],

                $datos['telefono'],

                $datos['correo'],

                $datos['institucion_estudio'],

                $datos['promedio'],

                $datos['ingreso_familiar'],

                $datos['id_tipo']


            ]);



            $id = $pdo->lastInsertId();



            header("Location: gracias.php?id=".$id);

            exit;


        }
        catch(PDOException $e)
        {


            // Error de DUI duplicado

            if($e->errorInfo[1] == 1062)
            {

                $errores[]="Ya existe una inscripción con ese DUI.";

            }
            else
            {

                $errores[]="Error al guardar: ".$e->getMessage();

            }


        }


    }


}


// Cargar tipos de beca

$sql = "

SELECT id_tipo,nombre,monto_mensual

FROM tipos_beca

WHERE activo = 1

ORDER BY nombre

";


$stmt = $pdo->query($sql);


$tiposBeca = $stmt->fetchAll(PDO::FETCH_ASSOC);



?>



<!DOCTYPE html>

<html lang="es">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Beca Joven Lourdes 2026</title>


<link rel="stylesheet" href="css/estilos.css">


</head>


<body>



<div class="contenedor">


<h1>
Beca Joven Lourdes 2026
</h1>



<p>
Completa tus datos para solicitar una beca.
</p>




<?php if(!empty($errores)): ?>

<div class="error">

<ul>

<?php foreach($errores as $error): ?>

<li>
<?= htmlspecialchars($error) ?>
</li>

<?php endforeach; ?>

</ul>

</div>

<?php endif; ?>





<form method="POST" action="inscripcion.php">



<h2>
Datos personales
</h2>



<label>DUI *</label>

<input
type="text"
name="dui"
maxlength="10"
placeholder="00000000-0"
value="<?=htmlspecialchars($datos['dui'])?>"
required>




<label>Nombres *</label>

<input
type="text"
name="nombres"
maxlength="60"
value="<?=htmlspecialchars($datos['nombres'])?>"
required>




<label>Apellidos *</label>

<input
type="text"
name="apellidos"
maxlength="60"
value="<?=htmlspecialchars($datos['apellidos'])?>"
required>




<label>Fecha de nacimiento *</label>

<input
type="date"
name="fecha_nacimiento"
value="<?=$datos['fecha_nacimiento']?>"
required>





<label>Sexo *</label>

<div class="radio">


<label>

<input
type="radio"
name="sexo"
value="F"
<?=($datos['sexo']=="F")?"checked":""?>>

Femenino

</label>


<label>

<input
type="radio"
name="sexo"
value="M"
<?=($datos['sexo']=="M")?"checked":""?>>

Masculino

</label>


</div>





<h2>
Contacto
</h2>



<label>Teléfono *</label>

<input
type="tel"
name="telefono"
placeholder="7000-0000"
value="<?=htmlspecialchars($datos['telefono'])?>"
required>




<label>Correo</label>

<input
type="email"
name="correo"
value="<?=htmlspecialchars($datos['correo'])?>">





<h2>
Datos académicos y económicos
</h2>




<label>Institución *</label>

<input
type="text"
name="institucion_estudio"
maxlength="100"
value="<?=htmlspecialchars($datos['institucion_estudio'])?>"
required>





<label>Promedio *</label>

<input
type="number"
name="promedio"
step="0.01"
min="6"
max="10"
value="<?=$datos['promedio']?>"
required>





<label>Ingreso familiar *</label>

<input
type="number"
name="ingreso_familiar"
step="0.01"
min="0"
value="<?=$datos['ingreso_familiar']?>"
required>





<label>Tipo de beca *</label>


<select name="id_tipo" required>


<option value="">
Seleccione...
</option>


<?php foreach($tiposBeca as $tipo): ?>


<option

value="<?=$tipo['id_tipo']?>"

<?=($datos['id_tipo']==$tipo['id_tipo'])?"selected":""?>

>

<?=htmlspecialchars($tipo['nombre'])?>

-

$

<?=number_format($tipo['monto_mensual'],2)?>

/mes


</option>


<?php endforeach; ?>


</select>





<div class="botones">


<button type="reset">

Limpiar

</button>


<button type="submit">

Enviar inscripción

</button>



</div>



</form>



</div>



</body>


</html>