<?php

namespace App\Controller\Inventario\Informe\Inventario\Movimiento;

use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\MaestroController;
use App\Entity\Inventario\InvDocumento;
use App\Entity\Inventario\InvDocumentoTipo;
use App\Entity\Inventario\InvInformeDisponible;
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

class DisponibleController extends MaestroController
{


    public $tipo = "utilidad";
    public $proceso = "invi0006";


    protected $procestoTipo = "I";
    protected $nombreProceso = "Kardex";
    protected $modulo = "Inventario";

    /**
     * @param Request $request
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/inventario/informe/inventario/movimiento/disponible", name="inventario_informe_inventario_movimiento_disponible")
     */
    public function lista(Request $request, PaginatorInterface $paginator )
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $arRegistros = [];
        $form = $this->createFormBuilder()
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
            ->add('txtLote', TextType::class, ['required' => false, 'data' => $session->get('filtroInvKardexLote')])
            ->add('txtCodigoItem', TextType::class, ['required' => false, 'data' => $session->get('filtroInvItemCodigo'), 'attr' => ['class' => 'form-control']])
            ->add('fechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'data' => $session->get('filtroInvKardexFechaHasta') ? date_create($session->get('filtroInvKardexFechaHasta')) : null])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $codigoItem = $form->get('txtCodigoItem')->getData();
                if($codigoItem) {
                    $session->set('filtroInvItemCodigo', $codigoItem);
                    $session->set('filtroInvKardexLote', $form->get('txtLote')->getData());
                    $session->set('filtroInvKardexFechaHasta', $form->get('fechaHasta')->getData() ? $form->get('fechaHasta')->getData()->format('Y-m-d') : null);
                    $em->getRepository(InvInformeDisponible::class)->generarInforme($this->getUser()->getUsername());
                } else {
                    Mensajes::error("Debe seleccionar un item");
                }
            }
            if ($form->get('btnExcel')->isClicked()) {
                General::get()->setExportar($em->getRepository(InvInformeDisponible::class)->lista($this->getUser()->getUsername()), "Disponible");
            }
        }
        $arInformeDisponibles = $paginator->paginate($em->getRepository(InvInformeDisponible::class)->lista($this->getUser()->getUsername()), $request->query->getInt('page', 1), 500);
        return $this->render('inventario/informe/inventario/movimiento/disponible.html.twig', [
            'arInformeDisponibles' => $arInformeDisponibles,
            'form' => $form->createView()
        ]);
    }

}

