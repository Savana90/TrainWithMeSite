<?php

/**
 * Represent superglogal session php ($_SESSION)
 * 
 * 7 functions
 */
class Session {
    
    /**
     * Function to start a new session so you can use it
     *@return void
     */
    public static function start(): void {
        if (session_status() === PHP_SESSION_NONE)
        {
            session_start();
        }
    }
    
    
    /**
     * Function to fill the $_Session with user info
     * @parameter 2 string 1 int|null 1 bool|null
     * The bool paramater is to know if the connection is exclusively in php or js 
     * because de redirection want happen the same way, $js will be true only if javascript is active
     *@return void
     */
    public static function init(string $user_name, string $email, ?int $admin = 0, ?bool $js = false): void {
        $_SESSION['auth'] = [
            'user_name' => $user_name,
            'email' => $email,
            'admin' => $admin,
            'js' => $js
        ];
        
        if(!$js){
            if($admin == 1){
                ApplicationController::redirect('admin');
            }else{
                ApplicationController::redirect('home');
            }
        }
        
        
    }
    
    
    /**
     * Function to logout the user and destroy the active SESSION
     *@return void
     */
    public static function logout(): void {
        
        if (isset($_SESSION['auth'])){
            
            $user = TestController::findUser($_SESSION['auth']['email']);
            
            // Delete user_id in session_user
            TestController::getModel('SessionUserModel')->deleteSession($user['id']);

            // Destroy session
            $_SESSION['auth'] = [];
            unset($_SESSION['auth']);
            session_destroy();
            ApplicationController::redirect();
            
        }else{
            ApplicationController::redirect();

        }
    }
    
    
    /**
     * Function to change variable $_SESSION['error']
     * @parameter one string 
     * @return void
     */
    public static function setError(string $error = null): void {
        $_SESSION['error'] = $error;
    }
    
    
    
    /**
     * Function to get variable $_SESSION['error']
     * @return void
     */
    public static function getError(): ?string {
        return isset($_SESSION['error']) ? $_SESSION['error'] : null;
    }
    
    
    
    /**
     * Function to change variable $_SESSION['succeed']
     * @parameter one string 
     * @return void
     */
    public static function setSucceed(string $succeed = null): void {
        $_SESSION['succeed'] = $succeed;
    }
    
    
    
    /**
     * Function to get variable $_SESSION['succeed']
     * @return void
     */
    public static function getSucceed(): ?string {
        return isset($_SESSION['succeed']) ? $_SESSION['succeed'] : null;
    }
    
    
    


}