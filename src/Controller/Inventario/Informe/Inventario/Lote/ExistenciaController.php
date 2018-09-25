<?php

namespace App\Controller\Inventario\Informe\Inventario\Lote;

use App\Entity\Inventario\InvLote;
use App\Formato\Inventario\ExistenciaLote;
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

class ExistenciaController extends Controller
{

    /**
     * @param Request $request
     * @return Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/inventario/informe/inventario/lote/existencia", name="inventario_informe_inventario_lote_existencia")
     */
    public function lista(Request $request)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('txtItem', TextType::class, ['required' => false, 'data' => $session->get('filtroInvLoteItem')])
            ->add('txtLote', TextType::class, ['required' => false, 'data' => $session->get('filtroInvLote')])
            ->add('txtBodega', TextType::class, ['required' => false, 'data' => $session->get('filtroInvLoteBodega')])
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
            ->add('btnPdf', SubmitType::class, array('label' => 'Pdf'))
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $session->set('filtroInvLoteItem', $form->get('txtItem')->getData());
                $session->set('filtroInvLote', $form->get('txtLote')->getData());
                $session->set('filtroInvLoteBodega', $form->get('txtBodega')->getData());
            }
            if ($form->get('btnExcel')->isClicked()) {
                General::get()->setExportar($em->createQuery($em->getRepository(InvLote::class)->existencia())->execute(), "Existencia");
            }
            if ($form->get('btnPdf')->isClicked()) {
                $formato = new ExistenciaLote();
                $formato->Generar($em);
            }
        }
        $arLotes = $paginator->paginate($em->getRepository(InvLote::class)->existencia(), $request->query->getInt('page', 1), 30);
        return $this->render('inventario/informe/inventario/lote/existencia.html.twig', [
            'arLotes' => $arLotes,
            'form' => $form->createView()
        ]);
    }

}

