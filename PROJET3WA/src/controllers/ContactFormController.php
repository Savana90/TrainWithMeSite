<?php

/**
 * Class ContactFormController inherit from TestController class
 * Class ContactFormController display contactForm page
 * 2 functions
 */
class ContactFormController extends TestController{
    
    
    /**
     * Function to insert contact message in contact_message DB
     * @return void
     */
    public static function insertMsg() : void { 
        
        extract($_POST);
        $nameContactUs = trim($nameContactUs);
        $emailContactUs = trim($emailContactUs);
        $msgContact = trim($msgContact);
        
        if(!self::isEmpty() ){
            Session::setError('Tous les champs doivent être renseignés');
            
        } else if (!self::verifyEmail($emailContactUs)) {
            Session::setError('Email invalide');
      
        } else if( !ctype_alpha($nameContactUs) ) {
            Session::setError('Pas de caractères spéciaux ni de chiffre dans le champs nom');
            
        } else {
        
            $user = self::findUser($emailContactUs);
            
            if(!empty($user) ) {
            
                $value = '(name, email, msg, id_user) VALUES (:name, :email, :msg, :id_user)';
                
                $execute = [
                    'name' => $nameContactUs,
                    'email' => $emailContactUs,
                    'msg' => strip_tags($msgContact),
                    'id_user' => $user['id']
                ];
                     
            } else {
                $value = '(name, email, msg) VALUES (:name, :email, :msg)';
                
                $execute = [
                    'name' => $nameContactUs,
                    'email' => $emailContactUs,
                    'msg' => strip_tags($msgContact)
                ];
            }
            self::getModel('ContactMessageModel')->insert($value, $execute);
            session::setSucceed ('Message envoyé !');
            ApplicationController::redirect('contactForm');
        }
        
    }
    
    
    
    /**
     * Function to display template contactForm page
     * @return void
     */
    public function display() : void
    {   
        if(isset($_SESSION['auth']) && !empty($_SESSION['auth']) ) {
            
            $user = TestController::findUser($_SESSION['auth']['email']);
        }
            
        $template = "src/views/contactForm.phtml";
        require "src/views/layout.phtml";
    }
    
    
}