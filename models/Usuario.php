<?php

namespace Model;

use Model\ActiveRecord;

class Usuario extends ActiveRecord{
    // mi tabla de base de datos
    protected static $tabla  = 'usuarios';

    // mis columnas
    protected static $columnasDB = ['id', 'nombre', 'apellido', 'email', 'password', 'telefono', 'admin', 'confirmado', 'token'];

    public $id;
    public $nombre;
    public $apellido;
    public $email;
    public $password;
    public $telefono;
    public $admin;
    public $confirmado;
    public $token;

    public function __construct($args = []){
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->apellido = $args['apellido'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->telefono = $args['telefono'] ?? '';
        $this->admin = $args['admin'] ?? '0';
        $this->confirmado = $args['confirmado'] ?? '0';
        $this->token = $args['token'] ?? '';
    }

    // Mensajes de validación
    public function validarNuevaCuenta(){
        if(!$this->nombre){
            self::$alertas['error'][] =  'El Nombre es obligatorio';
        }
        if(!$this->apellido){
            self::$alertas['error'][] =  'El Apellido es obligatorio';
        }
        if(!$this->email){
            self::$alertas['error'][] =  'El Email es obligatorio';
        }
        if(!$this->telefono){
            self::$alertas['error'][] =  'El Telefono es obligatorio';
        }
        if(!$this->password){
            self::$alertas['error'][] =  'El Password es obligatorio';
        }
        if(strlen($this->password)<6){
            self::$alertas['error'][] =  'El Password debe tener más de 6 caracteres';
        }
        return self::$alertas;
    }

    public function validarLogin(){
        if(!$this->email){
            self::$alertas['error'][] =  'El Email es obligatorio';
        }
        if(!$this->password){
            self::$alertas['error'][] =  'El Password es obligatorio';
        }
        return self::$alertas;
    }

    public function validarPassword(){
        if(!$this->password){
            self::$alertas['error'][] =  'El Password es obligatorio';
        }
        if(strlen($this->password)<6){
            self::$alertas['error'][] =  'El password debe contener minimo 6 caracteres';
        }
        return self::$alertas;
    }

    public function validarEmailReset(){
        if(!$this->email){
            self::$alertas['error'][] =  'El Email es obligatorio';
        }
        return self::$alertas;
    }

    public function existeUsuario(){
        $query = " SELECT * FROM " . self::$tabla . " WHERE email = '" . $this->email . "' LIMIT 1";
        $resultado = self::$db->query($query);
        if($resultado->num_rows){
            self::$alertas['error'][] = 'El usuario ya está registrado';
        }
        return $resultado;
    }
    // desestructura el objeto, usa this password y lo regresa
    public function hashearPassword(){
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }
    public function generarToken(){
        $this->token = uniqid();
    }
    public function confirmaActualizaToken() {
        $this->confirmado = "1";
        $this->token = '';
    }

    // usa el password del Usuario que escribió y lo compara con el objeto con el que se pasó, $this para el objeto actual
    // ! te arroja false si es 0 lo que contiene
    public function comprobarPasswordAndVerificado($password){
        $resultado = password_verify($password, $this->password);
        // si !this es 
        // !$resultado si es false
        // $resultado si es true
        if($resultado && $this->confirmado == 1){
            return true;
        } else {
            // debuggear("usuario no confirmado o contra incorrecta");
            self::$alertas['error'][] = "Contraseña incorrecta o usuario no confirmado";
        }
        
    }
}