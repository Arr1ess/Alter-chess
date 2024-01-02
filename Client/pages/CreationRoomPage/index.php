<?php
$gameName = $_GET['gameName'] ?? '';
function generateGuid()
{
	return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X',
		mt_rand(0, 65535),
		mt_rand(0, 65535),
		mt_rand(0, 65535),
		mt_rand(16384, 20479),
		mt_rand(32768, 49151),
		mt_rand(0, 65535),
		mt_rand(0, 65535),
		mt_rand(0, 65535)
	);
}

$gameId = generateGuid();
$minutes = 0;
$seconds = 0;
?>

<!DOCTYPE html>
<html>

<head>
	<title>Создание комнаты</title>
	<meta charset="UTF-8">
	<meta http-equiv="Cache-Control" content="no-cache">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="style.css">

</head>

<body>
	<main class="wrapper">
		<h2>Cоздать игру 
			<?= $gameName ?>
		</h2>
		<form class="card">
			<div class="labels">
				<label for="initialTime">Время на партию <br /> (мин):</label>
				<label for="addedTime">Добавление <br />(сек):</label>
			</div>
			<div class="inputs">
				<input type="number" id="initialTime" name="initialTime" step="1" min="0" value=<?= $minutes ?>>
				<input type="number" id="addedTime" name="addedTime" step="1" min="0" value=<?= $seconds ?>>
			</div>
			<div class="button-wrapper">
				<input type="submit" class="btn outline" value="Добавить партию">
			</div>
		</form>
		<div class="description">
			<?php
			$file = 'info.txt';

			// Проверяем существование файла
			if (file_exists($file)) {
				// Выводим содержимое файла
				echo file_get_contents($file);
			} else {
				echo 'Файл не найден';
			}
			?>
		</div>


		<script>
			gameId = '<?= $gameId ?>';
			gameName = '<?= $gameName ?>';
		</script>
		<script src="scripts/wsConection.js"></script>

	</main>
</body>

</html>