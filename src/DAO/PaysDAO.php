<?php

namespace App\DAO;

use App\Services\DbConnection;
use \PDO;

class PaysDAO
{
    public static function find($id)
    {
        $req = "select * from pays where id=:id";
        $res = DbConnection::getPdo()->prepare($req);
        $res->bindParam(":id", $id, PDO::PARAM_STR);
        $res->execute();
        $data = $res->fetch();
        return $data;
    }

    public static function findAll()
    {
        $req = "select * from pays";
        $res = DbConnection::getPdo()->prepare($req);
        $res->execute();
        $data = $res->fetchAll();

        return $data;
    }
}
