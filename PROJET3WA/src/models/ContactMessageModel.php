<?php

require_once 'GenericModel.php';
require_once 'DatabaseModel.php';
/**
 * Class ContactMessageModel inherit from GenericModel
 * 
 * Manage SQL requestes for the contact_message table
 * 
 * 1 function
 */
class ContactMessageModel extends GenericModel{
    
    protected string $table;
    
    public function __construct(){
        parent::__construct();
        $this->table = 'contact_message';
    }
    
   
    
    
} 