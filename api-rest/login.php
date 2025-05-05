<?php
// Capçaleres per permetre CORS (per a Flutter Web)
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Sense aixo no funciona per web
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

    include_once '../includes/Database.class.php';
    include_once '../includes/User.class.php';

    // Connecta amb la base de dades
    $database = new Database();
    $db = $database->getConnection();

    // Crea una instància de la classe User
    // i passa la connexió de la base de dades
    $user = new User($db);

    // Rebre les dades JSON de la petició
    $data = json_decode(file_get_contents("php://input"));

    // Comprova que hi ha correu i contrasenya
    if (!empty($data->email) && !empty($data->password)) {
        $user->email = $data->email;
        $password = $data->password;

        // Intenta fer login
        if ($user->login($password)) {
            echo json_encode([
                "missatge" => "Login correcte.",
                "usuari" => [
                    "id" => $user->id,
                    "username" => $user->username,
                    "email" => $user->email,
                    "role_id" => $user->role_id,
                    "active" => $user->active
                ]
            ]);
        } else {
            http_response_code(401); // No autoritzat
            echo json_encode(["missatge" => "Credencials incorrectes."]);
        }
    } else {
        http_response_code(400); // Petició incompleta
        echo json_encode(["missatge" => "Falten el correu i la contrasenya."]);
    }
?>
