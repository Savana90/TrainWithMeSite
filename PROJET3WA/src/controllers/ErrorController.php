<?php

/**
 * Class ErrorController display error page
 * 1 function
 */
class ErrorController{
    
    
    
    
    /**
     * Function to display template error
     * @return void
     */
    public function display() : void
    {   
            
        $template = "src/views/error.phtml";
        require "src/views/layout.phtml";
    }
    
    
}