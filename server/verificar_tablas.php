<?php
require('./conector.php'); //Requiere el archivo conector.php
$con = new ConectorDB(); //Iniciar el objeto ConectorBD
$usuarios = new Usuarios(); //Iniciar el objeto Usuario
$eventos = new Eventos(); //Iniciar el objeto Eventos
//Inicializar varialbes
$response['detalle'] = "Se han encontrado los siguientes errores:</br><ol>";
$resonse['usuarios'] = "";
$response['eventos'] = '';

//Iniciar la función crear tabla createTable con la información del objeto Eventos
$result = $con->createTable($usuarios->nombreTabla, $usuarios->data); //Ejecutar consulta para crear tabla
if ($result == "OK") { //Si el resultado es OK
    $response['msg'] = 'OK';
    $response['detalle'] = "OK";
    $response['usuarios'] = 'OK';
} else { //Si no, agregar error al listado de errores
    $response['detalle'] .= "<li>Error al crear la tabla usuarios.</li>";
}
//Iniciar la función crear tabla createTable con la información del objeto Eventos
$result = $con->createTable($eventos->nombreTabla, $eventos->data);
if ($result == "OK") { //Si el resultado es correcto
    $response['msg'] = 'OK';
    $response['detalle'] = "OK";
    $response['eventos'] = 'OK';
} else { //Si no, agregar error al listado de errores
    $response['detalle'] .= "<li>Error al crear la tabla eventos.</li>";
}

if ($response['eventos'] == 'OK' and $response['usuarios'] == 'OK') { //Si las tablas evento y detalle se encuentran en la base de datos
    //Crear un Índice (index) en la columna fk_usuarios de la tabla eventos
    $result =  $con->nuevaRestriccion($eventos->nombreTabla, 'ADD KEY fk_usuarios (fk_usuarios)');
    if ($result == "OK") {
        $response['Index'] = 'OK';
        $response['detalle'] = 'OK';
    }
    //Crear una relación entre las tablas eventos y usuarios asignando a la tabla eventos el valor email a través de una clave foránea
    $result =  $con->nuevaRelacion($eventos->nombreTabla, $usuarios->nombreTabla, 'fk_usuarioemail_evento', 'fk_usuarios', 'email'); //nombre de la tabla origen, nomvre tabla destino, nombre de la clave foranea, nombre de la columna origen, nombre de columna destino
    if ($result == "OK") {
        $response['Clave Foránea'] = 'OK';
        $response['detalle'] = 'OK';
    }
} else {
    $response['detalle'] .= '</ul> </br>Verifique que los datos del usuario utilizado para realizar la conexión en el archivo <code>conector.php</code> cuentr con permisos administrativos en phpmyadmin';
    $response['msg'] = $response['detalle'];
}

echo json_encode($response); //Devolver resultado