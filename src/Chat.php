<?php
namespace MyApp;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Chat implements MessageComponentInterface {
    protected $clients;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
        echo "server started";
    }

    public function onOpen(ConnectionInterface $conn) {
        // Store the new connection to send messages to later
        $this->clients->attach($conn);

        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $numRecv = count($this->clients) - 1;
        echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
            , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');

            $received_msg = JSON_decode($msg,true);
            $result = null;

            
           switch($received_msg["type"]){
                case "chat":$chat_msg = JSON_decode($msg,true);

                            $msg_obj = new \src\controller\Message;
                            $dsn = "mysql:host=localhost;dbname=my_cms";
                            $pdo = new \PDO($dsn,"root","");
                            
                            $stmt = $pdo->prepare("INSERT INTO messages (user_id,to_id,body) VALUES(:user_id,:to_id,:body)");
                            $msg_id = $stmt->execute([
                                ":user_id"=>$chat_msg["sender_id"],
                                ":to_id"=>$chat_msg["to_id"],
                                ":body"=>$chat_msg["body"]
                            ]);
                            
                            $msg_id = $pdo->lastInsertId();
                            $chat_msg["msg_id"] = $msg_id;
                            
                            $chat_msg = JSON_encode($chat_msg);
                            $msg = $chat_msg;
                            break;

                case "status_update":
                                    $chat_msg = JSON_decode($msg,true);
                                    
                                    $msg_obj = new \src\controller\Message;
                                    $dsn = "mysql:host=localhost;dbname=my_cms";
                                    $pdo = new \PDO($dsn,"root","");
                                    
                                    $stmt = $pdo->prepare("UPDATE messages SET status=:status WHERE id = :msg_id");
                                    $result = $stmt->execute([
                                        ":status"=>$chat_msg["status"],
                                        ":msg_id"=>$chat_msg["msg_id"]
                                    ]);
                    
                                    break; 
                case "message_view_update":
                    $chat_msg = JSON_decode($msg,true);
                                    
                    $msg_obj = new \src\controller\Message;
                    $dsn = "mysql:host=localhost;dbname=my_cms";
                    $pdo = new \PDO($dsn,"root","");
                    $stmt = $pdo->prepare("UPDATE messages SET status=:status WHERE id = :msg_id");
                    $result = $stmt->execute([
                        ":status"=>$chat_msg["status"],
                        ":msg_id"=>$chat_msg["msg_id"]
                    ]);
    
                    break; 
                   
                case "referal":
                    $chat_msg = JSON_decode($msg,true);
                                    
                    $msg_obj = new \src\controller\Message;
                    $dsn = "mysql:host=localhost;dbname=my_cms";
                    $pdo = new \PDO($dsn,"root","");

                    $pdo->beginTransaction();

                   
                    $stmt = $pdo->prepare("INSERT INTO referal_request VALUES(null,:sender_id,:case_id,:receiver_id)");
                    $result = $stmt->execute([
                        ":sender_id"=>$chat_msg["sender_id"],
                        ":case_id"=>$chat_msg["meta_data"]["case_id"],
                        ":receiver_id"=>$chat_msg["to_id"]
                    ]);


                    $stmt = $pdo->prepare("INSERT INTO notification VALUES(null,:to_id,:subject,:meta_data,'unseen','Null',null)");
                    $result = $stmt->execute([
                        ":to_id"=>(int)$chat_msg["to_id"],
                        ":meta_data"=>JSON_encode($chat_msg["meta_data"]),
                        ":subject"=>$chat_msg["subject"]
                    ]);

                    $pdo->commit();
                    break; 
                case "referal_response":
                    $chat_msg = JSON_decode($msg,true);
                    $dsn = "mysql:host=localhost;dbname=my_cms";
                    $pdo = new \PDO($dsn,"root","");

                    if($chat_msg["response"]){
                        $pdo->beginTransaction();

                        $stmt = $pdo->prepare("UPDATE complaint SET to_id= :to_id WHERE id = :id");
                        $result = $stmt->execute([
                            ":to_id"=>(int)$chat_msg["accepter_id"],
                            ":id"=>$chat_msg["case_id"]
                        ]);

                        $stmt = $pdo->prepare("UPDATE notification SET response = :response WHERE id = :id");
                        $result = $stmt->execute([
                            ":response"=>($chat_msg["response"])?"Accepted":"Rejected",
                            ":id"=>$chat_msg["notice_id"]
                        ]);

                        $stmt = $pdo->prepare("DELETE FROM referal_request WHERE receiver_id= :to_id AND  case_id = :case_id");
                        $result = $stmt->execute([
                            ":to_id"=>(int)$chat_msg["accepter_id"],
                            ":case_id"=>$chat_msg["case_id"]
                        ]);
    
                        $pdo->commit();
                    }else{
                        $pdo->beginTransaction();

                        $stmt = $pdo->prepare("UPDATE notification SET response = :response WHERE id = :id");
                        $result = $stmt->execute([
                            ":response"=>($chat_msg["response"])?"Accepted":"Rejected",
                            ":id"=>$chat_msg["notice_id"]
                        ]);

                        $stmt = $pdo->prepare("DELETE FROM referal_request WHERE receiver_id= :to_id AND  case_id = :case_id");
                        $result = $stmt->execute([
                            ":to_id"=>(int)$chat_msg["accepter_id"],
                            ":case_id"=>$chat_msg["case_id"]
                        ]);

                        $pdo->commit();
                    }

                    break;
                
                    case "cancel request":
                        $chat_msg = JSON_decode($msg,true);
                        $dsn = "mysql:host=localhost;dbname=my_cms";
                        $pdo = new \PDO($dsn,"root","");

                        $pdo->beginTransaction();

                        $stmt = $pdo->prepare("DELETE FROM referal_request WHERE id = :id");
                        $result = $stmt->execute([
                            ":id"=>$chat_msg["request_id"]
                        ]);

                        $stmt = $pdo->prepare("INSERT INTO notification VALUES(null,:to_id,:subject,:meta_data,'unseen','Cancelled',null)");
                        $result = $stmt->execute([
                            ":to_id"=>$chat_msg["to_id"],
                            ":subject"=>$chat_msg["subject"],
                            ":meta_data"=>JSON_encode($chat_msg["meta_data"])
                        ]);
                   
                        $pdo->commit();
                        break;

                    case "delete complaint":
                        $chat_msg = JSON_decode($msg,true);
                        $dsn = "mysql:host=localhost;dbname=my_cms";
                        $pdo = new \PDO($dsn,"root","");

                        $pdo->beginTransaction();
                        $stmt = $pdo->prepare("DELETE FROM complaint WHERE id = :id");
                        $result = $stmt->execute([
                            ":id"=>$chat_msg["id"]
                        ]);

                        $stmt = $pdo->prepare("DELETE FROM complaint_files WHERE complaint_id = :id");
                        $result = $stmt->execute([
                            ":id"=>$chat_msg["id"]
                        ]);

                        $stmt = $pdo->prepare("INSERT INTO notification VALUES(null,:to_id,:subject,:meta_data,'unseen','Null',null)");
                        $result = $stmt->execute([
                            ":to_id"=>$chat_msg["to_id"],
                            ":subject"=>$chat_msg["subject"],
                            ":meta_data"=>JSON_encode($chat_msg["meta_data"])
                        ]);

                        $pdo->commit();
                        break;

                        case "history":
                            $chat_msg = JSON_decode($msg,true);
                            $dsn = "mysql:host=localhost;dbname=my_cms";
                            $pdo = new \PDO($dsn,"root","");

                            $stmt = $pdo->prepare("INSERT INTO history VALUES(null,:user_id,:subject,:meta_data,null)");
                            $result = $stmt->execute([
                                ":user_id"=>$chat_msg["user_id"],
                                ":subject"=>$chat_msg["subject"],
                                ":meta_data"=>JSON_encode($chat_msg["meta_data"])
                            ]);
                            break;

                            case "notice":
                                $chat_msg = JSON_decode($msg,true);
                                $dsn = "mysql:host=localhost;dbname=my_cms";
                                $pdo = new \PDO($dsn,"root","");
                                $stmt = $pdo->prepare("INSERT INTO notification VALUES(null,:to_id,:subject,:meta_data,'unseen','Null',null)");
                                $result = $stmt->execute([
                                ":to_id"=>$chat_msg["to_id"],
                                ":subject"=>$chat_msg["subject"],
                                ":meta_data"=>JSON_encode($chat_msg["meta_data"])
                                ]);
                                break;

                                case "user_status":
                                    $chat_msg = JSON_decode($msg,true);
                                    $dsn = "mysql:host=localhost;dbname=my_cms";
                                    $pdo = new \PDO($dsn,"root","");
                                    $stmt = $pdo->prepare("UPDATE user_details SET online= :status WHERE user_id = :user_id");
                                    $result = $stmt->execute([
                                    ":user_id"=>$chat_msg["id"],
                                    ":status"=>$chat_msg["status"]
                                    ]);
                                    break;




            }
        foreach ($this->clients as $client) {
            if ($from !== $client) {
                // The sender is not the receiver, send to each client connected
                $client->send($msg);
            }
        }
    }

    public function onClose(ConnectionInterface $conn) {

        // The connection is closed, remove it, as we can no longer send it messages

        $this->clients->detach($conn);

        echo "Connection {$conn->resourceId} has disconnected\n";
        
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }
}