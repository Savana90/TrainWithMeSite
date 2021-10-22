<?php

/**
 * Class ContactMsgController inherit from TestController class
 * Class ContactMsgController display contactMsg page
 * 4 functions
 */
class ContactMsgController extends TestController{
    
    
    
    /**
     * Function request all records in contact_message table
     * @return array
     */
    public static function getContactMessage() : array {
        
        $msgs = self::getModel('ContactMessageModel')->findAll();
        
        return $msgs;
    }
    
    
    
    /**
     * Function to delete record(s) in contact_message table
     * @parameter 1 int
     * @return void
     */
    public static function deleteContactMessage(int $id) : void {
        
        $set= " WHERE id_contact_message = :id_contact_message";
        $execute = [
            ':id_contact_message' => $id
        ];
            
        self::getModel('ContactMessageModel')->deleteUser($set, $execute);
        session::setSucceed ('Message supprimé !');
    }
    
    
    
    /**
     * Function to update field msg_process in contact_message table
     * @return void
     */
    public static function updateMsgProcess() : void { 
        
        $set = ' SET msg_process = :msg_process';
        $execute = [
            'msg_process' => 1
        ];
            
        self::getModel('ContactMessageModel')->updateInfo($set, $execute);
    }
    
   
    
    /**
     * Function to display template contactMsg page
     * @return void
     */
    public function display() : void
    {   
        $msgs = self::getContactMessage(); 
        
        // to update statut message send by contact to admin
        self::updateMsgProcess();
            
        $template = "src/views/contactMsg.phtml";
        require "src/views/layout.phtml";
    }
    
    
}