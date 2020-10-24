<?php
require('./conector.php');
$con = new ConectorDB();
$response['conexion'] = $con->initConexion($con->database);

if (isset($_SESSION['email'])) {
    $response['msg'] = 'OK';
    $response['acceso'] = 'Autorizado';
} else {
    if ($response['conexion'] == 'OK') {
        if ($con->verifyUsers() > 0) {
            $resultado_consulta = $con->consultar(['usuarios'], ['email', 'password'], 'WHERE email="' . $_POST['username'] . '"');
            if ($resultado_consulta->num_rows != 0) {
                $fila = $resultado_consulta->fetch_assoc();
                if (password_verify($_POST['password'], $fila['password'])) {
                    $response['msg'] = 'OK';
                    $response['acceso'] = 'Autorizado';
                    $_SESSION['email'] = $fila['email'];
                } else {
                    $response['msg'] = 'contraseña incorrecta';
                    $response['acceso'] = 'acceso denegado';
                }
            } else {
                $response['msg'] = 'email incorrecto';
                $response['acceso'] = 'acceso denegado';
            }
        } else {
            $response['conexion'] = 'No se pudo inciar la sesion';
        }
    }
}
echo json_encode($response);
$con->cerrarConexion();