<?php

require_once 'GenericModel.php';
require_once 'DatabaseModel.php';

/**
 * Class GymModel inherit from GenericModel 
 *
 * Manage SQL requestes for the Gym table
 * 
 */
class GymModel extends GenericModel{
    
    protected string $table;
    
    public function __construct(){
        parent::__construct();
        $this->table = 'gym';
    }
    
}