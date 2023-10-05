<?php

namespace App\Entity;

class Serie
{
    private $id;
    private $titre;
    private $resume;
    private $premDiff;
    private $nbEpisodes;
    private $image;
    private $pays;

    public function __construct($id, $titre, $resume, $premDiff, $nbEpisodes, $image, $pays)
    {
        $this->id = $id;
        $this->titre = $titre;
        $this->resume = $resume;
        $this->premDiff = $premDiff;
        $this->nbEpisodes = $nbEpisodes;
        $this->image = $image;
        $this->pays = $pays;
    }

    /**
     * Get the value of id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of titre
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set the value of titre
     *
     * @return  self
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get the value of resume
     */
    public function getResume()
    {
        return $this->resume;
    }

    /**
     * Set the value of resume
     *
     * @return  self
     */
    public function setResume($resume)
    {
        $this->resume = $resume;

        return $this;
    }

    /**
     * Get the value of premDiff
     */
    public function getPremDiff()
    {
        return $this->premDiff;
    }

    /**
     * Set the value of premDiff
     *
     * @return  self
     */
    public function setPremDiff($premDiff)
    {
        $this->premDiff = $premDiff;

        return $this;
    }

    /**
     * Get the value of nbEpisodes
     */
    public function getNbEpisodes()
    {
        return $this->nbEpisodes;
    }

    /**
     * Set the value of nbEpisodes
     *
     * @return  self
     */
    public function setNbEpisodes($nbEpisodes)
    {
        $this->nbEpisodes = $nbEpisodes;

        return $this;
    }

    /**
     * Get the value of image
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set the value of image
     *
     * @return  self
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get the value of pays
     */
    public function getPays()
    {
        return $this->pays;
    }

    /**
     * Set the value of pays
     *
     * @return  self
     */
    public function setPays($pays)
    {
        $this->pays = $pays;

        return $this;
    }
}
