<?php
require_once('../includes/Database.class.php');

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

try {
    $db = new Database();
    $conn = $db->getConnection();

    $stmt = $conn->prepare("SELECT * FROM cameras WHERE active = 1");
    $stmt->execute();

    $cameras = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "success" => true,
        "data" => $cameras
    ]);
} catch (PDOException $e) {
    echo json_encode([
        "success" => false,
        "error" => "Error en obtenir càmeres: " . $e->getMessage()
    ]);
}
?>
