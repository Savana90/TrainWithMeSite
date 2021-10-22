<?php

require_once 'GenericModel.php';
require_once 'DatabaseModel.php';
/**
 * Class User inherit from GenericModel 
 *
 * Manage SQL requestes for the ChatBox table
 * 
 * 5 functions
 */
 
class ChatBoxModel extends GenericModel{
    
    protected string $table;
    
    public function __construct(){
        parent::__construct();
        $this->table = 'chat_box';
    }
    
    /**
     * Function requesting all messages exchanges with one user 
     * Function use in MessageController
     * @parametre string and array
     * @return array
     */
    public function findConversation(string $where, array $execute): array{
        try{
            $query = $this->pdo->prepare(
                "SELECT id, CAST(message_create_at AS TIME) AS time_cast, 
                DATE_FORMAT(message_create_at, '%e-%m-%Y %H:%i') AS date_cast, 
                content, sender_foreign_key, receiver_foreign_key
                FROM {$this->table} " . $where);
            
            $query->execute($execute);
            
            $allElementsWhere = $query->fetchAll();
            
            // If no records find return a empty array
            if ($allElementsWhere === false) {
                return [];
            } else {
                return $allElementsWhere;
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
            die;
        }
    }
    
    
    
    /**
     * Function request joint with user table to recover all messages send by one user
     * @parametre integer
     * @return array
     */
    public function messageSend(int $userId): array{
        try{
            $query = $this->pdo->prepare(
                "SELECT receiver.user_name AS contact_name, receiver_foreign_key AS contact_key,
                MAX(message_create_at) AS messages
                FROM {$this->table}
                LEFT JOIN user receiver 
                ON chat_box.receiver_foreign_key = receiver.id
                WHERE sender_foreign_key = :sender_foreign_key
                
                GROUP BY receiver_foreign_key");
            
            $query->execute([
                "sender_foreign_key" => $userId,
            ]);
            
            $allElementsWhere = $query->fetchAll();
            
            // If no records find return a empty array
            if ($allElementsWhere === false) {
                return [];
            } else {
                return $allElementsWhere;
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
            die;
        }
    }
    
    
    /**
     * Function request join with user table to recover all messages received by one user
     * @parametre integer
     * @return array
     */
    public function messageReceive(int $userId): array{
        try{
            $query = $this->pdo->prepare(
                "SELECT sender.user_name AS contact_name, sender_foreign_key AS contact_key,
                MAX(message_create_at) AS last_message_received, COUNT(status) AS statusU
                FROM {$this->table}
                LEFT JOIN user sender 
                ON chat_box.sender_foreign_key = sender.id
                
                WHERE receiver_foreign_key = :receiver_foreign_key
                
                GROUP BY sender_foreign_key");
            
            $query->execute([
                "receiver_foreign_key" => $userId,
            ]);
            
            $allElementsWhere = $query->fetchAll();
            
            // If no records find return a empty array 
            if ($allElementsWhere === false) {
                return [];
            } else {
                return $allElementsWhere;
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
            die;
        }
    }
    
    
    
     /**
     * Function request to recover message with status 0 mean not read by user yet
     * @parametre integer
     * @return array
     */
    public function messageNotRead(int $userId): array{
        try{
            $query = $this->pdo->prepare(
                "SELECT status, receiver_foreign_key, sender_foreign_key
                FROM {$this->table}
                WHERE receiver_foreign_key = :receiver_foreign_key 
                AND status = :status
            ");
            
            $query->execute([
                "receiver_foreign_key" => $userId,
                "status" => 0
            ]);
            
            $allElementsWhere = $query->fetchAll();
            
            // If no records find return a empty array
            if ($allElementsWhere === false) {
                return [];
            } else {
                return $allElementsWhere;
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
            die;
        }
    }
    
    
    
   
   
   
    
    
    
    
    
}