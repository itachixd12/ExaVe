<?php
$requested_file = __DIR__ . $_SERVER['REQUEST_URI'];

// Si es una carpeta o no existe, intentar con .html
if (is_dir($requested_file)) {
    $requested_file = $requested_file . 'index.html';
}

// Si el archivo existe, servirlo
if (file_exists($requested_file) && is_file($requested_file)) {
    // Detectar tipo MIME
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    header('Content-Type: ' . finfo_file($finfo, $requested_file));
    finfo_close($finfo);
    readfile($requested_file);
} else {
    // Intentar agregar .html si no existe
    $html_file = __DIR__ . preg_replace('/\/$/', '', $_SERVER['REQUEST_URI']) . '.html';
    if (file_exists($html_file)) {
        header('Content-Type: text/html; charset=utf-8');
        readfile($html_file);
    } else {
        // Servir index-veterinaria.html por defecto (veterinaria, no tienda)
        header('Content-Type: text/html; charset=utf-8');
        readfile(__DIR__ . '/index-veterinaria.html');
    }
}
