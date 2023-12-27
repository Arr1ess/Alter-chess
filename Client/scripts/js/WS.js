const socket = new WebSocket("ws://localhost:8080");

var gameIsFind = false;

socket.onmessage = function (event) {
	const message = JSON.parse(event.data);
	switch (message.type) {
		case "countdownUpdate":
			turnMove = message.countdownId;
			console.log(turnMove);
			break;
		case "setPosition":
			createChessboard(message.board);
			break;
		case "isFindRoom":
			gameIsFind = message.roomIsFind;
		// case "setRole":
		// 	setRole(message.role);
		// 	break;
		default:
			console.log(message);
			break;
	}
};


function isGameFind(){
	const request = {
		type: "findRoom",
		roomId: getIdFromGameTag(),
	};
	socket.send(JSON.stringify(request));
	return gameIsFind();
}