<?php
require('./conector.php');
$con = new ConectorDB();
if ($con->initConexion($con->database) == 1049) { //Verificar si no existe la base de adtos al comparar que la respuesta del servidor sea iguale a 1049
    $conexion['msg'] = "Creando base de datos " . $con->database;
    $database = $con->newDatabase(); //Ejecutar función crear base de datos

    if ($database != "OK") { //Si existe un error

        $conexion['msg'] = "<h6><b>Error de privilegios</b></h6></br>El usuario <b>'$con->user'</b> no existe o no posee la permisología requerida para crear la base de datos <b>$con->database</b>. Si desea crear automaticamente la base de datos, ingrese los parámetros de un usuario phpmyadmin con permisos para crear bases de datos en las variables usuario <b>\$user </b> y contraseña <b>\$password</b> respectivamente en el archivo <b>conector.php</b> en la carpeta <b>server</b> del proyecto. O bien puede crearla manualmente desde el panel de control phpmyadmin."; //Mostrar mensaje

    } else { //Si se crea exitosamente

        $conexion['database'] = "OK"; //Estado OK
        $conexion['msg'] = "Base de datos creada con éxito"; //Mensaje de descripción de la acción

    }
} else {
    //Si la base de datos ya existe
    $conexion['database'] = "OK"; //Estado OK
    $conexion['msg'] = "Base de datos <b>" . $con->database . "</b> encontrada"; //Mensaje de descripción de acción
}
echo json_encode($conexion);