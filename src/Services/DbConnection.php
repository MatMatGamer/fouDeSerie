<?php

namespace App\Services;

use \PDO;
use \PDOException;

class DbConnection
{
    private static $serveur = 'mysql:host=192.168.4.1';
    private static $bdd = 'dbname=mpeltier_fouDeSerie';
    private static $user = 'sqlmpeltier';
    private static $mdp = 'savary';
    private static $pdo = null;
    private static $options = array(PDO::MYSQL_ATTR_SSL_CA => '', PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false);

    private function __construct()
    {
    }

    public static function getPdo()
    {
        if (is_null(DbConnection::$pdo)) {
            try {
                DbConnection::$pdo = new PDO(DbConnection::$serveur . ';port=3306;' . DbConnection::$bdd, DbConnection::$user, DbConnection::$mdp, DbConnection::$options);
                DbConnection::$pdo->query("SET CHARACTER SET utf8");
                DbConnection::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die($e->getMessage());
            }
        }

        return DbConnection::$pdo;
    }
}
