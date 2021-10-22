<?php

/**
 * Class NewAdminController inherit from TestController class
 * Serve to operate the newAdmin page 
 * 2 functions
 * 
 */
class NewAdminController extends TestController{
    
    
    /**
     * Function to insert a new admin 
     * @return void
     */
    public static function createAdmin() : void {
        
        extract($_POST);
        
        if(!self::isEmpty()){
            Session::setError('Tous les champs doivent être renseignés');
            
        }else if (!self::verifyEmail($email)){
            Session::setError('Email invalide');
            
        }else if (!self::verifyUserName($user_name)){
            Session::setError('Le champs pseudo doit contenir au moins 7 caractères dont au moins un chiffre');
            
        }else if(!self::name($last_name, $first_name)){
            Session::setError('Les champs nom et prenom ne doivent contenir que des lettres');
            
        }else if(!self::patternPassword($password)){
            
            Session::setError('Le mot de passe doit contenir entre 8 et 12 caractères dont un chiffre, 
            une majuscule, une minuscule et un caractère spécial');
        
        }else{
            // search if email exist
            $user = self::findUser($email); 
            
            if (!empty($user)){
                Session::setError('Ce compte existe déja');
                
            }else{
                
                $_SESSION['error'] = [];
                
                $value = 
                    "(first_name, last_name, user_name, admin, email, password, created_at) 
                    VALUES (:first_name, :last_name, :user_name, :admin, :email, :password, now())";
                
                $execute = [
                    ":first_name" => $first_name, 
                    ":last_name" => $last_name, 
                    ":user_name" => $user_name,
                    ":admin" => $admin,
                    ":email" => $email, 
                    ":password" => password_hash($password, PASSWORD_DEFAULT)
                ];
                
                self::getModel('UserModel')->insert($value, $execute);
                session::setSucceed ('Utilisateur créé avec succès !');
            }
        }
    }
    
    
    /**
     * Function display templates
     * @return void
     */
    public function display() : void
    {
        $template = "src/views/newAdmin.phtml";
        require "src/views/layout.phtml";
    }
    
}