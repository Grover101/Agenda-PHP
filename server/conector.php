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

    function ejecutarQuery($query)
    {
        return $this->conexion->query($query);
    }

    function cerrarConexion()
    {
        $this->conexion->close();
    }

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