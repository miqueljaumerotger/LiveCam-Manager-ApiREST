<?php
require_once('../includes/Database.class.php');

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

$data = json_decode(file_get_contents("php://input"));

if (!$data) {
    echo json_encode(["success" => false, "error" => "Dades JSON no vàlides"]);
    exit();
}

try {
    $db = new Database();
    $conn = $db->getConnection();

    $stmt = $conn->prepare("
        INSERT INTO model_camera (name, description, stream1, stream2, stream3, fabricant_id)
        VALUES (:name, :description, :stream1, :stream2, :stream3, :fabricant_id)
    ");

    $stmt->execute([
        ':name' => $data->name ?? '',
        ':description' => $data->description ?? '',
        ':stream1' => $data->stream1 ?? '',
        ':stream2' => $data->stream2 ?? '',
        ':stream3' => $data->stream3 ?? '',
        ':fabricant_id' => $data->fabricant_id ?? null,
    ]);

    $lastId = $conn->lastInsertId();

    echo json_encode([
        "success" => true,
        "message" => "Model creat correctament.",
        "model_id" => $lastId
    ]);
} catch (PDOException $e) {
    echo json_encode([
        "success" => false,
        "error" => "Error en inserir el model: " . $e->getMessage()
    ]);
}
?>
