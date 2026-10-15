<?php
define('BRIGHT_SPACE', true);
session_start();
require __DIR__ . '/../config/database.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['ok' => false, 'error' => 'Not logged in']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$itemId = $input['item_id'] ?? '';

if (!$itemId) {
    echo json_encode(['ok' => false, 'error' => 'No item']);
    exit;
}

$user = current_user();
$userId = $user['id'];

// Cek sudah dimiliki?
$ada = db_one('SELECT id FROM user_items WHERE user_id = ? AND item_id = ?', [$userId, $itemId]);
if ($ada) {
    echo json_encode(['ok' => true, 'message' => 'Sudah dimiliki']);
    exit;
}

// Item dari config
require __DIR__ . '/../config/planets.php';
$itemData = null;
foreach ($items as $it) {
    if ($it['id'] === $itemId) { $itemData = $it; break; }
}

if (!$itemData) {
    echo json_encode(['ok' => false, 'error' => 'Item tidak ditemukan']);
    exit;
}

if ($user['star_coins'] < $itemData['harga']) {
    echo json_encode(['ok' => false, 'error' => 'Koin tidak cukup']);
    exit;
}

// Potong koin & simpan item
db_exec('UPDATE users SET star_coins = star_coins - ? WHERE id = ?', [$itemData['harga'], $userId]);
db_exec('INSERT INTO user_items (user_id, item_id, is_equipped) VALUES (?, ?, 1)', [$userId, $itemId]);

// Update avatar di user
if ($itemData['slot'] === 'topi') {
    db_exec('UPDATE users SET avatar_topi = ? WHERE id = ?', [$itemId, $userId]);
} elseif ($itemData['slot'] === 'kacamata') {
    db_exec('UPDATE users SET avatar_kacamata = ? WHERE id = ?', [$itemId, $userId]);
}

echo json_encode(['ok' => true]);