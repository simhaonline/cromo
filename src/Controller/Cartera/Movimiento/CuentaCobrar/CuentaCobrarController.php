<?php

namespace App\Controller\Cartera\Movimiento\CuentaCobrar;

use App\Entity\Cartera\CarCuentaCobrar;
use App\Entity\Cartera\CarCuentaCobrarTipo;
use App\General\General;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CuentaCobrarController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/cartera/movimiento/cuenta/cobrar/lista", name="cartera_movimiento_cuenta_cobrar_lista")
     */
    public function lista(Request $request)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
            ->add('txtNumero', NumberType::class, ['label' => 'Numero: ', 'required' => false, 'data' => $session->get('filtroNumero')])
            ->add('cboCuentaCobrarTipo', EntityType::class, $em->getRepository(CarCuentaCobrarTipo::class)->llenarCombo())
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->get('btnFiltrar')->isClicked()) {
            $session->set('filtroCarCuentaCobrarNumero', $form->get('txtNumero')->getData());
            $arCuentaCobrarTipo = $form->get('cboCuentaCobrarTipo')->getData();
            if ($arCuentaCobrarTipo) {
                $session->set('filtroCarCuentaCobrarTipo', $arCuentaCobrarTipo->getCodigoCuentaCobrarTipoPk());
            } else {
                $session->set('filtroCarCuentaCobrarTipo', null);
            }
        }
        if ($form->get('btnExcel')->isClicked()) {
            General::get()->setExportar($em->createQuery($em->getRepository(CarCuentaCobrar::class)->lista())->execute(), "Cuentas cobrar");
        }
        $arCuentaCobrar = $paginator->paginate($em->getRepository(CarCuentaCobrar::class)->lista(), $request->query->getInt('page', 1),20);
        return $this->render('cartera/movimiento/cuentaCobrar/lista.html.twig',
            ['arCuentaCobrar' => $arCuentaCobrar,
            'form' => $form->createView()]);
    }
}

