<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="style.css">
	<title>Альтернативные шахматы</title>
</head>

<?php $gameFolders = array_filter(glob('games/*'), 'is_dir'); ?>


<body>
	<main class="wrapper">
		<h1 class="title">Добро пожаловать в мир алтернативных шахмат</h1>
		<div class="content">
			<section class="games">
				<?php foreach ($gameFolders as $gameFolder): ?>
					<?php
					$gameName = basename($gameFolder);

					$infoFilePath = $gameFolder . '/info.txt';

					$gameInfo = file_exists($infoFilePath) ? file_get_contents($infoFilePath) : 'Описание отсутствует';

					$imagePath = $gameFolder . '/image.gif';

					?>
					<div class="section_card">
						<div class="card">
							<h2>
								<?php echo htmlspecialchars($gameName); ?>
							</h2>
							<img class="banner-image"  src="<?php echo ($imagePath); ?>" alt="<?php echo ($gameName); ?>">
							<p>
								<?php echo htmlspecialchars($gameInfo); ?>
							</p>
							<div class="button-wrapper"><a href="<?php echo '../CreationRoomPage/?gameName=' . $gameName; ?>"
									class="create-btn btn outline">Создать игру</a></div>
						</div>
					</div>
				<?php endforeach; ?>
			</section>
		</div>
	</main>
</body>

</html>