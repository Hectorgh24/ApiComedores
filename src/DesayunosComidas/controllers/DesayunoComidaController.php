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
}
?>