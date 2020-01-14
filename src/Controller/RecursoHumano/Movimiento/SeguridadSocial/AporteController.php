<?php


namespace App\Controller\RecursoHumano\Movimiento\SeguridadSocial;


use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Entity\General\GenConfiguracion;
use App\Entity\RecursoHumano\RhuAporte;
use App\Entity\RecursoHumano\RhuAporteContrato;
use App\Entity\RecursoHumano\RhuAporteDetalle;
use App\Entity\RecursoHumano\RhuAporteEntidad;
use App\Entity\RecursoHumano\RhuAportePlanilla;
use App\Entity\RecursoHumano\RhuAporteSoporte;
use App\Entity\RecursoHumano\RhuConfiguracion;
use App\Entity\RecursoHumano\RhuEntidad;
use App\Entity\Transporte\TteGuia;
use App\Form\Type\RecursoHumano\AporteDetalleType;
use App\Form\Type\RecursoHumano\AporteType;
use App\General\General;
use App\Utilidades\Estandares;
use App\Utilidades\Modelo;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class AporteController extends AbstractController
{
    protected $clase = RhuAporte::class;
    protected $claseNombre = "RhuAporte";
    protected $modulo = "RecursoHumano";
    protected $funcion = "Movimiento";
    protected $grupo = "SeguridadSocial";
    protected $nombre = "Aporte";

    /**
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("recursohumano/movimiento/seguridadsocial/aporte/lista", name="recursohumano_movimiento_seguridadsocial_aporte_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('anio', TextType::class, ['required' => false])
            ->add('mes', ChoiceType::class, [
                'choices' => array(
                    'TODOS' => '', 'ENERO' => '1', 'FEBRERO' => '2', 'MARZO' => '3', 'ABRIL' => '4', 'MAYO' => '5', 'JUNIO' => '6', 'JULIO' => '7',
                    'AGOSTO' => '8', 'SEPTIEMBRE' => '9', 'OCTUBRE' => '10', 'NOVIEMBRE' => '11', 'DICIEMBRE' => '12',
                ),
                'required' => false,
                'placeholder' => '',
            ])
            ->add('limiteRegistros', TextType::class, array('required' => false, 'data' => 100))
            ->add('btnEliminar', SubmitType::class, ['label' => 'Eliminar', 'attr' => ['class' => 'btn btn-sm btn-danger']])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnExcel', SubmitType::class, ['label' => 'Excel', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnContabilizar', SubmitType::class, ['label' => 'Contabilizar', 'attr' => ['class' => 'btn btn-sm btn-primary']])
            ->getForm();
        $form->handleRequest($request);
        $raw = [
            'limiteRegistros' => $form->get('limiteRegistros')->getData()
        ];
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $raw['filtros'] = $this->getFiltros($form);
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository(RhuAporte::class)->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('recursohumano_movimiento_seguridadsocial_aporte_lista'));
            }
            if ($form->get('btnContabilizar')->isClicked()) {
                $raw['filtros'] = $this->getFiltros($form);
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository(RhuAporte::class)->contabilizar($arrSeleccionados);
            }
            if ($form->get('btnExcel')->isClicked()) {
                $raw['filtros'] = $this->getFiltros($form);
                General::get()->setExportar($em->getRepository(RhuAporte::class)->lista($raw)->getQuery()->getResult(), "Periodo de aportes");
            }
        }
        $arAportes = $paginator->paginate($em->getRepository(RhuAporte::class)->lista($raw), $request->query->getInt('page', 1), 30);
        return $this->render('recursohumano/movimiento/seguridadsocial/aporte/lista.html.twig', [
            'arAportes' => $arAportes,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("recursohumano/movimiento/seguridadsocial/aporte/nuevo/{id}", name="recursohumano_movimiento_seguridadsocial_aporte_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arAporte = new RhuAporte();
        if ($id != 0) {
            $arAporte = $em->getRepository(RhuAporte::class)->find($id);
            if (!$arAporte) {
                return $this->redirect($this->generateUrl('recursohumano_movimiento_seguridadsocial_aporte_lista'));
            }
        } else {
            $arAporte->setAnio((new \DateTime('now'))->format('Y'));
            $arAporte->setMes((new \DateTime('now'))->format('m'));
        }
        $form = $this->createForm(AporteType::class, $arAporte);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arAporte = $form->getData();
                $fechas = FuncionesController::desdeHastaAnioMes($arAporte->getAnio(), $arAporte->getMes());
                $arAporte->setFechaDesde($fechas['fechaDesde']);
                $arAporte->setFechaHasta($fechas['fechaHasta']);
                $periodoSalud = $this->periodoSalud($arAporte->getAnio(), $arAporte->getMes());
                $arAporte->setAnioSalud($periodoSalud['anio']);
                $arAporte->setMesSalud($periodoSalud['mes']);
                $em->persist($arAporte);
                $em->flush();
                return $this->redirect($this->generateUrl('recursohumano_movimiento_seguridadsocial_aporte_detalle', ['id' => $arAporte->getCodigoAportePk()]));
            }
        }
        return $this->render('recursohumano/movimiento/seguridadsocial/aporte/nuevo.html.twig', [
            'form' => $form->createView(),
            'arAporte' => $arAporte
        ]);
    }

    /**
     * @Route("recursohumano/movimiento/seguridadsocial/aporte/detalle/{id}", name="recursohumano_movimiento_seguridadsocial_aporte_detalle")
     */
    public function detalle(Request $request, PaginatorInterface $paginator, Modelo $utilidadesModelo, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arAporte = $em->getRepository(RhuAporte::class)->find($id);
        if (!$arAporte) {
            return $this->redirect($this->generateUrl('recursohumano_movimiento_seguridadsocial_aporte_lista'));
        }
        $arrPropiedadesEliminarContratos = ['label' => 'Eliminar contratos', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-danger']];
        $arrPropiedadesCargarContratos = ['label' => 'Cargar contratos', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']];
        if ($arAporte->getEstadoAutorizado()) {
            $arrPropiedadesEliminarContratos['disabled'] = true;
            $arrPropiedadesCargarContratos['disabled'] = true;
        }

        $form = Estandares::botonera($arAporte->getEstadoAutorizado(), $arAporte->getEstadoAprobado(), $arAporte->getEstadoAnulado());
        $form
            ->add('btnExcelContrato', SubmitType::class, ['label' => 'Excel', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnExcelDetalle', SubmitType::class, ['label' => 'Excel', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnCargarContratos', SubmitType::class, $arrPropiedadesCargarContratos)
            ->add('btnEliminarContratos', SubmitType::class, $arrPropiedadesEliminarContratos)
            ->add('identificacion', TextType::class, array('required' => false))
            ->add('btnFiltrar', SubmitType::class, ['attr' => ['class' => 'btn btn-sm btn-default'], 'label' => 'Filtrar'])
            ->add('btnExportarPlano', SubmitType::class, ['label' => 'Plano pila', 'attr' => ['class' => 'btn btn-sm btn-default']]);
        $form->handleRequest($request);
        $raw = [
            'limiteRegistros' => null
        ];
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnAutorizar')->isClicked()) {
                $em->getRepository(RhuAporte::class)->autorizar($arAporte);
                $em->getRepository(RhuAporte::class)->liquidar($arAporte);
                return $this->redirect($this->generateUrl('recursohumano_movimiento_seguridadsocial_aporte_detalle', array('id' => $id)));
            }
            if ($form->get('btnDesautorizar')->isClicked()) {
                $em->getRepository(RhuAporte::class)->desAutorizar($arAporte);
                return $this->redirect($this->generateUrl('recursohumano_movimiento_seguridadsocial_aporte_detalle', array('id' => $id)));
            }

            if ($form->get('btnAprobar')->isClicked()) {
                $em->getRepository(RhuAporte::class)->Aprobar($arAporte);
                return $this->redirect($this->generateUrl('recursohumano_movimiento_seguridadsocial_aporte_detalle', array('id' => $id)));
            }
            if ($form->get('btnAnular')->isClicked()) {
                $em->getRepository(RhuAporte::class)->Anular($arAporte);
                return $this->redirect($this->generateUrl('recursohumano_movimiento_seguridadsocial_aporte_detalle', array('id' => $id)));
            }
            if ($form->get('btnExcelContrato')->isClicked()) {
                General::get()->setExportar($em->getRepository(RhuAporteContrato::class)->lista($id)->getQuery()->getResult(), "AporteContrato");
            }
            if ($form->get('btnExcelDetalle')->isClicked()) {
                General::get()->setExportar($em->getRepository(RhuAporteDetalle::class)->lista($id, $raw)->getQuery()->getResult(), "AporteDetalle");
            }
            if ($form->get('btnCargarContratos')->isClicked()) {
                $em->getRepository(RhuAporteContrato::class)->cargar($arAporte);
                return $this->redirect($this->generateUrl('recursohumano_movimiento_seguridadsocial_aporte_detalle', array('id' => $id)));
            }
            if ($form->get('btnExportarPlano')->isClicked()) {
                $this->generarPlano($arAporte);
            }
            if ($form->get('btnEliminarContratos')->isClicked()) {
                if (!$arAporte->getEstadoAutorizado()) {
                    $arrSeleccionados = $request->request->get('ChkSeleccionar');
                    $utilidadesModelo->eliminar(RhuAporteContrato::class, $arrSeleccionados);
                }
                return $this->redirect($this->generateUrl('recursohumano_movimiento_seguridadsocial_aporte_detalle', array('id' => $id)));
            }
            if ($form->get('btnFiltrar')->isClicked()) {
                $raw['filtros'] = $this->getFiltrosDetalle($form);
            }
        }
        $arAporteDetalles = $paginator->paginate($em->getRepository(RhuAporteDetalle::class)->lista($id, $raw), $request->query->getInt('page', 1), 2000);
        $arAporteContratos = $paginator->paginate($em->getRepository(RhuAporteContrato::class)->lista($id), $request->query->getInt('page', 1), 2000);
        $arAporteEntidades = $paginator->paginate($em->getRepository(RhuAporteEntidad::class)->lista($id), $request->query->getInt('page', 1), 2000);
        return $this->render('recursohumano/movimiento/seguridadsocial/aporte/detalle.html.twig', [
            'arAporte' => $arAporte,
            'arAporteContratos' => $arAporteContratos,
            'arAporteDetalles' => $arAporteDetalles,
            'arAporteEntidades' => $arAporteEntidades,
            'clase' => array('clase' => 'RhuAporte', 'codigo' => $id),
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("recursohumano/movimiento/seguridadsocial/aporte/editar/{id}", name="recursohumano_movimiento_seguridadsocial_aporte_editar")
     */
    public function detalleAporteEditar(Request $request, PaginatorInterface $paginator, Modelo $utilidadesModelo, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arAporteDetalle = $em->getRepository(RhuAporteDetalle::class)->find($id);
        $arAporte = $em->getRepository(RhuAporte::class)->find($arAporteDetalle->getCodigoAporteFk());
        if (!$arAporteDetalle) {
            return $this->redirect($this->generateUrl('recursohumano_movimiento_seguridadsocial_aporte_lista'));
        }
        $form = $this->createForm(AporteDetalleType::class, $arAporteDetalle);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $arAporteDetalleDetalle = $form->getData();
            $arAporteDetalleDetalle->setCodigoEntidadSaludPertenece($arAporteDetalle->getEntidadSaludRel()->getCodigoInterface());
            $arAporteDetalle->setCodigoEntidadCajaPertenece($arAporteDetalle->getEntidadCajaRel()->getCodigoInterface());
            $arAporteDetalle->setCodigoEntidadPensionPertenece($arAporteDetalle->getEntidadPensionRel()->getCodigoInterface());
            if (!$arAporteDetalle->getIngreso()) {
                $arAporteDetalle->setIngreso(' ');
            }
            if (!$arAporteDetalle->getRetiro()) {
                $arAporteDetalle->setRetiro(' ');
            }
            if ($arAporteDetalle->getAporteVoluntarioFondoPensionesObligatorias() > 0) {
                $arAporteDetalle->setAporteVoluntario('X');
            } else {
                $arAporteDetalle->setAporteVoluntario(' ');
            }
            $totalCotizacion = $arAporteDetalle->getAporteVoluntarioFondoPensionesObligatorias() + $arAporteDetalle->getAportesFondoSolidaridadPensionalSolidaridad() + $arAporteDetalle->getAportesFondoSolidaridadPensionalSubsistencia() + $arAporteDetalle->getCotizacionPension() + $arAporteDetalle->getCotizacionSalud() + $arAporteDetalle->getCotizacionRiesgos() + $arAporteDetalle->getCotizacionCaja() + $arAporteDetalle->getCotizacionIcbf() + $arAporteDetalle->getCotizacionSena();
            $arAporteDetalle->setTotalCotizacion($totalCotizacion);
            $em->persist($arAporteDetalle);
            $em->flush();
            $em->getRepository(RhuAporte::class)->liquidar2($arAporte->getCodigoAportePk());
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
        }
        return $this->render('recursohumano/movimiento/seguridadsocial/aporte/detalleEditar.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("recursohumano/movimiento/seguridadsocial/soporte/aporte/detalle/{id}", name="recursohumano_movimiento_seguridadsocial_soporte_aporte_detalle")
     */
    public function soporte(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arAporteContrato = $em->getRepository(RhuAporteContrato::class)->find($id);

        $form = $this->createFormBuilder()
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

        }
        $arAporteSoportes = $em->getRepository(RhuAporteSoporte::class)->listaSoporte($id);
        return $this->render('recursohumano/movimiento/seguridadsocial/aporte/soporte.html.twig', [
            'arAporteContrato' => $arAporteContrato,
            'arAporteSoportes' => $arAporteSoportes,
            'form' => $form->createView()
        ]);
    }

    /** @var $arAporte RhuAporte */
    private function generarPlano($arAporte)
    {
        ob_clean();
        $em = $this->getDoctrine()->getManager();
        $arrConfiguracionGeneral = $em->getRepository(GenConfiguracion::class)->planoAporte();
        $arrConfiguracionRecursoHumano = $em->getRepository(RhuConfiguracion::class)->planoAporte();
        /** @var $arEntidadRiesgos RhuEntidad */
        $arEntidadRiesgos = $em->getRepository(RhuEntidad::class)->find($arrConfiguracionRecursoHumano['codigoEntidadRiesgosProfesionalesFk']);

        $strRutaArchivo = $arrConfiguracionGeneral['rutaTemporal'];
        //Datos aportante
        $strNombreArchivo = "pila" . date('YmdHis') . ".txt";

        $ar = fopen($strRutaArchivo . "/" . $strNombreArchivo, "a") or
        die("Problemas en la creacion del archivo plano");

        $codigoSucursal = "";
        $sucursal = "";
        if ($arAporte->getFormaPresentacion() == "S") {
            $codigoSucursal = $arAporte->getSucursalRel()->getCodigo();
            $sucursal = $arAporte->getSucursalRel()->getNombre();
        }
        $periodoPagoDiferenteSalud = $arAporte->getAnio() . '-' . FuncionesController::RellenarNr($arAporte->getMes(), "0", 2, "I");
        $periodoPagoSalud = $arAporte->getAnioSalud() . '-' . FuncionesController::RellenarNr($arAporte->getMesSalud(), "0", 2, "I");

        //1	2	1	2	N	Tipo de registro	Obligatorio. Debe ser 01
        fputs($ar, FuncionesController::RellenarNr("01", " ", 2, "D"));
        //2	1	3	3	N	Modalidad de la Planilla	Obligatorio. Lo genera autómaticamente el Operador de Información.
        fputs($ar, FuncionesController::RellenarNr("1", " ", 1, "D"));
        //3	4	4	7	N	Secuencia	Obligatorio. Verificación de la secuencia ascendente. Para cada aportante inicia en 0001. Lo genera el sistema en el caso en que se estén digitando los datos directamente en la web. El aportante debe reportarlo en el caso de que los datos se suban en archivos planos.
        fputs($ar, FuncionesController::RellenarNr("0001", " ", 4, "D"));
        //4	200	8	207	A	Nombre o razón social del aportante	El registrado en el campo 1 del archivo tipo 1
        fputs($ar, FuncionesController::RellenarNr($arrConfiguracionGeneral['nombre'], " ", 200, "D"));
        //5	2	208	209	A	Tipo documento del aportante	El registrado en el campo 2 del archivo tipo 1
        fputs($ar, FuncionesController::RellenarNr($arrConfiguracionGeneral['codigoIdentificacionFk'], " ", 2, "D"));
        //6	16	210	225	A	Número de identificación del aportante	El registrado en el campo 3 del archivo tipo 1
        fputs($ar, FuncionesController::RellenarNr($arrConfiguracionGeneral['nit'], " ", 16, "D"));
        //7	1	226	226	N	Dígito de verificación aportante	El registrado en el campo 4 del archivo tipo 1
        fputs($ar, FuncionesController::RellenarNr($arrConfiguracionGeneral['digitoVerificacion'], " ", 1, "D"));
        //8	1	227	227	A	Tipo de Planilla	Obligatorio lo suministra el aportante
        fputs($ar, FuncionesController::RellenarNr("E", " ", 1, "D"));
        //9	10	228	237	N	Número de Planilla asociada a esta planilla.	Debe dejarse en blanco cuando el tipo de planilla sea E, A, I, M, S, Y, T o X. En este campo se incluirá el número de la planilla del periodo correspondiente cuando el tipo de planilla sea N ó F. Cuando se utilice la planilla U por parte de la UGPP, en este campo se diligenciará el número del título del depósito judicial.
        fputs($ar, FuncionesController::RellenarNr("", " ", 10, "D"));
        //10	10	238	247	A	Fecha de pago Planilla asociada a esta planilla. (AAAA-MM-DD)	Debe dejarse en blanco cuando el tipo de planilla sea E, A, I, M, S, Y, T, o X. En este campo se incluirá la fecha de pago de la planilla del período correspondiente cuando el tipo de planilla sea N ó F. Cuando se utilice la planilla U, la UGPP diligenciará la fecha en que se constituyó el depósito judicial.
        fputs($ar, FuncionesController::RellenarNr("", " ", 10, "D"));
        //11	1	248	248	A	Forma de presentación	El registrado en el campo 10 del archivo tipo 1.
        fputs($ar, FuncionesController::RellenarNr($arAporte->getFormaPresentacion(), " ", 1, "D"));
        //12	10	249	258	A	Código de la sucursal del Aportante	El registrado en el campo 5 del archivo tipo 1.
        fputs($ar, FuncionesController::RellenarNr($codigoSucursal, " ", 10, "D"));
        //13	40	259	298	A	Nombre de la sucursal	El registrado en el campo 6 del archivo tipo 1.
        fputs($ar, FuncionesController::RellenarNr($sucursal, " ", 40, "D"));
        //14	6	299	304	A	Código de la ARL a la cual el aportante se encuentra afiliado	Lo suministra el aportante
        fputs($ar, FuncionesController::RellenarNr($arEntidadRiesgos->getCodigoInterface(), " ", 6, "D"));
        //15	7	305	311	A	Periodo de pago para los sistemas diferentes al de salud	Obligatorio. Formato año y mes (aaaa-mm). Lo calcula el Operador de Información.
        fputs($ar, FuncionesController::RellenarNr($periodoPagoDiferenteSalud, " ", 7, "D"));
        //16	7	312	318	A	Periodo de pago para el sistema de salud	Obligatorio. Formato año y mes (aaaa-mm). Lo suministra el aportante.
        fputs($ar, FuncionesController::RellenarNr($periodoPagoSalud, " ", 7, "D"));
        //17	10	319	328	N	Número de radicación o de la Planilla Integrada de Liquidación de aportes.	Asignado por el sistema . Debe ser único por operador de información.
        fputs($ar, FuncionesController::RellenarNr("", " ", 10, "D"));
        //18	10	329	338	A	Fecha de pago (aaaa-mm-dd)	Asignado por el sistema a partir de la fecha del día efectivo del pago.
        fputs($ar, FuncionesController::RellenarNr("", " ", 10, "D"));
        //19	5	339	343	N	Número total de empleados	Obligatorio. Se debe validar que sea igual al número de cotizantes únicos incluidos en el detalle del registro tipo 2, exceptuando los que tengan 40 en el campo 5 – Tipo de cotizante.
        fputs($ar, FuncionesController::RellenarNr($arAporte->getCantidadEmpleados(), "0", 5, "I"));
        //20	12	344	355	N	Valor total de la nómina	Obligatorio. Lo suministra el aportante, corresponde a la sumatoria de los IBC para el pago de los aportes de parafiscales de la totalidad de los empleados. Puede ser 0 para independientes
        fputs($ar, FuncionesController::RellenarNr($arAporte->getVrIngresoBaseCotizacion(), "0", 12, "I"));
        //21	2	356	357	N	Tipo de aportante	Obligatorio y debe ser igual al registrado en el campo 30 del archivo tipo 1
        fputs($ar, FuncionesController::RellenarNr("01", " ", 2, "D"));
        //22	2	358	359	N	Código del operador de información	Asignado por el sistema del operador de información.
        fputs($ar, FuncionesController::RellenarNr("88", " ", 2, "D"));
        fputs($ar, "\n");

        $arAporteDetalles = $em->getRepository(RhuAporteDetalle::class)->findBy(array('codigoAporteFk' => $arAporte->getCodigoAportePk()));
        foreach ($arAporteDetalles as $arAporteDetalle) {
            //1	2	1	2	N	Tipo de registro	Obligatorio. Debe ser 02.
            fputs($ar, FuncionesController::RellenarNr($arAporteDetalle->getTipoRegistro(), "0", 2, "I"));
            //2	5	3	7	N	Secuencia	Debe iniciar en 00001 y ser secuencial para el resto de registros. Lo genera el sistema en el caso en que se estén digitando los datos directamente en la web. El aportante debe reportarlo en el caso de que los datos se suban en archivos planos.
            fputs($ar, FuncionesController::RellenarNr($arAporteDetalle->getSecuencia(), "0", 5, "I"));
            //3	2	8	9	A	Tipo documento el cotizante	Obligatorio. Lo suministra el aportante. Los valores validos son:
            fputs($ar, FuncionesController::RellenarNr($arAporteDetalle->getTipoDocumento(), " ", 2, "D"));
            //4	16	10	25	A	Número de identificación del cotizante	Obligatorio. Lo suministra el aportante. El operador de información validará que este campo este compuesto por letras de la A a la Z y los caracteres numéricos del Cero (0) al nueve (9). Sólo es permitido el número de identificación alfanumérico para los siguientes tipos de documentos de identidad: CE.  Cédula de Extranjería PA.  Pasaporte CD.  Carne Diplomático. Para los siguientes tipos de documento deben ser dígitos numéricos: TI.   Tarjeta de Identidad CC. Cédula de ciudadanía  SC.  Salvoconducto de permanencia RC.  Registro Civil
            fputs($ar, FuncionesController::RellenarNr($arAporteDetalle->getEmpleadoRel()->getNumeroIdentificacion(), " ", 16, "D"));
            //5	2	26	27	N	Tipo de cotizante	Obligatorio. Lo suministra el aportante. Los valores validos son:
            fputs($ar, FuncionesController::RellenarNr($arAporteDetalle->getTipoCotizante(), "0", 2, "I"));
            //6	2	28	29	N	Subtipo de cotizante	Obligatorio. Lo suministra el aportante
            fputs($ar, FuncionesController::RellenarNr($arAporteDetalle->getSubtipoCotizante(), "0", 2, "I"));
            //7	1	30	30	A	Extranjero no obligado a cotizar a pensiones 	Puede ser blanco o X Cuando aplique este campo los únicos tipos de documentos válidos son: CE. Cédula de extranjería PA.  Pasaporte CD.  Carné diplomático Lo suministra el aportante.
            fputs($ar, FuncionesController::RellenarNr($arAporteDetalle->getExtranjeroNoObligadoCotizarPension(), " ", 1, "D"));
            //8	1	31	31	A	Colombiano en el exterior	Puede ser blanco o X si aplica.  Este campo es utilizado cuando el tipo de documento es: CC.  Cédula de ciudadanía TI.    Tarjeta de identidad Lo suministra el aportante.
            fputs($ar, FuncionesController::RellenarNr($arAporteDetalle->getColombianoResidenteExterior(), " ", 1, "D"));
            //9	2	32	33	A	Código del Departamento de la ubicación laboral	Lo suministra el aportante. El operador de información deberá validar que este código este definido en la relación de la División Política y Administrativa – DIVIPOLA- expedida por el DANE Cuando marque el campo colombiano en el exterior se dejará  en blanco
            fputs($ar, FuncionesController::RellenarNr($arAporteDetalle->getCodigoDepartamentoUbicacionlaboral(), " ", 2, "D"));
            //10	3	34	36	A	Código del Municipio de la ubicación laboral	Lo suministra el aportante. El operador de información deberá validar que este código este definido en la relación de la División Política y Administrativa – DIVIPOLA- expedida por el DANE Cuando marque el campo colombiano en el exterior se dejará en blanco
            fputs($ar, FuncionesController::RellenarNr($arAporteDetalle->getCodigoMunicipioUbicacionlaboral(), " ", 3, "D"));
            //11	20	37	56	A	Primer apellido	Obligatorio. Lo suministra el aportante
            fputs($ar, FuncionesController::RellenarNr($arAporteDetalle->getPrimerApellido(), " ", 20, "D"));
            //12	30	57	86	A	Segundo apellido	Lo suministra el aportante
            fputs($ar, FuncionesController::RellenarNr($arAporteDetalle->getSegundoApellido(), " ", 30, "D"));
            //13	20	87	106	A	Primer nombre	Obligatorio. Lo suministra el aportante
            fputs($ar, FuncionesController::RellenarNr($arAporteDetalle->getPrimerNombre(), " ", 20, "D"));
            //14	30	107	136	A	Segundo nombre	Lo suministra el aportante
            fputs($ar, FuncionesController::RellenarNr($arAporteDetalle->getSegundoNombre(), " ", 30, "D"));
            //15	1	137	137	A	ING: ingreso	 Puede ser un blanco, R, X o C. Lo suministra el aportante.
            fputs($ar, FuncionesController::RellenarNr($arAporteDetalle->getIngreso(), " ", 1, "D"));
            //16	1	138	138	A	RET: retiro	Puede ser un blanco, P, R, X o C. Lo suministra el aportante.
            fputs($ar, FuncionesController::RellenarNr($arAporteDetalle->getRetiro(), " ", 1, "D"));
            //17	1	139	139	A	TDE: Traslado desde otra EPS ó EOC	Puede ser un blanco o X. Lo suministra el aportante.
            fputs($ar, FuncionesController::RellenarNr($arAporteDetalle->getTrasladoDesdeOtraEps(), " ", 1, "D"));
            //18	1	140	140	A	TAE: Traslado a otra EPS ó EOC	Puede ser un blanco o X. Lo suministra el aportante.
            fputs($ar, FuncionesController::RellenarNr($arAporteDetalle->getTrasladoAOtraEps(), " ", 1, "D"));
            //19	1	141	141	A	TDP: Traslado desde otra Administradora de Pensiones	Puede ser un blanco o X. Lo suministra el aportante.
            fputs($ar, FuncionesController::RellenarNr($arAporteDetalle->getTrasladoDesdeOtraPension(), " ", 1, "D"));
            //20	1	142	142	A	TAP: Traslado a otra  administradora de pensiones	Puede ser un blanco o X. Lo suministra el aportante.
            fputs($ar, FuncionesController::RellenarNr($arAporteDetalle->getTrasladoAOtraPension(), " ", 1, "D"));
            //21	1	143	143	A	VSP: Variación permantente de salario	Puede ser un blanco o X. Lo suministra el aportante.
            fputs($ar, FuncionesController::RellenarNr($arAporteDetalle->getVariacionPermanenteSalario(), " ", 1, "D"));
            //22	1	144	144	A	Correcciones	Puede ser un blanco, A o C. Lo suministra el aportante
            fputs($ar, FuncionesController::RellenarNr($arAporteDetalle->getCorrecciones(), " ", 1, "D"));
            //23	1	145	145	A	VST: Variación transitoria del salario	Puede ser un blanco o X. Lo suministra el aportante
            fputs($ar, FuncionesController::RellenarNr($arAporteDetalle->getVariacionTransitoriaSalario(), " ", 1, "D"));
            //24	1	146	146	A	SLN: suspensión temporal del contrato de trabajo o licencia no remunerada o comisión de servicios	Puede ser un blanco, X o C. Lo suministra el aportante
            fputs($ar, FuncionesController::RellenarNr($arAporteDetalle->getSuspensionTemporalContratoLicenciaServicios(), " ", 1, "D"));
            //25	1	147	147	A	IGE: Incapacidad Temporal por Enfermedad General	Puede ser un blanco o X. Lo suministra el aportante.
            fputs($ar, FuncionesController::RellenarNr($arAporteDetalle->getIncapacidadGeneral(), " ", 1, "D"));
            //26	1	148	148	A	LMA: Licencia de Maternidad  o de Paternidad	Puede ser un blanco o X. Lo suministra el aportante.
            fputs($ar, FuncionesController::RellenarNr($arAporteDetalle->getLicenciaMaternidad(), " ", 1, "D"));
            //27	1	149	149	A	VAC- LR: Vacaciones, Licencia Remunerada 	Puede ser: X:   Vacaciones L:    Licencia remunerada Blanco: Cuando no aplique esta novedad.
            fputs($ar, FuncionesController::RellenarNr($arAporteDetalle->getVacaciones(), " ", 1, "D"));
            //28	1	150	150	A	AVP: Aporte Voluntario	Puede ser un blanco o X. Lo suministra el aportante.
            fputs($ar, FuncionesController::RellenarNr($arAporteDetalle->getAporteVoluntario(), " ", 1, "D"));
            //29	1	151	151	A	VCT: Variación centros de trabajo	Puede ser un blanco o X. Lo suministra el aportante.
            fputs($ar, FuncionesController::RellenarNr($arAporteDetalle->getVariacionCentrosTrabajo(), " ", 1, "D"));
            //30	2	152	153	N	IRL:Dias de  Incapacidad por accidente de trabajo o enfermedad laboral	Puede ser cero o el número de días (entre 01 y 30). Lo suministra el aportante.
            fputs($ar, FuncionesController::RellenarNr($arAporteDetalle->getIncapacidadAccidenteTrabajoEnfermedadProfesional(), "0", 2, "I"));
            //31	6	154	159	A	Código de la Administradora de Fondo de Pensiones a la cual pertenece el afiliado	Es un campo obligatorio y solo se permite blanco, si el tipo de cotizante o el subtipo de cotizante no es obligado a aportar al Sistema General de Pensiones. Se debe utilizar un código válido y este lo suministra el aportante.
            fputs($ar, FuncionesController::RellenarNr($arAporteDetalle->getEntidadPensionRel()->getCodigoInterface(), " ", 6, "D"));
            //32	6	160	165	A	Código de la Administradora de Fondo de Pensiones a la cual se tralada el afiliado	Obligatorio si la novedad es traslado a otra administradora de fondo de pensiones. Lo suministra el aportante.
            fputs($ar, FuncionesController::RellenarNr($arAporteDetalle->getCodigoEntidadPensionTraslada(), " ", 6, "D"));
            //33	6	166	171	A	Código EPS ó EOC a la cual pertenece el afiliado	Es un campo obligatorio. Se debe utilizar un código válido y éste lo suministra el aportante.
            fputs($ar, FuncionesController::RellenarNr($arAporteDetalle->getEntidadSaludRel()->getCodigoInterface(), " ", 6, "D"));
            //34	6	172	177	A	Código EPS ó EOC a la cual se traslada el afiliado	Obligatorio si en el campo 18 del registro tipo 2 se marca X. Lo suministra el aportante.
            fputs($ar, FuncionesController::RellenarNr($arAporteDetalle->getCodigoEntidadSaludTraslada(), " ", 6, "D"));
            //35	6	178	183	A	Código CCF a la que pertenece el afiliado	Obligatorio y solo se permite blanco, si el tipo de cotizante no es obligado a aportar a CCF. Se debe utilizar un código válido y este lo suministra el aportante.
            fputs($ar, FuncionesController::RellenarNr($arAporteDetalle->getEntidadCajaRel()->getCodigoInterface(), " ", 6, "D"));
            //36	2	184	185	N	Número de días cotizados a pensión	Obligatorio y debe permitir valores entre 0 y 30. Solo se permite 0, si el tipo de cotizante o subtipo de cotizante no está obligado a aportar pensiones. Si es menor que 30 debe haber marcado una novedad de ingreso o retiro. Lo suministra el aportante.
            fputs($ar, FuncionesController::RellenarNr($arAporteDetalle->getDiasCotizadosPension(), "0", 2, "I"));
            //37	2	186	187	N	Número de días cotizados a salud	Obligatorio y debe permitir valores entre 0 y 30. Si es menor que 30 debe haber marcado  una  novedad  de ingreso o retiro. Lo suministra el aportante.
            fputs($ar, FuncionesController::RellenarNr($arAporteDetalle->getDiasCotizadosSalud(), "0", 2, "I"));
            //38	2	188	189	N	Número de días cotizados a Riesgos Laborales	Obligatorio y debe permitir valores entre 0 y 30. Solo se permite 0, si el tipo de cotizante no está obligado a aportar al Sistema General de Riesgos Laborales, o si en los campos 25, 26, 27, del registro tipo 2 se ha marcado X o el campo 30 del registro tipo 2 es mayor que 0. Si es menor que 30 debe haber marcado la novedad correspondiente. Lo suministra el aportante.
            fputs($ar, FuncionesController::RellenarNr($arAporteDetalle->getDiasCotizadosRiesgosProfesionales(), "0", 2, "I"));
            //39	2	190	191	N	Número de días cotizados a Caja de Compensación Familiar	Obligatorio y debe permitir valores entre 0 y 30. Solo se permite 0, si el tipo de cotizante no está obligado a aportar a Cajas de Compensación Familiar  Si es menor que 30 debe haber marcado la novedad correspondiente. Lo suministra el aportante.
            fputs($ar, FuncionesController::RellenarNr($arAporteDetalle->getDiasCotizadosCajaCompensacion(), "0", 2, "I"));
            //40	9	192	200	N	Salario básico 	Obligatorio, sin comas ni puntos. No puede ser menor cero. Puede ser menor que 1 smlmv. Lo suministra el aportante Este valor debe ser reportado sin centavos
            fputs($ar, FuncionesController::RellenarNr($arAporteDetalle->getSalarioBasico(), "0", 9, "I"));
            //41	1	201	201	A	Salario Integral	Se debe indicar con una X si el salario es integral o blanco si no lo es. Es responsabilidad del aportante suministrar esta información.
            fputs($ar, FuncionesController::RellenarNr($arAporteDetalle->getSalarioIntegral(), " ", 1, "D"));
            //42	9	202	210	N	IBC Pensión	Obligatorio. Lo suministra el aportante.
            fputs($ar, FuncionesController::RellenarNr($arAporteDetalle->getIbcPension(), "0", 9, "I"));
            //43	9	211	219	N	IBC Salud	Obligatorio. Lo suministra el aportante.
            fputs($ar, FuncionesController::RellenarNr($arAporteDetalle->getIbcSalud(), "0", 9, "I"));
            //44	9	220	228	N	IBC Riesgos Laborales	Obligatorio. Lo suministra el aportante.
            fputs($ar, FuncionesController::RellenarNr($arAporteDetalle->getIbcRiesgosProfesionales(), "0", 9, "I"));
            //45	9	229	237	N	IBC CCF	 Es un campo obligatorio para los tipos de cotizante 1, 2, 18,22, 30, 51 y 55.  Lo suministra el aportante.  Para el caso del tipo de cotizante 31 no es obligatorio cuando la cooperativa o precooperativa de trabajo asociado este exceptuada por el Ministerio del Trabajo.
            fputs($ar, FuncionesController::RellenarNr($arAporteDetalle->getIbcCaja(), "0", 9, "I"));
            //46	7	238	244	N	Tarifa de aportes pensiones	Lo suministra el aportante y la valida el Operador de Información de acuerdo con las tarifas vigentes en el periodo a liquidar
            fputs($ar, FuncionesController::RellenarNr(number_format($arAporteDetalle->getTarifaPension() / 100, 5, '.', ''), "0", 7, "I"));
            //47	9	245	253	N	Cotización obligatoria a Pensiones	Obligatorio. Lo suministra el aportante
            fputs($ar, FuncionesController::RellenarNr($arAporteDetalle->getCotizacionPension(), "0", 9, "I"));
            //48	9	254	262	N	Aporte voluntario del afiliado al Fondo de Pensiones Obligatorias	Lo suministra el aportante. Solo aplica para las Administradoras de Pensiones del Régimen de ahorro individual
            fputs($ar, FuncionesController::RellenarNr($arAporteDetalle->getAporteVoluntarioFondoPensionesObligatorias(), "0", 9, "I"));
            //49	9	263	271	N	Aporte voluntario del aportante al fondo de pensiones obligatoria. 	Lo suministra el aportante. Solo aplica para las Administradoras de Pensiones del Régimen de ahorro individual
            fputs($ar, FuncionesController::RellenarNr($arAporteDetalle->getCotizacionVoluntarioFondoPensionesObligatorias(), "0", 9, "I"));
            //50	9	272	280	N	Total cotización sistema general de pensiones	Lo calcula el sistema. Sumatoria de los campos 47, 48 y 49 del registro tipo 2.
            fputs($ar, FuncionesController::RellenarNr($arAporteDetalle->getTotalCotizacionFondos(), "0", 9, "I"));
            //51	9	281	289	N	Aportes a Fondo de Solidaridad  Pensional- Subcuenta de solidaridad	Lo suministra el aportante cuando aplique
            fputs($ar, FuncionesController::RellenarNr($arAporteDetalle->getAportesFondoSolidaridadPensionalSolidaridad(), "0", 9, "I"));
            //52	9	290	298	N	Aportes a Fondo de Solidad Pensional- Subcuenta de subsistencia	Lo suministra el aportante cuando aplique
            fputs($ar, FuncionesController::RellenarNr($arAporteDetalle->getAportesFondoSolidaridadPensionalSubsistencia(), "0", 9, "I"));
            //53	9	299	307	N	Valor no retenido por aportes voluntarios	Lo suministra el aportante
            fputs($ar, FuncionesController::RellenarNr("", "0", 9, "I"));
            //54	7	308	314	N	Tarifa de aportes de salud	Lo suministra el aportante y la valida el Operador de Información de acuerdo con las tarifas vigentes en el periodo a liquidar
            fputs($ar, FuncionesController::RellenarNr(number_format($arAporteDetalle->getTarifaSalud() / 100, 5, '.', ''), "0", 7, "I"));
            //55	9	315	323	N	Cotización Obligatoria a salud	Obligatorio. Lo suministra el aportante
            fputs($ar, FuncionesController::RellenarNr($arAporteDetalle->getCotizacionSalud(), "0", 9, "I"));
            //56	9	324	332	N	Valor de la UPC adicional	Debe corresponder al valor reportado en el campo 11 del archivo “información de la Base de Datos Única de Afiliados – BDUA con destino a los operadores de información”
            fputs($ar, FuncionesController::RellenarNr($arAporteDetalle->getValorUpcAdicional(), "0", 9, "I"));
            //57	15	333	347	A	N° autorización de la incapacidad por enfermedad general	Debe reportarse en blanco
            fputs($ar, FuncionesController::RellenarNr($arAporteDetalle->getNumeroAutorizacionIncapacidadEnfermedadGeneral(), " ", 15, "D"));
            //58	9	348	356	N	Valor de incapacidad por enfermedad general	Debe reportarse en blanco
            fputs($ar, FuncionesController::RellenarNr($arAporteDetalle->getValorIncapacidadEnfermedadGeneral(), "0", 9, "I"));
            //59	15	357	371	A	N° autorización de la licencia de maternidad o paternidad	Debe reportarse en blanco
            fputs($ar, FuncionesController::RellenarNr($arAporteDetalle->getNumeroAutorizacionLicenciaMaternidadPaternidad(), " ", 15, "D"));
            //60	9	372	380	N	Valor de la licencia de maternidad	Debe reportarse en cero
            fputs($ar, FuncionesController::RellenarNr($arAporteDetalle->getValorIncapacidadLicenciaMaternidadPaternidad(), "0", 9, "I"));
            //61	9	381	389	N	Tarifa de aportes a Riesgos Laborales	Lo suministra el aportante y la valida el Operador de Información de acuerdo con las tarifas vigentes en el periodo a liquidar
            fputs($ar, FuncionesController::RellenarNr(number_format($arAporteDetalle->getTarifaRiesgos() / 100, 7, '.', ''), "0", 9, "I"));
            //62	9	390	398	N	Centro de Trabajo CT	Lo suministra el aportante
            fputs($ar, FuncionesController::RellenarNr($arAporteDetalle->getCentroTrabajoCodigoCt(), "0", 9, "I"));
            //63	9	399	407	N	Cotización obligatoria al Sistema General de Riesgos Laborales	Lo suministra el aportante
            fputs($ar, FuncionesController::RellenarNr($arAporteDetalle->getCotizacionRiesgos(), "0", 9, "I"));
            //64	7	408	414	N	Tarifa de aportes CCF	Lo suministra el aportante y la valida el Operador de Información de acuerdo con las tarifas vigentes en el periodo a liquidar
            fputs($ar, FuncionesController::RellenarNr(number_format($arAporteDetalle->getTarifaCaja() / 100, 5, '.', ''), "0", 7, "I"));
            //65	9	415	423	N	Valor aporte CCF	Lo suministra el aportante
            fputs($ar, FuncionesController::RellenarNr($arAporteDetalle->getCotizacionCaja(), "0", 9, "I"));
            //66	7	424	430	N	Tarifa de aportes SENA	Lo suministra el aportante
            fputs($ar, FuncionesController::RellenarNr(number_format($arAporteDetalle->getTarifaSENA() / 100, 5, '.', ''), "0", 7, "I"));
            //67	9	431	439	N	Valor aportes SENA	Lo suministra el aportante
            fputs($ar, FuncionesController::RellenarNr($arAporteDetalle->getCotizacionSena(), "0", 9, "I"));
            //68	7	440	446	N	Tarifa aportes ICBF	Lo suministra el aportante
            fputs($ar, FuncionesController::RellenarNr(number_format($arAporteDetalle->getTarifaIcbf() / 100, 5, '.', ''), "0", 7, "I"));
            //69	9	447	455	N	Valor aporte ICBF	Lo suministra el aportante
            fputs($ar, FuncionesController::RellenarNr($arAporteDetalle->getCotizacionIcbf(), "0", 9, "I"));
            //70	7	456	462	N	Tarifa aportes ESAP	Lo suministra el aportante
            fputs($ar, FuncionesController::RellenarNr(number_format($arAporteDetalle->getTarifaAportesESAP() / 100, 5, '.', ''), "0", 7, "I"));
            //71	9	463	471	N	Valor aporte ESAP	Lo suministra el aportante
            fputs($ar, FuncionesController::RellenarNr($arAporteDetalle->getValorAportesESAP(), "0", 9, "I"));
            //72	7	472	478	N	Tarifa aportes MEN	Lo suministra el aportante
            fputs($ar, FuncionesController::RellenarNr(number_format($arAporteDetalle->getTarifaAportesMEN() / 100, 5, '.', ''), "0", 7, "I"));
            //73	9	479	487	N	Valor aporte MEN	Lo suministra el aportante
            fputs($ar, FuncionesController::RellenarNr($arAporteDetalle->getValorAportesMEN(), "0", 9, "I"));
            //74	2	488	489	A	Tipo de documento del cotizante principal	Corresponde al tipo de documento del cotizante Principal que corresponde a: CC.  Cédula de ciudadanía CE.  Cédula de extranjería TI.    Tarjeta de identidad PA.  Pasaporte CD.  Carné diplomático SC.  Salvoconducto de permanencia Lo suministra el aportante Solo debe ser reportado cuando se reporte un cotizante 40.
            fputs($ar, FuncionesController::RellenarNr($arAporteDetalle->getTipoDocumentoResponsableUPC(), " ", 2, "D"));
            //75	16	490	505	A	Número de identificación del cotizante principal	Lo suministra el aportante Solo debe ser reportado cuando se reporte un cotizante 40. El operador de información validará que este campo este compuesto por letras de la A a la Z y los caracteres numéricos del Cero (0) al nueve (9). Sólo es permitido el número de identificación alfanumérico para los siguientes tipos de documentos de identidad: CE.  Cédula de Extranjería PA.  Pasaporte CD.  Carne Diplomático   Para los siguientes tipos de documento deben ser dígitos numéricos: TI.   Tarjeta de Identidad CC. Cédula de ciudadanía  SC.  Salvoconducto de permanencia
            fputs($ar, FuncionesController::RellenarNr($arAporteDetalle->getNumeroIdentificacionResponsableUPCAdicional(), " ", 16, "D"));
            //76	1	506	506	A	Cotizante exonerado de pago de aporte salud, SENA e ICBF - Ley 1607 de 2012 	Obligatorio.  Lo suministra el aportante. S = Si  N = No Cuando el valor del campo 43 – IBC Salud sea superior a 10 SMLMV este campo debe ser N Obligatorio.  Lo suministra el aportante. S = Si  N = No   Cuando personas naturales empleen dos o más trabajadores y el valor del campo 43 – IBC Salud sea superior a 10 SMLMV este campo debe ser N
            fputs($ar, FuncionesController::RellenarNr($arAporteDetalle->getCotizanteExoneradoPagoAporteParafiscalesSalud(), " ", 1, "D"));
            //77	6	507	512	A	Código de la Administradora de Riesgos Laborales a la cual pertenece el afiliado	Lo suministra el aportante. Para el caso de cotizantes diferente al cotizante 3- independiente, se debe registrar el valor ingresado en el Campo 14 del registro Tipo 1 del archivo Tipo 2. Se deja en blanco cuando no sea obligatorio para el cotizante estar afiliado a una Administradora de Riesgos Laborales.
            fputs($ar, FuncionesController::RellenarNr($arAporteDetalle->getCodigoAdministradoraRiesgosLaborales(), " ", 6, "D"));
            //78	1	513	513	A	Clase de riesgo en la que se encuentra el afiliado	Lo suministra el aportante. 1. Clase de Riesgo I 2. Clase de Riesgo II 3. Clase de Riesgo III 4. Clase de Riesgo IV  5. Clase de Riesgo V  La clase de riesgo de acuerdo a la actividad económica establecida en el Decreto 1607 de 2002 o la norma que lo sustituya o modifique
            fputs($ar, FuncionesController::RellenarNr($arAporteDetalle->getClaseRiesgoAfiliado(), " ", 1, "D"));
            //79	1	514	514	A	Indicador tarifa especial pensiones 	Lo suministra el aportante y es: Blanco  Tarifa normal 1. Actividades de alto riesgo 2. Senadores 3. CTI 4. Aviadores
            fputs($ar, FuncionesController::RellenarNr($arAporteDetalle->getIndicadorTarifaEspecialPensiones(), " ", 1, "D"));
            //80	10	515	524	A	Fecha de ingreso Formato (AAAA-MM- DD). 	Es obligatorio cuando se reporte la novedad de ingreso. Lo suministra el aportante. Debe reportarse una fecha valida siempre y cuando la novedad se presente en el periodo que se esté liquidando  Cuando no se reporte la novedad el campo se dejará en blanco.
            fputs($ar, FuncionesController::RellenarNr($arAporteDetalle->getFechaIngreso(), " ", 10, "D"));
            //81	10	525	534	A	Fecha de retiro. Formato (AAAA-MM- DD).	Es obligatorio cuando se reporte la novedad de retiro.  Lo suministra el aportante. Debe reportarse una fecha valida siempre y cuando la novedad se presente en el periodo que se esté liquidando  Cuando no se reporte la novedad el campo se dejará en blanco.
            fputs($ar, FuncionesController::RellenarNr($arAporteDetalle->getFechaRetiro(), " ", 10, "D"));
            //82	10	535	544	A	Fecha Inicio  VSP Formato (AAAA-MM- DD).	Es obligatorio cuando se reporte la novedad de VSP.  Lo suministra el aportante Debe reportarse una fecha valida siempre y cuando la novedad se presente en el periodo que se esté liquidando  Cuando no se reporte la novedad el campo se dejará en blanco
            fputs($ar, FuncionesController::RellenarNr($arAporteDetalle->getFechaInicioVsp(), " ", 10, "D"));
            //83	10	545	554	A	Fecha Inicio SLN Formato (AAAA-MM- DD). 	Es obligatorio cuando se reporte la novedad de SLN. Lo suministra el aportante.   Debe reportarse una fecha valida siempre y cuando la novedad se presente en el periodo que se esté liquidando  Cuando no se reporte la novedad el campo se dejará en blanco.
            fputs($ar, FuncionesController::RellenarNr($arAporteDetalle->getFechaInicioSln(), " ", 10, "D"));
            //84	10	555	564	A	Fecha fin SLN Formato (AAAA-MM- DD). 	Es obligatorio cuando se reporte la novedad de SLN. Lo suministra el aportante.  Debe reportarse una fecha valida siempre y cuando la novedad se presente en el periodo que se esté liquidando Cuando no se reporte la novedad el campo se dejará en blanco.
            fputs($ar, FuncionesController::RellenarNr($arAporteDetalle->getFechaFinSln(), " ", 10, "D"));
            //85	10	565	574	A	Fecha inicio  IGE Formato (AAAA-MM- DD).	Es obligatorio cuando se reporte la novedad de IGE.  Lo suministra el aportante. Debe reportarse una fecha valida siempre y cuando la novedad se presente en el periodo que se esté liquidando  Cuando no se reporte la novedad el campo se dejará en blanco
            fputs($ar, FuncionesController::RellenarNr($arAporteDetalle->getFechaInicioIge(), " ", 10, "D"));
            //86	10	575	584	A	Fecha fin IGE. Formato (AAAA-MM- DD) 	Es obligatorio cuando se reporte la novedad de IGE. Lo suministra el aportante.  Debe reportarse una fecha valida siempre y cuando la novedad se presente en el periodo que se esté liquidando  Cuando no se reporte la novedad el campo se dejará en blanco
            fputs($ar, FuncionesController::RellenarNr($arAporteDetalle->getFechaFinIge(), " ", 10, "D"));
            //87	10	585	594	A	Fecha inicio LMA Formato (AAAA-MM- DD). 	Es obligatorio cuando se reporte la novedad de LMA.  Lo suministra el aportante. Debe reportarse una fecha valida siempre y cuando la novedad se presente en el periodo que se esté liquidando  Cuando no se reporte la novedad el campo se dejará en blanco.
            fputs($ar, FuncionesController::RellenarNr($arAporteDetalle->getFechaInicioLma(), " ", 10, "D"));
            //88	10	595	604	A	Fecha fin LMA Formato (AAAA-MM- DD) 	Es obligatorio cuando se reporte la novedad de LMA.  Lo suministra el aportante. Debe reportarse una fecha valida siempre y cuando la novedad se presente en el periodo que se esté liquidando  Cuando no se reporte la novedad el campo se dejará en blanco.
            fputs($ar, FuncionesController::RellenarNr($arAporteDetalle->getFechaFinLma(), " ", 10, "D"));
            //89	10	605	614	A	Fecha inicio VAC - LR Formato (AAAA-MM- DD). 	Es obligatorio cuando se reporte la novedad VAC - LR. Debe reportarse una fecha valida siempre y cuando la novedad se presente en el periodo que se esté liquidando  Cuando no se reporte la novedad el campo se dejará en blanco.
            fputs($ar, FuncionesController::RellenarNr($arAporteDetalle->getFechaInicioVacLr(), " ", 10, "D"));
            //90	10	615	624	A	Fecha fin VAC - LR Formato (AAAA-MM- DD). 	Es obligatorio cuando se reporte la novedad VAC - LR. Debe reportarse una fecha valida siempre y cuando la novedad se presente en el periodo que se esté liquidando  Cuando no se reporte la novedad el campo se dejará en blanco.
            fputs($ar, FuncionesController::RellenarNr($arAporteDetalle->getFechaFinVacLr(), " ", 10, "D"));
            //91	10	625	634	A	Fecha inicio VCT Formato (AAAA-MM- DD). 	Es obligatorio cuando se reporte la novedad VCT.  Lo suministra el aportante. Debe reportarse una fecha valida siempre y cuando la novedad se presente en el periodo que se esté liquidando  Cuando no se reporte la novedad el campo se dejará en blanco
            fputs($ar, FuncionesController::RellenarNr($arAporteDetalle->getFechaInicioVct(), " ", 10, "D"));
            //92	10	635	644	A	Fecha fin  VCT Formato (AAAA-MM- DD). 	Cuando no se reporte la novedad el campo se dejará en blanco.
            fputs($ar, FuncionesController::RellenarNr($arAporteDetalle->getFechaFinVct(), " ", 10, "D"));
            //93	10	645	654	A	Fecha inicio IRL Formato (AAAA-MM- DD). 	Es obligatorio cuando se reporte la novedad IRL. Debe reportarse una fecha valida siempre y cuando la novedad se presente en el periodo que se esté liquidando  Cuando no se reporte la novedad el campo se dejará en blanco.
            fputs($ar, FuncionesController::RellenarNr($arAporteDetalle->getFechaInicioIrl(), " ", 10, "D"));
            //94	10	655	664	A	Fecha fin  IRL Formato (AAAA-MM- DD). 	Es obligatorio cuando se reporte la novedad IRL. Debe reportarse una fecha valida siempre y cuando la novedad se presente en el periodo que se esté liquidando  Cuando no se reporte la novedad el campo se dejará en blanco
            fputs($ar, FuncionesController::RellenarNr($arAporteDetalle->getFechaFinIrl(), " ", 10, "D"));
            //95	9	665	673	N	IBC otros parafiscales diferentes a CCF	Es un campo obligatorio para los tipos de cotizante 1, 18, 20, 22, 30, 31, y 55.   Lo suministra el aportante.
            fputs($ar, FuncionesController::RellenarNr($arAporteDetalle->getIbcOtrosParafiscalesDiferentesCcf(), "0", 9, "I"));
            //96	3	674	676	N	Número de horas laboradas 	Es un campo obligatorio para los tipos de cotizante 1, 2, 18, 22, 30, 51 y 55.  Lo suministra el aportante.  Para el caso del tipo de cotizante 31 no es obligatorio cuando la cooperativa o precooperativa de trabajo asociado este exceptuada por el Ministerio del Trabajo.
            fputs($ar, FuncionesController::RellenarNr($arAporteDetalle->getNumeroHorasLaboradas(), "0", 3, "I"));
            //97	10	???	???	A	Fecha
            fputs($ar, FuncionesController::RellenarNr("", " ", 10, "D"));
            fputs($ar, "\n");

        }
        fclose($ar);
        $strArchivo = $strRutaArchivo . "/" . $strNombreArchivo;
        header('Content-Description: File Transfer');
        header('Content-Type: text/csv; charset=ISO-8859-15');
        header('Content-Disposition: attachment; filename=' . basename($strArchivo));
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($strArchivo));
        readfile($strArchivo);
        //$em->flush();
        exit;
    }

    private function periodoSalud($anio = null, $mes)
    {
        if ($mes == '12') {
            $mes = '1';
            $anio += 1;
        } else {
            $mes += 1;
        }
        $arrPeriodo = array('anio' => $anio, 'mes' => $mes);
        return $arrPeriodo;
    }

    public function getFiltros($form)
    {
        $filtro = [
            'anio' => $form->get('anio')->getData(),
            'mes' => $form->get('mes')->getData(),
        ];

        return $filtro;
    }

    public function getFiltrosDetalle($form)
    {
        $filtro = [
            'identificacion' => $form->get('identificacion')->getData(),
        ];

        return $filtro;

    }

}