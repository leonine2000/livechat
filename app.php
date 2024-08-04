<?php
require_once('config/class/autoload.php');
require_once("config/functions/functions.php");

$databse = new Database;
$conn = $databse->connect();


if (isset($_POST['action']) && $_SERVER['REQUEST_METHOD'] === "POST" && $_POST['action'] === "startchat") {
    $action = sanitizeStringX($_POST['action']);


    if ($action) {
        $chat = new Chat();
        $data =  $chat->getModal();
        $response = array(
            "data" => $data,
            "status_code" => http_response_code(200)

        );
    } else {
        $response = array(
            "data" => null,
            "status_code" => http_response_code(500),
            "action" => $action

        );
    }

    echo json_encode($response);
    exit;
}



if (isset($_POST['getenv']) && $_POST['getenv'] === 'getenv' && $_SERVER['REQUEST_METHOD'] === "POST") {

    if (isset($_POST['type'])) {
        $type = sanitizeStringX($_POST['type']); // Assuming sanitizeString() function properly sanitizes input
    } else {
        // Handle case where 'type' parameter is missing
        http_response_code(400); // Bad Request
        echo json_encode(array("error" => "Missing 'type' parameter"));
        exit;
    }

    // Assuming Actions class exists and has returnEnv() method to fetch environment variables
    $action = new Actions();
    $key = $action->returnEnv($type);

    if ($key) {
        // Return the key as JSON response
        echo json_encode(array("key" => $key));
        exit;
    } else {
        // Handle case where key retrieval fails
        http_response_code(500); // Internal Server Error
        echo json_encode(array("error" => "Failed to retrieve API key"));
        exit;
    }
}




/* $action = $_GET['action'] ?? '';

switch ($action) {
    case 'saveMessage':
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data['session_id'], $data['sender'], $data['message'])) {
            echo json_encode(saveChatMessage($data['session_id'], $data['sender'], $data['message']));
        } else {
            echo json_encode(['success' => false, 'error' => 'Invalid input data']);
        }
        break;

    case 'getSessions':
        echo json_encode(getChatSessions());
        break;

    case 'getMessages':
        $session_id = $_GET['session_id'] ?? 0;
        echo json_encode(getChatMessages($session_id));
        break;

    default:
        echo json_encode(['success' => false, 'error' => 'Invalid action']);
        break;
}
 */
function saveChatMessage($sessionId, $sender, $message)
{
    global $conn;
    try {
        $stmt = $conn->prepare("INSERT INTO chat_messages (session_id, sender, message) VALUES (?, ?, ?)");
        $stmt->execute([$sessionId, $sender, $message]);
        return ['success' => true];
    } catch (Exception $e) {
        return ['success' => false, 'error' => $e->getMessage()];
    }
}

function getChatSessions()
{
    global $conn;
    try {
        $stmt = $conn->prepare("SELECT id, status FROM chat_sessions ORDER BY id DESC");
        return $stmt->get_result();
    } catch (Exception $e) {
        return ['success' => false, 'error' => $e->getMessage()];
    }
}

function getChatMessages($session_id)
{
    global $conn;
    try {
        $stmt = $conn->prepare("SELECT sender, message FROM chat_messages WHERE session_id = ?");
        $stmt->execute([$session_id]);
        return ['messages' => $stmt->get_result()];
    } catch (Exception $e) {
        return ['success' => false, 'error' => $e->getMessage()];
    }
}

?>