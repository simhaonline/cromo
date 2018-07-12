<?php

namespace App\Controller\Inventario\Informe\Inventario\Lote;

use App\Entity\Inventario\InvLote;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\TextType;
class ExistenciaController extends Controller
{
   /**
    * @Route("/inventario/informe/inventario/lote/existencia", name="inventario_informe_inventario_lote_existencia")
    */
    public function lista(Request $request)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $session->set('filtroInvSolicitudNumero', $form->get('txtNumero')->getData());
                $session->set('filtroInvSolicitudEstadoAprobado', $form->get('chkEstadoAprobado')->getData());
                $solicitudTipo = $form->get('cboSolicitudTipoRel')->getData();
                if($solicitudTipo != ''){
                    $session->set('filtroInvSolicitudCodigoSolicitudTipo', $form->get('cboSolicitudTipoRel')->getData()->getCodigoSolicitudTipoPk());
                } else {
                    $session->set('filtroInvSolicitudCodigoSolicitudTipo', null);
                }
            }
            if($form->get('btnEliminar')->isClicked()){
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository(InvSolicitud::class)->eliminar($arrSeleccionados);
            }
        }
        $arLotes = $paginator->paginate($em->getRepository(InvLote::class)->existencia(), $request->query->getInt('page', 1), 30);
        return $this->render('inventario/informe/inventario/lote/existencia.html.twig', [
            'arLotes' => $arLotes,
            'form' => $form->createView()
        ]);
    }

}

