<?php
namespace App\Controller\Transporte\Movimiento\Transporte;

use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Controller\Estructura\MensajesController;
use App\Entity\Transporte\TteNovedad;
use App\Entity\Transporte\TteNovedadTipo;
use App\Utilidades\Estandares;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;

class NovedadController extends ControllerListenerGeneral
{
    protected $class= TteNovedad::class;
    protected $claseNombre = "TteNovedad";
    protected $modulo = "Transporte";
    protected $funcion = "Movimiento";
    protected $grupo = "Transporte";
    protected $nombre = "Novedad";

    /**
     * @Route("/transporte/movimiento/transporte/novedad/lista", name="transporte_movimiento_transporte_novedad_lista")
     */
    public function lista(Request $request)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('guia', TextType::class, ['label' => 'GUIA: ', 'required' => false, 'data' => $session->get('filtroNombreCondicion')])
            ->add('cboNovedadTipoRel', EntityType::class, $em->getRepository(TteNovedadTipo::class)->llenarCombo())
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $session->set('filtroNumeroGuia', $form->get('guia')->getData());
                $novedadTipo = $form->get('cboNovedadTipoRel')->getData();
                if ($novedadTipo != '') {
                    $session->set('filtroTteCodigoNovedadTipo', $form->get('cboNovedadTipoRel')->getData()->getCodigoNovedadTipoPk());
                } else {
                    $session->set('filtroTteCodigoNovedadTipo', null);
                }
            }
        }
        $arNovedad = $paginator->paginate($em->getRepository(TteNovedad::class)->lista(), $request->query->getInt('page', 1),10);
        return $this->render('transporte/movimiento/transporte/novedad/lista.html.twig',
            ['arNovedades' => $arNovedad,
            'form' => $form->createView()
        ]);
    }
    /**
     * @Route("/transporte/movimiento/transporte/novedad/detalle/{id}", name="transporte_movimiento_transporte_novedad_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arNovedad = $em->getRepository(TteNovedad::class)->find($id);
        return $this->render('transporte/movimiento/transporte/novedad/detalle.html.twig', array(
            'arNovedad' => $arNovedad,
        ));
    }
}

