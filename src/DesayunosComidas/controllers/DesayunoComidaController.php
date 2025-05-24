<?php
require_once __DIR__ . '/../services/DesayunoComidaService.php';
require_once __DIR__ . '/../../../handler/XmlHandler.php';

class DesayunoComidaController {

    public static function obtenerPorFechaDesayuno($fecha) {
        $DesayunosComidas = DesayunoComidaService::obtenerPorFechaDesayuno($fecha);
        header('Content-Type: application/xml'); 

        if(!$DesayunosComidas) {
            header("HTTP/1.1 404 Not Found");
            echo "<error>Desayuno o comida no encontrado</error>";
        }

        echo XmlHandler::generarXML($DesayunosComidas, 'desayunos_comidas', 'desayuno_comida');
    }

    public static function obtenerPorFechaComida($fecha) {
        $DesayunosComidas = DesayunoComidaService::obtenerPorFechaComida($fecha);
        header('Content-Type: application/xml'); 

        if(!$DesayunosComidas) {
            header("HTTP/1.1 404 Not Found");
            echo "<error>Desayuno o comida no encontrado</error>";
        }

        echo XmlHandler::generarXML($DesayunosComidas, 'desayunos_comidas', 'desayuno_comida');
    }

    public static function index() {
        $DesayunosComidas = DesayunoComidaService::obtenerTodos();
        header('Content-Type: application/xml'); 

        echo XmlHandler::generarXML($DesayunosComidas, 'desayunos_comidas', 'desayuno_comida');
    }

    public static function show ($id) {
        $DesayunoComidas = DesayunoComidaService::obtenerPorId($id);
        header('Content-Type: application/xml');

        if(!$DesayunoComidas) {
            header("HTTP/1.1 404 Not Found");
            echo "<error>Desayuno o comida no encontrado</error>";
        }

        echo XmlHandler::generarXML($DesayunoComidas, 'desayunos_comidas', 'desayuno_comida');
    }

    public static function obtenerDesayunos() {
        $DesayunosComidas = DesayunoComidaService::obtenerDesayunos();
        header('Content-Type: application/xml'); 

        echo XmlHandler::generarXML($DesayunosComidas, 'desayunos', 'desayuno');
    }

    public static function obtenerComidas() {
        $DesayunosComidas = DesayunoComidaService::obtenerComidas();
        header('Content-Type: application/xml'); 

        echo XmlHandler::generarXML($DesayunosComidas, 'comidas', 'comida');
    }

    public static function obtenerInformacionNutrimentales() {
        $DesayunosComidas = DesayunoComidaService::obtenerInformacionNutrimentales();
        header('Content-Type: application/xml'); 

        echo XmlHandler::generarXML($DesayunosComidas, 'informacion_nutrimental', 'nutrimento');
    }

    public static function obtenerInformacionNutrimentalPorId($id) {
        $DesayunosComidas = DesayunoComidaService::obtenerInformacionNutrimentalPorId($id);
        header('Content-Type: application/xml');

        if(!$DesayunosComidas) {
            header("HTTP/1.1 404 Not Found");
            echo "<error>Desayuno o comida no encontrado</error>";
        }
            
        echo XmlHandler::generarXML($DesayunosComidas, 'informacion_nutrimental', 'nutrimento');
    }

    public static function obtenerInformacionNutrimentalDesayunoPorFecha($fecha) {
        $DesayunosComidas = DesayunoComidaService::obtenerInformacionNutrimentalDesayunoPorFecha($fecha);
        header('Content-Type: application/xml'); 

        if(!$DesayunosComidas) {
            header("HTTP/1.1 404 Not Found");
            echo "<error>Desayuno o comida no encontrado</error>";
        }

        echo XmlHandler::generarXML($DesayunosComidas, 'informacion_nutrimental', 'nutrimento');
    }

    public static function obtenerInformacionNutrimentalComidaPorFecha($fecha) {
        $DesayunosComidas = DesayunoComidaService::obtenerInformacionNutrimentalComidaPorFecha($fecha);
        header('Content-Type: application/xml'); 

        if(!$DesayunosComidas) {
            header("HTTP/1.1 404 Not Found");
            echo "<error>Desayuno o comida no encontrado</error>";
        }

        echo XmlHandler::generarXML($DesayunosComidas, 'informacion_nutrimental', 'nutrimento');
    }

    public static function crear() {
        // Verificar que sea una solicitud POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("HTTP/1.1 405 Method Not Allowed");
            header('Content-Type: application/xml');
            echo "<e>Método no permitido</e>";
            return;
        }
        
        // Obtener los datos enviados
        $datosPost = file_get_contents('php://input');
        $xml = simplexml_load_string($datosPost);
        
        if ($xml === false) {
            header("HTTP/1.1 400 Bad Request");
            header('Content-Type: application/xml');
            echo "<e>XML inválido</e>";
            return;
        }
        
        // Convertir XML a array
        $datos = json_decode(json_encode($xml), true);
        
        // Validar datos requeridos
        if (!isset($datos['tipo']) || !isset($datos['fecha']) || 
            !isset($datos['descripcion']) || !isset($datos['img_url'])) {
            header("HTTP/1.1 400 Bad Request");
            header('Content-Type: application/xml');
            echo "<e>Faltan datos requeridos</e>";
            return;
        }
        
        // Validar tipo
        if ($datos['tipo'] != 'desayuno' && $datos['tipo'] != 'comida') {
            header("HTTP/1.1 400 Bad Request");
            header('Content-Type: application/xml');
            echo "<e>El tipo debe ser 'desayuno' o 'comida'</e>";
            return;
        }
        
        // Validar formato de fecha (YYYY-MM-DD)
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $datos['fecha'])) {
            header("HTTP/1.1 400 Bad Request");
            header('Content-Type: application/xml');
            echo "<e>El formato de fecha debe ser YYYY-MM-DD</e>";
            return;
        }
        
        // Crear el desayuno/comida
        $resultado = DesayunoComidaService::crearDesayunoComida($datos);
        
        header('Content-Type: application/xml');
        if (isset($resultado['error'])) {
            header("HTTP/1.1 500 Internal Server Error");
            echo "<e>" . $resultado['error'] . "</e>";
        } else {
            echo XmlHandler::generarXML($resultado, 'respuesta');
        }
    }

    public static function crearInformacionNutrimental() {
        // Verificar que sea una solicitud POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("HTTP/1.1 405 Method Not Allowed");
            header('Content-Type: application/xml');
            echo "<e>Método no permitido</e>";
            return;
        }
        
        // Obtener los datos enviados
        $datosPost = file_get_contents('php://input');
        $xml = simplexml_load_string($datosPost);
        
        if ($xml === false) {
            header("HTTP/1.1 400 Bad Request");
            header('Content-Type: application/xml');
            echo "<e>XML inválido</e>";
            return;
        }
        
        // Convertir XML a array
        $datos = json_decode(json_encode($xml), true);
        
        // Validar datos requeridos
        if (!isset($datos['id_desayuno_comida']) || !isset($datos['kcal']) || 
            !isset($datos['hc']) || !isset($datos['p']) || !isset($datos['l'])) {
            header("HTTP/1.1 400 Bad Request");
            header('Content-Type: application/xml');
            echo "<e>Faltan datos requeridos</e>";
            return;
        }
        
        // Validar que los valores sean enteros positivos
        if (!is_numeric($datos['id_desayuno_comida']) || intval($datos['id_desayuno_comida']) <= 0 ||
            !is_numeric($datos['kcal']) || intval($datos['kcal']) < 0 ||
            !is_numeric($datos['hc']) || intval($datos['hc']) < 0 ||
            !is_numeric($datos['p']) || intval($datos['p']) < 0 ||
            !is_numeric($datos['l']) || intval($datos['l']) < 0) {
            header("HTTP/1.1 400 Bad Request");
            header('Content-Type: application/xml');
            echo "<e>Los valores nutricionales deben ser números enteros positivos y el id_desayuno_comida debe ser un entero válido</e>";
            return;
        }
        
        // Convertir valores a enteros
        $datos['kcal'] = intval($datos['kcal']);
        $datos['hc'] = intval($datos['hc']);
        $datos['p'] = intval($datos['p']);
        $datos['l'] = intval($datos['l']);
        $datos['id_desayuno_comida'] = intval($datos['id_desayuno_comida']);
        
        // Crear la información nutrimental
        $resultado = DesayunoComidaService::crearInformacionNutrimental($datos);
        
        header('Content-Type: application/xml');
        if (isset($resultado['error'])) {
            header("HTTP/1.1 500 Internal Server Error");
            echo "<e>" . $resultado['error'] . "</e>";
        } else {
            echo XmlHandler::generarXML($resultado, 'respuesta');
        }
    }

    public static function eliminar($id) {
        header('Content-Type: application/xml; charset=utf-8');

        // Es una solicitud DELETE?
        if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
            header("HTTP/1.1 405 Method Not Allowed");
            echo "<e>Método no permitido</e>";
            return;
        }

        // Eliminar el desayuno/comida
        $resultado = DesayunoComidaService::eliminar($id);

        // Verificar si hubo un error
        if ($resultado['estado']  === 'false') {
            header("HTTP/1.1 400 Bad Request");
            echo "<e>{$resultado['mensaje']}</e>";
        } else {
            echo XmlHandler::generarXML($resultado, 'respuesta');
        }
        return;
    }

    public static function modificar() {
        header('Content-Type: application/xml; charset=utf-8');

        // Es una petición PUT?
        if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
            header("HTTP/1.1 405 Method Not Allowed");
            echo "<e>Método no permitido</e>";
            return;
        }

        // Obtener los datos del body
        $datosPost = file_get_contents('php://input');

        // Convertir datos en un objeto xml
        $xml = simplexml_load_string($datosPost);

        // Verifica si el xml parseo con exito
        if ($xml === false) {
            header("HTTP/1.1 400 Bad Request");
            echo "<e>XML inválido</e>";
            return;
        }

        // Convierte datos xml en array asociativo
        $datos = json_decode(json_encode($xml), true);

        // Validar datos requeridos
        if (!isset($datos['tipo']) || !isset($datos['fecha']) ||
            !isset($datos['descripcion']) || !isset($datos['img_url'])) {
            header("HTTP/1.1 400 Bad Request");
            echo "<e>Faltan datos requeridos</e>";
            return;
        }

        // Validar formato de fecha (YYYY-MM-DD)
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $datos['fecha'])) {
            header("HTTP/1.1 400 Bad Request");
            echo "<e>El formato de fecha debe ser YYYY-MM-DD</e>";
            return;
        }

        // Modificar el deayuno/comida
        $resultado = DesayunoComidaService::modificar($datos);

        // Verificar si hubo un error
        if($resultado['estado'] === 'false') {
            header("HTTP/1.1 400 Bad Request");
            echo "<e>{$resultado['mensaje']}</e>";
        } else {
            echo XmlHandler::generarXML($resultado, 'respuesta');
        }
        return;

    }
}
?>