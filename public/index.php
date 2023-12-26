<!DOCTYPE html>
<html>

<head>
    <title>Добавление партии</title>
    <link rel="stylesheet" href="styles/index.css">
</head>

<body>
    <?php include_once "php/errorOutput.php" ?>
    <div class="form-container">
        <h2>Создать партию</h2>
        <form action="php/addGame.php" method="post">
            <label for="player_name">Ваш Никнейм</label>
            <input type="text" id="player_name" name="player_name" value="<?php echo $playerName; ?>"><br>
            <label for="initialTime">Время на партию (мин):</label>
            <input type="text" id="initialTime" name="initialTime">
            <label for="addedTime">Добавление (сек):</label>
            <input type="text" id="addedTime" name="addedTime">
            <input type="submit" value="Добавить партию">
        </form>
    </div>

</body>

</html>

<?php
session_start();

// Проверяем, есть ли уже активная сессия для игрока
if (isset($_SESSION['player_name'])) {
    $playerName = $_SESSION['player_name'];
} else {
    $playerName = "";
}
?>