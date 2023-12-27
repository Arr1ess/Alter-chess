const countdownValue1 = document.getElementById("countdownValue1");
const countdownValue2 = document.getElementById("countdownValue2");

const socket = new WebSocket("ws://localhost:8080");

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
		// case "setRole":
		// 	setRole(message.role);
		// 	break;
		default:
			console.log(message);
			break;
	}
};

let timeLeft1 = 3600; // Например, 1 час
let timeLeft2 = 1800; // Например, 30 минут

function updateCountdown(timer, value) {
	const hours = Math.floor(timer / 3600);
	const minutes = Math.floor((timer % 3600) / 60);
	const seconds = timer % 60;

	value.textContent = `${String(hours).padStart(2, "0")}:${String(
		minutes
	).padStart(2, "0")}:${String(seconds).padStart(2, "0")}`;
}

function startCountdown() {
	const countdownInterval = setInterval(() => {
		if (turnMove) timeLeft1--;
		else timeLeft2--;

		updateCountdown(timeLeft1, countdownValue1);
		updateCountdown(timeLeft2, countdownValue2);

		if (timeLeft1 <= 0 || timeLeft2 <= 0) clearInterval(countdownInterval);
	}, 1000);
}

function takeMove(data = null) {
	const message = {
		type: "setPosition",
		board: data,
		roomId: getIdFromGameTag(),
	};
	socket.send(JSON.stringify(message));

	const time = {
		type: "countdownUpdate",
		countdownId: !turnMove,
		roomId: getIdFromGameTag(),
	};
	socket.send(JSON.stringify(time));
}

function createNewRoom() {
	const message = {
		type: "newRoom",
		roomId: getIdFromGameTag(),
	};
	socket.send(JSON.stringify(message));
}

function renderRooms(arr) {
	const roomsBlock = document.querySelector(".rooms");
	roomsBlock.innerHTML = ""; // Очистить содержимое элемента "rooms"
	arr.forEach((room) => {
		const button = document.createElement("button");
		button.innerText = room;
		button.addEventListener("click", () => removeRoom(room));
		roomsBlock.appendChild(button);
	});
}

function removeRoom(roomId) {
	const message = {
		type: "closeRoom",
		roomId: roomId,
	};
	socket.send(JSON.stringify(message));
	updateRooms();
}

function addInRoom(playerRole) {
	const message = {
		type: "addUser",
		roomId: getIdFromGameTag(),
		playerRole: playerRole,
		user: getUserId(),
	};
	socket.send(JSON.stringify(message));
}

function onPageLoad() {
	takeMove();
}

socket.onopen = function (event) {
	onPageLoad();
};
