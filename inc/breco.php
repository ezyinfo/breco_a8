<?php

class BrecoDB
{
    private $db_host;
    private $db_name;
    private $db_user;
    private $db_pass;
    private $pdo;

    public function __construct($db_host, $db_name, $db_user, $db_pass)
    {
        $this->db_host = $db_host;
        $this->db_name = $db_name;
        $this->db_user = $db_user;
        $this->db_pass = $db_pass;
    }

    public function connect()
    {
        try {
            $dsn = 'mysql:host=' . $this->db_host . ';dbname=' . $this->db_name;
            $this->pdo = new PDO($dsn, $this->db_user, $this->db_pass);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo 'Erreur de connexion : ' . $e->getMessage();
        }
    }

    private function is_exist($tableName)
    {
        try {
            // Paramètres de connexion à la base de données
            $dsn = 'mysql:host=' . $this->db_host . ';dbname=' . $this->db_name;

            // Créer une nouvelle instance de PDO
            $pdo = new PDO($dsn, $this->db_user, $this->db_pass);

            // Set errors as exceptions
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Requête pour vérifier l'existence de la table
            $query = "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = 'breco' AND table_name = :table";

            // Préparer et exécuter la requête
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':table', $tableName);
            $stmt->execute();

            // Récupérer le résultat
            $tableExists = $stmt->fetchColumn();

            if ($tableExists) {
                echo "La table '$tableName' existe dans la base de données 'breco'.<br>";
            } else {
                echo "La table '$tableName' n'existe pas dans la base de données 'breco'.<br>";
            }
            return $tableExists;
        } catch (PDOException $e) {
            echo 'Erreur : ' . $e->getMessage();
        }
    }


    public function initDb()
    {
        // Check if tables exists
        if (!$this->is_exist("Utilisateur")) {
            echo "Make tables<br>";
            $this->executeQuery("CREATE TABLE `breco`.`utilisateur` (
                `Utilisateur_id` INT NOT NULL AUTO_INCREMENT ,
                `Nom` VARCHAR(64) NOT NULL ,
                `Mail` VARCHAR(64) NOT NULL ,
                `Password` VARCHAR(64) NOT NULL , 
                PRIMARY KEY (`Utilisateur_id`)); ");
            $this->executeQuery("CREATE TABLE `breco`.`etape` (
                 `Etape_id` INT NOT NULL AUTO_INCREMENT ,
                 `Nom` VARCHAR(64) NOT NULL,
                 PRIMARY KEY (`Etape_id`));");
            $this->executeQuery("CREATE TABLE `breco`.`trajet` (
                `Trajet_id` INT NOT NULL AUTO_INCREMENT ,
                `Depart_id` INT NOT NULL , `Arrivee_nom` VARCHAR(64) NOT NULL ,
                `Horaire_depart` TIME NOT NULL ,
                `Horaire_arrivee` TIME NOT NULL ,
                `Lundi` BIT NOT NULL ,
                `Mardi` BIT NOT NULL ,
                `Mercredi` BIT NOT NULL ,
                `Jeudi` BIT NOT NULL ,
                `Vendredi` BIT NOT NULL ,
                `Samedi` BIT NOT NULL ,
                `Dimanche` BIT NOT NULL ,
                `Utilisateur_id` INT NOT NULL , 
                PRIMARY KEY (`Trajet_id`),
                FOREIGN KEY (`Utilisateur_id`) REFERENCES `utilisateur`(`Utilisateur_id`),
                FOREIGN KEY (`Depart_id`) REFERENCES `etape`(`Etape_id`));");
            $this->executeQuery("CREATE TABLE `breco`.`etape_trajet` (
                `Etape_trajet_id` INT NOT NULL AUTO_INCREMENT ,
                `Trajet_id` INT NOT NULL ,
                `Etape_id` INT NOT NULL ,
                PRIMARY KEY (`Etape_trajet_id`),
                FOREIGN KEY (`Etape_id`) REFERENCES `etape`(`Etape_id`),
                FOREIGN KEY (`Trajet_id`) REFERENCES `trajet`(`Trajet_id`));");
        }
    }

    // Ajoutez d'autres méthodes pour la manipulation de la base de données

    public function executeQuery($query, $params = [])
    {
        try {
            $stmt = $this->pdo->prepare($query);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo 'Erreur d\'exécution de la requête : ' . $e->getMessage();
        }
    }
}
