<?php
require_once __DIR__ . '/../../config/conexion.php';

class ProductoCarta{
    public static function obtenerTodos() {
        global $conn;
        $query = "SELECT * FROM producto_carta";
        $result = $conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public static function obtenerPorId($id) {
        global $conn;
        $query = "SELECT * FROM producto_carta WHERE id_categoria = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public static function obtenerPlatillos() {
        global $conn;
        $query = "SELECT * FROM producto_carta WHERE id_categoria = 1";
        $result = $conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

   public static function obtenerSandwichesTortas() {
        global $conn;
        $query = "SELECT * FROM producto_carta WHERE id_categoria = 2";
        $result = $conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
   }

    public static function obtenerSugerenciaChef() {
        global $conn;
        $query = "SELECT * FROM producto_carta WHERE id_categoria = 3";
        $result = $conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public static function obtenerPostres() {
        global $conn;
        $query = "SELECT * FROM producto_carta WHERE id_categoria = 4";
        $result = $conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public static function obtenerBebidas() {
        global $conn;
        $query = "SELECT * FROM producto_carta WHERE id_categoria = 5";
        $result = $conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public static function obtenerIngredienteExtra() {
        global $conn;
        $query = "SELECT * FROM producto_carta WHERE id_categoria = 6";
        $result = $conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public static function obtenerOtros() {
        global $conn;
        $query = "SELECT * FROM producto_carta WHERE id_categoria = 7";
        $result = $conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public static function crear($datos) {
        global $conn;
        $query = "INSERT INTO producto_carta (id_categoria, nombre, descripcion, precio, img_url) 
                  VALUES (?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($query);
        $descripcion = isset($datos['descripcion']) ? $datos['descripcion'] : null;
        
        $stmt->bind_param("issds", 
            $datos['id_categoria'],
            $datos['nombre'], 
            $descripcion,
            $datos['precio'], 
            $datos['img_url']
        );
        
        if ($stmt->execute()) {
            $nuevoId = $conn->insert_id;
            return ["id" => $nuevoId, "mensaje" => "Producto de carta creado correctamente"];
        } else {
            return ["error" => "Error al crear el producto de carta: " . $conn->error];
        }
    }
}
?>