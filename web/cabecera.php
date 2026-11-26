<?php

define("RUTABASE", dirname(__FILE__));
define("MODO_TRABAJO", "desarrollo");

if (MODO_TRABAJO == "produccion")
    error_reporting(0);
else
    error_reporting(E_ALL);

spl_autoload_register(function ($clase) {
    $ruta = RUTABASE . "/scripts/clases/";
    $fichero = $ruta . "$clase.php";

    if (file_exists($fichero)) {
        require_once($fichero);
    } else {
        throw new Exception("La clase $clase no se ha encontrado.");
    }
});

// Incluimos la plantilla y las librerías generales de la aplicación
include_once(RUTABASE . "/aplicacion/plantilla/plantilla.php");
include_once(RUTABASE . "/scripts/librerias/validacion.php");
include_once(RUTABASE . "/scripts/librerias/imagenes.php");
include_once(RUTABASE . "/scripts/librerias/ficheros.php");
include_once(RUTABASE . "/aplicacion/clases/RegistroTexto.php");

// Inicio la sesión para poder usar $_SESSION
session_start();

// Creo el objeto ACL: guarda los usuarios, los roles y los permisos
$acl = new ACLArray();
// Creo el objeto Acceso: guarda quién está conectado en este momento
$acceso = new Acceso();

// Creo una constante para los colores del texto
const COLORESTEXTO = [
    "negro" => "black",
    "azul" => "blue",
    "blanco" => "white",
    "rojo" => "red",
];

// Creo una constante para los colores de fondo
const COLORESFONDO = [
    "blanco" => "white",
    "rojo" => "red",
    "verde" => "green",
    "azul" => "blue",
    "amarillo" => "yellow"
];
