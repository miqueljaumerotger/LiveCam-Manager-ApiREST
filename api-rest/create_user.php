<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Sense aixo no funciona per web
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

    // Incloure els fitxers necessaris
    include_once '../includes/Database.class.php';
    include_once '../includes/User.class.php';

    // Crear connexió
    $database = new Database();
    $db = $database->getConnection();

    // Crear instància d'usuari
    $user = new User($db);

    // Rebre les dades JSON de la petició
    $data = json_decode(file_get_contents("php://input"));

    // Comprovar si les dades són vàlides
    if (!empty($data->username) && !empty($data->email) && !empty($data->password)) {
        $user->username = $data->username;
        $user->email = $data->email;
        $user->password_hash = $data->password;
        $user->role_id = isset($data->role_id) ? $data->role_id : null;

        // Intenta crear l'usuari a la base de dades
        if ($user->create_user()) {
            http_response_code(201); // Creat
            echo json_encode(["missatge" => "Usuari creat correctament."]);
        } else {
            http_response_code(503); // Error de servidor
            echo json_encode(["missatge" => "Error en crear l'usuari."]);
        }
    } else {
        http_response_code(400); // Dades incorrectes
        echo json_encode(["missatge" => "Falten dades obligatòries."], JSON_UNESCAPED_UNICODE); // JSON_UNESCAPED_UNICODE per evitar problemes amb caràcters especials
    }
?>
