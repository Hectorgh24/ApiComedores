<?php
require_once __DIR__ . '/../controllers/ProductoCartaController.php';

$request_uri = $_SERVER['REQUEST_URI'];
$request_method = $_SERVER['REQUEST_METHOD'];

if ($request_method == 'GET' && $request_uri === '/api/comedores/producto_carta/obtenerTodos') {
    ProductoCartaController::index();
} else if ($request_method == 'GET' && preg_match('/\/api\/comedores\/producto_carta\/obtenerPorId\/(\d+)/', $request_uri, $matches)) {
    $id = $matches[1];
    ProductoCartaController::show($id);
} else if ($request_method == 'GET' && $request_uri === '/api/comedores/producto_carta/obtenerPlatillos') {
    ProductoCartaController::obtenerPlatillos();
} else if ($request_method == 'GET' && $request_uri === '/api/comedores/producto_carta/obtenerSandwichesTortas') {
    ProductoCartaController::obtenerSandwichesTortas();
} else if ($request_method == 'GET' && $request_uri === '/api/comedores/producto_carta/obtenerSugerenciaChef') {
    ProductoCartaController::obtenerSugerenciaChef();
} else if ($request_method == 'GET' && $request_uri === '/api/comedores/producto_carta/obtenerPostres') {
    ProductoCartaController::obtenerPostres();
} else if ($request_method == 'GET' && $request_uri === '/api/comedores/producto_carta/obtenerBebidas') {
    ProductoCartaController::obtenerBebidas();
} else if ($request_method == 'GET' && $request_uri === '/api/comedores/producto_carta/obtenerIngredienteExtra') {
    ProductoCartaController::obtenerIngredienteExtra();
} else if ($request_method == 'GET' && $request_uri === '/api/comedores/producto_carta/obtenerOtros') {
    ProductoCartaController::obtenerOtros();
} 
?>