<?php

/**
 * Class CardController inherit from TestController class
 * Serve to operate the view page card
 * 4 functions
 */
class CardController extends TestController{
    
    
    
    /**
     * Function find a User
     * @parameter 1 integer
     * @return array
     */
    public static function findOne(int $id) : array {
        
        $set = " WHERE id = :id";
        $execute = [
            ':id' => $id
            ];
            
        $user = self::getModel('UserModel')->findUniqUser($set, $execute);
        
        return $user;
    }
    
    /**
     * Function to find gym club name
     * @parameter 1 integer
     * @return array
     */
    public static function gymClub(int $id) : array {
        
        $set = " WHERE id = :id";
        $execute = [
            "id" => $id
            ];
            
        $userGym = self::getModel('GymModel')->findUniq($set, $execute);
        
        return $userGym;
        
    }
    
    /**
     * Function search for user with a name
     * @parameter one string
     * @return array
     */
    public static function search(string $search) : array {

        if($search == ''){
            Session::setError('Champ vide');
            return [];
        }else{
            $user = self::getModel('UserModel')->searchUsers($search);
            return $user;
        }
    }
    
    
    /**
     * Function display templates
     * @return void
     */
    public function display() : void
    {
        
        if(isset($_GET['search']) && array_key_exists('search',$_POST) && !empty($_GET['search'])){
            // array of user find
            $searchUser = self::search($_POST['search']);
        }
        
        if(isset($_GET['profil']) && array_key_exists('profil',$_GET) && && !empty($_GET['profil'])){
            
            $oneUser = self::findOne($_GET['profil']);
            
            if(!empty($oneUser)){
        
                if(!empty($oneUser['gym_foreign_key'])){
                    
                    $gymClub = self::gymClub($oneUser['gym_foreign_key']); 
                }
            }
        }

        $template = "src/views/card.phtml";
        require "src/views/layout.phtml";
        
    }
    
}