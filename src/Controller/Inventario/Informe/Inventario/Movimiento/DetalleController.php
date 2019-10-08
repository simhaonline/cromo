<?php

namespace App\Controller\Inventario\Informe\Inventario\Movimiento;

use App\Controller\Estructura\ControllerListenerGeneral;
use App\Entity\Inventario\InvBodega;
use App\Entity\Inventario\InvDocumento;
use App\Entity\Inventario\InvLote;
use App\Entity\Inventario\InvMovimientoDetalle;
use App\General\General;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
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

class DetalleController extends AbstractController
{
    protected $proceso = "0002";
    protected $procestoTipo = "I";
    protected $nombreProceso = "MovimientoDetalle";
    protected $modulo = "Inventario";

    /**
     * @param Request $request
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/inventario/informe/inventario/movimiento/detalle", name="inventario_informe_inventario_movimiento_detalle")
     */
    public function lista(Request $request, PaginatorInterface $paginator )
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
            ->add('txtLote', TextType::class, ['required' => false, 'data' => $session->get('filtroInvKardexLote')])
            ->add('cboBodega', EntityType::class, $em->getRepository(InvBodega::class)->llenarCombo())
            ->add('cboDocumento', EntityType::class, $em->getRepository(InvDocumento::class)->llenarCombo())
            ->add('txtCodigoItem', TextType::class, ['required' => false, 'data' => $session->get('filtroInvItemCodigo'), 'attr' => ['class' => 'form-control']])
            ->add('filtrarFecha', CheckboxType::class, array('required' => false, 'data' => $session->get('filtroFecha')))
            ->add('fechaDesde', DateType::class, ['label' => 'Fecha desde: ', 'required' => false, 'data' => date_create($session->get('filtroInvMovimientoFechaDesde'))])
            ->add('fechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false, 'data' => date_create($session->get('filtroInvMovimientoFechaHasta'))])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $session->set('filtroInvItemCodigo', $form->get('txtCodigoItem')->getData());
                $session->set('filtroInvLote', $form->get('txtLote')->getData());
                $session->set('filtroInvMovimientoFechaDesde', $form->get('fechaDesde')->getData()->format('Y-m-d'));
                $session->set('filtroInvMovimientoFechaHasta', $form->get('fechaHasta')->getData()->format('Y-m-d'));
                $arBodega = $form->get('cboBodega')->getData();
                if ($arBodega != '') {
                    $session->set('filtroInvBodega', $form->get('cboBodega')->getData()->getCodigoBodegaPk());
                } else {
                    $session->set('filtroInvBodega', null);
                }
                $documentoTipo = $form->get('cboDocumento')->getData();
                if ($documentoTipo != '') {
                    $session->set('filtroInvCodigoDocumento', $form->get('cboDocumento')->getData()->getCodigoDocumentoPk());
                } else {
                    $session->set('filtroInvCodigoDocumento', null);
                }
            }
            if ($form->get('btnExcel')->isClicked()) {
                General::get()->setExportar($em->getRepository(InvMovimientoDetalle::class)->informeDetalles()->getQuery()->getResult(), "Detalles");
            }
        }
        $arMovimientosDetalles = $paginator->paginate($em->getRepository(InvMovimientoDetalle::class)->informeDetalles(), $request->query->getInt('page', 1), 100);
        return $this->render('inventario/informe/inventario/movimiento/detalle.html.twig', [
            'arMovimientosDetalles' => $arMovimientosDetalles,
            'form' => $form->createView()
        ]);
    }

}

