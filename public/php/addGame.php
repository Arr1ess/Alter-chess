<?php
session_start();
require_once '../../src/DB/GameHandler.php';
function generateGuid() {
	if (function_exists('com_create_guid') === true) {
			return trim(com_create_guid(), '{}');
	}

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

// Пример использования

if ($_SERVER["REQUEST_METHOD"] == "POST") {

	$player_name = !empty($_POST['player_name']) ? $_POST['player_name'] : "Player 1";
	$_SESSION['player_name'] = $player_name;
	if (!empty($_POST['initialTime'])) {
		$initialTime = $_POST['initialTime'];
		$addedTime = !empty($_POST['addedTime']) ? $_POST['addedTime'] : 0;
		$startTime = $initialTime . "+" . $addedTime;
	} else {
		$startTime = null;
	}


	try {
		//$gameHandler = new GameHandler();
		//$gameId = $gameHandler->createGame($player_name, $startTime);
		$guid = generateGuid();
		header('Location: ../game.php?gameId=' . $guid);
		exit;
	} catch (PDOException $e) {
		header('Location: ../index.php?error=' . urlencode("Ошибка при добавлении партии: " . $e->getMessage()));
		exit;
	}
}
?>