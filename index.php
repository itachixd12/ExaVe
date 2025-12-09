<?php
// Obtener la ruta solicitada
$request = $_SERVER['REQUEST_URI'];
$request = str_replace('/index.php', '', $request);

// Si la ruta es raíz o vacía, servir index-veterinaria.html
if ($request === '/' || $request === '') {
    $_GET['file'] = 'index-veterinaria.html';
} else {
    // Remover la barra inicial y servir el archivo solicitado
    $file = ltrim($request, '/');
    
    // Verificar si el archivo existe
    if (file_exists($file)) {
        // Si es un archivo HTML, CSS, JS, etc., servirlo directamente
        $mime_types = array(
            'html' => 'text/html',
            'css' => 'text/css',
            'js' => 'application/javascript',
            'json' => 'application/json',
            'png' => 'image/png',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'gif' => 'image/gif',
            'svg' => 'image/svg+xml'
        );
        
        $ext = pathinfo($file, PATHINFO_EXTENSION);
        $mime = $mime_types[$ext] ?? 'text/plain';
        
        header('Content-Type: ' . $mime);
        readfile($file);
        exit;
    }
}
?>
