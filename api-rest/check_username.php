<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Preflight per CORS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once '../includes/Database.class.php';

$data = json_decode(file_get_contents("php://input"));

if (!isset($data->username)) {
    http_response_code(400);
    echo json_encode(["error" => "Falta el nom d'usuari"]);
    exit();
}

try {
    $db = (new Database())->getConnection();

    $stmt = $db->prepare("SELECT id FROM users WHERE username = :username");
    $stmt->bindParam(':username', $data->username);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $exists = false;

    if ($result) {
        // Si existeix i no és el mateix usuari que s'està editant
        if (!isset($data->user_id) || $result['id'] != $data->user_id) {
            $exists = true;
        }
    }

    echo json_encode(["exists" => $exists]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => "Error del servidor"]);
}
