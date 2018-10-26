<?php

namespace App\Controller\Compra\Informe\CuentaPagar;

use App\Entity\Compra\ComCuentaPagar;
use App\Entity\Compra\ComCuentaPagarTipo;
use App\General\General;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
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
     * @Route("/compra/informe/cuenta/pagar/pendiente", name="compra_informe_cuenta_pagar_pendiente")
     */
    public function lista(Request $request)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $fechaDesde = (new \DateTime('now'))->format('Y-m-1');
        $fechaHasta = ((new \DateTime('now'))->modify('last day of this month'))->format('Y-m-d');
        $session->set('filtroComPendienteFechaDesde', null);
        $session->set('filtroComPendienteFechaHasta', null);
        $session->set('filtroComPendienteFiltrarPorFecha', null);
        $form = $this->createFormBuilder()
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
            ->add('txtNumero', NumberType::class, ['label' => 'Numero: ', 'required' => false, 'data' => $session->get('filtroNumero')])
            ->add('fechaDesde', DateType::class, ['label' => 'Fecha Desde', 'data' => new \DateTime($fechaDesde)])
            ->add('fechaHasta', DateType::class, ['label' => 'Fecha Hasta', 'data' => new \DateTime($fechaHasta)])
            ->add('filtrarPorFecha', CheckboxType::class, ['required' => false])
            ->add('cboCuentaPagarTipo', EntityType::class, $em->getRepository(ComCuentaPagarTipo::class)->llenarCombo())
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->get('btnFiltrar')->isClicked()) {
            if ($form->get('filtrarPorFecha')->getData() == true) {
                $session->set('filtroComPendienteFechaDesde', $form->get('fechaDesde')->getData()->format('Y-m-d'));
                $session->set('filtroComPendienteFechaHasta', $form->get('fechaHasta')->getData()->format('Y-m-d'));
                $session->set('filtroComPendienteFiltrarPorFecha', $form->get('filtrarPorFecha')->getData());
            }
            $session->set('filtroComCuentaPagarNumero', $form->get('txtNumero')->getData());
            $arCuentaPagarTipo = $form->get('cboCuentaPagarTipo')->getData();
            if ($arCuentaPagarTipo) {
                $session->set('filtroComCuentaPagarTipo', $arCuentaPagarTipo->getCodigoCuentaPagarTipoPk());
            } else {
                $session->set('filtroComCuentaPagarTipo', null);
            }
        }
        if ($form->get('btnExcel')->isClicked()) {
            General::get()->setExportar($em->createQuery($em->getRepository(ComCuentaPagar::class)->pendiente())->execute(), "Cuentas pagar");
        }
        $arCuentaPagar = $paginator->paginate($em->getRepository(ComCuentaPagar::class)->pendiente(), $request->query->getInt('page', 1), 20);
        return $this->render('compra/informe/cuentaPagar/lista.html.twig',
            ['arCuentaPagar' => $arCuentaPagar,
                'form' => $form->createView()]);
    }
}
