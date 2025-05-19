<?php
require_once __DIR__ . '/../models/Comentarios.php';

class ComentariosService {
    public static function obtenerComentarios($id) {
        return Comentarios::obtenerPorId($id);
    }
    
    public static function crearComentario($datos) {
        return Comentarios::crear($datos);
    }
}
?>