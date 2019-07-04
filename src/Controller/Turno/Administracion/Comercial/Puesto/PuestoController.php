<?php


namespace App\Controller\Turno\Administracion\Comercial\Puesto;

use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Entity\Transporte\TteCliente;
use App\Entity\Turno\TurCliente;
use App\Entity\Turno\TurPuesto;
use App\Form\Type\Turno\PuestoType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PuestoController extends Controller
{

    /**
     * @param Request $request
     * @return Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/turno/administracion/comercial/puesto/lista", name="turno_administracion_comercial_puesto_lista")
     */
    public function lista(Request $request)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
//            ->add('txtCodigoTercero', TextType::class, ['required' => false, 'data' => $session->get('filtroInvCodigoTercero'), 'attr' => ['class' => 'form-control']])
//            ->add('cboBodega', EntityType::class, $em->getRepository(InvBodega::class)->llenarCombo())
//            ->add('txtLote', TextType::class, array('required' => false))
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel', 'attr' => ['class' => 'btn btn-sm btn-default']))
            ->getForm();
        $form->handleRequest($request);
        $arPuestos = $paginator->paginate($em->getRepository(TurPuesto::class)->lista(), $request->query->getInt('page', 1), 30);
        return $this->render('turno/administracion/comercial/puesto/lista.html.twig', [
            'arPuestos' => $arPuestos,
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/turno/administracion/puesto/detalle/{id}", name="turno_administracion_comercial_puesto_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arPuesto = $em->getRepository(TurPuesto::class)->find($id);
        return $this->render('turno/administracion/comercial/puesto/detalle.html.twig', array(
            'arPuesto'=>$arPuesto
        ));
    }

}