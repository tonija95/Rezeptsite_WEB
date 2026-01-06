<?php
function getDbConnection() {
    require __DIR__ . '/db_access.php';

    $db_obj = new mysqli($host, $user, $password, $database);

    if ($db_obj->connect_error) {
        echo "Connection Error: " . $db_obj->connect_error;
    }
    
    return $db_obj;
}
?>