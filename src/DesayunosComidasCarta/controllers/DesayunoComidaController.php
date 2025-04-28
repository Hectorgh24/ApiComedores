<?php
require_once __DIR__ . '/../services/DesayunoComidaService.php';
require_once __DIR__ . '/../../../handler/XmlHandler.php';

class DesayunoComidaController {
    public static function index() {
        ob_start();
        $DesayunosComidas = DesayunoComidaService::obtenerTodos();
        header('Content-Type: application/xml'); 

        echo XmlHandler::generarXML($DesayunosComidas, 'desayunos_comidas', 'desayuno_comida');
        ob_end_flush(); // Envía el contenido del buffer y lo limpia
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
        ob_start();
        $DesayunosComidas = DesayunoComidaService::obtenerDesayunos();
        header('Content-Type: application/xml'); 

        echo XmlHandler::generarXML($DesayunosComidas, 'desayunos', 'desayuno');
        ob_end_flush(); // Envía el contenido del buffer y lo limpia
    }

    public static function obtenerComidas() {
        ob_start();
        $DesayunosComidas = DesayunoComidaService::obtenerComidas();
        header('Content-Type: application/xml'); 

        echo XmlHandler::generarXML($DesayunosComidas, 'comidas', 'comida');
        ob_end_flush(); // Envía el contenido del buffer y lo limpia
    }

    public static function obtenerInformacionNutrimentales() {
        ob_start();
        $DesayunosComidas = DesayunoComidaService::obtenerInformacionNutrimentales();
        header('Content-Type: application/xml'); 

        echo XmlHandler::generarXML($DesayunosComidas, 'informacion_nutrimental', 'nutrimento');
        ob_end_flush(); // Envía el contenido del buffer y lo limpia
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