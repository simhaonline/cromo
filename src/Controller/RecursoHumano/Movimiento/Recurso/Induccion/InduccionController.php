<?php

namespace App\Controller\RecursoHumano\Movimiento\Recurso\Induccion;

use App\Controller\MaestroController;
use App\Entity\RecursoHumano\RhuContrato;
use App\Entity\RecursoHumano\RhuEmpleado;
use App\Entity\RecursoHumano\RhuInduccion;
use App\Entity\RecursoHumano\RhuPermiso;
use App\Form\Type\RecursoHumano\InduccionType;
use App\Form\Type\RecursoHumano\PermisoType;
use App\General\General;
use App\Utilidades\Estandares;
use App\Utilidades\Mensajes;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class InduccionController extends MaestroController
{
    public $tipo = "Movimiento";
    public $modelo = "RhuInduccion";


    protected $clase = RhuInduccion::class;
    protected $claseFormulario = PermisoType::class;
    protected $claseNombre = "RhuInduccion";
    protected $modulo = "RecursoHumano";
    protected $funcion = "Movimiento";
    protected $grupo = "Recurso";
    protected $nombre = "Induccion";

    /**
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("recursohumano/movimiento/recurso/induccion/lista", name="recursohumano_movimiento_recurso_induccion_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('codigoInduccionPk', TextType::class, array('required' => false))
            ->add('codigoEmpleadoFk', TextType::class, ['required' => false])
            ->add('estadoAutorizado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
            ->add('estadoAprobado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
            ->add('estadoAnulado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
            ->add('fechaDesde', DateType::class, ['label' => 'Fecha desde: ', 'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd'])
            ->add('fechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd'])
            ->add('limiteRegistros', TextType::class, array('required' => false, 'data' => 100))
            ->add('btnEliminar', SubmitType::class, ['label' => 'Eliminar', 'attr' => ['class' => 'btn btn-sm btn-danger']])
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
                General::get()->setExportar($em->getRepository(RhuInduccion::class)->lista($raw), "Inducciones");
            }
        }
        $arInducciones = $paginator->paginate($em->getRepository(RhuInduccion::class)->lista($raw), $request->query->getInt('page', 1), 30);
        return $this->render('recursohumano/movimiento/recurso/induccion/lista.html.twig', [
            'arInducciones' => $arInducciones,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("recursohumano/movimiento/recurso/induccion/nuevo/{id}", name="recursohumano_movimiento_recurso_induccion_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arInduccion = new RhuInduccion();
        if ($id != 0) {
            $arInduccion = $em->getRepository($this->clase)->find($id);
        } else {
            $arInduccion->setFechaDesde(new \DateTime('now'));
            $arInduccion->setFechaHasta(new \DateTime('now'));
        }
        $form = $this->createForm(InduccionType::class, $arInduccion);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->get('guardar')->isClicked()) {
                $arEmpleado = $em->getRepository(RhuEmpleado::class)->find($arInduccion->getCodigoEmpleadoFk());
                if ($arEmpleado) {
                    $arContrato = null;
                    if ($arEmpleado->getCodigoContratoFk()) {
                        $arContrato = $em->getRepository(RhuContrato::class)->find($arEmpleado->getCodigoContratoFk());
                        if ($arContrato != null) {
                            if ($id == 0) {
                                $arInduccion->setFecha(new \DateTime('now'));
                            }
                            $arInduccion->setFecha(new \DateTime('now'));
                            $arInduccion->setEmpleadoRel($arEmpleado);
                            $em->persist($arInduccion);
                            $em->flush();
                            return $this->redirect($this->generateUrl('recursohumano_movimiento_recurso_induccion_detalle', ['id' => $arInduccion->getCodigoInduccionPk()]));
                        } else {
                            Mensajes::error('El empleado no tiene contratos en el sistema');
                        }
                    } else {
                        Mensajes::error('El emplado no tiene contrato');
                    }
                }
            }
        }
        return $this->render('recursohumano/movimiento/recurso/induccion/nuevo.html.twig', [
            'form' => $form->createView(),
            'arInduccion' => $arInduccion
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("recursohumano/movimiento/recurso/induccion/detalle/{id}", name="recursohumano_movimiento_recurso_induccion_detalle")
     */
    public function detalle(Request $request, $id, PaginatorInterface $paginator)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $arRegistro = $em->getRepository($this->clase)->find($id);
        $form = Estandares::botonera($arRegistro->getEstadoAutorizado(), $arRegistro->getEstadoAprobado(), $arRegistro->getEstadoAnulado());
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
        }
        return $this->render('recursohumano/movimiento/recurso/induccion/detalle.html.twig', [
            'arRegistro' => $arRegistro,
            'form' => $form->createView()
        ]);
    }

    public function getFiltros($form)
    {
        $filtro = [
            'codigoInduccion' => $form->get('codigoInduccionPk')->getData(),
            'codigoEmpleado' => $form->get('codigoEmpleadoFk')->getData(),
            'fechaDesde' => $form->get('fechaDesde')->getData() ? $form->get('fechaDesde')->getData()->format('Y-m-d') : null,
            'fechaHasta' => $form->get('fechaHasta')->getData() ? $form->get('fechaHasta')->getData()->format('Y-m-d') : null,
            'estadoAutorizado' => $form->get('estadoAutorizado')->getData(),
            'estadoAprobado' => $form->get('estadoAprobado')->getData(),
            'estadoAnulado' => $form->get('estadoAnulado')->getData(),
        ];

        return $filtro;

    }
}

