<?php
// Función para generar una cadena aleatoria
function generarCadenaAleatoria($longitud = 3) {
    return substr(str_shuffle('abcdefghijklmnopqrstuvwxyz0123456789'), 0, $longitud);
}

// Obtener el segmento de la URL que debería ser el nombre de la carpeta
$requestUri = $_SERVER['REQUEST_URI'];
$carpetaNombre = trim(parse_url($requestUri, PHP_URL_PATH), '/');

// Verificar si no se proporciona un nombre de carpeta o si es 'instafile'
if (empty($carpetaNombre) || $carpetaNombre == 'instafile') {
    // Generar un nuevo código aleatorio si no se proporciona ninguno
    $carpetaNombre = generarCadenaAleatoria();
    header("Location: /$carpetaNombre");
    exit();
}

// Definir la ruta completa de la carpeta
$carpetaRuta = __DIR__ . "/descarga/" . $carpetaNombre;

try {
    // Crear la carpeta si no existe
    if (!file_exists($carpetaRuta)) {
        if (!mkdir($carpetaRuta, 0755, true)) {
            throw new Exception("No se pudo crear la carpeta.");
        }
        $mensaje = "Carpeta '$carpetaNombre' creada con éxito.";
    } else {
        $mensaje = "La carpeta '$carpetaNombre' ya existe.";
    }

    // Manejo de la subida de archivos
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_FILES['archivo']) && $_FILES['archivo']['error'] === UPLOAD_ERR_OK) {
            $archivo = $_FILES['archivo'];
            $archivoRuta = $carpetaRuta . '/' . basename($archivo['name']);

            // Verificar si el archivo ya existe
            if (file_exists($archivoRuta)) {
                throw new Exception("El archivo ya existe.");
            }

            // Mover el archivo subido a la carpeta destino
            if (move_uploaded_file($archivo['tmp_name'], $archivoRuta)) {
                $mensaje = "Archivo subido con éxito.";
            } else {
                throw new Exception("Error al subir el archivo.");
            }
        } else {
            throw new Exception("Error durante la subida del archivo.");
        }
    }

    // Manejo de la eliminación de archivos
    if (isset($_POST['eliminarArchivo'])) {
        $archivoAEliminar = $_POST['eliminarArchivo'];
        $archivoRutaAEliminar = $carpetaRuta . '/' . $archivoAEliminar;

        if (file_exists($archivoRutaAEliminar)) {
            if (unlink($archivoRutaAEliminar)) {
                $mensaje = "Archivo '$archivoAEliminar' eliminado con éxito.";
            } else {
                throw new Exception("Error al eliminar el archivo.");
            }
        } else {
            throw new Exception("El archivo '$archivoAEliminar' no existe.");
        }
    }
} catch (Exception $e) {
    $mensaje = "Error: " . htmlspecialchars($e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compartir archivos</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
    <h1>Compartir archivos <sup class="beta">BETA</sup></h1>
    <div class="content">
        <h3>Sube tus archivos y comparte este enlace temporal: <span>ibu.pe/<?php echo htmlspecialchars($carpetaNombre); ?></span></h3>
        <div class="container">
            <div class="drop-area" id="drop-area">
                <form action="" id="form" method="POST" enctype="multipart/form-data">
                    <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" style="fill:#0730c5;"><path d="M13 19v-4h3l-4-5-4 5h3v4z"></path><path d="M7 19h2v-2H7c-1.654 0-3-1.346-3-3 0-1.404 1.199-2.756 2.673-3.015l.581-.102.192-.558C8.149 8.274 9.895 7 12 7c2.757 0 5 2.243 5 5v1h1c1.103 0 2 .897 2 2s-.897 2-2 2h-3v2h3c2.206 0 4-1.794 4-4a4.01 4.01 0 0 0-3.056-3.888C18.507 7.67 15.56 5 12 5 9.244 5 6.85 6.611 5.757 9.15 3.609 9.792 2 11.82 2 14c0 2.757 2.243 5 5 5z"></path></svg> <br>
                    <input type="file" class="file-input" name="archivo" id="archivo" onchange="document.getElementById('form').submit()">
                    <label> Arrastra tus archivos aquí<br>o</label>
                    <p><b>Abre el explorador</b></p> 
                </form>
            </div>

            <div class="container2">
                <div id="file-list" class="pila">
                    <?php
                    if (is_dir($carpetaRuta)) {
                        $files = scandir($carpetaRuta);
                        $files = array_diff($files, array('.', '..'));

                        if (count($files) > 0) {
                            echo "<h3 style='margin-bottom:10px;'>Archivos Subidos:</h3>";

                            foreach ($files as $file) {
                                echo "<div class='archivos_subidos'>
                                <div><a href='/$carpetaNombre/$file' download class='boton-descargar'>$file</a></div>
                                <div>
                                <form action='' method='POST' style='display:inline;'>
                                    <input type='hidden' name='eliminarArchivo' value='$file'>
                                    <button type='submit' class='btn_delete'>
                                        <svg xmlns='http://www.w3.org/2000/svg' class='icon icon-tabler icon-tabler-trash' width='24' height='24' viewBox='0 0 24 24' stroke-width='2' stroke='currentColor' fill='none' stroke-linecap='round' stroke-linejoin='round'>
                                            <path stroke='none' d='M0 0h24v24H0z' fill='none'/>
                                            <path d='M4 7l16 0' />
                                            <path d='M10 11l0 6' />
                                            <path d='M14 11l0 6' />
                                            <path d='M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12' />
                                            <path d='M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3' />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                            </div>";
                            }
                        } else {
                            echo "No se han subido archivos.";
                        }
                    } else {
                        echo "La carpeta no existe.";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <script src="parametro.js"></script>
</body>
</html>
