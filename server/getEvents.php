<?php

require('./conector.php');
$con = new ConectorDB();
$response['msg'] = $con->initConexion($con->database); // iniciando conexion
if ($response['msg'] == 'OK') {
    $resultado = $con->consultar(['eventos'], ['*'], "WHERE fk_usuarios ='" . $_SESSION['email'] . "'", '');
    $i = 0;
    while ($fila = $resultado->fetch_assoc()) { // recorrer el arreglo de resultados
        $response['eventos'][$i]['id'] = $fila['id'];
        $response['eventos'][$i]['title'] = $fila['titulo'];
        if ($fila['allday'] == 0) { // verificar si el regitro esta fullday
            $allDay = false;
            $response['eventos'][$i]['start'] = $fila['fecha_inicio'] . 'T' . $fila['hora_inicio'];
            $response['eventos'][$i]['end'] = $fila['fecha_finalizacion'] . 'T' . $fila['hora_finalizacion'];
        } else {
            $allDay = true;
            $response['eventos'][$i]['start'] = $fila['fecha_inicio'];
            $response['eventos'][$i]['end'] = "";
        }
        $response['eventos'][$i]['allDay'] = $allDay;
        $i++;
    }
    $response['getData'] = "OK";
}
echo json_encode($response); // devolver resultados en json