<?php

// Incluimos la cabecera y la plantilla de la aplicación
include_once(dirname(__FILE__) . "/../../../cabecera.php");

// Si no hay un usuario validado, lo mandamos al login
if (!$acceso->hayUsuario()) {
    header("Location: /aplicacion/acceso/login.php");
    exit;
}

// Variable con el título
$titulo = "Relación 8";

// Ruta de navegación de la página
$ubicacion = [
    "Inicio" => "../../../index.php",
    "Ejercicios" => "./../index.php",
    $titulo => "./index.php"
];

// ==========================================================
// CONTROLADOR
// ==========================================================

$tienePermiso = $acceso->puedePermiso(1);

// ==========================================================
// PLANTILLA
// ==========================================================

// Cabecera de la página
inicioCabecera($titulo);
cabecera();
finCabecera();

// Cuerpo de la página
inicioCuerpo($titulo, $ubicacion);
cuerpo($tienePermiso);
finCuerpo();

// ==========================================================
// VISTA
// ==========================================================

function cabecera() {}

function cuerpo(bool $tienePermiso)
{
?>
    <a href="/aplicacion/personalizar/index.php">Información</a><br>

    <?php if ($tienePermiso) { ?>
        <a href="/aplicacion/personalizar/personalizar.php">Personalizar</a><br>
    <?php } ?>

    <a href="/aplicacion/texto/verTextos.php">Ver textos</a>
<?php
}
