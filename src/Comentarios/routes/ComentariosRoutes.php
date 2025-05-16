<?php
require_once __DIR__ . '/../controllers/ComentariosController.php';

$request_uri = $_SERVER['REQUEST_URI'];
$request_method = $_SERVER['REQUEST_METHOD'];

if ($request_method == 'GET' && preg_match('/\/api\/comedores\/comentarios\/obtenerComentarios\/(\d+)/', $request_uri, $matches)) {
    $id = $matches[1];
    ComentariosController::index($id);
} 

?>