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

<h2>Iniciar Sesión</h2>

    <form action="procesar_iniciar_sesion.php" method="post">
        <div id="user-info" data-user-id="<?php echo $_SESSION['id_usuario']; ?>"></div>

        <label for="correo">Correo:</label>
        <input type="email" name="correo" required>

        <label for="contrasena">Contraseña:</label>
        <input type="password" name="contrasena" required>

        <button type="submit">Iniciar Sesión</button>
    </form>

    <p>¿No tienes cuenta? <a href="registrar.php">Regístrate aquí</a>.</p>
</body>
</html>