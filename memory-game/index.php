<?php session_start(); ?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Card Matching Game - เลือกระดับความยาก</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #FFFFE8;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            text-align: center;
        }
        .container {
            background-color: #CDE990;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
            width: 350px;
        }
        h1 {
            color: #694E4E;
            margin-bottom: 20px;
        }
        .input-group {
            margin-bottom: 25px;
            text-align: left;
        }
        .input-group label {
            display: block;
            margin-bottom: 8px;
            color: #694E4E;
            font-size: 14px;
        }
        .input-group input {
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #AACB73;
            background-color: #AACB73;
            color: #694E4E;
            font-size: 16px;
            box-sizing: border-box;
        }
        .input-group input:focus {
            outline: none;
            border-color: #000000;
        }
        .btn {
            display: block;
            width: 100%;
            padding: 15px;
            margin: 15px 0;
            font-size: 18px;
            font-weight: bold;
            color: #694E4E;
            background-color: #AACB73;
            border: 2px solid #FFAAB8;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .btn:hover {
            background-color: #FFD4D4;
            transform: scale(1.05);
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>เกมจับคู่ไพ่</h1>
        
        <form action="game.php" method="POST">
            <div class="input-group">
                <label for="player_name">ชื่อผู้เล่น (Player Name):</label>
                <input type="text" id="player_name" name="player_name" placeholder="ใส่ชื่อของคุณที่นี่..." required>
            </div>

            <p style="color: #694E4E; margin-bottom: 15px;">เลือกระดับความยาก</p>
            <button type="submit" name="difficulty" value="easy" class="btn">Easy (4 คู่)</button>
            <button type="submit" name="difficulty" value="medium" class="btn">Medium (6 คู่)</button>
            <button type="submit" name="difficulty" value="hard" class="btn">Hard (10 คู่)</button> 
        </form>
    </div>

</body>
</html>