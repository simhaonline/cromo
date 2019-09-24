<?php

namespace App\Controller\RecursoHumano\Movimiento\Recurso\Poligrafia;

use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Entity\RecursoHumano\RhuContrato;
use App\Entity\RecursoHumano\RhuCredito;
use App\Entity\RecursoHumano\RhuCreditoPago;
use App\Entity\RecursoHumano\RhuCreditoTipo;
use App\Entity\RecursoHumano\RhuEmpleado;
use App\Entity\RecursoHumano\RhuPermiso;
use App\Entity\RecursoHumano\RhuPermisoTipo;
use App\Entity\RecursoHumano\RhuPoligrafia;
use App\Entity\RecursoHumano\RhuPoligrafiaTipo;
use App\Entity\RecursoHumano\RhuVisita;
use App\Entity\RecursoHumano\RhuVisitaTipo;
use App\Form\Type\RecursoHumano\CreditoPagoType;
use App\Form\Type\RecursoHumano\CreditoType;
use App\Form\Type\RecursoHumano\PermisoType;
use App\Form\Type\RecursoHumano\PoligrafiaType;
use App\Form\Type\RecursoHumano\VisitaType;
use App\Formato\RecursoHumano\Credito;
use App\General\General;
use App\Utilidades\Estandares;
use App\Utilidades\Mensajes;
use Doctrine\ORM\EntityRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class PoligrafiaController extends AbstractController
{
    protected $clase = RhuPoligrafia::class;
    protected $claseFormulario = PermisoType::class;
    protected $claseNombre = "RhuPoligrafia";
    protected $modulo = "RecursoHumano";
    protected $funcion = "movimiento";
    protected $grupo = "Recurso";
    protected $nombre = "Poligrafia";

    /**
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("recursohumano/movimiento/recurso/poligrafia/lista", name="recursohumano_movimiento_recurso_poligrafia_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('codigoPoligrafiaTipoFk', EntityType::class, [
                'class' => RhuPoligrafiaTipo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('pt')
                        ->orderBy('pt.codigoPoligrafiaTipoPk', 'ASC');
                },
                'required' => false,
                'choice_label' => 'nombre',
                'placeholder' => 'TODOS',
                'attr' => ['class' => 'form-control to-select-2']
            ])
            ->add('codigoPoligrafiaPk', TextType::class, array('required' => false))
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
                General::get()->setExportar($em->getRepository(RhuPoligrafia::class)->lista($raw), "Poligrias");
            }
        }
        $arPoligrafias = $paginator->paginate($em->getRepository(RhuPoligrafia::class)->lista($raw), $request->query->getInt('page', 1), 30);
        return $this->render('recursohumano/movimiento/recurso/poligrafia/lista.html.twig', [
            'arPoligrafias' => $arPoligrafias,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("recursohumano/movimiento/recurso/poligrafia/nuevo/{id}", name="recursohumano_movimiento_recurso_poligrafia_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arPoligrafia = new RhuPoligrafia();
        if ($id != 0) {
            $arPoligrafia = $em->getRepository($this->clase)->find($id);
        } else {
            $arPoligrafia->setFecha(new \DateTime('now'));
        }
        $form = $this->createForm(PoligrafiaType::class, $arPoligrafia);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->get('guardar')->isClicked()) {
                $arEmpleado = $em->getRepository(RhuEmpleado::class)->find($arPoligrafia->getCodigoEmpleadoFk());
                if ($arEmpleado) {
                    $arContrato = null;
                    if ($arEmpleado->getCodigoContratoFk()) {
                        $arContrato = $em->getRepository(RhuContrato::class)->find($arEmpleado->getCodigoContratoFk());
                        if ($arContrato != null) {
                            if ($id == 0) {
                                $arPoligrafia->setFecha(new \DateTime('now'));
                            }
                            $arPoligrafia->setFecha(new \DateTime('now'));
                            $arPoligrafia->setEmpleadoRel($arEmpleado);
                            $arPoligrafia->setNumeroIdentificacion($arEmpleado->getNumeroIdentificacion());
                            $arPoligrafia->setNombreCorto($arEmpleado->getNombreCorto());
                            $em->persist($arPoligrafia);
                            $em->flush();
                            return $this->redirect($this->generateUrl('recursohumano_movimiento_recurso_poligrafia_detalle', ['id' => $arPoligrafia->getCodigoPoligrafiaPk()]));
                        } else {
                            Mensajes::error('El empleado no tiene contratos en el sistema');
                        }
                    } else {
                        Mensajes::error('El emplado no tiene contrato');
                    }
                }
            }
        }
        return $this->render('recursohumano/movimiento/recurso/poligrafia/nuevo.html.twig', [
            'form' => $form->createView(),
            'arPoligrafia' => $arPoligrafia
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("recursohumano/movimiento/recurso/poligrafia/detalle/{id}", name="recursohumano_movimiento_recurso_poligrafia_detalle")
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
        return $this->render('recursohumano/movimiento/recurso/poligrafia/detalle.html.twig', [
            'arRegistro' => $arRegistro,
            'form' => $form->createView()
        ]);
    }

    public function getFiltros($form)
    {
        $filtro = [
            'codigoPoligrafia' => $form->get('codigoPoligrafiaPk')->getData(),
            'codigoEmpleado' => $form->get('codigoEmpleadoFk')->getData(),
            'fechaDesde' => $form->get('fechaDesde')->getData() ? $form->get('fechaDesde')->getData()->format('Y-m-d') : null,
            'fechaHasta' => $form->get('fechaHasta')->getData() ? $form->get('fechaHasta')->getData()->format('Y-m-d') : null,
            'estadoAutorizado' => $form->get('estadoAutorizado')->getData(),
            'estadoAprobado' => $form->get('estadoAprobado')->getData(),
            'estadoAnulado' => $form->get('estadoAnulado')->getData(),
        ];

        $arPoligrafiaTipo = $form->get('codigoPoligrafiaTipoFk')->getData();

        if (is_object($arPoligrafiaTipo)) {
            $filtro['poligrafiaTipo'] = $arPoligrafiaTipo->getCodigoPoligrafiaTipoPk();
        } else {
            $filtro['poligrafiaTipo'] = $arPoligrafiaTipo;
        }

        return $filtro;

    }
}

