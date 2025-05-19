<?php
require_once('../includes/Database.class.php');
header("Content-Type: application/json");

$modelId = $_GET['model_id'] ?? null;

if (!$modelId) {
    echo json_encode(['success' => false, 'error' => 'Model ID requerit']);
    exit;
}

try {
    $db = new Database();
    $conn = $db->getConnection();

    $stmt = $conn->prepare("SELECT id, name, fabricant_id FROM model_camera WHERE id = :id");
    $stmt->execute([':id' => $modelId]);
    $model = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($model) {
        echo json_encode(['success' => true, 'data' => $model]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Model no trobat']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
