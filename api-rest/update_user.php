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

if (
    isset($data->id) &&
    isset($data->username) &&
    isset($data->email) &&
    isset($data->role_id) &&
    isset($data->active)
) {
    try {
        $db = new Database();
        $conn = $db->getConnection();

        $updateStmt = $conn->prepare("
            UPDATE users
            SET username = :username,
                email = :email,
                role_id = :role_id,
                active = :active
            WHERE id = :id
        ");

        $updateStmt->bindParam(':username', $data->username);
        $updateStmt->bindParam(':email', $data->email);
        $updateStmt->bindParam(':role_id', $data->role_id);
        $updateStmt->bindParam(':active', $data->active, PDO::PARAM_INT);
        $updateStmt->bindParam(':id', $data->id);

        if ($updateStmt->execute()) {
            $selectStmt = $conn->prepare("SELECT created_at FROM users WHERE id = :id");
            $selectStmt->bindParam(':id', $data->id);
            $selectStmt->execute();
            $user = $selectStmt->fetch(PDO::FETCH_ASSOC);

            echo json_encode([
                "missatge" => "Usuari actualitzat correctament.",
                "created_at" => $user['created_at'],
            ]);
        } else {
            echo json_encode(["error" => "No s’ha pogut actualitzar l’usuari."]);
        }
    } catch (Exception $e) {
        echo json_encode(["error" => "Error del servidor."]);
    }
} else {
    echo json_encode(["error" => "Falten camps obligatoris."]);
}
?>
