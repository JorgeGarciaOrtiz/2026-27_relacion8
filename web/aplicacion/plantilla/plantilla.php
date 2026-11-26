<?php

function paginaError($mensaje)
{
    header("HTTP/1.0 404 $mensaje");
    inicioCabecera("PRACTICA");
    finCabecera();
    inicioCuerpo("ERROR");

    echo "<br/>\n";
    echo $mensaje;
    echo "<br/>\n";
    echo "<br/>\n";
    echo "<br/>\n";
    echo "<a href='/index.php'>Ir a la pagina principal</a>\n";

    finCuerpo();
}

function inicioCabecera($titulo)
{
?>
    <!DOCTYPE html>
    <html lang="es">

    <head>
        <meta charset="utf-8">

        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

        <title><?php echo $titulo; ?></title>
        <meta name="description" content="">
        <meta name="author" content="Administrador">

        <meta name="viewport" content="width=device-width; initial-scale=1.0">

        <link rel="shortcut icon" href="/favicon.ico">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">

        <link rel="stylesheet" type="text/css" href="/estilos/base.css">
    <?php
}

function finCabecera()
{
    ?>
    </head>
<?php
}

function inicioCuerpo(string $cabecera, ?array $ubicacion = null)
{
    global $acceso;
?>

    <body>
        <div id="documento">
            <header>
                <a href="/index.php">
                    <img src="/imagenes/logo2.png" alt="Logo IES Pedro Espinosa" width="50" height="50" style="vertical-align: middle;">
                </a>
                <h1 id="titulo" style="display: inline-block; vertical-align: middle;"><?php echo $cabecera; ?></h1>
            </header>

            <div id="barraLogin">
                <?php
                // Si hay un usuario validado, mostramos su nombre y el enlace para cerrar sesión
                if ($acceso->hayUsuario()) {
                    echo "Conectado como " . $acceso->getNombre() . " | ";
                    echo " <a href='/aplicacion/acceso/loggout.php'>Cerrar sesión</a><br><br>";
                } else {
                    // Si no hay usuario, mostramos "Sin usuario" y el enlace para iniciar sesión
                    echo "Sin usuario | ";
                    echo "<a href='/aplicacion/acceso/login.php'>Iniciar sesión</a><br><br>";
                }
                ?>
            </div>

            <div id="barraMenu">
                <ul>
                    <li><a href="/index.php">Inicio</a></li>

                    <!-- Estos botones solo los ve un usuario validado -->
                    <?php if ($acceso->hayUsuario()) { ?>
                        <li><a href="/aplicacion/practicas">Ejercicios</a></li>
                        <li><a href="/aplicacion/personalizar/index.php">Información</a></li>

                        <!-- El botón Personalizar solo lo ve quien tiene el permiso 1, si pongo el permiso 3 no se verá -->
                        <?php if ($acceso->puedePermiso(1)) { ?>
                            <li><a href="/aplicacion/personalizar/personalizar.php">Personalizar</a></li>
                        <?php } ?>

                        <li><a href="/aplicacion/texto/verTextos.php">Ver textos</a></li>
                    <?php } ?>
                </ul>
            </div>

            <?php
            if ($ubicacion !== null) {
            ?>
                <div id="barraUbicacion">
                    <?php mostrarBarraUbicacion($ubicacion); ?>
                </div>
            <?php
            }
            ?>

            <div>
            <?php
        }

        function finCuerpo()
        {
            ?>
                <br />
                <br />
            </div>

            <footer>
                <hr width="90%" />
                <div>
                    &copy; Copyright by Jorge García Ortiz
                </div>
            </footer>
        </div>
    </body>

    </html>
<?php
        }

        function mostrarBarraUbicacion(array $ubicacion)
        {
            echo "<nav class='barraModdle'>";

            $total = count($ubicacion);
            $contador = 0;

            foreach ($ubicacion as $nombre => $url) {
                $contador++;

                if ($contador < $total) {
                    echo "<a href='{$url}'>{$nombre}</a> &raquo; ";
                } else {
                    echo "<span>{$nombre}</span>";
                }
            }

            echo "</nav>";
        }
