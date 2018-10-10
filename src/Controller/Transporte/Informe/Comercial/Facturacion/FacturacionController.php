<?php

namespace App\Controller\Transporte\Informe\Comercial\Facturacion;

use App\Controller\Estructura\MensajesController;
use App\Entity\Transporte\TteFactura;
use App\Entity\Transporte\TteFacturaTipo;
use App\Formato\Transporte\FacturaInforme;
use App\Formato\Transporte\ListaFactura;
use App\General\General;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class FacturacionController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/transporte/informe/comercial/facturacion/factura", name="transporte_informe_comercial_facturacion_factura")
     */
    public function lista(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $session = new Session();
        $form = $this->createFormBuilder()
            ->add('btnPdf', SubmitType::class, array('label' => 'Pdf'))
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
            ->add('filtrarFecha', CheckboxType::class, array('required' => false, 'data' => $session->get('filtroFecha')))
            ->add('fechaDesde', DateType::class, ['label' => 'Fecha desde: ',  'required' => false, 'data' => date_create($session->get('filtroFechaDesde'))])
            ->add('fechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false, 'data' => date_create($session->get('filtroFechaHasta'))])
            ->add('txtCodigo', TextType::class, ['required' => false, 'data' => $session->get('filtroTteFacturaCodigo')])
            ->add('txtNumero', TextType::class, ['required' => false, 'data' => $session->get('filtroTteFacturaNumero')])
            ->add('txtCodigoCliente', TextType::class, ['required' => false, 'data' => $session->get('filtroTteCodigoCliente'), 'attr' => ['class' => 'form-control']])
            ->add('txtNombreCorto', TextType::class, ['required' => false, 'data' => $session->get('filtroTteNombreCliente'), 'attr' => ['class' => 'form-control', 'readonly' => 'reandonly']])
            ->add('chkEstadoAprobado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'data' => $session->get('filtroTteFacturaEstadoAprobado'), 'required' => false])
            ->add('chkEstadoAnulado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'data' => $session->get('filtroTteFacturaEstadoAnulado'), 'required' => false])
            ->add('cboFacturaTipoRel', EntityType::class, $em->getRepository(TteFacturaTipo::class)->llenarCombo())
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $session->set('filtroTteFacturaEstadoAprobado', $form->get('chkEstadoAprobado')->getData());
                $session->set('filtroTteFacturaEstadoAnulado', $form->get('chkEstadoAnulado')->getData());
                $session->set('filtroTteFacturaNumero', $form->get('txtNumero')->getData());
                $session->set('filtroTteFacturaCodigo', $form->get('txtCodigo')->getData());
                $session->set('filtroFechaDesde',  $form->get('fechaDesde')->getData()->format('Y-m-d'));
                $session->set('filtroFechaHasta', $form->get('fechaHasta')->getData()->format('Y-m-d'));
                $session->set('filtroFecha', $form->get('filtrarFecha')->getData());

                if ($form->get('txtCodigoCliente')->getData() != '') {
                    $session->set('filtroTteCodigoCliente', $form->get('txtCodigoCliente')->getData());
                    $session->set('filtroTteNombreCliente', $form->get('txtNombreCorto')->getData());
                } else {
                    $session->set('filtroTteCodigoCliente', null);
                    $session->set('filtroTteNombreCliente', null);
                }
                $arFacturaTipo = $form->get('cboFacturaTipoRel')->getData();
                if ($arFacturaTipo) {
                    $session->set('filtroTteFacturaCodigoFacturaTipo', $arFacturaTipo->getCodigoFacturaTipoPk());
                } else {
                    $session->set('filtroTteFacturaCodigoFacturaTipo', null);
                }
            }
            if ($form->get('btnPdf')->isClicked()) {
                $formato = new FacturaInforme();
                $formato->Generar($em);
            }
            if ($form->get('btnExcel')->isClicked()) {
                General::get()->setExportar($em->createQuery($em->getRepository(TteFactura::class)->listaInforme())->execute(), "Facturas");
            }
        }
        $arFacturas = $paginator->paginate($this->getDoctrine()->getRepository(TteFactura::class)->listaInforme(), $request->query->getInt('page', 1), 500);
        return $this->render('transporte/informe/comercial/facturacion/factura.html.twig', [
            'arFacturas' => $arFacturas,
            'form' => $form->createView() ]);
    }
}

