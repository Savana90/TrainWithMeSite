<?php
/**
 * Class TestController
 * Functions to controle fields and instantiate models
 * Abstract class who only serve for heritage
 * 8 functions
 */
 
abstract class TestController 
{
    
    /**
     * Function serve to instantiate class model
     * @parameter one string => class name
     * @return instance
     */
    public static function getModel($model) {
        
        $instance = new $model;
        return $instance;
    }
    
    
    /**
     * Function verify if field is empty
     * @return bool
     */
    public static function isEmpty() : bool {
        $isEmpty = in_array('', $_POST);
        
        if($isEmpty){
            return false;
        }else{
            return true;
        }
    }
    
    
    /**
     * Function to verify mail format
     * @parameter string
     * @return bool
     * 
     */
    public static function verifyEmail(string $email) : bool{
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }else{
            return true;
        }
    }
    
    
    /**
     * Function to verify that the username respect a certain pattern
     * @parameter string
     * @return bool
     */
    public static function verifyUserName(string $user_name) : bool {
        
        $number = preg_match('@[0-9]@', $user_name); 
        $specialChars = preg_match('@[^\w]@', $user_name);
        
        if(strlen($user_name) < 7 || !$number || $specialChars) { 
            return false;
        }else{
            return true;
        }
    }
    
    
    /**
     * Function to verify lastname and firstname who have to respect a certain pattern
     * @parameter two string
     * @return bool
     */
    public static function name(string $last_name, string $first_name) : bool {
    
        if(!ctype_alpha($last_name) || !ctype_alpha($first_name)){
            return false;
        }else{
            return true;
        }
    }
    
    
    /**
     * Function verify password respect a certain pattern
     * @parameter string
     * @return bool
     */ 
    public static function patternPassword($password) : bool {
        
        $number = preg_match('@[0-9]@', $password);
        $uppercase = preg_match('@[A-Z]@', $password);
        $lowercase = preg_match('@[a-z]@', $password);
        $specialChars = preg_match('@[^\w^<>]@', $password);
        
        if(strlen($password) < 8 || !$number || !$uppercase || !$lowercase || !$specialChars || strlen($password) > 12 ) {
            return false;
        } else {
            return true;
        }
    }
    
    
    /**
     * Function verify password in DB match the password in field
     * @paramèter deux string
     * @return bool
     */
    public static function verifyPassword(string $password, string $user) : bool {
        
        if(password_verify($password, $user)) {
            return true;
        }else{
            
        }   return false;
    }
    
    
    /**
     * Function to recover all info on a user with is email
     * @paramèter string
     * @return array
     */
    public static function findUser(string $email) : array {

        $set = " WHERE email = :email";
        $execute = [
            ":email" => $email
        ];
            
        $user = self::getModel('UserModel')->findUniq($set, $execute);
        
        return $user;
    }
    
    
    
    
     
     
 }