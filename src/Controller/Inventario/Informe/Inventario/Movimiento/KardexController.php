<?php

namespace App\Controller\Inventario\Informe\Inventario\Movimiento;

use App\Controller\Estructura\ControllerListenerGeneral;
use App\Entity\Inventario\InvDocumento;
use App\Entity\Inventario\InvDocumentoTipo;
use App\Entity\Inventario\InvInformeDisponible;
use App\Entity\Inventario\InvInformeKardex;
use App\Entity\Inventario\InvLote;
use App\Entity\Inventario\InvMovimientoDetalle;
use App\General\General;
use App\Utilidades\Mensajes;
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

class KardexController extends AbstractController
{
    protected $proceso = "0001";
    protected $procestoTipo = "I";
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
    public function lista(Request $request, PaginatorInterface $paginator )
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
            ->add('txtLote', TextType::class, ['required' => false, 'data' => $session->get('filtroInvKardexLote')])
            ->add('txtBodega', TextType::class, ['required' => false, 'data' => $session->get('filtroInvKardexLoteBodega')])
            ->add('cboDocumento', EntityType::class, $em->getRepository(InvDocumento::class)->llenarCombo())
            ->add('cboDocumentoTipo', EntityType::class, $em->getRepository(InvDocumentoTipo::class)->llenarCombo())
            ->add('txtCodigoItem', TextType::class, ['required' => false, 'data' => $session->get('filtroInvItemCodigo'), 'attr' => ['class' => 'form-control']])
            ->add('fechaDesde', DateType::class, ['label' => 'Fecha desde: ', 'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'data' => $session->get('filtroInvKardexFechaDesde') ? date_create($session->get('filtroInvKardexFechaDesde')) : null])
            ->add('fechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'data' => $session->get('filtroInvKardexFechaHasta') ? date_create($session->get('filtroInvKardexFechaHasta')) : null])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $session->set('filtroInvItemCodigo', $form->get('txtCodigoItem')->getData());
                $session->set('filtroInvKardexLote', $form->get('txtLote')->getData());
                $session->set('filtroInvKardexLoteBodega', $form->get('txtBodega')->getData());
                $session->set('filtroInvKardexFechaDesde', $form->get('fechaDesde')->getData() ? $form->get('fechaDesde')->getData()->format('Y-m-d') : null);
                $session->set('filtroInvKardexFechaHasta', $form->get('fechaHasta')->getData() ? $form->get('fechaHasta')->getData()->format('Y-m-d') : null);
                $documento = $form->get('cboDocumento')->getData();
                if ($documento != '') {
                    $session->set('filtroInvCodigoDocumento', $form->get('cboDocumento')->getData()->getCodigoDocumentoPk());
                } else {
                    $session->set('filtroInvCodigoDocumento', null);
                }
                $documentoTipo = $form->get('cboDocumentoTipo')->getData();
                if ($documentoTipo != '') {
                    $session->set('filtroInvCodigoDocumentoTipo', $form->get('cboDocumentoTipo')->getData()->getCodigoDocumentoTipoPk());
                } else {
                    $session->set('filtroInvCodigoDocumentoTipo', null);
                }
                $em->getRepository(InvInformeKardex::class)->generarInforme($this->getUser()->getUsername());
            }
            if ($form->get('btnExcel')->isClicked()) {
                General::get()->setExportar($em->getRepository(InvInformeKardex::class)->lista($this->getUser()->getUsername()), "Kardex");
            }
        }
        $arInformeKardexs = $paginator->paginate($em->getRepository(InvInformeKardex::class)->lista($this->getUser()->getUsername()), $request->query->getInt('page', 1), 500);
        return $this->render('inventario/informe/inventario/movimiento/kardex.html.twig', [
            'arInformeKardexs' => $arInformeKardexs,
            'form' => $form->createView()
        ]);
    }

}

