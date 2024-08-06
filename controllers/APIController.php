<?php

namespace Controllers;

use MVC\Router;
use Model\Cita;
use Model\CitasServicios;
use Model\Servicio;

class APIController
{
    public static function index()
    {
        // echo "desde api";
        $servicio = Servicio::all();
        echo json_encode($servicio);
        // echo json_encode($servicio);
    }
    public static function guardar()
    {

        // // Almacena la cita y devuelve el ID
        $cita = new Cita($_POST);
        $resultado = $cita->guardar();

        $idServicios = explode(",", $_POST['servicios']); //RECEPCIONA LOS ID DE LOS SERVICIOS

        $id = $resultado['id'];

        // almacena los servicios con el id de la cita
        foreach ($idServicios as $idServicio) :
            $args = [
                "citaId" => $id,
                'servicioId' => $idServicio
            ];
            $citaServicio = new CitasServicios($args);
            $citaServicio->guardar();
        endforeach;

        echo json_encode(['resultado' => $resultado]);
    }
    public static function eliminar()
    {
        if($_SERVER['REQUEST_METHOD']=='POST'){
            $id = validarIdPOST($_SERVER['HTTP_REFERER']);
            $cita = Cita::find($id);
            $cita->eliminar();
            header('Location:' . $_SERVER['HTTP_REFERER']);
        }
    }
}
