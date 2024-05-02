<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="css/index.css">
    <style>
        
    </style>
</head>

<body>

<video autoplay muted loop id="video-fondo">
    <source src="/Img/11.mp4" type="video/mp4">
    Tu navegador no admite la etiqueta de video.
</video>

<div class="container">
    <h2>Iniciar Sesión</h2>

    <form action="procesar_iniciar_sesion.php" method="post">
        <!-- Agrega esto en algún lugar del HTML -->
        <div id="user-info" data-user-id="<?php echo $_SESSION['id_usuario']; ?>"></div>

        <label for="correo">Correo:</label>
        <input type="email" name="correo" required>

        <label for="contrasena">Contraseña:</label>
        <input type="password" name="contrasena" required>

        <button type="submit">Iniciar Sesión</button>
    </form>

    <p>¿No tienes cuenta? <a href="registrar.php">Regístrate aquí</a>.</p>
</div>
</body>
</html>
