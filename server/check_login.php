<?php

require('./conector.php');
$con = new ConectorDB();
$response['conexion'] = $con->initConexion($con->database); // obtener el estado de la coenxión

if (isset($_SESSION['email'])) { // verificar que no haya ninguna sesión iniciada
    $response['msg'] = 'Redireccionando';
    $response['acceso'] = "Usuario Autorizado";
} else {
    if ($response['conexion'] == 'OK') { // si no existe sesión iniciada
        if ($con->verifyUsers() > 0) { // verifica si exite el usuario

            $resultado_consulta = $con->consultar(['usuarios'], ['email', 'password'], 'WHERE email="' . $_POST['username'] . '"');

            if ($resultado_consulta->num_rows != 0) { // si el resultado es mayor que 0 el email se encuentra registrado
                $fila = $resultado_consulta->fetch_assoc(); // recorrer los resultados
                if (password_verify($_POST['password'], $fila['password'])) { // verifica el password
                    $response['msg'] = 'Redireccionando';
                    $response['acceso'] = 'Usuario Autorizado';
                    $_SESSION['email'] = $fila['email'];
                } else {
                    $response['msg'] = 'Contraseña incorrecta';
                    $response['acceso'] = 'Acceso rechazado';
                }
            } else {
                $response['msg'] = 'Email incorrecto';
                $response['acceso'] = 'Acceso rechazado';
            }
        } else {
            $response['acceso'] = 'No existen usuarios registrados'; // mostrar alerta Si no existen usuarios registrados
            $response['msg'] = 'Presione el botón Inicializar Usuarios'; // enviar mensajes para registrar los usuarios
        }
    } else {
        $response['conexion'] = 'Error al iniciar la conexion'; // mensaje de error de conexión
    }
}
echo json_encode($response); // devolver resultado

$con->cerrarConexion(); // cerrar la conexión