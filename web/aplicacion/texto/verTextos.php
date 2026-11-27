<?php

// Incluyo la cabecera y la plantilla de la aplicación
include_once(dirname(__FILE__) . "/../../cabecera.php");

// Si no hay un usuario validado, lo mandamos al login
if (!$acceso->hayUsuario()) {

    // Guardo la página a la que quería entrar para volver después del login
    $_SESSION["volver"] = $_SERVER["REQUEST_URI"];

    header("Location: /aplicacion/acceso/login.php");
    exit;
}

// Variable con el título
$titulo = "Registro de textos";

// Ruta de navegación de la página
$ubicacion = [
    "Inicio" => "../../index.php",
    "Relación 8" => "../practicas/relacion8/index.php",
    $titulo => ""
];

// ==========================================================
// CONTROLADOR
// ==========================================================

// Para borrar la sesión si es necesario
// unset($_SESSION["listaTextos"]);

// Si todavía no existe la lista de textos en la sesión:
if (!isset($_SESSION["listaTextos"])) {
    // La creo como un array vacío (solo pasa la primera vez)
    $_SESSION["listaTextos"] = [];
}

// Recojo los textos de la sesión en el array $textos
$textos = $_SESSION["listaTextos"];

// Creamos un array para meter los posibles errores
$errores = [];

// Creo un array con los datos del formulario
$datos = [
    "texto" => "",
];

// Si se ha pulsado el botón de registrar:
if (isset($_POST["registrar"])) {

    // Inicializamos el texto con el valor inicial del array de datos
    $texto = $datos["texto"];

    // Si el texto ha llegado desde el formulario:
    if (isset($_POST["texto"])) {

        // Asigno el valor del formulario a la variable para el texto
        $texto = $_POST["texto"];

        // Le quito los espacios de los extremos
        $texto = trim($texto);
    }

    // Si está vacío dará error
    if ($texto === "") {
        $errores["texto"][] = "El texto está vacío";
    }

    // Guardamos el texto para mostrarlo en el formulario
    $datos["texto"] = $texto;

    // Si no ha habido ningún error:
    if (count($errores) === 0) {

        // Creo un objeto de un registro
        $registroTexto = new RegistroTexto($texto);

        // Añado el nuevo registro al array $textos
        $textos[] = $registroTexto;

        // Actualizo la sesión con la copia de $textos
        $_SESSION["listaTextos"] = $textos;

        // Redirijo a la misma página para que F5 no reenvíe el formulario
        header("Location: verTextos.php");
        exit;
    }
}

// Si se ha pulsado el botón para limpiar:
if (isset($_POST["limpiar"])) {
    // Vacío el array $textos
    $textos = [];
    // Actualizo la sesión con el array vacío
    $_SESSION["listaTextos"] = $textos;
}

// ==========================================================
// PLANTILLA
// ==========================================================

// Cabecera de la página
inicioCabecera($titulo);
cabecera();
finCabecera();

// Cuerpo de la página
inicioCuerpo($titulo, $ubicacion);
cuerpo($errores, $datos, $textos);
finCuerpo();

// ==========================================================
// VISTA
// ==========================================================

function cabecera() {}

/**
 * Función que muestra el cuerpo de la página.
 *
 * @param array $errores Errores de validación del formulario
 * @param array $datos Datos que se muestran en el formulario
 * @param array $textos Textos guardados que se muestran en el textarea
 */
function cuerpo(
    array $errores,
    array $datos,
    array $textos
) {
    // Muestro el formulario con los datos y los errores
    formulario($errores, $datos, $textos);
}

/**
 * Función que muestra el formulario para escribir un registro
 *
 * @param array $errores Errores de validación que se muestran encima del formulario
 * @param array $datos Datos que se muestran en el formulario
 * @param array $textos Textos guardados que se muestran en el textarea
 */
function formulario(
    array $errores,
    array $datos,
    array $textos
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

        <!-- Texto -->
        <label for="texto">Texto: </label>
        <input type="text" name="texto" id="texto"
            value="">

        <!-- Textarea con todos los textos -->
        <h2>Textos</h2>
        <textarea readonly name="listaTextos" rows="6" cols="40"><?php foreach ($textos as $clave => $valor) { ?><?php echo $valor->getCadena() . ", " . $valor->getFechaHora()->format('d/m/Y H:i') . "\n" ?><?php } ?></textarea>

        <!-- Botón para añadir los textos -->
        <br><br><input type="submit" name="registrar" value="Registrar">

        <!-- Botón para limpiar los textos -->
        <br><br><input type="submit" name="limpiar" value="Limpiar">

    </form>
<?php
}
