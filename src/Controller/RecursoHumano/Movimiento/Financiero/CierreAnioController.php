<?php

namespace App\Controller\RecursoHumano\Movimiento\Financiero;

use App\Entity\RecursoHumano\RhuCierreAnio;
use App\Entity\RecursoHumano\RhuCosto;
use App\Entity\RecursoHumano\RhuPagoTipo;
use App\Entity\RecursoHumano\RhuCierre;
use App\Entity\Turno\TurSoporte;
use App\Form\Type\RecursoHumano\CierreAnioType;
use App\Form\Type\RecursoHumano\CierreType;
use App\Formato\RecursoHumano\ResumenConceptos;
use App\General\General;
use App\Utilidades\Estandares;
use App\Utilidades\Mensajes;
use Doctrine\ORM\EntityRepository;
use Knp\Component\Pager\PaginatorInterface;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class CierreAnioController extends AbstractController
{
    protected $clase = RhuCierre::class;
    protected $claseNombre = "RhuCierre";
    protected $modulo = "RecursoHumano";
    protected $funcion = "movimiento";
    protected $grupo = "Financiero";
    protected $nombre = "Cierre";

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("recursohumano/movimiento/financiero/cierreanio/lista", name="recursohumano_movimiento_financiero_cierreanio_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('estadoAutorizado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
            ->add('estadoAprobado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
            ->add('estadoAnulado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
            ->add('limiteRegistros', TextType::class, array('required' => false, 'data' => 100))
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnExcel', SubmitType::class, ['label' => 'Excel', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        $raw = [
            'limiteRegistros' => $form->get('limiteRegistros')->getData()
        ];
        if ($form->isSubmitted()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $raw['filtros'] = $this->getFiltros($form);
            }
            if ($form->get('btnExcel')->isClicked()) {
                $raw['filtros'] = $this->getFiltros($form);
                General::get()->setExportar($em->getRepository(RhuCierreAnio::class)->lista($raw), "Cierres");
            }
        }
        $arCierresAnio = $paginator->paginate($em->getRepository(RhuCierreAnio::class)->lista($raw), $request->query->getInt('page', 1), 30);
        return $this->render('recursohumano/movimiento/financiero/cierreanio/lista.html.twig', [
            'arCierresAnio' => $arCierresAnio,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("recursohumano/movimiento/financiero/cierreanio/nuevo/{id}", name="recursohumano_movimiento_financiero_cierreanio_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arCierreAnio = new RhuCierreAnio();
        if ($id != 0) {
            $arCierreAnio = $em->getRepository($this->clase)->find($id);
            if (!$arCierreAnio) {
                return $this->redirect($this->generateUrl('recursohumano_movimiento_financiero_cierreanio_lista'));
            }
        }
        $form = $this->createForm(CierreAnioType::class, $arCierreAnio);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $em->persist($arCierreAnio);
                $em->flush();
                return $this->redirect($this->generateUrl('recursohumano_movimiento_financiero_cierreanio_detalle', ['id' => $arCierreAnio->getCodigoCierreAnioPk()]));
            }
        }
        return $this->render('recursohumano/movimiento/financiero/cierreanio/nuevo.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @param PaginatorInterface $paginator
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     * @Route("recursohumano/movimiento/financiero/cierreanio/detalle/{id}", name="recursohumano_movimiento_financiero_cierreanio_detalle")
     */
    public function detalle(Request $request, $id, PaginatorInterface $paginator)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('salarioMinimo', NumberType::class)
            ->add('auxilioTransporte', NumberType::class)
            ->add('BtnGuardar', SubmitType::class, array('label' => 'Guardar'))
            ->getForm();
        $form->handleRequest($request);
        $arCierreAnio = new \Brasa\RecursoHumanoBundle\Entity\RhuCierreAnio();
        $arCierreAnio = $em->getRepository('BrasaRecursoHumanoBundle:RhuCierreAnio')->find($codigoCierreAnio);

        if ($form->isValid()) {
            set_time_limit(0);
            ini_set("memory_limit", -1);
            $floSalarioMinimo = $form->get('salarioMinimo')->getData();
            $floAuxilioTransporte = $form->get('auxilioTransporte')->getData();
            $arConfiguracion = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();
            $arConfiguracion = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1);
            $floSalarioMinimoAnterior = $arConfiguracion->getVrSalario();

            $arCierreAnio = new \Brasa\RecursoHumanoBundle\Entity\RhuCierreAnio();
            $arCierreAnio = $em->getRepository('BrasaRecursoHumanoBundle:RhuCierreAnio')->find($codigoCierreAnio);
            $arCierreAnio->setEstadoCerrado(1);
            $arCierreAnio->setFechaAplicacion(new \DateTime('now'));
            $em->persist($arCierreAnio);


            $strFechaCambio = $arConfiguracion->getAnioActual() . "/12/30";
            $arContratoMinimos = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
            $strDql = "SELECT c FROM BrasaRecursoHumanoBundle:RhuContrato c WHERE c.estadoActivo = 1 AND c.fechaDesde <= '{$arCierreAnio->getAnio()}-12-30' AND c.VrSalario <= " . $arConfiguracion->getVrSalario();
            $query = $em->createQuery($strDql);
            $arContratoMinimos = $query->getResult();

            // validacion para verificar que todos los empleados tengan pagadas las cesantias
            $strDql2 = "SELECT c FROM BrasaRecursoHumanoBundle:RhuContrato c WHERE c.estadoActivo = 1 AND c.fechaDesde <= '{$arCierreAnio->getAnio()}-12-30' "
                . "AND c.VrSalario <= {$arConfiguracion->getVrSalario()} "
                . "AND ((c.fechaUltimoPagoCesantias = '{$arCierreAnio->getAnio()}-12-30' OR c.fechaUltimoPagoCesantias = '{$arCierreAnio->getAnio()}-12-31' OR c.codigoContratoClaseFk = 4 OR c.codigoContratoClaseFk = 5) OR (c.codigoTipoPensionFk = 5)) ";
            $arContratoPagoCesantias = $em->createQuery($strDql2)->getResult();

            if (count($arContratoMinimos) == count($arContratoPagoCesantias)) {
                foreach ($arContratoMinimos as $arContratoMinimo) {
                    $arCambioSalario = new \Brasa\RecursoHumanoBundle\Entity\RhuCambioSalario();
                    $arCambioSalario->setContratoRel($arContratoMinimo);
                    $arCambioSalario->setEmpleadoRel($arContratoMinimo->getEmpleadoRel());
                    $arCambioSalario->setFecha(date_create($strFechaCambio));
                    $arCambioSalario->setVrSalarioAnterior($floSalarioMinimoAnterior);
                    $arCambioSalario->setVrSalarioNuevo($floSalarioMinimo);
                    $arCambioSalario->setDetalle('ACTUALIZACION SALARIO MINIMO');
                    $em->persist($arCambioSalario);
                    $arContratoActualizar = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
                    $arContratoActualizar = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->find($arContratoMinimo->getCodigoContratoPk());
                    $arContratoActualizar->setVrSalario($floSalarioMinimo);
                    $arContratoActualizar->setVrSalarioPago($floSalarioMinimo);
                    if ($arContratoActualizar->getCodigoTipoTiempoFk() == 2) {
                        $arContratoActualizar->setVrSalarioPago($floSalarioMinimo / 2);
                    }
                    $em->persist($arContratoActualizar);
                    $arEmpleadoActualizar = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
                    $arEmpleadoActualizar = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->find($arContratoMinimo->getCodigoEmpleadoFk());
                    $arEmpleadoActualizar->setVrSalario($floSalarioMinimo);
                    $em->persist($arEmpleadoActualizar);
                }
                $arConfiguracion->setAnioActual($arConfiguracion->getAnioActual() + 1);
                $arConfiguracion->setVrSalario($floSalarioMinimo);
                $arConfiguracion->setVrAuxilioTransporte($floAuxilioTransporte);
                $em->persist($arConfiguracion);
                //nuevo año periodo
                $anioNuevoPeriodo = new \Brasa\RecursoHumanoBundle\Entity\RhuCierreAnio;
                $anioNuevoPeriodo->setAnio($arConfiguracion->getAnioActual());
                $anioNuevoPeriodo->setEstadoCerrado(0);
                $em->persist($anioNuevoPeriodo);
                $em->flush();
            } else {
                $arrError = [];
                foreach ($arContratoMinimos as $arContratoMinimo) {
                    $econtrado = 0;
                    foreach ($arContratoPagoCesantias as $arContratoPagoCesantia) {
                        if ($arContratoMinimo->getCodigoContratoPK() == $arContratoPagoCesantia->getCodigoContratoPk()) {
                            $econtrado = 1;
                        }
                    }
                    if ($econtrado == 0) {
                        $arrError[] = "Cod: " . $arContratoMinimo->getCodigoContratoPK() . " Documento: " . $arContratoMinimo->getEmpleadoRel()->getNumeroIdentificacion() . " Nombre: " . $arContratoMinimo->getEmpleadoRel()->getNombreCorto();
                    }
                }
                $strMensaje = "Todos los empleados deben tener el pago de cesantías antes de generar el cierre:<br>";
                $strMensaje .= implode('<br>', $arrError);
                $objMensaje->Mensaje("error", $strMensaje);
            }
            return $this->redirect($this->generateUrl('brs_rhu_proceso_cierre_anio'));
        }
        return $this->render('BrasaRecursoHumanoBundle:Procesos/CierreAnio:cerrar.html.twig', array(
            'arCierreAnio' => $arCierreAnio,
            'form' => $form->createView()
        ));
//        $em = $this->getDoctrine()->getManager();
//        $arCierre = $this->clase;
//        if ($id != 0) {
//            $arCierre = $em->getRepository($this->clase)->find($id);
//            if (!$arCierre) {
//                return $this->redirect($this->generateUrl('recursohumano_movimiento_financiero_cierre_lista'));
//            }
//        }
//
//        $form = Estandares::botonera($arCierre->getEstadoAutorizado(), $arCierre->getEstadoAprobado(), $arCierre->getEstadoAnulado());
//        $form->handleRequest($request);
//        if ($form->isSubmitted() && $form->isValid()) {
//            $arrSeleccionados = $request->request->get('ChkSeleccionar');
//            if ($form->get('btnAutorizar')->isClicked()) {
//                set_time_limit(0);
//                ini_set("memory_limit", -1);
//                $em->getRepository(RhuCierre::class)->autorizar($arCierre, $this->getUser()->getUsername());
//                return $this->redirect($this->generateUrl('recursohumano_movimiento_financiero_cierre_detalle', ['id' => $id]));
//            }
//            if ($form->get('btnAprobar')->isClicked()) {
//                set_time_limit(0);
//                ini_set("memory_limit", -1);
//                $em->getRepository(RhuCierre::class)->aprobar($arCierre);
//                return $this->redirect($this->generateUrl('recursohumano_movimiento_financiero_cierre_detalle', ['id' => $id]));
//            }
//            if ($form->get('btnDesautorizar')->isClicked()) {
//                set_time_limit(0);
//                ini_set("memory_limit", -1);
//                $em->getRepository(RhuCierre::class)->desautorizar($arCierre);
//                return $this->redirect($this->generateUrl('recursohumano_movimiento_financiero_cierre_detalle', ['id' => $id]));
//            }
//        }
//        $arCostos = $paginator->paginate($em->getRepository(RhuCosto::class)->lista($arCierre->getCodigoCierrePk()), $request->query->get('page', 1), 1000);
//        return $this->render('recursohumano/movimiento/financiero/cierre/detalle.html.twig', [
//            'arCierre' => $arCierre,
//            'arCostos' => $arCostos,
//            'clase' => array('clase' => 'RhuCierre', 'codigo' => $id),
//            'form' => $form->createView(),
//        ]);
    }

    public function getFiltros($form)
    {
        $filtro = [
            'estadoAutorizado' => $form->get('estadoAutorizado')->getData(),
            'estadoAprobado' => $form->get('estadoAprobado')->getData(),
            'estadoAnulado' => $form->get('estadoAnulado')->getData(),
        ];

        return $filtro;

    }
}