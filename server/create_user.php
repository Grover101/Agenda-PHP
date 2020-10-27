<?php
require('./conector.php');

$con = new ConectorDB(); // iniciar un nuevo objeto ConectorDB

$response['conexion'] = $con->initConexion($con->database); // iniciar la conexion con la base de datos

if ($response['conexion'] == 'OK') { //  si la conexión es exitosa

    $conexion = $con->getConexion(); // obtener la conexión
    $insert = $conexion->prepare('INSERT INTO usuarios (email, nombre, password, fecha_nacimiento) VALUES (?,?,?,?)'); // insertar usuarios a travéz de de la interfaz de objetos de datos PDO

    $insert->bind_param("ssss", $email, $nombre, $password, $fecha_nacimiento); // definir el tipo de variable como string, seguido de los valores de las variables

    // definir los valores de las variables a insertar en la base de datos
    $defaultPassword = '123'; // contrasenia por defecto para todos los usuarios
    $email = "hola@gmail.com";
    $nombre = "Usuario Hola";
    $password = password_hash($defaultPassword, PASSWORD_DEFAULT); // encriptar la contraseña
    $fecha_nacimiento = "2000-10-20";
    // ejecutar la sentencia
    $insert->execute();

    // otra insercion
    $email = "estudiante@gmail.com";
    $nombre = "Estudiante";
    $password = password_hash($defaultPassword, PASSWORD_DEFAULT);
    $fecha_nacimiento = "1999-05-17";
    $insert->execute();

    $email = "nextu@gmail.com";
    $nombre = "Nextu";
    $password = password_hash($defaultPassword, PASSWORD_DEFAULT);
    $fecha_nacimiento = "2005-08-22";
    $insert->execute();

    $response['resultado'] = "1"; // devolver resultado positivo
    $response['msg'] = 'Información de inicio de sesion:</br>email:'; // mostrar mensaje con la infirmación de los usuarios guardados
    $getUsers = $con->consultar(['usuarios'], ['*'], $condicion = ""); // obtener los usuarios generados anteriormente
    while ($fila = $getUsers->fetch_assoc())
        $response['msg'] .= $fila['email'];
    $response['msg'] .= '</br>contraseña: ' . $defaultPassword; // mostrar la contraseña por defecto
} else {
    $response['resultado'] = "0"; // resultado de error
    $response['msg'] = "No se pudo conectar a la base de datos"; // mosrtar mensaje de error
}

echo json_encode($response);