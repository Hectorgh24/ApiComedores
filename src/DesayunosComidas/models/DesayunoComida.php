<?php
require_once __DIR__ . '/../../config/conexion.php';

class DesayunoComida {
    public static function obtenerTodos() {
        global $conn;
        $query = "SELECT * FROM desayuno_comida";
        $result = $conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public static function obtenerPorFechaDesayuno($fecha) {
        global $conn;
        $query = "SELECT * FROM desayuno_comida WHERE fecha = ? and tipo = 'desayuno'";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $fecha);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public static function obtenerPorFechaComida($fecha) {
        global $conn;
        $query = "SELECT * FROM desayuno_comida WHERE fecha = ? and tipo = 'comida'";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $fecha);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }


    public static function obtenerPorId($id) {
        global $conn;
        $query = "SELECT * FROM desayuno_comida WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public static function obtenerDesayunos() {
        global $conn;
        $query = "SELECT * FROM desayuno_comida WHERE tipo = 'desayuno'";
        $result = $conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public static function obtenerComidas() {
        global $conn;
        $query = "SELECT * FROM desayuno_comida WHERE tipo = 'comida'";
        $result = $conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public static function obtenerInformacionNutrimenteal() {
        global $conn;
        $query = "SELECT * FROM informacion_nutrimental";
        $result = $conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public static function obtenerInformacionNutrimentalPorId($id) {
        global $conn;
        $query = "SELECT * FROM informacion_nutrimental WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
}

?>