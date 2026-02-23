<?php
session_start();

$usuario = "admin";
$password = "1234";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_POST["usuario"] == $usuario && $_POST["password"] == $password) {
        $_SESSION["usuario"] = $usuario;
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Credenciales incorrectas";
    }
}
?>

<!--
<form method="POST">
    Usuario: <input type="text" name="usuario"><br>
    Password: <input type="password" name="password"><br>
    <button type="submit">Ingresar</button>
</form>
-->

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<div class="container">
    <form method="POST">
        <h2 class="texto">Iniciar Sesión</h2><br>
        <input type="text" name="usuario" placeholder="Usuario"><br>
        <input type="password" name="password" placeholder="Password"><br>

        <div class="btn-wrapper">
            <button type="submit" class="btn">Ingresar</button>
        </div>
    </form>
</div>

</body>
</html>