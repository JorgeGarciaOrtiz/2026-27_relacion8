<?php

// Incluyo la cabecera (sesión y objetos $acl y $acceso)
include_once(dirname(__FILE__) . "/../../cabecera.php");

// Quito el registro del usuario: pasa a estar no validado
$acceso->quitarRegistroUsuario();

// Redirigimos a index.php
header("Location: ../../index.php"); // header("Location: /index.php");
exit;
