<?php

// Clase que implementa la ACL usando arrays para almacenar los datos.
// Hereda de ACLBase, así que tiene que definir todas sus funciones abstractas
class ACLArray extends ACLBase
{
    /**
     * $_roles contiene los roles definidos en la ACL
     * Cada role sera un array asociativo de 
     * cod => [ "cod"=> ,
     *            "nombre" => ,
     *            "permisos" => [1=>false, 2=> XX, .........10=> XXX]
     *          ]
     */
    private $_roles = [];

    /**
     * $_usuarios contiene los usuarios definidos en la ACL
     * Cada usuario sera un array asociativo de
     * 
     * cod => [
     *          "cod" => ,
     *          "nick" =>  ,
     *          "nombre" => ,
     *          "contrasenia" => , usamos una función de cifrado.
     *          "cod_role" => ,
     *          "borrado" =>        
     * ]
     */
    private $_usuarios = [];

    function __construct()
    {
        // Añado los 2 roles
        $this->anadirRole("normales", [1 => true]);
        $this->anadirRole("administradores", [1 => true, 2 => true]);

        // Añado el primer usuario
        $this->anadirUsuario(
            "Es usuario alumno",
            "alumno",
            "alum",
            $this->getCodRole("normales")
        );

        // Añado el segundo usuario
        $this->anadirUsuario(
            "Es usuario profesor",
            "profesor",
            "profe",
            $this->getCodRole("administradores")
        );

        // Añado el tercer usuario
        $this->anadirUsuario(
            "Jorge García",
            "jorge",
            "jorge1234",
            $this->getCodRole("administradores")
        );
    }

    /**
     * Añade un role a nuestra ACL
     * 
     * @param string $nombre Nombre del role a añadir 
     * @param array $permisos Permisos que tendrá el role. Array con hasta 10 permisos
     * @return bool True si se ha podido crear, false en caso contrario
     */
    function anadirRole(string $nombre, array $permisos = array()): bool
    {
        // Pongo el nombre en minuscula
        $nombre = mb_strtolower($nombre);

        // Inicializo un array con todos los permisos a false
        $per = [
            1 => false,
            false,
            false,
            false,
            false,
            false,
            false,
            false,
            false,
            false
        ];

        // Compruebo si ya existe el role
        if ($this->getCodRole($nombre) !== false) {
            return false;
        }

        // Reviso los permisos
        foreach ($per as $clave => $val) {
            if (isset($permisos[$clave])) {
                $per[$clave] = boolval($permisos[$clave]);
            }
        }

        // Busco el código más alto que ya existe entre los roles
        $cont = 0;
        foreach ($this->_roles as $clave => $role) {
            if ($role["cod"] > $cont) {
                $cont = $role["cod"];
            }
        }

        // El nuevo role tendrá el siguiente código
        $cont++;

        // Creo el role
        $this->_roles[$cont] = [
            "cod" => $cont,
            "nombre" => $nombre,
            "permisos" => $per
        ];

        // Se ha creado el role correctamente
        return true;
    }

    /**
     * Función que localiza un role y devuelve el código de ese role o false si no lo encuentra
     *
     * @param string $nombre nombre del role
     * @return integer|false Devuelve el código de role para el nombre indicado o false si no encuentra el role
     */
    function getCodRole(string $nombre): int|false
    {
        // Pongo el nombre en minuscula
        $nombre = mb_strtolower($nombre);

        // Recorro todos los roles uno a uno
        foreach ($this->_roles as $clave => $role) {

            // Si el nombre del role coincide con el que busco    
            if ($role["nombre"] == $nombre) {
                // Devuelvo el código del role
                return $role["cod"];
            }
        }

        // No se encuentra el role
        return false;
    }

    /**
     * Función que comprueba la existencia de un role
     *
     * @param integer $codRole role a buscar
     * @return boolean Devuelve true si lo encuentra o false en caso contrario 
     */
    function existeRole(int $codRole): bool
    {
        // Forma larga
        // foreach ($this->_roles as $clave => $role) {
        //     if ($role["cod"] == $codRole) {
        //         return true;
        //     }
        // }
        // return false;

        // Forma corta
        // Compruebo si existe un role con ese código como índice
        return isset($this->_roles[$codRole]);
    }

    /**
     * Función que devuelve los permisos de un role dado
     *
     * @param integer $codRole Role a buscar
     * @return array|false Devuelve los permisos o false si no encuentra el role
     */
    function getPermisosRole(int $codRole): array|false
    {
        // Compruebo que existe el codigo de role
        if (!$this->existeRole($codRole)) {
            return false;
        }

        // Devuelvo el array de permisos del role
        return ($this->_roles[$codRole]["permisos"]);
    }

    /**
     * Función que devuelve si un role tiene o no un permiso concreto
     *
     * @param integer $codRole Role a buscar
     * @param integer $numero Número de permiso
     * @return boolean True si encuentra el role y lo tiene. False en cualquier otro caso
     */
    function getPermisoRole(int $codRole, int $numero): bool
    {
        // Si el rol existe, el permiso existe, y el permiso del rol es true devolvera true
        if (
            $this->existeRole($codRole) &&
            isset($this->_roles[$codRole]["permisos"][$numero]) &&
            $this->_roles[$codRole]["permisos"][$numero] == true
        ) {
            return true;
        }
        return false;
    }

    /**
     * Función que añade un nuevo usuario a nuestra ACL
     *
     * @param string $nombre Nombre del usuario
     * @param string $nick Nick unico para el usuario
     * @param string $contrasena contraseña del usuario
     * @param integer $codRole Role a asignarle
     * @return boolean Devuelve true si puede crearlo. False en caso contrario
     */
    function anadirUsuario(string $nombre, string $nick, string $contrasena, int $codRole): bool
    {
        // Pongo el nick a minuscula
        $nick = mb_strtolower($nick);

        // El nick tiene que ser único, así que compruebo que no exista ya
        if ($this->existeUsuario($nick)) {
            return false;
        }

        // Compruebo que exista el role
        if (!$this->existeRole($codRole)) {
            return false;
        }

        // La contraseña que llega por parámetro la cifro con BCRYPT y guardo el resultado en la misma variable
        $contrasena = password_hash($contrasena, PASSWORD_BCRYPT);

        // Busco cual es el ultimo codigo
        $cont = 0;
        foreach ($this->_usuarios as $clave => $usu) {
            if ($usu["cod"] > $cont)
                $cont = $usu["cod"];
        }

        $cont++;

        // Añado el usuario con la contraseña ya cifrada
        $this->_usuarios[$cont] = [
            "cod" => $cont,
            "nick" =>  $nick,
            "nombre" => $nombre,
            "contrasenia" =>  $contrasena,
            "cod_role" => $codRole,
            "borrado" => false
        ];

        // Si se añade el usuario
        return true;
    }

    /**
     * Obtiene el código de usuario para un nick dado
     *
     * @param string $nick nick del usuario a buscar
     * @return integer|false Devuelve el codigo del usuario o false si no lo encuentra
     */
    function getCodUsuario(string $nick): int|false
    {
        // Pongo el nick en minuscula
        $nick = mb_strtolower($nick);

        // Recorro todos los usuarios uno a uno
        foreach ($this->_usuarios as $clave => $usu) {

            // Si el nick del usuario coincide con el que busco
            if ($usu["nick"] == $nick) {
                // Devuelvo su código (la clave del array)
                return $clave;
            }
        }

        // No existe ningún usuario con ese nick
        return false;
    }

    /**
     * Verifica si existe un usuario dado un código
     *
     * @param integer $codUsuario Código del usuario a verificar
     * @return boolean Devuelve si existe o no el usuario
     */
    function existeCodUsuario(int $codUsuario): bool
    {
        // Forma larga
        // foreach ($this->_usuarios as $clave => $usu) {
        //     if ($usu["cod"] == $codUsuario) {
        //         return true;
        //     }
        // }
        // return false;

        // Forma corta
        return isset($this->_usuarios[$codUsuario]);
    }

    /**
     * Verifica si existe o no un usuario con el nick dado
     *
     * @param string $nick Nick del usuario a comprobar
     * @return boolean Devuelve true si encuentra el usuario y false en caso contrario
     */
    function existeUsuario(string $nick): bool
    {
        // Paso el nick a minúsculas
        $nick = mb_strtolower($nick);

        // Recorro todos los usuarios uno a uno
        foreach ($this->_usuarios as $clave => $usu) {

            // Si el nick del usuario coincide con el que se ha pasado por parametro
            if ($usu["nick"] == $nick) {
                // Si existe
                return true;
            }
        }

        // No existe
        return false;
    }

    /**
     * Función que comprueba que existe un usuario y la contraseña indicada es la correcta
     *
     * @param string $nick Nick del usuario a comprobar
     * @param string $contrasena Contraseña del usuario a comprobar
     * @return boolean Devuelve true si existe el usuario y tiene la contraseña indicada, false en otro caso
     */
    function esValido(string $nick, string $contrasena): bool
    {
        // Pongo el nick en minuscula
        $nick = mb_strtolower($nick);

        // Compruebo si existe el nick
        if (!$this->existeUsuario($nick)) {
            return false;
        }

        // Recojo cual es el codigo
        $codigo = $this->getCodUsuario($nick);

        // Compruebo si la contraseña que me pasan por parametro es la del usuario con ese nick
        if (!password_verify($contrasena, $this->_usuarios[$codigo]["contrasenia"])) {
            return false;
        }

        // Es valido
        return true;
    }

    /**
     * Función que comprueba si un usuario tiene un permiso concreto
     *
     * @param integer $codUsuario Usuario a buscar
     * @param integer $numero Permiso a buscar
     * @return boolean Devuelve true si existe el usuario y tiene el permiso. 
     * False en otro caso
     */
    function getPermiso(int $codUsuario, int $numero): bool
    {
        // Si existe el usuario con ese código
        if ($this->existeCodUsuario($codUsuario)) {

            // Saco el código del rol del usuario (getUsuarioRole) y compruebo si ese rol tiene el permiso en true (getPermisoRole)
            if ($this->getPermisoRole($this->getUsuarioRole($codUsuario), $numero)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Función que devuelve los permisos de un usuario
     *
     * @param integer $codUsuario Usuario a buscar
     * @return array|false Devuelve los permisos del usuario o false si no existe el usuario
     */
    function getPermisos(int $codUsuario): array|false
    {
        // Si el usuario no existe, devuelvo false
        if (!$this->existeCodUsuario($codUsuario)) {
            return false;
        }

        // Devuelvo los permisos del rol del usuario
        return $this->getPermisosRole($this->getUsuarioRole($codUsuario));
    }

    /**
     * Función que devuelve el nombre de un usuario
     *
     * @param integer $codUsuario Usuario a buscar
     * @return string|false Devuelve el nombre del usuario o false si no existe
     */
    function getNombre(int $codUsuario): string|false
    {
        // Si el usuario no existe, no se puede devolver el nombre
        if (!$this->existeCodUsuario($codUsuario)) {
            return false;
        }

        // Devuelvo el nombre del usuario
        return $this->_usuarios[$codUsuario]["nombre"];
    }

    /**
     * Devuelve si un usuario está borrado
     *
     * @param integer $codUsuario Usuario a buscar.
     * @return boolean true si el usuario está borrado, false en otro caso
     */
    function getBorrado(int $codUsuario): bool
    {
        // Recorro todos los usuarios uno a uno
        foreach ($this->_usuarios as $clave => $usu) {

            // Si el codigo es igual al pasado por parametro y 
            // borrado lo tiene en true devolvemos que si esta borrado (true)
            if ($usu["cod"] == $codUsuario && $usu["borrado"] == true) {
                return true;
            }
        }

        return false;
    }

    /**
     * Devuelve el código del rol que tiene un usuario concreto
     *
     * @param integer $codUsuario Usuario a buscar
     * @return integer|false Devuelve el código del rol del usuario o false si no existe.
     */
    function getUsuarioRole(int $codUsuario): int|false
    {
        // Si el usuario no existe, devuelvo false
        if (!$this->existeCodUsuario($codUsuario))
            return false;

        // Devuelvo el código del rol del usuario
        return $this->_usuarios[$codUsuario]["cod_role"];
    }

    /**
     * Función que asigna un nombre a un usuario
     *
     * @param integer $codUsuario Usuario a buscar
     * @param string $nombre Nombre a asignar
     * @return boolean Devuelve true si ha podido asignar el nombre, false en otro caso
     */
    function setNombre(int $codUsuario, string $nombre): bool
    {
        // Si el usuario no existe, no se puede asignar el nombre
        if (!$this->existeCodUsuario($codUsuario))
            return false;

        // Cambio el nombre del usuario
        $this->_usuarios[$codUsuario]["nombre"] = $nombre;

        // Indico que se ha podido hacer
        return true;
    }

    /**
     * Función que asigna una contraseña a un usuario
     *
     * @param integer $codUsuario usuario a buscar
     * @param string $contrasenia contraseña a asignar
     * @return boolean Devuelve true si ha podido asignar la contraseña, false en otro caso
     */
    function setContrasenia(int $codUsuario, string $contrasenia): bool
    {
        // Si el usuario no existe, no se puede asignar la contraseña
        if (!$this->existeCodUsuario($codUsuario)) {
            return false;
        }

        // La contraseña que llega por parámetro la cifro con BCRYPT y guardo el resultado en la misma variable
        $contrasenia = password_hash($contrasenia, PASSWORD_BCRYPT);

        // Cambio la contraseña del usuario
        $this->_usuarios[$codUsuario]["contrasenia"] = $contrasenia;

        // Indico que se ha podido hacer
        return true;
    }

    /**
     * Función que borra/desborra lógicamente un usuario 
     *
     * @param integer $codUsuario Usuario a buscar
     * @param boolean $borrado Estado a asignar
     * @return boolean Devuelve true si ha podido asignar el estado, false en otro caso
     */
    function setBorrado(int $codUsuario, bool $borrado): bool
    {
        // Si el usuario no existe, no se puede cambiar su estado
        if (!$this->existeCodUsuario($codUsuario)) {
            return false;
        }

        // Asigno el estado de borrado al usuario
        $this->_usuarios[$codUsuario]["borrado"] = $borrado;

        // Si se ha podido borra/desborra el usuario
        return true;
    }

    /**
     * Función que cambia el role de un usuario
     *
     * @param integer $codUsuario Usuario a buscar
     * @param integer $role Role a asignar
     * @return boolean true si ha podido asignar el role al usuario, false si el usuario no existe, el role no existe o no se ha podido asignar
     */
    function setUsuarioRole(int $codUsuario, int $role): bool
    {
        // Si el usuario no existe, no se puede cambiar el rol
        if (!$this->existeCodUsuario($codUsuario)) {
            return false;
        }

        // Si el role no existe, no se puede asignar
        if (!$this->existeRole($role)) {
            return false;
        }

        // Cambio el rol del usuario
        $this->_usuarios[$codUsuario]["cod_role"] =  $role;

        // Indico que se ha podido hacer
        return true;
    }

    /**
     * Devuelve un array con todos los usuarios existentes. 
     * La clave es el codigo de usuario, el valor es el nick del usuario 
     *
     * @return array Array con todos los usuarios existentes
     */
    function dameUsuarios(): array
    {
        $usuarios = [];

        // Guardo el nick en la posición del código de usuario
        foreach ($this->_usuarios as $clave => $usu) {
            $usuarios[$clave] = $usu["nick"];
        }

        return $usuarios;
    }

    /**
     * Devuelve un array con todos los roles existentes. 
     * La clave es el codigo de role, el valor es el nombre del role 
     *
     * @return array Array con todos los roles existentes
     */
    function dameRoles(): array
    {
        $roles = [];

        // Guardo el nombre del role en la posición de su código
        foreach ($this->_roles as $clave => $role) {
            $roles[$clave] = $role["nombre"];
        }

        return $roles;
    }
}
