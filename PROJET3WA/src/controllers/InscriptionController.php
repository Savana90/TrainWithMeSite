<?php

/**
 * Class InscriptionController inherit from TestController class
 * Serve to register a new user
 * 4 functions
 */
class InscriptionController extends TestController
{

    /**
     * function to verify all fields then register and connect user if every fields is ok in PHP
     * @return void
     */
    public static function create() : void {
        
        extract($_POST);
        
        $user_name = trim($user_name);
        $last_name = trim($last_name);
        $first_name = trim($first_name);
        $email = trim($email);
        
        if(!self::isEmpty()){
            Session::setError('Tous les champs doivent être renseignés');
        
        }else if (!isset($gender)) {
            Session::setError('Oups :( ce site est réservé aux femmes!');
            
        }else if (!self::verifyEmail($email)){
            Session::setError('Email invalide');
            
        }else if (!self::verifyUserName($user_name)){
            Session::setError('Le champs pseudo doit contenir au moins 7 caractères dont au moins un chiffre');
            
        }else if(!self::name($last_name, $first_name)){
            Session::setError('Les champs nom et prenom ne doivent contenir que des lettres');
            
        }else if(!self::patternPassword($password)){
            
            Session::setError('Le mot de passe doit contenir entre 8 et 12 caractères dont un chiffre, 
            une majuscule, une minuscule et un caractère spécial');
        
        }else if($password !== $confirm_password){
            
            Session::setError('Les champs \'mot de passe\' et \'confirmer le mot de passe\' ne correspondent pas');
        
        }else{
            
            $user = self::findUser($email); 
            
            if (!empty($user)){
                Session::setError('Ce compte existe déja veuillez vous connecter');
                
            }else{
                $_SESSION['error'] = [];
                
                // register new user
                self::register($first_name, $last_name, $user_name, $email, $password);
                
                // create new $_SESSION
                Session::init($user_name, $email);
            }
        }
    }
    
    
    /**
     * function to register and connect user
     * @parameter 5 string
     * @return void
     */
    public static function register($first_name, $last_name, $user_name, $email, $password) : void {

        $value = 
            "(first_name, last_name, user_name, email, password, created_at, last_connection_time) 
            VALUES (:first_name, :last_name, :user_name, :email, :password, NOW(), NOW())";
        
        $execute = [
            ":first_name" => $first_name, 
            ":last_name" => $last_name, 
            ":user_name" => $user_name,
            ":email" => $email, 
            ":password" => password_hash($password, PASSWORD_DEFAULT)
        ];
        
        // Insert new user
        self::getModel('UserModel')->insert($value, $execute); 
        
        // search for user in table User
        $newEntrie = self::findUser($email); 
        
        // Add user in table session_user means is connected
        self::getModel('SessionUserModel')->insertSessionUser($newEntrie['id'], session_id());
    }
    
    
    /**
     * function to verify all fields in js/ajax connection
     * @parameter 6 string
     * @return string
     */
    public static function fieldCheck($first_name, $last_name, $user_name, $email, $password, $confirm_password) : string {
        
        $user = self::findUser($email);
        
        $message;
        
        if(!self::isEmpty()){
            
            $message = 'Tous les champs doivent être renseignés' ; 
            return $message;
            
        }else if (!self::verifyEmail($email)){
            
            $message = 'Email invalide' ; 
            return $message;
            
        }else if (!self::verifyUserName($user_name)){
            
            $message = 'Le champs pseudo doit contenir au moins 7 caractères et au moins un chiffre' ; 
            return $message;
            
        }else if(!self::name($last_name, $first_name)){
            
            $message = 'Les champs nom et prenom ne doivent contenir que des lettres' ; 
            return $message;
            
        }else if(!self::patternPassword($password)){
            
            $message = 'Le mot de passe doit contenir entre 8 et 12 caractères dont un chiffre, 
            une majuscule, une minuscule, et un caractère spécial' ; 
            return $message;
        
        }else if($password !== $confirm_password){
            
            $message = 'Les champs \'mot de passe\' et \'mot de passe à confirmer\' 
            ne match pas' ; 
            return $message;
        
        }else if(!empty($user)) {
            
            $message = 'L\'email saisi existe déja veuillez vous connecter' ; 
            return $message;
            
        }else{
            $message = 'ok';
            return $message;
        }
    }
    
    /**
     * Function display templates
     * @return void
     */
    public function display() 
    {
        $template = "src/views/inscription.phtml";
        require "src/views/layout.phtml";
    }
    
    
}

