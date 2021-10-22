<?php

require_once 'GenericModel.php';
require_once 'DatabaseModel.php';

/**
 * Class SlotModel inherit from GenericModel 
 *
 * Manage SQL requestes for the Slot table
 * 
 * 1 function
 */
class SlotModel extends GenericModel{
    
    protected string $table;
    
    public function __construct(){
        parent::__construct();
        $this->table = 'slot';
    }
    
}