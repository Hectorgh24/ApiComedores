<?php
require_once __DIR__ . '/../middleware/Cors.php';

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    // Establece el código de estado 200 OK para el preflight.
    // Las cabeceras Access-Control ya fueron añadidas por Cors.php
    http_response_code(200);
    exit; // Detiene la ejecución del script, enviando solo las cabeceras CORS y el estado 200.
}

require_once __DIR__ . '/../src/DesayunosComidas/routes/DesayunoComidaRoutes.php';
require_once __DIR__ . '/../src/ProductosCarta/routes/ProductoCartaRoutes.php';
require_once __DIR__ . '/../src/Comentarios/routes/ComentariosRoutes.php';

$request_uri = $_SERVER['REQUEST_URI'];
$request_method = $_SERVER['REQUEST_METHOD'];


header("HTTP/1.1 404 Not Found");
echo "<error>Ruta no encontrada</error>";
?>
