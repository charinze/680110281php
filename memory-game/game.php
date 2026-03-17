<?php
session_start();

// ===== รับค่าจาก index.php =====
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['player_name'] = htmlspecialchars(trim($_POST['player_name']));
    $_SESSION['difficulty']  = $_POST['difficulty'];
}

// ถ้าไม่มีข้อมูล session ให้กลับหน้าแรก
if (empty($_SESSION['player_name']) || empty($_SESSION['difficulty'])) {
    header("Location: index.php");
    exit;
}

$difficulty  = $_SESSION['difficulty'];
$player_name = $_SESSION['player_name'];

// ===== กำหนดข้อมูลไพ่ตามระดับ =====

// Easy: จับคู่ สี ↔ สี (4 คู่ = 8 ใบ)
$easy_cards = [
    ['id' => 'red',    'display' => '<div style="width:60px;height:60px;border-radius:50%;background:#e74c3c;"></div>', 'type' => 'color', 'label' => ''],
    ['id' => 'blue',   'display' => '<div style="width:60px;height:60px;border-radius:50%;background:#3498db;"></div>', 'type' => 'color', 'label' => ''],
    ['id' => 'yellow', 'display' => '<div style="width:60px;height:60px;border-radius:50%;background:#f1c40f;"></div>', 'type' => 'color', 'label' => ''],
    ['id' => 'green',  'display' => '<div style="width:60px;height:60px;border-radius:50%;background:#2ecc71;"></div>', 'type' => 'color', 'label' => ''],
];
// Medium: จับคู่ ภาพสัตว์ ↔ ชื่อภาษาไทย (6 คู่ = 12 ใบ)
$medium_cards = [
    ['id' => 'cat', 'emoji' => '<img src="images/cat1.jpg" width="140" height="140" style="border-radius:8px;object-fit:cover;">', 'name' => 'แมว'],
    ['id' => 'dog',      'emoji' => '<img src="images/dog1.jpg" width="140" height="140" style="border-radius:8px;object-fit:cover;">', 'name' => 'หมา'],
    ['id' => 'rabbit',   'emoji' => '<img src="images/rabbit1.png" width="140" height="140" style="border-radius:8px;object-fit:cover;">', 'name' => 'กระต่าย'],
    ['id' => 'bear',     'emoji' => '<img src="images/bear1.webp" width="140" height="140" style="border-radius:8px;object-fit:cover;">', 'name' => 'หมี'],
    ['id' => 'panda',    'emoji' => '<img src="images/panda1.webp" width="140" height="140" style="border-radius:8px;object-fit:cover;">', 'name' => 'แพนด้า'],
    ['id' => 'elephant', 'emoji' => '<img src="images/ele1.jpg" width="140" height="140" style="border-radius:8px;object-fit:cover;">', 'name' => 'ช้าง'],
];

// Hard: จับคู่ ธงชาติ ↔ ชื่อประเทศ (10 คู่ = 20 ใบ)
$hard_cards = [
    ['id' => 'th', 'flag' => '<img src="https://flagcdn.com/w80/th.png" width="90" style="border-radius:4px;">', 'name' => 'Thailand'],
    ['id' => 'my', 'flag' => '<img src="https://flagcdn.com/w80/my.png" width="90" style="border-radius:4px;">', 'name' => 'Malaysia'],
    ['id' => 'sg', 'flag' => '<img src="https://flagcdn.com/w80/sg.png" width="90" style="border-radius:4px;">', 'name' => 'Singapore'],
    ['id' => 'id', 'flag' => '<img src="https://flagcdn.com/w80/id.png" width="90" style="border-radius:4px;">', 'name' => 'Indonesia'],
    ['id' => 'ph', 'flag' => '<img src="https://flagcdn.com/w80/ph.png" width="90" style="border-radius:4px;">', 'name' => 'Philippines'],
    ['id' => 'vn', 'flag' => '<img src="https://flagcdn.com/w80/vn.png" width="90" style="border-radius:4px;">', 'name' => 'Vietnam'],
    ['id' => 'mm', 'flag' => '<img src="https://flagcdn.com/w80/mm.png" width="90" style="border-radius:4px;">', 'name' => 'Myanmar'],
    ['id' => 'kh', 'flag' => '<img src="https://flagcdn.com/w80/kh.png" width="90" style="border-radius:4px;">', 'name' => 'Cambodia'],
    ['id' => 'la', 'flag' => '<img src="https://flagcdn.com/w80/la.png" width="90" style="border-radius:4px;">', 'name' => 'Laos'],
    ['id' => 'bn', 'flag' => '<img src="https://flagcdn.com/w80/bn.png" width="90" style="border-radius:4px;">', 'name' => 'Brunei'],
];

// ===== สร้าง array ไพ่ทั้งหมด (สร้าง 2 ใบต่อคู่) =====
$cards = [];

if ($difficulty === 'easy') {
    // Easy: ไพ่ทั้งสองใบเหมือนกัน (สี ↔ สี)
    foreach ($easy_cards as $c) {
        // ใบที่ 1
        $cards[] = ['pair_id' => $c['id'], 'display' => $c['display'], 'sub' => $c['label'], 'card_type' => 'same'];
        // ใบที่ 2
        $cards[] = ['pair_id' => $c['id'], 'display' => $c['display'], 'sub' => $c['label'], 'card_type' => 'same'];
    }
} elseif ($difficulty === 'medium') {
    // Medium: ภาพสัตว์ ↔ ชื่อภาษาไทย
    foreach ($medium_cards as $c) {
        // ใบที่ 1: แสดง emoji สัตว์
        $cards[] = ['pair_id' => $c['id'], 'display' => $c['emoji'], 'sub' => '', 'card_type' => 'image'];
        // ใบที่ 2: แสดงชื่อภาษาไทย
        $cards[] = ['pair_id' => $c['id'], 'display' => $c['name'], 'sub' => '', 'card_type' => 'name'];
    }
} else {
    // Hard: ธงชาติ ↔ ชื่อประเทศ
    foreach ($hard_cards as $c) {
        // ใบที่ 1: แสดงธง
        $cards[] = ['pair_id' => $c['id'], 'display' => $c['flag'], 'sub' => '', 'card_type' => 'flag'];
        // ใบที่ 2: แสดงชื่อประเทศ
        $cards[] = ['pair_id' => $c['id'], 'display' => $c['name'], 'sub' => '', 'card_type' => 'country'];
    }
}

// ===== สับไพ่แบบสุ่ม =====
shuffle($cards);

// ===== กำหนด grid columns ตามระดับ =====
$cols = ['easy' => 4, 'medium' => 4, 'hard' => 5];
$grid_cols = $cols[$difficulty];

// ===== กำหนดเวลา (วินาที) ตามระดับ =====
$time_limits = ['easy' => 60, 'medium' => 90, 'hard' => 120];
$time_limit  = $time_limits[$difficulty];

$total_pairs = count($cards) / 2;
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Memory Card Game - เกม</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #FFFFE8;
            color: #3E2C23;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
        }

        /* ===== Header ===== */
        .header {
            width: 100%;
            max-width: 700px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #CDE990;
            padding: 15px 25px;
            border-radius: 12px;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 10px;
        }

        .header h2 { color: #694E4E; font-size: 25px; }

        .stats {
            display: flex;
            gap: 30px;
            flex-wrap: wrap;
        }

        .stat-box {
            text-align: center;
            background-color: #AACB73;
            padding: 8px 16px;
            border-radius: 8px;
            min-width: 80px;
        }

        .stat-box .stat-label { font-size: 14px; color: #694E4E; }
        .stat-box .stat-value { font-size: 22px; font-weight: bold; color: #694E4E; }

        /* ===== กระดานไพ่ ===== */
        .board {
            display: grid;
            grid-template-columns: repeat(<?= $grid_cols ?>, 1fr);
            gap: 12px;
            max-width: 750px;
            width: 100%;
        }

        /* ===== ไพ่แต่ละใบ ===== */
        .card {
            aspect-ratio: 1;
            cursor: pointer;
            perspective: 1000px; /* สำหรับ 3D flip */
        }

        .card-inner {
            width: 100%;
            height: 100%;
            position: relative;
            transform-style: preserve-3d;
            transition: transform 0.5s ease;
            border-radius: 10px;
        }

        /* เมื่อไพ่ถูกพลิก */
        .card.flipped .card-inner {
            transform: rotateY(180deg);
        }

        /* ไพ่ที่จับคู่ได้แล้ว */
        .card.matched .card-inner {
            transform: rotateY(180deg);
        }

        .card.matched .card-back {
            background-color: #CDE990;
            border-color: #AACB73;
        }

        /* หน้าหลัง (ที่มองเห็นก่อนพลิก) */
        .card-front,
        .card-back {
            position: absolute;
            width: 100%;
            height: 100%;
            backface-visibility: hidden;
            border-radius: 10px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            font-size: 2em;
            border: 2px solid #FFAAB8;
        }

        /* หน้าปิด (ลาย ?) */
        .card-front {
            background-color: #FFD4D4;
            font-size: 2.5em;
        }

        /* หน้าเปิด (เนื้อหาไพ่) */
        .card-back {
            background-color: #F8F7BA;
            transform: rotateY(180deg); /* ซ่อนอยู่ก่อน */
            padding: 10px;
            text-align: center;
            font-size: 1.7em;
        }

        .card-back .card-name {
            font-size: 0.45em;
            color: #694E4E;
            margin-top: 4px;
        }

        /* ===== ป้ายระดับ ===== */
        .badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: bold;
        }

        .badge-easy   { background-color: #1a6b3c; color: #2ecc71; }
        .badge-medium { background-color: #7d5500; color: #f39c12; }
        .badge-hard   { background-color: #7d1a1a; color: #e74c3c; }

        /* ===== หน้าจอจบเกม ===== */
        #result-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.85);
            align-items: center;
            justify-content: center;
            z-index: 100;
        }

        #result-overlay.show { display: flex; }

        .result-box {
            background-color: #AACB73;
            border: 2px solid #FFAAB8;
            border-radius: 15px;
            padding: 40px;
            text-align: center;
            max-width: 350px;
            width: 90%;
        }

        .result-box h2 { color: #694E4E; font-size: 28px; margin-bottom: 10px; }
        .result-box p  { color: #694E4E; margin: 8px 0; font-size: 16px; }
        .result-box .big { font-size: 22px; color: white; font-weight: bold; }

        .btn-result {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 30px;
            background-color: #FFAAB8;
            color: #694E4E;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.3s;
        }

        .btn-result:hover { background-color: #FFD4D4; }

        /* ===== Responsive ===== */
        @media (max-width: 480px) {
            .board { gap: 8px; }
            .card-front, .card-back { font-size: 1.6em; }
        }
    </style>
</head>
<body>

<!-- ===== Header: ชื่อ / ระดับ / เวลา / moves / คู่ที่จับได้ ===== -->
<div class="header">
    <div>
        <h2>🃏 <?= htmlspecialchars($player_name) ?></h2>
        <span class="badge badge-<?= $difficulty ?>">
            <?= strtoupper($difficulty) ?>
        </span>
    </div>
    <div class="stats">
        <div class="stat-box">
            <div class="stat-label">⏱ เวลา</div>
            <div class="stat-value" id="timer"><?= $time_limit ?></div>
        </div>
        <div class="stat-box">
            <div class="stat-label">🖱 Moves</div>
            <div class="stat-value" id="moves">0</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">✅ คู่</div>
            <div class="stat-value" id="pairs">0/<?= $total_pairs ?></div>
        </div>
    </div>
</div>

<!-- ===== กระดานไพ่ ===== -->
<div class="board" id="board">
    <?php foreach ($cards as $i => $card): ?>
    <div class="card"
         data-index="<?= $i ?>"
         data-pair-id="<?= $card['pair_id'] ?>"
         data-card-type="<?= $card['card_type'] ?>"
         onclick="flipCard(this)">
        <div class="card-inner">
            <!-- หน้าปิด -->
            <div class="card-front">🍀</div>
            <!-- หน้าเปิด -->
            <div class="card-back">
                <?= $card['display'] ?>
                <?php if (!empty($card['sub'])): ?>
                <div class="card-name"><?= $card['sub'] ?></div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<!-- ===== หน้าจอจบเกม ===== -->
<div id="result-overlay">
    <div class="result-box">
        <h2 id="result-title">🎉 ยินดีด้วย!</h2>
        <p>ผู้เล่น: <span class="big"><?= htmlspecialchars($player_name) ?></span></p>
        <p>ระดับ: <span class="big"><?= strtoupper($difficulty) ?></span></p>
        <p>เวลาที่ใช้: <span class="big" id="result-time">-</span> วินาที</p>
        <p>จำนวน Moves: <span class="big" id="result-moves">-</span></p>
        <a href="result.php" class="btn-result">ดู Leaderboard 🏆</a>
    </div>
</div>

<!-- ===== JavaScript ===== -->
<script>
// ===== ตัวแปร State =====
let flippedCards  = [];   // ไพ่ที่พลิกอยู่ (สูงสุด 2 ใบ)
let matchedPairs  = 0;    // คู่ที่จับได้แล้ว
let moves         = 0;    // จำนวนครั้งที่พลิก
let canFlip       = true; // ล็อคไม่ให้กดระหว่างเช็ค
let timerValue    = <?= $time_limit ?>;
let timerInterval = null;
const totalPairs  = <?= $total_pairs ?>;
const difficulty  = "<?= $difficulty ?>";
const playerName  = "<?= addslashes($player_name) ?>";

// ===== เริ่มนับเวลา =====
function startTimer() {
    timerInterval = setInterval(() => {
        timerValue--;
        document.getElementById('timer').textContent = timerValue;

        // เปลี่ยนสีเมื่อเวลาน้อย
        if (timerValue <= 20) {
            document.getElementById('timer').style.color = '#8E0505';
        }

        // หมดเวลา
        if (timerValue <= 0) {
            clearInterval(timerInterval);
            endGame(false); // แพ้
        }
    }, 1000);
}

// ===== พลิกไพ่ =====
function flipCard(card) {
    // ไม่ให้พลิกถ้า: ล็อคอยู่, จับคู่แล้ว, หรือเป็นไพ่ใบเดิม
    if (!canFlip) return;
    if (card.classList.contains('matched')) return;
    if (card.classList.contains('flipped')) return;

    // เริ่มจับเวลาตอนพลิกใบแรก
    if (moves === 0 && flippedCards.length === 0) startTimer();

    card.classList.add('flipped');
    flippedCards.push(card);

    if (flippedCards.length === 2) {
        moves++;
        document.getElementById('moves').textContent = moves;
        canFlip = false; // ล็อคระหว่างเช็ค
        checkMatch();
    }
}

// ===== ตรวจสอบว่าจับคู่ได้ไหม =====
function checkMatch() {
    const [card1, card2] = flippedCards;
    const same = card1.dataset.pairId === card2.dataset.pairId;

    if (same) {
        // จับคู่ได้!
        card1.classList.add('matched');
        card2.classList.add('matched');
        matchedPairs++;
        document.getElementById('pairs').textContent = matchedPairs + '/' + totalPairs;

        flippedCards = [];
        canFlip      = true;

        // จบเกม
        if (matchedPairs === totalPairs) {
            clearInterval(timerInterval);
            setTimeout(() => endGame(true), 500);
        }
    } else {
        // จับคู่ไม่ได้ → พลิกกลับ
        setTimeout(() => {
            card1.classList.remove('flipped');
            card2.classList.remove('flipped');
            flippedCards = [];
            canFlip      = true;
        }, 900);
    }
}

// ===== จบเกม =====
function endGame(isWin) {
    const timeUsed = <?= $time_limit ?> - timerValue;

    document.getElementById('result-title').textContent = isWin ? '🎉 ยินดีด้วย!' : '⏰ หมดเวลา!';
    document.getElementById('result-time').textContent  = isWin ? timeUsed : '<?= $time_limit ?>';
    document.getElementById('result-moves').textContent = moves;
    document.getElementById('result-overlay').classList.add('show');

    // ส่งคะแนนไปบันทึก
    fetch('save_score.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            player_name: playerName,
            difficulty:  difficulty,
            time_taken:  isWin ? timeUsed : <?= $time_limit ?>,
            moves:       moves,
            is_win:      isWin
        })
    });
}
</script>

</body>
</html>