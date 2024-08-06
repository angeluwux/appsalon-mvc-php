<?php

namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;

class LoginController
{
    public static function login(Router $router)
    {

        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new Usuario($_POST);
            $alertas = $auth->validarLogin();

            if (empty($alertas)) {
                // almaneca la respuesta en el resultado
                $usuario = Usuario::where('email', $auth->email);

                // buscar si hay resultado, si encuentra entra en if y si no encuentra, en else
                if ($usuario) {
                    // ese resultado usa comprobar password y le pasa como argumento lo que escribió el usuario
                    if ($usuario->comprobarPasswordAndVerificado($auth->password)) {
                        // la extension devuelve que no encuentra id porque no está declarado sus atributos, pero aun asi se puede acceder
                        session_start();
                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre . " " . $usuario->apellido;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;

                        if ($usuario->admin === '1') {
                            $_SESSION['admin'] = $usuario->admin ?? '0';
                            header('Location: /admin');
                        } else {
                            $_SESSION['admin'] = $usuario->admin ?? '0';
                            header('Location: /cita');
                        }
                    }
                } else {
                    // no encontró usuario
                    Usuario::setAlerta('error', 'El usuario no está registrado');
                }
            }
        }
        $alertas = Usuario::getAlertas();
        $router->render('auth/login', [
            'alertas' => $alertas
        ]);
    }
    public static function logout(Router $router)
    {
        session_start();
        $_SESSION = [];
        header('Location: /');
    }
    public static function olvidePassword(Router $router)
    {
        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new Usuario($_POST);
            $alertas = $auth->validarEmailReset();

            if (empty($alertas)) {
                $usuario = Usuario::where('email', $auth->email);

                if ($usuario && $usuario->confirmado == 1) {
                    $usuario->generarToken();
                    $usuario->guardar();
                    Usuario::setAlerta('exito', 'Revisa tu email');

                    // enviar Email

                    $email = new Email($usuario->nombre, $usuario->email, $usuario->token);
                    $email->enviarInstrucciones();
                } else {
                    Usuario::setAlerta('error', 'El usuario no existe o no está confirmado');
                }
            }
        }
        $alertas = Usuario::getAlertas();
        $router->render('auth/olvide-password', [
            'alertas' => $alertas
        ]);
    }
    public static function recuperar(Router $router)
    {
        $alertas = [];
        $error = "";

        $token = s($_GET['token']);
        // el buscar va fuera del method porque busca sin necesidad del click 
        // buscar usuario
        $usuario = Usuario::where('token', $token);

        // sino hay resultado
        if (empty($usuario)) {
            Usuario::setAlerta('error', 'Token no válido');
            $error = true;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $password = new Usuario($_POST);

            $alertas = $password->validarPassword();

            if (empty($alertas)) {
                $usuario->password = '';
                // entro al resutlado de la consulta, arriba le asigno nulo y abajo lo igualo al nuevo password ingresado
                $usuario->password = $password->password;
                $usuario->hashearPassword();
                $usuario->token = '';
                $resultado = $usuario->guardar();
                
                if($resultado) {
                    // Crear mensaje de exito
                    Usuario::setAlerta('exito', 'Password Actualizado Correctamente');
                                    
                    // Redireccionar al login tras 3 segundos
                    header('Refresh: 3; url=/');
                }   
            }
        }
        $alertas = Usuario::getAlertas();
        $router->render('auth/recuperar-password', [
            'alertas' => $alertas,
            'error' => $error
        ]);
    }
    public static function crearCuenta(Router $router)
    {
        $usuario = new Usuario();
        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // para que el formulario conserve su contenido
            // puedes volver a instanciar
            // $usuario = new Usuario($_POST);
            // o puedes usar sincronizar
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarNuevaCuenta();

            if (empty($alertas)) {
                $resultado = $usuario->existeUsuario();

                // si marca num rows 1 es porque ya está registrado el usuario
                if ($resultado->num_rows) {
                    $alertas = Usuario::getAlertas();
                } else {
                    $usuario->hashearPassword();
                    $usuario->generarToken();

                    $email = new Email($usuario->nombre, $usuario->email, $usuario->token);

                    $email->enviarConfirmacion();

                    $resultado = $usuario->guardar();

                    if ($resultado) {
                        header('Location: /mensaje');
                    }
                }
            }
        }

        $router->render('auth/crear-cuenta', [
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }
    public static function mensaje(Router $router)
    {
        // ventana solo de mensaje, te hará ver tu correo
        $router->render('auth/mensaje');
    }
    public static function confirmar(Router $router)
    {
        // inicializa
        $alertas = [];
        $token = s($_GET['token']);
        $usuario = Usuario::where('token', $token);

        if (empty($usuario)) {
            // Mostrar mensaje de error
            Usuario::setAlerta('error', 'Token no valido');
            // puedes reasignar alertas en vez de abajo, funciona igual por la cascada del flujo pero duplica code
            // $alertas = Usuario::getAlertas();
        } else {
            $usuario->confirmaActualizaToken();
            $usuario->guardar();
            Usuario::setAlerta('exito', 'Cuenta comprobada correctamente');
            // puedes reasignar alertas en vez de abajo, funciona igual por la cascada del flujo pero duplica code
            // $alertas = Usuario::getAlertas();
        }
        $alertas = Usuario::getAlertas();
        $router->render('auth/confirmar-cuenta', [
            'alertas' => $alertas
        ]);
    }
}
