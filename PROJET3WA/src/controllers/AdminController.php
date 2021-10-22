<?php

/**
 * Class AdminController inherit form TestController
 * Serve to operate as a admin of the site on view Admin page 
 * 5 functions
 */
class AdminController extends TestController
{
    
    
    
    /**
     * Recover array of users with a limit of 10 records stating form a certain record
     * @parameter 2 integer
     * @return array
     */
    public static function allUserByPage(int $id, ?int $page = 1) : array {
        // determine where the record have to start
        $debut = ($page-1) * 10;
        
        // function to recover array of users
        $users = self::getModel('UserModel')->paging($debut, $id);
        
        return $users;
    }
    
    
    /**
     * Recover array with count of page quantity of record in array user
     * @return array
     */
    public static function pages() : array {
        
        // recover quantity of users register to the site
        $users = self::getModel('UserModel')->quantite();
        $qtt = $users[0]['qtt'];
        
        // Nbr of users per page
        $nbrOfCard = 10;
        
        // give the nbr of page needed to have 10 users per page
        $nbrOfPages = ceil($qtt/$nbrOfCard);
        
        $result = [
            "nbrOfPages" => $nbrOfPages,
            "nbrOfCard" => $nbrOfCard,
            "qtt" => $qtt
            ]; 
            
        return $result;
    }
    
    
     /**
     * Update status Administrator or User in table user
     * @parameter 2 integer
     * @return void
     */
    public static function updateAdmin(int $admin, int $id) : void {
        
        $execute = [
            ":admin" => $admin, 
            ":id" => $id];
        $set = " SET admin = :admin WHERE id = :id";
        
        // use of function of class UserModel to update status user
        self::getModel('UserModel')->updateInfo($set, $execute);
        
        session::setSucceed ('Modification enregistrée !');
    }
    
    
    /**
     * Function to delete a user 
     * @paramater 1 integer id of the user to delete
     * @return void
     */
    public static function eraseUser(int $id) : void {
        
        $setUser = " WHERE user_foreign_key = :user_foreign_key";
        $executeUser = [
            ":user_foreign_key" => $id
            ];
            
        // check if user is still connected in session_user table
        $isConnected = self::getModel('SessionUserModel')->findUniq($setUser, $executeUser);
        
        if (!empty($isConnected)) {
            // delete user form session_user table
            self::getModel('SessionUserModel')->deleteUser($setUser, $executeUser);
        }
        
        $execute = [
            ":id" => $id];
        $set = " WHERE id = :id";
       
        $setDeleteMessage = " WHERE sender_foreign_key = :sender_foreign_key
                OR receiver_foreign_key = :receiver_foreign_key";
                
        $executeDeleteMessage = [
            "sender_foreign_key" => $id,
            "receiver_foreign_key" => $id
            ];
            
        // Delete all messages from the user send and receive
        self::getModel('ChatBoxModel')->deleteUser($setDeleteMessage, $executeDeleteMessage);
        
        // Delete user
        self::getModel('UserModel')->deleteUser($set, $execute);
        
        session::setSucceed ('Utilisateur supprimé !');
    }
    
    
    /**
     * Function to request contact_message not seen by admin
     * @return int
     */
    public static function getNotif() : int {
        
        $where = 'WHERE msg_process = :msg_process';
        $execute = [
            'msg_process' => 0
            ];
            
        $msgs = self::getModel('ContactMessageModel')->findAllWithCondition($where, $execute);
        $quantity = count($msgs); 
        return $quantity; 
        
    }
    
    
    
     /**
     * Function to display templates
     * @return void
     */
    public function display() : void
    {   
        $pages  = self::pages();
        
        $msgs = self::getNotif();
        
        $userId = self::findUser($_SESSION['auth']['email']);

        if(isset($_GET) && array_key_exists('pages', $_GET)
        && !empty($_GET['pages'])) {

            $allUsers = self::allUserByPage($userId['id'], $_GET['pages']);
            
        }else{
            
            $allUsers = self::allUserByPage($userId['id']);
        }
            
        $template = "src/views/admin.phtml";
        require "src/views/layout.phtml";
    }

}

