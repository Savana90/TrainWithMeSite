<?php


/**Database Class
 * abstract class only use for inherit cannot be instantiate
 * 1 function to connect to database
 *
 */
abstract class DatabaseModel {
    
    protected PDO $pdo; 

    public function __construct() {
        try {
            $this->pdo = new PDO(
                'mysql:host=db.3wa.io;dbname=savannahcharles_training',
                'savannahcharles',
                '1872da6860e769c4012c84262ff62554',
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]
            );
        } catch (PDOException $e) {
            echo 'Erreur de Connexion : ' . $e->getMessage();
            exit();
        }
    }
}