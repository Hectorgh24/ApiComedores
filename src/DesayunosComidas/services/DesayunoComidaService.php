<?php
require_once __DIR__ . '/../models/DesayunoComida.php';

class DesayunoComidaService {

    public static function obtenerPorFechaDesayuno($fecha) {
        return DesayunoComida::obtenerPorFechaDesayuno($fecha);
    }

    public static function obtenerPorFechaComida($fecha) {
        return DesayunoComida::obtenerPorFechaComida($fecha);
    }

    public static function obtenerTodos() {
        return DesayunoComida::obtenerTodos();
    }

    public static function obtenerPorId($id) {
        return DesayunoComida::obtenerPorId($id);
    }

    public static function obtenerDesayunos() {
        return DesayunoComida::obtenerDesayunos();
    }

    public static function obtenerComidas() {
        return DesayunoComida::obtenerComidas();
    }

    public static function obtenerInformacionNutrimentales() {
        return DesayunoComida::obtenerInformacionNutrimenteal();
    }

    public static function obtenerInformacionNutrimentalPorId($id) {
        return DesayunoComida::obtenerInformacionNutrimentalPorId($id);
    }
   
}
?>