<!DOCTYPE html>
<html>

<head>
    <title>Партия</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/gameRoom.css">
    <link rel="stylesheet" href="../styles/index.css">

</head>

<script src="../scripts/js/WS.js"></script>
<!-- <script src="../scripts/js/webSocket.js"></script> -->
<?php
$gameId = $_GET['gameId'];
?>
<script>

    function getIdFromGameTag() {
        return "<?php echo $gameId ?>";
    }

    function getUserId() {
        return "<?php echo session_id() ?>";
    }

    if (!isGameFind()) {
        console.lod("Don't connect")
        <? //header('Location: createGame.php'); ?>
    }
</script>



<?php
session_start();
if (empty($_SESSION[$gameId])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $_SESSION['userName'] = $_POST['userName'];
        $_SESSION[$gameId] = $_POST['userRole'];
        // echo '<script>';
        // echo 'addInRoom(' . json_encode($_POST["userRole"]) . ');';
        // echo '</script>';
        header('Location: ' . $_SERVER['PHP_SELF'] . '?gameId=' . $gameId);
        exit;
    }
}
?>

<body>
    <style>
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 9999;
        }

        .form-container {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        }
    </style>
    <!-- <main class="wrapper"> -->
    <?php if (empty($_SESSION[$gameId])): ?>
        <div class="overlay" id="overlay">
            <div class="form-container">
                <h2>Введите ваше имя и выберите роль</h2>
                <form method="post">
                    <input type="text" name="userName" placeholder="Гость" value=<?= empty($_SESSION['userName']) ? "" : $_SESSION['userName'] ?>>
                    <select name="userRole">
                        <option value="whitePlayer">Белые</option>
                        <option value="blackPlayer">Черные</option>
                        <option value="viewer" selected>Зритель</option>
                    </select>
                    <button type="submit">Сохранить имя и роль</button>
                </form>
            </div>
        </div>
    <?php endif; ?>
    <main>
        <style>
            main {
                height: 100vh;
                width: 100%;
                display: flex;
                flex-direction: column;
                justify-content: space-around;
                align-items: center;
            }



            .chessboard {
                margin: 10px;
                position: relative;
                max-height: 80vh;
                aspect-ratio: 1/1;
                border-radius: 12px;
                border: 6px solid black;
                /* filter: drop-shadow(10px 100px 100px rgba(0, 0, 0, 0.125)); */
            }

            .clock {
                width: 100%;
                margin-bottom: 30px;
                display: flex;
                align-items: center;
                justify-content: space-around;
                font-size: 30px;
                color: white;
                font-family: 'Xenotron', sans-serif;
            }
        </style>
        <div class="chessboard"></div>
        <div class="clock">
            <span id="countdownValue1">00:57:49</span>
            <span id=" countdownValue2">00:48:23</span>
        </div>
    </main>
</body>
<script>


    const rulesPath = "../games/Endless_chess/logic.php";

</script>



<script src="../scripts/js/game.js"></script>

</html>