<?php

require_once 'GenericModel.php';
require_once 'DatabaseModel.php';
/**
 * Class SessionUser inherit from GenericModel 
 *
 * Manage SQL requests for the session_user table
 * 
 * 6 functions
 */
 
class SessionUserModel extends GenericModel
{
    
    protected string $table;
    
    public function __construct(){
        parent::__construct();
        $this->table = 'session_user';
    }
    
    
    
    /**
     * Function to request insertion in session_user table
     * @parameter 1 int 1 string
     * @return void
     */
    public function insertSessionUser(int $userId, string $numSession): void{
        try{
            $pdo = $this->pdo;
            $query = $pdo->prepare("
                INSERT INTO {$this->table} (user_foreign_key, php_session_id) 
                VALUES (:user_foreign_key, :php_session_id)");
                
            $query->execute([
                ":user_foreign_key" => $userId,
                ":php_session_id" => $numSession
            ]);
                    
        } catch (PDOException $e) {
            echo $e->getMessage();
            die;
        }
    }
    
    
    
    /**
     * Function to find user that have been active on the site for the last 20min
     * @parameter one integer
     * @return array
     */
    public function findSessionActivity(int $userId): array {
        try{
            $pdo = $this->pdo;
            $query = $pdo->prepare(
                "SELECT *
                FROM {$this->table} 
                WHERE user_foreign_key = :user_foreign_key
                AND timestamp > NOW() - INTERVAL :session_duration MINUTE"
            );
            $query->execute([
                "user_foreign_key" => $userId,
                "session_duration" => 20
            ]);
            $user = $query->fetch();
            
            // If no records find return a empty array
            if ($user === false) {
                return [];
            } else {
                return $user;
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
            die;
        }
    }
    
    
    /**
     * Function to update user activity on the web site 
     * @parametres one integer
     * @return void
     */
    public function updateSession(int $userId): void{
        try{
            $pdo = $this->pdo; 
            $query = $pdo->prepare(
                "UPDATE {$this->table} 
                SET timestamp = NOW() 
                WHERE user_foreign_key = :user_foreign_key"
            );
                
            $query->execute([
                ":user_foreign_key" => $userId
            ]);
                    
        }catch(PDOException $e) {
            echo $e->getMessage();
            die;
        }
    }
    
    
    
    /**
     * Function to delete a record from session table
     * @parameter one integer
     * @return void
     */
    public function deleteSession(int $userId) : void{
        try{
            $pdo = $this->pdo; 
            $query = $pdo->prepare(
                "DELETE FROM {$this->table} 
                WHERE user_foreign_key = :user_foreign_key");
                
            $query->execute([
                ":user_foreign_key" => $userId
            ]);
            
        }catch(PDOException $e){
            echo $e->getMessage();
            die;
        }
        
    }
    
    
    /** 
     * Function to find user in session table and to know if he was active for the last 2min
     * Function use in messageController to know if user is onligne when sending a message
     * @parametre int $id
     * @return array
     */
    public function userConnected(int $id): array{
        try{
            $pdo = $this->pdo;
            $query = $pdo->prepare("
                SELECT user_foreign_key
                FROM {$this->table}
                WHERE user_foreign_key = :user_foreign_key 
                AND timestamp > NOW() - INTERVAL :session_duration MINUTE"
                );
            $query->execute([
                "user_foreign_key" => $id,
                "session_duration" => 2
            ]);
            $user = $query->fetch();
            
            // If no records find return a empty array
            if ($user === false) {
                return [];
            } else {
                return $user;
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
            die;
        }
    }
    
    
    
    
}