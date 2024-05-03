<!-- registrar.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro</title>
    <link rel="stylesheet" href="css/registrar.css">

</head>
<body>
    <h2>Registrar cuenta</h2>

    <form method="post" action="procesar_registro.php">
        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" required>

        <label for="correo">Correo Electrónico:</label>
        <input type="email" name="correo" required>

        <label for="contrasena">Contraseña:</label>
        <input type="password" name="contrasena" required>

        <button type="submit" name="registro">Registrar</button>
    </form>

    <p>¿Ya tienes cuenta? <a href="index.php">Inicia sesión aquí</a>.</p>
</body>
</html>