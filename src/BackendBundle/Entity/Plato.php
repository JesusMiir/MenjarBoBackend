<?php

namespace BackendBundle\Entity;

/**
 * Plato
 */
class Plato
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
    private $descripcion;

    /**
     * @var float
     */
    private $precio;

    /**
     * @var string
     */
    private $imagen;

    /**
     * @var integer
     */
    private $contadordiario;

    /**
     * @var integer
     */
    private $contadorsetmanal;

    /**
     * @var integer
     */
    private $contadormensual;

    /**
     * @var integer
     */
    private $contadoranual;

    /**
     * @var integer
     */
    private $contadorgeneral;

    /**
     * @var \BackendBundle\Entity\Categoria
     */
    private $categoria;


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
     * @return Plato
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
     * Set descripcion
     *
     * @param string $descripcion
     *
     * @return Plato
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set precio
     *
     * @param float $precio
     *
     * @return Plato
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
     * Set imagen
     *
     * @param string $imagen
     *
     * @return Plato
     */
    public function setImagen($imagen)
    {
        $this->imagen = $imagen;

        return $this;
    }

    /**
     * Get imagen
     *
     * @return string
     */
    public function getImagen()
    {
        return $this->imagen;
    }

    /**
     * Set contadordiario
     *
     * @param integer $contadordiario
     *
     * @return Plato
     */
    public function setContadordiario($contadordiario)
    {
        $this->contadordiario = $contadordiario;

        return $this;
    }

    /**
     * Get contadordiario
     *
     * @return integer
     */
    public function getContadordiario()
    {
        return $this->contadordiario;
    }

    /**
     * Set contadorsetmanal
     *
     * @param integer $contadorsetmanal
     *
     * @return Plato
     */
    public function setContadorsetmanal($contadorsetmanal)
    {
        $this->contadorsetmanal = $contadorsetmanal;

        return $this;
    }

    /**
     * Get contadorsetmanal
     *
     * @return integer
     */
    public function getContadorsetmanal()
    {
        return $this->contadorsetmanal;
    }

    /**
     * Set contadormensual
     *
     * @param integer $contadormensual
     *
     * @return Plato
     */
    public function setContadormensual($contadormensual)
    {
        $this->contadormensual = $contadormensual;

        return $this;
    }

    /**
     * Get contadormensual
     *
     * @return integer
     */
    public function getContadormensual()
    {
        return $this->contadormensual;
    }

    /**
     * Set contadoranual
     *
     * @param integer $contadoranual
     *
     * @return Plato
     */
    public function setContadoranual($contadoranual)
    {
        $this->contadoranual = $contadoranual;

        return $this;
    }

    /**
     * Get contadoranual
     *
     * @return integer
     */
    public function getContadoranual()
    {
        return $this->contadoranual;
    }

    /**
     * Set contadorgeneral
     *
     * @param integer $contadorgeneral
     *
     * @return Plato
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
     * Set categoria
     *
     * @param \BackendBundle\Entity\Categoria $categoria
     *
     * @return Plato
     */
    public function setCategoria(\BackendBundle\Entity\Categoria $categoria = null)
    {
        $this->categoria = $categoria;

        return $this;
    }

    /**
     * Get categoria
     *
     * @return \BackendBundle\Entity\Categoria
     */
    public function getCategoria()
    {
        return $this->categoria;
    }
}

