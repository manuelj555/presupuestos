<?php

namespace AppBundle\Model;

use Doctrine\ORM\EntityManager;
use AppBundle\Entity\ManoDeObraRepository;
use AppBundle\Entity\ManosDeObra;
use AppBundle\Form\ManoDeObraForm;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

/**
 * Description of ManoObraManager
 *
 * @author Manuel Aguirre <programador.manuel@gmail.com>
 */
class ManoDeObraManager extends AbstractManager
{

    /**
     * @deprecated usar getRepository directamente
     * @return ManoDeObraRepository
     */
    public function getManoDeObraRepository()
    {
        return $this->getRepository();
    }

    public function getAllManosDeObra()
    {
        return $this->getManoDeObraRepository()
                        ->queryAllManosDeObra()
                        ->getArrayResult();
    }

    public function persist(ManosDeObra $manoDeObra)
    {
        $this->em->persist($manoDeObra);

        foreach ($manoDeObra->getMateriales() as $material) {
//            $material->setManoDeObra($manoDeObra);
            $this->em->persist($material);
        }

        $this->em->flush();
    }

    protected function getFormType()
    {
        return new ManoDeObraForm();
    }

}
