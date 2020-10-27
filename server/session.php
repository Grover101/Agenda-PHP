<?php

require('./conector.php');
$con = new ConectorDB();
echo $con->userSession();