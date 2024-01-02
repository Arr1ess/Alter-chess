const socket = new WebSocket("wss://chess-alter.ru/wss");

var gameIsFind = false;
socket.onmessage = function (event) 
{
	const message = JSON.parse(event.data);
	switch (message.type) {
		case "isFindRoom":
			if (!message.roomIsFind) {
				uncorrectGameId();
			}
			break;
		case "takeMove":
			turnMove = message.turnMove;
			data = message.board;
			createChessboard(data);
			break;
		case "isWhiteSet":
			if (message.isWhiteSet) addOption("whitePlayer", "Белые", false);
			// console.log("White is set");
			break;
		case "isBlackSet":
			// console.log("black is set");
			if (message.isBlackSet) addOption("blackPlayer", "Черные", false);
			break;
		default:
			console.log(message);
			break;
	}
};

function takeMove(data) {
	const request = {
		type: "takeMove",
		userId: getUserId(),
		board: data,
	};
	send(request);
	// console.log("Move is Take");
}

function addUser(userRole = "viewer") {
	const request = {
		type: "addUser",
		playerRole: userRole,
		userId: getUserId(),
	};
	send(request);
	// console.log("Player is add");
}

function getData() {
	const request = {
		type: "getData",
	};
	send(request);
}

function send(data) {
	if (socket.readyState === WebSocket.OPEN) {
		data["roomId"] = getIdFromGameTag();
		socket.send(JSON.stringify(data));
	}
}

function isGameFind() {
	const request = {
		type: "findRoom",
		roomId: getIdFromGameTag(),
	};
	send(request);
	return gameIsFind;
}
