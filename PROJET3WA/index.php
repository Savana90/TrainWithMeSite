<?php
spl_autoload_register(function ($class) {
    if (stristr($class, "Model") !== FALSE){
        include 'src/models/'.$class.'.php';
    }else if (stristr($class, "Controller") !== FALSE) {
        include 'src/controllers/'.$class.'.php';
    }else{
        include 'src/services/'. $class. '.php';
    }
});


// session start
Session::start();
// call to function to disconnect user if inative
disconnect();
// call to function to track user activity on the site
updateTimestamp();


// display templates
if (!isset($_GET['template'])) {
    include_once 'src/views/layout.phtml';

}else{

    ApplicationController::process();
}
// handling forms of the site with a switch using input hidden
if (isset($_POST) && !empty($_POST)) {
    switch (($_POST['action'])) {
        
        //Inscription page
        case 'insert':
            
            // insert new user
            InscriptionController::create();

            if(!empty($_SESSION['error'])){
                
                ApplicationController::redirect('inscription');
            }
            break;
            
         // Connexion page
        case 'connexion':
            
            // to login
            ConnexionController::login();
            
            if(!empty($_SESSION['error'])){
        
                ApplicationController::redirect('connexion');
            }
            break;
            
        // message page 
        case 'message':

            foreach($_POST as $key => $value){
               
                if(is_numeric($key)){
                    // insert new message
                    MessageController::insertMessage($value, intval($key)); 
                    ApplicationController::redirect('message&user='. $key);
                }
            }
            break;
            
        // Profil page modify de user identity
        case 'modify-name':
            
            ProfilController::update();
            
            if(!empty($_SESSION['error'])){
                ApplicationController::redirect('profil');
            }
            break;
        
        // Profil page modify password
        case 'update-password':
            
            ProfilController::updatePassword();
            
            if(!empty($_SESSION['error'])){
                ApplicationController::redirect('profil');
            }
            break;
            
        // Profil page modify gym club
        case 'modify-club' :
            
            // to update the gym place
            ProfilController::updateGym($_POST['gym_club']);
            
            ApplicationController::redirect('profil');
            break;
            
        // Profil page modify picture
        case 'modify-pic' :
            
            // update profil picture
            ProfilController::updatePicture();
            break;
            
        // admin page update status or erase user
        case 'admin':
            foreach($_POST as $key => $value){
                
                if(is_numeric($key)){
                    AdminController::updateAdmin(intval($value), intval($key)); 
                }
                if($value == 'delete'){
        
                    // delete user and all messages the user send and received
                    AdminController::eraseUser($key);
                }
            }
            ApplicationController::redirect('admin');
            break;
            
            
        // newAdmin page to create a new admin
        case 'new-user' :
            
            NewAdminController::createAdmin();
            
            if(!empty($_SESSION['error'])){
                ApplicationController::redirect('newAdmin');
            }else{
                ApplicationController::redirect('admin');
            }
            break;
        
        // home page to form to find contact who goes to the same gym 
        // and choose the same time_slot
        case 'home' :
            
            HomeController::updateSlot($_POST['time_slot']);
            ApplicationController::redirect('home');
            break;
        
        // form from contact page 
        case 'contact' :
        
            ContactFormController::insertMsg();
            if(!empty($_SESSION['error']) ) {
                ApplicationController::redirect('contactForm');
            }
            break;
        
        // contact message page 
        case 'deleteMsgContact' :
            
            foreach($_POST as $key => $value) {
        
                if(is_numeric($key) ) {
                    ContactMsgController::deleteContactMessage(intval($key));
                }
            }
              ApplicationController::redirect('contactMsg');
  
            break;
            
       
    }
}


/**
 * Function to disconnect automatically the user after 20minutes of inactivity on the site
 */    
function disconnect(){

    if(isset($_SESSION['auth']) && array_key_exists('auth', $_SESSION)){
        
        $user = TestController::findUser($_SESSION['auth']['email']);
        
        $setS = " WHERE php_session_id = :php_session_id";
                
        $executeS = [
            "php_session_id" => session_id(),
        ];
        
        $sessionExit = TestController::getModel('SessionUserModel')->findUniq($setS, $executeS);

            if(empty($sessionExit)){
                
            $_SESSION['auth'] = [];
            
            unset($_SESSION['auth']);
            session_destroy();
            
            // redirect user
            ApplicationController::redirect();
            
            }else{
                
                // call to function to analyse user activity on the site and know if user is active
                $userActivity = TestController::getModel('SessionUserModel')->findSessionActivity($user['id']);

                if(empty($userActivity)){ 
                    
                    // user is inative for over 20min on the site
                    
                    // delete user of the table session_user
                    TestController::getModel('SessionUserModel')->deleteSession($user['id']);

                    // disconnect user => delete and destroy $_SESSION['auth']
                    $_SESSION['auth'] = [];
                    unset($_SESSION['auth']);
                    session_destroy();
                    
                    // redirect user 
                    ApplicationController::redirect();
                }
            }
    }
}


/**
 * Function to track and update user activity on the site 
 * 
 */
function updateTimestamp(){

    if(array_key_exists('auth', $_SESSION)){
        $user = TestController::findUser($_SESSION['auth']['email']);
        
        // update last mouvement on the site 
        TestController::getModel('SessionUserModel')->updateSession($user['id']);
    }
    
}



