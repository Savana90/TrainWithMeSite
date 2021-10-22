<?php

require_once 'DatabaseModel.php';

/**
 * GenericModel class
 * 
 * 7 generics requestes functions 
 * 
 * Class will only be use for inherit
 */
 
class GenericModel extends DatabaseModel{
     
    protected string $table;

    public function __construct(){
        parent::__construct();
    }
     
    /**
     * Function to insert a record
     * @parameter 1 string 1 array
     * @return void
     */
    public function insert(string $value, array $execute): void{
        try{
            $pdo = $this->pdo;
            $query = $pdo->prepare("
                INSERT INTO {$this->table}" . $value);
            $query->execute($execute);
        } catch (PDOException $e) {
            echo $e->getMessage();
            die;
        }
    }
    
    /**
     * Function recover an entirety table 
     * @parametre optionnel string
     * @return array
     */
    public function findAll(?string $order = ""): array{
        try{
            $sql = "
                SELECT *
                FROM {$this->table}";
            
            if($order){
                $sql .= " ORDER BY" . $order;
            }
            $query = $this->pdo->query($sql);
            
            $allElements = $query->fetchAll();
            
            return $allElements;
        } catch (PDOException $e) {
            echo $e->getMessage();
            die;
        }
    }
    
    
    /**
     * Function to recover a set of records with a specific condition
     * @parameter 1 string 1 array
     * @return array
     */
    public function findAllWithCondition(string $where, array $execute): array{
        try{
            $query = $this->pdo->prepare(
                "SELECT *
                FROM {$this->table} " . $where);
            
            $query->execute($execute);
            
            $allElementsWhere = $query->fetchAll();
            
            // If no record find return a empty array
            if ($allElementsWhere === false) {
                return [];
            } else {
                return $allElementsWhere;
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
            die;
        }
    }
    
    
    /**
     * Function to recover one specific record
     * @parametre one string and one array
     * @return array
     */
    public function findUniq(string $set, array $execute): array {
        try{
            $pdo = $this->pdo;
            $query = $pdo->prepare(
                "SELECT *
                FROM {$this->table}" . $set
            );
            $query->execute($execute);
            
            $user = $query->fetch();
            
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
     * Function to update field in table
     * @parametres one string one array
     * @return void
     */
    public function updateInfo(string $set, array $execute): void{
        try{
            $pdo = $this->pdo; 
            $query = $pdo->prepare("UPDATE {$this->table}" . $set);
                
            $query->execute($execute);
            
        }catch(PDOException $e) {
            echo $e->getMessage();
            die;
        }
    }
    
    /**
     * Function to delete record(s) in DB
     * @parameter one string one array
     * @return void
     */
    public function deleteUser(string $set, array $execute) : void{
        try{
            $pdo = $this->pdo; 
            $query = $pdo->prepare("DELETE FROM {$this->table}" . $set);
                
            $query->execute($execute);
            
        }catch(PDOException $e){
            echo $e->getMessage();
            die;
        }
    }
    
    
    
  
    
    
} 