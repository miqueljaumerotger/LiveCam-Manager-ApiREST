<?php
require_once('../includes/Database.class.php');

header("Access-Control-Allow-Origin: *"); // Permet peticions des de qualsevol origen (ideal per proves)
header("Access-Control-Allow-Methods: GET"); // Només acceptem GET
header("Content-Type: application/json; charset=UTF-8"); // Retornem JSON

// Gestió de peticions preflight (necessari per a Flutter Web i navegadors)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

    try {
        $db = new Database();
        $conn = $db->getConnection();

        $stmt = $conn->prepare("SELECT id, username, email, role_id, created_at, active FROM users");
        $stmt->execute();

        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode(["usuaris" => $users]);
    } catch (Exception $e) {
        echo json_encode(["error" => "Error en carregar usuaris"]);
    }
?>