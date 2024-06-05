<?php
function limpiarDatos($datos)
{
    $datos = htmlspecialchars($datos, ENT_QUOTES);
    $datos = trim($datos);
    $datos = stripslashes($datos);
    return $datos;
}

function iniciarSesionSiNoIniciada()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

