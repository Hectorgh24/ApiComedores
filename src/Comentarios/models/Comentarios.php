<?php
require_once __DIR__ . '/../../config/conexion.php';

class Comentarios {

    public static function obtenerPorId($id) {
        global $conn;
        $query = "SELECT * FROM comentarios WHERE id_desayuno_comida = ? OR WHERE id_producto_carta = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
}
?>