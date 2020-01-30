<?php

namespace App\Controller\Transporte\Utilidad\Transporte;

use App\Controller\MaestroController;
use App\Entity\Transporte\TteDespacho;
use App\Entity\Transporte\TteDespachoDetalle;
use App\Entity\Transporte\TteGuia;
use App\Entity\Transporte\TteVehiculo;
use App\Entity\Transporte\TteVehiculoDisponible;
use App\Form\Type\Transporte\VehiculoDisponibleDescarteType;
use App\Form\Type\Transporte\VehiculoDisponibleType;
use App\General\General;
use App\Utilidades\Mensajes;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class VehiculoDisponibleController extends MaestroController
{
    public $tipo = "utilidad";
    public $proceso = "tteu0011";


    /**
     * @param Request $request
     * @return Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/transporte/utilidad/transporte/vehiculoDisponible/lista", name="transporte_utilidad_transporte_vehiculoDisponible_lista")
     */
    public function lista(Request $request,  PaginatorInterface $paginator)
    {
        set_time_limit(0);
        ini_set("memory_limit", -1);
        $session = new Session();
        $em = $this->getDoctrine()->getManager();

        $form = $this->createFormBuilder()
            ->add('txtCodigoVehiculo', TextType::class, ['required' => false, 'data' => $session->get('filtroTteVehiculo'), 'attr' => ['class' => 'form-control']])
            ->add('fechaDesde', DateType::class, ['label' => 'Fecha desde: ', 'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'data' => $session->get('filtroTteVehiculoDisponibleFechaDesde') ? date_create($session->get('filtroTteVehiculoDisponibleFechaDesde')) : null])
            ->add('fechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'data' => $session->get('filtroTteVehiculoDisponibleFechaHasta') ? date_create($session->get('filtroTteVehiculoDisponibleFechaHasta')) : null])
            ->add('chkEstadoDespachado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'data' => $session->get('filtroTteVehiculoDisponibleEstadoDespachado'), 'required' => false])
            ->add('chkEstadoDescartado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'data' => $session->get('filtroTteVehiculoDisponibleEstadoDescartado'), 'required' => false])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->get('btnFiltrar')->isClicked()) {
            $session->set('filtroTteVehiculo', $form->get('txtCodigoVehiculo')->getData());
            $session->set('filtroTteVehiculoDisponibleFechaDesde', $form->get('fechaDesde')->getData() ? $form->get('fechaDesde')->getData()->format('Y-m-d') : null);
            $session->set('filtroTteVehiculoDisponibleFechaHasta', $form->get('fechaHasta')->getData() ? $form->get('fechaHasta')->getData()->format('Y-m-d') : null);
            $session->set('filtroTteVehiculoDisponibleEstadoDespachado', $form->get('chkEstadoDespachado')->getData());
            $session->set('filtroTteVehiculoDisponibleEstadoDescartado', $form->get('chkEstadoDescartado')->getData());
        }
        if ($form->get('btnExcel')->isClicked()) {
            General::get()->setExportar($em->getRepository(TteVehiculoDisponible::class)->lista()->getQuery()->execute(), "Vehiculos disponibles");
        }
        $query = $this->getDoctrine()->getRepository(TteVehiculoDisponible::class)->lista();
        $arVehiculosDisponible = $paginator->paginate($query, $request->query->getInt('page', 1), 100);
        return $this->render('transporte/utilidad/transporte/vehiculoDisponible/lista.html.twig', [
            'arVehiculosDisponible' => $arVehiculosDisponible,
            'form' => $form->createView()]);
    }


    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Exception
     * @Route("/transporte/utilidad/vehiculoDisponible/nuevo/{id}", name="transporte_utilidad_transporte_vehiculoDisponible_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        set_time_limit(0);
        ini_set("memory_limit", -1);
        $em = $this->getDoctrine()->getManager();
        $arVehiculoDisponible = new TteVehiculoDisponible();
        if ($id != 0) {
            $arVehiculoDisponible = $em->getRepository(TteVehiculoDisponible::class)->find($id);
            if (!$arVehiculoDisponible) {
                return $this->redirect($this->generateUrl('transporte/utilidad/transporte/vehiculoDisponible/nuevo.html.twig'));
            }
        }
        $form = $this->createForm(VehiculoDisponibleType::class, $arVehiculoDisponible);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $txtCodigoVehiculo = $request->request->get('txtCodigoVehiculo');
                if ($txtCodigoVehiculo != '') {
                    $arVehiculo = $em->getRepository(TteVehiculo::class)->find($txtCodigoVehiculo);
                    if ($arVehiculo) {
                        $vehiculoSinDespacho = $em->getRepository(TteVehiculoDisponible::class)->findBy(array('codigoVehiculoFk' => $arVehiculo->getCodigoVehiculoPk(), 'estadoDespachado' => 0 , 'estadoDescartado' => 0));
                        if (!$vehiculoSinDespacho) {
                            if ($id == 0) {
                                $arVehiculoDisponible->setFecha(new \DateTime('now'));
                            }
                            $arVehiculoDisponible->setVehiculoRel($arVehiculo);
                            $arVehiculoDisponible->setUsuario($this->getUser()->getUsername());
                            $em->persist($arVehiculoDisponible);
                            $em->flush();
                            return $this->redirect($this->generateUrl('transporte_utilidad_transporte_vehiculoDisponible_lista'));
                        } else {
                            Mensajes::error('Este vehículo ya tiene un registro sin despachar');
                        }
                    } else {
                        Mensajes::error('No se encontro un vehículo con el codigo ingresado');
                    }
                } else {
                    Mensajes::error('Debe de seleccionar un vehículo');
                }
            }
        }
        return $this->render('transporte/utilidad/transporte/vehiculoDisponible/nuevo.html.twig', [
            'arVehiculoDisponible' => $arVehiculoDisponible,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Exception
     * @Route("/transporte/utilidad/vehiculoDisponible/descartar/{id}", name="transporte_utilidad_transporte_vehiculoDisponible_descartar")
     */
    public function descartar(Request $request, $id)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $arVehiculoDisponible = $em->getRepository(TteVehiculoDisponible::class)->find($id);
        $form = $this->createForm(VehiculoDisponibleDescarteType::class, $arVehiculoDisponible);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                if ($form->get('guardar')->isClicked()) {
                    $arVehiculoDisponible->setUsuarioDescarte($this->getUser()->getUsername());
                    $arVehiculoDisponible->setFechaDescartado(new \DateTime('now'));
                    $arVehiculoDisponible->setEstadoDescartado(1);
                    $em->persist($arVehiculoDisponible);
                    $em->flush();
                }
            }
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
        }
        return $this->render('transporte/utilidad/transporte/vehiculoDisponible/descartar.html.twig', array(
            'arVehiculoDisponible' => $arVehiculoDisponible,
            'form' => $form->createView()));
    }
}

