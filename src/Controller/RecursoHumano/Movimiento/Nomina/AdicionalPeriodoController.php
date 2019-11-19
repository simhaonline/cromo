<?php


namespace App\Controller\RecursoHumano\Movimiento\Nomina;


use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Entity\RecursoHumano\RhuAdicional;
use App\Entity\RecursoHumano\RhuAdicionalPeriodo;
use App\Entity\RecursoHumano\RhuContrato;
use App\Entity\RecursoHumano\RhuEmpleado;
use App\Form\Type\RecursoHumano\AdicionalPeriodoType;
use App\Form\Type\RecursoHumano\AdicionalType;
use App\General\General;
use App\Utilidades\Mensajes;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class AdicionalPeriodoController extends AbstractController
{
    protected $clase = RhuAdicionalPeriodo::class;
    protected $claseFormulario = AdicionalPeriodoType::class;
    protected $claseNombre = "RhuAdicionalPeriodo";
    protected $modulo = "RecursoHumano";
    protected $funcion = "movimiento";
    protected $grupo = "Nomina";
    protected $nombre = "AdicionalPeriodo";

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("recursohumano/movimiento/nomina/adicionalperiodo/lista", name="recursohumano_movimiento_nomina_adicionalperiodo_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('estadoCerrado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
            ->add('limiteRegistros', TextType::class, array('required' => false, 'data' => 100))
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnExcel', SubmitType::class, ['label' => 'Excel', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnEliminar', SubmitType::class, ['label' => 'Eliminar', 'attr' => ['class' => 'btn btn-sm btn-danger']])
            ->getForm();
        $form->handleRequest($request);
        $raw = [
            'limiteRegistros' => $form->get('limiteRegistros')->getData()
        ];
        if ($form->isSubmitted()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $raw['filtros'] = $this->getFiltros($form);
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository(RhuAdicionalPeriodo::class)->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('recursohumano_movimiento_nomina_adicionalperiodo_lista'));
            }
            if ($form->get('btnExcel')->isClicked()) {
                $raw['filtros'] = $this->getFiltros($form);
                General::get()->setExportar($em->getRepository(RhuAdicionalPeriodo::class)->lista($raw)->getQuery()->getResult(), "Adicional periodo");
            }

        }
        $arAdicionalPeriodos = $paginator->paginate($em->getRepository(RhuAdicionalPeriodo::class)->lista($raw), $request->query->getInt('page', 1), 30);
        return $this->render('recursohumano/movimiento/nomina/adicionalPeriodo/lista.html.twig', [
            'arAdicionalPeriodos' => $arAdicionalPeriodos,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("recursohumano/movimiento/nomina/adicionalperiodo/nuevo/{id}", name="recursohumano_movimiento_nomina_adicionalperiodo_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arAdicionalPeriodo = new RhuAdicionalPeriodo();
        if ($id != 0) {
            $arAdicionalPeriodo = $em->getRepository(RhuAdicionalPeriodo::class)->find($id);
        } else {
            $arAdicionalPeriodo->setFecha(new \DateTime('now'));
        }
        $form = $this->createForm(AdicionalPeriodoType::class, $arAdicionalPeriodo);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arAdicionalPeriodo = $form->getData();
                $em->persist($arAdicionalPeriodo);
                $em->flush();
                return $this->redirect($this->generateUrl('recursohumano_movimiento_nomina_adicionalperiodo_detalle', array('id' => $arAdicionalPeriodo->getCodigoAdicionalPeriodoPk())));
            }
        }
        return $this->render('recursohumano/movimiento/nomina/adicionalPeriodo/nuevo.html.twig', [
            'arAdicionalPeriodo' => $arAdicionalPeriodo,
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("recursohumano/movimiento/nomina/adicionalperiodo/detalle/{id}", name="recursohumano_movimiento_nomina_adicionalperiodo_detalle")
     */
    public function detalle(Request $request, $id, PaginatorInterface $paginator)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $arAdicionalPeriodo = $em->getRepository(RhuAdicionalPeriodo::class)->find($id);

        $arrBtnEliminar = ['attr' => ['class' => 'btn btn-sm btn-danger'], 'label' => 'Eliminar'];
        $form = $this->createFormBuilder()
            ->add('txtCodigoEmpleado', TextType::class, ['required' => false])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnEliminar', SubmitType::class, $arrBtnEliminar)
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if ($form->get('btnFiltrar')->isClicked()) {
                $session->set('filtroRhuEmpleadoCodigo', $form->get('txtCodigoEmpleado')->getData());
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $em->getRepository(RhuAdicional::class)->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('recursohumano_movimiento_nomina_adicionalperiodo_detalle', ['id' => $id]));
            }

        }
        $arAdicionalPeriodo = $em->getRepository(RhuAdicionalPeriodo::class)->find($id);
        $arAdicionales = $paginator->paginate($em->getRepository(RhuAdicional::class)->adicionalesPorPeriodo($id), $request->query->getInt('page', 1), 30);
        return $this->render('recursohumano/movimiento/nomina/adicionalPeriodo/detalle.html.twig', [
            'arAdicionalPeriodo' => $arAdicionalPeriodo,
            'arAdicionales' => $arAdicionales,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("recursohumano/movimiento/nomina/adicionalperiodo/detalle/nuevo/{codigoAdicional}/{codigoAdicionalPeriodo}", name="recursohumano_movimiento_nomina_adicionalperiodo_detalle_nuevo")
     */
    public function detalleNuevo(Request $request, $codigoAdicional, $codigoAdicionalPeriodo)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var $arAdicionalPerido RhuAdicionalPeriodo */
        $arAdicionalPerido = $em->getRepository(RhuAdicionalPeriodo::class)->find($codigoAdicionalPeriodo);
        $arAdicional = new RhuAdicional();
        if ($codigoAdicional != 0) {
            $arAdicional = $em->getRepository(RhuAdicional::class)->find($codigoAdicional);
        }
        $arAdicional->setAplicaNomina(true);
        $form = $this->createForm(AdicionalType::class, $arAdicional);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arEmpleado = $em->getRepository(RhuEmpleado::class)->find($arAdicional->getCodigoEmpleadoFk());
                $arContrato = null;
                if ($arEmpleado->getCodigoContratoFk()) {
                    $arContrato = $arEmpleado->getCodigoContratoFk();
                } elseif ($arEmpleado->getCodigoContratoUltimoFk()) {
                    $arContrato = $em->getReference(RhuContrato::class, $arEmpleado->getCodigoContratoUltimoFk());
                }
                if ($arContrato) {
                    $arAdicional->setFecha($arAdicionalPerido->getFecha());
                    $arAdicional->setEmpleadoRel($arEmpleado);
                    $arAdicional->setContratoRel($arContrato);
                    $arAdicional->setAdicionalPeriodoRel($arAdicionalPerido);
                    $em->persist($arAdicional);
                    $em->flush();

                    return $this->redirect($this->generateUrl('recursohumano_movimiento_nomina_adicionalperiodo_detalle', ['id' => $arAdicionalPerido->getCodigoAdicionalPeriodoPk()]));
                } else {
                    Mensajes::error('El empleado no tiene un contrato activo en el sistema');
                }
            }
        }
        return $this->render('recursohumano/movimiento/nomina/adicional/nuevo.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function getFiltros($form)
    {
        $filtro = [
            'estadoCerrado' => $form->get('estadoCerrado')->getData(),
        ];

        return $filtro;
    }
}