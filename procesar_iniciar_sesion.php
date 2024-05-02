<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $correo = $_POST['correo'];
    $contrasena = $_POST['contrasena'];

    // Buscar el usuario por correo
    $sql = "SELECT * FROM usuarios WHERE correo = '$correo'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $hashed_password = $row['contrasena'];

        // Verificar la contraseña utilizando password_verify
        if (password_verify($contrasena, $hashed_password)) {
            // Iniciar sesión y almacenar información del usuario en la sesión
            session_start();
            $_SESSION['id_usuario'] = $row['id']; // Guardar solo la ID del usuario

            // Redirigir a la página de inicio
            header("Location: inicio.php");
            exit();
        } else {
            // Contraseña incorrecta, mostrar mensaje de error
            echo "Credenciales inválidas. Por favor, verifica tu correo y contraseña.";
        }
    } else {
        // Usuario no encontrado, mostrar mensaje de error
        echo "Credenciales inválidas. Por favor, verifica tu correo y contraseña.";
    }
}

// Si llega a este punto, probablemente se accedió a procesar_iniciar_sesion.php sin enviar el formulario
echo "Acceso no autorizado.";
?>
