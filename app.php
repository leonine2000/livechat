<?php
require 'db.php';

$action = $_GET['action'] ?? '';

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

function saveChatMessage($sessionId, $sender, $message) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("INSERT INTO chat_messages (session_id, sender, message) VALUES (?, ?, ?)");
        $stmt->execute([$sessionId, $sender, $message]);
        return ['success' => true];
    } catch (Exception $e) {
        return ['success' => false, 'error' => $e->getMessage()];
    }
}

function getChatSessions() {
    global $pdo;
    try {
        $stmt = $pdo->query("SELECT id, status FROM chat_sessions ORDER BY id DESC");
        return $stmt->fetchAll();
    } catch (Exception $e) {
        return ['success' => false, 'error' => $e->getMessage()];
    }
}

function getChatMessages($session_id) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT sender, message FROM chat_messages WHERE session_id = ?");
        $stmt->execute([$session_id]);
        return ['messages' => $stmt->fetchAll()];
    } catch (Exception $e) {
        return ['success' => false, 'error' => $e->getMessage()];
    }
}
?>
?>
