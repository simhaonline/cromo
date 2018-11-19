<?php

namespace App\Controller\Inventario\Informe\Inventario\Movimiento;

use App\Controller\Estructura\ControllerListenerGeneral;
use App\Entity\Inventario\InvDocumento;
use App\Entity\Inventario\InvLote;
use App\Entity\Inventario\InvMovimientoDetalle;
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

class KardexController extends ControllerListenerGeneral
{
    protected $proceso = "0001";
    protected $procestoTipo= "I";
    protected $nombreProceso = "Kardex";
    protected $modulo = "Inventario";

    /**
     * @param Request $request
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/inventario/informe/inventario/movimiento/kardex", name="inventario_informe_inventario_movimiento_kardex")
     */
    public function lista(Request $request)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
            ->add('txtLote', TextType::class, ['required' => false, 'data' => $session->get('filtroInvKardexLote')])
            ->add('txtBodega', TextType::class, ['required' => false, 'data' => $session->get('filtroInvKardexLoteBodega')])
            ->add('cboDocumento', EntityType::class, $em->getRepository(InvDocumento::class)->llenarCombo())
            ->add('txtCodigoItem', TextType::class, ['required' => false, 'data' => $session->get('filtroInvItemCodigo'), 'attr' => ['class' => 'form-control']])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $session->set('filtroInvItemCodigo', $form->get('txtCodigoItem')->getData());
                $session->set('filtroInvKardexLote', $form->get('txtLote')->getData());
                $session->set('filtroInvKardexLoteBodega', $form->get('txtBodega')->getData());
                $documentoTipo = $form->get('cboDocumento')->getData();
                if($documentoTipo != ''){
                    $session->set('filtroInvCodigoDocumento', $form->get('cboDocumento')->getData()->getCodigoDocumentoPk());
                } else {
                    $session->set('filtroInvCodigoDocumento', null);
                }
            }
            if ($form->get('btnExcel')->isClicked()) {
                General::get()->setExportar($em->createQuery($em->getRepository(InvMovimientoDetalle::class)->listaKardex())->execute(), "Kardex");
            }
        }
        $arMovimientosDetalles = $paginator->paginate($em->getRepository(InvMovimientoDetalle::class)->listaKardex(), $request->query->getInt('page', 1), 30);
        return $this->render('inventario/informe/inventario/movimiento/kardex.html.twig', [
            'arMovimientosDetalles' => $arMovimientosDetalles,
            'form' => $form->createView()
        ]);
    }

}

