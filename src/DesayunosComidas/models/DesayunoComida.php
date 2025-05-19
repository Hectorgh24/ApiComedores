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

    public static function obtenerInformacionNutrimentalDesayunoPorFecha($fecha) {
        global $conn;
        $query = "SELECT kcal,hc,p,l FROM informacion_nutrimental INNER JOIN desayuno_comida ON informacion_nutrimental.id_desayuno_comida = desayuno_comida.id
        WHERE desayuno_comida.fecha = ? AND desayuno_comida.tipo = 'desayuno'";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $fecha); // Cambiado de "d" a "s"
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public static function obtenerInformacionNutrimentalComidaPorFecha($fecha) {
        global $conn;
        $query = "SELECT kcal,hc,p,l FROM informacion_nutrimental INNER JOIN desayuno_comida ON informacion_nutrimental.id_desayuno_comida = desayuno_comida.id
        WHERE desayuno_comida.fecha = ? AND desayuno_comida.tipo = 'comida'";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $fecha); // Cambiado de "d" a "s"
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public static function crear($datos) {
        global $conn;
        $query = "INSERT INTO desayuno_comida (tipo, fecha, descripcion, img_url) 
                  VALUES (?, ?, ?, ?)";
        
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssss", 
            $datos['tipo'],
            $datos['fecha'],
            $datos['descripcion'], 
            $datos['img_url']
        );
        
        if ($stmt->execute()) {
            $nuevoId = $conn->insert_id;
            return ["id" => $nuevoId, "mensaje" => "DesayunoComida creado correctamente"];
        } else {
            return ["error" => "Error al crear el DesayunoComida: " . $conn->error];
        }
    }

    public static function crearInformacionNutrimental($datos) {
        global $conn;
        $query = "INSERT INTO informacion_nutrimental (id_desayuno_comida, kcal, hc, p, l) 
                  VALUES (?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($query);
        $stmt->bind_param("iiiii", 
            $datos['id_desayuno_comida'],
            $datos['kcal'], 
            $datos['hc'], 
            $datos['p'],
            $datos['l']
        );
        
        if ($stmt->execute()) {
            $nuevoId = $conn->insert_id;
            return ["id" => $nuevoId, "mensaje" => "Información nutricional creada correctamente"];
        } else {
            return ["error" => "Error al crear la información nutricional: " . $conn->error];
        }
    }
}
?>