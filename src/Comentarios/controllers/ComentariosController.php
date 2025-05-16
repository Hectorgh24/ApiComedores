<?php
require_once __DIR__ . '/../services/ComentariosService.php';
require_once __DIR__ . '/../../../handler/XmlHandler.php';

class ComentariosController {
    public static function index($id) {
        ob_start();
        $comentarios = ComentariosService::obtenerComentarios($id);
        header('Content-Type: application/xml');

        if(!$comentarios) {
            header("HTTP/1.1 404 Not Found");
            echo "<error>Comentarios no encontrados</error>";
        }
        echo XmlHandler::generarXML($comentarios, 'comentarios', 'comentario');
        ob_end_flush(); // Envía el contenido del buffer y lo limpia
    }
}

?>