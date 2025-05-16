<?php
require_once __DIR__ . '/../src/DesayunosComidas/routes/DesayunoComidaRoutes.php';
require_once __DIR__ . '/../src/ProductosCarta/routes/ProductoCartaRoutes.php';
require_once __DIR__ . '/../src/Comentarios/routes/ComentariosRoutes.php';

$request_uri = $_SERVER['REQUEST_URI'];
$request_method = $_SERVER['REQUEST_METHOD'];

if (strpos($request_uri, '/api/comedores/') === 0) {
    // La ruta ya será manejada por empleadoRoutes.php
    // que incluimos arriba

}
/* else {
    header("HTTP/1.1 404 Not Found");
    echo "<error>Ruta no encontrada</error>";
}*/
?>
