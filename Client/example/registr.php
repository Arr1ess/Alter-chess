<?php
session_start();

if (empty($_SESSION['userRole'])) {
    $_SESSION['userRole'] = 'зритель';
}

if (empty($_SESSION['userName'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $_SESSION['userName'] = $_POST['userName'];
        $_SESSION['userRole'] = $_POST['userRole'];
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Добро пожаловать</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    
    <?php if (empty($_SESSION['userName'])): ?>
        <div class="overlay" id="overlay">
            <div class="form-container">
                <h2>Введите ваше имя и выберите роль</h2>
                <form method="post">
                    <input type="text" name="userName" placeholder="Ваше имя">
                    <select name="userRole">
                        <option value="белые">Белые</option>
                        <option value="черные">Черные</option>
                        <option value="зритель" selected>Зритель</option>
                    </select>
                    <button type="submit">Сохранить имя и роль</button>
                </form>
            </div>
        </div>
    <?php endif; ?>

    <h1>Добро пожаловать,
        <?php echo $_SESSION['userName'] ?? "гость"; ?>!
    </h1>
    <p>Ваша роль:
        <?php echo $_SESSION['userRole']; ?>
    </p>
    <!-- Остальное содержимое страницы -->
</body>

</html>