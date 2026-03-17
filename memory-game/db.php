<?php
// SQLite ไม่ต้องติดตั้งอะไรเพิ่ม PHP มีมาในตัวแล้ว
$db_path = __DIR__ . '/memory_game.db';

try {
    $pdo = new PDO("sqlite:" . $db_path);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // สร้างตาราง leaderboard อัตโนมัติถ้ายังไม่มี
    $pdo->exec("CREATE TABLE IF NOT EXISTS leaderboard (
        id          INTEGER PRIMARY KEY AUTOINCREMENT,
        player_name TEXT    NOT NULL,
        difficulty  TEXT    NOT NULL,
        time_taken  INTEGER NOT NULL,
        moves       INTEGER NOT NULL,
        is_win      INTEGER DEFAULT 1,
        created_at  DATETIME DEFAULT CURRENT_TIMESTAMP
    )");

} catch (Exception $e) {
    die("Database error: " . $e->getMessage());
}
?>