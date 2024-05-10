<?php
include 'config.php';

// Procesar el formulario de registro
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["registro"])) {
    $nombre = $_POST["nombre"];
    $correo = $_POST["correo"];
    $contrasena = password_hash($_POST["contrasena"], PASSWORD_DEFAULT);

    // Insertar nuevo usuario en la base de datos
    $sql = "INSERT INTO usuarios (nombre, correo, contrasena) VALUES ('$nombre', '$correo', '$contrasena')";
    if ($conn->query($sql) === TRUE) {
        // Redirigir a la página de inicio de sesión
        header("Location: index.html");
        exit();
    } else {
        echo "<p>Error al registrar el usuario: " . $conn->error . "</p>";
    }
}
?>