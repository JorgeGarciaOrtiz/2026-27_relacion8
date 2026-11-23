<?php

// Incluimos la cabecera y la plantilla de la aplicación
include_once(dirname(__FILE__) . "/../../cabecera.php");

// Variable con el titulo
$titulo = "Prueba de cookies y sesiones";

// Ruta de navegación de la página
$ubicacion = [
    "Inicio" => "../../../index.php",
    "Pruebas" => "./index.php",
    $titulo => ""
];

// ==========================================================
// CONTROLADOR
// ==========================================================

setcookie("numero", mt_rand(1, 100), time()+60*60*24*2);

$valor = $_COOKIE["numero"];

session_start();

$_SESSION["nombre"] = "Jorge";

// ==========================================================
// PLANTILLA
// ==========================================================

// Dibujamos la cabecera de la página
inicioCabecera($titulo);
cabecera();
finCabecera();

// Dibujamos el cuerpo de la página
inicioCuerpo($titulo, $ubicacion);
cuerpo($valor);

finCuerpo();

// ==========================================================
// VISTA
// ==========================================================

// Cabecera propia de la vista
function cabecera() {}

// Cuerpo propio de la vista
function cuerpo(int $valor) {
    echo "Leida la cookie con valor " . $valor;
}
