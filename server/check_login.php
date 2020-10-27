<?php

require('./conector.php'); //requerir el archivo conector.php
$con = new ConectorDB(); //Iniciar el objeto ConectorBD
$response['conexion'] = $con->initConexion($con->database); //Obtener el estado de la coenxión

if (isset($_SESSION['email'])) { //Verificar que no haya ninguna sesión iniciada
    $response['msg'] = 'Redireccionando'; //Enviar mensaje de redirección
    $response['acceso'] = "Usuario Autorizado"; //Devolver el estado de sesión iniciada
} else {
    if ($response['conexion'] == 'OK') { //Si no existe sesión iniciada
        if ($con->verifyUsers() > 0) { //Verificar que la base de datos tenga usuarios registrados con la función verifyUsers
            //Verificar que el email del usuario está registrado
            $resultado_consulta = $con->consultar(['usuarios'], ['email', 'password'], 'WHERE email="' . $_POST['username'] . '"');

            if ($resultado_consulta->num_rows != 0) { //Si el resultado es mayor que 0 el email se encuentra registrado
                $fila = $resultado_consulta->fetch_assoc(); //recorrer los resultados
                if (password_verify($_POST['password'], $fila['password'])) { //Verificar que la contraseña ingresada corresponda con el usuario de manera encriptada
                    $response['msg'] = 'Redireccionando'; //Enviar mensaje de redirección
                    $response['acceso'] = 'Usuario Autorizado'; //Validar el acceso del usuario
                    $_SESSION['email'] = $fila['email']; //Asiganar la sesion al usuario actual
                } else {
                    $response['msg'] = 'Contraseña incorrecta'; //Si la contraseña no existe mostrar mensaje
                    $response['acceso'] = 'Acceso rechazado'; //Estado del acceso
                }
            } else {
                $response['msg'] = 'Email incorrecto'; //Mensaje si el email no existe
                $response['acceso'] = 'Acceso rechazado'; //Estado del acceso
            }
        } else {
            $response['acceso'] = 'No existen usuarios registrados'; //Mostrar alerta Si no existen usuarios registrados
            $response['msg'] = 'Presione el botón Inicializar Usuarios'; //Enviar mensajes para registrar los usuarios
        }
    } else {
        $response['conexion'] = 'Error al iniciar la conexion'; //Mensaje de error de conexión
    }
}
echo json_encode($response); //Devolver resultado
$con->cerrarConexion(); //Cerrar la conexión