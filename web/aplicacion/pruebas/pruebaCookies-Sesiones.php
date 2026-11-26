<?php

// Incluimos la cabecera y la plantilla de la aplicación
include_once(dirname(__FILE__) . "/../../cabecera.php");

// Variable con el título
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

// setcookie("numero", mt_rand(1, 100), time()+60*60*24*2);
// $valor = $_COOKIE["numero"];
// session_start();
// $_SESSION["nombre"] = "Jorge";

// Leo la cookie; si no existe, uso un valor cualquiera
$valor = $_COOKIE["miCookie"] ?? 777;

// Guardo una cookie: setcookie(nombre, valor, caducidad, ruta)
// nombre: "miCookie" (como se llama la cookie, para leerla con $_COOKIE["miCookie"])
// valor: $valor (lo que se guarda en la cookie)
// caducidad: time() + 60 * 60 * 24 * 30 (dura 30 días desde ahora)
// ruta: "/" (la cookie está disponible en todas las páginas del sitio)
setcookie("miCookie", $valor, time() + 60 * 60 * 24 * 30, "/");

// Borro la cookie: le pongo una caducidad en el pasado (hace una hora)
// setcookie("miCookie", "", time() - 3600, "/");

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
function cuerpo(mixed $valor) {
    echo "Leida la cookie con valor " . $valor;
}
