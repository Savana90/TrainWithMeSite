<?php

/**
 * Class ProfilController inherit from TestController class
 * Class to Update all informations on user on the view profil page
 * 6 functions
 */

class ProfilController extends TestController
{
    
    /**
     * Function to update user => user_name/last_name/first_name/email
     * @return void
     */
    public static function update() : void{
        
        extract($_POST);
        
        if(!self::isEmpty()){
            Session::setError('Tous les champs doivent être renseignés');
            
        }else if (!self::verifyEmail($email)){
            Session::setError('Email invalide');
            
        }else if (!self::verifyUserName($user_name)){
            Session::setError('Le champs pseudo doit contenir au moins 7 caractères');
            
        }else if(!self::name($last_name, $first_name)){
            Session::setError('Les champs nom et prenom ne doivent contenir que des lettres');
            
        }else{
            $_SESSION['error'] = [];
            
            $set = " SET first_name = :first_name, last_name = :last_name, user_name = :user_name, email = :email_update 
                WHERE email = :email";
                    
            $execute = [
                ":first_name" => $first_name,
                ":last_name" => $last_name,
                ":user_name" => $user_name,
                ":email_update" => $email,
                ":email" => $_SESSION['auth']['email']
            ];
                
            self::getModel('UserModel')->updateInfo($set, $execute); 
            
            session::setSucceed ('Modification enregistrée !');
            
            $_SESSION['auth']['email'] = $email;
            $_SESSION['auth']['user_name'] = $user_name;
            
            ApplicationController::redirect('profil');
        }
        
    }
    
    
     /**
     * Function update user password
     * @return void
     */
    public static function updatePassword() : void{
        
        extract($_POST);
        
        // search for user
        $user = self::findUser($_SESSION['auth']['email']);
        
        if(!self::isEmpty()){
            
            Session::setError('Pour la modification de votre mot de passe
            tous les champs doivent être renseignés !');
            
        }else if(!password_verify($password, $user['password'])){
            
            Session::setError('Ancien mot de passe incorrect');
            
        }else if(!self::patternPassword($new_password)){
            
            Session::setError('Le mot de passe doit contenir au moins 8 caractères dont un chiffre, 
            une majuscule, une minuscule, et un caractère spécial');
            
        }else if($password == $new_password) {
            
            Session::setError('Le nouveau mot de passe est identique à l\'ancien merci de saisir un nouveau mot de passe');
            
        }else if($new_password !== $confirm_password){
            Session::setError('Le champs confirmer ne correspond pas');
            
        }else{
            
            $_SESSION['error'] = [];
            
            $set = " SET password = :password WHERE email = :email";
            $execute = [
                ':password' => password_hash($new_password, PASSWORD_DEFAULT),
                ':email' => $_SESSION['auth']['email']
                ];
                
            self::getModel('UserModel')->updateInfo($set, $execute);
            
            Session::setSucceed ('Mot de passe modifié !');
            
            ApplicationController::redirect('profil'); 
        }
    }
    
    
    /**
     * Function recover all records in gym table
     * @return array
     */
    public static function allGym() : array {
        
        $allGym = self::getModel('GymModel')->findAll(' gym_name');
        
        return $allGym;
    }
    
    
    /**
     * Function update user gym place
     * @parametre 1 integer
     * @return void
     */
    public static function updateGym(int $gymForeignKey) : void{
        
        $user = self::findUser($_SESSION['auth']['email']);
        
        $set = " SET gym_foreign_key = :gym_foreign_key WHERE id = :id";
        $execute = [
            'gym_foreign_key' => $gymForeignKey,
            'id' => $user['id']
        ];
        
        self::getModel('UserModel')->updateInfo($set, $execute);
        
        session::setSucceed ('Modification enregistrée !');
    }
    
    
    /**
     * Function update user profil picture
     * @return void
     */
    public static function updatePicture() : void {

        extract($_FILES['picture']);
        
        $user = self::findUser($_SESSION['auth']['email']);
        
        $picType = explode('/', $type);

        if($error === UPLOAD_ERR_OK) {
            
            $extension = $picType[1];
            
            if(!($size < (1024 * 1024 * 4))){
                session::setError('Fichier trop lourd, taille maximum acceptée est de 4MO');
                
            }else if($extension !== 'jpeg'){
                session::setError("Le format " . $extension . " n'est pas autorisé seul format accepté 'jpeg'");
                ApplicationController::redirect('profil');
                    
            }else{

                $filename = uniqid() . '-'. $user['id'] . '.' . $extension;
                
                move_uploaded_file($tmp_name, 'ressources/' . $filename);
                
                $set = " SET profil_picture = :profil_picture WHERE email = :email";
                $execute = [
                    ':profil_picture' => $filename,
                    ':email' => $user['email']
                ];
                    
                self::getModel('UserModel')->updateInfo($set, $execute);
                
                session::setSucceed('Modification enregistrée !');
                
                ApplicationController::redirect('profil');
            }
            
        }else if($error === UPLOAD_ERR_NO_FILE){ 
            
            session::setError('Aucun fichier envoyé');
            ApplicationController::redirect('profil');
        
        }else{
            
            session::setError('Une erreur est survenue, veuillez contacter l\'administrateur du site');
            ApplicationController::redirect('profil');
        }
    }
    
    
    /**
     * Function display templates
     * @return void
     */
    public function display() {
        
        $allGym = self::allGym();
        
        $user = self::findUser($_SESSION['auth']['email']);

        $template = "src/views/profil.phtml";
        require "src/views/layout.phtml";
    }
     
     
     
}

