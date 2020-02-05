<?php

namespace BackendBundle\Entity;

/**
 * Conjuntoingredientes
 */
class Conjuntoingredientes
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \BackendBundle\Entity\Ingrediente
     */
    private $ingrediente;

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
     * Set ingrediente
     *
     * @param \BackendBundle\Entity\Ingrediente $ingrediente
     *
     * @return Conjuntoingredientes
     */
    public function setIngrediente(\BackendBundle\Entity\Ingrediente $ingrediente = null)
    {
        $this->ingrediente = $ingrediente;

        return $this;
    }

    /**
     * Get ingrediente
     *
     * @return \BackendBundle\Entity\Ingrediente
     */
    public function getIngrediente()
    {
        return $this->ingrediente;
    }

    /**
     * Set plato
     *
     * @param \BackendBundle\Entity\Plato $plato
     *
     * @return Conjuntoingredientes
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

