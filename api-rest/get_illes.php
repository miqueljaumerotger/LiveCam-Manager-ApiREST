<?php
require_once('../includes/Database.class.php');

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

try {
    $db = new Database();
    $conn = $db->getConnection();

    $stmt = $conn->prepare("SELECT id, illa_name AS name FROM illes");
    $stmt->execute();

    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
?>
