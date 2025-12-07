<?php
require_once 'db_access.php';

$db_obj = new mysqli($host, $user, $password, $database);

if ($db_obj->connect_error) {
echo "Connection Error: " . $db_obj->connect_error;
exit();
}
$sql = "SELECT * FROM recipes";
$result = $db_obj->query($sql);
echo "<pre>" . print_r($result->fetch_assoc(), true) . "</pre>";
?>