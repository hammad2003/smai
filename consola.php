<?php
// Verificar si se ha recibido el ID del servidor
if(isset($_GET['servidorId'])) {
    // Incluir archivo de configuración de la base de datos
    include 'config.php';

    // Obtener el ID del servidor desde la solicitud GET
    $servidorId = $conn->real_escape_string($_GET['servidorId']);

    // Obtener la información del servidor de la base de datos
    $query = "SELECT * FROM servidores WHERE id = $servidorId";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $servidor = $result->fetch_assoc();
        
        // Verificar si existe la ID del contenedor
        if (!empty($servidor['container_id'])) {
            // Obtener la ID del contenedor
            $containerId = $servidor['container_id'];

            // Ejecutar el comando `cat` para obtener el contenido de latest.log
            $comando = "sudo docker exec $containerId cat /data/logs/latest.log";
            $output = shell_exec($comando);

            if (!empty($output)) {
                // Separar las líneas del archivo latest.log
                $lineas = explode("\n", $output);

                // Imprimir cada línea con formato HTML
                echo '<div class="log-lines">';
                foreach ($lineas as $linea) {
                    echo "<p>$linea</p>";
                }
                echo '</div>';
            } else {
                echo "Error: No se encontró contenido en latest.log.";
            }
        } else {
            echo "Error: No se encontró la ID del contenedor.";
        }
    } else {
        echo "Error: No se encontró el servidor con el ID proporcionado.";
    }

    // Cerrar la conexión a la base de datos
    $conn->close();
} else {
    echo "Error: No se proporcionó el ID del servidor.";
}

?>