<?php

/**
 * Class MessageController inherit TestController
 * Serve to operate view message
 * 9 functions form display data to recover data 
 * 
 */
class MessageController extends TestController{
    
    
    /**
     * Function to insert new messages in DB
     * @parameter 1 string 1 int
     * @return void
     */
    public static function insertMessage(string $content, int $receiverKey) {
        
        $trimMessage = trim($content);
        
        if(!empty($trimMessage)) {
        
            $user = self::findUser($_SESSION['auth']['email']);
            
            $value = " (message_create_at, content, sender_foreign_key, receiver_foreign_key) 
                VALUES (NOW(), :content, :sender_foreign_key, :receiver_foreign_key)";
            
            $execute = [
                "content" => strip_tags($trimMessage),
                "sender_foreign_key" => $user['id'],
                "receiver_foreign_key" => $receiverKey
            ];
            
            self::getModel('ChatBoxModel')->insert($value, $execute);
        }
        
    }
     
     
     
     /**
     * Function to find an User with his id
     * @parameter 1 int => id user
     * @return array
     */
    public static function findOneUser(int $id) : array {
        
        $set = " WHERE id = :id AND admin = :admin ";
        $execute = [
            ':id' => $id,
            ':admin' => 0
        ];
            
        $user = self::getModel('UserModel')->findUniq($set, $execute);
        
        return $user;
    }
    
    
    
    /**
     * Function to recover all messages exchange between user and contact
     * @parameter 2 int => id of user and contact
     * @return array
     */
    public static function findAllMessages(int $contactId, int $userId) : array { 

        $where = 
        "WHERE (receiver_foreign_key = :receiver_foreign_key AND sender_foreign_key = :sender_foreign_key)
        OR (sender_foreign_key = :sender AND receiver_foreign_key = :receiver)
        ORDER BY  message_create_at";
        
        $execute = [
            'receiver_foreign_key' => $userId,
            'sender_foreign_key' =>$contactId,
            'sender' =>$userId,
            'receiver' =>$contactId
        ];
            
        $msgs = self::getModel('ChatBoxModel')->findConversation($where, $execute);
        
        return $msgs;
     }
    
   
   
    /**
    * function recover all identity of contact the user echange messages with
    * message sent and received then gather all result in one array
    * @paramter 1 int user ID
    * @return array
    */
    public static function allContact(int $userId) : array {
        
        // array of contact the user send messages to
        $sendToContact = self::getModel('ChatBoxModel')->messageSend($userId);
        
        // array of contacts who send messages to user
        $receiveFromContact = self::getModel('ChatBoxModel')->messageReceive($userId);
        
        // gather in one array both result
        $result = array_merge($receiveFromContact, $sendToContact);
        
        return $result;
    }
    


    /**
    * Function to erase all duplicate info in a array
    * @parameter 1 array 1 int
    * @return array
    */ 
    public static function unique_multidim_array($array, $key) {
        $temp_array = array();
        $i = 0;
        $key_array = array();
       
        foreach($array as $val) {
            if (!in_array($val[$key], $key_array)) {
                $key_array[$i] = $val[$key];
                $temp_array[$i] = $val;
            }
            $i++;
        }
        return $temp_array;
    }
    
    
    
    /**
    * Function to know if a user is actually connected
    * return empty array if user is not active on the site for the last 2min
    * @return array
    */    
    public static function isUserConnect(int $id) : array {

        $result = self::getModel('SessionUserModel')->userConnected($id);
        
        return $result;
    }
    
    
    
    /**
    * function to change the statut of a read messages in chatbox table
    * @parameter 2 integer
    * @return void
    */    
    public static function updateStatus(int $userId, int $contactId) : void{

        $set = " SET status = :status
                WHERE receiver_foreign_key = :receiver_foreign_key 
                AND sender_foreign_key = :sender_foreign_key";
        $execute = [
            "status" => NULL,
            "receiver_foreign_key" => $userId,
            "sender_foreign_key" => $contactId
            ];
        
        self::getModel('ChatBoxModel')->updateInfo($set, $execute);
    }
    
    
    
    /**
    * Function to erase oldest messages if the count of all messages exchange between user and contact is over 20
    * @parameter 1 array 2 integer
    * @return void
    */  
    public static function deleteMessages($messages, int $userId, int $contactId) : void {
        
        if(count($messages) > 20){
            
            // search for the oldest message between user and contact
            $where = 
            "WHERE (receiver_foreign_key = :receiver_foreign_key AND sender_foreign_key = :sender_foreign_key)
            OR (sender_foreign_key = :sender AND receiver_foreign_key = :receiver)
            ORDER BY  message_create_at ASC
            LIMIT 1";
            
            $execute = [
                'receiver_foreign_key' => $userId,
                'sender_foreign_key' =>$contactId,
                'sender' =>$userId,
                'receiver' =>$contactId
            ];
                
            $msgs = self::getModel('ChatBoxModel')->findConversation($where, $execute);
            
            // delete message with an specific id
            $set = " WHERE id = :id ";
            $executeD = [ "id" => $msgs[0]['id'] ];
            
            self::getModel('ChatBoxModel')->deleteUser($set, $executeD); 
                
        }
    }
    
    
    

    /**
     * function display templates
     * @return void
     */
    public function display() : void
    
    {   
        // Information on the user connect
        $findSenderId = self::findUser($_SESSION['auth']['email']);
        
        // Array of all contacts the user exchanges messages with
        $allContacts = self::allContact($findSenderId['id']);
        
        // Array of all contacts the user exchanges messages without no duplicate contact
        $myContacts = self::unique_multidim_array($allContacts, 'contact_name') ;
        
        if(isset($_GET['user']) && array_key_exists('user', $_GET) && !empty($_GET['user']) ){
            
            
            // Information on one contact
            $oneUser = self::findOneUser($_GET['user']);
            
            if(!empty($oneUser)) {
                
                // Array of all messages exchanges with one contact
                $contactMessage = self::findAllMessages($_GET['user'], $findSenderId['id']);
                
                // to know if contact is on ligne 
                $ongoingSession = self::isUserConnect($_GET['user']);
                
                // update messages read
                self::updateStatus($findSenderId['id'], $_GET['user']);
                // to delete message if count of all messages exchange with one contact is over 20 messages
                self::deleteMessages($contactMessage,  $_GET['user'], $findSenderId['id']);
            
            }else{
                $error = 'Error cet utilisateur n\'existe pas !';
            }
        }
        
        $template = "src/views/message.phtml";
        require "src/views/layout.phtml"; 
    }
    
    
}