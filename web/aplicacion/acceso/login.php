<?php

// Incluyo la cabecera (sesión y objetos $acl y $acceso) y la plantilla de la aplicación
include_once(dirname(__FILE__) . "/../../cabecera.php");

// Variable con el título
$titulo = "Iniciar sesión";

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
    "nick" => "",
    "contrasena" => "",
];

// Si se ha pulsado el botón de login:
if (isset($_POST["login"])) {

    // Inicializamos el nick con el valor inicial del array de datos
    $nick = $datos["nick"];

    // Si el nick ha llegado desde el formulario:
    if (isset($_POST["nick"])) {

        // Asigno el valor del formulario a la variable para el nick
        $nick = $_POST["nick"];

        // Le quito los espacios de los extremos
        $nick = trim($nick);
    }

    // ==========================================================

    // Inicializamos la contraseña con el valor inicial del array de datos
    $contrasena = $datos["contrasena"];

    // Si la contraseña ha llegado desde el formulario:
    if (isset($_POST["contrasena"])) {

        // Asigno el valor del formulario a la variable para la contraseña
        $contrasena = $_POST["contrasena"];
    }

    // ==========================================================

    // Comprobamos con la ACL si el usuario existe y la contraseña es correcta
    if ($acl->esValido($nick, $contrasena)) {

        // Obtenemos el código del usuario para pedir sus datos a la ACL
        $codUsuario = $acl->getCodUsuario($nick);

        // Guardamos al usuario validado en la sesión con el objeto Acceso
        $acceso->registrarUsuario($nick, $acl->getNombre($codUsuario), $acl->getPermisos($codUsuario));

        // Si una página protegida guardó su ruta, vuelvo a ella; si no, voy al index
        $destino = $_SESSION["volver"] ?? "../../index.php";

        // Borro la ruta guardada para que no se use en el siguiente login
        unset($_SESSION["volver"]);

        // Redirijo al destino para que no se vuelva a enviar el formulario
        header("Location: $destino");
        exit;

    } else {
        // Si no es correcto, guardamos el error
        $errores["login"][] = "Usuario o contraseña incorrectos";
    }
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
cuerpo($errores);
finCuerpo();

// ==========================================================
// VISTA
// ==========================================================

function cabecera() {}

/**
 * Función que muestra el cuerpo de la página.
 *
 * @param array $errores Errores de validación del formulario
 */
function cuerpo(array $errores)
{
    // Muestro el formulario y los errores
    formulario($errores);
}

/**
 * Función que muestra el formulario para iniciar sesión
 *
 * @param array $errores
 */
function formulario(array $errores)
{
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
    <!-- Formulario de login -->
    <form action="" method="post">
        <!-- Usuario -->
        <label for="nick">Usuario: </label>
        <input type="text" name="nick" id="nick"><br>

        <!-- Contraseña -->
        <label for="contrasena">Contraseña: </label>
        <input type="password" name="contrasena" id="contrasena"><br>

        <!-- Botón -->
        <input type="submit" value="Login" name="login">
    </form>
<?php
}
