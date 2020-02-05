<?php

namespace BackendBundle\Entity;

/**
 * Restaurante
 */
class Restaurante
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
    private $contrasena;

    /**
     * @var integer
     */
    private $telefono;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $direccionrestaurante;

    /**
     * @var integer
     */
    private $tiempopedidos;


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
     * @return Restaurante
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
     * Set contrasena
     *
     * @param string $contrasena
     *
     * @return Restaurante
     */
    public function setContrasena($contrasena)
    {
        $this->contrasena = $contrasena;

        return $this;
    }

    /**
     * Get contrasena
     *
     * @return string
     */
    public function getContrasena()
    {
        return $this->contrasena;
    }

    /**
     * Set telefono
     *
     * @param integer $telefono
     *
     * @return Restaurante
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;

        return $this;
    }

    /**
     * Get telefono
     *
     * @return integer
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Restaurante
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set direccionrestaurante
     *
     * @param string $direccionrestaurante
     *
     * @return Restaurante
     */
    public function setDireccionrestaurante($direccionrestaurante)
    {
        $this->direccionrestaurante = $direccionrestaurante;

        return $this;
    }

    /**
     * Get direccionrestaurante
     *
     * @return string
     */
    public function getDireccionrestaurante()
    {
        return $this->direccionrestaurante;
    }

    /**
     * Set tiempopedidos
     *
     * @param integer $tiempopedidos
     *
     * @return Restaurante
     */
    public function setTiempopedidos($tiempopedidos)
    {
        $this->tiempopedidos = $tiempopedidos;

        return $this;
    }

    /**
     * Get tiempopedidos
     *
     * @return integer
     */
    public function getTiempopedidos()
    {
        return $this->tiempopedidos;
    }
}

