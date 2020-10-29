<?php

require('./conector.php');
$con = new ConectorDB();
$response['conexion'] = $con->initConexion($con->database);
if ($response['conexion'] == 'OK') {
    // generar un arreglo con la información a enviar 
    $data['titulo'] = '"' . $_POST['titulo'] . '"';
    $data['fecha_inicio'] = '"' . $_POST['start_date'] . '"';
    $data['hora_inicio'] = '"' . $_POST['start_hour'] . ':00"';
    $data['fecha_fin'] = '"' . $_POST['end_date'] . '"';
    $data['hora_fin'] = '"' . $_POST['end_hour'] . ':00"';
    $data['allday'] = $_POST['allDay'];
    $data['fk_usuarios'] = '"' . $_SESSION['email'] . '"';

    if ($con->insertData('eventos', $data)) { // insertar la información en la base de datos
        $resultado = $con->consultar(['eventos'], ['MAX(id)']); // obtener el id registrado perteneciente al nuevo registro
        while ($fila = $resultado->fetch_assoc())
            $response['id'] = $fila['MAX(id)']; // enviar ultimo Id guardado como parámetro para el calendario
        $response['msg'] = 'OK';
    } else
        $response['msg'] = "Ha ocurrido un error al guardar el evento"; // mensaje de error
} else
    $response['msg'] = "Error en la comunicacion con la base de datos"; // mensaje de error en caso de conexion fallida
echo json_encode($response);