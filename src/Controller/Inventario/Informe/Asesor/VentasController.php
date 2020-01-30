<?php

namespace App\Controller\Inventario\Informe\Asesor;

use App\Controller\MaestroController;
use App\Entity\Cartera\CarRecibo;
use App\Entity\Cartera\CarReciboTipo;
use App\Entity\General\GenAsesor;
use App\Entity\Inventario\InvMovimiento;
use App\Formato\Cartera\Recaudo;
use App\General\General;
use App\Utilidades\Mensajes;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class VentasController extends MaestroController
{
    public $tipo = "inventario";
    public $proceso = "invi0019";


    protected $procestoTipo= "I";
    protected $nombreProceso = "Ventas de asesor";
    protected $modulo = "Inventario";

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/inventario/informe/asesor/ventas", name="inventario_informe_inventario_asesor_ventas")
     */
    public function lista(Request $request, PaginatorInterface $paginator )
    {
        set_time_limit(0);
        ini_set("memory_limit", -1);
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $asesor = $this->getUser()->getCodigoAsesorFk();
        if($asesor == null){
            Mensajes::error("El usuario no tiene asesor asignado");
        }
        $fecha = new \DateTime('now');
        $form = $this->createFormBuilder()
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
            ->add('fechaDesde', DateType::class, ['label' => 'Fecha desde: ',  'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'data' => $session->get('filtroInvInformeAsesorVentasFechaDesde') ? date_create($session->get('filtroInvInformeAsesorVentasFechaDesde')): null])
            ->add('fechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false,  'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'data' => $session->get('filtroInvInformeAsesorVentasFechaHasta') ? date_create($session->get('filtroInvInformeAsesorVentasFechaHasta')): null])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->get('btnFiltrar')->isClicked() || $form->get('btnExcel')->isClicked()) {
            $session->set('filtroInvInformeAsesorVentasFechaDesde',  $form->get('fechaDesde')->getData() ?$form->get('fechaDesde')->getData()->format('Y-m-d'): null);
            $session->set('filtroInvInformeAsesorVentasFechaHasta', $form->get('fechaHasta')->getData() ? $form->get('fechaHasta')->getData()->format('Y-m-d'): null);
        }
        if ($form->get('btnExcel')->isClicked()) {
            General::get()->setExportar($em->getRepository(InvMovimiento::class)->ventasSoloAsesor($asesor)->getQuery()->getResult(), "Ventas por asesor");
        }
        $arFacturas = $paginator->paginate($em->getRepository(InvMovimiento::class)->ventasSoloAsesor($asesor), $request->query->getInt('page', 1), 500);
        return $this->render('inventario/informe/asesor/ventas.twig', [
            'arFacturas' => $arFacturas,
            'form' => $form->createView()
        ]);
    }
}

