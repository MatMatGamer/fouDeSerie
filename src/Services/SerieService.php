<?php

namespace App\Services;

use App\DAO\SerieDAO;
use App\Entity\Serie;
use App\Entity\Pays;

class SerieService
{

    public function __construct()
    {
    }

    public static function getSeries()
    {
        $dao = SerieDAO::findAll();
        $res = array();

        foreach ($dao as $ligne) {
            $res[] = new Serie($ligne["id"], $ligne["titre"], $ligne["resume"], $ligne["premiereDiffusion"], $ligne["nbEpisodes"], $ligne["image"], PaysService::getPaysById($ligne["idPays"]));
        }

        return $res;
    }

    public static function getSerieById($id)
    {
        $dao = SerieDAO::find($id);

        return new Serie($dao["id"], $dao["titre"], $dao["resume"], $dao["premiereDiffusion"], $dao["nbEpisodes"], $dao["image"], PaysService::getPaysById($dao["idPays"]));
    }

    public static function getLast($number)
    {
        $dao = SerieDAO::findLast($number);

        $res = array();

        foreach ($dao as $ligne) {
            $res[] = new Serie($ligne["id"], $ligne["titre"], $ligne["resume"], $ligne["premiereDiffusion"], $ligne["nbEpisodes"], $ligne["image"], PaysService::getPaysById($ligne["idPays"]));
        }

        return $res;
    }

    public static function getLastIds($number)
    {
        $dao = SerieDAO::findLast($number);
        $res = array();

        foreach ($dao as $ligne) {
            $res[] = $ligne["id"];
        }

        return $res;
    }

    public static function count()
    {
        return SerieDAO::count()["count"];
    }
}
