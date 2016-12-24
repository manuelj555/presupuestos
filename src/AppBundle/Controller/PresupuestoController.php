<?php

namespace AppBundle\Controller;

use AppBundle\Entity\DescripcionPresupuesto;
use AppBundle\Entity\Presupuesto;
use AppBundle\Entity\Presupuestos;
use AppBundle\Model\PresupuestoManager;
use AppBundle\Report;
use Closure;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PresupuestoController extends Controller
{

    /**
     *
     * @return PresupuestoManager
     */
    protected function getManager()
    {
        return $this->get('presupuesto.manager');
    }

    /**
     *
     * @param type $page
     * @return type
     * @Template()
     */
    public function listadoAction($page)
    {
        $query = $this->getManager()
            ->getRepository()
            ->queryAll();

        $presupuestos = $this->get("knp_paginator")
            ->paginate($query, $page);

        return array(
            'presupuestos' => $presupuestos,
        );
    }

    /**
     * @Route("/new/", name="presupuesto_crear")
     */
    public function newAction()
    {
        $presupuesto = new Presupuesto();
        $em = $this->getDoctrine()->getManager();

        $em->persist($presupuesto);
        $em->flush();

        return $this->redirectToRoute('presupuesto_editar', [
            'id' => $presupuesto->getId(),
        ]);
    }

    /**
     * @Route("/edit/{id}/", name="presupuesto_editar")
     */
    public function edicionAction(Presupuesto $presupuesto)
    {
        return $this->render('presupuesto.html.twig', [
            'presupuesto' => $presupuesto,
        ]);
    }

    /**
     * @Route("/ajax/{id}/", name="presupuesto_obtener_datos")
     * @Method("GET")
     */
    public function getDataAction(Presupuesto $presupuesto)
    {
        $data = $this->get('serializer')->serialize($presupuesto, 'json', [
            'groups' => ['serializacion', 'default'],
        ]);

        return JsonResponse::fromJsonString($data);
    }

    /**
     * @Route("/save/{id}/", name="presupuesto_guardar")
     * @Route("/ajax/{id}/", name="presupuesto_guardar_datos")
     * @Method("POST")
     */
    public function saveAction(Request $request, Presupuesto $presupuesto)
    {
        $serializer = $this->get('serializer');
        $data = $serializer->decode($request->getContent(), 'json');

        $serializer->denormalize($data, Presupuesto::class, 'json', [
            'object_to_populate' => $presupuesto,
            'groups' => ['deserializacion', 'default'],
        ]);

        $em = $this->getDoctrine()->getManager();

        $rep = $em->getRepository(DescripcionPresupuesto::class);

        foreach ($data['descripciones'] as $item) {
            if (empty($item['id']) or !$desc = $rep->find($item['id'])) {
                $desc = new DescripcionPresupuesto($presupuesto);
            }

            $serializer->denormalize($item, DescripcionPresupuesto::class, null, [
                'object_to_populate' => $desc,
                'groups' => ['deserializacion', 'default'],
            ]);

            $em->persist($desc);
        }

        $em->persist($presupuesto);
        $em->flush();

        return JsonResponse::fromJsonString($serializer->serialize($presupuesto, 'json', [
            'groups' => ['serializacion', 'default'],
        ]));
    }

    /**
     * @ParamConverter("presupuesto", class="PresupuestoBundle:Presupuestos")
     */
    public function exportAction(Presupuestos $presupuesto)
    {
        return $this->prepareExport(function () use ($presupuesto) {
            Report\Presupuesto::excel($presupuesto);
        }, $presupuesto->getTitulo());
    }

    protected function prepareExport(Closure $function, $filename = 'report')
    {
        $response = new StreamedResponse($function);
        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', "attachment;filename=\"{$filename}.xlsx\"");
        $response->headers->set('Cache-Control', 'ax-age=0');

        return $response;
    }

}
