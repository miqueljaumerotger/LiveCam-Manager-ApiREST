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

if (!$data || !isset($data->id)) {
    echo json_encode(["success" => false, "error" => "ID o dades invàlides"]);
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
            active = :active,
            status = :status
        WHERE id = :id
    ");

    $stmt->execute([
        ':name' => $data->name ?? '',
        ':cam_ip' => $data->cam_ip ?? '',
        ':cam_username' => $data->cam_username ?? '',
        ':cam_password' => $data->cam_password ?? '',
        ':cam_port' => $data->cam_port ?? '',
        ':location' => $data->location ?? '',
        ':latitude' => $data->latitude ?? '',
        ':longitude' => $data->longitude ?? '',
        ':url_preview' => $data->url_preview ?? '',
        ':url_video' => $data->url_video ?? '',
        ':model_id' => !empty($data->model_id) ? $data->model_id : null,
        ':illa_id' => !empty($data->illa_id) ? $data->illa_id : null,
        ':active' => isset($data->active) ? (int)$data->active : 1,
        ':status' => $data->status ?? 'online',
        ':id' => $data->id,
    ]);

    echo json_encode(["success" => true, "message" => "Càmera actualitzada correctament"]);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
}
?>
