<?php

namespace App\Controller\Compra\Informe\Egreso;

use App\Entity\Compra\ComEgreso;
use App\Entity\Compra\ComEgresoTipo;
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

class EgresoController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/compra/informe/egreso/egreso/lista", name="compra_informe_egreso_egreso_lista")
     */
    public function lista(Request $request)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $fechaDesde = (new \DateTime('now'))->format('Y-m-1');
        $fechaHasta = ((new \DateTime('now'))->modify('last day of this month'))->format('Y-m-d');
        $session->set('filtroComEgresoFechaDesde', null);
        $session->set('filtroComEgresoFechaHasta', null);
        $session->set('filtroComEgresoFiltrarPorFecha', null);
        $form = $this->createFormBuilder()
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
            ->add('txtNumero', NumberType::class, ['label' => 'Numero: ', 'required' => false, 'data' => $session->get('filtroNumero')])
            ->add('fechaDesde', DateType::class, ['label' => 'Fecha Desde', 'data' => new \DateTime($fechaDesde)])
            ->add('fechaHasta', DateType::class, ['label' => 'Fecha Hasta', 'data' => new \DateTime($fechaHasta)])
            ->add('filtrarPorFecha', CheckboxType::class, ['required' => false])
            ->add('cboEgresoTipo', EntityType::class, $em->getRepository(ComEgresoTipo::class)->llenarCombo())
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->get('btnFiltrar')->isClicked()) {
            if ($form->get('filtrarPorFecha')->getData() == true) {
                $session->set('filtroComEgresoFechaDesde', $form->get('fechaDesde')->getData()->format('Y-m-d'));
                $session->set('filtroComEgresoFechaHasta', $form->get('fechaHasta')->getData()->format('Y-m-d'));
                $session->set('filtroComEgresoFiltrarPorFecha', $form->get('filtrarPorFecha')->getData());
            }
            $session->set('filtroComCuentaPagarNumero', $form->get('txtNumero')->getData());
            $arCuentaPagarTipo = $form->get('cboEgresoTipo')->getData();
            if ($arCuentaPagarTipo) {
                $session->set('filtroComCuentaPagarTipo', $arCuentaPagarTipo->getCodigoCuentaPagarTipoPk());
            } else {
                $session->set('filtroComCuentaPagarTipo', null);
            }
        }
        if ($form->get('btnExcel')->isClicked()) {
            General::get()->setExportar($em->createQuery($em->getRepository(ComEgreso::class)->lista())->execute(), "Cuentas pagar");
        }
        $arEgresos = $paginator->paginate($em->getRepository(ComEgreso::class)->lista(), $request->query->getInt('page', 1), 20);
        return $this->render('compra/informe/egreso/lista.html.twig',
            ['arEgresos' => $arEgresos,
                'form' => $form->createView()]);
    }
}
