<?php

// Incluyo la cabecera y la plantilla de la aplicación
include_once(dirname(__FILE__) . "/../../cabecera.php");

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

// Creamos un array para meter los posibles errores
$errores = [];

// Creo un array con los datos del formulario
$datos = [
    "texto" => "",
];

// Si se ha pulsado el botón de registrar:
if (isset($_POST["registrar"])) {
    
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
cuerpo($errores, $datos);
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
 */
function cuerpo(
    array $errores,
    array $datos
) {
    // Muestro el formulario con los datos y los errores
    formulario($errores, $datos);
}

/**
 * Función que muestra el formulario para escribir un registro
 *
 * @param array $errores Errores de validación que se muestran encima del formulario
 * @param array $datos Datos que se muestran en el formulario
 */
function formulario(
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

        <!-- Texto -->
        <label for="texto">Texto: </label>
        <input type="text" name="texto" id="texto"
            value="<?php echo $datos["texto"]; ?>">

        <!-- Botón para añadir los registros -->
        <br><br><input type="submit" name="registrar" value="Registrar">

    </form>
<?php
}
