<?php

namespace App\Controller\Transporte\Informe\Servicio\Novedad;

use App\Controller\MaestroController;
use App\Entity\Transporte\TteNovedad;
use App\General\General;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;

class PendienteSolucionarController extends MaestroController
{
    public $tipo = "proceso";
    public $proceso = "ttei0002";



    /**
     * @param Request $request
     * @return Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/transporte/inf/control/novedad/pendiente/solucionar", name="transporte_inf_servicio_novedad_pendiente_solucionar")
     */
    public function lista(Request $request,  PaginatorInterface $paginator)
    {
        $em = $this->getDoctrine()->getManager();

        $session = new session;
        $form = $this->createFormBuilder()
            ->add('txtCodigoCliente', TextType::class, ['required' => false, 'data' => $session->get('filtroTteCodigoCliente'), 'attr' => ['class' => 'form-control']])
            ->add('txtNombreCorto', TextType::class, ['required' => false, 'data' => $session->get('filtroTteNombreCliente'), 'attr' => ['class' => 'form-control', 'readonly' => 'reandonly']])
            ->add('fechaDesde', DateType::class, ['label' => 'Fecha desde: ',  'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'data' => $session->get('filtroTtePendienteSolucionarFechaDesde') ? date_create($session->get('filtroTtePendienteSolucionarFechaDesde')): null])
            ->add('fechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false,  'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'data' => $session->get('filtroTtePendienteSolucionarFechaHasta') ? date_create($session->get('filtroTtePendienteSolucionarFechaHasta')): null])
            ->add('btnReportar', SubmitType::class, array('label' => 'Reportar'))
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                if ($form->get('btnFiltrar')->isClicked()) {
                    $session->set('filtroTtePendienteSolucionarFechaDesde',  $form->get('fechaDesde')->getData() ?$form->get('fechaDesde')->getData()->format('Y-m-d'): null);
                    $session->set('filtroTtePendienteSolucionarFechaHasta', $form->get('fechaHasta')->getData() ? $form->get('fechaHasta')->getData()->format('Y-m-d'): null);
                    if ($form->get('txtCodigoCliente')->getData() != '') {
                        $session->set('filtroTteCodigoCliente', $form->get('txtCodigoCliente')->getData());
                        $session->set('filtroTteNombreCliente', $form->get('txtNombreCorto')->getData());
                    } else {
                        $session->set('filtroTteCodigoCliente', null);
                        $session->set('filtroTteNombreCliente', null);
                    }
                }
                if ($form->get('btnReportar')->isClicked()) {
                    $arrNovedades = $request->request->get('chkSeleccionar');
                    $arrControles = $request->request->All();
                    $respuesta = $this->getDoctrine()->getRepository(TteNovedad::class)->setReportar($arrNovedades, $arrControles);
                }
                if ($form->get('btnExcel')->isClicked()) {
                    General::get()->setExportar($em->getRepository(TteNovedad::class)->pendienteSolucionar()->getQuery()->getResult(), 'Novedades pendientes por solucionar');
                }
            }
        }
        $arNovedades = $paginator->paginate($this->getDoctrine()->getRepository(TteNovedad::class)->pendienteSolucionar(), $request->query->getInt('page', 1), 500);
        return $this->render('transporte/informe/servicio/novedad/pendienteSolucionar.html.twig', [
            'arNovedades' => $arNovedades,
            'form' => $form->createView()]);
    }
}

