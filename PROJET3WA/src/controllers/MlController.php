<?php

/**
 * Class MlController inherit from TestController class
 * Class MlController display ml page
 * 1 function
 */
class MlController{
    
    
    /**
     * Function to display template ml
     * @return void
     */
    public function display() : void
    {   
            
        $template = "src/views/ml.phtml";
        require "src/views/layout.phtml";
    }
    
    
}