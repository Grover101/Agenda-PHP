<?php
session_start();
class ConectorDB
{
    // datos del servidor
    private $host = 'localhost';
    public $user = 'root';
    private $password = '';
    public $database = 'agendaDB';
    private $conexion;

    // funcion de verificacino de conexion
    function verifyConexion()
    {
        $init = @$this->conexion = new mysqli($this->host, $this->user, $this->password); // iniciar conexion con el servidor

        if (!$this->conexion) { // si existe error de conexión
            $conexion['msg'] = "<h3>Error al conectarse a la base de datos.</h3>";
        }
        if ($this->conexion->connect_errno != '0') { //Verificar si existe un error al comparar que la respuesta del servidor sea diferente de 0

            $response =  "<h6>Error al conectarse a la base de datos.</h6> "; //Mensaje de error

            if ($this->conexion->connect_errno == "2002") { //Verificar que el error sea por resolución de nombre de servidor a través de la respuesta del servidor numero "202"
                $response .= "<p><h6><b>Error de conexión</b></h6> Vefirique que el <b style='font-size:1em'>nombre del servidor</b> corresponda al parámetro localhost en el archivo <b>conector.php</b> dentro de la <b>carpeta server</b> del proyecto</p>";
            }

            if ($this->conexion->connect_errno == "1045") { //Verificar que el error sea por error de usuario y/o contraseña a través de la respuesta del servidor numero "1045"
                $response .= "<h6><b>Error de conexión</b></h6><p>Vefirique que los parámetros de conexion <b>username y password </b> del archivo <b>conector.php</b> dentro de la <b>carpeta server</b> del proyecto correspondan a un <b>usuario y contraseña válido de phpmyadmin</b></br>" . $this->conexion->connect_error . "\n</p>";
            }

            if ($this->conexion->connect_errno == "1044") { //Verificar que el error sea por error de usuario y/o contraseña a través de la respuesta del servidor numero "1045"
                $response .= "<h6><b>Error de conexión</b></h6><p>Vefirique que los parámetros de conexion <b>username y password </b> del archivo <b>conector.php</b> dentro de la <b>carpeta server</b> del proyecto correspondan a un <b>usuario y contraseña válido de phpmyadmin</b></br>" . $this->conexion->connect_error . "\n</p>";
            }

            $conexion['phpmyadmin'] = "Error"; //Guardar el estado Si existe un error durante la conexión en el índice "phpmyadmin"
            $conexion['msg'] = $response; //Guardar el error error durante la conexión en el índice "msg"

        } else {

            /*Si los parametros de conexion a phpMyadmin son correctos continuar*/
            $conexion['phpmyadmin'] =  "OK"; //Guardar el estado Si existe un error durante la conexión en el índice "phpmyadmin"
            $conexion['msg'] =  "<p>Conexion establecida con phpMyadmin</p>"; //Guardar el mensaje si existe un error durante la conexión en el índice "msg"
        }
        echo json_encode($conexion); //Devolver respuesta
    }

    // funcion de inicializacion de conexion
    function initConexion($nombre_db)
    {
        @$this->conexion = new mysqli($this->host, $this->user, $this->password, $nombre_db);
        if ($this->conexion->connect_error) {
            return $this->conexion->connect_errno;
        } else {
            return "OK";
        }
    }

    // funcion para validar la sesion del usuario
    function userSession()
    {
        if (isset($_SESSION['email'])) { // verificar que la sesion no sea vacía
            $response['msg'] = $_SESSION['email']; // si hay una sesión iniciada guardar el nombre del usuario
        } else {
            $response['msg'] = '';
        }
        return json_encode($response);
    }

    // funcion que verfica si existen usuarios en la base de datos
    function verifyUsers()
    {
        $sql = 'SELECT COUNT(email) FROM usuarios;';
        $totalUsers = $this->ejecutarQuery($sql);
        while ($row = $totalUsers->fetch_assoc()) {
            return $row['COUNT(email)'];
        }
    }

    function getConexion()
    {
        return $this->conexion;
    }

    // funcion que crear nueva base de datos
    function newDatabase()
    {
        $this->conexion = new mysqli($this->host, $this->user, $this->password);
        $query = $this->conexion->query('CREATE DATABASE IF NOT EXISTS ' . $this->database);
        if ($query == 1) {
            return "OK";
        } else {
            return "Error";
        }
    }

    // funcion para crear tabla
    function createTable($nombre_tbl, $campos)
    {
        $result = @$this->conexion = new mysqli($this->host, $this->user, $this->password, $this->database);
        if ($result->connect_errno) {
            return $result->connect_errno;
        } else {
            // construccion del script
            $sql = 'CREATE TABLE IF NOT EXISTS ' . $nombre_tbl . ' (';
            $length_array = count($campos);
            $i = 1;
            foreach ($campos as $key => $value) {
                $sql .= $key . ' ' . $value;
                if ($i != $length_array) {
                    $sql .= ', ';
                } else {
                    $sql .= ');';
                }
                $i++;
            }

            $query =  $this->ejecutarQuery($sql);

            if ($query == 1) {
                return "OK";
            } else {
                return "Error";
            }
        }
    }

    function ejecutarQuery($query)
    {
        return $this->conexion->query($query);
    }

    function cerrarConexion()
    {
        $this->conexion->close();
    }

    function nuevaRestriccion($tabla, $restriccion)
    {
        $sql = 'ALTER TABLE ' . $tabla . ' ' . $restriccion;
        return $this->ejecutarQuery($sql);
    }

    // crear relciones
    function nuevaRelacion($from_tbl, $to_tbl, $fk_foreign_key_name, $from_field, $to_field)
    {
        $sql = 'ALTER TABLE ' . $from_tbl . ' ADD CONSTRAINT ' . $fk_foreign_key_name . ' FOREIGN KEY (' . $from_field . ') REFERENCES ' . $to_tbl . '(' . $to_field . ');';
        return $this->ejecutarQuery($sql);
    }

    // funcion para insertar informacion en tablas de base de datos
    function insertData($tabla, $data)
    {
        $sql = 'INSERT INTO ' . $tabla . ' (';
        $i = 1;
        foreach ($data as $key => $value) {
            $sql .= $key;
            if ($i < count($data)) {
                $sql .= ', ';
            } else $sql .= ')';
            $i++;
        }
        $sql .= ' VALUES (';
        $i = 1;
        foreach ($data as $key => $value) {
            $sql .= $value;
            if ($i < count($data)) {
                $sql .= ', ';
            } else $sql .= ');';
            $i++;
        }
        return $this->ejecutarQuery($sql);
    }

    // funcion para actualizar registro en la base de datos
    function actualizarRegistro($tabla, $data, $condicion)
    {
        $sql = 'UPDATE ' . $tabla . ' SET ';
        $i = 1;
        foreach ($data as $key => $value) {
            $sql .= $key . '=' . $value;
            if ($i < sizeof($data)) {
                $sql .= ', ';
            } else $sql .= ' WHERE ' . $condicion . ';';
            $i++;
        }
        return $this->ejecutarQuery($sql);
    }

    // funcion para eliminar registro en base de datos
    function eliminarRegistro($tabla, $condicion)
    {
        $sql = "DELETE FROM " . $tabla . " WHERE " . $condicion . ";";
        return $this->ejecutarQuery($sql);
    }

    // funcion para consultar informacion en DB
    function consultar($tablas, $campos, $condicion = "")
    {
        $sql = "SELECT ";
        $result = array_keys($campos);
        $ultima_key = end($result);
        foreach ($campos as $key => $value) {
            $sql .= $value;
            if ($key != $ultima_key) {
                $sql .= ", ";
            } else $sql .= " FROM ";
        }

        $result = array_keys($tablas);
        $ultima_key = end($result);
        foreach ($tablas as $key => $value) {
            $sql .= $value;
            if ($key != $ultima_key) {
                $sql .= ", ";
            } else $sql .= " ";
        }

        if ($condicion == "") {
            $sql .= ";";
        } else {
            $sql .= $condicion . ";";
        }
        return $this->ejecutarQuery($sql);
    }
}

// crar atributos para la tabla usuarios
class Usuarios
{
    public $nombreTabla = 'usuarios';
    public $data = [
        'email' => 'varchar(50) NOT NULL PRIMARY KEY',
        'nombre' => 'varchar(50) NOT NULL',
        'password' => 'varchar(255) NOT NULL',
        'fecha_nacimiento' => 'date NOT NULL'
    ];
}

// crar atributos para la tabla eventos
class Eventos
{
    public $nombreTabla = 'eventos';
    public $data = [
        'id' => 'INT NOT NULL PRIMARY KEY AUTO_INCREMENT',
        'titulo' => 'VARCHAR(50) NOT NULL',
        'fecha_inicio' => 'date NOT NULL',
        'hora_inicio' => 'varchar(20)',
        'fecha_fin' => 'varchar(20)',
        'hora_fin' => 'varchar(20)',
        'allday' => 'tinyint(1) NOT NULL',
        'fk_usuarios' => 'varchar(50) NOT NULL'
    ];
}