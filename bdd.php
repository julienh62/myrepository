<?php

    /** Notre fichier "bdd.php" contient toutes nos fonctions dites "génériques"
     * Ce sont donc des fonctions qui nous seront utiles partout dans notre site.
     * Elles pouront être appellée depuis n'importe quel fichier .php dès l'instant 
     * où ce dernier require notre fichier.
    */


    /** Fonction qui retourne un objet de type PDO
     * @param void
     *
     * @return PDO objet de connexion PDO
     */
   /* function dbConnexion() {
        // Ma connexion à la bdd
        $dbh = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME. ';charset=utf8', DB_USER, DB_PASS);
        return $dbh;
    }  */
   
    function dbconnexion() {
    $bdh = new PDO('mysql:host=db.3wa.io;dbname=julienhennebo_c1dev;charset=utf8','julienhennebo','2d1043b81bdfee62f9bb561d70d43c8a');
    return $bdh;

}  

/*
 function dbconnexion() {
    $bdh = new PDO('mysql:host=DB_HOST;dbname=DB_NAME;charset=utf8','DB_USER','DB_PASSWORD');
    return $bdh;

}  */


    /** Fonction qui retourne la date actuelle au format précisé en paramètre
     * @param string $format - Le format de la date (ex: 'd-m-Y')
     * 
     * @return string - La date du jour au format désiré
     */ 
    function getCurrentDate($format = 'd-m-Y') {
        return date($format);
    }
    
    
    /** Fonction qui retourne l'heure au format précisé en paramètre
     * @param string $format - Le format de l'heure (ex: 'H:i:s')
     * @param string $fuseau - Le $fuseau horaire (ex: "Europe/Paris")
     * 
     * @return string - L'heure au format désiré
     */ 
    function getCurrentHour($format = 'H:i:s', $fuseau="Europe/Paris") {
        date_default_timezone_set($fuseau);
        return date($format);
    }
    
    
    /** Fonction qui retourne l'incrémentation d'une valeur
     * @param int $x - Le nombre auquel on veut ajouter 1
     * 
     * @return int - La valeur obtenue
     */ 
    function increment($x) {
        return $x++;
    }

    /** Déplace un fichier transmis dans un répertoire du serveur
     * @param $file contenu du tableau $_FILES à l'index du fichier à uploader
     * @param $errors la variable devant contenir les erreurs. Passage par référence ;)
     * @param $folder chemin absolue ou relatif où le fichier sera déplacé. Par default UPLOADS_DIR
     * @param $fileExtensions par defaut vaut FILE_EXT_IMG. un tableau d'extensions valident
     * @return array un tableau contenant les erreurs ou vide
     */
    function uploadFile(array $file, array &$errors, string $folder = UPLOADS_DIR, array $fileExtensions = FILE_EXT_IMG)
    {
        $filename = '';
    
        if ($file["error"] === UPLOAD_ERR_OK) {
            $tmpName = $file["tmp_name"];
    
            // On récupère l'extension du fichier pour vérifier si elle est dans  $fileExtensions
            $tmpNameArray = explode(".", $file["name"]);
            $tmpExt = end($tmpNameArray);
            if(in_array($tmpExt,$fileExtensions))
            {
                // basename() peut empêcher les attaques de système de fichiers en supprimant les éventuels répertoire dans le nom
                // la validation/assainissement supplémentaire du nom de fichier peut être appropriée
                // On donne un nouveau nom au fichier
                $filename = uniqid().'-'.basename($file["name"]);
                if(!move_uploaded_file($tmpName, $folder.$filename))
                {
                    $errors[] = 'Le fichier n\'a pas été enregistré correctement';
                }
            }
            else
                $errors[] = 'Ce type de fichier n\'est pas autorisé !';
        }
        else if($file["error"] == UPLOAD_ERR_INI_SIZE || $file["error"] == UPLOAD_ERR_FORM_SIZE) {
            //fichier trop volumineux
            $errors[] = 'Le fichier est trop volumineux';
        }
        else {
            $errors[] = 'Une erreur a eu lieu au moment de l\'upload';
        }
    
        return $filename;
    }
    
    
    
    /** Fonction qui vérifie si l'utilisateur est connecté et s'il est admin
     * @param - void
     * 
     * @return - void
     */ 
    function verifAdmin() {
        if(!isset($_SESSION['client']) || $_SESSION['client']['role'] != "ADMIN") {
            header("Location: index.php");
            exit();
        }
    }
    


/**
 * @param array $params tableau contenant les éléments à binder dans la requête
 *
 * @return array jeu d'enregistrement
 */
function dbSelectAll(PDO $bdh , string $sql, array $params = []) : array
{
    /* 1. Preparer une requête */
    $sth = $bdh->prepare($sql);

    /* 2. Executer la requête */
    $sth->execute($params);

    /* 3. On récupère le jeu d'enregistrement */
    return $sth->fetchAll(PDO::FETCH_ASSOC);
}

/** Execute une requête SQL et retourne une ligne du jeu d'enregistrement
 * @param PDO $dbh un objet PDO de connexion
 * @param string $sql la requête a executé
 * @param array $params tableau contenant les éléments à binder dans la requête
 *
 * @return array jeu d'enregistrement
 */
function dbSelectOne(PDO $dbh , string $sql, array $params = [])
{
    /* 1. Preparer une requête */
    $sth = $dbh->prepare($sql);

    /* 2. Executer la requête */
    $sth->execute($params);

    /* 3. On récupère le jeu d'enregistrement */
    return $sth->fetch(PDO::FETCH_ASSOC);
}


/** Execute une requête SQL type INSERT ou UPDATE ou DELETE
 * @param PDO $dbh un objet PDO de connexion
 * @param string $sql la requête a executé
 * @param array $params tableau contenant les éléments à binder dans la requête
 *
 * @return int
 */
function dbExecute(PDO $dbh , string $sql, array $params = []) : int
{
    /* 1. Preparer une requête */
    $sth = $dbh->prepare($sql);

    /* 2. Executer la requête */
    if($sth->execute($params))
        return $dbh->lastInsertId();
    else
        return false;
}

/** Execute une requête SQL permettant de compter le nombre d'éléments dans une table
 * @param PDO $dbh un objet PDO de connexion
 * @param string $table le nom de la table
 *
 * @return array tableau contenant la chaine de caractères correspondant au nombre d'entrées dans la table
 */
function countEntry(PDO $dbh, string $table) : array {
    $sth = $dbh->prepare('SELECT COUNT(*) FROM ' . $table);
    $sth->execute();
    return $sth->fetch(PDO::FETCH_ASSOC);
}

/** Fonction qui permet rapidement d'afficher tous les noms des colonnes d'une table
 *
 * @param string $table le nom de la table
 * @param PDO $dbh l'objet PDO de connexion à la base de données
 *
 * @return string une chaine de caractère du nom des colonne de $table séparés par des virgules
 */
function getColumnName(PDO $dbh, string $table) : string
{
    $q = $dbh->prepare("DESCRIBE ".$table);
    $q->execute();
    $table_fields = $q->fetchAll(PDO::FETCH_COLUMN);
    $stringFields = '';
    foreach($table_fields as $field)
        $stringFields .= $field.',';

    return $stringFields;
}

/** Exécute une requête SQL type SELECT ALL simple par rapport à un ID
 * @param string $table_name    - Nom de la table dans laquelle on va modifier les informations
 * @param integer $id           - ID de référence
 */
function searchToId(string $table_name, int $id){

    $dbh = dbConnexion();

    $stringid = getColumnName($dbh, $table_name);
    $ColumnId = explode(",", $stringid);
    $id_autoIncrement = $ColumnId[0];

    $sth = $dbh->prepare('SELECT * FROM '. $table_name .' WHERE ' . $id_autoIncrement . ' = :id');
    $sth->bindValue('id', $id, PDO::PARAM_STR);
    $sth->execute();
    return $elements = $sth->fetch(PDO::FETCH_ASSOC);
}