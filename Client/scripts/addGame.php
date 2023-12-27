<?php

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



if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if (!empty($_POST['initialTime'])) {
		$initialTime = $_POST['initialTime'];
		$addedTime = !empty($_POST['addedTime']) ? $_POST['addedTime'] : 0;
		$startTime = $initialTime . "+" . $addedTime;
	} else {
		$startTime = null;
	}
	$gameName = $_POST['gameName'] ?? '';
	$guid = generateGuid();
	
	header('Location: ../game.php?gameId=' . $guid);
	exit;
}
?>