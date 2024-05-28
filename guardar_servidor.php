<?php
include 'config.php';
session_start();

// Función para enviar respuestas JSON
function enviarRespuesta($success, $message, $extra = []) {
    echo json_encode(array_merge(['success' => $success, 'message' => $message], $extra));
    exit();
}

// Obtener datos del cuerpo de la solicitud
$data = json_decode(file_get_contents("php://input"));

// Validar datos básicos
if (empty($data->nombreServidor) || empty($data->software) || empty($data->version)) {
    enviarRespuesta(false, 'Todos los campos son obligatorios');
}

// Validar sesión de usuario
if (!isset($_SESSION['id_usuario'])) {
    enviarRespuesta(false, 'Usuario no autenticado');
}
$idUsuario = $_SESSION['id_usuario'];

// Preparar y ejecutar consulta para contar servidores
$stmt = $conn->prepare("SELECT COUNT(id) AS cantidad FROM servidores WHERE id_usuario = ?");
$stmt->bind_param('i', $idUsuario);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$cantidad_servidores = $row['cantidad'];

// Verificar si el usuario ha alcanzado el límite de servidores
if ($cantidad_servidores >= 4) {
    enviarRespuesta(false, 'Has alcanzado el límite de servidores. No puedes crear más.');
}

// Extraer y sanitizar datos
$nombreServidor = $conn->real_escape_string($data->nombreServidor);
$software = $conn->real_escape_string($data->software);
$version = $conn->real_escape_string($data->version);

// Insertar datos básicos en la tabla 'servidores'
$stmt = $conn->prepare("INSERT INTO servidores (nombre, software, version, id_usuario) VALUES (?, ?, ?, ?)");
$stmt->bind_param('sssi', $nombreServidor, $software, $version, $idUsuario);

if ($stmt->execute()) {
    $servidorId = $stmt->insert_id; // Obtener el ID del servidor recién creado
    
    // Extraer y asignar valores por defecto o proporcionados para las propiedades avanzadas
    $maxPlayers = isset($data->maxPlayers) ? (int)$data->maxPlayers : 20; 
    $difficulty = isset($data->difficulty) ? $conn->real_escape_string($data->difficulty) : 'easy';
    $mode = isset($data->mode) ? $conn->real_escape_string($data->mode) : 'survival';
    $maxBuildHeight = isset($data->maxBuildHeight) ? (int)$data->maxBuildHeight : 256;
    $viewDistance = isset($data->viewDistance) ? (int)$data->viewDistance : 10;
    $spawnNpcs = isset($data->spawnNpcs) ? (bool)$data->spawnNpcs : true;
    $allowNether = isset($data->allowNether) ? (bool)$data->allowNether : true;
    $spawnAnimals = isset($data->spawnAnimals) ? (bool)$data->spawnAnimals : true;
    $spawnMonsters = isset($data->spawnMonsters) ? (bool)$data->spawnMonsters : true;
    $pvp = isset($data->pvp) ? (bool)$data->pvp : true;
    $enableCommandBlock = isset($data->enableCommandBlock) ? (bool)$data->enableCommandBlock : false;
    $allowFlight = isset($data->allowFlight) ? (bool)$data->allowFlight : true;

    // Insertar datos en la tabla 'server_properties'
    $stmt = $conn->prepare("INSERT INTO server_properties (server_id, max_players, difficulty, mode, max_build_height, view_distance, spawn_npcs, allow_nether, spawn_animals, spawn_monsters, pvp, enable_command_block, allow_flight) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param('iissiiiiiiiii', $servidorId, $maxPlayers, $difficulty, $mode, $maxBuildHeight, $viewDistance, $spawnNpcs, $allowNether, $spawnAnimals, $spawnMonsters, $pvp, $enableCommandBlock, $allowFlight);

    if ($stmt->execute()) {
        // Incrementar el contador de servidores del usuario
        $cantidad_servidores++;
        $stmt = $conn->prepare("UPDATE usuarios SET servidores_creados = ? WHERE id = ?");
        $stmt->bind_param('ii', $cantidad_servidores, $idUsuario);

        if ($stmt->execute()) {
            enviarRespuesta(true, 'Datos guardados correctamente.', ['cantidad_servidores' => $cantidad_servidores]);
        } else {
            enviarRespuesta(false, 'Error al actualizar el contador de servidores creados: ' . $conn->error);
        }
    } else {
        enviarRespuesta(false, 'Error al guardar propiedades del servidor: ' . $conn->error);
    }
} else {
    enviarRespuesta(false, 'Error al guardar datos: ' . $conn->error);
}

// Cerrar la conexión a la base de datos
$conn->close();
?>