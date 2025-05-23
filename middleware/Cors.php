<?php
 
class Cors {
    public static function permitirOrigen() {
        header("Access-Control-Allow-Origin: *"); // Permitir cualquier origen
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE"); // Métodos permitidos
        header("Access-Control-Allow-Headers: Content-Type, Authorization"); // Encabezados permitidos

        // Manejo de solicitudes OPTIONS (preflight)
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(204); // No Content
            exit(); // Termina la ejecución para solicitudes preflight
        }
    }
}
?>