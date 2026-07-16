<?php

session_start();

require_once "../config/config.php";


$error="";


if($_SERVER["REQUEST_METHOD"]=="POST")
{


$correo=$_POST['correo'];

$password=$_POST['password'];



$sql="

SELECT *

FROM usuarios_admin

WHERE correo=?

";


$stmt=$pdo->prepare($sql);

$stmt->execute([$correo]);


$usuario=$stmt->fetch(PDO::FETCH_ASSOC);



if($usuario && password_verify($password,$usuario['password']))
{


$_SESSION['admin']=$usuario['nombre'];


header("Location:index.php");

exit;


}
else
{

$error="Correo o contraseña incorrectos.";

}


}


?>


<!DOCTYPE html>

<html>

<head>

<title>Login Admin</title>

<link rel="stylesheet" href="estilos_admin.css">

</head>


<body>


<div class="panel">


<h1>
Acceso administrador
</h1>



<?php if($error): ?>

<p style="color:red">

<?=$error?>

</p>

<?php endif; ?>



<form method="POST">


<label>
Correo
</label>


<input 
type="email"
name="correo"
required>



<label>
Contraseña
</label>


<input
type="password"
name="password"
required>



<button>
Ingresar
</button>



</form>



</div>


</body>

</html>