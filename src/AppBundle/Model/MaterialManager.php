<?php

namespace AppBundle\Model;

use AppBundle\Entity\Material;
use AppBundle\Form\MaterialType;

/**
 * @author Manuel Aguirre <programador.manuel@gmail.com>
 */
class MaterialManager extends AbstractManager
{

    public function persist(Material $material)
    {
        $this->em->persist($material);

        foreach ($material->getUnidades() as $unidad) {
            $this->em->persist($unidad);
        }

        $this->em->flush();
    }

    protected function getFormType()
    {
        return new MaterialType();
    }

}
