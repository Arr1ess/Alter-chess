var turnMove = true;

var size = 50;
var data = "rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR";

// const figures = {
// 	P: "pawn_white.png",
// 	R: "rook_white.png",
// 	N: "knight_white.png",
// 	B: "bishop_white.png",
// 	Q: "queen_white.png",
// 	K: "king_white.png",
// 	p: "pawn_black.png",
// 	r: "rook_black.png",
// 	n: "knight_black.png",
// 	b: "bishop_black.png",
// 	q: "queen_black.png",
// 	k: "king_black.png",
// };

const figures = {
  P: "LichessChess/WP.svg",
  R: "LichessChess/WR.svg",
  N: "LichessChess/WN.svg",
  B: "LichessChess/WB.svg",
  Q: "LichessChess/WQ.svg",
  K: "LichessChess/WK.svg",
  p: "LichessChess/BP.svg",
  r: "LichessChess/BR.svg",
  n: "LichessChess/BN.svg",
  b: "LichessChess/BB.svg",
  q: "LichessChess/BQ.svg",
  k: "LichessChess/BK.svg",
};



//Удаление выделения у клеток
function removeActiveClassFromCells() {
	for (let cellId = 0; cellId < 64; cellId++) {
		const cellElement = document.getElementById("cell" + cellId);
		if (cellElement.classList.contains("active")) {
			cellElement.classList.remove("active");
		}
	}
}
//Получение списка выделеных клеток
function getCellsByActiveClass() {
	const activeCells = [];
	for (let cellId = 0; cellId < 64; cellId++) {
		const cellElement = document.getElementById("cell" + cellId);
		if (cellElement && cellElement.classList.contains("active")) {
			activeCells.push(cellId);
		}
	}
	return activeCells;
}
//Получение выбранной фигуры
function getCellIdByReadyClass() {
	const readyElement = document.querySelector(".ready");
	if (readyElement) {
		const id = readyElement.id.substring(4); // убираем "cell" из начала id
		return id; // преобразуем строку в число
	} else {
		return "-1"; // или можно вернуть что-то другое, если элемент не найден
	}
}
//Проверка на заглавную букву
function isUpperCase(char) {
	return char === char.toUpperCase() && char !== char.toLowerCase();
}
//Получение списка белых фигур
function getWhiteFigure(data, st) {
	x = st % 8;
	y = Math.floor(st / 8);
	array = [];
	index = 0;
	for (let char of data) {
		if (!isNaN(char)) {
			index += parseInt(char, 10);
		} else if (char != "/") {
			if (isUpperCase(char) && char != "P") {
				if (
					!(char == "B" && ((x + y) % 2 != (((index % 8) + Math.floor(index / 8)) % 2)))
				) {
					array.push(index);
				}
			}
			index++;
		}
	}
	return array;
}
//Получение списка черных фигур
function getBlackFigure(data, st) {
	x = st % 8;
	y = Math.floor(st / 8);
	array = [];
	index = 0;
	for (let char of data) {
		if (!isNaN(char)) {
			index += parseInt(char, 10);
		} else if (char != "/") {
			if (!isUpperCase(char) && char != "p") {
				if (
					!(char == "b" && (x + y) % 2 != (((index % 8) + Math.floor(index / 8)) % 2))
				) {
					array.push(index);
				}
			}
			index++;
		}
	}
	return array;
}
//Проверка на белую проходную
function checkPromotionWhite(str) {
	var rows = str.split("/");
	var lastRow = rows[0];
	return lastRow.indexOf("P");
}
//Проверка на черную проходную
function checkPromotionBlack(str) {
	var rows = str.split("/");
	var lastRow = rows[7];
	return lastRow.indexOf("p") + 56;
}
//жопа
function sendCoordinates(y, x) {
	var cellId = y * 8 + x;
	var xhr = new XMLHttpRequest();
	xhr.open("POST", rulesPath, true);
	xhr.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
	xhr.onreadystatechange = function () {
		if (xhr.readyState === XMLHttpRequest.DONE) {
			if (xhr.status === 200) {
				// console.log(xhr.responseText);
				// console.log(cellId);
				var activeCellsData = JSON.parse(xhr.responseText);

				data = activeCellsData.board;
				createChessboard(data);
				var newTurnMove = turnMove;
				// console.log(data);
				var w = checkPromotionWhite(data);
				var b = checkPromotionBlack(data);
				if (w != -1) {
					var active = getWhiteFigure(data, w);
					turnMove = true;
					var ready = w;
				} else if (b != 55) {
					var active = getBlackFigure(data, b);
					var ready = b;
					turnMove = false;
				} else {
					var active = activeCellsData.active;
					turnMove = activeCellsData.turnMove;
					var ready = activeCellsData.ready;
				}

				removeActiveClassFromCells();
				if (active.length > 0) {
					for (let i = 0; i < active.length; i++) {
						var cell = document.getElementById("cell" + active[i]);
						cell.classList.add("active");
					}
				}

				if (ready != -1) {
					var cell = document.getElementById("cell" + ready);
					cell.classList.add("ready");
				}

				if(newTurnMove != turnMove){
					//takeMove(data);
				}
			} else {
				console.error("Ошибка запроса: " + xhr.status);
			}
		}
	};
	
	xhr.send(
		JSON.stringify({
			cellId: cellId,
			board: data,
			active: getCellsByActiveClass(),
			ready: getCellIdByReadyClass(),
			turnMove: turnMove,
		})
	);
}
//Отрисовка доски
function createChessboard(data) {
	const chessboard = document.querySelector(".chessboard");
	while (chessboard.firstChild) {
		chessboard.removeChild(chessboard.firstChild);
	}
	let x = 0,
		y = 0;
	for (let char of data) {
		if (char === "/") {
			y++;
			x = 0;
		} else if (!isNaN(char)) {
			let num = parseInt(char, 10);
			for (let i = 0; i < num; i++) {
				let id = y * 8 + x;
				createChessField(chessboard, id, x, y, figures);
				x++;
			}
		} else if (figures.hasOwnProperty(char)) {
			let id = y * 8 + x;
			createChessField(chessboard, id, x, y, figures, char);
			x++;
		} else {
			console.log("Это что-то другое");
		}
	}
}
//Создание клетки
function createChessField(chessboard, id, x, y, figures, char) {
	let field = document.createElement("div");
	field.setAttribute("class", "field");
	field.setAttribute("id", `cell${id}`);
	field.style.backgroundColor = (x + y) % 2 ? "#f0d9b5" : "#b58863";
	field.style.width = `12.5%`;
	field.style.height = `12.5%`;
	field.onclick = function () {
		sendCoordinates(y, x);
	};

	if (char) {
		let img = document.createElement("img");
		img.src = `../images/${figures[char]}`;
		field.appendChild(img);
	}
	chessboard.appendChild(field);
}



createChessboard(data);