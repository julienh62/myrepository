<?php
// TITLE POUR LE NAVIGATEUR
define("TITLE" , "title");

define( 'DB_NAME', 'julienhennebo_c1dev' );

/** Utilisateur de la base de données MySQL. */
define( 'DB_USER', 'julienhennebo' );

/** Mot de passe de la base de données MySQL. */
define( 'DB_PASSWORD', '2d1043b81bdfee62f9bb561d70d43c8a' );

/** Adresse de l’hébergement MySQL. */
define( 'DB_HOST', 'db.3wa.io' );

/** Jeu de caractères à utiliser par la base de données lors de la création des tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/**
 * @var FILE_EXT_IMG extensions acceptees pour les images
 */
const FILE_EXT_IMG = ['jpg', 'jpeg', 'gif','png'];
/**
 * @var BASE_DIR Repertoire de base du blog sur le disque du serveur
 */
define('BASE_DIR', realpath(dirname(__FILE__) . "/../"));

/**
* @var UPLOADS_DIR Repertoire ou seront uploades les fichiers
*/
const UPLOADS_DIR = BASE_DIR.'/uploads/';

   ?>