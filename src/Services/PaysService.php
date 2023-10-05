<?php

namespace App\Services;

use App\DAO\PaysDAO;
use App\Entity\Pays;

class PaysService
{

    public function __construct()
    {
    }

    public static function getSeries()
    {
        $dao = PaysDAO::findAll();
        $res = array();

        foreach ($dao as $ligne) {
            $res[] = new Pays($ligne["id"], $ligne["nom"], $ligne["drapeau"]);
        }

        return $res;
    }

    public static function getPaysById($id)
    {
        $dao = PaysDAO::find($id);

        return new Pays($dao["id"], $dao["nom"], $dao["drapeau"]);
    }
}
