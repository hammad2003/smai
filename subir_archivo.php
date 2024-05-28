<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar si se recibieron los datos esperados
    if (isset($_FILES['archivo'], $_POST['servidorId'])) {
        $archivo = $_FILES['archivo'];
        $servidorId = $_POST['servidorId'];

        // Verificar si hay algún error al subir el archivo
        if ($archivo['error'] !== UPLOAD_ERR_OK) {
            $errorMessage = obtenerMensajeError($archivo['error']);
            echo json_encode(['success' => false, 'message' => 'Error al subir el archivo: ' . $errorMessage]);
            exit();
        }

        // Obtener información del servidor desde la base de datos
        $query = "SELECT container_id, software FROM servidores WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $servidorId);
        $stmt->execute();
        $stmt->bind_result($container_id, $software);
        $stmt->fetch();
        $stmt->close();

        // Verificar si se obtuvo la información del servidor correctamente
        if (!$container_id) {
            echo json_encode(['success' => false, 'message' => 'Servidor no encontrado']);
            exit();
        }

        // Definir el directorio de destino en función del software del servidor
        switch ($software) {
            case 'Forge':
                $directorioDestino = "/var/www/smai/mods";
                $directorioDestinoContenedor = "/data/mods/";
                break;
            case 'Spigot':
            case 'Bukkit':
                $directorioDestino = "/var/www/smai/plugins";
                $directorioDestinoContenedor = "/data/plugins/";
                break;
            default:
                echo json_encode(['success' => false, 'message' => 'Software del servidor no compatible']);
                exit();
        }

        // Verificar si el directorio de destino existe, si no, crearlo
        if (!is_dir($directorioDestino) && !mkdir($directorioDestino, 0775, true)) {
            echo json_encode(['success' => false, 'message' => 'No se pudo crear el directorio de destino']);
            exit();
        }

        // Mover el archivo al directorio de destino en el servidor
        $nombreArchivoServidor = $directorioDestino . "/" . basename($archivo['name']);
        if (!move_uploaded_file($archivo['tmp_name'], $nombreArchivoServidor)) {
            echo json_encode(['success' => false, 'message' => 'Error al subir el archivo al servidor']);
            exit();
        }

        // Transferir el archivo al contenedor Docker
        $comando = "sudo docker cp \"$nombreArchivoServidor\" \"$container_id:$directorioDestinoContenedor\"";
        $output = [];
        $return_var = null;
        exec($comando, $output, $return_var);

        // Eliminar el archivo del servidor después de la transferencia
        unlink($nombreArchivoServidor);

        // Verificar el resultado de la transferencia al contenedor
        if ($return_var === 0) {
            echo json_encode(['success' => true, 'message' => 'Archivo subido correctamente. Por favor, reinicie el servidor para completar la carga']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al enviar el archivo al contenedor']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}

function obtenerMensajeError($codigo) {
    switch ($codigo) {
        case UPLOAD_ERR_INI_SIZE:
            return 'El archivo subido excede la directiva upload_max_filesize en php.ini';
        case UPLOAD_ERR_FORM_SIZE:
            return 'El archivo subido excede la directiva MAX_FILE_SIZE especificada en el formulario HTML';
        case UPLOAD_ERR_PARTIAL:
            return 'El archivo subido solo se ha subido parcialmente';
        case UPLOAD_ERR_NO_FILE:
            return 'No se ha subido ningún archivo';
        case UPLOAD_ERR_NO_TMP_DIR:
            return 'Falta la carpeta temporal';
        case UPLOAD_ERR_CANT_WRITE:
            return 'No se pudo escribir el archivo en el disco';
        case UPLOAD_ERR_EXTENSION:
            return 'Una extensión de PHP detuvo la subida del archivo';
        default:
            return 'Error desconocido al subir el archivo';
    }
}
?>