<?php

namespace Controllers;

use Classes\Email;
use Model\Servicio;
use Model\Usuario;
use MVC\Router;

class ServicioController
{

    public static function index(Router $router)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        isAdmin();
        $rst = $_GET['rst'] ?? null;
        $servicios = Servicio::all();

        $router->render('servicios/index', [
            'nombre' => $_SESSION['nombre'],
            'rst' => $rst,
            'servicios' => $servicios
        ]);
    }
    public static function crear(Router $router)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        isAdmin();
        $servicio = new Servicio();
        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $servicio->sincronizar($_POST);
            $alertas = $servicio->validarServicio();

            if (empty($alertas)) {
                $servicio->guardar();
                header('Location: /servicios?rst=1');
            }
        }
        $router->render('servicios/crear', [
            'nombre' => $_SESSION['nombre'],
            'servicio' => $servicio,
            'alertas' => $alertas
        ]);
    }
    public static function actualizar(Router $router)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        isAdmin();

        $id = validarIdGET('/servicios');
        $servicio = Servicio::find($id);
        $alertas = [];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $servicio->sincronizar($_POST);
            $alertas = $servicio->validarServicio();
            
            if (empty($alertas)) {
                $servicio->guardar();
                header('Location: /servicios?rst=2');
            }
        }
        $router->render('servicios/actualizar', [
            'nombre' => $_SESSION['nombre'],
            'servicio' => $servicio,
            'alertas' => $alertas
        ]);
    }
    public static function eliminar()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        isAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = validarIdPOST('/servicios');
            $servicio = Servicio::find($id);
            $servicio->eliminar();
            header('Location: /servicios?rst=3');
        }
    }
}
