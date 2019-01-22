<?php

namespace App\Controller\Transporte\Informe\Comercial\Guia;

use App\Entity\Transporte\TteGuia;
use App\Entity\Transporte\TteGuiaTipo;
use App\Formato\Transporte\PendienteDespachoRuta;
use App\Formato\Transporte\ProduccionCliente;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use App\General\General;
class ProduccionClienteController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/transporte/informe/comercial/guia/produccion", name="transporte_informe_comercial_guia_produccion")
     */
    public function lista(Request $request)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $fecha = new \DateTime('now');
        $form = $this->createFormBuilder()
            ->add('btnPdf', SubmitType::class, array('label' => 'Pdf'))
            ->add('txtNombreCorto', TextType::class, ['label' => 'Nombre: ', 'required' => false, 'data' => $session->get('filtroTteNombreCliente')])
            ->add('txtIdentificacion', NumberType::class, ['label' => 'Nombre: ', 'required' => false, 'data' => $session->get('filtroTteIdentificacionCliente')])
            ->add('fechaDesde', DateType::class, ['label' => 'Fecha desde: ', 'required' => false, 'data' => $fecha])
            ->add('fechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false, 'data' => $fecha])
            ->add('chkMercanciaPeligrosa', CheckboxType::class, array('label' => ' ','required' => false, 'data' => $session->get('filtroMercanciaPeligrosa')))
            ->add('cboGuiaTipoRel', EntityType::class, $em->getRepository(TteGuiaTipo::class)->llenarCombo())
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
            ->add('btnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
            ->getForm();
        $form->handleRequest($request);
        $arGuias = null;
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                if ($form->get('btnFiltrar')->isClicked()) {
                    $session->set('filtroTteNombreCliente', $form->get('txtNombreCorto')->getData());
                    $session->set('filtroTteIdentificacionCliente', $form->get('txtIdentificacion')->getData());
                    $arGuiaTipo = $form->get('cboGuiaTipoRel')->getData();
                    if ($arGuiaTipo) {
                        $session->set('filtroTteGuiaCodigoGuiaTipo', $arGuiaTipo->getCodigoGuiaTipoPk());
                    } else {
                        $session->set('filtroTteGuiaCodigoGuiaTipo', null);
                    }
                    $fechaDesde = $form->get('fechaDesde')->getData()->format('Y-m-d');
                    $fechaHasta = $form->get('fechaHasta')->getData()->format('Y-m-d');
                    $session->set('filtroMercanciaPeligrosa', $form->get('chkMercanciaPeligrosa')->getData());
                    $queryBuilder = $this->getDoctrine()->getRepository(TteGuia::class)->informeProduccionCliente($fechaDesde, $fechaHasta);
                    $arGuias = $queryBuilder->getQuery()->getResult();
                    $arGuias = $paginator->paginate($arGuias, $request->query->getInt('page', 1), 1000);
                }
                if ($form->get('btnExcel')->isClicked()) {
                    $fechaDesde = $form->get('fechaDesde')->getData()->format('Y-m-d');
                    $fechaHasta = $form->get('fechaHasta')->getData()->format('Y-m-d');
                    General::get()->setExportar($em->createQuery($this->getDoctrine()->getRepository(TteGuia::class)->informeProduccionCliente($fechaDesde, $fechaHasta))->execute(), "Produccion_Cliente");
                }
            }
            $fechaDesde = $form->get('fechaDesde')->getData()->format('Y-m-d');
            $fechaHasta = $form->get('fechaHasta')->getData()->format('Y-m-d');
            if ($form->get('btnPdf')->isClicked()) {
                $formato = new ProduccionCliente();
                $formato->Generar($em, $fechaDesde, $fechaHasta);
            }
        }
        return $this->render('transporte/informe/comercial/guia/informeProduccion.html.twig', [
            'arGuias' => $arGuias,
            'form' => $form->createView()]);
    }


}

