<?php
require_once __DIR__ .'/../services/ProductoCartaService.php';
require_once __DIR__ . '/../../../handler/XmlHandler.php';

class ProductoCartaController {
    public static function index() {
        $ProductoCarta = ProductoCartaService::obtenerTodosProductoCarta();
        header('Content-Type: application/xml');

        echo XmlHandler::generarXML($ProductoCarta, 'producto_carta', 'producto');
    }

    public static function show($id) {
        $productoCarta = ProductoCartaService::obtenerProductoCartaPorId($id);
        header('Content-Type: application/xml');

        if(!$productoCarta) {
            header("HTTP/1.1 404 Not Found");
            echo "<error>Producto no encontrado</error>";
        }
        echo XmlHandler::generarXML($productoCarta, 'producto_carta', 'producto');
    }

    public static function obtenerPlatillos() {
        $productoCarta = ProductoCartaService::obtenerPlatillos();
        header('Content-Type: application/xml'); 

        echo XmlHandler::generarXML($productoCarta, 'platillos', 'platillo');
    }

    public static function obtenerSandwichesTortas() {
        $productoCarta = ProductoCartaService::obtenerSandwichesTortas();
        header('Content-Type: application/xml'); 

        echo XmlHandler::generarXML($productoCarta, 'sandwiches_tortas', 'sandwich_torta');
    }

    public static function obtenerSugerenciaChef() {
        $productoCarta = ProductoCartaService::obtenerSugerenciaChef();
        header('Content-Type: application/xml'); 

        echo XmlHandler::generarXML($productoCarta, 'sugerencia_chef', 'sugerencia');
    }

    public static function obtenerPostres() {
        $productoCarta = ProductoCartaService::obtenerPostres();
        header('Content-Type: application/xml'); 

        echo XmlHandler::generarXML($productoCarta, 'postres', 'postre');
    }

    public static function obtenerBebidas() {
        $productoCarta = ProductoCartaService::obtenerBebidas();
        header('Content-Type: application/xml'); 

        echo XmlHandler::generarXML($productoCarta, 'bebidas', 'bebida');
    }

    public static function obtenerIngredienteExtra() {
        $productoCarta = ProductoCartaService::obtenerIngredienteExtra();
        header('Content-Type: application/xml'); 

        echo XmlHandler::generarXML($productoCarta, 'ingredientes_extra', 'ingrediente_extra');
    }

    public static function obtenerOtros() {
        $productoCarta = ProductoCartaService::obtenerOtros();
        header('Content-Type: application/xml'); 

        echo XmlHandler::generarXML($productoCarta, 'otros', 'otro');
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
        if (!isset($datos['nombre']) || !isset($datos['precio']) || 
            !isset($datos['id_categoria']) || !isset($datos['img_url'])) {
            header("HTTP/1.1 400 Bad Request");
            header('Content-Type: application/xml');
            echo "<e>Faltan datos requeridos</e>";
            return;
        }
        
        // Validar que la categoría exista (1-7)
        if (!is_numeric($datos['id_categoria']) || intval($datos['id_categoria']) < 1 || intval($datos['id_categoria']) > 7) {
            header("HTTP/1.1 400 Bad Request");
            header('Content-Type: application/xml');
            echo "<e>La categoría debe estar entre 1 y 7</e>";
            return;
        }
        
        // Validar que el precio sea un número válido
        if (!is_numeric($datos['precio']) || floatval($datos['precio']) <= 0) {
            header("HTTP/1.1 400 Bad Request");
            header('Content-Type: application/xml');
            echo "<e>El precio debe ser un número positivo</e>";
            return;
        }
        
        // Asegurar que id_categoria sea un entero
        $datos['id_categoria'] = intval($datos['id_categoria']);
        
        // Crear el producto de carta
        $resultado = ProductoCartaService::crearProductoCarta($datos);
        
        header('Content-Type: application/xml');
        if (isset($resultado['error'])) {
            header("HTTP/1.1 500 Internal Server Error");
            echo "<e>" . $resultado['error'] . "</e>";
        } else {
            echo XmlHandler::generarXML($resultado, 'respuesta');
        }
    }
}
?>