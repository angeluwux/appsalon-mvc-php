<?php

namespace Model;

use Model\ActiveRecord;

class Servicio extends ActiveRecord
{
    // bd
    protected static $tabla = "servicios";
    protected static $columnasDB = ['id', 'nombre', 'precio'];

    public $id;
    public $nombre;
    public $precio;

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->precio = $args['precio'] ?? '';
    }


    public function validarServicio()
    {
        if (!$this->nombre) {
            self::$alertas['error'][] =  'El nombre es obligatorio';
        }
        if (!$this->precio) {
            self::$alertas['error'][] =  'El precio es obligatorio';
        }
        if (!is_numeric($this->precio)) {
            self::$alertas['error'][] =  'El precio no es válido';
        }
        
        return self::$alertas;
    }
}
