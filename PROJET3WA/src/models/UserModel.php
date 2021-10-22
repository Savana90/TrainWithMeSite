<?php

require_once 'GenericModel.php';
require_once 'DatabaseModel.php';
/**
 * Class User inherit from GenericModel
 * 
 * Manage SQL request for the user table
 * 
 * 5 functions
 */
 
class UserModel extends GenericModel{
    
    protected string $table;
    
    public function __construct(){
        
        parent::__construct();
        $this->table = 'user';
    }
    
    
    /**
     * Function requeste user
     * @parametre 1 string 1 array
     * @return array
     */
    public function findUniqUser(string $set, array $execute): array {
        try{
            $pdo = $this->pdo;
            $query = $pdo->prepare(
                "SELECT id, first_name, last_name, user_name, admin, 
                email, profil_picture, DATE_FORMAT(created_at, '%e-%m-%Y') AS date_create , gym_foreign_key, 
                DATE_FORMAT(last_connection_time, '%e-%m-%Y %H:%i')  AS last_connected
                FROM {$this->table}" . $set
            );
            $query->execute($execute);
            $user = $query->fetch();
            
            // If user dont exist return a empty array
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
     * Function to find a user
     * @parametre one string 
     * @return array
     */
    public function searchUsers(string $search): array{
        try{
            $pdo = $this->pdo;
            $query = $pdo->prepare(
                "SELECT *
                FROM {$this->table}
                WHERE user_name LIKE :search 
                OR first_name LIKE :search
                OR last_name LIKE :search" 
            );
            $query->execute([
                ':search' => '%' . $search . '%'
                ]);
            $user = $query->fetchAll();
            
            // If no record find return a empty array
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
     * Function to request joint with session_user table to recover users
     * @parameter 1 int 1 string
     * @return array
     */
    public function paging(string $begin, int $id): array{
        try{
            $pdo = $this->pdo;
            
            $query = $pdo->prepare("
                SELECT id, first_name, last_name, admin,
                TIME_TO_SEC(TIMEDIFF(NOW(), timestamp) )as connected
                FROM {$this->table}
                LEFT JOIN session_user
                ON user.id = session_user.user_foreign_key
                WHERE id != :id
                ORDER BY created_at
                LIMIT {$begin}, 10
            ");
                
            $query->execute([
                'id' => $id
                ]);
           
            $user = $query->fetchAll();
            
            // If no record find return a empty array
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
     * Function to request count of user in table user
     * @return array
     */
    public function quantite(): array{
        try{
            $pdo = $this->pdo;
            $query = $pdo->query("
                SELECT COUNT(id) as qtt
                FROM {$this->table}
            ");
           
            $user = $query->fetchAll();
            
            // If no record find return a empty array
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