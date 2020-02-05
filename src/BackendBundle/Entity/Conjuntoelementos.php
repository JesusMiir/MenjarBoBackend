<?php

namespace BackendBundle\Entity;

/**
 * Conjuntoelementos
 */
class Conjuntoelementos
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
     * @var \BackendBundle\Entity\Plato
     */
    private $plato;


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
     * @return Conjuntoelementos
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
     * Set plato
     *
     * @param \BackendBundle\Entity\Plato $plato
     *
     * @return Conjuntoelementos
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
}

