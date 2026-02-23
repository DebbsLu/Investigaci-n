<?php
session_start();

if (!isset($_SESSION["usuario"])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<div class="container">
    <h1 class="texto">Bienvenido al Dashboard</h1>
    <p class="texto"> Has iniciado sesión correctamente.</p>
    <p class="texto"> Servidor: <?php echo gethostname(); ?></p>

    <div class="btn-wrapper">
        <a href="index.php">
            <button class="btn">Cerrar sesión</button>
        </a>
    </div>
</div>

</body>
</html>