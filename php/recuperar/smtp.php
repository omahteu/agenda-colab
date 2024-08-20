<?php
require_once '../Database.php';
require_once '../Crud.php';

$database = new Database();
$db = $database->getConnection();

$crud = new Crud($db);

$query = "SELECT * FROM smtp_config LIMIT 1";
$result = $crud->read($query);

header('Content-Type: application/json');
echo json_encode($result);
?>
