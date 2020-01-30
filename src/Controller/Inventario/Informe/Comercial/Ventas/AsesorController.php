<?php

namespace App\Controller\Inventario\Informe\Comercial\Ventas;

use App\Controller\MaestroController;
use App\Entity\Cartera\CarRecibo;
use App\Entity\Cartera\CarReciboTipo;
use App\Entity\General\GenAsesor;
use App\Entity\Inventario\InvMovimiento;
use App\Formato\Cartera\Recaudo;
use App\General\General;
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

class AsesorController extends MaestroController
{


    public $tipo = "inventario";
    public $proceso = "invi0014";


    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/inventario/informe/inventario/comercial/ventas/asesor", name="inventario_informe_inventario_comercial_ventas_asesor")
     */
    public function lista(Request $request, PaginatorInterface $paginator )
    {
        set_time_limit(0);
        ini_set("memory_limit", -1);
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $fecha = new \DateTime('now');
        $form = $this->createFormBuilder()
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
            ->add('filtrarFecha', CheckboxType::class, array('required' => false, 'data' => $session->get('filtroFecha')))
            ->add('fechaDesde', DateType::class, ['label' => 'Fecha desde: ',  'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'data' => $fecha])
            ->add('fechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false,  'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'data' => $fecha])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->get('btnFiltrar')->isClicked() || $form->get('btnExcel')->isClicked()) {
            $session->set('filtroInformeVentasPorAsesorFechaDesde',  $form->get('fechaDesde')->getData()->format('Y-m-d'));
            $session->set('filtroInformeVentasPorAsesorFechaHasta', $form->get('fechaHasta')->getData()->format('Y-m-d'));
            $session->set('filtroFecha', $form->get('filtrarFecha')->getData());
        }
        if ($form->get('btnExcel')->isClicked()) {
            General::get()->setExportar($em->getRepository(InvMovimiento::class)->ventaPorAsesor()->getQuery()->getResult(), "Ventas por asesor");
        }
        $arFacturas = $paginator->paginate($em->getRepository(InvMovimiento::class)->ventaPorAsesor(), $request->query->getInt('page', 1), 100);
        return $this->render('inventario/informe/comercial/Venta/porAsesor.twig', [
            'arFacturas' => $arFacturas,
            'form' => $form->createView()
        ]);
    }
}

