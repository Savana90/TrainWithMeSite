<?php
spl_autoload_register(function ($class) {
    if (stristr($class, "Model") !== FALSE){
        include '../models/'.$class.'.php';
    }else if (stristr($class, "Controller") !== FALSE) {
        include '../controllers/'.$class.'.php';
    }else{
        include '../services/'. $class. '.php';
    }
});

Session::start();

// processing on the register form and the connexion form
if (isset($_POST) && !empty($_POST)){
    switch (($_POST['action'])){
        
        //Inscription
        case 'insert':
            extract($_POST);
            
            $message = InscriptionController::fieldCheck($first_name, $last_name, 
            $user_name, $email, $password, $confirm_password);
            
            if($message == 'ok'){
                
                InscriptionController::register($first_name, $last_name, $user_name, $email, $password);
                
                Session::init($user_name, $email, 0, true);
                echo $message;
            }else{
                echo $message;
            }
            
            break;
        
        // Connexion
        case 'connexion' :
            
            $result = ConnexionController::loginJs();
            
            if(is_string($result)){
                
                if($result == 'erreur'){
                    
                    echo $result;
                    
                }else if($result == 'connecté') {
                    
                    echo $result;
                }
                
            }
            if(is_array($result)){
                
                if(array_key_exists('admin',$result) && $result['admin'] == 0){
                    Session::init($result['user_name'], $result['email'], $result['admin'], true);
                    echo 'home';
                }else{
                    Session::init($result['user_name'], $result['email'], $result['admin'], true);
                    echo 'admin';
                }
                
            }
            break;
        
        // ChatBox (message page)
        case 'chatBox' :
            
            $trimMessage = trim($_POST['message']);
            
            if(!empty($trimMessage)){
            
                MessageController::insertMessage($trimMessage, $_POST['receiver']);
                
                echo 'ok';
            } else {
                echo 'vide';
            }
            
            break;
            
    }
        
}

if (isset($_GET) && !empty($_GET)) {
    
    // Check if user already register in database
    if(array_key_exists('searchEmail',$_GET) && !empty($_GET['searchEmail'])) {
    
        $user = TestController::findUser($_GET['searchEmail']);
       
        if (!empty($user)) {
            echo true;
        }else{
            echo false;
        }
    
    // search for users (card page)
    }else if(array_key_exists('searchUser', $_GET) && !empty($_GET['searchUser'])) {
        
        // result 
        $users = CardController::search($_GET['searchUser']);
        
        echo json_encode($users);
    
    // get messages
    }else if( array_key_exists('id', $_GET) && !empty($_GET['id']) ) {

        $user = TestController::findUser($_SESSION['auth']['email']);
        
        // get all messages
        $messages = MessageController::findAllMessages($_GET['id'], $user['id'] );
        
        // to delete message if count of all messages exchange with one contact is over 20 messages
        MessageController::deleteMessages($messages, $user['id'], $_GET['id'] );
        
        // update fields status in table chatbox
        MessageController::updateStatus($user['id'], $_GET['id']);
            
        echo json_encode($messages);
    }
 
 
 
}
            