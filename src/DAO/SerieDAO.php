<?php

namespace App\DAO;

use App\Services\DbConnection;
use \PDO;

class SerieDAO
{
    public static function find($id)
    {
        $req = "select * from serie where id=:id";
        $res = DbConnection::getPdo()->prepare($req);
        $res->bindParam(":id", $id, PDO::PARAM_INT);
        $res->execute();
        $data = $res->fetch();
        return $data;
    }

    public static function findLast($number)
    {
        $req = "select * from serie order by premiereDiffusion desc limit :number";
        $res = DbConnection::getPdo()->prepare($req);
        $res->bindParam(":number", $number, PDO::PARAM_INT);
        $res->execute();
        $data = $res->fetchAll();
        return $data;
    }

    public static function findAll()
    {
        $req = "select * from serie";
        $res = DbConnection::getPdo()->prepare($req);
        $res->execute();
        $data = $res->fetchAll();

        return $data;
    }

    public static function count()
    {
        $req = "select count(*) as count from serie";
        $res = DbConnection::getPdo()->prepare($req);
        $res->execute();
        return $res->fetch();
    }
}
