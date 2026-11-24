<?php

// Clase que guarda un texto y la fecha y hora en la que se creó.
class RegistroTexto
{

    // ------------------------------------------------
    // Propiedades
    // ------------------------------------------------

    // Texto que ha escrito el usuario
    private string $_cadena;

    // Fecha y hora en la que se creó el objeto
    private DateTime $_fechaHora;

    // -------------------------------------------------------------
    // Constructor
    // -------------------------------------------------------------

    /**
     * Constructor que crea un registro con el texto indicado y la hora actual
     *
     * @param string $cadena Texto que se quiere guardar
     */
    public function __construct(
        string $cadena,
    ) {
        $this->_cadena = $cadena;

        // Creo un DateTime con la fecha y hora de ahora
        $fechaHoraActual = new DateTime();
        $this->_fechaHora = $fechaHoraActual;
    }

    // ------------------------------------------------
    // Métodos get
    // ------------------------------------------------

    /**
     * Método get que devuelve la fecha y hora en la que se creó el registro.
     *
     * @return DateTime Fecha y hora del registro
     */
    public function getFechaHora(): DateTime
    {
        return $this->_fechaHora;
    }

    /**
     * Método get que devuelve el texto guardado en el registro.
     *
     * @return string Texto del registro
     */
    public function getCadena(): string
    {
        return $this->_cadena;
    }

    // ------------------------------------------------ 
    // Métodos mágicos 
    // ------------------------------------------------

    /**
     * Impide crear o modificar propiedades que no existen
     *
     * @param string $nombre Nombre de la propiedad
     * @param mixed $valor Valor que se intenta asignar
     * @return void No devuelve nada
     * @throws Exception Si se intenta crear o modificar la propiedad
     */
    public function __set(string $nombre, mixed $valor)
    {
        throw new Exception("No existe la propiedad " . $nombre);
    }

    /**
     * Impide leer propiedades que no existen
     *
     * @param string $nombre Nombre de la propiedad
     * @return mixed No llega a devolver nada, siempre lanza una excepción
     * @throws Exception Si se intenta leer la propiedad
     */
    public function __get(string $nombre)
    {
        throw new Exception("No existe la propiedad " . $nombre);
    }

    /**
     * Indica que las propiedades que no existen no están definidas
     *
     * @param string $nombre Nombre de la propiedad
     * @return bool Siempre false
     */
    public function __isset(string $nombre): bool
    {
        return false;
    }

    /**
     * Impide borrar propiedades que no existen
     *
     * @param string $nombre Nombre de la propiedad
     * @return void No devuelve nada
     * @throws Exception Si se intenta borrar la propiedad
     */
    public function __unset(string $nombre)
    {
        throw new Exception("No existe la propiedad " . $nombre);
    }
}
