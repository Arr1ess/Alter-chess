<?php
$gameName = $_POST['gameName'] ?? '';
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
	<title>Создание партии</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="../styles/createGame.css">
	<link rel="stylesheet" href="../styles/index.css">
</head>

<body>
	<div class="wrapper">
		<h2>Cоздать
			<? $gameName ?>
		</h2>
		<form class="card">
			<div class="labels">
				<label for="initialTime">Время на партию <br /> (мин):</label>
				<label for="addedTime">Добавление <br />(сек):</label>
			</div>
			<div class="inputs">
				<input type="number" id="initialTime" name="initialTime" step="1" min="0" value=<?= $minutes?>>
				<input type="number" id="addedTime" name="addedTime" step="1" min="0" value=<?= $seconds?>>
			</div>
			<div class="button-wrapper">
				<input type="submit" class="btn outline" value="Добавить партию">
			</div>
		</form>

		<script>
			const socket = new WebSocket("ws://localhost:8080");
			document.querySelector('.btn').addEventListener('click', function (e) {
				e.preventDefault();
				const message = {
					type: "newRoom",
					roomId: "<?= $gameId ?>",
					gameType: "<?= $gameName ?>"
				};
				socket.send(JSON.stringify(message));

				const setTime = {
					type: "setTime",
					roomId: "<?= $gameId ?>",
					whiteTime: "<?= $minutes * 60 + $seconds ?>",
					blackTime: "<?= $minutes * 60 + $seconds ?>"
				};
				socket.send(JSON.stringify(setTime));

				window.location.href = 'gameRoom.php?gameId=<?= $gameId ?>';
			});
		</script>
	</div>
</body>

</html>