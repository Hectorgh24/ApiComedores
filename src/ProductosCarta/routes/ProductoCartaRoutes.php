<?php
require_once __DIR__ . '/../controllers/ProductoCartaController.php';

$request_uri = $_SERVER['REQUEST_URI'];
$request_method = $_SERVER['REQUEST_METHOD'];

if ($request_method == 'GET' && $request_uri === '/api/comedores/producto_carta/obtenerTodos') {
    ProductoCartaController::index();
    exit;
} else if ($request_method == 'GET' && preg_match('/\/api\/comedores\/producto_carta\/obtenerPorId\/(\d+)/', $request_uri, $matches)) {
    $id = $matches[1];
    ProductoCartaController::show($id);
    exit;
} else if ($request_method == 'GET' && $request_uri === '/api/comedores/producto_carta/obtenerPlatillos') {
    ProductoCartaController::obtenerPlatillos();
    exit;
} else if ($request_method == 'GET' && $request_uri === '/api/comedores/producto_carta/obtenerSandwichesTortas') {
    ProductoCartaController::obtenerSandwichesTortas();
    exit;
} else if ($request_method == 'GET' && $request_uri === '/api/comedores/producto_carta/obtenerSugerenciaChef') {
    ProductoCartaController::obtenerSugerenciaChef();
    exit;
} else if ($request_method == 'GET' && $request_uri === '/api/comedores/producto_carta/obtenerPostres') {
    ProductoCartaController::obtenerPostres();
    exit;
} else if ($request_method == 'GET' && $request_uri === '/api/comedores/producto_carta/obtenerBebidas') {
    ProductoCartaController::obtenerBebidas();
    exit;
} else if ($request_method == 'GET' && $request_uri === '/api/comedores/producto_carta/obtenerIngredienteExtra') {
    ProductoCartaController::obtenerIngredienteExtra();
    exit;
} else if ($request_method == 'GET' && $request_uri === '/api/comedores/producto_carta/obtenerOtros') {
    ProductoCartaController::obtenerOtros();
    exit;
} else if ($request_method == 'POST' && $request_uri === '/api/comedores/producto_carta/crear') {
    ProductoCartaController::crear();
    exit;
} else if ($request_method == 'PUT' && $request_uri === '/api/comedores/producto_carta/modificar') {
    ProductoCartaController::modificar();
    exit;
} else if ($request_method == 'DELETE' && preg_match('/\/api\/comedores\/producto_carta\/eliminar\/(\d+)/', $request_uri, $matches)) {
    $id = $matches[1];
    ProductoCartaController::eliminar($id);
    exit;
}
?>