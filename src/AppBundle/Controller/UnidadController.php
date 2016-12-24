<?php

/*
 * This file is part of the Manuel Aguirre Project.
 *
 * (c) Manuel Aguirre <programador.manuel@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Material;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class UnidadController extends Controller
{

    /**
     * @ParamConverter("material", class="PresupuestoBundle:Material")
     * @Template()
     */
    public function unidadesSelectAction(Material $material)
    {

        $em = $this->getDoctrine()->getManager();
        
        $data = $em->getRepository('PresupuestoBundle:Unidades')
                    ->getForMaterialQuery($material)
                    ->getArrayResult();

        return JsonResponse::create($data);
    }

}
