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
    
    public static function crear($datos) {
        global $conn;
        $query = "INSERT INTO comentario (id_desayuno_comida, id_producto_carta, sabor, porcion, precio, satisfaccion_general) 
                 VALUES (?, ?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($query);
        $idDesayunoComida = isset($datos['id_desayuno_comida']) ? $datos['id_desayuno_comida'] : null;
        $idProductoCarta = isset($datos['id_producto_carta']) ? $datos['id_producto_carta'] : null;
        
        $stmt->bind_param("iiiiii", 
            $idDesayunoComida, 
            $idProductoCarta, 
            $datos['sabor'], 
            $datos['porcion'],
            $datos['precio'],
            $datos['satisfaccion_general']
        );
        
        if ($stmt->execute()) {
            $nuevoId = $conn->insert_id;
            return ["id" => $nuevoId, "mensaje" => "Comentario creado correctamente"];
        } else {
            return ["error" => "Error al crear el comentario: " . $conn->error];
        }
    }
}
?>