<?php
defined('BRIGHT_SPACE') or exit('Akses langsung tidak diizinkan.');

define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'bright_space');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

function db()
{
    static $pdo = null;
    if ($pdo === null) {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
        } catch (PDOException $e) {
            http_response_code(500);
            exit('BRIGHT SPACE: Gagal konek database. Pastikan MySQL aktif & DB "bright_space" sudah di-import. Error: ' . htmlspecialchars($e->getMessage()));
        }
    }
    return $pdo;
}

function db_one($sql, $params = [])
{
    $stmt = db()->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetch() ?: null;
}

function db_all($sql, $params = [])
{
    $stmt = db()->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

function db_exec($sql, $params = [])
{
    $stmt = db()->prepare($sql);
    return $stmt->execute($params);
}

function db_insert($sql, $params = [])
{
    db_exec($sql, $params);
    return (int) db()->lastInsertId();
}

function current_user()
{
    if (!isset($_SESSION['user_id'])) { return null; }
    return db_one('SELECT * FROM users WHERE id = ?', [$_SESSION['user_id']]);
}

function require_login()
{
    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }
}

function get_soal($planetId, $modeType, $limit = 15)
{
    $rows = db_all(
        'SELECT * FROM soal_bank WHERE planet_id = ? AND mode_type = ? ORDER BY RAND() LIMIT ' . (int) $limit,
        [$planetId, $modeType]
    );
    $hasil = [];
    foreach ($rows as $r) {
        $hasil[] = [
            'pertanyaan'  => $r['pertanyaan'],
            'pilihan'     => [
                'A' => $r['pilihan_a'],
                'B' => $r['pilihan_b'],
                'C' => $r['pilihan_c'],
            ],
            'jawaban'     => $r['jawaban'],
            'penjelasan'  => $r['penjelasan'],
            'umpan_salah' => [
                'A' => $r['umpan_salah_a'],
                'B' => $r['umpan_salah_b'],
                'C' => $r['umpan_salah_c'],
            ],
        ];
    }
    return $hasil;
}