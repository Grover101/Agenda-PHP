<?php

require('./conector.php');
$con = new ConectorDB();
$response['conexion'] = $con->initConexion($con->database);
if ($response['conexion'] == 'OK') {
    // generar un arreglo con la información a enviar 
    $data['titulo'] = '"' . $_POST['titulo'] . '"';
    $data['fecha_inicio'] = '"' . $_POST['start_date'] . '"';
    $data['hora_inicio'] = '"' . $_POST['start_hour'] . ':00"';
    $data['fecha_finalizacion'] = '"' . $_POST['end_date'] . '"';
    $data['hora_finalizacion'] = '"' . $_POST['end_hour'] . ':00"';
    $data['allday'] = $_POST['allDay'];
    $data['fk_usuarios'] = '"' . $_SESSION['email'] . '"';

    if ($con->insertData('eventos', $data)) { // Insertar la información en la base de datos
        $resultado = $con->consultar(['eventos'], ['MAX(id)']); // Obtener el id registrado perteneciente al nuevo registro
        while ($fila = $resultado->fetch_assoc())
            $response['id'] = $fila['MAX(id)']; // Enviar ultimo Id guardado como parámetro para el calendario
        $response['msg'] = 'OK';
    } else
        $response['msg'] = "Ha ocurrido un error al guardar el evento"; // Mensaje de error
} else
    $response['msg'] = "Error en la comunicacion con la base de datos"; // Mensaje de error en caso de conexion fallida
echo json_encode($response);