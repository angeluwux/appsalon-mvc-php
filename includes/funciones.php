<?php

function debuggear($variable) : string {
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    exit;
}

// Escapa / Sanitizar el HTML
function s($html) : string {
    $s = htmlspecialchars($html);
    return $s;
}

function validarContenido($tipo)
{
    $tipos = ['xxx', 'xxx'];
    return in_array($tipo, $tipos);
}

function validarIdGET(string $url)
{
    // validando id, no te precupes, la funcion se llama cuando la parte de arriba ya esta hecha, como si fuera un teletransportado
    $id = $_GET['id'];
    $id = filter_var($id, FILTER_VALIDATE_INT);
    if (!$id) {
        header("Location: {$url}");
    }
    return $id;
}
function validarIdPOST(string $url)
{
    // validando id, no te precupes, la funcion se llama cuando la parte de arriba ya esta hecha, como si fuera un teletransportado
    $id = $_POST['id'];
    $id = filter_var($id, FILTER_VALIDATE_INT);
    if (!$id) {
        header("Location: {$url}");
    }
    return $id;
}

function isAuth() : void {
    if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
        header('Location: /');
        exit();
    }
}

function isAdmin() : void {
    if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== '1') {
        header('Location: /');
        exit();
    }
}


function esUltimo(string $actual, string $proximo): bool {

    if($actual !== $proximo) {
        return true;
    }
    return false;
}