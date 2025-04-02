<?php
// Sử dụng thư viện Ratchet cho WebSocket
require 'vendor/autoload.php';

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

/**
 * WebSocket Handler cho Check-in Display
 */
class CheckinDisplaySocket implements MessageComponentInterface
{
    protected $clients;
    protected $eventSubscriptions = [];
    
    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
        echo "WebSocket Server started!\n";
    }
    
    public function onOpen(ConnectionInterface $conn)
    {
        $this->clients->attach($conn);
        echo "New connection! ({$conn->resourceId})\n";
    }
    
    public function onMessage(ConnectionInterface $from, $msg)
    {
        $data = json_decode($msg, true);
        
        // Đăng ký client theo eventId
        if (isset($data['type']) && $data['type'] === 'register' && isset($data['eventId'])) {
            $eventId = $data['eventId'];
            
            if (!isset($this->eventSubscriptions[$eventId])) {
                $this->eventSubscriptions[$eventId] = new \SplObjectStorage;
            }
            
            $this->eventSubscriptions[$eventId]->attach($from);
            echo "Client {$from->resourceId} registered for event {$eventId}\n";
            
            // Gửi xác nhận đăng ký thành công
            $from->send(json_encode([
                'type' => 'registered',
                'eventId' => $eventId
            ]));
        }
        // Nhận dữ liệu check-in và broadcast tới các client đã đăng ký
        elseif (isset($data['type']) && $data['type'] === 'checkin' && isset($data['eventId'])) {
            $eventId = $data['eventId'];
            
            if (isset($this->eventSubscriptions[$eventId])) {
                echo "Broadcasting check-in to " . count($this->eventSubscriptions[$eventId]) . " clients for event {$eventId}\n";
                
                foreach ($this->eventSubscriptions[$eventId] as $client) {
                    $client->send($msg);
                }
            } else {
                echo "No clients registered for event {$eventId}\n";
            }
        }
    }
    
    public function onClose(ConnectionInterface $conn)
    {
        $this->clients->detach($conn);
        
        // Xóa client khỏi tất cả các đăng ký sự kiện
        foreach ($this->eventSubscriptions as $eventId => $clients) {
            if ($clients->contains($conn)) {
                $clients->detach($conn);
                echo "Client {$conn->resourceId} unregistered from event {$eventId}\n";
                
                // Nếu không còn client nào đăng ký sự kiện, xóa sự kiện
                if ($clients->count() === 0) {
                    unset($this->eventSubscriptions[$eventId]);
                    echo "No more clients for event {$eventId}, removing event\n";
                }
            }
        }
        
        echo "Connection {$conn->resourceId} has disconnected\n";
    }
    
    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }
}

// Thiết lập và chạy WebSocket server
$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new CheckinDisplaySocket()
        )
    ),
    8080
);

echo "WebSocket Server running at 127.0.0.1:8080\n";
$server->run();
