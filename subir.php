<?php
<<<<<<< HEAD
// Obtén el nombre de la carpeta desde la URL
$nombreCarpeta = isset($_GET['carpeta']) ? $_GET['carpeta'] : '';

// Verifica que se haya proporcionado un nombre de carpeta
if (empty($nombreCarpeta)) {
    echo "No se proporcionó un nombre de carpeta.";
    exit;
}

// Ruta donde deseas crear la carpeta (por ejemplo, en la carpeta 'descarga')
$carpetaRuta = "./descarga/" . $nombreCarpeta;

// Verifica si la carpeta ya existe antes de crearla
if (!file_exists($carpetaRuta)) {
    mkdir($carpetaRuta, 0755, true);
}

// Maneja la subida de archivos
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['archivos'])) {
    $archivos = $_FILES['archivos'];
    $totalArchivos = count($archivos['name']);

    for ($i = 0; $i < $totalArchivos; $i++) {
        $nombreArchivo = str_replace(' ', '_', $archivos['name'][$i]);
        if (move_uploaded_file($archivos['tmp_name'][$i], $carpetaRuta . '/' . $nombreArchivo)) {
            echo "Archivo(s) subido(s) con éxito.";
        } else {
            echo "Error al subir el archivo(s).";
        }
    }
} else {
    echo "No se han recibido archivos.";
}
?>
=======
// Nombre de la carpeta a crear (obtenido del parámetro)
$carpetaNombre = $_GET['nombre'];

// Ruta donde deseas crear la carpeta (por ejemplo, en la carpeta 'descarga')
$carpetaRuta = "./descarga/" . $carpetaNombre;

// Verifica si la carpeta ya existe antes de crearla
if (!file_exists($carpetaRuta)) {
    // Crea la carpeta con permisos adecuados (por ejemplo, 0755)
    mkdir($carpetaRuta, 0755, true);
    $mensaje = "Carpeta '$carpetaNombre' creada con éxito.";
} else {
    $mensaje = "La carpeta '$carpetaNombre' ya existe.";
}

// Luego, cuando se procese un archivo, guárdalo en la carpeta creada
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $archivo = $_FILES['archivo'];

    if (move_uploaded_file($archivo['tmp_name'], $carpetaRuta . '/' . $archivo['name'])) {
        echo "Archivo subido con éxito.";
    } else {
        echo "Error al subir el archivo.";
    }
}
?>





>>>>>>> ac06d9e2f0bff1cb7b6da4f1c148a3c10684ec17
