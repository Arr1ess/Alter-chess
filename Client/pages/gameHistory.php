<?php
    //connection modules


?>


<?php
	//logics page (из-за еблана будет логика внутри страницы)
	
	session_start();

	$error = [];

	if ($_SERVER['REQUEST_METHOD'] == 'GET')
	{
		unset($error);

		if (isset($_SESSION['file_content']))
		{
			
			$array_moves_non_parse = explode("\n", $_SESSION['file_content']);

			$array_moves_parse = [];

			for($i = 0; $i  < count($array_moves_non_parse); $i++)
			{
				$tokens = explode('->', $array_moves_non_parse[$i]);


				if ($tokens[0]=="")
				{
					break;
				}

				$temp_parse = [];
				
				for ($j = 0; $j < count($tokens); $j++)
				{
					switch (intval($tokens[$j]) % 8)
					{
						case 0:
							$temp_parse[$j] = 'A' . strval(intdiv( 63 - intval($tokens[$j]), 8) + 1);
							break;
						case 1:
							$temp_parse[$j] = 'B' . strval(intdiv(63 - intval($tokens[$j]), 8)+ 1);
							break;
						case 2:
							$temp_parse[$j] = 'C' . strval(intdiv(63 - intval($tokens[$j]), 8) + 1);
							break;
						case 3:
							$temp_parse[$j] = 'D' . strval(intdiv(63 - intval($tokens[$j]), 8) + 1);
							break;
						case 4:
							$temp_parse[$j] = 'E' . strval(intdiv(63 - intval($tokens[$j]), 8) + 1);
							break;
						case 5:
							$temp_parse[$j] = 'F' . strval(intdiv(63 - intval($tokens[$j]), 8) + 1);
							break;
						case 6:
							$temp_parse[$j] = 'G' . strval(intdiv(63 - intval($tokens[$j]), 8) + 1);
							break;
						case 7:
							$temp_parse[$j] = 'H' . strval(intdiv(63 - intval($tokens[$j]), 8) + 1);
							break;
						
					}
				}
				
				$string_parse_result = $temp_parse[0] . " -> " .$temp_parse[1];
				$array_moves_parse[$i] = $string_parse_result;
			}
			


			
		}
	}
	else
	{
		unset($_SESSION['file_content']);

		if (isset($_FILES['file_game']))
		{
			$file_extension = pathinfo($_FILES['file_game']['name'],PATHINFO_EXTENSION);
			
			if (str_contains($file_extension,'kda'))
			{
				$_SESSION['file_content'] = file_get_contents($_FILES['file_game']['tmp_name']);
				
				if ($_SESSION['file_content'] == null)
				{
					$error['file'] = "файл сликом большной";
					unset($_SESSION["file_content"]);
				}
			}
			else
			{
				$error['file'] = 'неправильный формат';
			}
		}
		else
		{
			$_error['file'] = "файл не выбран";
		}

		if (empty($error))
		{
			header("Location: gameHistory.php");			
		}
	}
?>


<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="/alter-chess-main/Client/styles/index.css">
	<link rel="stylesheet" href="../styles/gameHistory.css">
	<title>Alter-chess.ru</title>
</head>

<body>
	<main class="wrapper">
		<div class="container">
			<h1 class="title_history">История матча</h1>
			<div class="content_main">
				<div class="chessfield">	
					<div class="chessboard"></div>
				</div>
				<div class="history_move">
					<div class="tabble_history_move">
						<h3 class = "subtitle_page">Ходы</h3>
						
						<div class="moves_figures">
							<?php $i = 0;?>
							<?php if (isset($array_moves_parse)):?>
								<?php foreach($array_moves_parse as $move):?>
									<?php if($i % 2 == 0 ):?>
										<div class  = "moves white"><p class = "number_move"><?php echo ($i++ + 1) . '. '; ?></p><?php echo ($move);?></div>
									<?php else:?>
										<div class  = "moves black"><p class = "number_move"><?php echo ($i++ + 1) . '.'; ?></p><?php echo ($move);?></div>
									<?php endif;?>
								<?php endforeach; ?>
							<?php endif;?>
						</div>
						<div class = "control_elements">
							<div class="img_left"><img src="../images/fast-forward.png" style="max-width: 30px;"></div>
							<div class="img_right"><img src="../images/fast-forward.png" style="max-width: 30px;"></div>
						</div>
					</div>

				</div>
			</div>
			<div class="form">
				<form action = "gameHistory.php" enctype="multipart/form-data" method="post">
					<div class="section_from">
						<label for="file_input" class="subtitle_page st_p_new" >Загрузить игру</label>
						<div class="section_form_file">
							<input type="file" name = "file_game" id= "file_input">
							<?php if (!empty($error)) :?>
								<p class="text_error"><?php echo htmlspecialchars($error['file']); ?></p>
							<?php endif; ?>
						</div>
					</div>
					<button type="submit" class="btn">Загрузить игру</button>
				</form>
			</div>
		</div>
	</main>
</body>
<script src="../scripts/js/game.js"></script>
</html>

