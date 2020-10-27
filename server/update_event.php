<?php

require('./conector.php');
$con = new ConectorDB();
$response['conexion'] = $con->initConexion($con->database); // iniciar un conexion
if ($response['conexion'] == 'OK') { // verificacion de la conexion
    $data['id'] = '"' . $_POST['id'] . '"';
    $data['fecha_inicio'] = '"' . $_POST['start_date'] . '"';
    $data['hora_inicio'] = '"' . $_POST['start_hour'] . '"';
    $data['fecha_finalizacion'] = '"' . $_POST['end_date'] . '"';
    $data['hora_finalizacion'] = '"' . $_POST['end_hour'] . '"';

    if ($data['id'] != 'undefined') {
        $resultado = $con->actualizarRegistro('eventos', $data, 'id =' . $data['id']); // actualizar el registro que coincida con el id del evento a actualizar
        $response['msg'] = 'OK';
    } else
        $response['msg'] = "Ha ocurrido un error al actualizar el evento";
} else
    $response['msg'] = "Error en la comunicacion con la DB";

echo json_encode($response);