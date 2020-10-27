<?php

require('./conector.php');
$con = new ConectorDB();
$response['conexion'] = $con->initConexion($con->database);
if ($response['conexion'] == 'OK')
    if ($con->eliminarRegistro('eventos', 'id=' . $_POST['id']))
        $response['msg'] = 'OK';
    else
        $response['msg'] = "Ha ocurrido un error al Eliminar el evento";
else
    $response['msg'] = "Error en la comunicacion con la base de datos";
echo json_encode($response);