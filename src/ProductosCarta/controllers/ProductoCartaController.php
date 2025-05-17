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
}
?>