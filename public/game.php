<!DOCTYPE html>
<html>

<head>
    <title>Партия</title>
    <!-- <link rel="stylesheet" href="styles/test.css">   -->
</head>
<style>
    .chessboard {
        flex-wrap: wrap;
        position: relative;
        box-sizing: content-box;
        margin: auto;
        display: flex;
    }

    .light-cell {
        background-color: #f0d9b5;
    }

    .dark-cell {
        background-color: #b58863;
    }

    .field {
        box-sizing: border-box;
        position: relative;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 5px;
    }

    .field img {
        max-width: 100%;
        max-height: 100%;
        margin: auto;
        /* Чтобы изображение оставалось по центру ячейки */
    }

    .active {
        border: blue solid 4px;
    }

    .ready {
        border: rgb(255, 0, 0) solid 4px;
    }
</style>
<?php
session_start();
$gameId = $_GET['gameId'];

?>

<body>
    <h1 class="game-tag" id="<?php echo $gameId ?>">Игра
        <?php echo $gameId; ?>
    </h1>

    <div class="chessboard"></div>
    <form>
        <label for="dateInput">FEN</label>
        <input type="text" id="dateInput">
    </form>
    <button onclick="createNewRoom()">Создать новую комнату</button>
    <button onclick="addInRoom()">Добавиться в комнтау</button>
    <button id="toggleButton">Переключить отсчеты</button>
    <div id="countdown1">
        Отсчет 1: <span id="countdownValue1">00:00:00</span>
    </div>
    <div id="countdown2"">
            Отсчет 2: <span id="countdownValue2">00:00:00</span>
    </div>
    <div class="rooms"></div>
</body>

</html>

<script>
    function getIdFromGameTag() {
        return "<?php echo $gameId ?>";
    }   
    function getUserId(){
        return "<?php echo session_id()?>";
    }

    function setRole(role) {
    $.ajax({
        url: 'php/ajax/setRole.php', 
        method: 'POST',
        data: { role: role },
        success: function(response) {
            console.log('Роль успешно установлена');
        },
        error: function(error) {
            console.error('Произошла ошибка при установке роли');
        }
    });
}

</script>



<script src="js/game.js"></script>
<script src="js/webSocket.js"></script>