<!DOCTYPE html>
<html lang="es">
<head>
   <meta charset="UTF-8">
   <title>Lista de Servidores</title>
   <link rel="stylesheet" href="css/servidores.css">
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10">
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
   <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>
<body>
    
<menu></menu>

   <h1>Servidores</h1>
   <!-- Botón para mostrar el formulario de nuevo servidor -->
   <button id="botonCrear" onclick="mostrarFormulario()">Crear Servidor</button>

   <!-- Formulario para crear un nuevo servidor -->
   <div id="nuevoServidorForm">
       <h2>Crear Nuevo Servidor</h2>
       <label for="nombreServidor">Nombre del Servidor:</label>
       <input type="text" id="nombreServidor" required>


       <label for="estilo">Estilo:</label>
       <select id="estilo" required>
           <option value="Java">Java</option>
           <option value="Bedrock">Bedrock</option>
       </select>


       <label for="version">Versión:</label>
       <select id="version" required>
           <option value="1.20.6">1.20.6</option>
           <option value="1.20.4">1.20.4</option>
           <option value="1.18.2">1.18.2</option>
           <option value="1.18.2">1.12.2</option>
           <option value="1.18.2">1.8.9</option>
           
           <!-- Agrega más versiones según sea necesario -->
       </select>

       <button onclick="guardarNuevoServidor()">Guardar Servidor</button>
   </div>

    <!-- Lista de Servidores -->
    <div id="listaServidores">
        <h2>Lista de Servidores</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre del Servidor</th>
                    <th>Estilo</th>
                    <th>Versión</th>
                    <th>Dirección IP</th>
                    <th class="Acciones">Acciones</th>
                </tr>
            </thead>
            <tbody id="tbodyServidores">
                <!-- Aquí se mostrarán los servidores -->
            </tbody>
        </table>
    </div>

<!-- Elemento para mostrar el estado del servidor -->
<div id="estadoServidor"></div>


<script>
    // Función para mostrar el formulario de nuevo servidor y ocultar el botón
    function mostrarFormulario() {
        $('#botonCrear').hide();
        $('#nuevoServidorForm').slideDown();
    }

    // Función para guardar un nuevo servidor.
    // Función para guardar un nuevo servidor.
    function guardarNuevoServidor() {
        var nombreServidor = $('#nombreServidor').val();
        var estilo = $('#estilo').val();
        var version = $('#version').val();

        // Realizar una solicitud AJAX para guardar el nuevo servidor.
        $.ajax({
            type: 'POST',
            url: 'guardar_servidor.php',
            data: JSON.stringify({
                nombreServidor: nombreServidor,
                estilo: estilo,
                version: version
            }),
            contentType: 'application/json',
            success: function(response) {
                var result = JSON.parse(response);
                if (result.success) {
                    // Alerta de éxito con SweetAlert2
                    Swal.fire({
                        icon: 'success',
                        title: 'Éxito',
                        text: 'Servidor creado con éxito',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Ok'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            cargarServidores(); // Recargar la lista de servidores.
                            $('#nuevoServidorForm').slideUp(); // Ocultar el formulario.
                            $('#botonCrear').show(); // Mostrar el botón nuevamente.
                        }
                    });
                } else {
                    // Alerta de error con SweetAlert2
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error al crear el servidor: ' + result.message,
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Ok'
                    });
                }
            },
            error: function() {
                // Alerta de error de conexión con SweetAlert2
                Swal.fire({
                    icon: 'error',
                    title: 'Error de conexión al servidor',
                    text: 'Tu sesión ha expirado. Por favor, inicia sesión nuevamente.',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Ok'
                });
            }
        });
    }


      // Llamada a la función para cargar los servidores al cargar la página.
$(document).ready(function() {
    cargarServidores();
});

// Función para cargar los servidores desde el servidor.
function cargarServidores() {
    // Realizar una solicitud AJAX para obtener los servidores.
    $.ajax({
        type: 'GET',
        url: 'obtener_servidores.php',
        success: function(response) {
            // Parsear la respuesta JSON.
            var servidores = JSON.parse(response);

            // Limpiar la tabla antes de agregar nuevos datos.
            $('#tbodyServidores').empty();

            // Iterar sobre los servidores y agregarlos a la tabla.
            for (var i = 0; i < servidores.length; i++) {
                var servidor = servidores[i];
                var row = '<tr>' +
                            '<td>' + servidor.id + '</td>' +
                            '<td>' + servidor.nombre + '</td>' +
                            '<td>' + servidor.software + '</td>' +
                            '<td>' + servidor.version + '</td>' +
                            '<td>' + servidor.ip_address + '</td>' + // Mostrar la dirección IP almacenada
                            '<td>' +
                                '<button class="boton-iniciar-servidor" onclick="iniciarServidor(' + servidor.id + ')">Iniciar</button>' +
                                '<button class="boton-parar-servidor" onclick="eliminarServidor(' + servidor.id + ')">Eliminar</button>' +
                            '</td>' +
                          '</tr>';
                $('#tbodyServidores').append(row);
            }
        },
        error: function() {
            // Alerta con estilo personalizado para el error
            Swal.fire({
                icon: 'error',
                title: 'Error al cargar los servidores',
                text: 'Hubo un problema al cargar los servidores. Por favor, vuelva a iniciar sesión.',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Ok'
            }).then((result) => {
                // Redireccionar a index.php después de cerrar la alerta
                if (result.isConfirmed || result.dismiss === Swal.DismissReason.backdrop || result.dismiss === Swal.DismissReason.esc) {
                    window.location.href = 'index.php';
                }
            });
        }
    });
}


</script>

<footer></footer>
</body>
<script type="text/javascript" src="main.js"></script>
</html>