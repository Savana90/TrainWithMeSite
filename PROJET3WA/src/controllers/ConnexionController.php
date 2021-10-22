<?php
/**
 * Class ConnexionController inherit from TestController class
 * Serve to operate the connexion page so user can connect to is member profil 
 * 6 functions
 */
 
class ConnexionController extends TestController
{
    
    
    /**
     * Function to update the last connection date in table user
     * @parameter 1 int
     * @return void
     */
    public static function UpdateDateConnection(int $userId) : void{
        
        $setUpdateDate = " SET last_connection_time = NOW() WHERE id = :id" ;
                        
        $executeUpdateDate = [
            "id" => $userId
            ];
            
        self::getModel('UserModel')->updateInfo($setUpdateDate, $executeUpdateDate);
    }
    
    
    /**
     * Function to login user
     * @return void
     */
    public static function login() : void {
        
        extract($_POST);
        
        if(!self::isEmpty()){
            Session::setError('Tous les champs doivent être renseignés');
            
        }else if (!self::verifyEmail($email)){
            Session::setError('Email invalide');
            
        }else{
            // search for user
            $user = self::findUser($email); 
            
            if (empty($user)){
                Session::setError('Cet email n\'existe pas merci de saisir 
                un email valide ou de vous inscrire' );
                
            }else{
                // verify password
                if(!password_verify($password, $user['password'])){
                    Session::setError('Mot de passe incorrect');
                    
                }else{
                    
                    $setA = " WHERE user_foreign_key = :user_foreign_key";
                    $executeA = [
                        ":user_foreign_key" => $user['id']
                        ];
                    
                    // search if id user exist in session table meaning that the user is already connected
                    $findSession = self::getModel('SessionUserModel')->findUniq($setA, $executeA);
                    
                    if(!empty($findSession)){
                        
                        // user id is in session_user table, now we want to know if user is trying to reconnect with the same session_id
                        if($findSession['php_session_id'] !== session_id()){
                            
                            // now we want to know if user as been active in the last 20min
                            $newSession = self::getModel('SessionUserModel')->findSessionActivity($user['id']);
                            
                            if(empty($newSession)){
                                
                                // the user has not been active on the site for over 20min
                                // so we can reconnect the user and update the session_id
                                $setUpdate = " 
                                    SET php_session_id = :php_session_id  
                                        
                                    WHERE user_foreign_key = :user_foreign_key";
                                $executeUpdate = [
                                    "user_foreign_key" => $user['id'],
                                    "php_session_id" => session_id()
                                    ];
                                    
                                // update session_id in session_user table
                                self::getModel('SessionUserModel')->updateInfo($setUpdate, $executeUpdate);
                                
                                // to connect user
                                self::connectUser($user['id'], $user['email'], $user['user_name'], $user['admin']);
                               
                            }else{
                                // the user has been active on the site for the last 20min so no reconnection possible
                                Session::setError('Une session avec vos données de connection est déjà en cour, 
                                veuillez vous déconnectez de celle ci avant de vous reconnecter');
                            }
                            
                        }else{
                            
                            // update timestamp in session_user table
                            self::getModel('SessionUserModel')->updateSession($user['id']);
                            
                            // to connect user
                            self::connectUser($user['id'], $user['email'], $user['user_name'], $user['admin']);
                        }
                    }else{
                        
                        // insert user in session_user table 
                        self::getModel('SessionUserModel')->insertSessionUser($user['id'], session_id());
                        
                        // to connect user
                        self::connectUser($user['id'], $user['email'], $user['user_name'], $user['admin']);
                    }
                    
                }
            }
        }
    }
    
    
    /**
     * Function to connect user
     * @parameter 2 int 2 string
     * @return void
     */
    public static function connectUser(int $id, string $email, string $user_name, int $admin) : void {
        
        // update last_connection_time in user table
        self::updateDateConnection($id);
    
        
        if($admin == 0){
            $_SESSION['error'] = [];
            Session::init($user_name, $email);
    
        }else{
            $_SESSION['error'] = [];
            Session::init($user_name, $email, $admin);
        }
    }
    
    /**
     * Function to create session et connect user
     * @parameter 2 int 2 string
     * @return array
     */
    public static function infoUser(int $id, string $user_name, string $email, int $admin) : array {
        
        // update last_connection_time in user table
        self::updateDateConnection($id);
        
        // array with info needed for $_SESSION['auth']
        $userSession = [
            'user_name' => $user_name,
            'email' => $email,
            'admin' => $admin
        ];
        
        return $userSession;
    }
    
    
    /**
     * Function to verify user connection
     * @return array|string
     */
    public static function loginJs() {
        extract($_POST);
        
        $error = 'erreur';
            
        if(!self::isEmpty()){
            return $error;
            
        }else if (!self::verifyEmail($email)){
            return $error;
            
        }else{
            
            // search if user exist
            $user = self::findUser($email); 
            
            if (empty($user)){
                return $error; 
                
            }else{
                
                // verify password user
                if(!password_verify($password, $user['password'])){
                    return $error;
                    
                }else{
                    
                    $setA = " WHERE user_foreign_key = :user_foreign_key";
                    $executeA = [
                        ":user_foreign_key" => $user['id']
                    ];
                    
                    // search if id user exist in session table meaning that the user is already connected
                    $findSession = self::getModel('SessionUserModel')->findUniq($setA, $executeA);
                    
                   
                    if(!empty($findSession)){
                       
                        // user id is in session table, now we want to know if user is trying to reconnect with the same session_id
                        if($findSession['php_session_id'] !== session_id()){
                            
                            // now we want to know if user as been active in the last 20min
                            $newSession = self::getModel('SessionUserModel')->findSessionActivity($user['id']);
                            
                            if(empty($newSession)){
                                
                                // the user has not been active on the site for over 20min
                                // so we can reconnect the user and update the session_id
                                $setUpdate = " 
                                    SET php_session_id = :php_session_id
                                    WHERE user_foreign_key = :user_foreign_key";
                                    
                                $executeUpdate = [
                                    "user_foreign_key" => $user['id'],
                                    "php_session_id" => session_id()
                                ];
                                
                                // update session_id in session_user table
                                self::getModel('SessionUserModel')->updateInfo($setUpdate, $executeUpdate);
                                
                                $userSession = self::infoUser($user['id'], $user['user_name'], $user['email'], $user['admin']);
                                
                                return $userSession;
                                
                            }else{
                                // the user has been active on the site for the last 20min so no reconnection possible
                                $error = 'connecté';
                                return $error;
                            }
                        }else{
                            
                            // update timestamp in session_user table
                            self::getModel('SessionUserModel')->updateSession($user['id']);
                            
                            $userSession = self::infoUser($user['id'], $user['user_name'], $user['email'], $user['admin']);
                                
                            return $userSession;
                        }
                    }else{
                        
                        // insert user in session_user table
                        self::getModel('SessionUserModel')->insertSessionUser($user['id'], session_id());
                        
                        $userSession = self::infoUser($user['id'], $user['user_name'], $user['email'], $user['admin']);
                                
                        return $userSession;
                    }
                    
                }
            }
        }
    }
    
    /**
     * Function display templates
     * @return void
     */
    public function display() 
    {
        $template = "src/views/connexion.phtml";
        require "src/views/layout.phtml";
    } 
    
    

}

