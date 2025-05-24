<?php
require_once __DIR__ .'../../models/ProductoCarta.php';

class ProductoCartaService {
    public static function obtenerTodosProductoCarta() {
        return ProductoCarta::obtenerTodos();
    }

    public static function obtenerProductoCartaPorId($id) {
        return ProductoCarta::obtenerPorId($id);
    }

    public static function obtenerPlatillos() {
        return ProductoCarta::obtenerPlatillos();
    }

    public static function obtenerSandwichesTortas() {
        return ProductoCarta::obtenerSandwichesTortas();
    }

    public static function obtenerSugerenciaChef() {
        return ProductoCarta::obtenerSugerenciaChef();
    }

    public static function obtenerPostres() {
        return ProductoCarta::obtenerPostres();
    }

    public static function obtenerBebidas() {
        return ProductoCarta::obtenerBebidas();
    }

    public static function obtenerIngredienteExtra() {
        return ProductoCarta::obtenerIngredienteExtra();
    }

    public static function obtenerOtros() {
        return ProductoCarta::obtenerOtros();
    }
    
    public static function crearProductoCarta($datos) {
        return ProductoCarta::crear($datos);
    }

    public static function modificar($datos) {
        return ProductoCarta::modificar($datos);
    }

    public static function eliminar($id) {
        return ProductoCarta::eliminar($id);
    }
}
?>