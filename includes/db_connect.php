<?php

function getDbConnection(): mysqli
{
    require __DIR__ . '/db_access.php';

    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    try {
        $db = new mysqli($host, $user, $password, $database);
        $db->set_charset('utf8mb4');
        return $db;
    } catch (Throwable $e) {
        http_response_code(500);
        exit('Database connection failed');
    }
}
