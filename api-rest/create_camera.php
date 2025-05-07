<?php
require_once('../includes/Database.class.php');

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Rebem les dades JSON
$data = json_decode(file_get_contents("php://input"));

if (!$data) {
    echo json_encode(["success" => false, "error" => "Dades JSON no vàlides"]);
    exit();
}

try {
    $db = new Database();
    $conn = $db->getConnection();

    $stmt = $conn->prepare("
    INSERT INTO cameras (
        name, cam_ip, cam_username, cam_password, cam_port,
        location, latitude, longitude,
        url_preview, url_video,
        model_id, illa_id,
        active, status, created_by
    ) VALUES (
        :name, :cam_ip, :cam_username, :cam_password, :cam_port,
        :location, :latitude, :longitude,
        :url_preview, :url_video,
        :model_id, :illa_id,
        :active, :status, :created_by
    )
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
$stmt->bindValue(':model_id', !empty($data->model_id) ? $data->model_id : null, PDO::PARAM_INT);
$stmt->bindValue(':illa_id', !empty($data->illa_id) ? $data->illa_id : null, PDO::PARAM_STR);
$stmt->bindValue(':active', isset($data->active) ? (int)$data->active : 1, PDO::PARAM_INT); // ✅ Correcte per BIT
$stmt->bindValue(':status', $data->status ?? 'online');
$stmt->bindValue(':created_by', isset($data->created_by) ? (int)$data->created_by : null, PDO::PARAM_INT);

$stmt->execute();
   

    echo json_encode([
        "success" => true,
        "message" => "Càmera creada correctament."
    ]);
} catch (PDOException $e) {
    echo json_encode([
        "success" => false,
        "error" => "Error en inserir la càmera: " . $e->getMessage()
    ]);
}
?>
