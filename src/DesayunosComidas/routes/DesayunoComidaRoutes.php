<?php
require_once __DIR__ . '/../controllers/DesayunoComidaController.php';

$request_uri = $_SERVER['REQUEST_URI'];
$request_method = $_SERVER['REQUEST_METHOD'];

if ($request_method == 'GET' && $request_uri === '/api/comedores/desayunos_comidas/obtenerTodos') {
    DesayunoComidaController::index();
} else if ($request_method == 'GET' && preg_match('/\/api\/comedores\/desayunos_comidas\/obtenerPorId\/(\d+)/', $request_uri, $matches)) {
    $id = $matches[1];
    DesayunoComidaController::show($id);
} else if ($request_method == 'GET' && $request_uri === '/api/comedores/desayunos/obtenerDesayunos') {
    DesayunoComidaController::obtenerDesayunos();
} else if ($request_method == 'GET' && $request_uri === '/api/comedores/comidas/obtenerComidas') {
    DesayunoComidaController::obtenerComidas();
} else if ($request_method == 'GET' && $request_uri === '/api/comedores/informacionNutrimental/obtenerInformacionNutrimental') {
    DesayunoComidaController::obtenerInformacionNutrimentales();
} else if ($request_method == 'GET' && preg_match('/\/api\/comedores\/informacionNutrimental\/obtenerInformacionNutrimentalPorId\/(\d+)/', $request_uri, $matches)) {
    $id = $matches[1];
    DesayunoComidaController::obtenerInformacionNutrimentalPorId($id);
} else if ($request_method == 'GET' && preg_match('/\/api\/comedores\/desayunos_comidas\/obtenerPorFechaDesayuno\/(\d{4}-\d{2}-\d{2})/', $request_uri, $matches)) {
    $fecha = $matches[1];
    DesayunoComidaController::obtenerPorFechaDesayuno($fecha);
}  else if ($request_method == 'GET' && preg_match('/\/api\/comedores\/desayunos_comidas\/obtenerPorFechaComida\/(\d{4}-\d{2}-\d{2})/', $request_uri, $matches)) {
    $fecha = $matches[1];
    DesayunoComidaController::obtenerPorFechaComida($fecha);
}
?>