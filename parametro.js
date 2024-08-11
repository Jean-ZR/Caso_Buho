// Obtén la URL actual
const urlActual = window.location.href;

// Verifica si el parámetro 'nombre' ya está presente en la URL
const parametros = new URLSearchParams(window.location.search);
let carpetaNombre = parametros.get("nombre");

if (!carpetaNombre) {
    // Si 'nombre' no está presente, genera un número aleatorio
    carpetaNombre = generarCadenaAleatoria();
    // Agrega el parámetro 'nombre' a la URL
    const urlConParametro = urlActual.includes("?") ? `${urlActual}&nombre=${carpetaNombre}` : `${urlActual}?nombre=${carpetaNombre}`;
    // Redirige a la nueva URL con el parámetro 'nombre'
    window.location.href = urlConParametro;
} else {
    // Llama a la función para crear la carpeta con el nombre obtenido
    crearCarpeta(carpetaNombre);
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

// Función para crear la carpeta en el servidor
function crearCarpeta(carpetaNombre) {
    fetch('crearCarpeta.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `nombreCarpeta=${carpetaNombre}`
    })
    .then(response => response.text())
    .then(data => {
        console.log('Carpeta creada con éxito:', data);
    })
    .catch(error => {
        console.error('Error al crear la carpeta:', error);
    });
}

// Elementos DOM
const dropArea = document.getElementById('drop-area');
const form = document.getElementById('form');
const fileInput = document.getElementById('archivo');

// Función para ajustar la altura de los contenedores
function adjustContainerHeights() {
    const dropArea = document.querySelector('.drop-area');
    const container2 = document.querySelector('.container2');
    const content = document.querySelector('.content');
    
    const viewportHeight = window.innerHeight;
    const contentRect = content.getBoundingClientRect();
    const availableHeight = viewportHeight - contentRect.top - 40; // 40px for bottom margin

    const minHeight = 200; // px
    const containerHeight = Math.max(availableHeight / 2, minHeight);

    dropArea.style.height = `${containerHeight}px`;
    container2.style.height = `${containerHeight}px`;
    
    content.style.minHeight = `${availableHeight}px`;
}

// Eventos para el área de arrastrar y soltar
['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
    dropArea.addEventListener(eventName, preventDefaults, false);
});

function preventDefaults(e) {
    e.preventDefault();
    e.stopPropagation();
}

['dragenter', 'dragover'].forEach(eventName => {
    dropArea.addEventListener(eventName, highlight, false);
});

['dragleave', 'drop'].forEach(eventName => {
    dropArea.addEventListener(eventName, unhighlight, false);
});

function highlight() {
    dropArea.classList.add('drag-over');
}

function unhighlight() {
    dropArea.classList.remove('drag-over');
}

// Manejar la caída de archivos
dropArea.addEventListener('drop', handleDrop, false);

function handleDrop(e) {
    const dt = e.dataTransfer;
    const file = dt.files[0];
    handleFile(file);
}

// Manejar la selección de archivos
fileInput.addEventListener('change', function() {
    handleFile(this.files[0]);
});

// Función para manejar el archivo seleccionado
function handleFile(file) {
    if (file) {
        console.log('Archivo seleccionado:', file.name);
        uploadFile(file);
    }
}

// Función para subir el archivo al servidor
function uploadFile(file) {
    const formData = new FormData();
    formData.append('archivo', file);
    formData.append('nombre', carpetaNombre);

    fetch('subir.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(result => {
        console.log('Archivo subido con éxito:', result);
        // Actualizar la lista de archivos
        updateFileList();
    })
    .catch(error => {
        console.error('Error al subir el archivo:', error);
    });
}

// Función para actualizar la lista de archivos
function updateFileList() {
    fetch(`obtenerArchivos.php?nombre=${carpetaNombre}`)
    .then(response => response.text())
    .then(html => {
        document.getElementById('file-list').innerHTML = html;
    })
    .catch(error => {
        console.error('Error al actualizar la lista de archivos:', error);
    });
}

// Manejar el envío del formulario
form.addEventListener('submit', function(e) {
    e.preventDefault();
    const file = fileInput.files[0];
    if (file) {
        uploadFile(file);
    } else {
        alert('Por favor, seleccione un archivo primero.');
    }
});

// Eventos de carga y redimensionamiento
window.addEventListener('load', () => {
    adjustContainerHeights();
    updateFileList();
});
window.addEventListener('resize', adjustContainerHeights);

// Manejar la eliminación de archivos
document.addEventListener('click', function(e) {
    if (e.target && e.target.classList.contains('btn_delete')) {
        e.preventDefault();
        const fileName = e.target.closest('form').querySelector('input[name="eliminarArchivo"]').value;
        deleteFile(fileName);
    }
});

// Función para eliminar un archivo
function deleteFile(fileName) {
    fetch('eliminarArchivo.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `eliminarArchivo=${fileName}&nombre=${carpetaNombre}`
    })
    .then(response => response.text())
    .then(result => {
        console.log('Archivo eliminado:', result);
        updateFileList();
    })
    .catch(error => {
        console.error('Error al eliminar el archivo:', error);
    });
}
