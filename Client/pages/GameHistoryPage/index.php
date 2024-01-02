
<!--connection modules -->
<?php
    //connection modules
?>


<!-- function -->
<?php 

	function fen_to_array($old_position)
	{
		$chessboard_array = [];

		$pos = 0;

		for ($i = 0; $i < mb_strlen($old_position);$i++)
		{
			if($old_position[$i] == '/')
			{
				continue;
			}

			if (is_numeric($old_position[$i]))
			{
				for ($j = 0; $j < intval($old_position[$i]); $j++)
				{
					$chessboard_array[$pos++] = 1;
				}
			}
			else
			{
				$chessboard_array[$pos++] = $old_position[$i];
			}
		}

		return $chessboard_array;
	}
	function array_to_fen($array)
	{
		$fen = "";

		$int_val = 0;

		for ($i = 0; $i< 64; $i++)
		{
			if($i != 0 && $i % 8 == 0)
			{
				if ($int_val != 0)
				{
					$fen .= $int_val;
					$int_val =0;
				}
				$fen .= '/';
			}

			if (is_numeric($array[$i]))
			{
				$int_val++;
			}
			else
			{
				if ($int_val != 0)
				{
					$fen .= $int_val;
					$int_val = 0;
				}
				$fen .= $array[$i];
			}

		}

		return $fen;
	}
	function move_to_fen($move_from, $move_to, $old_position)
	{
		$chess_board_array = fen_to_array($old_position);	

		$temp_figures = $chess_board_array[intval($move_from)];

		$chess_board_array[intval($move_from)] = $chess_board_array[intval($move_to)];

		$chess_board_array[intval($move_to)] = $temp_figures;

		$fen = array_to_fen($chess_board_array);

		return $fen;

		
	}

?>

<!--server logics page -->
<?php
	//logics page (из-за еблана будет логика внутри страницы)
	
	session_start();

	$array_fen_parse = [];
	$array_fen_parse[0] = "rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR";


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
				
				$array_fen_parse[$i + 1] = move_to_fen($tokens[0],$tokens[1],$array_fen_parse[$i]);
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
	<link rel="stylesheet" href="index.css">
	<link rel="stylesheet" href="gameHistory.css">
	<meta http-equiv="pragma" content="no-cache" />
	<title>Alter-chess.ru</title>
</head>

<body>
	<main class="wrapper">
		<div class="screen_intro">
			<div class="container">
				<div class="wrapper_creen">
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
												<div class  = "moves white" id = "<?php echo 'move_id_' . ($i + 1); ?>" onclick="current_pos_game(<?php echo ($i + 1);?>)" ><p class = "number_move"><?php echo ($i++ + 1) . '. '; ?></p><?php echo ($move);?></div>
											<?php else:?>
												<div class  = "moves black" id = "<?php echo 'move_id_' . ($i + 1); ?>" onclick="current_pos_game(<?php echo ($i + 1);?>)" ><p class = "number_move"><?php echo ($i++ + 1) . '.'; ?></p><?php echo ($move);?></div>
											<?php endif;?>
										<?php endforeach; ?>
									<?php endif;?>
								</div>
								<div class = "control_elements">
									<div class="img_left"><img src="images/fast-forward.png" style="max-width: 30px;" onclick="prev_pos_game()"></div>
									<div class="img_right"><img src="images/fast-forward.png" style="max-width: 30px;" onclick="next_pos_game()"></div>
								</div>
							</div>
		
						</div>
					</div>
				</div>
		</div>
			<div class="form">
				<form action = "index.php" enctype="multipart/form-data" method="post">
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


<!--connect modules-->
<script src="../GameRoomPage/scripts/js/game.js"></script>

<!--client logics -->
<script>

	
	window.onload = function() 
	{
		if (window.screen.width >= 1100)
	{
		var element1 = document.getElementsByClassName('chessboard')[0];
		var element2 = document.getElementsByClassName('tabble_history_move')[0];
		var element1Height = element1.clientHeight;
		var element1Width = element1.clientWidth;
		element2.style.height = element1Height + 'px';
	}
	else
	{
		var element3 = document.getElementsByClassName("title_history")[0].clientHeight;
		var element1 = document.getElementsByClassName('chessboard')[0];
		var element2 = document.getElementsByClassName('tabble_history_move')[0];

		var screen_width = window.screen.height;

		var element1Height = element1.clientHeight;
		var element1Width = element1.clientWidth;

		element2.style.height = (screen_width - element3 - element1Height - 30) + 'px';
	}

	}

	window.addEventListener("resize",function(event)
	{
		if (window.screen.width >= 1100)
	{
		var element1 = document.getElementsByClassName('chessboard')[0];
		var element2 = document.getElementsByClassName('tabble_history_move')[0];
		var element1Height = element1.clientHeight;
		var element1Width = element1.clientWidth;
		element2.style.height = element1Height + 'px';
	}
	else
	{
		var element3 = document.getElementsByClassName("title_history")[0].clientHeight;
		var element1 = document.getElementsByClassName('chessboard')[0];
		var element2 = document.getElementsByClassName('tabble_history_move')[0];

		var screen_width = window.screen.height;

		var element1Height = element1.clientHeight;
		var element1Width = element1.clientWidth;

		element2.style.height = (screen_width - element3 - element1Height - 30) + 'px';
	}
	},
	true
	);
	

	var pos_game = 0;

	<?php if (!empty($array_fen_parse)):?>
	var array_moves = <?php echo json_encode($array_fen_parse)?>;
	<?php else:?>
	var array_moves = null;
	<?php endif;?>

	createChessboard(array_moves[0]);


</script>

<!--function clients -->
<script>

	function current_pos_game(id)
	{
		if (pos_game != 0 || pos_game > array_moves.length - 1)
		{
			document.getElementById("move_id_"+(pos_game)).style.color = "#ffffff";
			document.getElementById("move_id_"+ (pos_game)).style.backgroundColor = "transparent";
		}
		
	
		document.getElementById("move_id_"+ (id)).style.backgroundColor = "#4CAF50";
		document.getElementById("move_id_" + (id)).style.color = "#000000";
		
		pos_game = id;

		if(pos_game > 0 && pos_game <= array_moves.length - 1)
		{
			createChessboard(array_moves[pos_game]);
		}
	}

	function prev_pos_game()
	{
		if (pos_game == 0)
		{
			return;
		}
		if (pos_game != 0 || pos_game > array_moves.length - 1)
		{
			document.getElementById("move_id_"+ (pos_game)).style.backgroundColor = "transparent";
			document.getElementById("move_id_"+(pos_game)).style.color = "#ffffff";
		}

		pos_game--;

		if (pos_game != 0)
		{
			document.getElementById("move_id_"+ (pos_game)).style.backgroundColor = "#4CAF50";
			document.getElementById("move_id_" + (pos_game)).style.color = "#000000";
		}
		


		createChessboard(array_moves[pos_game]);
	}

	function next_pos_game()
	{
		if (pos_game < (array_moves.length - 1)) 
		{
			if (pos_game != 0 || pos_game > array_moves.length - 1)
			{
				document.getElementById("move_id_"+ (pos_game)).style.backgroundColor = "transparent";	
				document.getElementById("move_id_"+(pos_game)).style.color = "#ffffff";
			}

			pos_game += 1;

			if (pos_game < array_moves.length)
			{
				document.getElementById("move_id_" + (pos_game)).style.color = "#000000";
				document.getElementById("move_id_"+ (pos_game)).style.backgroundColor = "#4CAF50";
			}
		
			createChessboard(array_moves[pos_game]);
		}
	}
</script>

</html>

