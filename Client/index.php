<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="styles/card.css">
	<link rel="stylesheet" href="styles/index.css">
	<title>Alter-chess.ru</title>
</head>

<body>
	<main class="wrapper">
		<h1 class="title">Добро пожаловать в мир алтернативных шахмат</h1>
		<section class="games">
			<?php
			$gameFolders = array_filter(glob('games/*'), 'is_dir');
			foreach ($gameFolders as $gameFolder) {
				$gameName = basename($gameFolder);

				$infoFilePath = $gameFolder . '/info.txt';
				$gameInfo = file_exists($infoFilePath) ? file_get_contents($infoFilePath) : 'Описание отсутствует';

				$imagePath = $gameFolder . '/image.gif';

				echo '<div class="card">';
				echo '<h2>' . $gameName . '</h2>';
				echo '<img class="banner-image" src="' . $imagePath . '" alt="' . $gameName . '">';
				echo '<p>' . $gameInfo . '</p>';
				echo '<div class="button-wrapper"><a href="pages/createGame.php?gameName=' . $gameName . '" class="create-btn btn outline">Создать игру</a></div>';
				echo '</div>';
			}
			?>
		</section>
	</main>
</body>

</html>