<?php

/**
 * Class HomeController inherit from TestController class
 * Serve to operate the home page 
 * 8 functions
 */
class HomeController extends TestController
{
    
    
    /**
     * Function to find contact that goes in a specific gym place
     * @parameter 2 integer id of the gym place and id of the user so he will not
     * be includ in the result
     * @return array
     */
    public static function findAllUserByGymName(int $gym_foreign_key, int $id ) : array {

        $where = "WHERE gym_foreign_key = :gym_foreign_key AND id != :id";
        $execute = [
            "gym_foreign_key" => $gym_foreign_key,
            "id" => $id
        ];
            
        $allUsersByGym = self::getModel('UserModel')->findAllWithCondition($where, $execute);
        
        return $allUsersByGym;
    }
    
    
    /**
     * Function to get a gym place
     * @parameter 1 string
     * @return array
     */
    public static function gymUser(int $id) : array { 
        
        $set = " WHERE id = :id";
        $execute = [
            "id" => $id
        ];
            
        $userGym = self::getModel('GymModel')->findUniq($set, $execute);
        
        return $userGym;

    }
    
    
    /**
    * Function to find quantity of message send to user and not read yet
    * @parameter 1 integer id user
    * @return int
    */  
    public static function awaitmessages(int $id) : int {

        $messages = self::getModel('ChatBoxModel')->messageNotRead($id);
        
        $quantity = count($messages);
        
        return $quantity;
    }
    
    
    /**
     * Function recover all slot
     * @return array
     */
    public static function allSlot() : array {
        
        $allSlot = self::getModel('SlotModel')->findAll();
        
        return $allSlot;
    }
    
    
    
    /**
     * Function to update time_slot
     * @parameter 1 integer
     * @return void
     */
    public static function updateSlot(int $newTimeSlot) : void {
        
        $user = TestController::findUser($_SESSION['auth']['email']);
        
        if(empty($user['gym_foreign_key'])) {
            
            Session::setError('Vous devez choisir une salle de sport');
            
        }else {
            
            $set = " SET time_slot = :time_slot WHERE id = :id";
            $execute = [
                'time_slot' => $newTimeSlot, 
                'id' => $user['id']
            ];
            
            //  Update column time_slot in user table
            self::getModel('UserModel')->updateInfo($set, $execute);
        }
    }
     
     
     /**
     * Function to find a slot with id
     * @parameter 1 int
     * @return array
     */
    public static function findOneSlot(int $slotId) : array {
        
        $set = " WHERE id_slot = :id_slot";
        $execute = [
            "id_slot" => $slotId
        ];
             
        $result = self::getModel('SlotModel')->findUniq($set, $execute);
        
        return $result;
    }
     
     
     /**
     * Function to find contacts that goes to the same gym and same time_slot as user
     * @parametre 3 integer
     * @return array
     */
    public static function findPartener(int $gym_foreign_key, int $id, int $time_slot) : array {

        $where = "WHERE gym_foreign_key = :gym_foreign_key AND id != :id
            AND time_slot = :time_slot";
            
        $execute = [
            "gym_foreign_key" => $gym_foreign_key,
            "id" => $id,
            "time_slot" => $time_slot
        ];
            
        $allUsers = self::getModel('UserModel')->findAllWithCondition($where, $execute);
        
        return $allUsers;
        
    }
     
    
    
    /**
     * Function display templates
     * @return void
     */
    public function display() {  
        
        // find user by email
        $userP = TestController::findUser($_SESSION['auth']['email']);
        
        // Nbr of messages not read by user
        $notif = self::awaitmessages($userP['id']);
        
        // array of all slot in table slot
        $slots = self::allSlot();
        
        if(array_key_exists('gym_foreign_key',$userP) && !empty($userP['gym_foreign_key'])){
            
            // to find user gym place
            $userA = self::gymUser($userP['gym_foreign_key']);
            
            // array of contact that goes to the same gym as the user
            $allUsersByGym = self::findAllUserByGymName($userP['gym_foreign_key'], $userP['id']);
            
            if(empty($allUsersByGym)) {
                $error = 'Nous n\'avons trouvé personne dans ta salle de sport';
            }
                if(array_key_exists('time_slot', $userP) && !empty($userP['time_slot']) ) {
                
                    // array of contact that goes to the same gym and choose the same time_slot
                    $parteners = self::findPartener($userP['gym_foreign_key'], $userP['id'], $userP['time_slot']);
                    
                    // find user slot
                    $slotDay = self::findOneSlot($userP['time_slot']);
                    
                    if(empty($parteners)) {
                        $error1 = 'Nous n\'avons trouvé personne sur le créneau horaire du ' . $slotDay['day_slot'];
                    }
                }
        }
        $template = "src/views/home.phtml";
        require "src/views/layout.phtml";
    }
    
    
    


}

