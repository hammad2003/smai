# 🚀 Servidores de Minecraft. Automatizados. Increíbles.

¡Bienvenido a SMAI!

Nuestra misión es hacer que tu experiencia de juego en Minecraft sea la más fácil y agradable posible. Desde modos de juego únicos hasta características innovadoras, estamos aquí para proporcionarte un mundo lleno de posibilidades emocionantes y diferentes.

Explora con nosotros nuevas formas de sumergirte en el universo de Minecraft. En SMAI, queremos facilitar tu aventura, ofreciéndote un hogar digital donde puedas expresar tu creatividad, probar cosas nuevas y disfrutar de cada momento.

Así que prepárate para disfrutar de lo inesperado, lo no convencional y lo extraordinario. ¡Estamos emocionados de tenerte con nosotros en este viaje hacia la diversión y la exploración!


> [!NOTE]
> ## Requisitos
> - **Cliente:** 
>   - Linux (Ubuntu o distribución similar).
>   - Acceso a Internet.
> - **Servidor:**
>   - Linux (Ubuntu o distribución similar).
>   - Acceso a Internet.


## 🛠️ Installation / Configuración de SMAI

1. **Clonar el Repositorio:**
   Clona o descarga el repositorio de GitHub en el cliente.
   ```bash
   https://github.com/hammad2003/Scripts-Playbooks.git && cd Scripts-Playbooks

3. **Ejecutar Script de Instalación:**
   Ejecuta el script `run.sh` para configurar el servidor o cliente. Asegúrate de dar permisos de ejecución al script con el comando:
    ```bash
    chmod u+x install_client.sh
    ```
    ```bash
    chmod u+x install_server.sh
    ```
    ```bash
    chmod u+x run.sh && ./run.sh
    ```
    
   Al ejecutar `run.sh`, se te presentará un menú con dos opciones:
   - **Configurar el Cliente:** Instala el TLauncher (Minecraft).
   - **Configurar el Servidor SMAI:** Configura una página para alojar servidores de Minecraft con SMAI.


## Configuración del Cliente

Para configurar el Cliente , sigue estos pasos:

1. Abre una terminal.
2. Navega al directorio ejecutando el siguiente comando:
    ```
    cd /home/usuario/smai/launcher
    ```

3. Una vez en el directorio del lanzador, hay dos opciones:

    a. Ejecutar el lanzador directamente mediante el comando:
       ```
       java -jar TLauncher.jar
       ```

    b. Alternativamente, puedes hacer lo siguiente:

       - Haz clic derecho en el archivo `TLauncher.jar`.
       - Selecciona "Propiedades".
       - Ve a la pestaña "Permisos".
       - Marca la opción "Permitir ejecutar el archivo como un programa".
       - Luego, abre el archivo con OpenJDK Java 18 Runtime.

Recuerda que estos pasos son necesarios para iniciar correctamente el cliente (Minecraft). ¡Disfruta de tu experiencia!


## Configuración del Servidor SMAI

Después de instalar y configurar el Servidor SMAI, es importante seguir estos pasos adicionales:

1. Abre una terminal.
2. Navega al directorio `/var/www/html` ejecutando el siguiente comando:
    ```
    cd /var/www/html
    ```

3. Una vez en el directorio, inicia el servidor PHP ejecutando el siguiente comando:
    ```
    php -S 0.0.0.0:5500 -t .
    ```

Este comando iniciará un servidor PHP en la dirección IP `0.0.0.0` y el puerto `5500`, sirviendo los archivos del directorio actual (`.`). Asegúrate de que el servidor esté en funcionamiento antes de proceder con cualquier otra tarea.


## Herramientas ⚙:
![GitHub](https://img.shields.io/badge/github-%23121011.svg?style=for-the-badge&logo=github&logoColor=white)
![Google Drive](https://img.shields.io/badge/Google%20Drive-4285F4?style=for-the-badge&logo=googledrive&logoColor=white)
![Discord](https://img.shields.io/badge/Discord-%235865F2.svg?style=for-the-badge&logo=discord&logoColor=white)
![Gmail](https://img.shields.io/badge/Gmail-D14836?style=for-the-badge&logo=gmail&logoColor=white)
![Apache](https://img.shields.io/badge/apache-%23D42029.svg?style=for-the-badge&logo=apache&logoColor=white)
![MySQL](https://img.shields.io/badge/mysql-4479A1.svg?style=for-the-badge&logo=mysql&logoColor=white)
![Ansible](https://img.shields.io/badge/ansible-%231A1918.svg?style=for-the-badge&logo=ansible&logoColor=white)
![Shell Script](https://img.shields.io/badge/shell_script-%23121011.svg?style=for-the-badge&logo=gnu-bash&logoColor=white)
![YAML](https://img.shields.io/badge/yaml-%23ffffff.svg?style=for-the-badge&logo=yaml&logoColor=151515)
![Ubuntu](https://img.shields.io/badge/Ubuntu-E95420?style=for-the-badge&logo=ubuntu&logoColor=white)
![HTML5](https://img.shields.io/badge/html5-%23E34F26.svg?style=for-the-badge&logo=html5&logoColor=white)
![CSS3](https://img.shields.io/badge/css3-%231572B6.svg?style=for-the-badge&logo=css3&logoColor=white)
![PHP](https://img.shields.io/badge/php-%23777BB4.svg?style=for-the-badge&logo=php&logoColor=white)
![Docker](https://img.shields.io/badge/docker-%230db7ed.svg?style=for-the-badge&logo=docker&logoColor=white)
![Visual Studio Code](https://img.shields.io/badge/Visual%20Studio%20Code-0078d7.svg?style=for-the-badge&logo=visual-studio-code&logoColor=white)
![IntelliJ IDEA](https://img.shields.io/badge/IntelliJIDEA-000000.svg?style=for-the-badge&logo=intellij-idea&logoColor=white)
