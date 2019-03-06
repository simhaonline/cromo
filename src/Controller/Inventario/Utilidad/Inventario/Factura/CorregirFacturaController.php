<?php

namespace App\Controller\Inventario\Utilidad\Inventario\Factura;

use App\Controller\Estructura\ControllerListenerGeneral;
use App\Entity\General\GenAsesor;
use App\Entity\Inventario\InvBodega;
use App\Entity\Inventario\InvLote;
use App\Entity\Inventario\InvMovimiento;
use App\Entity\Inventario\InvMovimientoDetalle;
use App\Form\Type\Inventario\CorreccionFacturaType;
use App\Formato\Inventario\ExistenciaLote;
use App\General\General;
use App\Utilidades\Mensajes;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CorregirFacturaController extends ControllerListenerGeneral
{
    protected $proceso = "0008";


    /**
     * @param Request $request
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/inventario/utilidad/inventario/factura/corregirfactura", name="inventario_utilidad_inventario_factura_corregirfactura")
     */
    public function lista(Request $request)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('txtNumeroFactura', TextType::class, array('data' => $session->get('filtroInvFacturaNumero')))
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $session->set('filtroInvFacturaNumero', $form->get('txtNumeroFactura')->getData());
            }
        }
        $arCorregirFacturas = $paginator->paginate($em->getRepository(InvMovimiento::class)->corregirFactura(), $request->query->getInt('page', 1), 100);
        return $this->render('inventario/utilidad/inventario/factura/corregirFactura.html.twig', [
            'arCorregirFacturas' => $arCorregirFacturas,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/inventario/utilidad/inventario/factura/corregirfactura/nuevo/{id}", name="inventario_utilidad_inventario_factura_corregirfactura_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $arFactura = $em->getRepository(InvMovimiento::class)->find($id);
        $form = $this->createForm(CorreccionFacturaType::class, $arFactura);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                if ($form->get('guardar')->isClicked()) {
                    $em->persist($arFactura);
                    $em->flush();
                }
            }
            return $this->redirect($this->generateUrl('inventario_utilidad_inventario_factura_corregirfactura'));
        }
        return $this->render('inventario/utilidad/inventario/factura/nuevo.html.twig', array(
            'arFactura' => $arFactura,
            'form' => $form->createView()));
    }
}

