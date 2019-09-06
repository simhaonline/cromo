<?php

namespace App\Controller\Inventario\Informe\Comercial\Precio;

use App\Entity\Inventario\InvLote;
use App\Entity\Inventario\InvPrecioDetalle;
use App\General\General;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class PrecioDetalleController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/inventario/informe/inventario/comercial/precio/precioDetalle", name="inventario_informe_inventario_comercial_precio_detalle")
     */
    public function lista(Request $request)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('txtItemCodigo', TextType::class, array('data' => $session->get('filtroInvInformeItemCodigo'), 'required' => false))
            ->add('txtItemNombre', TextType::class, array('data' => $session->get('filtroInvInformeItemNombre'), 'required' => false , 'attr' => ['readonly' => 'readonly']))
            ->add('txtNombreListaPrecio', TextType::class, ['required' => false, 'data' => $session->get('filtroInvInformeNombreListaPrecio')])
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel', 'attr' => ['class' => 'btn btn-sm btn-default']))
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $session->set('filtroInvInformeItemCodigo', $form->get('txtItemCodigo')->getData());
                $session->set('filtroInvInformeItemNombre', $form->get('txtItemNombre')->getData());
                $session->set('filtroInvNombreListPrecio', $form->get('txtNombreListaPrecio')->getData());
            }
            if ($form->get('btnExcel')->isClicked()) {
                General::get()->setExportar($em->getRepository(InvPrecioDetalle::class)->informePrecioDetalle()->getQuery()->getResult(), "Informe precio detalles");
            }
        }
        $arPrecioDetalle = $paginator->paginate($em->getRepository(InvPrecioDetalle::class)->informePrecioDetalle(), $request->query->getInt('page', 1), 30);
        return $this->render('inventario/informe/inventario/precio/precioDetalle.html.twig', [
            'arPrecioDetalles' => $arPrecioDetalle,
            'form' => $form->createView()
        ]);
    }

}

