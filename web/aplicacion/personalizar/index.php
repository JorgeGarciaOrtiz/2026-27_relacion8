<?php


// Incluimos la cabecera y la plantilla de la aplicación
include_once(dirname(__FILE__) . "/../../cabecera.php");

// Variable con el titulo
$titulo = "Información";

// Ruta de navegación de la página (ajusta los enlaces a tu proyecto)
$ubicacion = [
    "Inicio" => "../../index.php",
    "Relación 8" => "../practicas/relacion8/index.php",
    $titulo => "./index.php"
];

// ==========================================================
// CONTROLADOR
// ==========================================================

// Si no hay cookie el color es blanco, si no sera el valor de la cookie
// Si el valor de la cookie no esta en COLORESFONDO sera white
$colorFondo = COLORESFONDO[$_COOKIE["colorFondo"] ?? "blanco"] ?? "white";

// Si no hay cookie el texto es negro, si no será el valor de la cookie
// Si el valor de la cookie no está en COLORESTEXTO será black
$colorTexto = COLORESTEXTO[$_COOKIE["colorTexto"] ?? "negro"] ?? "black";

// Leo la cookie del contador (si no existe uso 0) y la convierto a número entero
$contador = (int) ($_COOKIE["contadorVisitas"] ?? 0);
// Le sumo uno al contador
$contador++;
// Guardo el contador de visitas en la cookie durante un año
setcookie("contadorVisitas", $contador, time() + 60 * 60 * 24 * 365, "/");

// ==========================================================
// PLANTILLA
// ==========================================================

// Cabecera de la página
inicioCabecera($titulo);
cabecera($colorFondo, $colorTexto);
finCabecera();

// Cuerpo de la página
inicioCuerpo($titulo, $ubicacion);
cuerpo($contador);
finCuerpo();

// ==========================================================
// VISTA
// ==========================================================

/**
 * Función que escribe en el head de la página el estilo con los colores elegidos.
 *
 * @param string $colorFondo Color de fondo en CSS
 * @param string $colorTexto Color del texto en CSS
 */
function cabecera(string $colorFondo, string $colorTexto)
{
    echo
    "
        <style>
            #documento {
                background-color: $colorFondo;
                color: $colorTexto;
            }
        </style>
    ";
}

/**
 * Función que muestra el cuerpo de la página con una información cualquiera.
 */
function cuerpo(int $contador)
{
    echo "<h2>Página de Jorge García Ortiz</h2>";
    echo "Número de veces que he entrado a la página: " . $contador;
}
