<?php

namespace App\Controller\Inventario\Proceso\Inventario;

use App\Entity\Inventario\InvLote;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class RegenerarKardexController extends Controller
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
            ->add('btnRegenerarKardex', SubmitType::class, ['label' => 'Regenerar kardex'])
            ->add('btnRegenerarCostos', SubmitType::class, ['label' => 'Regenerar costos'])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnRegenerarKardex')->isClicked()) {
                $em->getRepository(InvLote::class)->regenerarKardex();
            }
            if ($form->get('btnRegenerarCostos')->isClicked()) {

            }
        }
        return $this->render('inventario/proceso/regenerar.html.twig', [
        ]);
    }

}