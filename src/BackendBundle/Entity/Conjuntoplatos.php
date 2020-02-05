<?php

namespace BackendBundle\Entity;

/**
 * Conjuntoplatos
 */
class Conjuntoplatos
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \BackendBundle\Entity\Combo
     */
    private $combo;

    /**
     * @var \BackendBundle\Entity\Encargo
     */
    private $encargo;

    /**
     * @var \BackendBundle\Entity\Menu
     */
    private $menu;

    /**
     * @var \BackendBundle\Entity\Plato
     */
    private $plato;

    /**
     * @var integer
     */
    private $cantidad;


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
     * Set combo
     *
     * @param \BackendBundle\Entity\Combo $combo
     *
     * @return Conjuntoplatos
     */
    public function setCombo(\BackendBundle\Entity\Combo $combo = null)
    {
        $this->combo = $combo;

        return $this;
    }

    /**
     * Get combo
     *
     * @return \BackendBundle\Entity\Combo
     */
    public function getCombo()
    {
        return $this->combo;
    }

    /**
     * Set encargo
     *
     * @param \BackendBundle\Entity\Encargo $encargo
     *
     * @return Conjuntoplatos
     */
    public function setEncargo(\BackendBundle\Entity\Encargo $encargo = null)
    {
        $this->encargo = $encargo;

        return $this;
    }

    /**
     * Get encargo
     *
     * @return \BackendBundle\Entity\Encargo
     */
    public function getEncargo()
    {
        return $this->encargo;
    }

    /**
     * Set menu
     *
     * @param \BackendBundle\Entity\Menu $menu
     *
     * @return Conjuntoplatos
     */
    public function setMenu(\BackendBundle\Entity\Menu $menu = null)
    {
        $this->menu = $menu;

        return $this;
    }

    /**
     * Get menu
     *
     * @return \BackendBundle\Entity\Menu
     */
    public function getMenu()
    {
        return $this->menu;
    }

    /**
     * Set plato
     *
     * @param \BackendBundle\Entity\Plato $plato
     *
     * @return Conjuntoplatos
     */
    public function setPlato(\BackendBundle\Entity\Plato $plato = null)
    {
        $this->plato = $plato;

        return $this;
    }

    /**
     * Get plato
     *
     * @return \BackendBundle\Entity\Plato
     */
    public function getPlato()
    {
        return $this->plato;
    }

    /**
     * Set cantidad
     *
     * @param integer $cantidad
     *
     * @return Conjuntoplatos
     */
    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;

        return $this;
    }

    /**
     * Get cantidad
     *
     * @return integer
     */
    public function getCantidad()
    {
        return $this->cantidad;
    }
}

