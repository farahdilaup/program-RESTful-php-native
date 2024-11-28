<?php
include '../koneksi/db.php';
include '../api/response.php';

$method = $_SERVER['REQUEST_METHOD'];
$id = $_GET['id'] ?? null;

switch ($method) {
    case 'GET':
        $sql = $id ? "SELECT * FROM program WHERE id = ?" : "SELECT * FROM program";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($id ? [$id] : []);
        $programs = $stmt->fetchAll(PDO::FETCH_ASSOC);
        sendResponse(200, $programs ?: ['error' => 'Program not found']);
        break;

    case 'POST':
        $name = $_POST['name'] ?? null;
        $description = $_POST['description'] ?? null;
        $duration = $_POST['duration'] ?? null;

        if (!$name || !$description || !$duration) {
            sendResponse(400, ['error' => 'All fields are required!']);
        }

        $stmt = $pdo->prepare("SELECT code FROM program ORDER BY code DESC LIMIT 1");
        $stmt->execute();
        $lastCode = $stmt->fetchColumn();
        $newCode = $lastCode ? 'PRG' . str_pad((int)substr($lastCode, 3) + 1, 2, '0', STR_PAD_LEFT) : 'PRG01';

        $stmt = $pdo->prepare("INSERT INTO program (code, name, description, duration) VALUES (?, ?, ?, ?)");
        $stmt->execute([$newCode, $name, $description, $duration]);

        sendResponse(201, ['message' => 'Program added successfully', 'code' => $newCode]);
        break;

    case 'PUT':
        if (!$id) sendResponse(400, ['error' => 'ID is required for update']);
        
        $stmt = $pdo->prepare("SELECT * FROM program WHERE id = ?");
        $stmt->execute([$id]);
        $existingData = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$existingData) sendResponse(404, ['error' => 'Program not found']);
        
        $name = $_POST['name'] ?? $existingData['name'];
        $description = $_POST['description'] ?? $existingData['description'];
        $duration = $_POST['duration'] ?? $existingData['duration'];

        $pdo->prepare("UPDATE program SET name = ?, description = ?, duration = ? WHERE id = ?")
            ->execute([$name, $description, $duration, $id]);
        sendResponse(200, ['message' => 'Program updated successfully']);
        break;

    case 'DELETE':
        if (!$id) sendResponse(400, ['error' => 'ID required for deletion!']);
        $stmt = $pdo->prepare("DELETE FROM program WHERE id = ?");
        $stmt->execute([$id]);

        sendResponse($stmt->rowCount() > 0 ? 200 : 404, ['message' => 'Program deleted successfully']);
        break;

    default:
        sendResponse(405, ['error' => 'Method not allowed']);
}
?>
