<?php

// Clase que guarda la información de acceso del usuario validado (si hay usuario,
// su nick, su nombre y sus permisos) y la mantiene en la sesión
class Acceso
{
    // ---------------------------------------------------
    // Variables de instancia
    // ---------------------------------------------------

    // Indica si hay un usuario validado (true) o no (false)
    private bool $_validado;
    // Nick del usuario validado
    private string $_nick;
    // Nombre del usuario validado
    private string $_nombre;
    // Permisos del usuario validado (array del 1 al 10 con true/false)
    private array $_permisos;

    // ---------------------------------------------------
    // Constructor
    // ---------------------------------------------------

    public function __construct()
    {
        // Empiezo sin ningún usuario validado
        $this->_validado = false;
        $this->_nick = "";
        $this->_nombre = "";
        $this->_permisos = [];

        // Cargo de la sesión los datos guardados del usuario validado
        $this->recogerDeSesion();
    }

    // ---------------------------------------------------
    // Funciones
    // ---------------------------------------------------

    /**
     * Funcion privada para guardar la información en la sesión
     *
     * @return boolean Devuelve true si se ha podido hacer. False en cualquier otro caso
     */
    private function escribirASesion(): bool
    {
        // Si la sesión no está iniciada, no se puede guardar nada
        if (!isset($_SESSION)) {
            return false;
        }

        // Si hay un usuario validado, guardo todos sus datos en la sesión
        if ($this->_validado) {
            $_SESSION["acceso"]["validado"] = true;
            $_SESSION["acceso"]["nick"] = $this->_nick;
            $_SESSION["acceso"]["nombre"] = $this->_nombre;
            $_SESSION["acceso"]["permisos"] = $this->_permisos;
        } else {
            // Si no hay usuario, solo guardo que no está validado
            $_SESSION["acceso"]["validado"] = false;
        }

        // Se han guardado correctamente los datos del usuario en la sesión
        return true;
    }

    /**
     * Función privada que recoje la información de la sesión
     *
     * @return boolean Devuelve true si se ha podido recoger
     */
    private function recogerDeSesion(): bool
    {
        // Si no hay sesión o no existe "acceso" o no existe "validado" o no está validado
        if (
            !isset($_SESSION) ||
            !isset($_SESSION["acceso"]) ||
            !isset($_SESSION["acceso"]["validado"]) ||
            $_SESSION["acceso"]["validado"] == false
        ) {
            // No hay ningún usuario validado
            $this->_validado = false;
        } else {
            // Hay un usuario validado: cargo sus datos desde la sesión
            $this->_validado = true;
            $this->_nick = $_SESSION["acceso"]["nick"];
            $this->_nombre = $_SESSION["acceso"]["nombre"];
            $this->_permisos = $_SESSION["acceso"]["permisos"];
        }

        // Se han recogido correctamente los datos de la sesión
        return true;
    }

    /**
     * Sirve para registrar un usuario en la aplicación. Almacena
     * los valores en las propiedades apropiadas y en la sesión 
     * para guardar en la sesión la información del usuario validado.
     *
     * @param string $nick nick del usuario a registrar
     * @param string $nombre nombre del usuario a registrar
     * @param array $permisos permisos del usuario a registrar
     * @return boolean Devuelve true si ha podido registrar el usuario
     */
    public function registrarUsuario(string $nick, string $nombre, array $permisos): bool
    {
        // Si el nick está vacío, no se puede registrar ningún usuario
        if ($nick == "") {
            $this->_validado = false;
        } else {
            // Si no, guardo los datos del usuario en las propiedades y lo marco como validado
            $this->_validado = true;
            $this->_nick = $nick;
            $this->_nombre = $nombre;
            $this->_permisos = $permisos;
        }

        // Guardo los datos en la sesión; si no se ha podido, devuelvo false
        if (!$this->escribirASesion()) {
            return false;
        }

        // Se ha registrado correctamente el usuario
        return true;
    }

    /**
     * Elimina la información de registro de un usuario
     *
     * @return boolean Devuelve true si ha podido hacerlo
     */
    public function quitarRegistroUsuario(): bool
    {
        // Marco que ya no hay ningún usuario validado
        $this->_validado = false;

        // Guardo ese cambio en la sesión; si no se ha podido, devuelvo false
        if (!$this->escribirASesion()) {
            return false;
        }

        // Se ha quitado el registro del usuario correctamente
        return true;
    }

    /**
     * Función que devuelve si hay o no un usuario registrado
     *
     * @return boolean Devuelve true si hay usuario registrado. False en caso contrario
     */
    public function hayUsuario(): bool
    {
        // Devuelvo true si hay un usuario validado, false si no
        return $this->_validado;
    }

    /**
     * Función que devuelve si el usuario registrado tiene o no el permiso indicado
     *
     * @param integer $numero Numero de permiso a comprobar
     * @return bool Devuelve true si hay usuario registrado y tiene el permiso indicado
     */
    public function puedePermiso(int $numero): bool
    {
        // Si hay un usuario validado, el permiso existe y tiene el valor true
        if ($this->hayUsuario() && isset($this->_permisos[$numero]) && $this->_permisos[$numero] == true) {
            // Devuelvo true
            return true;
        }

        // En cualquier otro caso, devuelvo false
        return false;
    }

    // ---------------------------------------------------
    // Métodos get
    // ---------------------------------------------------

    /**
     * Devuelve el nick del usuario indicado o false si no hay usuario
     *
     * @return string|false
     */
    public function getNick(): string|false
    {
        // Si no hay ningún usuario validado, devuelvo false
        if (!$this->hayUsuario()) {
            return false;
        }

        // Si lo hay, devuelvo su nick
        return $this->_nick;
    }

    /**
     * Devuelve el nombre del usuario registrado o false si no hay usuario
     *
     * @return string|false
     */
    public function getNombre(): string|false
    {
        // Si no hay ningún usuario validado, devuelvo false
        if (!$this->hayUsuario()) {
            return false;
        }

        // Si lo hay, devuelvo su nombre
        return $this->_nombre;
    }
}
