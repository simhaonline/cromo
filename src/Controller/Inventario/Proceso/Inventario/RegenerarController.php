<?php

namespace App\Controller\Inventario\Proceso\Inventario;

use App\Entity\Inventario\InvLote;
use App\Entity\Inventario\InvMovimientoDetalle;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class RegenerarController extends Controller
{

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @Route("/inventario/proceso/inventario/regenerar/lista", name="inventario_proceso_inventario_regenerar_lista")
     */
    public function lista(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('btnRegenerarExistencias', SubmitType::class, ['label' => 'Regenerar kardex'])
            ->add('btnRegenerarCostos', SubmitType::class, ['label' => 'Regenerar costos'])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnRegenerarExistencias')->isClicked()) {
                $em->getRepository(InvMovimientoDetalle::class)->regenerarExistencia();
            }
            if ($form->get('btnRegenerarCostos')->isClicked()) {
                $em->getRepository(InvMovimientoDetalle::class)->regenerarCosto();
            }
        }
        return $this->render('inventario/proceso/regenerar.html.twig', [
            'form' => $form->createView()
        ]);
    }

}