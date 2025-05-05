<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once('../includes/Database.class.php');

$data = json_decode(file_get_contents("php://input"));

if (!isset($data->email)) {
    http_response_code(400);
    echo json_encode(["error" => "Camp email requerit"]);
    exit();
}

try {
    $db = new Database();
    $conn = $db->getConnection();

    $stmt = $conn->prepare("SELECT id FROM users WHERE email = :email");
    $stmt->bindParam(':email', $data->email);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $exists = false;

    if ($result) {
        // Si s'edita un usuari, no comptar el mateix
        if (!isset($data->user_id) || $result['id'] != $data->user_id) {
            $exists = true;
        }
    }

    echo json_encode(["exists" => $exists]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => "Error en consultar email"]);
}
