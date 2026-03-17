<?php
session_start();
require 'db.php';

// ดึง Top 10 แยกตามระดับ เรียงตาม เวลาน้อย → moves น้อย
function getTop10($pdo, $difficulty) {
    $stmt = $pdo->prepare("SELECT player_name, time_taken, moves, created_at 
        FROM leaderboard 
        WHERE difficulty = ? AND is_win = 1
        ORDER BY time_taken ASC, moves ASC 
        LIMIT 10");
    $stmt->execute([$difficulty]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$easy_board   = getTop10($pdo, 'easy');
$medium_board = getTop10($pdo, 'medium');
$hard_board   = getTop10($pdo, 'hard');

$player_name = $_SESSION['player_name'] ?? 'ผู้เล่น';
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leaderboard 🏆</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #FFFFE8;
            color: white;
            min-height: 100vh;
            padding: 30px 20px;
        }

        h1 {
            text-align: center;
            color: #694E4E;
            margin-bottom: 8px;
            font-size: 32px;
        }

        .subtitle {
            text-align: center;
            color: #694E4E;
            margin-bottom: 30px;
        }

        /* แท็บระดับ */
        .tabs {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-bottom: 25px;
            flex-wrap: wrap;
        }

        .tab-btn {
            padding: 10px 28px;
            border-radius: 25px;
            border: 2px solid #FFAAB8;
            background: transparent;
            color: #694E4E;
            font-size: 15px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .tab-btn.active,
        .tab-btn:hover {
            background-color: #FFAAB8;
        }

        /* ตาราง */
        .board-section { display: none; max-width: 600px; margin: 0 auto; }
        .board-section.active { display: block; }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #AACB73;
            border-radius: 12px;
            overflow: hidden;
        }

        thead { background-color: ##FFFFE8; }

        th, td {
            padding: 14px 16px;
            text-align: center;
            font-size: 15px;
        }

        th { color: #694E4E; font-size: 13px; }

        tbody tr:nth-child(even) { background-color: #1a2a50; }
        tbody tr:hover { background-color: #243460; }

        /* อันดับพิเศษ */
        .rank-1 { color: #FFD700; font-size: 18px; }
        .rank-2 { color: #C0C0C0; font-size: 17px; }
        .rank-3 { color: #CD7F32; font-size: 17px; }

        .empty {
            text-align: center;
            padding: 40px;
            color: #694E4E;
        }

        /* ปุ่มเล่นใหม่ */
        .btn-play {
            display: block;
            width: fit-content;
            margin: 30px auto 0;
            padding: 14px 40px;
            background-color: #AACB73;
            color: #694E4E;
            border-radius: 8px;
            font-size: 16px;
            text-decoration: none;
            text-align: center;
            transition: background 0.3s;
        }

        .btn-play:hover { background-color: #FFD4D4; }
    </style>
</head>
<body>

<h1>🏆 Leaderboard</h1>
<p class="subtitle">สวัสดี <?= htmlspecialchars($player_name) ?>! นี่คืออันดับสูงสุด</p>

<!-- แท็บ -->
<div class="tabs">
    <button class="tab-btn active" onclick="showTab('easy')">🟢 Easy</button>
    <button class="tab-btn" onclick="showTab('medium')">🟡 Medium</button>
    <button class="tab-btn" onclick="showTab('hard')">🔴 Hard</button>
</div>

<!-- ตาราง Easy -->
<div class="board-section active" id="tab-easy">
    <?php if (empty($easy_board)): ?>
        <p class="empty">ยังไม่มีคะแนน Easy เลยครับ!</p>
    <?php else: ?>
    <table>
        <thead>
            <tr><th>อันดับ</th><th>ชื่อ</th><th>เวลา (วิ)</th><th>Moves</th></tr>
        </thead>
        <tbody>
            <?php foreach ($easy_board as $i => $row): ?>
            <tr>
                <td class="rank-<?= $i+1 ?>"><?= $i === 0 ? '🥇' : ($i === 1 ? '🥈' : ($i === 2 ? '🥉' : $i+1)) ?></td>
                <td><?= htmlspecialchars($row['player_name']) ?></td>
                <td><?= $row['time_taken'] ?></td>
                <td><?= $row['moves'] ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
</div>

<!-- ตาราง Medium -->
<div class="board-section" id="tab-medium">
    <?php if (empty($medium_board)): ?>
        <p class="empty">ยังไม่มีคะแนน Medium เลยครับ!</p>
    <?php else: ?>
    <table>
        <thead>
            <tr><th>อันดับ</th><th>ชื่อ</th><th>เวลา (วิ)</th><th>Moves</th></tr>
        </thead>
        <tbody>
            <?php foreach ($medium_board as $i => $row): ?>
            <tr>
                <td><?= $i === 0 ? '🥇' : ($i === 1 ? '🥈' : ($i === 2 ? '🥉' : $i+1)) ?></td>
                <td><?= htmlspecialchars($row['player_name']) ?></td>
                <td><?= $row['time_taken'] ?></td>
                <td><?= $row['moves'] ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
</div>

<!-- ตาราง Hard -->
<div class="board-section" id="tab-hard">
    <?php if (empty($hard_board)): ?>
        <p class="empty">ยังไม่มีคะแนน Hard เลยครับ!</p>
    <?php else: ?>
    <table>
        <thead>
            <tr><th>อันดับ</th><th>ชื่อ</th><th>เวลา (วิ)</th><th>Moves</th></tr>
        </thead>
        <tbody>
            <?php foreach ($hard_board as $i => $row): ?>
            <tr>
                <td><?= $i === 0 ? '🥇' : ($i === 1 ? '🥈' : ($i === 2 ? '🥉' : $i+1)) ?></td>
                <td><?= htmlspecialchars($row['player_name']) ?></td>
                <td><?= $row['time_taken'] ?></td>
                <td><?= $row['moves'] ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
</div>

<a href="index.php" class="btn-play">🎮 เล่นใหม่</a>

<script>
function showTab(level) {
    document.querySelectorAll('.board-section').forEach(s => s.classList.remove('active'));
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    document.getElementById('tab-' + level).classList.add('active');
    event.target.classList.add('active');
}
</script>

</body>
</html>