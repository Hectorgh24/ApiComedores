<?php
require_once __DIR__ . '/../controllers/DesayunoComidaController.php';

$request_uri = $_SERVER['REQUEST_URI'];
$request_method = $_SERVER['REQUEST_METHOD'];

if ($request_method == 'GET' && $request_uri === '/api/comedores/desayunos_comidas/obtenerTodos') {
    DesayunoComidaController::index();
    exit;
} else if ($request_method == 'GET' && preg_match('/\/api\/comedores\/desayunos_comidas\/obtenerPorId\/(\d+)/', $request_uri, $matches)) {
    $id = $matches[1];
    DesayunoComidaController::show($id);
    exit;
} else if ($request_method == 'GET' && $request_uri === '/api/comedores/desayunos/obtenerDesayunos') {
    DesayunoComidaController::obtenerDesayunos();
    exit;
} else if ($request_method == 'GET' && $request_uri === '/api/comedores/comidas/obtenerComidas') {
    DesayunoComidaController::obtenerComidas();
    exit;
} else if ($request_method == 'GET' && $request_uri === '/api/comedores/informacionNutrimental/obtenerInformacionNutrimental') {
    DesayunoComidaController::obtenerInformacionNutrimentales();
    exit;
} else if ($request_method == 'GET' && preg_match('/\/api\/comedores\/informacionNutrimental\/obtenerInformacionNutrimentalPorId\/(\d+)/', $request_uri, $matches)) {
    $id = $matches[1];
    DesayunoComidaController::obtenerInformacionNutrimentalPorId($id);
    exit;
} else if ($request_method == 'GET' && preg_match('/\/api\/comedores\/desayunos_comidas\/obtenerPorFechaDesayuno\/(\d{4}-\d{2}-\d{2})/', $request_uri, $matches)) {
    $fecha = $matches[1];
    DesayunoComidaController::obtenerPorFechaDesayuno($fecha);
    exit;
}  else if ($request_method == 'GET' && preg_match('/\/api\/comedores\/desayunos_comidas\/obtenerPorFechaComida\/(\d{4}-\d{2}-\d{2})/', $request_uri, $matches)) {
    $fecha = $matches[1];
    DesayunoComidaController::obtenerPorFechaComida($fecha);
    exit;
} else if ($request_method == 'GET' && preg_match('/\/api\/comedores\/informacionNutrimental\/obtenerInformacionNutrimentalDesayunoPorFecha\/(\d{4}-\d{2}-\d{2})/', $request_uri, $matches)) {
    $fecha = $matches[1];
    DesayunoComidaController::obtenerInformacionNutrimentalDesayunoPorFecha($fecha);
    exit;
} else if ($request_method == 'GET' && preg_match('/\/api\/comedores\/informacionNutrimental\/obtenerInformacionNutrimentalComidaPorFecha\/(\d{4}-\d{2}-\d{2})/', $request_uri, $matches)) {
    $fecha = $matches[1];
    DesayunoComidaController::obtenerInformacionNutrimentalComidaPorFecha($fecha);
    exit;
} else if ($request_method == 'POST' && $request_uri === '/api/comedores/desayunos_comidas/crear') {
    DesayunoComidaController::crear();
    exit;
} else if ($request_method == 'POST' && $request_uri === '/api/comedores/informacionNutrimental/crear') {
    DesayunoComidaController::crearInformacionNutrimental();
    exit;
} else if ($request_method == 'DELETE' && preg_match('/\/api\/comedores\/desayunos_comidas\/eliminar\/(\d+)/', $request_uri, $matches)) {
    $id = $matches[1];
    DesayunoComidaController::eliminar($id);
    exit;
} else if ($request_method == 'PUT' && $request_uri === '/api/comedores/desayunos_comidas/modificar') {
    DesayunoComidaController::modificar();
    exit;
}
?>