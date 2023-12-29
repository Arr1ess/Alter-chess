<?php


$allowedCells = [];

$chessPieces = [
	'k' => 'king',
	'q' => 'queen',
	'r' => 'rook',
	'n' => 'knight',
	'b' => 'bishop',
	'p' => 'pawn'
];

function recordToBoardArray($boardRecord)
{
	$x = 0;
	foreach (str_split($boardRecord) as $char) {

		if (is_numeric($char)) {

			for ($i = 0; $i < intval($char); $i++) {
				$boardArray[$x] = "";
				$x++;
			}
		} elseif ($char != "/") {
			$boardArray[$x] = $char;
			$x++;
		}
	}
	return $boardArray;
}
function boardArrayToRecord($boardArray)
{
	$boardRecord = "";
	$count = 0;
	for ($i = 0; $i < 64; $i++) {
		$ceil = $boardArray[$i];
		if ($i % 8 == 0 && $i != 0) {
			if ($count != 0) {
				$boardRecord .= $count;
				$count = 0;
			}
			$boardRecord .= "/";
		}
		if ($ceil == "") {
			$count++;
		} else {
			if ($count != 0) {
				$boardRecord .= $count;
				$count = 0;
			}
			$boardRecord .= $ceil;
		}
	}
	if ($count != 0) {
		$boardRecord .= $count;
		$count = 0;
	}
	return $boardRecord;
}
function movePiece($boardRecord, $start, $finish)
{
	$board = recordToBoardArray($boardRecord);

	$temp = $board[$start];
	$board[$start] = $board[$finish];
	$board[$finish] = $temp;

	$newBoardRecord = boardArrayToRecord($board);

	return $newBoardRecord;
}
function processField($board, $cellId, $start)
{
	global $allowedCells;
	global $isWhite;
	$content = $board[intval($cellId)];
	if ($content == "") {
		array_push($allowedCells, intval($cellId));
		return true;
	}
	$startRow = intdiv($start, 8);
	$currentRow = intdiv($cellId, 8);
	$stx = $start%8;
	$cuX = $cellId%8;

	if (($isWhite && $startRow != 0) || (!$isWhite && $startRow != 7)) {
		if (ctype_upper($content) != $isWhite) {
			if (($content == "B" || $content == "b") && (($startRow + $stx) % 2 != ($currentRow + $cuX) % 2)) {
				return false;
			} else {
				array_push($allowedCells, intval($cellId));
				return false;
			}
		}
	}

	return false;
}
function getCoordinatesAndDirection($cellId)
{
	global $isWhite;
	$X = $cellId % 8;
	$Y = intdiv($cellId, 8);
	$direction = ($isWhite) ? ($Y == 0 ? 1 : -1) : ($Y == 7 ? -1 : 1);
	return [$X, $Y, $direction];
}
function processLine($board, $X, $Y, $dX, $dY, $directionX, $directionY)
{
	for ($i = 1; $X + $i * $dX >= 0 && $X + $i * $dX < 8 && $Y + $i * $dY >= 0 && $Y + $i * $dY < 8; $i++) {
		$result = processField($board, 8 * ($Y + $i * $dY) + ($X + $i * $dX), $Y * 8 + $X);
		if (!($result)) {
			break;
		}
	}
}
function king($cellId, $board)
{
	list($X, $Y, $direction) = getCoordinatesAndDirection($cellId);

	for ($i = max($X - 1, 0); $i <= min($X + 1, 7); $i++) {
		processField($board, $Y * 8 + $i, $Y * 8 + $X);
		if ($Y + $direction >= 0 && $Y + $direction < 8) {
			processField($board, ($Y + $direction) * 8 + $i, $Y * 8 + $X);
		}
	}
}
function queen($cellId, $board)
{
	list($X, $Y, $direction) = getCoordinatesAndDirection($cellId);
	processLine($board, $X, $Y, 1, 0, 0, $direction);
	processLine($board, $X, $Y, -1, 0, 0, $direction);
	processLine($board, $X, $Y, 0, $direction, 0, $direction);
	processLine($board, $X, $Y, 1, $direction, 0, $direction);
	processLine($board, $X, $Y, -1, $direction, 0, $direction);
}
function rook($cellId, $board)
{
	list($X, $Y, $direction) = getCoordinatesAndDirection($cellId);
	processLine($board, $X, $Y, 1, 0, 0, $direction);
	processLine($board, $X, $Y, -1, 0, 0, $direction);
	processLine($board, $X, $Y, 0, $direction, 0, $direction);
}
function bishop($cellId, $board)
{
	list($X, $Y, $direction) = getCoordinatesAndDirection($cellId);
	processLine($board, $X, $Y, 1, $direction, 1, $direction);
	processLine($board, $X, $Y, -1, $direction, -1, $direction);
}
function knight($cellId, $board)
{
	list($X, $Y, $direction) = getCoordinatesAndDirection($cellId);

	$moves = [
		[$X + 1, $Y + 2 * $direction],
		[$X + 2, $Y + 1 * $direction],
		[$X - 2, $Y + 1 * $direction],
		[$X - 1, $Y + 2 * $direction]
	];

	foreach ($moves as $move) {
		checkAndProcessField($move[0], $move[1], $board, $Y * 8 + $X);
	}
}
function checkAndProcessField($X, $Y, $board, $start)
{
	if ($X >= 0 && $X < 8 && $Y >= 0 && $Y < 8) {
		ProcessField($board, $Y * 8 + $X, $start);
	}
}
function pawn($cellId, $board)
{
	list($X, $Y, $direction) = getCoordinatesAndDirection($cellId);

	$forwardY = $Y + $direction;
	$leftX = $X - 1;
	$rightX = $X + 1;

	if ($leftX >= 0 && $leftX < 8 && $board[$forwardY * 8 + $leftX] != "") {
		processField($board, $forwardY * 8 + $leftX, $Y * 8 + $X);
	}
	if ($rightX >= 0 && $rightX < 8 && $board[$forwardY * 8 + $rightX] != "") {
		processField($board, $forwardY * 8 + $rightX, $Y * 8 + $X);
	}

	// Check forward move
	if ($board[8 * $forwardY + $X] == "") {
		processField($board, 8 * $forwardY + $X, $Y * 8 + $X);
		if (($direction == 1 && $Y == 1) || ($direction == -1 && $Y == 6)) {
			$doubleMoveY = $Y + 2 * $direction;
			if ($board[8 * $doubleMoveY + $X] == "") {
				processField($board, 8 * $doubleMoveY + $X, $Y * 8 + $X);
			}
		}
	}
}

$data = json_decode(file_get_contents('php://input'), true);


$cellId = $data['cellId'];
$field = $data['board'];
$active = $data['active'];
$ready = $data['ready'];
$isWhite;
$turnMove = $data['turnMove'];

if (in_array($cellId, $active) && $ready != -1) {
	$processedData = array(
		'board' => movePiece($field, $ready, $cellId),
		'active' => [],
		'ready' => -1,
		'turnMove' => !$turnMove
	);
} elseif ($cellId == $ready) {
	$processedData = array(
		'board' => $field,
		'active' => [],
		'ready' => -1,
		'turnMove' => $turnMove
	);
} else {
	$board = recordToBoardArray($field);
	$ceil = $board[intval($cellId)];
	if ($ceil == "") {
		$processedData = array(
			'board' => $field,
			'active' => [],
			'ready' => -1,
			'turnMove' => $turnMove
		);
	} else {
		$isWhite = ctype_upper($ceil);
		if ($isWhite != $turnMove) {
			$processedData = array(
				'board' => $field,
				'active' => [],
				'ready' => -1,
				'turnMove' => $turnMove
			);
		} else {
			$chessPieces[strtolower($ceil)]($cellId, $board);
			$processedData = array(
				'board' => $field,
				'active' => $allowedCells,
				'ready' => $cellId,
				'turnMove' => $turnMove
			);
		}
	}
}

echo json_encode($processedData);
?>