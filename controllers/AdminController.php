<?php

namespace Controllers;

use Model\AdminCita;
use MVC\Router;

class AdminController
{
    public static function index(Router $router)
    {

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        isAdmin();

        $fecha = $_GET['fecha'] ?? date('Y-m-d');

        $desestructurar = explode('-', $fecha);

        $verificarFecha = checkdate($desestructurar[1], $desestructurar[2], $desestructurar[0] );

        if(!$verificarFecha){
            header('Location: /404');
        }

        $consulta = "SELECT citas.id, citas.hora, CONCAT( usuarios.nombre, ' ', usuarios.apellido) as cliente, ";
        $consulta .= " usuarios.email, usuarios.telefono, servicios.nombre as servicio, servicios.precio  ";
        $consulta .= " FROM citas  ";
        $consulta .= " LEFT OUTER JOIN usuarios ";
        $consulta .= " ON citas.usuarioId=usuarios.id  ";
        $consulta .= " LEFT OUTER JOIN citasServicios ";
        $consulta .= " ON citasServicios.citaId=citas.id ";
        $consulta .= " LEFT OUTER JOIN servicios ";
        $consulta .= " ON servicios.id=citasServicios.servicioId ";
        $consulta .= " WHERE fecha =  '{$fecha}' ";

        $citas = AdminCita::SQL($consulta);

        $router->render('admin/index', [
            'nombre' => $_SESSION['nombre'],
            'citas' => $citas,
            'fechaActual' => $fecha,
            'cantCitas' => count($citas)
        ]);
    }
}
