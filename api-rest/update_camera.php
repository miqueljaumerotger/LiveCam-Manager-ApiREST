<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once('../includes/Database.class.php');

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

$data = json_decode(file_get_contents("php://input"));

if (!isset($data->id)) {
    echo json_encode(["success" => false, "error" => "Falten camps obligatoris."]);
    exit();
}

try {
    $db = new Database();
    $conn = $db->getConnection();

    $stmt = $conn->prepare("
        UPDATE cameras SET
            name = :name,
            cam_ip = :cam_ip,
            cam_username = :cam_username,
            cam_password = :cam_password,
            cam_port = :cam_port,
            location = :location,
            latitude = :latitude,
            longitude = :longitude,
            url_preview = :url_preview,
            url_video = :url_video,
            model_id = :model_id,
            illa_id = :illa_id,
            status = :status,
            active = :active
        WHERE id = :id
    ");

    $stmt->bindValue(':name', $data->name ?? '');
    $stmt->bindValue(':cam_ip', $data->cam_ip ?? '');
    $stmt->bindValue(':cam_username', $data->cam_username ?? '');
    $stmt->bindValue(':cam_password', $data->cam_password ?? '');
    $stmt->bindValue(':cam_port', $data->cam_port ?? '');
    $stmt->bindValue(':location', $data->location ?? '');
    $stmt->bindValue(':latitude', $data->latitude ?? '');
    $stmt->bindValue(':longitude', $data->longitude ?? '');
    $stmt->bindValue(':url_preview', $data->url_preview ?? '');
    $stmt->bindValue(':url_video', $data->url_video ?? '');
    $stmt->bindValue(':model_id', $data->model_id ?? null);
    $stmt->bindValue(':illa_id', $data->illa_id ?? null);
    $stmt->bindValue(':status', $data->status ?? '');
    $stmt->bindValue(':active', isset($data->active) ? (int)$data->active : 1, PDO::PARAM_INT);
    $stmt->bindValue(':id', $data->id);

    if ($stmt->execute()) {
        echo json_encode([
            "success" => true,
            "message" => "Càmera actualitzada correctament"
        ]);
    } else {
        echo json_encode(["success" => false, "error" => "No s’ha pogut actualitzar la càmera."]);
    }
} catch (Exception $e) {
    echo json_encode(["success" => false, "error" => "Error del servidor: " . $e->getMessage()]);
}
