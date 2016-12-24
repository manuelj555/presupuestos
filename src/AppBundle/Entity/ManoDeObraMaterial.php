<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ManoDeObraMaterial
 *
 * @ORM\Table(name="mano_de_obra_material")
 * @ORM\Entity
 */
class ManoDeObraMaterial
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ManoDeObra")
     */
    private $manoDeObra;

    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Material")
     */
    private $material;

    /**
     * @var float
     *
     * @ORM\Column(name="cantidad", type="float")
     */
    private $cantidad;

    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Unidad")
     */
    private $defaultUnidad;


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
     * Set manoDeObra
     *
     * @param string $manoDeObra
     * @return ManoDeObraMaterial
     */
    public function setManoDeObra($manoDeObra)
    {
        $this->manoDeObra = $manoDeObra;
    
        return $this;
    }

    /**
     * Get manoDeObra
     *
     * @return string 
     */
    public function getManoDeObra()
    {
        return $this->manoDeObra;
    }

    /**
     * Set material
     *
     * @param string $material
     * @return ManoDeObraMaterial
     */
    public function setMaterial($material)
    {
        $this->material = $material;
    
        return $this;
    }

    /**
     * Get material
     *
     * @return string 
     */
    public function getMaterial()
    {
        return $this->material;
    }

    /**
     * Set cantidad
     *
     * @param float $cantidad
     * @return ManoDeObraMaterial
     */
    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;
    
        return $this;
    }

    /**
     * Get cantidad
     *
     * @return float 
     */
    public function getCantidad()
    {
        return $this->cantidad;
    }

    /**
     * Set defaultUnidad
     *
     * @param \AppBundle\Entity\Unidades $defaultUnidad
     * @return ManoDeObraMaterial
     */
    public function setDefaultUnidad(\AppBundle\Entity\Unidades $defaultUnidad = null)
    {
        $this->defaultUnidad = $defaultUnidad;
    
        return $this;
    }

    /**
     * Get defaultUnidad
     *
     * @return \AppBundle\Entity\Unidades
     */
    public function getDefaultUnidad()
    {
        return $this->defaultUnidad;
    }
}