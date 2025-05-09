<?php
require_once __DIR__ .'/../services/ProductoCartaService.php';
require_once __DIR__ . '/../../../handler/XmlHandler.php';

class ProductoCartaController {
    public static function index() {
        ob_start();
        $ProductoCarta = ProductoCartaService::obtenerTodosProductoCarta();
        header('Content-Type: application/xml');

        echo XmlHandler::generarXML($ProductoCarta, 'producto_carta', 'producto');
        ob_end_flush(); // Envía el contenido del buffer y lo limpia
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
        ob_start();
        $productoCarta = ProductoCartaService::obtenerPlatillos();
        header('Content-Type: application/xml'); 

        echo XmlHandler::generarXML($productoCarta, 'platillos', 'platillo');
        ob_end_flush(); // Envía el contenido del buffer y lo limpia
    }

    public static function obtenerSandwichesTortas() {
        ob_start();
        $productoCarta = ProductoCartaService::obtenerSandwichesTortas();
        header('Content-Type: application/xml'); 

        echo XmlHandler::generarXML($productoCarta, 'sandwiches_tortas', 'sandwich_torta');
        ob_end_flush(); // Envía el contenido del buffer y lo limpia
    }

    public static function obtenerSugerenciaChef() {
        ob_start();
        $productoCarta = ProductoCartaService::obtenerSugerenciaChef();
        header('Content-Type: application/xml'); 

        echo XmlHandler::generarXML($productoCarta, 'sugerencia_chef', 'sugerencia');
        ob_end_flush(); // Envía el contenido del buffer y lo limpia
    }

    public static function obtenerPostres() {
        ob_start();
        $productoCarta = ProductoCartaService::obtenerPostres();
        header('Content-Type: application/xml'); 

        echo XmlHandler::generarXML($productoCarta, 'postres', 'postre');
        ob_end_flush(); // Envía el contenido del buffer y lo limpia
    }

    public static function obtenerBebidas() {
        ob_start();
        $productoCarta = ProductoCartaService::obtenerBebidas();
        header('Content-Type: application/xml'); 

        echo XmlHandler::generarXML($productoCarta, 'bebidas', 'bebida');
        ob_end_flush(); // Envía el contenido del buffer y lo limpia
    }

    public static function obtenerIngredienteExtra() {
        ob_start();
        $productoCarta = ProductoCartaService::obtenerIngredienteExtra();
        header('Content-Type: application/xml'); 

        echo XmlHandler::generarXML($productoCarta, 'ingredientes_extra', 'ingrediente_extra');
        ob_end_flush(); // Envía el contenido del buffer y lo limpia
    }

    public static function obtenerOtros() {
        ob_start();
        $productoCarta = ProductoCartaService::obtenerOtros();
        header('Content-Type: application/xml'); 

        echo XmlHandler::generarXML($productoCarta, 'otros', 'otro');
        ob_end_flush(); // Envía el contenido del buffer y lo limpia
   }
}
?>