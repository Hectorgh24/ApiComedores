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

}
?>