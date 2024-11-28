<?php
include '../koneksi/db.php';
include '../api/response.php';

$method = $_SERVER['REQUEST_METHOD'];
$id = $_GET['id'] ?? null;

if (in_array($method, ['GET', 'PUT', 'DELETE']) && !$id) {
    sendResponse(400, ['error' => 'ID is required']);
}

switch ($method) {
    case 'GET':
        $sql = $id ? "SELECT * FROM program WHERE id = ?" : "SELECT * FROM program";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($id ? [$id] : []);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        sendResponse($data ? 200 : 404, $data ?: ['error' => 'Program not found']);
        break;

    case 'POST':
        $name = $_POST['name'] ?? '';
        $description = $_POST['description'] ?? '';
        $duration = $_POST['duration'] ?? '';
        if (!$name || !$description || !$duration) 
        sendResponse(400, ['error' => 'All fields are required']);

        $stmt = $pdo->prepare("SELECT code FROM program ORDER BY code DESC LIMIT 1");
        $stmt->execute();
        $newCode = $stmt->fetchColumn() ? 'PR' . str_pad((int)substr($stmt->fetchColumn(), 2) + 1, 3, '0', STR_PAD_LEFT) : 'PR001';

        $pdo->prepare("INSERT INTO program (code, name, description, duration) VALUES (?, ?, ?, ?)")
            ->execute([$newCode, $name, $description, $duration]);
        sendResponse(201, ['message' => 'Program added', 'code' => $newCode]);
        break;

    case 'PUT':
        if (!$id) 
        sendResponse(400, ['error' => 'ID is required']);
        
        $stmt = $pdo->prepare("SELECT * FROM program WHERE id = ?");
        $stmt->execute([$id]);
        $existingData = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$existingData) sendResponse(404, ['error' => 'Program not found']);

        $name = $_POST['name'] ?? $existingData['name'];
        $description = $_POST['description'] ?? $existingData['description'];
        $duration = $_POST['duration'] ?? $existingData['duration'];

        $pdo->prepare("UPDATE program SET name = ?, description = ?, duration = ? WHERE id = ?")
            ->execute([$name, $description, $duration, $id]);
        sendResponse(200, ['message' => 'Program updated']);
        break;

    case 'DELETE':
        if (!$id) 
        sendResponse(400, ['error' => 'ID is required']);
        
        $stmt = $pdo->prepare("DELETE FROM program WHERE id = ?");
        $stmt->execute([$id]);
        sendResponse($stmt->rowCount() ? 200 : 404, ['message' => $stmt->rowCount() ? 'Program deleted' : 'Program not found']);
        break;

    default:
        sendResponse(405, ['error' => 'Method not allowed']);
        break;
}
?>
