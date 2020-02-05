<?php


namespace App\Controller\Turno\Informe\Comercial;

use App\Entity\Inventario\InvPedidoDetalle;
use App\Entity\Inventario\InvPedidoTipo;
use App\Entity\Turno\TurCliente;
use App\Entity\Turno\TurPedido;
use App\Entity\Turno\TurPedidoDetalle;
use App\General\General;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class PedidoDetalleController extends Controller
{
    /**
     * @Route("/turno/informe/comercial/pedidoDetalle/lista", name="turno_informe_comercial_pedidoDetalle_lista")
     */
    public function lista(Request $request)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('codigoClienteFk', TextType::class, array('required' => false))
            ->add('codigoPedidoDetallePk', TextType::class, array('required' => false))
            ->add('codigoPuestoFk', TextType::class, array('required' => false))
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnExcel', SubmitType::class, ['label' => 'Excel', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('limiteRegistros', TextType::class, array('required' => false, 'data' => 100))
            ->getForm();
        $form->handleRequest($request);
        $raw = [
            'limiteRegistros' => $form->get('limiteRegistros')->getData()
        ];
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $raw['filtros'] = $this->getFiltros($form);
            }
            if ($form->get('btnExcel')->isClicked()) {
                set_time_limit(0);
                ini_set("memory_limit", -1);
                General::get()->setExportar($em->getRepository(TurPedidoDetalle::class)->informe($raw), "Pedidos detalle");
            }
        }
        $arPedidosDetalles = $paginator->paginate($em->getRepository(TurPedidoDetalle::class)->informe($raw), $request->query->getInt('page', 1), 30);
        return $this->render('turno/informe/comercial/pedidoDetalle.html.twig', [
            'arPedidosDetalles' => $arPedidosDetalles,
            'form' => $form->createView()
        ]);
    }

    public function getFiltros($form)
    {
        $filtro = [
            'codigoCliente' => $form->get('codigoClienteFk')->getData(),
            'codigoPedidoDetalle' => $form->get('codigoPedidoDetallePk')->getData(),
            'codigoPuesto' => $form->get('codigoPuestoFk')->getData(),

        ];

        return $filtro;

    }
}