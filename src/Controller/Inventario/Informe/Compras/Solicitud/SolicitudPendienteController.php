<?php

namespace App\Controller\Inventario\Informe\Compras\Solicitud;

use App\Entity\Inventario\InvSolicitud;
use App\Entity\Inventario\InvSolicitudTipo;
use App\General\General;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;

class SolicitudPendienteController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/inventario/informe/inventario/compras/solicitudPendientes", name="inventario_informe_inventario_compras_solicitudPendientes")
     */
    public function lista(Request $request)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('solicitudTipoRel',EntityType::class,[
                'class' => 'App\Entity\Inventario\InvSolicitudTipo',
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('e')
                        ->orderBy('e.codigoSolicitudTipoPk');
                },
                'choice_label' => 'nombre',
                'required' => false,
                'data' => '',
                'empty_data' => '',
                'placeholder' => 'TODOS'
            ])
            ->add('btnExcel', SubmitType::class, ['label' => 'Excel','attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $arSolicitudTipo = $form->get('solicitudTipoRel')->getData();
                if($arSolicitudTipo){
                    /** @var  $arSolicitudTipo InvSolicitudTipo */
                    $arSolicitudTipo = $arSolicitudTipo->getCodigoSolicitudTipoPk();
                }
                $session->set('filtroInvInformeSolicitudSolicitudTipo', $arSolicitudTipo);
            }
            if ($form->get('btnExcel')->isClicked()) {
                General::get()->setExportar($em->getRepository(InvSolicitud::class)->pendientes()->execute(), "Informe solicitudes pendientes");
            }
        }
        $arSolicitudDetalles = $paginator->paginate($em->getRepository(InvSolicitud::class)->pendientes(), $request->query->getInt('page', 1), 30);
        return $this->render('inventario/informe/inventario/solicitud/lista.html.twig', [
            'arSolicitudDetalles' => $arSolicitudDetalles,
            'form' => $form->createView()
        ]);
    }
}

