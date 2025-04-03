<?php
/**
 * WebSocket Server cho tính năng Check-in Realtime
 * 
 * Yêu cầu:
 * - PHP 7.4+ với extension sockets
 * - Composer packages: cboden/ratchet, react/socket
 * 
 * Cách chạy:
 * - php websocket_server.php
 * 
 * Hoặc chạy dưới nền với:
 * - nohup php websocket_server.php > websocket.log 2>&1 &
 */

require 'vendor/autoload.php';

use Ratchet\ConnectionInterface;
use Ratchet\Http\HttpServer;
use Ratchet\MessageComponentInterface;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use React\EventLoop\Factory;
use React\Socket\SecureServer;
use React\Socket\Server;
use React\Http\Message\Response;
use Psr\Http\Message\RequestInterface;

// Thông tin server
$host = '0.0.0.0'; // Lắng nghe trên tất cả các interfaces
$port = 8080;      // Port mặc định
$debug = true;     // Bật/tắt log debug

// Tạo một thư mục lưu log nếu chưa có
$logDir = __DIR__ . '/logs';
if (!is_dir($logDir)) {
    mkdir($logDir, 0755, true);
}

// Tạo file log
$logFile = $logDir . '/websocket_' . date('Y-m-d') . '.log';
$pushLogFile = $logDir . '/websocket_push_' . date('Y-m-d') . '.log';

class EventCheckInServer implements MessageComponentInterface
{
    protected $clients;
    protected $subscriptions = [];
    protected $debug;
    protected $logFile;

    public function __construct($debug = false, $logFile = null)
    {
        $this->clients = new \SplObjectStorage;
        $this->debug = $debug;
        $this->logFile = $logFile;
        $this->log('WebSocket server initialized');
    }

    /**
     * Ghi log
     */
    public function log($message)
    {
        $timestamp = date('Y-m-d H:i:s');
        $logMessage = "[$timestamp] $message" . PHP_EOL;
        
        if ($this->debug) {
            echo $logMessage;
        }
        
        if ($this->logFile) {
            file_put_contents($this->logFile, $logMessage, FILE_APPEND);
        }
    }

    /**
     * Xử lý khi có client kết nối mới
     */
    public function onOpen(ConnectionInterface $conn)
    {
        $this->clients->attach($conn);
        $conn->subscriptions = [];
        
        $clientId = $conn->resourceId;
        $clientIp = $conn->remoteAddress;
        
        $this->log("New connection: Client {$clientId} from {$clientIp}");
        
        // Gửi phản hồi connection_established
        $conn->send(json_encode([
            'type' => 'connection_established',
            'client_id' => $clientId,
            'message' => 'Connected to check-in server'
        ]));
    }

    /**
     * Xử lý khi nhận được message từ client
     */
    public function onMessage(ConnectionInterface $from, $msg)
    {
        $fromId = $from->resourceId;
        $this->log("Received message from client {$fromId}: {$msg}");
        
        try {
            $data = json_decode($msg, true);
            
            if (!is_array($data) || !isset($data['type'])) {
                throw new \Exception("Invalid message format");
            }
            
            // Xử lý các loại message
            switch ($data['type']) {
                case 'subscribe':
                    $this->handleSubscribe($from, $data);
                    break;
                    
                case 'unsubscribe':
                    $this->handleUnsubscribe($from, $data);
                    break;
                    
                case 'ping':
                    $from->send(json_encode([
                        'type' => 'pong',
                        'time' => time()
                    ]));
                    break;
                    
                default:
                    $this->log("Unknown message type: {$data['type']}");
                    break;
            }
        } catch (\Exception $e) {
            $this->log("Error processing message: " . $e->getMessage());
            
            $from->send(json_encode([
                'type' => 'error',
                'message' => 'Invalid message format'
            ]));
        }
    }

    /**
     * Xử lý khi client đóng kết nối
     */
    public function onClose(ConnectionInterface $conn)
    {
        $this->clients->detach($conn);
        
        // Xóa tất cả các subscriptions của client này
        if (isset($conn->subscriptions)) {
            foreach ($conn->subscriptions as $eventId) {
                if (isset($this->subscriptions[$eventId])) {
                    $key = array_search($conn, $this->subscriptions[$eventId]);
                    if ($key !== false) {
                        unset($this->subscriptions[$eventId][$key]);
                    }
                }
            }
        }
        
        $this->log("Connection {$conn->resourceId} has disconnected");
    }

    /**
     * Xử lý lỗi kết nối
     */
    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        $this->log("Error on connection {$conn->resourceId}: " . $e->getMessage());
        $conn->close();
    }

    /**
     * Xử lý đăng ký nhận thông báo cho sự kiện
     */
    protected function handleSubscribe(ConnectionInterface $client, $data)
    {
        if (!isset($data['eventId'])) {
            $client->send(json_encode([
                'type' => 'error',
                'message' => 'Missing eventId in subscribe request'
            ]));
            return;
        }
        
        $eventId = $data['eventId'];
        
        if (!isset($this->subscriptions[$eventId])) {
            $this->subscriptions[$eventId] = [];
        }
        
        // Thêm client vào danh sách theo dõi sự kiện
        $this->subscriptions[$eventId][] = $client;
        
        // Lưu danh sách đăng ký của client
        if (!isset($client->subscriptions)) {
            $client->subscriptions = [];
        }
        $client->subscriptions[] = $eventId;
        
        $this->log("Client {$client->resourceId} subscribed to event {$eventId}");
        
        // Phản hồi xác nhận đăng ký thành công
        $client->send(json_encode([
            'type' => 'subscription_confirmed',
            'eventId' => $eventId,
            'message' => 'Successfully subscribed to event'
        ]));
    }

    /**
     * Xử lý hủy đăng ký nhận thông báo cho sự kiện
     */
    protected function handleUnsubscribe(ConnectionInterface $client, $data)
    {
        if (!isset($data['eventId'])) {
            $client->send(json_encode([
                'type' => 'error',
                'message' => 'Missing eventId in unsubscribe request'
            ]));
            return;
        }
        
        $eventId = $data['eventId'];
        
        if (isset($this->subscriptions[$eventId])) {
            $key = array_search($client, $this->subscriptions[$eventId]);
            if ($key !== false) {
                unset($this->subscriptions[$eventId][$key]);
                $this->log("Client {$client->resourceId} unsubscribed from event {$eventId}");
            }
        }
        
        // Cập nhật danh sách đăng ký của client
        if (isset($client->subscriptions)) {
            $key = array_search($eventId, $client->subscriptions);
            if ($key !== false) {
                unset($client->subscriptions[$key]);
            }
        }
        
        // Phản hồi xác nhận hủy đăng ký thành công
        $client->send(json_encode([
            'type' => 'unsubscription_confirmed',
            'eventId' => $eventId,
            'message' => 'Successfully unsubscribed from event'
        ]));
    }

    /**
     * Phương thức này được gọi từ HTTP endpoint để broadcast dữ liệu
     */
    public function broadcastToEvent($eventId, $data)
    {
        if (!isset($this->subscriptions[$eventId]) || empty($this->subscriptions[$eventId])) {
            $this->log("No subscribers for event {$eventId}");
            return 0;
        }
        
        $message = json_encode($data);
        $clientCount = 0;
        
        // Gửi dữ liệu đến tất cả các client đăng ký
        foreach ($this->subscriptions[$eventId] as $client) {
            try {
                $client->send($message);
                $clientCount++;
            } catch (\Exception $e) {
                $this->log("Error sending to client {$client->resourceId}: " . $e->getMessage());
            }
        }
        
        $this->log("Broadcasted to {$clientCount} clients for event {$eventId}: {$message}");
        return $clientCount;
    }
}

// Khởi tạo Push Endpoint cho webhook
function handlePushRequest(RequestInterface $request)
{
    global $wsServer, $pushLogFile, $debug;
    
    // Log request
    $timestamp = date('Y-m-d H:i:s');
    $requestMethod = $request->getMethod();
    $requestPath = $request->getUri()->getPath();
    $requestBody = (string)$request->getBody();
    
    $logMessage = "[$timestamp] Received HTTP {$requestMethod} request to {$requestPath}\n";
    $logMessage .= "Body: {$requestBody}\n";
    
    if ($debug) {
        echo $logMessage;
    }
    
    if ($pushLogFile) {
        file_put_contents($pushLogFile, $logMessage, FILE_APPEND);
    }
    
    // Chỉ chấp nhận POST requests
    if ($requestMethod !== 'POST') {
        return new Response(
            405,
            ['Content-Type' => 'application/json'],
            json_encode(['error' => 'Method not allowed'])
        );
    }
    
    // Xác định đường dẫn
    if ($requestPath !== '/push') {
        return new Response(
            404,
            ['Content-Type' => 'application/json'],
            json_encode(['error' => 'Endpoint not found'])
        );
    }
    
    // Parse dữ liệu JSON
    try {
        $data = json_decode($requestBody, true);
        
        if (!is_array($data)) {
            throw new \Exception('Invalid JSON data');
        }
        
        if (!isset($data['data']) || !isset($data['data']['eventId'])) {
            throw new \Exception('Missing eventId in data');
        }
        
        $eventId = $data['data']['eventId'];
        $clientCount = $wsServer->broadcastToEvent($eventId, $data);
        
        return new Response(
            200,
            ['Content-Type' => 'application/json'],
            json_encode([
                'success' => true,
                'message' => "Data sent to {$clientCount} clients",
                'clients' => $clientCount
            ])
        );
    } catch (\Exception $e) {
        $errorMessage = "Error processing push request: " . $e->getMessage();
        
        if ($debug) {
            echo $errorMessage . "\n";
        }
        
        if ($pushLogFile) {
            file_put_contents($pushLogFile, "[$timestamp] ERROR: {$errorMessage}\n", FILE_APPEND);
        }
        
        return new Response(
            400,
            ['Content-Type' => 'application/json'],
            json_encode([
                'error' => $e->getMessage()
            ])
        );
    }
}

// Khởi tạo EventLoop
$loop = Factory::create();

// Khởi tạo WebSocket server
$wsServer = new EventCheckInServer($debug, $logFile);

// Khởi tạo WebSocket server
$webSock = new Server("{$host}:{$port}", $loop);
$webServer = new IoServer(
    new HttpServer(
        new WsServer($wsServer)
    ),
    $webSock
);

// Khởi tạo HTTP server cho push endpoint
$httpServer = new \React\Http\HttpServer(
    function (RequestInterface $request) {
        return handlePushRequest($request);
    }
);

// Listen trên cùng socket
$httpServer->listen($webSock);

echo "WebSocket server running at ws://{$host}:{$port}" . PHP_EOL;
echo "Push endpoint available at http://{$host}:{$port}/push" . PHP_EOL;

// Chạy event loop
$loop->run();
