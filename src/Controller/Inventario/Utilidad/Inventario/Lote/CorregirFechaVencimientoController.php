<?php

namespace App\Controller\Inventario\Utilidad\Inventario\Lote;

use App\Controller\Estructura\ControllerListenerGeneral;
use App\Entity\Inventario\InvBodega;
use App\Entity\Inventario\InvLote;
use App\Entity\Inventario\InvMovimientoDetalle;
use App\Formato\Inventario\ExistenciaLote;
use App\General\General;
use App\Utilidades\Mensajes;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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

class CorregirFechaVencimientoController extends AbstractController
{
    protected $proceso = "0008";


    /**
     * @param Request $request
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/inventario/utilidad/inventario/lote/corregirfechavencimiento", name="inventario_utilidad_inventario_lote_corregirfechavencimiento")
     */
    public function lista(Request $request, PaginatorInterface $paginator )
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('txtCodigoItem', TextType::class, array('required' => false))
            ->add('txtNombreItem', TextType::class, array('required' => false , 'attr' => ['readonly' => 'readonly']))
            ->add('txtLote', TextType::class, ['required' => false])
            ->add('fechaVencimiento', DateType::class,
                [
                    'widget' => 'single_text',
                    'format' => 'yyyy-MM-dd',
                    'attr' => array('class' => 'date'),
                    'label' => 'Fecha vence: ',
                    'required' => false
                ])
            ->add('cboBodega', EntityType::class, $em->getRepository(InvBodega::class)->llenarCombo())
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('limiteRegistros', TextType::class, array('required' => false, 'data' => 100))
            ->setMethod('GET')
            ->getForm();
        $form->handleRequest($request);
        $raw = [
            'limiteRegistros' => $form->get('limiteRegistros')->getData()
        ];
        if ($form->isSubmitted()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $raw['filtros'] = $this->getFiltros($form);
            }
        }
        $arLotes = $paginator->paginate($em->getRepository(InvLote::class)->listaCorregirFechaVencimiento($raw), $request->query->getInt('page', 1), 100);
        return $this->render('inventario/utilidad/inventario/lote/corregirFechaVencimiento.html.twig', [
            'arLotes' => $arLotes,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/inventario/utilidad/inventario/lote/corregirfechavencimiento/corregir/{id}", name="inventario_utilidad_inventario_lote_corregirfechavencimiento_corregir")
     */
    public function cargarDatosDistribuidos(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arLoteOriginal = $em->find(InvLote::class, $id);
        $form = $this->createFormBuilder()
            ->add('btnGuardar', SubmitType::class, ['label' => 'Guardar', 'attr' => ['class' => 'btn btn-sm btn-primary']])
            ->add('fechaVencimiento', DateType::class, ['label' => 'Fecha vencimiento: ',  'required' => false, 'data' => $arLoteOriginal->getFechaVencimiento()])
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnGuardar')->isClicked()) {
                $fecha = $form->get('fechaVencimiento')->getData();
                if($fecha != $arLoteOriginal->getFechaVencimiento()) {
                    $arLotes = $em->getRepository(InvLote::class)->findBy(array('loteFk' => $arLoteOriginal->getLoteFk(), 'codigoItemFk' => $arLoteOriginal->getCodigoItemFk()));
                    foreach ($arLotes as $arLote) {
                        $arLote->setFechaVencimiento($fecha);
                        $em->persist($arLote);
                    }

                    $arMovimientoDetalles = $em->getRepository(InvMovimientoDetalle::class)->findBy(array('loteFk' => $arLoteOriginal->getLoteFk(), 'codigoItemFk' => $arLoteOriginal->getCodigoItemFk()));
                    foreach ($arMovimientoDetalles as $arMovimientoDetalle) {
                        $arMovimientoDetalle->setFechaVencimiento($fecha);
                        $em->persist($arMovimientoDetalle);
                    }
                }
            }
            $em->flush();
            Mensajes::success("La fecha de vencimiento fue corregida con existo");
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
        }
        return $this->render('inventario/utilidad/inventario/lote/corregirFechaVencimientoAplicar.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function getFiltros($form)
    {
        $filtro = [
            'codigoItem' => $form->get('txtCodigoItem')->getData(),
            'lote' => $form->get('txtLote')->getData(),
            'fechaVencimiento' => $form->get('fechaVencimiento')->getData() ? $form->get('fechaVencimiento')->getData()->format('Y-m-d') : null,
        ];

        $arBodega = $form->get('cboBodega')->getData();

        if (is_object($arBodega)) {
            $filtro['bodega'] = $arBodega->getCodigoBodegaPk();
        } else {
            $filtro['bodega'] = $arBodega;
        }

        return $filtro;

    }

}

