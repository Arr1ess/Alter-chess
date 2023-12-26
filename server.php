<?php
require __DIR__ . '/vendor/autoload.php';

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use Ratchet\Http\HttpServer;

class ChessRoom
{
    public $participants; // Список всех участников
    public $whitePlayer; // Игрок за белых
    public $blackPlayer; // Игрок за черных
    public $currentPosition; // Текущая позиция в строковом формате
    public $moveList; // Список ходов в строковом формате
    public $remainingTimeWhite; // Оставшееся время белых в секундах
    public $remainingTimeBlack; // Оставшееся время черных в секундах

    public $roomId;
    public function __construct($roomId)
    {
        $this->participants = new \SplObjectStorage();
        $this->whitePlayer = null;
        $this->blackPlayer = null;
        $this->currentPosition = "rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR";
        $this->moveList = "";
        $this->remainingTimeWhite = 0;
        $this->remainingTimeBlack = 0;
        $this->roomId = $roomId;
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
    protected $rooms; // Словарь для хранения комнат и их участников 

    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
        $this->rooms = [];
    }

    public function onOpen(ConnectionInterface $conn)
    {
        $this->clients->attach($conn);
        $conn->roomId = null; // Идентификатор текущей комнаты
        echo "New connection! ({$conn->resourceId})\n";
    }

    

    public function onMessage(ConnectionInterface $from, $msg)
    {
        $message = json_decode($msg, true);
        if (isset($message['type'])) {
            $type = $message['type'];
            if (isset($message['roomId'])) {
                $roomId = $message['roomId'];
                $room = array_key_exists($roomId, $this->rooms) ? $this->rooms[$roomId] : new ChessRoom($roomId);
                switch ($type) {
                    case "newRoom":
                        if (!isset($room))
                            $room = new ChessRoom($roomId);
                        break;
                    case "addUser":
                        if (!$room->participants->contains($from)) {
                            $room->participants->attach($from);
                            echo "user is add\n";
                            
                            if (!isset($room->whitePlayer))
                                $room->whitePlayer = $message['user'];
                            else if (!isset($room->blackPlayer))
                                $room->blackPlayer = $message['user'];
                        }
                        // echo "send all users in" . $room -> getInformationAsString() . "\n";
                        if ($room->whitePlayer === $message['user'])
                            $this->getRole($from, 'white');
                        else if ($room->blackPlayer === $message['user'])
                            $this->getRole($from, 'black');
                        else
                            $this->getRole($from, 'viewer');

                        break;
                    case "closeRoom":
                        $this->closeRoom($roomId);
                        break;
                    case "setPosition":
                        if (isset($message['board'])) {
                            $room->currentPosition = $message['board'];
                        }
                        $message = array(
                            'type' => 'setPosition',
                            'board' => $room -> currentPosition,
                        );
                        $this->sendAllUsers($message, $room);
                        echo "position is set\n" . $room -> roomId . "\n";
                        break;
                    case "countdownUpdate":
                        $this->sendAllUsers($msg, $room);
                        break;
                    default:
                        echo "Command is not definded\n";
                }
            } else {
                echo "roomId is not definded";
            }
        }
    }

    public function getPosition($roomId)
    {
        return isset($this->rooms[$roomId]->currentPosition) ? $this->rooms[$roomId]->currentPosition : "rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR";
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
        // echo "send all users in" . $room -> getInformationAsString() . "\n";
        foreach ($room -> participants as $client) {
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