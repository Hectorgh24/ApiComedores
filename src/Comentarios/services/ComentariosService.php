<?php
require_once __DIR__ . '/../models/Comentarios.php';

class ComentariosService {
    public static function obtenerComentarios($id) {
        return Comentarios::obtenerPorId($id);
    }
}

?>