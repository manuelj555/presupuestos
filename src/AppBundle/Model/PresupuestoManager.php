<?php

namespace AppBundle\Model;

use Doctrine\ORM\EntityManager;
use AppBundle\Entity\PresupuestoRepository;
use AppBundle\Entity\Presupuestos;
use AppBundle\Form\PresupuestoForm;
use Symfony\Component\Form\FormFactoryInterface;

/**
 * Description of PresupuestoManager
 *
 * @author Manuel Aguirre <programador.manuel@gmail.com>
 */
class PresupuestoManager extends AbstractManager
{

    public function getForm($object = null)
    {
        $object || $object = new Presupuestos();
        return parent::getForm($object);
    }

    public function save(Presupuestos $presupuesto)
    {
        $total = 0;
        foreach ($presupuesto->getDescripciones() as $des) {
            $des->calculateSubtotal();
            $total += $des->getSubtotal();
            $this->em->persist($des);
        }

        $presupuesto->setTotal($total);
        $this->em->persist($presupuesto);

        $this->em->flush();
    }

    protected function getFormType()
    {
        return 'presupuesto';
    }

}
