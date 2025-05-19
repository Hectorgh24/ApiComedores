<?php
require_once __DIR__ . '/../services/ComentariosService.php';
require_once __DIR__ . '/../../../handler/XmlHandler.php';

class ComentariosController {
    public static function index($id) {
        $comentarios = ComentariosService::obtenerComentarios($id);
        header('Content-Type: application/xml');

        if(!$comentarios) {
            header("HTTP/1.1 404 Not Found");
            echo "<e>Comentarios no encontrados</e>";
        }
        echo XmlHandler::generarXML($comentarios, 'comentarios', 'comentario');
    }
    
    public static function crear() {
        // Verificar que sea una solicitud POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("HTTP/1.1 405 Method Not Allowed");
            header('Content-Type: application/xml');
            echo "<e>Método no permitido</e>";
            return;
        }
        
        // Obtener los datos enviados
        $datosPost = file_get_contents('php://input');
        $xml = simplexml_load_string($datosPost);
        
        if ($xml === false) {
            header("HTTP/1.1 400 Bad Request");
            header('Content-Type: application/xml');
            echo "<e>XML inválido</e>";
            return;
        }
        
        // Convertir XML a array
        $datos = json_decode(json_encode($xml), true);
        
        // Validar datos requeridos
        if ((!isset($datos['id_desayuno_comida']) && !isset($datos['id_producto_carta'])) || 
            !isset($datos['sabor']) || !isset($datos['porcion']) || 
            !isset($datos['precio']) || !isset($datos['satisfaccion_general'])) {
            header("HTTP/1.1 400 Bad Request");
            header('Content-Type: application/xml');
            echo "<e>Faltan datos requeridos</e>";
            return;
        }
        
        // Validar que solo uno de los campos id_desayuno_comida o id_producto_carta tenga valor
        if (isset($datos['id_desayuno_comida']) && isset($datos['id_producto_carta'])) {
            header("HTTP/1.1 400 Bad Request");
            header('Content-Type: application/xml');
            echo "<e>Solo debe proporcionarse id_desayuno_comida o id_producto_carta, no ambos</e>";
            return;
        }
        
        // Validar rangos de calificación (1-5)
        if ($datos['sabor'] < 1 || $datos['sabor'] > 5 ||
            $datos['porcion'] < 1 || $datos['porcion'] > 5 ||
            $datos['precio'] < 1 || $datos['precio'] > 5 ||
            $datos['satisfaccion_general'] < 1 || $datos['satisfaccion_general'] > 5) {
            header("HTTP/1.1 400 Bad Request");
            header('Content-Type: application/xml');
            echo "<e>Las calificaciones deben estar en el rango de 1 a 5</e>";
            return;
        }
        
        // Crear el comentario
        $resultado = ComentariosService::crearComentario($datos);
        
        header('Content-Type: application/xml');
        if (isset($resultado['error'])) {
            header("HTTP/1.1 500 Internal Server Error");
            echo "<e>" . $resultado['error'] . "</e>";
        } else {
            echo XmlHandler::generarXML($resultado, 'respuesta');
        }
    }
}
?>