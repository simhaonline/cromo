<?php

namespace App\Controller\Compra\Movimiento;

use App\Entity\Compra\ComCuentaPagar;
use App\Entity\Compra\ComCuentaPagarTipo;
use App\General\General;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class CuentaPagarController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/compra/movimiento/cuenta/pagar/lista", name="compra_movimiento_cuenta_pagar_lista")
     */
    public function lista(Request $request)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
            ->add('txtNumero', NumberType::class, ['label' => 'Numero: ', 'required' => false, 'data' => $session->get('filtroNumero')])
            ->add('cboCuentaPagarTipo', EntityType::class, $em->getRepository(ComCuentaPagarTipo::class)->llenarCombo())
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->get('btnFiltrar')->isClicked()) {
            $session->set('filtroComCuentaPagarNumero', $form->get('txtNumero')->getData());
            $arCuentaPagarTipo = $form->get('cboCuentaPagarTipo')->getData();
            if ($arCuentaPagarTipo) {
                $session->set('filtroComCuentaPagarTipo', $arCuentaPagarTipo->getCodigoCuentaPagarTipoPk());
            } else {
                $session->set('filtroComCuentaPagarTipo', null);
            }
        }
        if ($form->get('btnExcel')->isClicked()) {
            General::get()->setExportar($em->createQuery($em->getRepository(ComCuentaPagar::class)->lista())->execute(), "Cuentas pagar");
        }
        $arCuentaPagar = $paginator->paginate($em->getRepository(ComCuentaPagar::class)->lista(), $request->query->getInt('page', 1), 20);
        return $this->render('compra/movimiento/cuentaPagar/lista.html.twig',
            ['arCuentaPagar' => $arCuentaPagar,
                'form' => $form->createView()]);
    }
}
