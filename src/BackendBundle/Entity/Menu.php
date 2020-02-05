<?php

namespace BackendBundle\Entity;

/**
 * Menu
 */
class Menu
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $nombre;

    /**
     * @var string
     */
    private $nombreprimero;

    /**
     * @var string
     */
    private $descripcionprimero;

    /**
     * @var string
     */
    private $nombresegundo;

    /**
     * @var string
     */
    private $descripcionsegundo;

    /**
     * @var float
     */
    private $precio;

    /**
     * @var integer
     */
    private $contadoractual;

    /**
     * @var integer
     */
    private $contadorgeneral;

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
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Menu
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set nombreprimero
     *
     * @param string $nombreprimero
     *
     * @return Menu
     */
    public function setNombreprimero($nombreprimero)
    {
        $this->nombreprimero = $nombreprimero;

        return $this;
    }

    /**
     * Get nombreprimero
     *
     * @return string
     */
    public function getNombreprimero()
    {
        return $this->nombreprimero;
    }

    /**
     * Set descripcionprimero
     *
     * @param string $descripcionprimero
     *
     * @return Menu
     */
    public function setDescripcionprimero($descripcionprimero)
    {
        $this->descripcionprimero = $descripcionprimero;

        return $this;
    }

    /**
     * Get descripcionprimero
     *
     * @return string
     */
    public function getDescripcionprimero()
    {
        return $this->descripcionprimero;
    }

    /**
     * Set nombresegundo
     *
     * @param string $nombresegundo
     *
     * @return Menu
     */
    public function setNombresegundo($nombresegundo)
    {
        $this->nombresegundo = $nombresegundo;

        return $this;
    }

    /**
     * Get nombresegundo
     *
     * @return string
     */
    public function getNombresegundo()
    {
        return $this->nombresegundo;
    }

    /**
     * Set descripcionsegundo
     *
     * @param string $descripcionsegundo
     *
     * @return Menu
     */
    public function setDescripcionsegundo($descripcionsegundo)
    {
        $this->descripcionsegundo = $descripcionsegundo;

        return $this;
    }

    /**
     * Get descripcionsegundo
     *
     * @return string
     */
    public function getDescripcionsegundo()
    {
        return $this->descripcionsegundo;
    }

    /**
     * Set precio
     *
     * @param float $precio
     *
     * @return Menu
     */
    public function setPrecio($precio)
    {
        $this->precio = $precio;

        return $this;
    }

    /**
     * Get precio
     *
     * @return float
     */
    public function getPrecio()
    {
        return $this->precio;
    }

    /**
     * Set contadoractual
     *
     * @param integer $contadoractual
     *
     * @return Menu
     */
    public function setContadoractual($contadoractual)
    {
        $this->contadoractual = $contadoractual;

        return $this;
    }

    /**
     * Get contadoractual
     *
     * @return integer
     */
    public function getContadoractual()
    {
        return $this->contadoractual;
    }

    /**
     * Set contadorgeneral
     *
     * @param integer $contadorgeneral
     *
     * @return Menu
     */
    public function setContadorgeneral($contadorgeneral)
    {
        $this->contadorgeneral = $contadorgeneral;

        return $this;
    }

    /**
     * Get contadorgeneral
     *
     * @return integer
     */
    public function getContadorgeneral()
    {
        return $this->contadorgeneral;
    }

    /**
     * Set restaurante
     *
     * @param \BackendBundle\Entity\Restaurante $restaurante
     *
     * @return Menu
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

