<?php
require_once 'db.php';
header('Content-Type: application/json');

$db = new MySqlDB();
$groups = $db->getAllEmailGroups();

echo json_encode(['groups' => $groups]);
?>
