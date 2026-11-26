<?php

// Incluyo la cabecera y la plantilla de la aplicación
include_once(dirname(__FILE__) . "/../../cabecera.php");

// Si no hay un usuario validado, lo mandamos al login
if (!$acceso->hayUsuario()) {
    header("Location: /aplicacion/acceso/login.php");
    exit;
}

// Si el usuario no tiene el permiso 1, mostramos una página de error
if (!$acceso->puedePermiso(1)) { // Si pongo el permiso 3, dará error
    paginaError("Acceso no permitido");
    exit;
}

// Variable con el título
$titulo = "Personalizar";

// Ruta de navegación de la página
$ubicacion = [
    "Inicio" => "../../index.php",
    "Relación 8" => "../practicas/relacion8/index.php",
    $titulo => ""
];

// ==========================================================
// CONTROLADOR
// ==========================================================

// Creo un array con los colores de fondo (es una copia)
$coloresFondo = COLORESFONDO;

// Creo un array con los colores de texto (es una copia)
$coloresTexto = COLORESTEXTO;

// Creo un array con los datos iniciales del formulario:
// el color guardado en la cookie o, si no hay cookie, blanco y negro
$datos = [
    "colorFondo" => $_COOKIE["colorFondo"] ?? "blanco",
    "colorTexto" => $_COOKIE["colorTexto"] ?? "negro"
];

// Creamos un array con los errores
$errores = [];

// Si se ha pulsado el botón de guardar:
if (isset($_POST["guardar"])) {

    // Creo el color de fondo con el valor inicial del array de datos
    $colorFondo = $datos["colorFondo"];

    // Si el color de fondo ha llegado desde el formulario:
    if (isset($_POST["colorFondo"])) {
        // Asigno el valor del formulario
        $colorFondo = $_POST["colorFondo"];
    }

    // Si el color de fondo no es una de las opciones dará error
    if (!validaRango($colorFondo, $coloresFondo, 2)) {
        $errores["colorFondo"][] = "El color de fondo seleccionado no existe";
    }

    // Guardo el color de fondo para mostrarlo en el formulario
    $datos["colorFondo"] = $colorFondo;

    // ======================================================

    // Creo el color de texto con el valor inicial del array de datos
    $colorTexto = $datos["colorTexto"];

    // Si el color de texto ha llegado desde el formulario:
    if (isset($_POST["colorTexto"])) {
        // Asigno el valor del formulario
        $colorTexto = $_POST["colorTexto"];
    }

    // Si el color de texto no es una de las opciones dará error
    if (!validaRango($colorTexto, $coloresTexto, 2)) {
        $errores["colorTexto"][] = "El color de texto seleccionado no existe";
    }

    // Guardo el color de texto para mostrarlo en el formulario
    $datos["colorTexto"] = $colorTexto;

    // var_dump($datos["colorFondo"]);
    // var_dump($datos["colorTexto"]);

    // ======================================================

    // Si no hay errores, guardo los colores elegidos en las cookies
    if (!$errores) {
        // setcookie(nombre de la cookie, valor, duración);
        setcookie("colorFondo", $colorFondo, time() + 60 * 60 * 24 * 30, "/");
        setcookie("colorTexto", $colorTexto, time() + 60 * 60 * 24 * 30, "/");
    }
}

// Estos valores se pasan a cabecera() para pintar la página con los colores elegidos
$colorCabeceraFondo = $coloresFondo[$datos["colorFondo"] ?? "blanco"] ?? "white";
$colorCabeceraTexto = $coloresTexto[$datos["colorTexto"] ?? "negro"] ?? "black";

// ==========================================================
// PLANTILLA
// ==========================================================

// Cabecera de la página
inicioCabecera($titulo);
cabecera($colorCabeceraFondo, $colorCabeceraTexto);
finCabecera();

// Cuerpo de la página
inicioCuerpo($titulo, $ubicacion);
cuerpo($coloresFondo, $coloresTexto, $errores, $datos);
finCuerpo();

// ==========================================================
// VISTA
// ==========================================================

/**
 * Función que escribe en el head de la página el estilo con los colores elegidos.
 *
 * @param string $colorCabeceraFondo Color de fondo en CSS
 * @param string $colorCabeceraTexto Color del texto en CSS
 */
function cabecera(string $colorCabeceraFondo, string $colorCabeceraTexto)
{
    echo
    "
        <style>
            #documento {
                background-color: $colorCabeceraFondo;
                color: $colorCabeceraTexto;
            }
        </style>
    ";
}

/**
 * Función que muestra el cuerpo de la página.
 *
 * @param array $coloresFondo Colores de fondo que se pueden elegir
 * @param array $coloresTexto Colores de texto que se pueden elegir
 * @param array $errores Errores de validación del formulario
 * @param array $datos Datos que se muestran en el formulario
 */
function cuerpo(
    array $coloresFondo,
    array $coloresTexto,
    array $errores,
    array $datos
) {
    // Muestro el formulario con los datos y los errores
    formulario($coloresFondo, $coloresTexto, $errores, $datos);
}

/**
 * Función que muestra el formulario para elegir los colores.
 *
 * @param array $coloresFondo Colores de fondo que se pueden elegir
 * @param array $coloresTexto Colores de texto que se pueden elegir
 * @param array $errores Errores de validación que se muestran encima del formulario
 * @param array $datos Colores que aparecen seleccionados en los desplegables
 */
function formulario(
    array $coloresFondo,
    array $coloresTexto,
    array $errores,
    array $datos
) {
    // Si hay errores, los mostramos todos (campo => mensaje)
    if ($errores) {
        echo "<div class='error'>";
        foreach ($errores as $clave => $valor) {
            foreach ($valor as $error)
                echo "$clave => $error<br>" . PHP_EOL;
        }
        echo "</div><br>";
    }
?>
    <!-- Formulario -->
    <form action="" method="post">

        <!-- Desplegable con todos los colores de fondo -->
        <p>Color de fondo: </p>
        <select name="colorFondo">
            <?php foreach ($coloresFondo as $clave => $valor) { ?>
                <option value="<?php echo $clave ?>" <?php if ($datos["colorFondo"] == $clave) { ?> selected <?php } ?>>
                    <?php echo $clave ?>
                </option>
            <?php } ?>
        </select>

        <!-- Desplegable con todos los colores de texto -->
        <p>Color de texto: </p>
        <select name="colorTexto">
            <?php foreach ($coloresTexto as $clave => $valor) { ?>
                <option value="<?php echo $clave ?>" <?php if ($datos["colorTexto"] == $clave) { ?> selected <?php } ?>>
                    <?php echo $clave ?>
                </option>
            <?php } ?>
        </select>

        <!-- Botón para guardar los colores -->
        <br><br><input type="submit" name="guardar" value="guardar">

    </form>

<?php
}
