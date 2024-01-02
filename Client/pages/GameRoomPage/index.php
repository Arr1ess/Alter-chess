<!DOCTYPE html>
<html>

<head>
    <title>Партия</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <script src="scripts/js/WS.js"></script>

</head>


<?php
    $gameId = $_GET['gameId'];
?>




<?php
session_start();
if (empty($_SESSION[$gameId])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $_SESSION['userName'] = $_POST['userName'];
        $_SESSION[$gameId] = $_POST['userRole'];
        header('Location: ' . $_SERVER['PHP_SELF'] . '?gameId=' . $gameId);
        exit;
    }
}
?>

<body>
    <div class="loadingPage active" id="loadingPage">
        <h1>Loading...</h1>
    </div>
    <main class="main">
        <?php if (empty($_SESSION[$gameId])): ?>
            <div class="overlay" id="overlay">
                <div class="form-container">
                    <div class="form">
                        <h2>Введите ваше имя и выберите роль</h2>
                        <form method="post">
                            <div class="input_container">
                                <input type="text" name="userName" placeholder="Гость" value=<?= empty($_SESSION['userName']) ? "" : $_SESSION['userName'] ?>>
                            </div>
                            <div class="select_container">
                                <select name="userRole" class="userRole">
                                    <option value="viewer" selected>Зритель</option>
                                </select>
                            </div>
                            <button type="submit">Сохранить имя и роль</button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div class="chessboard <?= $_SESSION[$gameId] == 'blackPlayer' ? 'blackPlayer' : ''; ?>"></div>
        <div class="chess-square <?= $_SESSION[$gameId] == 'blackPlayer' ? 'blackPlayer' : ''; ?>"
            id="transparentSquare"></div>
        <div class="clock">
            <span id="countdownValue1">00:57:49</span>
            <span id=" countdownValue2">00:48:23</span>
        </div>
    </main>
</body>
<script src="scripts/js/game.js"></script>

<script>
    // Добавляем буквы и цифры на краях блока
    // Добавляем буквы и цифры на краях блока
    
    /*
    const square = document.getElementById('transparentSquare');
    const letters = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h'];
    for (let i = 0; i < 8; i++) {
        const number = document.createElement('div');
        number.textContent = 8 - i;
        number.style.position = 'absolute';
        number.style.left = '0';
        number.style.top = `${ i * 12.5 }%`;
        number.style.pointerEvents = 'none';
        square.appendChild(number);

        const letter = document.createElement('div');
        letter.textContent = letters[i];
        letter.style.position = 'absolute';
        letter.style.bottom = '7%';
        letter.style.left = `${ i * 12.5 }%`;
        letter.style.pointerEvents = 'none';
        square.appendChild(letter);
    }
    */

    //жопа
    function sendCoordinates(y, x) {
            {
            var cellId = y * 8 + x;
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "scripts/logic.php", true);
            xhr.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
            xhr.onreadystatechange = function () {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        // console.log(xhr.responseText);
                        // console.log(cellId);
                        var activeCellsData = JSON.parse(xhr.responseText);

                        data = activeCellsData.board;
                        createChessboard(data);
                        var newTurnMove = turnMove;
                        // console.log(data);
                        var w = checkPromotionWhite(data);
                        var b = checkPromotionBlack(data);
                        if (w != -1) {
                            var active = getWhiteFigure(data, w);
                            turnMove = true;
                            var ready = w;
                        } else if (b != 55) {
                            var active = getBlackFigure(data, b);
                            var ready = b;
                            turnMove = false;
                        } else {
                            var active = activeCellsData.active;
                            turnMove = activeCellsData.turnMove;
                            var ready = activeCellsData.ready;
                        }

                        removeActiveClassFromCells();
                        if (active.length > 0) {
                            for (let i = 0; i < active.length; i++) {
                                var cell = document.getElementById("cell" + active[i]);
                                cell.classList.add("active");
                            }
                        }

                        if (ready != -1) {
                            var cell = document.getElementById("cell" + ready);
                            cell.classList.add("ready");
                        }
                        // console.log("turnMove");
                        if (newTurnMove != turnMove) {
                            takeMove(data);
                            // setTurnMove();
                        }
                    } else {
                        console.error("Ошибка запроса: " + xhr.status);
                    }
                }
            };

            xhr.send(
                JSON.stringify({
                    cellId: cellId,
                    board: data,
                    active: getCellsByActiveClass(),
                    ready: getCellIdByReadyClass(),
                    turnMove: turnMove,
                })
            );
        }
    }

    function uncorrectGameId() {
        window.location.href = '../CreationRoomPage';
    }

    function addOption(value, text, selected) {
        if (<?= isset($_SESSION[$gameId]) ? 'false' : 'true'; ?>) {
            var select = document.getElementsByName("userRole")[0];
            var option = document.createElement("option");
            option.value = value;
            option.text = text;
            if (selected) {
                option.selected = true;
            }
            select.appendChild(option);
        }
    }

    function removeLoadingPage() {
        const loadingPageElement = document.getElementById('loadingPage');
        loadingPageElement.classList.remove("active");
    }
    socket.onopen = function (event) {
        isGameFind();
        getData();
        <?php
        if (isset($_SESSION[$gameId])) {
            $sessionValue = $_SESSION[$gameId]; // Получаем значение из сессии
            echo "addUser('" . $sessionValue . "');";
        }
        ?>
        removeLoadingPage();
    };



    function getIdFromGameTag() {
        return "<?= $gameId ?>";
    }

    function getUserId() {
        return "<?= session_id() ?>";
    }
</script>

<script>
    const rulesPath = "../games/Endless_chess/logic.php";
</script>

</html>