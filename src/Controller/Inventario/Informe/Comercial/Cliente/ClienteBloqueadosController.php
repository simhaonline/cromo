<?php


namespace App\Controller\Inventario\Informe\Comercial\Cliente;


use App\Entity\Inventario\InvPedidoDetalle;
use App\Entity\Inventario\InvPedidoTipo;
use App\Entity\Inventario\InvTercero;
use App\General\General;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class ClienteBloqueadosController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/inventario/informe/comercial/cliente/bloqueados", name="inventario_informe_comercial_cliente_bloqueados")
     */
    public function lista(Request $request)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('txtCodigoTercero', TextType::class, ['required' => false, 'data' => $session->get('filtroInvCodigoTercero'), 'attr' => ['class' => 'form-control']])
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel', 'attr' => ['class' => 'btn btn-sm btn-default']))
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $session->set('filtroInvTerceroInformeCodigoTercero', $form->get('txtCodigoTercero')->getData());
            }
            if ($form->get('btnExcel')->isClicked()) {
                General::get()->setExportar($em->getRepository(InvTercero::class)->informeBloqueadosExcel()->getQuery()->getResult(), "Clientes bloqueados");
            }
        }
        $arClienteBloqueados = $paginator->paginate($em->getRepository(InvTercero::class)->informeBloqueados(), $request->query->getInt('page', 1), 30);
        return $this->render('inventario/informe/cliente/bloqueados.html.twig', [
            'arClienteBloqueados' => $arClienteBloqueados,
            'form' => $form->createView()
        ]);
    }
}