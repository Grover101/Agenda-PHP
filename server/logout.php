<?php

session_start(); // iniciar el manejador de sesiones
if (isset($_SESSION['email'])) { // verificar si existe una sesion
    session_destroy(); // destruir la sesion actual
    $response['msg'] = 'Redireccionar'; // redireccion
} else
    $response['msg'] = 'Sesion no iniciada'; // mostrar el munsaje en caso contrario que no exista una sesion
echo json_encode($response); // devolver respuestacondition