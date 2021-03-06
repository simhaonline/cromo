<?php

namespace App\Controller\Inventario\Informe\Comercial\Pedido;

use App\Controller\MaestroController;
use App\Entity\Inventario\InvPedidoDetalle;
use App\Entity\Inventario\InvPedidoTipo;
use App\General\General;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class PedidoPendienteController extends MaestroController
{

    public $tipo = "proceso";
    public $proceso = "invi0011";

    /**
     * @param Request $request
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/inventario/informe/inventario/comercial/pedido/pendiente", name="inventario_informe_inventario_comercial_pedido_pendiente")
     */
    public function lista(Request $request, PaginatorInterface $paginator )
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('pedidoTipoRel', EntityType::class, $em->getRepository(InvPedidoTipo::class)->llenarCombo())
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel', 'attr' => ['class' => 'btn btn-sm btn-default']))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $arPedidoTipo = $form->get('pedidoTipoRel')->getData();
                if ($arPedidoTipo) {
                    /** @var  $arPedidoTipo InvPedidoTipo */
                    $arPedidoTipo = $arPedidoTipo->getCodigoPedidoTipoPk();
                }
                $session->set('filtroInvPedidoTipo', $arPedidoTipo);
            }
            if ($form->get('btnExcel')->isClicked()) {
                General::get()->setExportar($em->getRepository(InvPedidoDetalle::class)->pendientes()->getQuery()->getResult(), "Informe pedidos pendientes");
            }
        }
        $arPedidoDetalles = $paginator->paginate($em->getRepository(InvPedidoDetalle::class)->pendientes(), $request->query->getInt('page', 1), 30);
        return $this->render('inventario/informe/comercial/pedido/pedidosPendientes.twig', [
            'arPedidoDetalles' => $arPedidoDetalles,
            'form' => $form->createView()
        ]);
    }
}