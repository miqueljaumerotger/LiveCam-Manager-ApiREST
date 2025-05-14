<?php
require_once('../includes/Database.class.php');
header("Content-Type: application/json");

$fabricantId = $_GET['fabricant_id'] ?? null;

if (!$fabricantId) {
    echo json_encode(['success' => false, 'error' => 'Fabricant ID requerit']);
    exit;
}

try {
    $db = new Database();
    $conn = $db->getConnection();

    $stmt = $conn->prepare("SELECT id, name FROM model_camera WHERE fabricant_id = :id");
    $stmt->execute([':id' => $fabricantId]);
    $models = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['success' => true, 'data' => $models]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>