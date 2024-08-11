<?php
// Obtén el nombre de la carpeta desde la URL
$nombreCarpeta = isset($_GET['carpeta']) ? $_GET['carpeta'] : '';

// Verifica que se haya proporcionado un nombre de carpeta
if (empty($nombreCarpeta)) {
    $nombreCarpeta = generarCadenaAleatoria();
    header("Location: " . $_SERVER['PHP_SELF'] . "?carpeta=" . $nombreCarpeta);
    exit;
}

// Ruta donde deseas crear la carpeta (por ejemplo, en la carpeta 'descarga')
$carpetaRuta = "./descarga/" . $nombreCarpeta;

// Verifica si la carpeta ya existe antes de crearla
if (!file_exists($carpetaRuta)) {
    mkdir($carpetaRuta, 0755, true);
}

// Maneja el mensaje para mostrar
$mensaje = "";

// Lista los archivos en la carpeta si existe
if (file_exists($carpetaRuta)) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['archivos'])) {
        $archivos = $_FILES['archivos'];
        $totalArchivos = count($archivos['name']);

        for ($i = 0; $i < $totalArchivos; $i++) {
            $nombreArchivo = str_replace(' ', '_', $archivos['name'][$i]);
            if (move_uploaded_file($archivos['tmp_name'][$i], $carpetaRuta . '/' . $nombreArchivo)) {
                $mensaje = "Archivo(s) subido(s) con éxito.";
            } else {
                $mensaje = "Error al subir el archivo(s).";
            }
        }
    }

    $files = array_diff(scandir($carpetaRuta), array('.', '..'));
} else {
    $mensaje = "La carpeta no existe.";
}

function generarCadenaAleatoria() {
    $caracteres = 'abcdefghijklmnopqrstuvwxyz0123456789';
    $cadenaAleatoria = '';
    for ($i = 0; $i < 3; $i++) {
        $caracterAleatorio = $caracteres[random_int(0, strlen($caracteres) - 1)];
        $cadenaAleatoria .= $caracterAleatorio;
    }
    return $cadenaAleatoria;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compartir archivos</title>
    <link rel="stylesheet" href="estilo.css">
<<<<<<< HEAD
    <style>
        .drop-area {
            border: 2px dashed #cccccc;
            padding: 20px;
            text-align: center;
            cursor: pointer;
        }
        .drop-area.dragging {
            border-color: #0000ff;
        }
        .file-input {
            display: none;
        }
        .archivos_subidos {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 10px 0;
        }
        .boton-descargar {
            text-decoration: none;
            color: #0000ff;
        }
        .btn_delete {
            background: none;
            border: none;
            color: #ff0000;
            cursor: pointer;
        }
        .btn_delete svg {
            width: 24px;
            height: 24px;
        }
    </style>
=======
>>>>>>> ac06d9e2f0bff1cb7b6da4f1c148a3c10684ec17
</head>
<body>
    <h1>Compartir archivos <sup class="beta">BETA</sup></h1>
    <div class="content">
<<<<<<< HEAD
        <h3>Sube tus archivos y comparte este enlace temporal: <span id="share-link"><?php echo htmlspecialchars("zrcarlos20.xyz/?carpeta=" . $nombreCarpeta); ?></span></h3>
        <div class="container">
            <div class="drop-area" id="drop-area">
                <form action="index.php?carpeta=<?php echo htmlspecialchars($nombreCarpeta); ?>" id="form" method="POST" enctype="multipart/form-data">
                    <input type="file" class="file-input" name="archivos[]" id="archivos" multiple>
                    <label for="archivos">Arrastra tus archivos aquí<br>o</label>
                    <p><b>Abre el explorador</b></p>
                </form>
            </div>
        </div>
        <?php if (!empty($mensaje)) : ?>
            <p><?php echo htmlspecialchars($mensaje); ?></p>
        <?php endif; ?>
        <?php if (!empty($files)) : ?>
            <h3>Archivos en la carpeta '<?php echo htmlspecialchars($nombreCarpeta); ?>':</h3>
            <div id="file-list">
                <?php foreach ($files as $file) : ?>
                    <div class="archivos_subidos">
                        <div><a href="<?php echo htmlspecialchars($carpetaRuta . '/' . $file); ?>" download class="boton-descargar"><?php echo htmlspecialchars($file); ?></a></div>
                        <div>
                            <form action="eliminar.php?carpeta=<?php echo htmlspecialchars($nombreCarpeta); ?>" method="POST" style="display:inline;">
                                <input type="hidden" name="eliminarArchivo" value="<?php echo htmlspecialchars($file); ?>">
                                <button type="submit" class="btn_delete">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M4 7l16 0" />
                                        <path d="M10 11l0 6" />
                                        <path d="M14 11l0 6" />
                                        <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                        <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    <script>
        // URL actual
        const urlActual = window.location.href;

        // Extrae el segmento final de la URL como nombre de la carpeta
        var nombreCarpeta = window.location.search.split('carpeta=')[1] || '';

        if (!nombreCarpeta) {
            // Genera un nombre aleatorio si no existe
            nombreCarpeta = generarCadenaAleatoria();
            // Redirige a la nueva URL con el nombre generado
            window.location.href = `${window.location.origin}${window.location.pathname}?carpeta=${nombreCarpeta}`;
        }

        // Función para generar una cadena aleatoria de 3 caracteres
        function generarCadenaAleatoria() {
            const caracteres = 'abcdefghijklmnopqrstuvwxyz0123456789';
            let cadenaAleatoria = '';
            for (let i = 0; i < 3; i++) {
                const caracterAleatorio = caracteres.charAt(Math.floor(Math.random() * caracteres.length));
                cadenaAleatoria += caracterAleatorio;
            }
            return cadenaAleatoria;
        }

        // Maneja la subida de archivos
        document.querySelector('.file-input').addEventListener('change', function(e) {
            const files = e.target.files;
            if (files.length > 0) {
                let formData = new FormData();
                for (let i = 0; i < files.length; i++) {
                    formData.append('archivos[]', files[i]);
                }

                fetch(`index.php?carpeta=${nombreCarpeta}`, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(data => {
                    console.log(data);
                    // Aquí puedes mostrar un mensaje de éxito o realizar otras acciones después de la subida
                    location.reload(); // Recarga la página para mostrar los archivos actualizados
                })
                .catch(error => {
                    console.error('Error al subir los archivos:', error);
                    // Aquí puedes mostrar un mensaje de error
                });
            }
        });

        // Manejo de eventos de arrastre
        const dropArea = document.getElementById('drop-area');

        dropArea.addEventListener('dragover', (event) => {
            event.preventDefault();
            dropArea.classList.add('dragging');
        });

        dropArea.addEventListener('dragleave', () => {
            dropArea.classList.remove('dragging');
        });

        dropArea.addEventListener('drop', (event) => {
            event.preventDefault();
            dropArea.classList.remove('dragging');
            const files = event.dataTransfer.files;
            if (files.length > 0) {
                let formData = new FormData();
                for (let i = 0; i < files.length; i++) {
                    formData.append('archivos[]', files[i]);
                }

                fetch(`index.php?carpeta=${nombreCarpeta}`, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(data => {
                    console.log(data);
                    location.reload(); // Recarga la página para mostrar los archivos actualizados
                })
                .catch(error => {
                    console.error('Error al subir los archivos:', error);
                });
            }
        });
    </script>
=======
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
>>>>>>> ac06d9e2f0bff1cb7b6da4f1c148a3c10684ec17
</body>
</html>
