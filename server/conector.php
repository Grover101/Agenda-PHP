<?php
session_start();

class ConectorDB
{
    private $host = 'localhost';
    private $user = 'root';
    private $password = '';
    private $database = 'agendaDB';

    private $conexion;

    function initConexion($DataBase)
    {
        $this->conexion = new mysqli($this->host, $this->user, $this->password, $DataBase);
        if ($this->conexion->connect_error)
            return $this->conexion->connect_error;
        else
            return "OK";
    }

    function userSession()
    {
        if (isset($_SESSION['email']))
            $response['msg'] = $_SESSION['email'];
        else
            $response['msg'] = '';
        return json_encode($response);
    }

    function verifyUsers() // verificar si existen usuarios
    {
        $sql = 'SELECT COUNT(email) FROM usuarios;'; // contar numero de ususarios
        $totalUsers = $this->ejecutarQuery($sql); // ejecutar sentencia
        while ($row = $totalUsers->fetch_assoc())
            return $row['COUNT(email)']; // devuelve resultado
    }

    function getConexion()
    {
        return $this->conexion;
    }

    function ejecutarQuery($query)
    {
        return $this->conexion->query($query);
    }

    function cerrarConexion()
    {
        $this->conexion->close();
    }

    //Función para insertar información en tablas de base de datos
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

    //Función para actualizar registro en la base de datos
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

    //Función para eliminar registro en base de datos
    function eliminarRegistro($tabla, $condicion)
    {
        $sql = "DELETE FROM " . $tabla . " WHERE " . $condicion . ";";
        return $this->ejecutarQuery($sql);
    }

    // Funcion para consultar informacion
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