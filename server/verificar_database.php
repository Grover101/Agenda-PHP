<?php

require('./conector.php');
$conexion = new ConectorDB();
$response['msg'] = $conexion->verifyConexion();
return $response['msg'];