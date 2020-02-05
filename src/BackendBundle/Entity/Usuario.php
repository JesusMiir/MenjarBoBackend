<?php

namespace BackendBundle\Entity;

/**
 * Usuario
 */
class Usuario
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
    private $apellidos;

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
    private $direccioncasa;


    /**
     * @var \DateTime
     */
    private $createat;


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
     * @return Usuario
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
     * Set apellidos
     *
     * @param string $apellidos
     *
     * @return Usuario
     */
    public function setApellidos($apellidos)
    {
        $this->apellidos = $apellidos;

        return $this;
    }

    /**
     * Get apellidos
     *
     * @return string
     */
    public function getApellidos()
    {
        return $this->apellidos;
    }

    /**
     * Set contrasena
     *
     * @param string $contrasena
     *
     * @return Usuario
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
     * @return Usuario
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
     * @return Usuario
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
     * Set direccioncasa
     *
     * @param string $direccioncasa
     *
     * @return Usuario
     */
    public function setDireccioncasa($direccioncasa)
    {
        $this->direccioncasa = $direccioncasa;

        return $this;
    }

    /**
     * Get direccioncasa
     *
     * @return string
     */
    public function getDireccioncasa()
    {
        return $this->direccioncasa;
    }


    /**
     * Set createat
     *
     * @param \DateTime $createat
     *
     * @return Usuario
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

}

