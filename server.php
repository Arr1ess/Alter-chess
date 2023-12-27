<?php
require __DIR__ . '/vendor/autoload.php';

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use Ratchet\Http\HttpServer;

class ChessRoom
{
    public $participants;
    public $whitePlayer;
    public $blackPlayer;
    public $currentPosition;
    public $moveList;
    public $remainingTimeWhite;
    public $remainingTimeBlack;
    public $roomId;
    public $gameType;
    public function __construct($roomId, $gameType)
    {
        $this->participants = new \SplObjectStorage();
        $this->whitePlayer = null;
        $this->blackPlayer = null;
        $this->currentPosition = "rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR";
        $this->moveList = "";
        $this->remainingTimeWhite = 0;
        $this->remainingTimeBlack = 0;
        $this->roomId = $roomId;
        $this->gameType = $gameType;
    }
    public function getInformationAsString()
    {
        ob_start();
        var_dump($this);
        return ob_get_clean();
    }
}


class MyWebSocketServer implements MessageComponentInterface
{
    protected $clients;
    protected $rooms;

    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
        $this->rooms = [];
        echo "Server was start\n";
    }

    public function onOpen(ConnectionInterface $conn)
    {
        $this->clients->attach($conn);
        $conn->roomId = null; // Идентификатор текущей комнаты
        // echo "New connection! ({$conn->resourceId})\n";
    }
    public function onMessage(ConnectionInterface $from, $msg)
    {
        $message = json_decode($msg, true);
        if (isset($message['type'])) {
            $type = $message['type'];

            if (isset($message['roomId'])) {
                $roomId = $message['roomId'];
                if ($type == "findRoom") {
                    $answer = array(
                        'type' => "isFindRoom",
                        'roomIsFind' => array_key_exists($roomId, $this->rooms)
                    );
                    $from->send($answer);
                }
                // echo "find = " . array_key_exists($roomId, $this->rooms) . "\n";
                $room = array_key_exists($roomId, $this->rooms) ? $this->rooms[$roomId] : new ChessRoom($roomId, $message['gameType']);
                switch ($type) {
                    case "newRoom":
                        if (!isset($room))
                            $room = new ChessRoom($roomId, $message['gameType']);
                        break;
                    case "addUser":
                        if (!$room->participants->contains($from)) {
                            $room->participants->attach($from);
                            echo "user is add\n";
                            $playerRole = isset($message['playerRole']) ? $message['playerRole'] : 'viewer';
                            if ($playerRole == "whitePlayer") {
                                $room->whitePlayer = $message['userId'];
                            } else if ($playerRole == "blackPlayer") {
                                $room->blackPlayer = $message['userId'];
                            }
                        }
                        echo "user is add as " . $playerRole . " in game " . $room->roomId . "\n";
                        break;
                    case "closeRoom":
                        $this->closeRoom($roomId);
                        break;
                    case "setPosition":
                        if (isset($message['board'])) {
                            $room->currentPosition = $message['board'];
                        }
                        $answer = array(
                            'type' => 'setPosition',
                            'board' => $room->currentPosition,
                        );
                        $this->sendAllUsers($answer, $room);
                        break;
                    case "setTurnMove":
                        $answer = array(
                            'type' => 'setTurnMove',
                            'turnMove' => $message['turnMove'],
                        );
                        $this->sendAllUsers($answer, $room);
                        break;
                    case "setTime":
                        if (isset($message['whiteTime'])) {
                            $room->remainingTimeWhite = $message['whiteTime'];
                        }
                        if (isset($message['blackTime'])) {
                            $room->remainingTimeBlack = $message['blackTime'];
                        }
                        $answer = array(
                            'type' => 'setTime',
                            'whiteTime' => $room->remainingTimeWhite,
                            'blackTime' => $room->remainingTimeBlack
                        );
                        $this->sendAllUsers($answer, $room);
                        break;
                    default:
                        echo "Command is not definded\n";
                }
            } else {
                echo "roomId is not definded\n" . $msg . "\n";
            }
        }
    }

    public function getRole($from, $role)
    {
        $message = [
            'type' => 'setRole',
            'role' => $role
        ];
        $from->send(json_encode($message));
    }

    public function sendAllUsers($data, $room)
    {
        foreach ($room->participants as $client) {
            $client->send(json_encode($data));
        }
    }

    public function closeRoom($roomId)
    {
        if (isset($this->rooms[$roomId])) {
            unset($this->rooms[$roomId]);
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
        $this->clients->detach($conn);
        echo "Connection closed\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }
}

$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new MyWebSocketServer()
        )
    ),
    8080
);

$server->run();
?>