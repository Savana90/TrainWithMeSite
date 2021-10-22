<?php
/**
 * Class ApplicationController
 * Function to display all templates of the site and redirect
 * 3 functions
 */
class ApplicationController
{
    
    
    /**
     * Function to instentiate controller
     * @parameter string
     * @return void
     */
    public static function instentiateController(string $template) : void
    {
        $Controller = $template .'Controller';
        $inscriptionController = new $Controller;
        $inscriptionController->display();
    }
    
    
    
    /**
     * Function to redirect user
     * @parameter string | null
     * @return void
     */
    public static function redirect(?string $controller = "") : void
    {
        if ($controller) {
            header('Location: index.php?template=' . $controller);
            exit;
        } else {
            header('Location: index.php');
            exit;
        }
    }
    
 
    
    /**
     * Function to display and instentiate all Controllers
     * @return void
     */
    public static function process() : void
    
    {   
        if(!isset($_SESSION['auth'])) {
            
            $template = ucfirst($_GET['template']);
            
            if (!isset($_GET['template'])) {
                
                self::redirect();
                
            }else if(isset($_GET['template']) && !empty($_GET['template'])) {
                
                if ($template == 'Inscription') {
                   
                    self::instentiateController($template);
                    
                }else if ($template == 'Connexion') {
                   
                    self::instentiateController($template);
                    
                }else if ($template == 'Ml' ) {
                    
                    self::instentiateController($template);
                    
                }else if ($template == 'ContactForm') {
                    
                    self::instentiateController($template);
                    
                }else {
                    
                    self::redirect();
                    
                }
            }
            $_SESSION['error'] = [];
            $_SESSION['succeed'] = [];
        }
                
        if (isset($_SESSION['auth']) && !empty($_SESSION['auth'])) {
            
            $template = ucfirst($_GET['template']);
                    
            if($template == 'Logout') {
            
                Session::logout();
            }
        
        // controle of the user status to allow him to certain page
        // here he's a user
            if ($_SESSION['auth']['admin'] == 0) {
                
                if ($template == 'Home') {
                   
                    self::instentiateController($template);

                }else if($template == 'Message'){
                   
                    self::instentiateController($template);
                    
                }else if($template == 'Profil') {
                    
                    self::instentiateController($template);
                    
                }else if ($template == 'Ml' ) {
                    
                    self::instentiateController($template);
                    
                }else if ($template == 'ContactForm') {
                    
                    self::instentiateController($template);
                    
                }else{
                    
                    $template = 'Error';
                    
                    self::instentiateController($template);
                    
                }
                
            // status admin
            }else{
                
                if($template == 'Admin'){
                   
                    self::instentiateController($template);
                    
                }else if($template == 'Card'){
                 
                    self::instentiateController($template);
                    
                }else if($template == 'NewAdmin'){
                   
                    self::instentiateController($template);
                    
                }else if($template == 'Profil'){
                    
                    self::instentiateController($template);
                    
                }else if ($template == 'ContactMsg') {
                    
                    self::instentiateController($template);
                    
                }else {
                    $template = 'Error';
                    
                    self::instentiateController($template);
                }
            }
            
            $_SESSION['error'] = [];
            $_SESSION['succeed'] = [];
            
        }
    }
                

    
    
}



