<?php

namespace BackendBundle\Entity;

/**
 * Encargo
 */
class Encargo
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $estado;

    /**
     * @var float
     */
    private $preciototal;

    /**
     * @var string
     */
    private $informacionusuario;

    /**
     * @var string
     */
    private $informacionrestaurante;

    /**
     * @var string
     */
    private $vivienda;

    /**
     * @var boolean
     */
    private $efectivo;

    /**
     * @var \DateTime
     */
    private $createat;

    /**
     * @var \BackendBundle\Entity\Restaurante
     */
    private $restaurante;

    /**
     * @var \BackendBundle\Entity\Usuario
     */
    private $usuario;


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
     * Set estado
     *
     * @param string $estado
     *
     * @return Encargo
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Get estado
     *
     * @return string
     */
    public function getEstado()
    {
        return $this->estado;
    }


    /**
     * Set preciototal
     *
     * @param float $preciototal
     *
     * @return Encargo
     */
    public function setPreciototal($preciototal)
    {
        $this->preciototal = $preciototal;

        return $this;
    }

    /**
     * Get preciototal
     *
     * @return float
     */
    public function getPreciototal()
    {
        return $this->preciototal;
    }

    /**
     * Set informacionusuario
     *
     * @param string $informacionusuario
     *
     * @return Encargo
     */
    public function setInformacionusuario($informacionusuario)
    {
        $this->informacionusuario = $informacionusuario;

        return $this;
    }

    /**
     * Get informacionusuario
     *
     * @return string
     */
    public function getInformacionusuario()
    {
        return $this->informacionusuario;
    }

    /**
     * Set informacionrestaurante
     *
     * @param string $informacionrestaurante
     *
     * @return Encargo
     */
    public function setInformacionrestaurante($informacionrestaurante)
    {
        $this->informacionrestaurante = $informacionrestaurante;

        return $this;
    }

    /**
     * Get informacionrestaurante
     *
     * @return string
     */
    public function getInformacionrestaurante()
    {
        return $this->informacionrestaurante;
    }

    /**
     * Set vivienda
     *
     * @param string $vivienda
     *
     * @return Encargo
     */
    public function setVivienda($vivienda)
    {
        $this->vivienda = $vivienda;

        return $this;
    }

    /**
     * Get vivienda
     *
     * @return string
     */
    public function getVivienda()
    {
        return $this->vivienda;
    }

    /**
     * Set efectivo
     *
     * @param boolean $efectivo
     *
     * @return Encargo
     */
    public function setEfectivo($efectivo)
    {
        $this->efectivo = $efectivo;

        return $this;
    }

    /**
     * Get efectivo
     *
     * @return boolean
     */
    public function getEfectivo()
    {
        return $this->efectivo;
    }

    /**
     * Set createat
     *
     * @param \DateTime $createat
     *
     * @return Encargo
     */
    public function setCreateat($createat)
    {
        $this->createat = $createat;

        return $this;
    }

    /**
     * Get createat
     *
     * @return \DateTime
     */
    public function getCreateat()
    {
        return $this->createat;
    }

    /**
     * Set restaurante
     *
     * @param \BackendBundle\Entity\Restaurante $restaurante
     *
     * @return Encargo
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

    /**
     * Set usuario
     *
     * @param \BackendBundle\Entity\Usuario $usuario
     *
     * @return Encargo
     */
    public function setUsuario(\BackendBundle\Entity\Usuario $usuario = null)
    {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * Get usuario
     *
     * @return \BackendBundle\Entity\Usuario
     */
    public function getUsuario()
    {
        return $this->usuario;
    }
}

