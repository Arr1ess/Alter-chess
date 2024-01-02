const socket = new WebSocket("wss://chess-alter.ru/wss");
document.querySelector(".btn").addEventListener("click", function (e) {
	e.preventDefault();
	const message = {
		type: "newRoom",
		roomId: gameId,
		gameType: gameName,
	};
	socket.send(JSON.stringify(message));

	const setTime = {
		type: "setTime",
		roomId: "<?= $gameId ?>",
		whiteTime: "<?= $minutes * 60 + $seconds ?>",
		blackTime: "<?= $minutes * 60 + $seconds ?>",
	};

	//socket.send(JSON.stringify(setTime));

	window.location.href = "../GameRoomPage/?gameId=" + gameId;
});
