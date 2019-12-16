<?php


namespace App\Controller\Turno\Movimiento\Novedad;


use App\Entity\RecursoHumano\RhuContrato;
use App\Entity\RecursoHumano\RhuEmpleado;
use App\Entity\Turno\TurNovedad;
use App\Entity\Turno\TurNovedadTipo;
use App\Entity\Turno\TurProgramacion;
use App\Entity\Turno\TurProgramacionRespaldo;
use App\Form\Type\Turno\NovedadType;
use App\General\General;
use App\Utilidades\Estandares;
use App\Utilidades\Mensajes;
use Doctrine\ORM\EntityRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class NovedadController extends AbstractController
{
    /**
     * @Route("/turno/movimiento/novedad/lista", name="turno_movimiento_novedad_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('codigoEmpleadoFk', TextType::class, array('required' => false))
            ->add('codigoNovedad', TextType::class, array('label' => 'Codigo', 'required' => false))
            ->add('codigoNovedadTipo', TextType::class, array('label' => 'Codigo', 'required' => false))
            ->add('estadoAnulado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
            ->add('estadoAprobado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
            ->add('estadoAutorizado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
            ->add('estadoAplicado', ChoiceType::class, array('choices' => array('TODOS' => '2', 'APLICADAS' => '1', 'SIN APLICAR' => '0')))
            ->add('fechaDesde', DateType::class, ['label' => 'Fecha desde: ', 'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd'])
            ->add('fechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd'])
            ->add('btnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
            ->add('btnEliminar', SubmitType::class, array('label' => 'Eliminar'))
            ->add('limiteRegistros', TextType::class, array('required' => false, 'data' => 100))
            ->setMethod('GET')
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
                set_time_limit(0);
                ini_set("memory_limit", -1);
                $raw['filtros'] = $this->getFiltros($form);
                General::get()->setExportar($em->getRepository(TurNovedad::class)->lista($raw), "Novedad");
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->query->get('ChkSeleccionar');
                $em->getRepository(TurNovedad::class)->eliminar($arrSeleccionados);
            }
        }
        $arNovedades = $paginator->paginate($em->getRepository(TurNovedad::class)->lista($raw), $request->query->getInt('page', 1), 30);

        return $this->render('turno/movimiento/novedad/lista.html.twig', [
            'arNovedades' => $arNovedades,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/turno/movimiento/novedad/nuevo/{id}", name="turno_movimiento_novedad_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        /**
         * @var $arNovedad TurNovedad
         */
        $arNovedad = new TurNovedad();
        if ($id != 0) {
            $arNovedad = $em->getRepository(TurNovedad::class)->find($id);
        } else {
            $arNovedad->setOrigen('TUR');
            $arNovedad->setFecha(new \DateTime('now'));
            $arNovedad->setFechaDesde(new \DateTime('now'));
            $arNovedad->setFechaHasta(new \DateTime('now'));
        }
        $form = $this->createForm(NovedadType::class, $arNovedad);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arrControles = $request->request->All();
                if ($arrControles['txtCodigoEmpleado'] != '' && $arrControles['txtCodigoEmpleadoReemplazo'] != '') {
                    /**
                     * @var $arEmpelado RhuEmpleado
                     */
                    $arNovedad = $form->getData();
                    $arNovedad->setEmpleadoRel($em->getReference(RhuEmpleado::class, $arrControles['txtCodigoEmpleado']));
                    $arNovedad->setEmpleadoReemplazoRel($em->getReference(RhuEmpleado::class, $arrControles['txtCodigoEmpleadoReemplazo']));
                    $boolFechas = $this->validarContratos($arNovedad, $em->getReference(RhuEmpleado::class, $arrControles['txtCodigoEmpleado']));
                    if ($boolFechas) {
                        $arNovedad->setUsuario($this->getUser()->getUserName());
                        $em->persist($arNovedad);
                        $em->flush();
                        return $this->redirect($this->generateUrl('turno_movimiento_novedad_detalle', array('id' => $arNovedad->getCodigoNovedadPK())));
                    }
                } else {
                    Mensajes::error("Validar que los campos del empleado y remplazo estén llenos");
                }
            }
        }
        return $this->render('turno/movimiento/novedad/nuevo.html.twig', [
            'arNovedad' => $arNovedad,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/turno/movimiento/novedad/detalle/{id}", name="turno_movimiento_novedad_detalle")
     */
    public function detalle(Request $request, PaginatorInterface $paginator, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arNovedad = $em->getRepository(TurNovedad::class)->find($id);
        $form = Estandares::botonera($arNovedad->getEstadoAutorizado(), $arNovedad->getEstadoAprobado(), $arNovedad->getEstadoAnulado());
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
//            if ($form->get('btnAutorizar')->isClicked()) {
//                $em->getRepository(RhuAspirante::class)->autorizar($arAspirante);
//            }
//            if ($form->get('btnAnular')->isClicked()) {
//                $em->getRepository(RhuAspirante::class)->anular($arAspirante);
//            }
        }
        return $this->render('turno/movimiento/novedad/detalle.html.twig', [
            'arNovedad' => $arNovedad,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param $arNovedad TurNovedad
     * @param $arRecurso TurRecurso
     * @return bool
     */
    private function validarContratos(&$arNovedad, $arEmpleado)
    {
        $em = $this->getDoctrine()->getManager();
        $boolFechas = false;
        if ($arEmpleado) {
            if ($arNovedad->getEmpleadoRel()->getCodigoContratoFk() == null || $arNovedad->getEmpleadoRel()->getCodigoContratoFk() == "" || $arNovedad->getEmpleadoRel()->getCodigoContratoFk() == null) {
                $arContrato = $em->getRepository(RhuContrato::class)->find($arNovedad->getEmpleadoRel()->getCodigoContratoUltimoFk());
                if ($arContrato) {
                    $arNovedad->setCodigoContratoFk($arEmpleado->getCodigoContratoUltimoFk());
                    if (($arNovedad->getFechaDesde() >= $arContrato->getFechaDesde() &&
                            $arNovedad->getFechaDesde() <= $arContrato->getFechaHasta() &&
                            $arNovedad->getFechaHasta() >= $arContrato->getFechaDesde() &&
                            $arNovedad->getFechaHasta() <= $arContrato->getFechaHasta()) ||
                        ($arNovedad->getNovedadTipoRel()->getEstadoIngreso() == 1 || $arNovedad->getNovedadTipoRel()->getEstadoRetiro() == 1)) {
                        $boolFechas = true;
                    } else {
                        Mensajes::error('Las fechas de la noveda no coinciden con el rango de fechas del ultimo contrato', $this);
                    }
                } else {
                    Mensajes::error('El Recurso no tiene un contrato asociado', $this);
                }
            } else {
                $arNovedad->setCodigoContratoFk($arEmpleado->getCodigoContratoFk());
                $boolFechas = true;
            }
        }
        return $boolFechas;
    }

    /**
     * @Route("/turno/movimiento/novedad/ver/programacion/{codigoNovedad}", name="turno_movimiento_novedad_ver_programacion")
     */
    public function verProgramacionEmpleado(Request $request, $codigoNovedad)
    {
        $em = $this->getDoctrine()->getManager();
        $arNovedad = $em->getRepository(TurNovedad::class)->find($codigoNovedad);
        $strFecha = $arNovedad->getFechaDesde();
        $strAnio = $strFecha->format('Y');
        $strMes = $strFecha->format('m');
        //Consulta de las programaciones detalles del empleado.
        $arProgramaciones = $em->getRepository(TurProgramacion::class)->findBy(array('codigoEmpleadoFk' => $arNovedad->getCodigoEmpleadoFk(), 'anio' => $strAnio, 'mes' => $strMes));
        return $this->render('turno/movimiento/novedad/programacion.html.twig', [
            'arProgramaciones' => $arProgramaciones
        ]);
    }

    /**
     * @Route("/turno/movimiento/novedad/cambiar/tipo/{codigoNovedad}", name="turno_movimiento_novedad_cambiar_tipo")
     */
    public function cambiarTipo(Request $request, $codigoNovedad)
    {
        $em = $this->getDoctrine()->getManager();
        $arNovedad = $em->getRepository(TurNovedad::class)->find($codigoNovedad);
        $form = $this->createFormBuilder()
            ->add('novedadTipoRel', EntityType::class, array(
                'class' => TurNovedadTipo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('nt')
                        ->orderBy('nt.codigoNovedadTipoPk', 'ASC');
                },
                'choice_label' => 'nombre',
                'required' => true))
            ->add('guardar', SubmitType::class, array('label' => 'Guardar'))
            ->setAction($this->generateUrl('turno_movimiento_novedad_cambiar_tipo', array('codigoNovedad' => $codigoNovedad)))
            ->setMethod('GET')
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $arNovedadTipo = $form->get('novedadTipoRel')->getData();
            if ($arNovedad->getCodigoNovedadTipoFk() != $arNovedadTipo->getCodigoNovedadTipoPk()) {
                $arNovedad->setNovedadTipoRel($arNovedadTipo);
                $em->flush();

                $resultado = $em->getRepository(TurNovedad::class)->aplicar($codigoNovedad, 0, 1, $this->getUser()->getUserName());
                $resultado= true;
                if (!is_array($resultado)) {
                    echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                } else {
                    $mensaje = "Se encontraron novedades con las siguientes fechas para este empleado: ";
                    $mensaje .= "<ul>";
                    $mensaje .= implode("", array_map(function ($licencia) {
                        return "<li>{$licencia}</li>";
                    }, $resultado));
                    Mensajes::error($mensaje);
                }
            }
        }
        return $this->render('turno/movimiento/novedad/cambiarTipo.html.twig', [
            'arNovedad' => $arNovedad,
            'form' => $form->createView()
        ]);
    }

    public function getFiltros($form)
    {
        $filtro = [
            'codigoEmpleado' => $form->get('codigoEmpleadoFk')->getData(),
            'codigoNovedad' => $form->get('codigoNovedad')->getData(),
            'novedadTipo' => $form->get('codigoNovedadTipo')->getData(),
            'fechaDesde' => $form->get('fechaDesde')->getData() ? $form->get('fechaDesde')->getData()->format('Y-m-d') : null,
            'fechaHasta' => $form->get('fechaHasta')->getData() ? $form->get('fechaHasta')->getData()->format('Y-m-d') : null,
            'estadoAutorizado' => $form->get('estadoAutorizado')->getData(),
            'estadoAprobado' => $form->get('estadoAprobado')->getData(),
            'estadoAnulado' => $form->get('estadoAnulado')->getData(),
            'estadoAplicado' => $form->get('estadoAplicado')->getData(),
        ];
        return $filtro;

    }
}