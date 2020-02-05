<?php

namespace BackendBundle\Entity;

/**
 * Horario
 */
class Horario
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $lunesM;

    /**
     * @var string
     */
    private $lunesT;

    /**
     * @var string
     */
    private $martesM;

    /**
     * @var string
     */
    private $martesT;

    /**
     * @var string
     */
    private $miercolesM;

    /**
     * @var string
     */
    private $miercolesT;


    /**
     * @var string
     */
    private $juevesM;

    /**
     * @var string
     */
    private $juevesT;

    /**
     * @var string
     */
    private $viernesM;

    /**
     * @var string
     */
    private $viernesT;

    /**
     * @var string
     */
    private $sabadoM;

    /**
     * @var string
     */
    private $sabadoT;

    /**
     * @var string
     */
    private $domingoM;

    /**
     * @var string
     */
    private $domingoT;

    /**
     * @var \BackendBundle\Entity\Restaurante
     */
    private $restaurante;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set lunesM
     *
     * @param string $lunesM
     *
     * @return Menu
     */
    public function setLunesM($lunesM)
    {
        $this->lunesM = $lunesM;

        return $this;
    }

    /**
     * Get lunesM
     *
     * @return string
     */
    public function getLunesM()
    {
        return $this->lunesM;
    }

    /**
     * Set lunesT
     *
     * @param string $lunesT
     *
     * @return Horario
     */
    public function setLunesT($lunesT)
    {
        $this->lunesT = $lunesT;

        return $this;
    }

    /**
     * Get lunesT
     *
     * @return string
     */
    public function getLunesT()
    {
        return $this->lunesT;
    }

    /**
     * Set martesM
     *
     * @param string $martesM
     *
     * @return Horario
     */
    public function setMartesM($martesM)
    {
        $this->martesM = $martesM;

        return $this;
    }

    /**
     * Get martesM
     *
     * @return string
     */
    public function getMartesM()
    {
        return $this->martesM;
    }

    /**
     * Set martesT
     *
     * @param string $martesT
     *
     * @return Horario
     */
    public function setMartesT($martesT)
    {
        $this->martesT = $martesT;

        return $this;
    }

    /**
     * Get martesT
     *
     * @return string
     */
    public function getMartesT()
    {
        return $this->martesT;
    }

    /**
     * Set miercolesM
     *
     * @param string $miercolesM
     *
     * @return Horario
     */
    public function setMiercolesM($miercolesM)
    {
        $this->miercolesM = $miercolesM;

        return $this;
    }

    /**
     * Get miercolesM
     *
     * @return string
     */
    public function getMiercolesM()
    {
        return $this->miercolesM;
    }

    /**
     * Set miercolesT
     *
     * @param string $miercolesT
     *
     * @return Horario
     */
    public function setMiercolesT($miercolesT)
    {
        $this->miercolesT = $miercolesT;

        return $this;
    }

    /**
     * Get miercolesT
     *
     * @return string
     */
    public function getMiercolesT()
    {
        return $this->miercolesT;
    }

    /**
     * Set juevesM
     *
     * @param string $juevesM
     *
     * @return Horario
     */
    public function setJuevesM($juevesM)
    {
        $this->juevesM = $juevesM;

        return $this;
    }

    /**
     * Get juevesM
     *
     * @return string
     */
    public function getJuevesM()
    {
        return $this->juevesM;
    }

    /**
     * Set juevesT
     *
     * @param string $juevesT
     *
     * @return Horario
     */
    public function setJuevesT($juevesT)
    {
        $this->juevesT = $juevesT;

        return $this;
    }

    /**
     * Get juevesT
     *
     * @return string
     */
    public function getJuevesT()
    {
        return $this->juevesT;
    }

    /**
     * Set viernesM
     *
     * @param string $viernesM
     *
     * @return Horario
     */
    public function setViernesM($viernesM)
    {
        $this->viernesM = $viernesM;

        return $this;
    }

    /**
     * Get viernesM
     *
     * @return string
     */
    public function getViernesM()
    {
        return $this->viernesM;
    }

    /**
     * Set viernesT
     *
     * @param string $viernesT
     *
     * @return Horario
     */
    public function setViernesT($viernesT)
    {
        $this->viernesT = $viernesT;

        return $this;
    }

    /**
     * Get viernesT
     *
     * @return string
     */
    public function getViernesT()
    {
        return $this->viernesT;
    }

    /**
     * Set sabadoM
     *
     * @param string $sabadoM
     *
     * @return Horario
     */
    public function setSabadoM($sabadoM)
    {
        $this->sabadoM = $sabadoM;

        return $this;
    }

    /**
     * Get sabadoM
     *
     * @return string
     */
    public function getSabadoM()
    {
        return $this->sabadoM;
    }

    /**
     * Set sabadoT
     *
     * @param string $sabadoT
     *
     * @return Horario
     */
    public function setSabadoT($sabadoT)
    {
        $this->sabadoT = $sabadoT;

        return $this;
    }

    /**
     * Get sabadoT
     *
     * @return string
     */
    public function getSabadoT()
    {
        return $this->sabadoT;
    }

    /**
     * Set domingoM
     *
     * @param string $domingoM
     *
     * @return Horario
     */
    public function setDomingoM($domingoM)
    {
        $this->domingoM = $domingoM;

        return $this;
    }

    /**
     * Get domingoM
     *
     * @return string
     */
    public function getDomingoM()
    {
        return $this->domingoM;
    }

    /**
     * Set domingoT
     *
     * @param string $domingoT
     *
     * @return Horario
     */
    public function setDomingoT($domingoT)
    {
        $this->domingoT = $domingoT;

        return $this;
    }

    /**
     * Get domingoT
     *
     * @return string
     */
    public function getDomingoT()
    {
        return $this->domingoT;
    }

    /**
     * Set restaurante
     *
     * @param \BackendBundle\Entity\Restaurante $restaurante
     *
     * @return Horario
     */
    public function setRestaurante(\BackendBundle\Entity\Restaurante $restaurante = null)
    {
        $this->restaurante = $restaurante;

        return $this;
    }

    /**
     * Get restaurante
     *
     * @return \BackendBundle\Entity\Restaurante
     */
    public function getRestaurante()
    {
        return $this->restaurante;
    }
}

